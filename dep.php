<!-- 
© Copyright : Aboo / www.aboo.fr : Frédéric MEYROU : tous droits réservés
-->
<?php
// Vérification de l'Authent
    session_start();
    require('authent.php');
    if( !Authent::islogged()){
        // Non authentifié on repart sur la HP
        header('Location:index.php');
    }

// Dépendances
	require_once('fonctions.php');

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
    include_once 'database.php';
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
		header("Location: dep.php");
    } // If POST
	
	
// Lecture dans la base des depenses (sur user_id et exercice_id et mois) 
    $sql = "SELECT * FROM depense WHERE
    		(user_id = :userid AND exercice_id = :exerciceid AND mois = :mois)
    		";
// Requette pour calcul de la somme	
    $sql2 = "SELECT SUM(montant) FROM depense WHERE
    		(user_id = :userid AND exercice_id = :exerciceid AND mois = :mois)
    		";	
    $q = array('userid' => $user_id, 'exerciceid' => $exercice_id, 'mois' => MoisRelatif($abodep_mois,$exercice_mois));
    $req = $pdo->prepare($sql);
    $req->execute($q);
    $data = $req->fetchAll(PDO::FETCH_ASSOC);
    $req->execute($q);	
    $count = $req->rowCount($sql);
	
	$req = $pdo->prepare($sql2);
    $req->execute($q);	
    $data2 = $req->fetch(PDO::FETCH_ASSOC);
    
    if ($count==0) { // Il n'y a rien à afficher
        $affiche = false;              
    } else {   
    		// Calcul des sommes
    		$total_depenses= !empty($data2["SUM(montant)"]) ? $data2["SUM(montant)"] : 0;
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
        <h2>Dépenses & Charges</h2>
        <br>

        <!-- Affiche le dropdown formulaire mois avec selection automatique du mois en cours de la session -->
        <form class="form-inline" role="form" action="dep.php" method="post">      
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
        </div>
       </div>
        <?php       
        }   
        ?>  
        
		<!-- Affiche la table des dépenses en base sous condition -->
		<div class="span10">
			<?php 
 			if ($affiche) {
			?>
            <div class="row">
                <h3>Liste des dépenses du mois courant : <button type="button" class="btn btn-info"><?php echo NumToMois($abodep_mois); ?> : <span class="badge "><?php echo $count; ?></span></button></h3>
		
				<table class="table table-striped table-bordered table-hover success">
					<thead>
						<tr>
						  <th>Type</th>
	  					  <th>Montant</th>
						  <th>Commentaire</th>
						  <th>Action</th>					  			  
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
							<div class="btn-group btn-group-sm">
								  	<a href="dep_update.php?id=<?php echo $row['id']; ?>" class="btn btn-default btn-sm btn-warning glyphicon glyphicon-edit" role="button"> </a>
								  	<a href="dep_delete.php?id=<?php echo $row['id']; ?>" class="btn btn-default btn-sm btn-danger glyphicon glyphicon-trash" role="button"> </a>
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
					<button type="button" class="btn btn-info">Total dépenses : <?php echo $total_depenses; ?> €</button>
				</p>             
			</div> 	<!-- /row -->
			<?php
			} // If Affiche
			?>
		<!-- Affiche le formulaire inline ajout depense -->			
            <div class="row">
                <h3>Ajout d'une dépense :</h3>
	            <form class="form-inline" role="form" action="dep.php" method="post">
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
		       		<?php Affiche_Champ($montant, $montantError, 'montant','Montant €', 'text' ); ?>
		       		<?php Affiche_Champ($commentaire, $commentaireError, 'commentaire','Commentaire', 'text' ); ?>

	              	<button type="submit" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus-sign"></span> Ajout</button>
	            </form>
            </div> 	<!-- /row -->		
            <script type="text/javascript">
				document.getElementById('montant').focus();
			</script>
			<!-- Affiche le bouton retour -->
			<br>        
			<p>
				<a class="btn btn-primary" href="journal.php"><span class="glyphicon glyphicon-chevron-up"></span> Retour</a>
			</p>
			
        </div>  <!-- /span -->        			
    
    </div> <!-- /container -->

    <?php require 'footer.php'; ?>
        
  </body>
</html>