<!DOCTYPE html>
<html lang="fr">
<head>
    <title>GestAbo</title>
    <meta charset="utf-8">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" media="screen">
</head>

<body>
    <?php
           $id = null; 
           $password = null;
           $identifiant = null;
    ?>
    <script src="bootstrap/js/jquery-2.0.3.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <div class="container">
        <h2>HP GestAbo</h2>
      	<ul class="nav nav-pills">
          <li class="active"><a href="index.php">HomePage</a></li>
          <li><a href="connexion.php">Connexion</a></li>
          <li><a href="crud/index.php">Exemple CRUD</a></li>
        </ul>

        <div class="row">
			<p>
				<a href="admin/user.php" class="btn btn-success">Gestion des comptes Utilisateur</a>
			</p>
    	</div>
    	<?php
           if ( !empty($_POST['identifiant'])) {
                            $identifiant = $_REQUEST['identifiant'];
                            $password = $_REQUEST['password'];
                            include 'database.php';
                            $pdo = Database::connect();
                            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            $sql = "SELECT * FROM user where identifiant = ?";
                            $q = $pdo->prepare($sql);
                            $q->execute(array($identifiant));
                            $data = $q->fetch(PDO::FETCH_ASSOC);
                            $id = $data['id'];    
                            $passwordBD = $data['password'];
                            $nom = $data['nom'];
                            $prenom = $data['prenom'];
                            $expiration = $data['expiration'];
                            Database::disconnect();
          
                              // Test si identifiant existe
                              if ( $id==null) {
                              ?>
                                 <div class="alert alert alert-fail alert-dismissable fade in">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <strong>Bonjour je ne connais pas cet identifiant : <?php echo "$identifiant" ?>.</strong>
                                </div>        
                              <?php               
                              } elseif ( "$password"=="$passwordBD" ) {  // Test si me mot de passe est valide 
                              ?>
                                 <div class="alert alert alert-success alert-dismissable fade in">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <strong>Bonjour <?php echo "$prenom $nom" ?>!</strong> Bienvenue sur l'application GestAbo.
                                </div>
                            <?php
                              } else { // Tester si expiration > now()
                            ?>
                                 <div class="alert alert alert-fail alert-dismissable fade in">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <strong>Bonjour <?php echo "$prenom $nom" ?> : Votre mot de passe est invalide.</strong>
                                </div>        
                            <?php 
                              }
           }
        ?>
    </div> <!-- /container -->
  </body>
</html>