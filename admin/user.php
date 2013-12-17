<?php
// Vérification de l'Authent
    session_start();
    require('../authent.php');
    if( !Authent::islogged()){
        // Non authentifié on repart sur la HP
        header('Location:../index.php');
    }

// Dépendances
	require_once('../fonctions.php');
	require_once('../database.php');
	
// Mode Debug
	$debug = false;	

// Sécurisation POST & GET
    foreach ($_GET as $key => $value) {
        $sGET[$key]=htmlentities($value, ENT_QUOTES);
    }
    foreach ($_POST as $key => $value) {
        $sPOST[$key]=htmlentities($value, ENT_QUOTES);
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Aboo</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" media="screen">
    <link href="../bootstrap/css/aboo.css" rel="stylesheet">
    <link rel='stylesheet' id='google_fonts-css'  href='http://fonts.googleapis.com/css?family=PT+Sans|Lato:300,400|Lobster|Quicksand' type='text/css' media='all' />
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>

<body>
    
    <script src="../bootstrap/js/jquery-2.0.3.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>

    <!-- Affiche la navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">      
      <!-- Marque -->
      <div class="navbar-header">
        <a class="navbar-brand" href="user.php">Aboo</a>
      </div>      
      <!-- Liens -->
      <div class="collapse navbar-collapse" id="TOP">
        <ul class="nav navbar-nav">
          <li class="active"><a href="user.php">Gestion utilisateurs</a></li>
          <li><a href="../deconnexion.php">Deconnexion</a></li>                   
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
        <h2>Gestion des comptes utilisateur</h2>  	
        	<br>
			<div class="row">
				<p>
					<a href="user_create.php" class="btn btn-success">Création d'un compte Utilisateur</a>
				</p>
				
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
		       				
				
				<table class="table table-striped table-bordered table-hover success">
		              <thead>
		                <tr>
                          <th>eMail</th>
		                  <th>Mot de passe</th>
		                  <th>Nom</th>
						  <th>Prénom</th>
		                  <th>Téléphone</th>
		                  <th>Date inscription</th>
		                  <th>Date expiration</th>
		                  <th>Montant abonnement</th>
                          <th>Administrateur</th>  		                  
		                  <th>Action</th>
		                </tr>
		              </thead>
		              <tbody>
		              <?php 
					   $pdo = Database::connect();
					   $sql = 'SELECT * FROM user ORDER BY id DESC';
	 				   foreach ($pdo->query($sql) as $row) {
						   		echo '<tr>';
                                echo '<td>'. $row['email'] . '</td>';
							   	echo '<td>'. $row['password'] . '</td>';
							   	echo '<td>'. $row['nom'] . '</td>';
								echo '<td>'. $row['prenom'] . '</td>';
								echo '<td>'. $row['telephone'] . '</td>';
							   	echo '<td>'. $row['inscription'] . '</td>';
								echo '<td>'. $row['expiration'] . '</td>';
							   	echo '<td>'. $row['montant'] . '</td>';
                                echo '<td>'. $row['administrateur'] . '</td>';                                  
							   	echo '<td width=200>';                             
							   	echo '<a class="btn btn-success" href="user_update.php?id='.$row['id'].'">Modifier</a>';
							   	echo '&nbsp;';
							   	echo '<a class="btn btn-danger" href="user_delete.php?id='.$row['id'].'">Supprimer</a>';
							   	echo '</td>';
							   	echo '</tr>';
					   }
					   Database::disconnect();
					  ?>
				      </tbody>
	            </table>
    	</div>
    </div> <!-- /container -->
  </body>
</html>