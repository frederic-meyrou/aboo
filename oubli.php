<!-- 
© Copyright : Aboo / www.aboo.fr : Frédéric MEYROU : tous droits réservés
-->
<?php

// Dépendances
    include_once('lib/database.php');
    include_once('lib/fonctions.php');		

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

// Flag d'affichage du formulaire
	$affiche_formulaire=true;
	$affiche_erreur=false;
	
// Validation du formulaire
	$validate=false;	
	if(!empty($_POST)) { // On a un POST on check le formulaire

		// Récupération des variables du POST
	    $email = $sPOST['email'];		
		$validate=true;
		// On traite les erreur de formulaire 
	    if(!filter_var($sPOST['email'], FILTER_VALIDATE_EMAIL)){
	        $error_email = 'Votre Email n\'est pas valide !';
			$validate=false;
	    }
	}

// On check que l'adresse eMail existe bien en BDD
	if(!empty($_POST) && $validate) {	
	    $q = array('email'=>$email);
	    $sql = "SELECT * from user WHERE email=:email";
	    $req = $pdo->prepare($sql);
	    $req->execute($q);		
	    $data = $req->fetch(PDO::FETCH_ASSOC);
	    $count = $req->rowCount($sql);
		if ($count==0) { // Le user existe pas
	        $error_email = 'cette adresse eMail n\'existe pas dans Aboo!';
			$validate=false;     
	    }		
	}	

// On écrit dans la BDD et on envoi un eMail si le formulaire est validé	
	if(!empty($_POST) && $validate) {	
		// Generation du token
	    $token = sha1(uniqid(rand()));
	
		// MàJ de la BDD
	    $q = array('email'=>$email, 'token'=>$token);
	    $sql = 'UPDATE user set token=:token WHERE email = :email';
	    $req = $pdo->prepare($sql);
	    $req->execute($q);
	
	    //Envoyer un mail pour la modifiation du MdP

	    $TO = $email;
	    $SUBJECT = 'Modification de votre mot de passe';
	    
	   	$EOL="\r\n";
		$FROM="contact@aboo.fr";
		$BCC="frederic@meyrou.com";
		
		//=====Création de la boundary
		$boundary = "-----=".md5(rand());
		
		
		//Création du Header eMail
		$header= "From: [Aboo] <$FROM>".$EOL;
		$header.= "Reply-to: [Aboo] <$FROM>".$EOL;
		$header.= "Bcc: Frédéric Meyrou <$BCC>".$EOL;
		$header.= "Return-path:<$FROM>".$EOL;		
		$header.= "MIME-Version: 1.0".$EOL;
		$header.= "Content-Type: multipart/alternative;".$EOL." boundary=\"$boundary\"".$EOL;
		$header.= "Subject: {$SUBJECT}".$EOL;
		$header.= "X-Mailer: PHP/".phpversion().$EOL;
		$header.= "X-Originating-IP: ".$_SERVER['SERVER_ADDR'];

	    $body=$EOL."--".$boundary.$EOL;
		$body.="Content-Type: text/html; charset=\"UTF-8\"".$EOL;
		$body.="Content-Transfer-Encoding: 8bit".$EOL;
	    $body.=$EOL.'
	    <html>
	    <head>
	    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Votre demande de modification de mot de passe</title>
        </head>
        <body>
        <p>Bonjour,</p>
        <br>
        <p>Vous venez de demander la modification de votre mot de passe <strong>Aboo</strong>,<p>
        <p>pour cela veuillez cliquer sur le lien ici ->         
	    <a href="'. MyBaseURL() . '/motdepasse.php?token='.$token.'&email='.$TO.'">Modification du mot de passe</a></p>
	    <br>
	    <p>L\'équipe Aboo vous remerçie.</p>
	    </body>
     	</html>
	    '.$EOL;
		$body.=$EOL."--".$boundary.$EOL;
	
		// Envoi eMail
	    $statut = mail($TO,$SUBJECT,$body,$header);
		
		// On affiche un retour à l'utilisateur
		if ($statut) {
			$affiche_formulaire=false;
			$affiche_erreur=false;
		} else {
			$affiche_formulaire=false;
			$affiche_erreur=true;
			
		}
	}
    Database::disconnect(); 

