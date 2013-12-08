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
	$debug = true;	

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
    <title>GestAbo</title>
    <meta charset="utf-8">
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" media="screen">
</head>

<body>
    <script src="../bootstrap/js/jquery-2.0.3.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <div class="container">
        <h2>Gestion des comptes utilisateur</h2>  	
        <ul class="nav nav-pills">
          <li class="active"><a href="user.php">Administration GestAbo</a></li>
          <li><a href="../deconnexion.php">Deconnexion</a></li>        
        </ul>
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