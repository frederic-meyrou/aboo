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
        header('Location:../index.php');
    }

// Récupération des variables de session
	include_once('lib/var_session.php');
	
// Mode Debug
	$debug = false;

// Sécurisation POST & GET
    foreach ($_GET as $key => $value) {
        $sGET[$key]=htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
    foreach ($_POST as $key => $value) {
        $sPOST[$key]=htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
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
    
// Lecture du POST Formulaire
    $montant = null;
	$commentaire = null;
	$montantError = null;	
    if (isset($sPOST['montant']) ) { // J'ai un POST
        $montant = $sPOST['montant'];
		$commentaire = $sPOST['commentaire'];
		$type = $sPOST['type'];    
		
		// validate input
		$valid = true;
		
		if (empty($montant) || $montant < 0 || $montant == null) {
			$montantError= "Veuillez entrer un montant positif.";
			$valid = false;
		}

		// insert data
		if ($valid) {
			$sql = "INSERT INTO depense (user_id,exercice_id,type,montant,mois,commentaire) values(?, ?, ?, ?, ?, ?)";
			$q = $pdo->prepare($sql);		
			$q->execute(array($user_id, $exercice_id, $type, $montant, MoisRelatif($abodep_mois,$exercice_mois), $commentaire));
		}
		
		// Réinitialise pour le formulaire		
		header("Location: depense.php");
    } // If POST
	
	
// Lecture dans la base des depenses (sur user_id et exercice_id et mois) 
    $sql = "SELECT * FROM depense WHERE
    		(user_id = :userid AND exercice_id = :exerciceid AND mois = :mois)
    		";
    $q = array('userid' => $user_id, 'exerciceid' => $exercice_id, 'mois' => MoisRelatif($abodep_mois,$exercice_mois));
    
    $req = $pdo->prepare($sql);
    $req->execute($q);
    $data = $req->fetchAll(PDO::FETCH_ASSOC);
    $req->execute($q);	
    $count = $req->rowCount($sql);
    
    if ($count==0) { // Il n'y a rien à afficher
        $affiche = false;              
    } else { // On affiche le tableau 
        $affiche = true;
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
            <h2>Dépenses & Charges</h2>
        </div>

        <!-- Affiche le dropdown formulaire mois avec selection automatique du mois en cours de la session -->
        <form class="form-inline" role="form" action="depense.php" method="post">      
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
            <!-- Affiche le bouton des recettes  -->
            <a class="btn btn-primary" href="recette.php"><span class="glyphicon glyphicon-plus-sign"></span> Recettes</a>            
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
	            <button type="button" class="btn btn-info">Dépenses :</button>
	            <button type="button" class="btn btn-default"><?php echo $TableauBilanMensuel[MoisRelatif($abodep_mois, $exercice_mois)]['DEPENSE']; ?> €</button>
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
	        <h3 class="panel-title">Liste des dépenses du mois courant : <button type="button" class="btn btn-sm btn-info"><?php echo NumToMois($abodep_mois) . " $exercice_annee - " . ($exercice_annee +1); ?> : <span class="badge "><?php echo $count; ?></span></button></h3>
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
					<tr class="active">
					  <th>Type</th>
					  <th>Montant</th>
					  <th>Commentaire</th>
					  <th>Action <a href="#" onclick="$('#modalAideActions').modal('show'); "><span class="glyphicon glyphicon-info-sign"></span></a></th>					  			  
					</tr>
				</thead>
	            
				<tbody>
				<?php		 
					foreach ($data as $row) {
						echo '<tr>';
						echo '<td>' . NumToTypeDepense($row['type']) . '</td>';
						echo '<td>' . number_format($row['montant'],2,',','.') . ' €</td>';
						echo '<td>' . $row['commentaire'] . '</td>';
					   	echo '<td width=90>';
				?>		
						<div class="btn-group btn-group-xs">
							  	<a href="depense_update.php?id=<?php echo $row['id']; ?>" class="btn btn-default btn-xs btn-warning glyphicon glyphicon-edit" role="button"> </a>
	                            <!-- Le bonton Delete active la modal et modifie le champ value à la volée pour passer l'ID a supprimer en POST -->
	                            <a href="#" id="<?php echo $row['id']; ?>"
	                               onclick="$('#DeleteInput').val('<?php echo $row['id']; ?>'); $('#modalDelete').modal('show'); "
	                               class="btn btn-default btn-xs btn-danger glyphicon glyphicon-trash" role="button"> </a>
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
            <?php include('modal/depense_delete.php'); ?>		

		<!-- Affiche le formulaire inline ajout depense -->			               
        <?php 
            // Insère l'aide en ligne pour les actions
            $IDModale = "modalAideFormDepense";
            include('lib/aide.php'); 
        ?>                
        
        <div class="panel panel-default">
            <div class="panel-heading"><strong>Ajout d'une dépense : </strong><a href="#" onclick="$('#modalAideFormDepense').modal('show'); "><span class="glyphicon glyphicon-info-sign"></span></a></div>
            <div class="panel-body">
        
	            <form class="form-inline" role="form" action="depense.php" method="post">
		            <?php function Affiche_Champ(&$champ, &$champError, $champinputname, $champplaceholder, $type) { ?>
		            		<div class="form-group <?php echo !empty($champError)?'has-error':'';?>">
		                    	<input name="<?php echo "$champinputname" ?>" id="<?php echo "$champinputname" ?>" type="<?php echo "$type" ?>" class="form-control" value="<?php echo !empty($champ)?$champ:'';?>" placeholder="<?php echo "$champplaceholder" ?>">		            
		                    <?php if (!empty($champError)): ?>
		                     		<span class="help-inline"><?php echo $champError;?></span>
		                    <?php endif; ?>		            
		       				</div>
		            <?php } ?>
		            <div class="form-group">
		                    <select name="type" class="form-control">
				            <?php
				                foreach ($Liste_Depense as $d) {
				            ?>
				                <option value="<?php echo TypeDepenseToNum($d);?>"><?php echo $d;?></option>    
				            <?php 
				                } // foreach   
				            ?>
		                    </select>
		            </div>		
                    <div class="form-group <?php echo !empty($montantError)?'has-error':'';?>">
                        <input name="montant" id="montant" type="text" class="form-control" value="<?php echo !empty($montant)?$montant:'';?>" placeholder="Montant €" required autofocus>                              
                    </div>                          	                  		            
		       		<?php Affiche_Champ($commentaire, $commentaireError, 'commentaire','Commentaire', 'text' ); ?>

	              	<button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-plus-sign"></span> Ajout</button>
	            </form>
            </div>  <!-- /panel-body -->                        
        </div>  <!-- /panel -->			

    <?php require 'footer.php'; ?>    
    </div> <!-- /container -->
    
    <script>  
        /* Table initialisation */
        $(document).ready(function() {
            $('.datatable').dataTable();
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
        
  </body>
</html>