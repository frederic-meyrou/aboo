<!-- 
© Copyright : Aboo / www.aboo.fr : Frédéric MEYROU : tous droits réservés
-->
<?php
// Vérification de l'Authent
    session_start();
    require('lib/authent.php');
    if( !Authent::islogged()){
        // Non authentifié on repart sur la HP
        header('Location:index.php');
    }

// Dépendances
	require_once('lib/fonctions.php');

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
    $exercice_id = null;
    $exercice_annee = null;
    $exercice_mois = null;
    $exercice_treso = null;
    if(isset($_SESSION['exercice'])) {
        $exercice_id = $_SESSION['exercice']['id'];
        $exercice_annee = $_SESSION['exercice']['annee'];
        $exercice_mois = $_SESSION['exercice']['mois'];
        $exercice_treso = $_SESSION['exercice']['treso'];
    }

// Récupération des variables de session abodep
    $abodep_mois = null;
    if(isset($_SESSION['abodep'])) {
        $abodep_mois = $_SESSION['abodep']['mois'];
    }

// Initialisation de la base
    include_once 'lib/database.php';
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
// Lecture BDD

	// Requette pour calcul de la somme Annuelle			
	$sql = "SELECT * FROM client WHERE
    		user_id = :userid
    		";

    $q = array('userid' => $user_id);	
    
	$req = $pdo->prepare($sql);
	$req->execute($q);
	$data = $req->fetchAll(PDO::FETCH_ASSOC);
    $count = $req->rowCount($sql);
	
	if ($count==0) { // Il n'y a rien en base sur l'année
        $affiche = false;         
    } else {
        // On affiche le tableau
        $affiche = true;
    }
	Database::disconnect();		
?>

<!DOCTYPE html>
<html lang="fr">
<?php require 'head.php'; ?>

<body>

    <?php $page_courante = "mesclients.php"; require 'nav.php'; ?>
        
    <div class="container">
        <h2>Gestion de mes clients</h2>       
        <br>
		<p>
			<a href="client_create.php" class="btn btn-primary"><span class="glyphicon glyphicon-plus-sign"></span> Création d'un client</a>
  			<a href="#" class="btn btn-primary"><span class="glyphicon glyphicon-list-alt"></span> Envoi d'un eMail de relance</a>			
  			<a href="#" class="btn btn-primary"><span class="glyphicon glyphicon-list-alt"></span> Export Excel</a>
  			<a href="#" class="btn btn-primary"><span class="glyphicon glyphicon-briefcase"></span> Export PDF</a>
		</p>
				             
        <!-- Affiche les informations de debug -->
        <?php 
 		if ($debug) {
		?>
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
        <?php       
        }   
        ?> 

		<!-- Affiche la table en base sous condition -->
		<div class="span10">
			<?php 
 			if ($affiche) {
			?>
				<table class="table table-striped table-bordered table-hover success">
		              <thead>
		                <tr>
						  <th>Prénom</th>
		                  <th>Nom</th>
                          <th>eMail</th>
		                  <th>Téléphone Fixe</th>
		                  <th>Téléphone Mobile</th>
		                  <th>Age</th>
		                  <th>Actions</th>
		                </tr>
		              </thead>
		              <tbody>
		              <?php	
	 				   foreach ($data as $row) {
						   		echo '<tr>';
								echo '<td>'. ucfirst($row['prenom']) . '</td>';
							   	echo '<td>'. ucfirst($row['nom']) . '</td>';
								echo '<td>'. $row['email'] . '</td>';
								echo '<td>'. $row['telephone'] . '</td>';
								echo '<td>'. $row['mobile'] . '</td>';
								echo '<td>'. $row['age'] . '</td>';
							   	echo '<td width=130>';
					  ?>	
								<div class="btn-group btn-group-sm">
									  	<a href="client_details.php?id=<?php echo $row['id']; ?>" class="btn btn-default btn-sm btn-info glyphicon glyphicon-star" role="button"> </a>
									  	<a href="client_update.php?id=<?php echo $row['id']; ?>" class="btn btn-default btn-sm btn-warning glyphicon glyphicon-edit" role="button"> </a>
									  	<a href="client_delete.php?id=<?php echo $row['id']; ?>" class="btn btn-default btn-sm btn-danger glyphicon glyphicon-trash" role="button"> </a>
								</div>
								
							   	</td>						
								</tr>
					  <?php								                             
					   }
					  ?>
				      </tbody>
	            </table>
			</div> 	<!-- /row -->
			<?php 	
			} // if
			?>
        </div>  <!-- /span -->        	        
             
    </div> <!-- /container -->

    <?php require 'footer.php'; ?>
        
  </body>
</html>