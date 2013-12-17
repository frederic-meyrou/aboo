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
    <title>Aboo</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" media="screen">
    <link href="bootstrap/css/aboo.css" rel="stylesheet">
    <link rel='stylesheet' id='google_fonts-css'  href='http://fonts.googleapis.com/css?family=PT+Sans|Lato:300,400|Lobster|Quicksand' type='text/css' media='all' />
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>

<body>
    
    <script src="bootstrap/js/jquery-2.0.3.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>

    <!-- Affiche la navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">      
      <!-- Marque -->
      <div class="navbar-header">
        <a class="navbar-brand" href="home.php">Aboo</a>
      </div>      
      <!-- Liens -->
      <div class="collapse navbar-collapse" id="TOP">
        <ul class="nav navbar-nav">
          <li><a href="abodep.php">Abonnements & Dépenses</a></li>
          <li><a href="meusuel.php">Bilan Mensuel</a></li>
          <li><a href="bilan.php">Bilan Annuel</a></li>
          <li><a href="encaissements.php">Encaissements</a></li>
          <li><a href="paiements.php">Paiements</a></li>
          <li class="active"><a href="conf.php">Configuration</a></li>
          <li><a href="deconnexion.php">Deconnexion</a></li>                    
           <!--<li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Menu Dropdown <b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li><a href="#">Action</a></li>
              <li class="divider"></li>
              <li><a href="#">Action</a></li>
            </ul>
          </li>-->      
        </ul>
      </div><!-- /.navbar-collapse -->    
    </nav>
        
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
 		  	<a class="btn btn-success" href="conf.php">Retour</a>
        </div>  <!-- /span -->        			

    </div> <!-- /container -->
  </body>
</html>