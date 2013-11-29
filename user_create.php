<?php 
	
	require 'database.php';

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
		if (empty($identifiant)) {
			$identifiantError = 'Veuillez entrer un indentifiant';
			$valid = false;
		}
		if (empty($password)) {
			$passwordError = 'Veuillez entrer un mot de passe';
			$valid = false;
		}
		if (empty($nom)) {
			$nomError = 'Veuillez entrer un nom';
			$valid = false;
		}
		if (empty($prenom)) {
			$prenomError = 'Veuillez entrer un prénom';
			$valid = false;
		}
		if (empty($email)) {
			$emailError = 'Veuillez entrer votre adresse Email';
			$valid = false;
		} else if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
			$emailError = 'Veuillez entrer une adresse eMail valide';
			$valid = false;
		}
		if (empty($telephone)) {
			$telephoneError = 'Veuillez entrer un numéro de téléphone';
			$valid = false;
		}
		if (empty($inscription)) {
			$inscription = 'Veuillez entrer un nom';
			$valid = false;
		}
	
		// insert data
		if ($valid) {
			$pdo = Database::connect();
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = "INSERT INTO customers (prenom,nom,email,telephone,identifiant,password,inscription,montant,expiration) values(?, ?, ?, ?, ?, ?, ?, ?, ?)";
			$q = $pdo->prepare($sql);
			$q->execute(array($prenom, $nom, $email, $telephone, $identifiant, $password, $inscription, $montant, $expiration));
			Database::disconnect();
			header("Location: user.php");
		}
	}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <link   href="bootstrap.css" rel="stylesheet">
    <script src="bootstrap.js"></script>
</head>

<body>
    <div class="container">
    
    			<div class="span10 offset1">
    				<div class="row">
		    			<h3>Création d'un utilisateur</h3>
		    		</div>
		
	    			<form class="form-horizontal" action="create.php" method="post">
					  <div class="control-group <?php echo !empty($identifiantError)?'error':'';?>">
					    <label class="control-label">Identifiant</label>
					    <div class="controls">
					      	<input name="identifiant" type="text"  placeholder="Identifiant" value="<?php echo !empty($identifiant)?$identifiant:'';?>">
					      	<?php if (!empty($identifiantError)): ?>
					      		<span class="help-inline"><?php echo $identifiantError;?></span>
					      	<?php endif; ?>
					    </div>
					  </div>
					  <div class="control-group <?php echo !empty($passwordError)?'error':'';?>">
					    <label class="control-label">Mot de passe</label>
					    <div class="controls">
					      	<input name="password" type="text" placeholder="Mot de passe" value="<?php echo !empty($password)?$password:'';?>">
					      	<?php if (!empty($passwordError)): ?>
					      		<span class="help-inline"><?php echo $passwordError;?></span>
					      	<?php endif;?>
					    </div>
					  </div>
					  <div class="control-group <?php echo !empty($nomError)?'error':'';?>">
					    <label class="control-label">Nom</label>
					    <div class="controls">
					      	<input name="nom" type="text"  placeholder="Nom" value="<?php echo !empty($nom)?$nom:'';?>">
					      	<?php if (!empty($nomError)): ?>
					      		<span class="help-inline"><?php echo $nomError;?></span>
					      	<?php endif;?>
					    </div>
					  <div class="control-group <?php echo !empty($prenomError)?'error':'';?>">
					    <label class="control-label">Prénom</label>
					    <div class="controls">
					      	<input name="prenom" type="text"  placeholder="Mobile Number" value="<?php echo !empty($prenom)?$prenom:'';?>">
					      	<?php if (!empty($mobileError)): ?>
					      		<span class="help-inline"><?php echo $prenomError;?></span>
					      	<?php endif;?>
					    </div>


						
					  </div>
					  <div class="form-actions">
						  <button type="submit" class="btn btn-success">Créer</button>
						  <a class="btn btn-success" href="user.php">Retour</a>
						</div>
					</form>
				</div>
				
    </div> <!-- /container -->
  </body>
</html>