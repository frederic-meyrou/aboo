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

// Récupération des variables de session
	include_once('lib/var_session.php');
				
// Mode Debug
	$debug = false;

// Sécurisation POST & GET
    foreach ($_GET as $key => $value) {
        $sGET[$key]=htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
    foreach ($_POST as $key => $value) {
        $sPOST[$key]=htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
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
        		
		// keep track post values
		$password = trim($sPOST['password']);
        $usernom = ucfirst(mb_strtolower($sPOST['nom'], 'UTF-8'));   
        $userprenom = ucfirst(trim(mb_strtolower($sPOST['prenom'], 'UTF-8')));		
		$email = trim($sPOST['email']);
		$telephone = trim($sPOST['telephone']);
		$inscription = trim($sPOST['inscription']);
		$expiration = trim($sPOST['expiration']);
		$montant = trim($sPOST['montant']);

		// Statut utilisateur Radio
		$administrateur=$sPOST['administrateur'];
		$actif=$sPOST['actif'];
		$essai=$sPOST['essai'];
		
		// validate input
		$valid = true;
        if (empty($email)) {
            $emailError = 'Veuillez entrer une adresse Email';
            $valid = false;
        } else if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
            $emailError = 'Veuillez entrer une adresse eMail valide';
            $valid = false;
        }
		if (empty($password) || strlen($password) < 6) {
            $passwordError = 'Votre mot de passe ne peut être inférieur à 6 caractères';
            $valid=false;						
		}
		if (empty($usernom)) {
			$nomError = 'Veuillez entrer un nom';
			$valid = false;
		}
		if (empty($userprenom)) {
			$prenomError = 'Veuillez entrer un prénom';
			$valid = false;
		}
        if(!empty($telephone) && !preg_match('/^[0-9]{10,14}$/', $telephone)) {
            $telephoneError = 'Le numéro de téléphone mobile n\'est pas valide.';
            $valid=false;
        } 
		if (empty($telephone)) {
			$telephoneError = 'Veuillez entrer un numéro de téléphone';
			$valid = false;
		}
		if (empty($inscription)) {
			$inscription = date('Y-m-d');
		} elseif ( !IsDate($inscription)) {
			$inscriptionError = 'Format date : AA-MM-JJ';
			$valid = false;
		}
		if (empty($expiration)) {
			$expirationError = 'Date obligatoire';
			$valid = false;
		} elseif ( !IsDate($expiration)) {
			$expirationError = 'Format date : AA-MM-JJ';
			$valid = false;
		}
		if (empty($montant)) {
			$montant = 0;
		}
		//$montant = number_format($montant,2,',','.');	
		
		// update data
		if ($valid) {
			$pdo = Database::connect();
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = "UPDATE user set prenom=?,nom=?,email=?,telephone=?,password=?,inscription=?,montant=?,expiration=?,administrateur=?, actif=?, essai=? WHERE id = ?";
			$q = $pdo->prepare($sql);
			$q->execute(array($prenom, $nom, $email, $telephone, $password, $inscription, $montant, $expiration, $administrateur, $actif, $essai, $id));
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
        $actif = $data['actif'];
        $essai = $data['essai'];       
		
		Database::disconnect();
	}
?>


<!DOCTYPE html>
<html lang="fr">
<?php require 'head.php'; ?>

<body>

    <?php $page_courante = "user.php"; require 'nav.php'; ?>
        
    <div class="container">   

       <div class="page-header">   
            <h2>Gestion des comptes utilisateur : Mise à jour du compte</h2>          
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

		<div class="row">
 			 <div class="col-md-5 col-md-offset-1">
		        <!-- Formulaire -->    		               					
    			<form class="form-horizontal" action="user_update.php?id=<?php echo $id?>" method="post">
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
					
					<li class="list-group-item">
	                <?php Affiche_Champ($email, $emailError, 'email','eMail', 'mail' ); ?>
					<?php Affiche_Champ($password, $passwordError, 'password','Mot de passe', 'password' ); ?>
					<?php Affiche_Champ($nom, $nomError, 'nom','Nom', 'text' ); ?>
					<?php Affiche_Champ($prenom, $prenomError, 'prenom','Prénom', 'text' ); ?>
					<?php Affiche_Champ($telephone, $telephoneError, 'telephone','Téléphone', 'tel' ); ?>
					<?php Affiche_Champ($inscription, $inscriptionError, 'inscription','Inscription', 'date' ); ?>
					<?php Affiche_Champ($expiration, $expirationError, 'expiration','Expiration', 'date' ); ?>
					<?php Affiche_Champ($montant, $montantError, 'montant','Montant', 'text' ); ?>
					</li>
					<center>
					<li class="list-group-item">
                    <div class="radio-inline">
					  <label>
					    <input type="radio" name="administrateur" id="administrateur" value="0" <?php echo ($administrateur==0)?'checked':''; ?>>
					    Utilisateur
					  </label>
					</div>
					<div class="radio-inline">
					  <label>
					    <input type="radio" name="administrateur" id="administrateur" value="1" <?php echo ($administrateur==1)?'checked':''; ?>>
					    Administrateur
					  </label>
					</div>	                								 
					</li>
					<li class="list-group-item">
					<div class="radio-inline">
					  <label>
					    <input type="radio" name="essai" id="essai" value="0" <?php echo ($essai==0)?'checked':''; ?>>
					    License
					  </label>
					</div>									
                    <div class="radio-inline">
					  <label>
					    <input type="radio" name="essai" id="essai" value="1" <?php echo ($essai==1)?'checked':''; ?>>
					    Essai
					  </label>
					</div>
					</li>
					<li class="list-group-item ">
                    <div class="radio-inline">
					  <label>
					    <input type="radio" name="actif" id="actif" value="1" <?php echo ($actif==1)?'checked':''; ?>>
					    Compte Actif
					  </label>
					</div>
					<div class="radio-inline">
					  <label>
					    <input type="radio" name="actif" id="actif" value="0" <?php echo ($actif==0)?'checked':''; ?>>
					    Compte Inactif
					  </label>
					</div>	
					</li>
				    </center>
	                					
				    <div class="form-actions">
				      <br>  
			              <button type="submit" class="btn btn-warning"><span class="glyphicon glyphicon-check"></span> Mise à jour</button>
			              <a class="btn btn-primary" href="user.php"><span class="glyphicon glyphicon-eject"></span> Retour</a>
					</div>
				</form>
				
	   		 </div> <!-- /col -->    			
	    </div> <!-- /row -->   
				
    </div> <!-- /container -->
    
    <?php require 'footer.php'; ?>    
        
  </body>
</html>