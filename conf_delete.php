<?php
// Vérification de l'Authent
    session_start();
    require('authent.php');
    if( !Authent::islogged()){
        // Non authentifié on repart sur la HP
        header('Location:index.php');
    }
// Récupère l'ID de l'exercice � supprimer en GET	
	if ( !empty($_GET['id'])) {
		$id = $_REQUEST['id'];
	} else {
		// Redirection vers conf puisque on a rien � supprimer
		header('Location:conf.php');
	}

// Lecture et validation du POST
	if ( !empty($_POST)) {
		// keep track post values
		$id = $_POST['id'];
		
		// delete data
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "DELETE FROM exercice WHERE id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($id));
		Database::disconnect();
		header("Location: conf.php");
		
	} 
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
          <li><a href="home.php">Console</a></li>
          <li><a href="abodep.php">Editer abonnements et d�penses</a></li>
          <li><a href="meusuel.php">Bilan Mensuel</a></li>
          <li><a href="bilan.php">Bilan Annuel</a></li>
          <li><a href="encaissements.php">Encaissements</a></li>
          <li><a href="paiements.php">Paiements</a></li>
          <li class="active"><a href="conf.php">Configuration</a></li>
          <li><a href="deconnexion.php">Deconnexion</a></li>
        </ul>
		<div class="span10 offset1">
			<div class="row">
    			<h3>Suppression d'un exercice</h3>
    		</div>
			
			<form class="form-horizontal" action="conf_delete.php" method="post">
			  <input type="hidden" name="id" value="<?php echo $id;?>"/>
			  <p class="alert alert-error">Confirmation de la suppression ?</p>
				<div class="form-actions">
				  <button type="submit" class="btn btn-danger">Oui</button>
				  <a class="btn" href="conf.php">Non</a>
				</div>
			</form>
			
		</div> <!-- /span10 -->		
    </div> <!-- /container -->
  </body>
</html>