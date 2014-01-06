<!-- 
© Copyright : Aboo / www.aboo.fr : Frédéric MEYROU : tous droits réservés
-->
<?php 
// Vérification de l'Authent
    session_start();
    require('authent.php');
    if( !Authent::islogged()){
        // Non authentifié on repart sur la HP
        header('Location:index.php');
    }
		
// Dépendances
	require_once('fonctions.php');
	require_once('database.php');
	
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
        	
	if ( !empty($sPOST)) {
		// keep track validation errors
		$nomError = null;
		$prenomError = null;
		$emailError = null;
		$telephoneError = null;
		$mobileError = null;
		$adresse1Error = null;
		$adresse2Error = null;
		$cpError = null;
		$villeError = null;
		$ageError = null;
		$professionError = null;
		$descriptionError = null;
		
		// keep track post values
		$nomclient = $sPOST['nom'];
		$prenomclient = $sPOST['prenom'];
		$email = $sPOST['email'];
		$telephone = $sPOST['telephone'];
		$mobile = $sPOST['mobile'];
		$age = $sPOST['age'];
		$profession = $sPOST['profession'];
		$adresse1 = $sPOST['adresse1'];
		$adresse2 = $sPOST['adresse2'];
		$cp = $sPOST['cp'];
		$ville = $sPOST['ville'];
		$description = $sPOST['description'];
		
		// validate input
		$valid = true;
        if (empty($email)) {
            $emailError = 'Veuillez entrer votre adresse Email';
            $valid = false;
        } else if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
            $emailError = 'Veuillez entrer une adresse eMail valide';
            $valid = false;
        }
		if (empty($nomclient)) {
			$nomError = 'Veuillez entrer un nom';
			$valid = false;
		}
		if (empty($prenomclient)) {
			$prenomError = 'Veuillez entrer un prénom';
			$valid = false;
		}	
	
		// insert data
		if ($valid) {
			$pdo = Database::connect();
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = "INSERT INTO client (user_id,prenom,nom,email,telephone,mobile,adresse1,adresse2,cp,ville,age,profession,description) values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
			$q = $pdo->prepare($sql);
			$q->execute(array($user_id, $prenomclient, $nomclient, $email, $telephone, $mobile, $adresse1, $adresse2, $cp, $ville, $age, $profession, $description));
			Database::disconnect();
			header("Location: mesclients.php");
		}
	}
	
?>


<!DOCTYPE html>
<html lang="fr">
<?php require 'head.php'; ?>

<body>

    <?php $page_courante = "mesclients.php"; require 'nav.php'; ?>
        
    <div class="container">

		<h3>Création d'un client</h3>

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
 			 			<form class="form-horizontal" action="client_create.php" method="post">
						
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
							
						    <?php Affiche_Champ($prenomclient, $prenomError, 'prenom','Prénom', 'text' ); ?>
						    <?php Affiche_Champ($nomclient, $nomError, 'nom','Nom', 'text' ); ?>
	                        <?php Affiche_Champ($email, $emailError, 'email','eMail', 'email' ); ?>
						    <?php Affiche_Champ($telephone, $telephoneError, 'telephone','Téléphone Fixe', 'tel' ); ?>
						    <?php Affiche_Champ($mobile, $mobileError, 'mobile','Téléphone Mobile', 'tel' ); ?>
	                        <?php Affiche_Champ($adresse1, $adresse1Error, 'adresse1','Adresse ligne 1', 'text' ); ?>
	                        <?php Affiche_Champ($adresse2, $adresse2Error, 'adresse2','Adresse ligne 2', 'text' ); ?>
	                        <?php Affiche_Champ($cp, $cpError, 'cp','Code Postal', 'number' ); ?>
	                        <?php Affiche_Champ($ville, $villeError, 'ville','Ville', 'text' ); ?>
	                        <?php Affiche_Champ($age, $ageError, 'age','Age', 'number' ); ?>
	                        <?php Affiche_Champ($profession, $professionError, 'profession','Profession', 'text' ); ?>
	                        <?php Affiche_Champ($description, $descriptionError, 'description','Description', 'text' ); ?>
							  
						    <div class="form-actions">
						      <br>
							  <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-check"></span> Créer</button>
							  <a class="btn btn-primary" href="mesclients.php"><span class="glyphicon glyphicon-chevron-up"></span> Retour</a>
						    </div>
						</form>
	   		 </div> <!-- /col -->    
		</div> <!-- /row -->
    </div> <!-- /container -->

    <?php require 'footer.php'; ?>
        
  </body>
</html>