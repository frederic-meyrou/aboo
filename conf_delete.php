<?php
// Vérification de l'Authent
    session_start();
    require('authent.php');
    if( !Authent::islogged()){
        // Non authentifié on repart sur la HP
        header('Location:index.php');
    }


// Mode Debug
	$debug = true;
	
// Récupère l'ID de l'exercice à supprimer en GET	
	if ( !empty($_GET['id'])) {
		$id = $_REQUEST['id'];
		$annee = $_REQUEST['annee'];
	} else {
		// Redirection vers conf puisque on a rien à supprimer
		header('Location:conf.php');
	}
	
// Récupération des variables de session d'Authent
    $user_id = $_SESSION['authent']['id']; 

// Récupération des variables de session exercice
    $exercice_annee = null;
    $exercice_mois = null;
    $exercice_treso = null;
    if(isset($_SESSION['exercice'])) {
        $exercice_annee = $_SESSION['exercice']['annee'];
        $exercice_mois = $_SESSION['exercice']['mois'];
        $exercice_treso = $_SESSION['exercice']['treso'];
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

        // On modifie la session
        if ($annee == $exercice_annee) {
            $_SESSION['exercice'] = array();
        }            
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

        <!-- Affiche les informations de debug -->
        <?php 
 		if ($debug) {
		?>
        <div class="alert alert alert-error alert-dismissable fade in">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <strong>Informations de Debug : </strong><br>
            SESSION:<br>
            <pre><?php var_dump($_SESSION); ?></pre>
            POST:<br>
            <pre><?php var_dump($_POST); ?></pre>
            GET:<br>
            <pre><?php var_dump($_GET); ?></pre>
        </div>
        <?php       
        }   
        ?>  
        
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