?>

<!DOCTYPE html>
<html lang="fr">
<?php require 'head.php'; ?>

<body>
	
	<div class="container">
    	
    <!-- Affiche la navigation --> 
    <nav class="navbar navbar-inverse " role="navigation">
       <div class="container">      
          <div class="navbar-header">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <!-- Marque -->
              <a class="navbar-brand" href="http://www.aboo.fr">Aboo</a>
          </div>                  
	      <!-- Liens -->
	      <div class="collapse navbar-collapse" id="TOP">
	        <ul class="nav navbar-nav">                              
	        </ul>
	      </div><!-- /.navbar-collapse -->
       </div>      
    </nav>    

    <div class="page-header">   
       <h2>Mot de passe perdu</h2>     
    </div>

    <!-- Affiche les informations de debug -->
    <?php 
	if ($debug) {
	?>
    <div class="alert alert alert-danger alert-dismissable fade in">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <strong>Informations de Debug : </strong><br>
        POST:<br>
        <pre><?php var_dump($_POST); ?></pre>
        sPOST:<br>
        <pre><?php var_dump($sPOST); ?></pre>
        data:<br>
        <pre><?php var_dump($data); ?></pre>        
    </div>
    <br>
    <?php       
    }   
    ?>  
        

    <!-- Affiche le formulaire -->
    <?php 
	if ($affiche_formulaire) {
	?>		 	
	<div class="row">
		 <div class="col-md-4 col-md-offset-4">

	        <!-- Formulaire -->
	        <div class="panel panel-default">
  				<div class="panel-body">
		            <form role="form"  action="oubli.php" method="POST">
		
			            <?php function Affiche_Champ(&$champ, &$champError, $champinputname, $champplaceholder, $type) { ?>
			            <div class="form-group <?php echo !empty($champError)?'has-error':''; ?>">
			                <label class="control-label"><?php echo "$champplaceholder"; ?></label>
			                <div class="controls">
			                    <input name="<?php echo "$champinputname"; ?>" class="form-control" type="<?php echo "$type"; ?>" value="<?php echo !empty($champ)?$champ:''; ?>">
			                    <?php if (!empty($champError)) { ?>
			                        <span class="help-inline text-error"><?php echo $champError ?></span>
			                    <?php } ?>
			                </div>
			            </div>
			            <?php } ?>
		
		    		    <?php Affiche_Champ($email, $error_email, 'email','Email', 'email' ); ?>        		            		    		            
		                			            
			            <center>
			           	<div class="form-actions">
			              <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-check"></span> Changer mon mot de passe</button>
			              <a class="btn btn-primary" href="connexion.php"><span class="glyphicon glyphicon-eject"></span> Retour</a>
			            </div>
			            </center>
			                                
		            </form>
			  </div>
			</div> <!-- /panel --> 
		</div> <!-- /col -->    			
	</div> <!-- /row -->    

    <?php       
    } elseif (!$affiche_erreur) {  
    ?>  
    <!-- Alert informant que l'utilisateur est bien créé -->
    <div class="alert alert alert-success alert-dismissable fade in">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <strong>
        <p>Votre demande de changement de mot de passe pour "<?php echo $email ; ?>" à bien été traité.</p>
        <p>Par sécurité, un eMail vous a été envoyé pour confirmation, veuillez surveiller votre messagerie électronique.</p>
        </strong>	

    </div>
    <?php       
    } else {   
    ?>  
    <!-- Alert informant que l'utilisateur créé mais que le message n'est pas parti -->
    <div class="alert alert alert-warning alert-dismissable fade in">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <strong>
        <p>Votre demande de changement de mot de passe pour "<?php echo $email ; ?>" à bien été traité.</p>
        <p>Cependant un eMail n'a pu vous être envoyé pour le valider, veuillez contacter le support Aboo : <a href="mailto:contact@aboo.fr">contact@aboo.fr</a></p>
        </strong>	
    </div>
    <?php       
    }   
    ?>  

   </div> <!-- /container -->

    <?php require 'footer.php'; ?>
    
</body>
</html>