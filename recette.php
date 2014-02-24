<!-- 
© Copyright : Aboo / www.aboo.fr : Frédéric MEYROU : tous droits réservés
-->
<?php
// Dépendances
	require_once('lib/fonctions.php');
    include_once('lib/database.php');
    include_once('lib/calcul_bilan.php');  
    	
// Vérification de l'Authent
    session_start();
    require('lib/authent.php');
    if( !Authent::islogged()){
        // Non authentifié on repart sur la HP
        header('Location:index.php');
    }

// Mode Debug
	$debug = false;

// Sécurisation POST & GET
    foreach ($_GET as $key => $value) {
        $sGET[$key]=htmlentities($value, ENT_QUOTES);
    }
    foreach ($_POST as $key => $value) {
        $sPOST[$key]=htmlentities($value, ENT_QUOTES);
    }
        	
// Récupération des variables de session d'Authent
    $user_id = $_SESSION['authent']['id']; 
    $nom = $_SESSION['authent']['nom'];
    $prenom = $_SESSION['authent']['prenom'];
    $nom = $_SESSION['authent']['nom'];

// Récupération des variables de session exercice
    if(isset($_SESSION['exercice'])) {
        $exercice_id = $_SESSION['exercice']['id'];
        $exercice_annee = $_SESSION['exercice']['annee'];
        $exercice_mois = $_SESSION['exercice']['mois'];
        $exercice_treso = $_SESSION['exercice']['treso'];	
    } else { // On a pas de session on retourne vers la gestion d'exercice
    	header("Location: exercice.php");    	
    }

// Récupération des variables de session abodep
    $abodep_mois = null;
    if(!empty($_SESSION['abodep']['mois'])) {
        $abodep_mois = $_SESSION['abodep']['mois'];
    } else { // On a pas de session avec le mois on retourne d'ou on vient
    	header("Location: journal.php");
    }
	
// Initialisation de la base
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Lecture du POST (Choix du mois)
    if (isset($sPOST['mois']) ) { // J'ai un POST
            $mois_choisi = $sPOST['mois'];
    } else { // Je n'ai pas de POST
            $mois_choisi = null;
    }
	
// Selection du mois par défaut
	// On va lire le mois en cours en BDD si il exite
	if ($mois_choisi == null) {
	    $sql = "SELECT mois_encours FROM user WHERE id = ?";
	    $q = $pdo->prepare($sql);
	    $q->execute(array($user_id));
	    $data = $q->fetch(PDO::FETCH_ASSOC);	
	    $count = $q->rowCount($sql);
		if ($count==1) { // On a bien un mois en cours
			$mois_choisi = $data['mois_encours'];
		}
	}		
	if ($exercice_mois != null && ($mois_choisi == null && $abodep_mois == null)) {
		// On a pas de POST ni de SESSION mais on a un mois de debut d'exercice
		$mois_choisi = $exercice_mois;
	} elseif ($mois_choisi == null && $abodep_mois != null) {
		// On a dejà une session mais pas de POST
		$mois_choisi = $abodep_mois;
	} elseif ($mois_choisi == null) {
		// On a vraiment rien on prend le mois courant
		$mois_choisi = date('n');
	}
	$_SESSION['abodep']['mois'] = $mois_choisi;
    $abodep_mois = $mois_choisi;
	// On met à jour la BDD pour les champs encours
    $sql = "UPDATE user SET mois_encours=? WHERE id = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($mois_choisi, $user_id));
    	
	// Calcul du mois relatif	
	$mois_choisi_relatif = MoisRelatif($mois_choisi,$exercice_mois);
	    
