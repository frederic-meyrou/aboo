<?php 
	
	require '../database.php';

	$id = null;
	if ( !empty($_GET['id'])) {
		$id = $_REQUEST['id'];
	}
	
	if ( null==$id ) {
		header("Location: user.php");
	}
	
	if ( !empty($_POST)) {
		// keep track validation errors
		$identifiantError = null;
		$passwordError = null;
		$nomError = null;
		$prenomError = null;
		$nomError = null;
		$emailError = null;
		$telephoneError = null;
		$inscriptionError = null;
		$expirationError = null;
		$montantError = null;
		
		// keep track post values
		$identifiant = $_POST['identifiant'];
		$password = $_POST['password'];
		$nom = $_POST['nom'];
		$prenom = $_POST['prenom'];
		$email = $_POST['email'];
		$telephone = $_POST['telephone'];
		$inscription = $_POST['inscription'];
		$expiration = $_POST['expiration'];
		$montant = $_POST['montant'];
		
		// validate input
		$valid = true;
		
		
		// update data
		if ($valid) {
			$pdo = Database::connect();
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = "UPDATE user set prenom=?,nom=?,email=?,telephone=?,identifiant=?,password=?,inscription=?,montant=?,expiration=? WHERE id = ?";
			$q = $pdo->prepare($sql);
			$q->execute(array($prenom, $nom, $email, $telephone, $identifiant, $password, $inscription, $montant, $expiration, $id));
			Database::disconnect();
			header("Location: user.php");
		}
	} else {
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "SELECT * FROM user where id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($id));
		$data = $q->fetch(PDO::FETCH_ASSOC);
		$identifiant = $data['identifiant'];
		$password = $data['password'];
		$nom = $data['nom'];
		$prenom = $data['prenom'];
		$email = $data['email'];
		$telephone = $data['telephone'];
		$inscription = $data['inscription'];
		$expiration = $data['expiration'];
		$montant = $data['montant'];		
		
		Database::disconnect();
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
		    			<h3>Mise à jour utilisateur</h3>
		    		</div>
					
	    			<form class="form-horizontal" action="user_update.php?id=<?php echo $id?>" method="post">
					<?php function Affiche_Champ(&$champ, &$champError, $champinputname, $champplaceholder ) { ?>
					<div class="control-group <?php echo !empty($champ)?'error':'';?>">
					    <label class="control-label"><?php echo "$champplaceholder" ?></label>
					    <div class="controls">
					      	<input name="<?php echo "$champinputname" ?>" type="text" placeholder="<?php echo "$champplaceholder" ?>" value="<?php echo !empty($champ)?$champ:'';?>">
					      	<?php if (!empty($champError)): ?>
					      		<span class="help-inline"><?php echo $champError;?></span>
					      	<?php endif; ?>
					    </div>
					</div>
					<?php } ?>
					
					<?php Affiche_Champ($identifiant, $identifiantError, 'identifiant','Identifiant' ); ?>
					<?php Affiche_Champ($password, $passwordError, 'password','Mot de passe' ); ?>
					<?php Affiche_Champ($nom, $nomError, 'nom','Nom' ); ?>
					<?php Affiche_Champ($prenom, $prenomError, 'prenom','Prénom' ); ?>
					<?php Affiche_Champ($email, $emailError, 'email','eMail' ); ?>
					<?php Affiche_Champ($telephone, $telephoneError, 'telephone','Téléphone' ); ?>
					<?php Affiche_Champ($inscription, $inscriptionError, 'inscription','Inscription' ); ?>
					<?php Affiche_Champ($expiration, $expirationError, 'expiration','Expiration' ); ?>
					<?php Affiche_Champ($montant, $montantError, 'montant','Montant' ); ?>
					
					<div class="form-actions">
						  <button type="submit" class="btn btn-success">Mise à jour</button>
						  <a class="btn btn-success" href="user.php">Retour</a>
						</div>
					</form>
				</div>
				
    </div> <!-- /container -->
  </body>
</html>