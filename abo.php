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
    	header("Location: abodep.php");
    }
	
// Initialisation de la base
    include_once 'database.php';
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
// Lecture du POST (Choix du mois)
    $montant = null;
	$commentaire = null;
	$montantError = null;	
    if (isset($sPOST['montant']) ) { // J'ai un POST
        $type = $sPOST['type'];        
        $montant = $sPOST['montant'];
        $periodicitee = $sPOST['periodicitee'];
		$commentaire = $sPOST['commentaire'];
		
		// validate input
		$valid = true;
		
		if (empty($montant) || $montant < 0 || $montant == null) {
			$montantError= "Veuillez entrer un montant positif ou nul.";
			$valid = false;
		}
		
		// Verification de la periodicitee
		if (($abodep_mois + $periodicitee - 1 ) > 12) { // La périodicitee est superieure a l'année
			$periodiciteeError= "La périodicité de l'abonnement est trop grande pour l'exercice en cours.";		
			$valid = false;			
		}
		$ventillation = Ventillation($abodep_mois, $montant, $periodicitee);

		// insert data
		if ($valid) {
			$sql = "INSERT INTO abonnement (user_id,exercice_id,type,montant,mois,periodicitee,commentaire) values(?, ?, ?, ?, ?, ?, ?)";
			$q = $pdo->prepare($sql);
			$q->execute(array($user_id, $exercice_id, $type, $montant, $abodep_mois, $periodicitee, $commentaire));
			Database::disconnect();
		}
		
		// Réinitialise le formulaire		
		header("Location: abo.php");
    } // If POST
	
	
// Lecture dans la base des abonnements (sur user_id et exercice_id et mois) 
    $sql = "SELECT * FROM abonnement WHERE
    		(user_id = :userid AND exercice_id = :exerciceid AND mois = :mois)
    		";
// Requette pour calcul de la somme	
    $sql2 = "SELECT SUM(montant) FROM abonnement WHERE
    		(user_id = :userid AND exercice_id = :exerciceid AND mois = :mois)
    		";			
    $q = array('userid' => $user_id, 'exerciceid' => $exercice_id, 'mois' => $abodep_mois);
    $req = $pdo->prepare($sql);
    $req->execute($q);
    $data = $req->fetchAll(PDO::FETCH_ASSOC);
	$req = $pdo->prepare($sql2);
    $req->execute($q);
    $data2 = $req->fetch(PDO::FETCH_ASSOC);
    $count = $req->rowCount($sql);
    if ($count==0) { // Il n'y a rien à afficher
        $affiche = false;              
    } else {
    		// Calcul des sommes
	        $total_recettes= !empty($data2["SUM(montant)"]) ? $data2["SUM(montant)"] : 0;  	   
	        // On affiche le tableau
	        $affiche = true;
    }
	Database::disconnect();
	$infos = true;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>GestAbo</title>
    <meta charset="utf-8">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" media="screen">
</head>

<body>

    <script src="bootstrap/js/jquery-2.0.3.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    
    <div class="container">
        <h2>Affichage et Modification des Abonnement</h2>
        
        <!-- Affiche la navigation -->
        <ul class="nav nav-pills">
          <li><a href="home.php">Console</a></li>
          <li class="active"><a href="abodep.php">Abonnements & Dépenses</a></li>
          <li><a href="meusuel.php">Bilan Mensuel</a></li>
          <li><a href="bilan.php">Bilan Annuel</a></li>
          <li><a href="encaissements.php">Encaissements</a></li>
          <li><a href="paiements.php">Paiements</a></li>
          <li><a href="conf.php">Configuration</a></li>
          <li><a href="deconnexion.php">Deconnexion</a></li>
        </ul>
                      
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
        
		<!-- Affiche la table des abonnements en base sous condition -->
		<div class="span10">
			<?php 
 			if ($affiche) {
			?>
            <div class="row">
                <h3>Liste des abonnements du mois courant : <button type="button" class="btn btn-info"><?php echo NumToMois($abodep_mois); ?></button></h3>
		
			<table class="table table-striped table-bordered table-hover success">
				<thead>
					<tr>
					  <th>Type</th>
					  <th>Montant</th>
					  <th>Périodicitée</th>
					  <th>Commentaire</th>
					  <th>Action</th>
					</tr>
				</thead>
                
				<tbody>
				<?php		 
					foreach ($data as $row) {
						echo '<tr>';					
						echo '<td>' . NumToTypeRecette($row['type']) . '</td>';
						echo '<td>' . $row['montant'] . ' €</td>';
						echo '<td>' . NumToPeriodicitee($row['periodicitee']) . '</td>';						
						echo '<td>' . $row['commentaire'] . '</td>';
					   	echo '<td width=90>';
				?>		
						<div class="btn-group btn-group-sm">
							  	<a href="abo_update.php?id=<?php echo $row['id']; ?>" class="btn btn-default btn-sm btn-warning glyphicon glyphicon-edit" role="button"> </a>
							  	<a href="abo_delete.php?id=<?php echo $row['id']; ?>" class="btn btn-default btn-sm btn-danger glyphicon glyphicon-trash" role="button"> </a>
						</div>

					   	</td>						
						</tr>
			<?php
				   } // Foreach	
			} // If Affiche
			?>
                </tbody>
            </table>
            <!-- Affiche les sommmes -->        
			<p>
				<button type="button" class="btn btn-info">Total recettes : <?php echo $total_recettes; ?> €</button>
			</p>             
			</div> 	<!-- /row -->

		<!-- Affiche le formulaire inline ajout abonnement -->			
            <div class="row">
                <h3>Ajout d'un abonnement :</h3>
	            <form class="form-inline" role="form" action="abo.php" method="post">
	            
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
				                foreach ($Liste_Recette as $r) {
				            ?>
				                <option value="<?php echo TypeRecetteToNum($r);?>"><?php echo $r;?></option>    
				            <?php 
				                } // foreach   
				            ?>
		                    </select>
		            </div>		      
		       		<?php Affiche_Champ($montant, $montantError, 'montant','Montant €', 'text' ); ?>
		            <div class="form-group">
		                    <select name="periodicitee" class="form-control">
				            <?php
				                foreach ($Liste_Periodicitee as $p) {
				            ?>
				                <option value="<?php echo PeriodiciteeToNum($p);?>"><?php echo $p;?></option>    
				            <?php
				                } // foreach   
				            ?>
		                    </select>
		            </div>			       		
		       		<?php Affiche_Champ($commentaire, $commentaireError, 'commentaire','Commentaire', 'text' ); ?>

	              	<button type="submit" class="btn btn-success btn-sm">Ajout</button>
	            </form>
            </div> 	<!-- /row -->
			<script type="text/javascript">
				document.getElementById('montant').focus(); 
			</script>
			<!-- Affiche le bouton retour -->
			<br>        
			<p>
				<a href="abodep.php" class="btn btn-success">Retour</a>
			</p>
			
        </div>  <!-- /span -->        			
    
    </div> <!-- /container -->
  </body>
</html>