// Lecture du POST Formulaire
	$type = null;
    $montant = null;
	$commentaire = null;
	$periodicitee = null;
	$paiement = null;
	$client_id = null;
	$paiementError = null;
    $paiement_mois_Error = null;
	$montantError = null;	
	$periodiciteeError = null;
	$enregistre_paiement = false;
	$affiche_paiement_etale = false;
    $black = false;
		
	if (isset($_POST['montant']) ) { // J'ai un POST
        $type = $sPOST['type'];        
        $montant = $sPOST['montant'];
        $periodicitee = $sPOST['periodicitee'];
		$commentaire = $sPOST['commentaire'];
		$paiement = isset($_POST['paiement']) ? $sPOST['paiement'] : 0;
		$client_id = isset($_POST['client']) ? $sPOST['client'] : null;
		$verif_paiement_etale = isset($_POST['etale']) ? true : false;
		$black = isset($_POST['black']) ? true : false;
		
		// Validation du formulaire
		$valid = true;

		if ($montant == "0") {
		    $montant = 0;
		}
		if ($montant < 0) {
            $montantError= "Veuillez entrer un montant positif ou nul.";
            $valid = false;
        }
        if (!is_numeric($montant)) {
            $montantError= "Veuillez entrer un montant.";
            $valid = false;
        }				
		
		// Verification de la periodicitee
		if (($periodicitee == 12) && ( $mois_choisi_relatif + $periodicitee - 1 ) > 12) { // Periodicitee annuelle ou lissée
			// Modification de la valeure de la periodicitee si il est besoin de lisser sur un nombre de mois inférieur à 12
			$periodicitee = 12 - $mois_choisi_relatif + 1;
		}
		if (($mois_choisi_relatif + $periodicitee - 1 ) > 12) { // La périodicitee est superieure au nombre de mois restant retourne une erreur
			$periodiciteeError= "La périodicité de l'abonnement est trop grande pour l'exercice en cours.";		
			$valid = false;			
		}

		// Vérification de la coherence type/periodicitee
		if (NumToTypeRecette($type) != "Abonnement" && $periodicitee != 1 ) {
			$periodiciteeError = "Une " . NumToTypeRecette($type) . " ne peut pas faire l'objet d'une periodicitée autre que Ponctuel.";
			$valid = false;				
		}

        // Type de déclaration (black)              
        if ($black) {
            $declaration = 0;
        } else {
            $declaration = 1;
        } 
        						
		// Test du selecteur de paiement
		if ($valid && $paiement == 2 && (NumToTypeRecette($type) == "Abonnement" && $periodicitee != 1 )) { // Paiement étalé
			$affiche_paiement_etale = true;
			$valid = false;	
		} elseif ($paiement == 2 && ( NumToTypeRecette($type) != "Abonnement" || $periodicitee == 1)) {
			$affiche_paiement_etale = false;
			$paiementError = "Seul un abonnement peut faire l'objet d'un etalement des paiements";
			$valid = false;	
		}
		if ($paiement == 0 ) { // Enregistrement du statut du paiement pour la BDD table recette
			$paye = 1;
		} else {
			$paye = 0;
		}
				
        
		// Vérification des paiements etalés
		if ($affiche_paiement_etale && $verif_paiement_etale) { // On a un POST avec les paiements étalés et sans pb de validation
			$total = 0;
			for ($m = 1; $m <= 12; $m++) {
				$paiement_mois_{$m} = isset($_POST['paiement_mois_' . $m]) ? $_POST['paiement_mois_' . $m] : 0;
				$total = $total + $paiement_mois_{$m};
			} // endfor
			if ($total == $montant) {
				$valid = true;
				$enregistre_paiement = true;
			} else {
				$paiement_mois_Error = "Le total de vos paiements étalés est différent du montant de l'recette!";
				$valid = false;
			}			
		}
		
		// Calcul de la ventilation
		$ventillation = Ventillation($mois_choisi_relatif, $montant, $periodicitee);
		
		// On remet la périodicitée du POST pour enregistrement
		$periodicitee = $sPOST['periodicitee'];

		// insert data
		if ($valid) {
		    // Enregistre la recette
			$sql = "INSERT INTO recette (user_id,exercice_id,client_id,type,montant,paye,declaration,mois,periodicitee,commentaire,mois_1,mois_2,mois_3,mois_4,mois_5,mois_6,mois_7,mois_8,mois_9,mois_10,mois_11,mois_12) values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
			$q = $pdo->prepare($sql);
			$q->execute(array($user_id, $exercice_id, $client_id, $type, $montant, $paye, $declaration, $mois_choisi_relatif, $periodicitee, $commentaire, $ventillation[1], $ventillation[2], $ventillation[3], $ventillation[4], $ventillation[5], $ventillation[6], $ventillation[7], $ventillation[8], $ventillation[9], $ventillation[10], $ventillation[11], $ventillation[12]));
			$recette_id = $pdo->lastInsertId();
            // Enregistre une recette avec paiement différé dans la table paiement (mois courant)
			if ($paiement == 1 && $recette_id != 0) {
				$sql = "INSERT INTO paiement (recette_id,mois_{$mois_choisi_relatif}) values(?, ?)";
				$q = $pdo->prepare($sql);
				$q->execute(array($recette_id, $montant));
			}
            // Enregistre le paiement élalé	des abonnements	dans la table paiement		
			if ($enregistre_paiement && $recette_id != 0) { 
				$sql = "INSERT INTO paiement (recette_id,mois_1,mois_2,mois_3,mois_4,mois_5,mois_6,mois_7,mois_8,mois_9,mois_10,mois_11,mois_12) values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
				$q = $pdo->prepare($sql);
				$q->execute(array($recette_id, $paiement_mois_{1}, $paiement_mois_{2}, $paiement_mois_{3}, $paiement_mois_{4}, $paiement_mois_{5}, $paiement_mois_{6}, $paiement_mois_{7}, $paiement_mois_{8}, $paiement_mois_{9}, $paiement_mois_{10}, $paiement_mois_{11}, $paiement_mois_{12}));
			}	
			Database::disconnect();				
			// Réinitialise le formulaire		
			header("Location: recette.php");
		}
		
    } // If POST
	
	
    // Lecture dans la base des recettes (sur user_id et exercice_id et mois) 
    $sql = "SELECT * FROM recette WHERE
    		(user_id = :userid AND exercice_id = :exerciceid AND mois = :mois)
    		ORDER by date_creation
    		";
    // Lecture dans la base des clients (sur user_id) 
    $sql3 = "SELECT id,prenom,nom FROM client WHERE
    		(user_id = :userid)
    		ORDER by nom
    		";
                        
    // Selecteur					
    $q = array('userid' => $user_id, 'exerciceid' => $exercice_id, 'mois' => MoisRelatif($abodep_mois,$exercice_mois));
    $q3 = array('userid' => $user_id);

    // Execution des requettes    
    $req = $pdo->prepare($sql);
    $req->execute($q);
    $data = $req->fetchAll(PDO::FETCH_ASSOC);
    $count = $req->rowCount($sql);

	$req3 = $pdo->prepare($sql3);
    $req3->execute($q3);	
    $data3 = $req3->fetchAll(PDO::FETCH_ASSOC);
	$count3 = $req3->rowCount($sql3);
   	        
    if ($count==0) { // Il n'y a aucunes recettes à afficher
        $affiche = false;              
    } else { // On affiche le tableau              			
	    $affiche = true;
    }

    // Liste des clients à afficher dans le select
    $Liste_Client = array();        
    if ($count3!=0) {
        $i=0;
        foreach ($data3 as $row3) {
            $Liste_Client[$i]['id']=$row3['id'];
            $Liste_Client[$i]['prenometnom']=ucfirst($row3['prenom']) . ' ' . ucfirst($row3['nom']);
            $i++;
        }       
    }     	

	Database::disconnect();
	$infos = true;
    
