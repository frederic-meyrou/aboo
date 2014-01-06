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

// Vérification du GET
    $id = null;
    if ( !empty($sGET['id'])) {
        $id = $sGET['id'];
    }  	

// Initialisation de la base
    include_once 'database.php';
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
// Lecture BDD
	
	// Lecture du detail client
	$sql = "SELECT * FROM client WHERE
    		id = :id
    		";
    $q = array('id' => $id);	
	$req = $pdo->prepare($sql);
	$req->execute($q);
	$data = $req->fetch(PDO::FETCH_ASSOC);
	// Lecture du CA 
	$sql2 = "SELECT SUM(montant) FROM abonnement WHERE
    		client_id = :id AND user_id = :user_id
    		";
    $q2 = array('id' => $id, 'user_id' => $user_id);	
	$req2 = $pdo->prepare($sql2);
	$req2->execute($q2);
	$data2 = $req2->fetch(PDO::FETCH_ASSOC);
	// Lecture des abonnements
	$sql3 = "SELECT * FROM abonnement WHERE
    		client_id = :id AND user_id = :user_id AND type = '1'
    		";
    $q3 = array('id' => $id, 'user_id' => $user_id);	
	$req3 = $pdo->prepare($sql3);
	$req3->execute($q3);
	$data3 = $req3->fetchAll(PDO::FETCH_ASSOC);
	$count3 = $req3->rowCount($sql3);
	// Lecture des reventes
	$sql4 = "SELECT * FROM abonnement WHERE
    		client_id = :id AND user_id = :user_id AND type = '2'
    		";
    $q4 = array('id' => $id, 'user_id' => $user_id);	
	$req4 = $pdo->prepare($sql4);
	$req4->execute($q4);
	$data4 = $req4->fetchAll(PDO::FETCH_ASSOC);
	$count4 = $req4->rowCount($sql4);
	
	
	
	Database::disconnect();		
?>

<!DOCTYPE html>
<html lang="fr">
<?php require 'head.php'; ?>

<body>

    <?php $page_courante = "mesclients.php"; require 'nav.php'; ?>
        
    <div class="container">
        <h2>Détails d'un client</h2>       
        <br>
		<p>
	 	    <a href="mesclients.php" class="btn btn-primary" ><span class="glyphicon glyphicon-chevron-up"></span> Retour</a>			
  			<a href="#" class="btn btn-primary"><span class="glyphicon glyphicon-list-alt"></span> Envoi d'un eMail</a>			
  			<a href="#" class="btn btn-primary"><span class="glyphicon glyphicon-list-alt"></span> Envoi d'un eMail de relance</a>			
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

		<!-- Affiche le detail d'un client -->
        <h2>Détails de : <?php echo ucfirst($data['prenom']) . ' '. ucfirst($data['nom']); ?></h2>       

    	<div class="row">

			<div class="col-sm-4">
				
            <!-- Coordonnées -->    				
              <div class="panel panel-success">
                <div class="panel-heading">
                  <h3 class="panel-title">Coordonnées</h3>
                </div>
                <div class="panel-body">
			          <?php	
								
								echo 'eMail : '. $data['email'] . '<br>';
								echo 'Téléphone Fixe : '. $data['telephone'] . '</br>'; 
								echo 'Mobile : '. $data['mobile']; 
					  ?>
                </div>
              </div>
           
              <!-- Adresse -->
              <div class="panel panel-success">
                <div class="panel-heading">
                  <h3 class="panel-title">Adresse</h3>
                </div>
                <div class="panel-body">
			          <?php	
								echo $data['adresse1'] . '</br>' . $data['adresse2'] . '</br>';
							   	echo $data['cp'] . '  '. $data['ville'];
					  ?>
                </div>
              </div>
              
            </div> <!-- /col -->  
                 			
			<div class="col-sm-4 col-md-offset-0">

              <!-- Statistiques -->
              <div class="panel panel-warning">
                <div class="panel-heading">
                  <h3 class="panel-title">Statistiques</h3>
                </div>
                <div class="panel-body">
			          <?php
			          		// CA
			          		echo 'CA : ';
							echo ($data2["SUM(montant)"] == null)?'0':$data2["SUM(montant)"];
							echo ' € <br>';
							// Nombre d'abonnements
							echo "Nombre d'abonnements : $count3" .	'<br>';
							// Nombre de reventes
							echo "Nombre de ventes : $count4" .	'<br>';
					  ?>
                </div>
              </div>
              
              <!-- Information diverses -->
              <div class="panel panel-info">
                <div class="panel-heading">
                  <h3 class="panel-title">Informations diverses</h3>
                </div>
                <div class="panel-body">
			          <?php	
								echo 'Age : '. $data['age'] . '<br>';
							   	echo 'Profession : '. $data['profession'] . '<br>';
							   	echo 'Description : '. $data['description'];
					  ?>
                </div>
              </div>
              
            </div> <!-- /col -->
		</div> 	<!-- /row -->
             
    </div> <!-- /container -->

    <?php require 'footer.php'; ?>
        
  </body>
</html>