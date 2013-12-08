<?php 
// Vérification de l'Authent
    session_start();
    require('../authent.php');
    if( !Authent::islogged()){
        // Non authentifié on repart sur la HP
        header('Location:../index.php');
    }
		
// Dépendances
	require_once('../database.php');
	require_once('../fonctions.php');

// Mode Debug
	$debug = true;

// Sécurisation POST & GET
    foreach ($_GET as $key => $value) {
        $sGET[$key]=htmlentities($value, ENT_QUOTES);
    }
    foreach ($_POST as $key => $value) {
        $sPOST[$key]=htmlentities($value, ENT_QUOTES);
    }
        	
// GET	
	$id = 0;
	
	if ( !empty($sGET['id'])) {
		$id = $sGET['id'];
	}

// POST	
	if ( !empty($sPOST)) {
		// keep track post values
		$id = $sPOST['id'];
		
		// delete data
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "DELETE FROM user WHERE id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($id));
		Database::disconnect();
		header("Location: user.php");
		
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
    			<div class="span10 offset1">
    				<div class="row">
		    			<h3>Suppression Utilisateur</h3>
		    		</div>

        
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
			        </div>
			        <?php       
			        }   
			        ?>   		    		
		    		
	    			<form class="form-horizontal" action="user_delete.php" method="post">
	    			  <input type="hidden" name="id" value="<?php echo $id;?>"/>
					  <p class="alert alert-danger">Confirmation de la suppression ?</p>
					  <div class="form-actions">
						  <button type="submit" class="btn btn-danger">Yes</button>
						  <a class="btn" href="user.php">No</a>
						</div>
					</form>
				</div>
				
    </div> <!-- /container -->
  </body>
</html>