// Charge le Bilan    
    $TableauBilanMensuel = CalculBilanMensuel($user_id, $exercice_id, $exercice_treso);      
?>

<!DOCTYPE html>
<html lang="fr">
<?php require 'head.php'; ?>

<body>

    <?php $page_courante = "journal.php"; require 'nav.php'; ?>
        
    <div class="container">
        <div class="page-header">   
            <h2>Recettes & Abonnements</h2>
        </div>

        <!-- Affiche le dropdown formulaire mois avec selection automatique du mois en cours de la session -->
        <form class="form-inline" role="form" action="recette.php" method="post">      
            <select name="mois" class="form-control">
            <?php
                foreach ($Liste_Mois as $m) {
            ?>
                <option value="<?php echo MoisToNum($m);?>"<?php echo ($m==NumToMois($mois_choisi))?'selected':'';?>><?php echo "$m";?></option>    
            <?php       
                }   
            ?>    
            </select>
            <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-refresh"></span> Changer de mois</button>
            <!-- Affiche le bouton retour -->
            <a class="btn btn-primary" href="journal.php"><span class="glyphicon glyphicon-eject"></span> Retour</a>                              
        </form>
        <br>
        
        <!-- Affiche les informations de debug -->
        <?php 
 		if ($debug) {
		?>
		<div class="span10 offset1">
        <div class="alert alert alert-danger alert-dismissable fade in">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <strong>Informations de Debug : </strong><br>
            SESSION:<br>
            <pre><?php var_dump($_SESSION); ?></pre>
            POST:<br>
            <pre><?php var_dump($_POST); ?></pre>
            GET:<br>
            <pre><?php var_dump($_GET); ?></pre>           
        </div>
        </div>
        <?php       
        }   
        ?>  
        
        <!-- Affiche les sommmes -->
        <div>
	        <div class="btn-group btn-group-sm">
	            <button type="button" class="btn btn-info">CA :</button>
	            <button type="button" class="btn btn-default"><?php echo $TableauBilanMensuel[MoisRelatif($abodep_mois, $exercice_mois)]['CA']; ?> €</button>
	        </div>    
            <div class="btn-group btn-group-sm">
                <button type="button" class="btn btn-info">CA non déclaré:</button>
                <button type="button" class="btn btn-default"><?php echo $TableauBilanMensuel[MoisRelatif($abodep_mois, $exercice_mois)]['NON_DECLARE']; ?> €</button>
            </div> 
	        <div class="btn-group btn-group-sm">
	            <button type="button" class="btn btn-info">Encaissements :</button>
	            <button type="button" class="btn btn-default"><?php echo $TableauBilanMensuel[MoisRelatif($abodep_mois, $exercice_mois)]['ENCAISSEMENT']; ?> €</button>
	        </div>
	        <div class="btn-group btn-group-sm">
	            <button type="button" class="btn btn-info">Paiement étalés :</button>
	            <button type="button" class="btn btn-default"><?php echo $TableauBilanMensuel[MoisRelatif($abodep_mois, $exercice_mois)]['PAIEMENT']; ?> €</button>
	        </div>
	        <div class="btn-group btn-group-sm">
	            <button type="button" class="btn btn-info">Paiement échus :</button>
	            <button type="button" class="btn btn-default"><?php echo $TableauBilanMensuel[MoisRelatif($abodep_mois, $exercice_mois)]['ECHUS']; ?> €</button>
	        </div>                                            
	        <div class="btn-group btn-group-sm">
	            <button type="button" class="btn btn-info">Salaire :</button>                             
	            <button type="button" class="btn btn-default"><?php echo $TableauBilanMensuel[MoisRelatif($abodep_mois, $exercice_mois)]['SALAIRE']; ?> €</button>                            
	        </div>    
	        <div class="btn-group btn-group-sm">
	            <button type="button" class="btn btn-info">Trésorerie :</button>               
	            <button type="button" class="btn btn-default"><?php echo $TableauBilanMensuel[MoisRelatif($abodep_mois, $exercice_mois)]['REPORT_TRESO']; ?> €</button>             
	        </div>
        </div>
        <br>
        
		<!-- Affiche la table -->
		<div class="panel panel-default">
		  <div class="panel-heading">
	        <h3 class="panel-title">Liste des recettes du mois courant : <button type="button" class="btn btn-sm btn-info"><?php echo NumToMois($abodep_mois) . " $exercice_annee - " . ($exercice_annee +1); ?> : <span class="badge "><?php echo $count; ?></span></button></h3>
		  </div>			
		  <div class="panel-body">
			<?php 
	            // Insère l'aide en ligne pour les actions
	            $IDModale = "modalAideActions";
	            include('lib/aide.php'); 			    
			?>
            <div class="table-responsive">  			
			<table cellpadding="0" cellspacing="0" border="0" class="datatable table table-striped table-bordered table-hover success">
				<thead>
					<tr>
					  <th>Type</th>
					  <th>Montant</th>
					  <th>Périodicitée</th>
					  <th>Client</th>							  
					  <th>Commentaire</th>
					  <th>Action <a href="#" onclick="$('#modalAideActions').modal('show'); "><span class="glyphicon glyphicon-info-sign"></span></a></th>
					</tr>
				</thead>
	            
				<tbody>
				<?php		 
					foreach ($data as $row) {
						echo '<tr>';					
						echo '<td>' . NumToTypeRecette($row['type']) . '</td>';
						echo '<td>' . number_format($row['montant'],2,',','.') . ' €</td>';
						echo '<td>' . NumToPeriodicitee($row['periodicitee']) . '</td>';
						$result = 'N/C';
						foreach ($data3 as $row3) {
							if ($row3['id'] == $row['client_id']) {
								$result = ucfirst($row3['prenom']) . ' ' . ucfirst($row3['nom']);		
							}
						}	
						echo '<td>' . $result . '</td>';			
						echo '<td>' . $row['commentaire'] . '</td>';
					   	echo '<td width=90>';
				?>		
						<div class="btn-group btn-group-sm">
							  	<a href="recette_update.php?id=<?php echo $row['id']; ?>" class="btn btn-default btn-sm btn-warning glyphicon glyphicon-edit" role="button"> </a>
							  	<!-- Le bonton Delete active la modal et modifie le champ value à la volée pour passer l'ID a supprimer en POST -->
							  	<a href="#" id="<?php echo $row['id']; ?>"
							  	   onclick="$('#DeleteInput').val('<?php echo $row['id']; ?>'); $('#modalDelete').modal('show'); "
							  	   class="btn btn-default btn-sm btn-danger glyphicon glyphicon-trash" role="button"> </a>
						</div>
	
					   	</td>						
						</tr>
			<?php
				   } // Foreach	
			?>
	            </tbody>
	        </table>
            </div> <!-- /table-responsive -->
                	        
            <!-- Modal delete-->				
            <?php include('modal/recette_delete.php'); ?>
    	
    	<!-- Affiche le formulaire inline ajout recette -->		
        <?php 
            // Insère l'aide en ligne pour les actions
            $IDModale = "modalAideFormRecette";
            include('lib/aide.php'); 
        ?>
                                    
        <div class="panel panel-default">
            <div class="panel-heading"><strong>Ajout d'une recette : </strong><a href="#" onclick="$('#modalAideFormRecette').modal('show'); "><span class="glyphicon glyphicon-info-sign"></span></a></div>
            <div class="panel-body">
                <form class="form-inline" role="form" action="recette.php" method="post">
                        
    				<!-- Formulaire principal -->                 
    	            <div class="form-group ">
    	                    <select name="type" class="form-control">
    			            <?php
    			                foreach ($Liste_Recette as $r) {
    			            ?>
    			                <option value="<?php echo TypeRecetteToNum($r);?>" <?php echo (TypeRecetteToNum($r)==$type)?'selected':'';?>><?php echo $r;?></option>    
    			            <?php 
    			                } // foreach   
    			            ?>
    	                    </select>
    	            </div>		      
    	            <div class="form-group  <?php echo !empty($periodiciteeError)?'has-error':'';?>">
    	                    <select name="periodicitee" class="form-control">
    			            <?php
    			                foreach ($Liste_Periodicitee as $p) {
    			            ?>
    			                <option value="<?php echo PeriodiciteeToNum($p);?>" <?php echo (PeriodiciteeToNum($p)==$periodicitee)?'selected':'';?>><?php echo $p;?></option>    
    			            <?php
    			                } // foreach   
    			            ?>
    	                    </select>
    	            </div>			       		
    				<div class="form-group ">
    	                    <select name="paiement" id="paiement" class="form-control">
    			                <option value="0" <?php echo ($paiement == '0')?'selected':'';?>>Réglé</option>
    			                <option value="1" <?php echo ($paiement == '1')?'selected':'';?>>A régler</option>   
    			                <option value="2" <?php echo ($paiement == '2')?'selected':'';?>>Paiement étalé</option>   				                				                    
    	                    </select>
    	            </div>
    				<div class="form-group ">
    	                    <select name="client" id="client" class="form-control">
    			            	<option value="0">N/C</option>
    			            <?php
    			            	foreach ($Liste_Client as $c) {
    			            ?>
    			                <option value="<?php echo $c['id'];?>" <?php echo ($c['id']==$client_id)?'selected':'';?>><?php echo $c['prenometnom'];?></option>    
    			            <?php
    			                } // foreach   
    			            ?>			                				                    
    	                    </select>
    	            </div>
    	            <label class="form-group ">
                        <button class="btn btn-default"> <input type="checkbox" name="black" id="black" value="1" <?php echo ($black)?'checked':'';?>>Non déclaré</button>
                    </label>
    	            <br>
                    <div class="form-group  <?php echo !empty($montantError)?'has-error':'';?>">
                        <input name="montant" id="montant" type="text" class="form-control" value="<?php echo !empty($montant)?$montant:'';?>" placeholder="Montant €" required autofocus>                              
                    </div>
                    <div class="form-group  <?php echo !empty($commentaireError)?'has-error':'';?>">
                        <input name="commentaire" id="commentaire" type="text" class="form-control" value="<?php echo !empty($commentaire)?$commentaire:'';?>" placeholder="Commentaire">                              
                    </div>                      
    	       		
                  	<button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-plus-sign"></span> Ajout</button><br>
    
    				<!-- Affiche les erreurs -->
    				<div class="help-block has-error">
    				<?php if (!empty($montantError)): ?>
    				<span class="help-block has-error"><?php echo $montantError;?></span>
    				<?php endif; ?>
    				<?php if (!empty($periodiciteeError)): ?>
    				<span class="help-block has-error"><?php echo $periodiciteeError;?></span>
    				<?php endif; ?>						
    				<?php if (!empty($commentaireError)): ?>
    				<span class="help-block has-error"><?php echo $commentaireError;?></span>
    				<?php endif; ?>
    				<?php if (!empty($paiementError)): ?>
    				<span class="help-block has-error"><?php echo $paiementError;?></span>
    				<?php endif; ?>					
    				</div>
    						
    				<!-- Modal paiement-->
                    <?php include('modal/recette_paiement.php'); ?>
    
            </div>  <!-- /panel-body -->        	            
        </div> 	<!-- /panel -->
    
    </div> <!-- /container -->

    <?php require 'footer.php'; ?>

    <script>  
        /* Table initialisation */
        $(document).ready(function(){
            $('.datatable').dataTable({
                "sPaginationType": "bs_full"
            });
            $('.datatable').each(function(){
                var datatable = $(this);
                // SEARCH - Add the placeholder for Search and Turn this into in-line form control
                var search_input = datatable.closest('.dataTables_wrapper').find('div[id$=_filter] input');
                search_input.attr('placeholder', 'Rechercher');
                search_input.addClass('form-control input-sm');
                // LENGTH - Inline-Form control
                var length_sel = datatable.closest('.dataTables_wrapper').find('div[id$=_length] select');
                length_sel.addClass('form-control input-sm');
            });             
        });
    </script> 
	
	<?php 
	if ($affiche_paiement_etale) { // Modal conditionné par PHP
	?>	
	    <script>
		    $(document).ready(function(){ // Le DOM est chargé
				$('#modalPaiement').modal('show');	
			});
		</script>
	<?php	 									
	} // endif
	?>    
        
  </body>
</html>