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
        // Non authentifi� on repart sur la HP
        header('Location:../index.php');
    }

// Récupération des variables de session
	include_once('lib/var_session.php');
	
// Récupère l'annee de l'exercice à supprimer en GET
	if ( !empty($_GET['annee'])) {
		$annee = $_REQUEST['annee'];
	} else {
		// Redirection vers conf puisque on a rien � afficher
		header('Location:exercice.php');
	}
	
// Initialisation de la base
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
        header('Location:exercice.php');                
    } else {
		$id = $data['id'];
		$mois = $data['mois_debut'];
		$treso = $data['montant_treso_initial'];
	}	  
?>

<!DOCTYPE html>
<html lang="fr">
<?php require 'head.php'; ?>

<body>

    <?php $page_courante = "exercice.php"; require 'nav.php'; ?>
        
    <div class="container">

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
 		  	<a class="btn btn-success" href="exercice.php">Retour</a>
        </div>  <!-- /span -->        			

    </div> <!-- /container -->

    <?php require 'footer.php'; ?>
        
  </body>
</html>