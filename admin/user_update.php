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
		$passwordError = null;
		$nomError = null;
		$prenomError = null;
		$nomError = null;
		$emailError = null;
		$telephoneError = null;
		$inscriptionError = null;
		$expirationError = null;
		$montantError = null;
        $adminError = null;
        		
		// keep track post values
		$password = $_POST['password'];
		$nom = $_POST['nom'];
		$prenom = $_POST['prenom'];
		$email = $_POST['email'];
		$telephone = $_POST['telephone'];
		$inscription = $_POST['inscription'];
		$expiration = $_POST['expiration'];
		$montant = $_POST['montant'];
        $administrateur = $_POST['administrateur'];
		
		// validate input
		$valid = true;		
		
		// update data
		if ($valid) {
			$pdo = Database::connect();
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = "UPDATE user set prenom=?,nom=?,email=?,telephone=?,password=?,inscription=?,montant=?,expiration=?,administrateur=? WHERE id = ?";
			$q = $pdo->prepare($sql);
			$q->execute(array($prenom, $nom, $email, $telephone, $password, $inscription, $montant, $expiration, $administrateur, $id));
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
        $email = $data['email'];
		$password = $data['password'];
		$nom = $data['nom'];
		$prenom = $data['prenom'];
		$telephone = $data['telephone'];
		$inscription = $data['inscription'];
		$expiration = $data['expiration'];
        $montant = $data['montant'];        
        $administrateur = $data['administrateur'];        
		
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
                    <?php function Affiche_Champ(&$champ, &$champError, $champinputname, $champplaceholder, $type) { ?>
                    <div class="control-group <?php echo !empty($champError)?'error':'';?>">
                        <label class="control-label"><?php echo "$champplaceholder" ?></label>
                        <div class="controls">
                            <input name="<?php echo "$champinputname" ?>" type="<?php echo "$type" ?>" value="<?php echo !empty($champ)?$champ:'';?>">
                            <?php if (!empty($champError)): ?>
                                <span class="help-inline"><?php echo $champError;?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php } ?>
					
                    <?php Affiche_Champ($email, $emailError, 'email','eMail', 'mail' ); ?>
					<?php Affiche_Champ($password, $passwordError, 'password','Mot de passe', 'password' ); ?>
					<?php Affiche_Champ($nom, $nomError, 'nom','Nom', 'text' ); ?>
					<?php Affiche_Champ($prenom, $prenomError, 'prenom','Prénom', 'text' ); ?>
					<?php Affiche_Champ($telephone, $telephoneError, 'telephone','Téléphone', 'tel' ); ?>
					<?php Affiche_Champ($inscription, $inscriptionError, 'inscription','Inscription', 'date' ); ?>
					<?php Affiche_Champ($expiration, $expirationError, 'expiration','Expiration', 'date' ); ?>
					<?php Affiche_Champ($montant, $montantError, 'montant','Montant', 'number' ); ?>
                    <?php Affiche_Champ($administrateur, $adminError, 'administrateur','Administrateur', 'number' ); ?>
                    					
					    <div class="form-actions">
						  <button type="submit" class="btn btn-success">Mise à jour</button>
						  <a class="btn btn-success" href="user.php">Retour</a>
						</div>
					</form>
				</div>
				
    </div> <!-- /container -->
  </body>
</html>