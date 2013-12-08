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
	$debug = true;

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
//    if (isset($sPOST['']) ) { // J'ai un POST
//            $ = $sPOST[''];
//    } else { // Je n'ai pas de POST
//            $ = null;
//   }
	
// Lecture dans la base des abonnements (sur user_id et exercice_id et mois) 
    $sql = "SELECT * FROM abonnement A WHERE
    		(A.user_id = :userid AND A.exercice_id = :exerciceid AND A.mois = :mois)
    		";
    $q = array('userid' => $user_id, 'exerciceid' => $exercice_id, 'mois' => $abodep_mois);
    $req = $pdo->prepare($sql);
    $req->execute($q);
    $data = $req->fetch(PDO::FETCH_ASSOC);
    $count = $req->rowCount($sql);
    if ($count==0) { // Il n'y a rien à afficher
        $affiche = false;              
    } else {   
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
          <li><a href="abodep.php">Abonnements & Dépenses</a></li>
          <li><a href="meusuel.php">Bilan Mensuel</a></li>
          <li><a href="bilan.php">Bilan Annuel</a></li>
          <li><a href="encaissements.php">Encaissements</a></li>
          <li><a href="paiements.php">Paiements</a></li>
          <li><a href="conf.php">Configuration</a></li>
          <li><a href="deconnexion.php">Deconnexion</a></li>
        </ul>
               
        <!-- Affiche le bouton de retour -->        
		<p>
			<a href="abodep.php" class="btn btn-success">Retour</a>
		</p>
        
        <!-- Affiche les informations de debug -->
        <?php 
 		if ($debug) {
		?>
        <div class="alert alert-error alert-dismissable fade in">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <strong>Informations de Debug : </strong><br>
            SESSION:<br>
            <pre><?php var_dump($_SESSION); ?></pre>
            POST:<br>
            <pre><?php var_dump($_POST); ?></pre>
            GET:<br>
            <pre><?php var_dump($_GET); ?></pre>
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
                <h3>Liste des abonnements du mois courant</h3>
            </div>			
			<table class="table table-striped table-bordered table-hover success">
				<thead>
					<tr>
					  <th>Montant</th>
					  <th>Commentaire</th>
					  <th>Action</th>					  			  
					</tr>
				</thead>
                
				<tbody>
				<?php 			 
					foreach ($data as $row) {
						echo '<tr>';
						echo '<td>' . $row['montant'] . '</td>';
						echo '<td>' . $row['commentaire'] . '</td>';
					   	echo '<td width=250>';
				?>		
						<div class="btn-group btn-group-sm">
							<button type="button" class="btn btn-default btn-sm btn-warning">
							  <span class="glyphicon glyphicon-edit"></span>
							  <a href="abo_update.php?id=<?php echo $row['id']; ?>"></a>
							</button>	
							<button type="button" class="btn btn-default btn-sm btn-danger">
							  <span class="glyphicon glyphicon-trash"></span>
							  <a href="abo_delete.php?id=<?php echo $row['id']; ?>"></a>
							</button>
						</div>

					   	</td>						
						</tr>
			<?php
				   } // Foreach	
			} // If
			?>
                </tbody>
            </table>           
			</div> 	<!-- /row -->
			
			<!-- Affiche le bouton retour -->        
			<p>
				<a href="abo.php" class="btn btn-success">Retour</a>
			</p>
			<!-- Test glyphicon -->
			<div class="btn-group btn-group-sm">
			<button type="button" class="btn btn-default btn-sm btn-warning">
			  <span class="glyphicon glyphicon-edit"></span>
			</button>	
			<button type="button" class="btn btn-default btn-sm btn-danger">
			  <span class="glyphicon glyphicon-trash"></span>
			</button>
			</div>
			<!-- Test Modal -->
			<!-- Button trigger modal -->
			<button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
			  Fenêtre popup
			</button>
			 
			<!-- Modal -->
			<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			  <div class="modal-dialog">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			        <h4 class="modal-title" id="myModalLabel">Titre</h4>
			      </div>
			      <div class="modal-body">
			        <h1>Test!</h1>
			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-default" data-dismiss="modal">Retour</button>
			        <button type="button" class="btn btn-primary">Ok</button>
			      </div>
			    </div><!-- /.modal-content -->
			  </div><!-- /.modal-dialog -->
			</div><!-- /.modal -->

			
        </div>  <!-- /span -->        			
    
    </div> <!-- /container -->
  </body>
</html>