<!-- 
© Copyright : Aboo / www.aboo.fr : Frédéric MEYROU : tous droits réservés
-->
<?php

// Dépendances
    include_once('lib/database.php');

// Mode Debug
	$debug = false;

// Sécurisation POST & GET
    foreach ($_GET as $key => $value) {
        $sGET[$key]=htmlentities($value, ENT_QUOTES);
    }
    foreach ($_POST as $key => $value) {
        $sPOST[$key]=htmlentities($value, ENT_QUOTES);
    }

// Initialisation de la base
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if(!empty($_POST) && filter_var($sPOST['email'], FILTER_VALIDATE_EMAIL)) {
	
	
	// Récupération des variables du POST
    $prenom = $sPOST['prenom'];
	$nom = $sPOST['nom'];
    $email = $sPOST['email'];
    $password = $sPOST['password'];
    $telephone = $sPOST['telephone'];	
	$inscription = date("Y-m-d");
	// Generation du token
    $token = sha1(uniqid(rand()));

	// MàJ de la BDD
    $q = array('prenom'=>$prenom, 'nom'=>$nom, 'email'=>$email, 'telephone'=>$telephone, 'password'=>$password, 'inscription'=>$inscription, 'token'=>$token);
    $sql = 'INSERT INTO user (prenom, nom, email, telephone, password, inscription, token) VALUES (:prenom, :nom, :email, :telephone, :password, :inscription, :token)';
    $req = $pdo->prepare($sql);
    $req->execute($q);
    Database::disconnect(); 

    //Envoyer un mail pour la validation du compte
    $to = $email;
    $sujet = 'Activation de votre compte';
    $body = '
    Bonjour, veuillez activer votre compte en cliquant ici ->
    <a href="http://localhost/gestabo/activate.php?token='.$token.'&email='.$to.'">Activation du compte</a>';
    $entete = "MIME-Version: 1.0\r\n";
    $entete .= "Content-type: text/html; charset=UTF-8\r\n";
    $entete .= 'From: CreatiQ.FR ::' . "\r\n" .
    'Reply-To: contact@creatiq.fr' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

	// Envoi eMail
    mail($to,$sujet,$body,$entete);
	
	// Retour
	header('Location: index.php');
} else {
	// On traite les erreur de formulaire 
    if(!empty($sPOST['nom'])){
        $error_nom = 'Veuillez renseigner votre nom';
    }
    if(!empty($sPOST['prenom'])){
        $error_prenom = 'Veuillez renseigner votre prénom';
    }
    if(!empty($sPOST['password'])){
        $error_password = 'Veuillez renseigner un mot de passe';
    }
    if(!empty($sPOST['telephone'])){
        $error_telephone = 'Veuillez renseigner votre téléphone';
    }		
    if(!empty($_POST) && !filter_var($sPOST['email'], FILTER_VALIDATE_EMAIL)){
        $error_email = ' Votre Email n\'est pas valide !';
    }

}

?>

<!DOCTYPE html>
<html lang="fr">
<?php require 'head.php'; ?>

<body>

    <!-- Affiche la navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">      
      <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <!-- Marque -->
          <a class="navbar-brand" href="connexion.php">Aboo</a>
      </div>     
      <!-- Liens -->
      <div class="collapse navbar-collapse" id="TOP">
        <ul class="nav navbar-nav">                              
        </ul>
      </div><!-- /.navbar-collapse -->
    </nav>    
    
    <div class="container">

		<div class="row">
 			 <div class="col-md-5 col-md-offset-1">
 			 	
	            <h3>Demande de compte utilisateur</h3><br/>
		        <!-- Formulaire -->  
                <form class="form-horizontal" action="inscription.php" method="POST">

		            <?php function Affiche_Champ(&$champ, &$champError, $champinputname, $champplaceholder, $type) { ?>
		            <div class="form-group <?php echo !empty($champError)?'has-error':'';?>">
		                <label class="control-label"><?php echo "$champplaceholder" ?></label>
		                <div class="controls">
		                    <input name="<?php echo "$champinputname" ?>" class="form-control" type="<?php echo "$type" ?>" value="<?php echo !empty($champ)?$champ:'';?>">
		                    <?php if (!empty($champError)): ?>
		                        <span class="help-inline"><?php echo $champError;?></span>
		                    <?php endif; ?>
		                </div>
		            </div>
		            <?php } ?>

        		    <?php Affiche_Champ($prenom, $error_prenom, 'prenom','Prenom', 'text' ); ?>
        		    <?php Affiche_Champ($nom, $error_nom, 'nom','Nom', 'text' ); ?>
        		    <?php Affiche_Champ($email, $error_email, 'email','Email', 'email' ); ?>        		            		    		            
        		    <?php Affiche_Champ($telephone, $error_telephone, 'telephone','Telephone', 'phone' ); ?>
        		    <?php Affiche_Champ($password, $error_password, 'password','Mot de passe', 'password' ); ?>			
                    
		            <div class="form-actions">
		              <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-check"></span> S'inscrire</button>
		              <a class="btn btn-primary" href="connexion.php"><span class="glyphicon glyphicon-chevron-up"></span> Retour</a>
		            </div>
		                                
                </form>
	   		 </div> <!-- /col -->    			
	    </div> <!-- /row -->    
    </div>

    <?php require 'footer.php'; ?>
    
</body>
</html>