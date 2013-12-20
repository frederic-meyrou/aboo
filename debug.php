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
    include_once 'database.php';
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
// Lecture tableau de bord

	// Requette pour calcul de la somme Annuelle			
		$sql1 = "(SELECT SUM(montant) FROM abonnement WHERE
	    		user_id = :userid AND exercice_id = :exerciceid )
	    		UNION
	    		(SELECT SUM(montant * -1) FROM depense WHERE
	    		user_id = :userid AND exercice_id = :exerciceid )
	    		";
	// Requette pour calcul de la somme	du mois en cours		
		$sql2 = "(SELECT SUM(montant) FROM abonnement WHERE
	    		user_id = :userid AND exercice_id = :exerciceid AND mois = :mois)
	    		UNION
	    		(SELECT SUM(montant * -1) FROM depense WHERE
	    		user_id = :userid AND exercice_id = :exerciceid AND mois = :mois )
	    		";
	// requette pour calcul des ventilations abo Annuelle
	    $sql3 = "SELECT SUM(mois_1),SUM(mois_2),SUM(mois_3),SUM(mois_4),SUM(mois_5),SUM(mois_6),SUM(mois_7),SUM(mois_8),SUM(mois_9),SUM(mois_10),SUM(mois_11),SUM(mois_12) FROM abonnement WHERE
	    		(user_id = :userid AND exercice_id = :exerciceid)
	    		";
				
    $q1 = array('userid' => $user_id, 'exerciceid' => $exercice_id);				
    $q3 = array('userid' => $user_id, 'exerciceid' => $exercice_id);
    
	$req = $pdo->prepare($sql1);
	$req->execute($q1);
	$data1 = $req->fetchAll(PDO::FETCH_ASSOC);
    $count = $req->rowCount($sql1);
	
	$req = $pdo->prepare($sql3);
	$req->execute($q3);
	$data3 = $req->fetch(PDO::FETCH_ASSOC);	
	
	if ($count!=0) { // Il n'y a rien en base sur l'année     
			
    		// Calcul des sommes de tout les mois
            for ($i = 1; $i <= 12; $i++) {
                // Requette BDD
                $q2 = array('userid' => $user_id, 'exerciceid' => $exercice_id, 'mois' => $i);
                $req = $pdo->prepare($sql2);
                $req->execute($q2);
                $data2 = $req->fetchAll(PDO::FETCH_ASSOC);
                // Calcul     
                $total_recettes_mois_{$i}= !empty($data2[0]["SUM(montant)"]) ? $data2[0]["SUM(montant)"] : 0; 
                $total_depenses_mois_{$i}= !empty($data2[1]["SUM(montant)"]) ? $data2[1]["SUM(montant)"] : 0;
                $solde_mois_{$i}= $total_recettes_mois_{$i} + $total_depenses_mois_{$i};               
            }    		      
			
			// Calcul des sommes ventillées
	        for ($i = 1; $i <= 12; $i++) { 
	        	$total_mois_{$i}= !empty($data3["SUM(mois_$i)"]) ? $data3["SUM(mois_$i)"] : 0;
			}	        

    }
	Database::disconnect();
		
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
      <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <!-- Marque -->
          <a class="navbar-brand" href="home.php">Aboo</a>
      </div>     
      <!-- Liens -->
      <div class="collapse navbar-collapse" id="TOP">
        <ul class="nav navbar-nav">
          <li><a href="journal.php"><span class="glyphicon glyphicon-th-list"></span> Recettes & Dépenses</a></li>
          <li class="active"><a href="bilan.php"><span class="glyphicon glyphicon-calendar"></span> Bilan</a></li>
          <li><a href="encaissements.php"><span class="glyphicon glyphicon-credit-card"></span> Encaissements</a></li>
          <li><a href="paiements.php"><span class="glyphicon glyphicon-euro"></span> Paiements</a></li>
          <li><a href="mesclients.php"><span class="glyphicon glyphicon-star"></span> Clients</a></li>                           
          <li class="dropdown">
	        <!-- Affiche le nom de l'utilisateur à droite de la barre de Menu -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> <?php echo ucfirst($prenom) . ' ' . ucfirst($nom); ?><b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li><a href="conf.php"><span class="glyphicon glyphicon-wrench"></span> Configuration</a></li>
              <li><a href="deconnexion.php"><span class="glyphicon glyphicon-off"></span> Deconnexion</a></li>  
            </ul> 
          </li>
          <li><a href="deconnexion.php"><span class="glyphicon glyphicon-off"></span></a></li>      
        </ul>
      </div><!-- /.navbar-collapse -->
    </nav>
        
    <div class="container">
     
       
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
            DATA1 Sommes annuelles:<br>
            <pre><?php var_dump($data1); ?></pre>
            DATA3 Ventilation:<br>
            <pre><?php var_dump($data3); ?></pre>                           
        </div>
        <?php       
        }   
        ?> 

             
    </div> <!-- /container -->
  </body>
</html>