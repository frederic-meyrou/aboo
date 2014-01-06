<!-- 
© Copyright : Aboo / www.aboo.fr : Frédéric MEYROU : tous droits réservés
-->
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

// Récupération des variables de session d'Authent
    $user_id = $_SESSION['authent']['id']; 
    $nom = $_SESSION['authent']['nom'];
    $prenom = $_SESSION['authent']['prenom'];
	
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
<?php require 'head.php'; ?>

<body>

    <?php $page_courante = "user.php"; require 'nav.php'; ?>  
    
    <div class="container">
        <h2>Gestion des comptes utilisateur</h2>  	
        	<br>
			<div class="row">
				<p>
					<a href="user_create.php" class="btn btn-primary"><span class="glyphicon glyphicon-plus-sign"></span> Création d'un compte Utilisateur</a>
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
                          <th>Admin</th>  		                  
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
                                echo '<td>'; echo ($row['administrateur']==1)?'Oui':'Non' . '</td>';                                
							   	echo '<td width=90>';
					  ?>	
								<div class="btn-group btn-group-sm">
									  	<a href="user_update.php?id=<?php echo $row['id']; ?>" class="btn btn-default btn-sm btn-warning glyphicon glyphicon-edit" role="button"> </a>
									  	<a href="user_delete.php?id=<?php echo $row['id']; ?>" class="btn btn-default btn-sm btn-danger glyphicon glyphicon-trash" role="button"> </a>
								</div>
								
							   	</td>						
								</tr>
					  <?php								                             
					   }
					   Database::disconnect();
					  ?>
				      </tbody>
	            </table>
    	</div>
    </div> <!-- /container -->
    
    <?php require 'footer.php'; ?>    
    
  </body>
</html>