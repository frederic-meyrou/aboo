<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="bootstrap.css" rel="stylesheet" media="screen">
</head>

<body>
    <h1>Gestion des comptes utilisateur</h1>
    <script src="jquery.js"></script>
    <script src="bootstrap.js"></script>
    <div class="container">
    	
			<div class="row">
				<p>
					<a href="index.php" class="btn btn-success">HP</a>
				</p>
				<p>
					<a href="user_create.php" class="btn btn-success">Création d'un compte Utilisateur</a>
				</p>
				
				<table class="table table-striped table-bordered table-hover success">
		              <thead>
		                <tr>
		                  <th>Identifiant</th>
		                  <th>Mot de passe</th>
		                  <th>Nom</th>
						  <th>Prénom</th>
		                  <th>eMail</th>
		                  <th>Téléphone</th>
		                  <th>Date inscription</th>
		                  <th>Date expiration</th>
		                  <th>Montant abonnement</th>
		                  <th>Action</th>
		                </tr>
		              </thead>
		              <tbody>
		              <?php 
					   include 'database.php';
					   $pdo = Database::connect();
					   $sql = 'SELECT * FROM user ORDER BY id DESC';
	 				   foreach ($pdo->query($sql) as $row) {
						   		echo '<tr>';
							   	echo '<td>'. $row['identifiant'] . '</td>';
							   	echo '<td>'. $row['password'] . '</td>';
							   	echo '<td>'. $row['nom'] . '</td>';
								echo '<td>'. $row['prenom'] . '</td>';
							   	echo '<td>'. $row['email'] . '</td>';
								echo '<td>'. $row['telephone'] . '</td>';
							   	echo '<td>'. $row['inscription'] . '</td>';
								echo '<td>'. $row['expiration'] . '</td>';
							   	echo '<td>'. $row['montant'] . '</td>';
							   	echo '<td width=250>';
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