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
	    $prenom = $sPOST['prenom'];
		$nom = $sPOST['nom'];
	    $email = $sPOST['email'];
	    $password = $sPOST['password'];
	    $telephone = $sPOST['telephone'];	
		$inscription = date("Y-m-d");
		
		$validate=true;
		// On traite les erreur de formulaire 
	    if(empty($sPOST['nom'])){
	        $error_nom = 'Veuillez renseigner votre nom';
			$validate=false;
	    }
	    if(empty($sPOST['prenom'])){
	        $error_prenom = 'Veuillez renseigner votre prénom';
			$validate=false;
	    }
	    if(empty($sPOST['password'])){
	        $error_password = 'Veuillez renseigner un mot de passe';
			$validate=false;
	    }
	    if(empty($sPOST['telephone'])){
	        $error_telephone = 'Veuillez renseigner votre téléphone';
			$validate=false;
	    }
	    if(!filter_var($sPOST['email'], FILTER_VALIDATE_EMAIL)){
	        $error_email = 'Votre Email n\'est pas valide !';
			$validate=false;
	    }
	}

// On check que l'adresse eMail n'existe pas déjà en BDD
	if(!empty($_POST) && $validate) {	
	    $q = array('email'=>$email);
	    $sql = "SELECT * from user WHERE email=:email";
	    $req = $pdo->prepare($sql);
	    $req->execute($q);		
	    $data = $req->fetch(PDO::FETCH_ASSOC);
	    $count = $req->rowCount($sql);
		if ($count!=0) { // Le user existe déjà
	        $error_email = 'Votre adresse eMail existe déjà dans Aboo!';
			$validate=false;     
	    }		
	}	

// On écrit dans la BDD et on envoi un eMail si le formulaire est validé	
	if(!empty($_POST) && $validate) {	
		// Generation du token
	    $token = sha1(uniqid(rand()));
	
		// MàJ de la BDD
	    $q = array('prenom'=>$prenom, 'nom'=>$nom, 'email'=>$email, 'mobile'=>$telephone, 'password'=>$password, 'inscription'=>$inscription, 'token'=>$token);
	    $sql = 'INSERT INTO user (prenom, nom, email, mobile, password, inscription, token, essai) VALUES (:prenom, :nom, :email, :mobile, :password, :inscription, :token, 1)';
	    $req = $pdo->prepare($sql);
	    $req->execute($q);
	
	    //Envoyer un mail pour la validation du compte
	    $TO = $email;
	    $SUBJECT = 'Activation de votre compte';
	    
	   	$EOL="\r\n";
		$FROM="contact@aboo.fr";
		$BCC="frederic@meyrou.com";
		
		//Création du Header eMail
		$header= "From: [Aboo] <$FROM>".$EOL;
		$header.= "Reply-To: <$FROM>".$EOL;
		$header.= "Bcc: <$BCC>".$EOL;
		$header.= "Return-path:<$FROM>".$EOL;		
		$header.= "MIME-Version: 1.0".$EOL;
		$header.= "Content-Type: text/html; charset=utf-8".$EOL;
//        $header.= "Content-Transfer-Encoding: quoted-printable".$EOL;
		$header.= "Subject: {$SUBJECT}".$EOL;
		$header.= "X-Mailer: PHP/".phpversion().$EOL;
		$header.= "X-Originating-IP: ".$_SERVER['SERVER_ADDR'];

	    $body='
        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
            <title>[Aboo] Confirmation de la création de votre compte</title>
            <style type="text/css">
                body, table, p, h1,h2,h3,h4,h5 {font-family:Verdana, Arial, Helvetica, sans-serif;}
                body, table, p {font-size:14px;}
                #outlook a {padding:0;} /* Force Outlook to provide a "view in browser" menu link. */
                body{width:100% !important; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; margin:0; padding:0;}
                .ExternalClass {width:100%;} /* Force Hotmail to display emails at full width */
                .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;} 
                #backgroundTable {margin:0; padding:0; width:100% !important; line-height: 100% !important;}
                img {outline:none; text-decoration:none; -ms-interpolation-mode: bicubic;}
                a img {border:none;}
                .image_fix {display:block;}
                p {margin: 1em 0;}
                h1, h2, h3, h4, h5, h6 {color: black !important;}
                h1 a, h2 a, h3 a, h4 a, h5 a, h6 a {color: blue !important;}
                h1 a:active, h2 a:active,  h3 a:active, h4 a:active, h5 a:active, h6 a:active {
                    color: red !important; 
                }
                h1 a:visited, h2 a:visited,  h3 a:visited, h4 a:visited, h5 a:visited, h6 a:visited {
                    color: purple !important;
                }
                table td {border-collapse: collapse;}
                table { border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; }
                a {color: orange; text-decoration: none;}
        
                a:link { color: orange; }
                a:visited { color: blue; }
                a:hover { color: gray; }

            </style>
        </head>
        <body>
            <table cellpadding="0" cellspacing="0" border="0" id="backgroundTable">
            <tr>
                <td>
                    <h3>Bonjour et bienvenue sur Aboo!</h3>
                    <table><tbody><td>Veuillez activer votre compte : </td><td><a target="_blank" href="'. MyBaseURL() . '/activate.php?token='.$token.'&email='.$TO.'" style="font-family:lucida grande, tahoma, verdana, arial, sans-serif;
                    line-height:2em; 
                    color:orange; 
                    text-decoration:none; 
                    font-size:13px; 
                    -moz-text-shadow:0px 1px #ffffff; 
                    -webkit-text-shadow:0px 1px #ffffff; 
                    text-shadow:0px 1px #ffffff; 
                    display:block; 
                    padding:1px 6px 3px 6px; 
                    border-top:1px solid #ffffff; 
                    white-space:nowrap; 
                    -webkit-border-radius:2px; 
                    -moz-border-radius:2px; 
                    border-radius:2px;
                    background-color: #E9E9E9;
                    border: 1px solid #CCCCCC;
                    border-radius: 2px;">Validation</a></td></tbody></table>
                    <br>
                    <em>L\'équipe Aboo vous remerçie.</em>
                 </td>
            </tr>
            </table>
        </body>
        </html>
	    '.$EOL;
	
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
       <h2>Demande de compte utilisateur</h2>     
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
		            <form role="form"  action="inscription.php" method="POST">
		
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
		
		    		    <?php Affiche_Champ($prenom, $error_prenom, 'prenom','Prènom', 'text' ); ?>
		    		    <?php Affiche_Champ($nom, $error_nom, 'nom','Nom', 'text' ); ?>
		    		    <?php Affiche_Champ($email, $error_email, 'email','Email', 'email' ); ?>        		            		    		            
		    		    <?php Affiche_Champ($telephone, $error_telephone, 'telephone','Telephone', 'phone' ); ?>
		    		    <?php Affiche_Champ($password, $error_password, 'password','Mot de passe', 'password' ); ?>			
		                			            
			            <center>
			           	<div class="form-actions">
			              <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-check"></span> S'inscrire</button>
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
        <p>Le compte utilisateur pour "<?php echo $email ; ?>" à bien été créé, il est en attente de validation.</p>
        <p>Un eMail vous a été envoyé pour le valider, veuillez surveiller votre messagerie électronique.</p>
        </strong>	

    </div>
    <?php       
    } else {   
    ?>  
    <!-- Alert informant que l'utilisateur créé mais que le message n'est pas parti -->
    <div class="alert alert alert-warning alert-dismissable fade in">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <strong>
        <p>Le compte utilisateur pour "<?php echo $email ; ?>" à bien été créé, il est en attente de validation.</p>
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