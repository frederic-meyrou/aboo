<?php
session_start();
require('authent.php');
if( !Authent::islogged()){
    // Non authentifié on repart sur la HP
    header('Location:index.php');
}
// Récupération des variables de session
$id = $_SESSION['authent']['id']; 
$nom = $_SESSION['authent']['nom'];
$prenom = $_SESSION['authent']['prenom'];
$nom = $_SESSION['authent']['nom'];
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
        <h2>Console</h2>
        <ul class="nav nav-pills">
          <li class="active"><a href="home.php">Console</a></li>
          <li><a href="abodep.php">Editer abonnements et dépenses</a></li>
          <li><a href="meusuel.php">Bilan Mensuel</a></li>
          <li><a href="bilan.php">Bilan Annuel</a></li>
          <li><a href="encaissements.php">Encaissements</a></li>
          <li><a href="paiements.php">Paiements</a></li>
          <li><a href="conf.php">Configuration</a></li>
          <li><a href="deconnexion.php">Deconnexion</a></li>
        </ul>

        <div class="alert alert alert-success alert-dismissable fade in">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <strong>Bonjour <?php echo "$prenom $nom"; ?> !</strong> Bienvenue sur ton espace sécurisé GestAbo.
        </div>
    
    </div> <!-- /container -->
  </body>
</html>