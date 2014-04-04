<!-- 
© Copyright : Aboo / www.aboo.fr : Frédéric MEYROU : tous droits réservés
-->

<?php
	session_start();

	// Dépendances
	include 'lib/database.php';
	include 'lib/fonctions.php';
	
	// Sécurisation POST & GET
    foreach ($_GET as $key => $value) {
        $sGET[$key]=htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
    foreach ($_POST as $key => $value) {
        $sPOST[$key]=htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

	// Mode Debug
	$debug = false;
	
	// Init BDD
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);        

	$email = null;
	$password = null;
	
    // Le Formulaire est rempli
	if (isset($sPOST['email']) && isset($sPOST['password'])) {
	    $email = $sPOST['email'];
	    $password = $sPOST['password'];
	    // Lecture dans la base
	    $sql = "SELECT * FROM user WHERE email = ? AND password = ?";
	    $q = $pdo->prepare($sql);
	    $q->execute(array($email,$password));
	    $data = $q->fetch(PDO::FETCH_ASSOC);
	    $count = $q->rowCount($sql);

	    if ($count==1) {
			// Vérification de l'expiration du compte
			$datejour = date('Y-m-d');
			$datefin = $data['expiration'];
			if ( $data['administrateur'] == 0 && (strtotime($datefin) - strtotime($datejour)) < 0 ) {
	        	$error_expiration = "Ce compte est expiré depuis le " . DateFr($datefin) . ".  <br>Veuillez comtacter le support Aboo : contact@aboo.fr en cas de problème.";				
			} else { // Compte non expiré ou Administrateur		    	
		        // On a bien l'utilisateur dans la base, on charge ses infos dans la session      
	            $_SESSION['authent'] = array(
	                'id' => $data['id'],
	                'email' => $email,
	                'nom' => $data['nom'],
	                'prenom' => $data['prenom'],
	                'expiration' => $data['expiration'],
	                'admin' => $data['administrateur']
	                );
	            // Chargement des options    
	            $_SESSION['options']['gestion_social'] = $data['gestion_social'];
	            $_SESSION['options']['regime_fiscal'] = $data['regime_fiscal'];   			   
	            // Gestion du profil Admin      
		        if ($_SESSION['authent']['admin']==1) {
			        // Cas ou l'utilisateur est Admin, redirection vers page admin
					Database::disconnect();     	
			        header('Location:user.php');            
		        } else {
			        // On charge les infos de session mois en cours si déjà enregistré
					if ($data['mois_encours'] != null) {
						$_SESSION['abodep']['mois'] = $data['mois_encours'];
					}	        	         	
			        // On charge les infos exercice de session si déjà enregistré
					if ($data['exerciceid_encours'] != null) {
				        $sql2 = "SELECT * FROM exercice where id = ?";
				        $q = $pdo->prepare($sql2);
				        $q->execute(array($data['exerciceid_encours']));
				        $data2 = $q->fetch(PDO::FETCH_ASSOC);
						$_SESSION['exercice'] = array(
			                'id' => $data['exerciceid_encours'],
			                'annee' => $data2['annee_debut'],
			                'mois' => $data2['mois_debut'],
			                'treso' => $data2['montant_treso_initial'],
			                'provision' => $data2['montant_provision_charges']	
		                );         
					}	
					Database::disconnect();     	
			        // Redirection vers la home sécurisé            
			        header('Location:home.php');
		        }
			}
	    } else { // Rien en base
	        //Utilisateur inconnu
	        $errorEmail = "Compte $email inconnu ou mot de passe invalide!";
			$errorPassword = " ";			
	    }  
	} else { // Formulaire incomplet
		$errorPassword=null;	
		$errorEmail=null;			
		$errorPassword=null;	       	        
	        if (isset($sPOST['password']) && empty($sPOST['password'])) {
	        	$errorPassword="Le Mot de passe vide";
	        }
	        if (isset($sPOST['email']) && empty($sPOST['email'])) {
	        	$errorEmail="L'eMail est vide";
	        }				
	}
?>

<!DOCTYPE html>
<html lang="fr">
<?php require 'head.php'; ?>

<body>
    
    <!-- Affiche la navigation -->
    <nav class="navbar navbar-inverse" role="navigation">   
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
              <li><a href="connexion.php"><span class="glyphicon glyphicon-off"></span> Connexion Classique</a></li>
              <li><a href="connexion2.php"><span class="glyphicon glyphicon-off"></span> Connexion Modale</a></li>                                    
            </ul>
          </div><!-- /.navbar-collapse -->
      </div>          
    </nav>    
    
    <div class="container">

        <!-- Affiche les informations de debug -->
        <?php 
 		if ($debug) {
		?>
		<div class="span10 offset1">
        <div class="alert alert-danger alert-dismissable fade in">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <strong>Informations de Debug : </strong><br>
            POST:<br>
            <pre><?php var_dump($_POST); ?></pre>
            GET:<br>
            <pre><?php var_dump($_GET); ?></pre>
        </div>
       </div>
        <?php       
        }   
        ?>  
       	
		<!-- Modal Login-->
		<div class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="LoginModalLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      	<form class="form-horizontal" method="post" action='connexion2.php' name="loginForm">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			        <h3 class="modal-title" id="LoginModalLabel">Connectez-vous!</h3>
			      </div><!-- /.modal-header -->
			      <div class="modal-body">
			      	<div class="form-group <?php echo !empty($errorEmail)?'has-error':'';?>">
				        <input name="email" id="email" type="email" class="form-control" value="<?php if(!empty($email)){ echo $email; } ?>" placeholder="Email" maxlength=96 required autofocus>
				        <?php if (!empty($errorEmail)): ?>
		                	<span class="help-inline"><?php echo $errorEmail;?></span>
		                <?php endif; ?>
	 		        </div>	      		
			      	<div class="form-group <?php echo !empty($errorPassword)?'has-error':'';?>">
				        <input name="password" id="password" type="password" class="form-control" value="" value="<?php if(!empty($password)){ echo $password; } ?>" placeholder="Mot de passe" maxlength=20 required>
				        <?php if (!empty($errorPassword)): ?>
		                	<span class="help-inline"><?php echo $errorPassword;?></span>
		                <?php endif; ?>
	 		        </div>	      		
 			        <h5> 
			           	<span class="glyphicon glyphicon-user"></span> <a href="inscription.php"> Vous souhaitez créer un compte ?</a><br/>
					    <span class="glyphicon glyphicon-lock"></span> <a href="oubli.php"> Vous avez oublié votre mot de passe ?</a>
					</h5>
				    <!-- Affiche les informations d'expiration -->
				    <?php 
					if (isset($error_expiration)) {
					?>
					<div class="span10 offset1">
				    <div class="alert alert-danger alert-dismissable fade in">
				        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				        <strong>Impossible de se connecter !</strong><br>
				        <?php echo $error_expiration ?>
				    </div>
				   </div>
				    <?php       
				    }   
				    ?> 					
			      </div><!-- /.modal-body -->					    				  
				  <div class="modal-footer">
				  	<div class="form-actions pull-right">
				        <button type="button" class="btn btn-lg btn-primary" data-dismiss="modal"><span class="glyphicon glyphicon-eject"></span> Retour</button>
				        <button type="submit" class="btn btn-lg btn-success" ><span class="glyphicon glyphicon-log-in"></span> Connexion</button>
				    </div>
			      </div><!-- /.modal-footer -->
		        </form>	
		    </div><!-- /.modal-content -->
		  </div><!-- /.modal-dialog -->
		</div><!-- /.modal -->

    </div> <!-- /container -->

    <?php require 'footer.php'; ?>
    
    <script>
	    $(document).ready(function(){ // Le DOM est chargé
			$('#login').modal('show');	
		});
	</script>    

</body>
</html>