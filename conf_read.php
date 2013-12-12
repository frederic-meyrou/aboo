<?php
	require_once('fonctions.php');
// Vérification de l'Authent
    session_start();
    require('authent.php');
    if( !Authent::islogged()){
        // Non authentifi� on repart sur la HP
        header('Location:index.php');
    }

// Récupère l'annee de l'exercice à supprimer en GET
	if ( !empty($_GET['annee'])) {
		$annee = $_REQUEST['annee'];
	} else {
		// Redirection vers conf puisque on a rien � afficher
		header('Location:conf.php');
	}
	
// Récupération des variables de session d'Authent
    $user_id = $_SESSION['authent']['id']; 
	
// Initialisation de la base
    include_once 'database.php';
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    

// Lecture dans la base de l'exercice 
    $sql = "SELECT * FROM exercice WHERE user_id = ? AND annee_debut = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($user_id,$annee));
    $data = $q->fetch(PDO::FETCH_ASSOC);
    $count = $q->rowCount($sql);
    if ($count==0) { // Pas d'exercice ds la BDD
        Database::disconnect();
        // Redirection
        header('Location:conf.php');                
    } else {
		$id = $data['id'];
		$mois = $data['mois_debut'];
		$treso = $data['montant_treso_initial'];
	}	  
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
        <ul class="nav nav-pills">
          <li><a href="home.php">Console</a></li>
          <li><a href="abodep.php">Abonnements & Dépenses</a></li>
          <li><a href="meusuel.php">Bilan Mensuel</a></li>
          <li><a href="bilan.php">Bilan Annuel</a></li>
          <li><a href="encaissements.php">Encaissements</a></li>
          <li><a href="paiements.php">Paiements</a></li>
          <li class="active"><a href="conf.php">Configuration</a></li>
          <li><a href="deconnexion.php">Deconnexion</a></li>
        </ul>

        <div class="span10 offset1">
            <div class="row">
                <h3>Consultation de l'exercice</h3>
            </div>    
            <div class="row">
               
                <table class="table table-striped table-bordered table-hover success">
                      <thead>
                        <tr>
						
                          <th>Années exercice</th>
                          <th>Mois de démarrage</th>
                          <th>Montant de trésorerie de départ</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php 
                                echo '<tr>';
                                echo '<td>'. $annee . ' - ' . ($annee + 1) . '</td>';
                                echo '<td>'. NumToMois($mois) . '</td>';
                                echo '<td>'. $treso . '</td>';
                                echo '</tr>';
                      ?>
                      </tbody>
                </table>
            </div> 	<!-- /row -->
 		  	<a class="btn btn-success" href="conf.php">Retour</a>
        </div>  <!-- /span -->        			

    </div> <!-- /container -->
  </body>
</html>