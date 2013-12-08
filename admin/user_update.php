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
	$debug = true;

// Sécurisation POST & GET
    foreach ($_GET as $key => $value) {
        $sGET[$key]=htmlentities($value, ENT_QUOTES);
    }
    foreach ($_POST as $key => $value) {
        $sPOST[$key]=htmlentities($value, ENT_QUOTES);
    }
        	
// GET
	$id = null;
	if ( !empty($sGET['id'])) {
		$id = $sGET['id'];
	}
	
	if ( null==$id ) {
		header("Location: user.php");
	}

// POST	
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
		$password = $sPOST['password'];
		$nom = $sPOST['nom'];
		$prenom = $sPOST['prenom'];
		$email = $sPOST['email'];
		$telephone = $sPOST['telephone'];
		$inscription = $sPOST['inscription'];
		$expiration = $sPOST['expiration'];
		$montant = $sPOST['montant'];
        $administrateur = $sPOST['administrateur'];
		
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
					<?php Affiche_Champ($montant, $montantError, 'montant','Montant', 'text' ); ?>
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