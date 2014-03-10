<!-- 
© Copyright : Aboo / www.aboo.fr : Frédéric MEYROU : tous droits réservés
-->
<?php 
// Dépendances
	require_once('lib/fonctions.php');
	require_once('lib/database.php');

	// Vérification de l'Authent
    session_start();
    require('lib/authent.php');
    if( !Authent::islogged()){
        // Non authentifié on repart sur la HP
        header('Location:../index.php');
    }
	
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
	
// Valeures par défaut :
	$administrateur=0;
	$utilisateur=1;	

// Traitement du formulaire :        	
	if ( !empty($sPOST)) {
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
		
		// keep track post values
		$password = $sPOST['password'];
		$nom = $sPOST['nom'];
		$prenom = $sPOST['prenom'];
		$email = $sPOST['email'];
		$telephone = $sPOST['telephone'];
		$inscription = $sPOST['inscription'];
		$expiration = $sPOST['expiration'];
		$montant = $sPOST['montant'];
		
		// Statut utilisateur Radio
		empty($sPOST['utilisateur']) ? $utilisateur=0 : $utilisateur=1;
		empty($sPOST['administrateur']) ? $administrateur=0 : $administrateur=1;

		// validate input
		$valid = true;
        if (empty($email)) {
            $emailError = 'Veuillez entrer votre adresse Email';
            $valid = false;
        } else if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
            $emailError = 'Veuillez entrer une adresse eMail valide';
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
		if (empty($telephone)) {
			$telephoneError = 'Veuillez entrer un numéro de téléphone';
			$valid = false;
		}
		if (empty($inscription)) {
			$inscriptionError = 'Veuillez entrer une date AA-MM-JJ';
			$valid = false;
		} elseif ( !IsDate($inscription)) {
			$inscription = 'AA-MM-JJ';
			$valid = false;
		}
		if (empty($expiration)) {
			$expiration = NULL;
		} elseif ( !IsDate($expiration)) {
			$expiration = 'AA-MM-JJ';
			$valid = false;
		}
		if (empty($montant)) {
			$montant = 0;
		}
		$montant = number_format($montant,2,',','.');
				
		// insert data
		if ($valid) {
			$pdo = Database::connect();
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = "INSERT INTO user (prenom,nom,email,telephone,password,inscription,montant,expiration,administrateur) values(?, ?, ?, ?, ?, ?, ?, ?, ?)";
			$q = $pdo->prepare($sql);
			$q->execute(array($prenom, $nom, $email, $telephone, $password, $inscription, $montant, $expiration, $administrateur));
			Database::disconnect();
			header("Location: user.php");
		}
	}
	
?>


<!DOCTYPE html>
<html lang="fr">
<?php require 'head.php'; ?>

<body>

    <?php $page_courante = "user.php"; require 'nav.php'; ?>  
       
    <div class="container">

       <div class="page-header">   
            <h2>Gestion des comptes utilisateur</h2>          
        </div>
        
		<h3>Création d'un utilisateur</h3>

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
			
    	<div class="row">
 			 <div class="col-md-5 col-md-offset-1">    	 
 			 			<!-- Formulaire -->    
 			 			<form class="form-horizontal" action="user_create.php" method="post">
						
							<?php function Affiche_Champ(&$champ, &$champError, $champinputname, $champplaceholder, $type) { ?>
							<div class="control-group <?php echo !empty($champError)?'has-error':'';?>">
							    <label class="control-label"><?php echo "$champplaceholder" ?></label>
							    <div class="controls">
							      	<input name="<?php echo "$champinputname" ?>" class="form-control" type="<?php echo "$type" ?>" value="<?php echo !empty($champ)?$champ:'';?>">
							      	<?php if (!empty($champError)): ?>
							      		<span class="help-inline"><?php echo $champError;?></span>
							      	<?php endif; ?>
							    </div>
							</div>
							<?php } ?>
							
	                        <?php Affiche_Champ($email, $emailError, 'email','eMail', 'email' ); ?>
						    <?php Affiche_Champ($password, $passwordError, 'password','Mot de passe', 'password' ); ?>
						    <?php Affiche_Champ($nom, $nomError, 'nom','Nom', 'text' ); ?>
						    <?php Affiche_Champ($prenom, $prenomError, 'prenom','Prénom', 'text' ); ?>
						    <?php Affiche_Champ($telephone, $telephoneError, 'telephone','Téléphone', 'tel' ); ?>
						    <?php Affiche_Champ($inscription, $inscriptionError, 'inscription','Inscription', 'date' ); ?>
						    <?php Affiche_Champ($expiration, $expirationError, 'expiration','Expiration', 'date' ); ?>
						    <?php Affiche_Champ($montant, $montantError, 'montant','Montant', 'text' ); ?>

	                        <div class="radio">
							  <label>
							    <input type="radio" name="administrateur" id="utilisateur" value="0" <?php echo ($administrateur==0)?'checked':''; ?>>
							    Utilisateur
							  </label>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="administrateur" id="administrateur" value="1" <?php echo ($administrateur==1)?'checked':''; ?>>
							    Administrateur
							  </label>
							</div>	                								 
							  
						    <div class="form-actions">
						      <br>
							  <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-check"></span> Créer</button>
							  <a class="btn btn-primary" href="user.php"><span class="glyphicon glyphicon-eject"></span> Retour</a>
						    </div>
						</form>
	   		 </div> <!-- /col -->    
		</div> <!-- /row -->
    </div> <!-- /container -->
    
    <?php require 'footer.php'; ?>    
    
  </body>
</html>