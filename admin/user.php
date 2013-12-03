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
			<div class="row">
				<p>
					<a href="user_create.php" class="btn btn-success">Création d'un compte Utilisateur</a>
				</p>
				
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
					   include '../database.php';
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
							   	echo '<td width=250>';
                                echo '<a class="btn " href="user_read.php?id='.$row['id'].'">Lire</a>';
                                echo '&nbsp;';                                
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