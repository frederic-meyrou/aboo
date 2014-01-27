<!-- 
© Copyright : Aboo / www.aboo.fr : Frédéric MEYROU : tous droits réservés
-->
<?php
// Dépendances
	require_once('lib/fonctions.php');
    include_once('lib/database.php');
	
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
    	header("Location: conf.php");    	
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
		
	if (isset($_POST['montant']) ) { // J'ai un POST
        $type = $sPOST['type'];        
        $montant = $sPOST['montant'];
        $periodicitee = $sPOST['periodicitee'];
		$commentaire = $sPOST['commentaire'];
		$paiement = isset($_POST['paiement']) ? $sPOST['paiement'] : 0;
		$client_id = isset($_POST['client']) ? $sPOST['client'] : null;
		$verif_paiement_etale = isset($_POST['etale']) ? true : false;
		
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
			$sql = "INSERT INTO recette (user_id,exercice_id,client_id,type,montant,paye,mois,periodicitee,commentaire,mois_1,mois_2,mois_3,mois_4,mois_5,mois_6,mois_7,mois_8,mois_9,mois_10,mois_11,mois_12) values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
			$q = $pdo->prepare($sql);
			$q->execute(array($user_id, $exercice_id, $client_id, $type, $montant, $paye, $mois_choisi_relatif, $periodicitee, $commentaire, $ventillation[1], $ventillation[2], $ventillation[3], $ventillation[4], $ventillation[5], $ventillation[6], $ventillation[7], $ventillation[8], $ventillation[9], $ventillation[10], $ventillation[11], $ventillation[12]));
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
    // Requette pour calcul de la somme	du montant (CA)
    $sql2 = "SELECT SUM(montant),SUM(mois_1),SUM(mois_2),SUM(mois_3),SUM(mois_4),SUM(mois_5),SUM(mois_6),SUM(mois_7),SUM(mois_8),SUM(mois_9),SUM(mois_10),SUM(mois_11),SUM(mois_12) FROM recette WHERE
    		(user_id = :userid AND exercice_id = :exerciceid AND mois = :mois)
    		";
    // Lecture dans la base des clients (sur user_id) 
    $sql3 = "SELECT id,prenom,nom FROM client WHERE
    		(user_id = :userid)
    		ORDER by nom
    		";
    $q3 = array('userid' => $user_id);
    // Requette pour calcul de la somme des encaissements
    $sql4 = "SELECT SUM(montant) FROM recette WHERE
            (user_id = :userid AND exercice_id = :exerciceid AND mois = :mois) AND
            paye = 1
            ";
                        
    // Selecteur					
    $q = array('userid' => $user_id, 'exerciceid' => $exercice_id, 'mois' => MoisRelatif($abodep_mois,$exercice_mois));

    // Execution des requettes    
    $req = $pdo->prepare($sql);
    $req->execute($q);
    $data = $req->fetchAll(PDO::FETCH_ASSOC);
    $count = $req->rowCount($sql);
	
	$req2 = $pdo->prepare($sql2);
    $req2->execute($q);	
    $data2 = $req2->fetch(PDO::FETCH_ASSOC);

	$req3 = $pdo->prepare($sql3);
    $req3->execute($q3);	
    $data3 = $req3->fetchAll(PDO::FETCH_ASSOC);
	$count3 = $req3->rowCount($sql3);

    $req4 = $pdo->prepare($sql4);
    $req4->execute($q); 
    $data4 = $req4->fetch(PDO::FETCH_ASSOC);	
   	        
    if ($count==0) { // Il n'y a aucunes recettes à afficher
        $affiche = false;              
    } else {
    		// Calcul des sommes
	        $total_recettes= floatval(!empty($data2["SUM(montant)"]) ? $data2["SUM(montant)"] : 0);
	        for ($i = 1; $i <= 12; $i++) { 
	        	$total_mois_{$i}= !empty($data2["SUM(mois_$i)"]) ? $data2["SUM(mois_$i)"] : 0;
			}
            $total_encaissement= floatval(!empty($data4["SUM(montant)"]) ? $data4["SUM(montant)"] : 0);
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
			// On affiche le tableau
	        $affiche = true;
    }
	
	Database::disconnect();
	$infos = true;
?>

<!DOCTYPE html>
<html lang="fr">
<?php require 'head.php'; ?>

<body>

    <?php $page_courante = "journal.php"; require 'nav.php'; ?>
        
    <div class="container">
        <h2>Recettes & Abonnements</h2>
        <br>

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
            DATA :<br>
            <pre><?php var_dump($data); ?></pre>
            DATA2 :<br>
            <pre><?php var_dump($data2); ?></pre>  
            DATA3 :<br>
            <pre><?php var_dump($data3); ?></pre>
            DATA4 :<br>
            <pre><?php var_dump($data4); ?></pre>
            DATA5 :<br>
            <pre><?php var_dump($data5); ?></pre>                                        
        </div>
       </div>
        <?php       
        }   
        ?>  
        
		<!-- Affiche la table des recettes en base sous condition -->
		<div class="span10">
			<?php 
 			if ($affiche) {
                // Insère l'aide en ligne pour les actions
                $IDModale = "modalAideActions";
                include('lib/aide.php'); 			    
			?>
	            <div class="row">
	                <h3>Liste des recettes du mois courant : <button type="button" class="btn btn-info"><?php echo NumToMois($abodep_mois); ?> : <span class="badge "><?php echo $count; ?></span></button></h3>
			
					<table class="table table-striped table-bordered table-hover success">
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
		            <!-- Affiche les sommmes -->        
					<p>
						<button type="button" class="btn btn-info">Total recettes : <?php echo $total_recettes; ?> €</button>
                        <button type="button" class="btn btn-info">Total encaissement : <?php echo $total_encaissement; ?> €</button>						
						<button type="button" class="btn btn-info">Total affecté au salaire : <?php echo $total_mois_{$mois_choisi_relatif}; ?> €</button>
						<button type="button" class="btn btn-info">Trésorerie : <?php echo ($total_recettes - $total_mois_{$mois_choisi_relatif}); ?> €</button>
						
					</p>             
				</div> 	<!-- /row -->
				
                <!-- Modal Delete -->
                <div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-labelledby="DeleteModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                        <form class="form-horizontal" action="recette_delete.php" method="post">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3 class="modal-title" id="DeleteModalLabel">Suppression d'une recette :</h3>
                          </div><!-- /.modal-header -->
                          <div class="modal-body">
                              <strong>
                               <p class="alert alert-danger">Confirmez-vous la suppression ?</p>
                               <center>Attention cette action supprimera aussi tous les paiements étalé associés.</center>
                               <input id="DeleteInput" type="hidden" name="id" value=""/>
                              </strong>
                          </div><!-- /.modal-body -->                                         
                          <div class="modal-footer">
                            <div class="form-actions">                              
                                <button type="submit" class="btn btn-danger pull-right"><span class="glyphicon glyphicon-trash"></span> Suppression</button>
                                <button type="button" class="btn btn-primary pull-left" data-dismiss="modal"><span class="glyphicon glyphicon-eject"></span> Retour</button>                                  
                            </div>
                          </div><!-- /.modal-footer -->
                        </form>                   
                    </div><!-- /.modal-content -->
                  </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->				
			<?php
			} // If Affiche
			?>
			
			<!-- Affiche le formulaire inline ajout recette -->		
            <div class="row">
                
                <div class="panel panel-default">
                    <div class="panel-heading"><strong>Ajout d'une recette :</strong></div>
                    <div class="panel-body">
        	            <form class="form-inline" role="form" action="recette.php" method="post">
        	            
        		            <?php function Affiche_Champ(&$champ, &$champError, $champinputname, $champplaceholder, $type) { ?>
        		            		<div class="form-group <?php echo !empty($champError)?'has-error':'';?>">
        		                    	<input name="<?php echo "$champinputname" ?>" id="<?php echo "$champinputname" ?>" type="<?php echo "$type" ?>" class="form-control" value="<?php echo !empty($champ)?$champ:'';?>" placeholder="<?php echo "$champplaceholder" ?>">		                      
        		       				</div>
        		            <?php } ?>
        		            
        					<!-- Formulaire principal -->
        		            <div class="form-group">
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
                            <div class="form-group <?php echo !empty($montantError)?'has-error':'';?>">
                                <input name="montant" id="montant" type="text" class="form-control" value="<?php echo !empty($montant)?$montant:'';?>" placeholder="Montant €" required autofocus>                              
                            </div>        		       		
        		            <div class="form-group <?php echo !empty($periodiciteeError)?'has-error':'';?>">
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
        					<div class="form-group">
        		                    <select name="paiement" id="paiement" class="form-control">
        				                <option value="0" <?php echo ($paiement == '0')?'selected':'';?>>Réglé</option>
        				                <option value="1" <?php echo ($paiement == '1')?'selected':'';?>>A régler</option>   
        				                <option value="2" <?php echo ($paiement == '2')?'selected':'';?>>Paiement étalé</option>   				                				                    
        		                    </select>
        		            </div>
        					<div class="form-group">
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
        		       		<?php Affiche_Champ($commentaire, $commentaireError, 'commentaire','Commentaire', 'text' ); ?>
        
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
        					<div class="modal fade" id="modalPaiement" tabindex="-1" role="dialog" aria-labelledby="PaiementModalLabel" aria-hidden="true">
        					  <div class="modal-dialog">
        					    <div class="modal-content">
        						      <div class="modal-header">
        						        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        						        <h3 class="modal-title" id="PaiementModalLabel">Saisie des paiements étalés :</h3>
        						      </div><!-- /.modal-header -->
        						      <div class="modal-body">
                                        <?php 
                                        if ($affiche_paiement_etale) { // On affiche le formulaire que si nécessaire
                                            echo "Répartissez votre " . NumToTypeRecette($type) . " de $montant € sur les mois suivants : "; 
                                            echo '<div class="panel panel-default"><div class="panel-body">';                                         
                                            echo '<dl class="dl-horizontal">'; 						
        			 						for ($m = $mois_choisi_relatif; $m <= 12; $m++) { // Affiche les champs paiements
        			 						    echo '<dt>';
                                                echo '<dt>' . NumToMois(MoisAnnee($m,$exercice_mois)) . ' : </dt>';
                                                echo '<dd>';
        										Affiche_Champ($paiement_mois_{$m}, $paiement_mois_Error, 'paiement_mois_' . $m, NumToMois(MoisAnnee($m,$exercice_mois)) . ' €', 'text' );
        										echo '</dd>';
        									} // endfor
        									echo '</dl>';
                                            echo '</div></div>';
        									echo '<input type="hidden" name="etale" value="1">'; //Flag pour traitement du formulaire
        								    echo '<div class="help-block has-error">';
                                            if (!empty($paiement_mois_Error)) {
                                                echo '<span class="help-block has-error">' . $paiement_mois_Error . '</span>';
                                            echo '</div>';
                                            } // If
                                        } // IF
                                        ?>                                                         
        						      </div><!-- /.modal-body -->					    				  
        							  <div class="modal-footer">
        							  	<div class="form-actions pull-right">
                                            <button type="button" class="btn btn-primary" data-dismiss="modal"><span class="glyphicon glyphicon-eject"></span> Retour</button>							  	    
        					              	<button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-plus-sign"></span> Ajout des paiements</button>
        							    </div>
        						      </div><!-- /.modal-footer -->
        					    </div><!-- /.modal-content -->
        					  </div><!-- /.modal-dialog -->
        					</div><!-- /.modal -->
        
        	            </form>
        	            
                    </div>  <!-- /row -->
                </div>  <!-- /panel-body -->        	            
            </div> 	<!-- /panel -->
			
			<!-- Affiche le bouton retour -->
			<br>        
			<a class="btn btn-primary" href="journal.php"><span class="glyphicon glyphicon-eject"></span> Retour</a>
		
        </div>  <!-- /span -->        			
    
    </div> <!-- /container -->

    <?php require 'footer.php'; ?>

    <script>
        $(document).ready(function(){ // Le DOM est chargé
            // RaS
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