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
        $sGET[$key]=htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
    foreach ($_POST as $key => $value) {
        $sPOST[$key]=htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

// Initialisation de la base
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Initialisation des status
	$affiche_formulaire = false;
    $affiche_ok = false;
	$affiche_inactif = false;
    $affiche_mauvais_token = false;	
    $affiche_mauvais_lien = false;

// Validation du lien entrant
	if(!empty($_GET)) {
		$token = $sGET['token'];
		$email = $sGET['email'];
		// Recherche ds la BDD
	    $q = array('email'=>$email,'token'=>$token);
	    $sql = 'SELECT * FROM user WHERE email = :email AND token = :token';
	    $req = $pdo->prepare($sql);
		$req->execute($q);
		$data = $req->fetch(PDO::FETCH_ASSOC);
	    $count = $req->rowCount($sql);
	    if($count == 1) { // Trouvé!
	        // Verfier si l'utilisateur est actif
	        if ($data['actif']==1) {        	
	            $affiche_formulaire = true;
	        } else { //Sinon   
	        	$affiche_inactif = true;     	 
	        }
	    } else { //Utilisateur incconu        
	        $affiche_mauvais_token = true;
	    }
	} else { //Pas de GET
       $affiche_mauvais_lien = true;
	}
// Validation du POST
	if(!empty($_POST)) {
		$password = $sPOST['password'];
		$password2 = $sPOST['password2'];
		$token = $sPOST['token'];
		$email = $sPOST['email'];
		if ($password == $password2) {
		    $q = array('password'=>$password,'email'=>$email,'token'=>$token);
		    $sql2 = "UPDATE user SET password = :password, token = NULL WHERE email = :email AND token = :token";
		    $req = $pdo->prepare($sql2);
		    $req->execute($q);
		    $affiche_ok = true;
	        $affiche_formulaire = false;
		} else {
			$affiche_formulaire = true;
			$error_password = "Votre mot de passe n'est pas identique!";
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
       <h2>Modification du mot de passe utilisateur</h2>     
    </div>

   <!-- Affiche les informations de debug -->
    <?php 
	if ($debug) {
	?>
    <div class="alert alert alert-danger alert-dismissable fade in">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <strong>Informations de Debug : </strong><br>
        POST:<br>
        <pre><?php var_dump($_GET); ?></pre>
        sPOST:<br>
        <pre><?php var_dump($sGET); ?></pre>
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
		            <form role="form"  action="motdepasse.php" method="POST">
		
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
						<input name="email" class="form-control" type="hidden" value="<?php echo !empty($email)?$email:''; ?>">
						<input name="token" class="form-control" type="hidden" value="<?php echo !empty($token)?$token:''; ?>">
		    		    <?php Affiche_Champ($password, $error_password, 'password','Mot de passe', 'password' ); ?>			
		    		    <?php Affiche_Champ($password2, $error_password, 'password2','Confirmez le mot de passe', 'password' ); ?>			
		                			            
			            <center>
			           	<div class="form-actions">
			              <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-check"></span> Enregistrer</button>
			              <a class="btn btn-primary" href="connexion.php"><span class="glyphicon glyphicon-eject"></span> Retour</a>
			            </div>
			            </center>
			                                
		            </form>
			  </div>
			</div> <!-- /panel --> 
		</div> <!-- /col -->    			
	</div> <!-- /row -->    

    <?php       
    } elseif ($affiche_ok) {  
    ?>  
    <!-- Alert informant que l'utilisateur est bien activé -->
    <div class="alert alert-success alert-dismissable fade in">
        <strong>
        <p>Le mot de passe du compte utilisateur "<?php echo $email ; ?>" à bien été changé.</p>
        <p>Vous pouvez dès maintenant vous connecter sur Aboo.</p>
        </strong>
    </div>
    <?php       
    } elseif ($affiche_inactif) {   
    ?>  
    <!-- Alert informant que le MdP est mauvais -->
    <div class="alert alert-warning alert-dismissable fade in">
        <strong>
        <p>Le compte utilisateur pour "<?php echo $email ; ?>" est désactivé, vous ne pouvez plus l'utiliser.</p>
        <p>En cas de problème, veuillez contacter le support Aboo : <a href="mailto:contact@aboo.fr">contact@aboo.fr</a></p>
        </strong>	
    </div>
    <?php       
    } elseif ($affiche_mauvais_token) {   
    ?>  
    <!-- Alert informant que le compte est déjà actif -->
    <div class="alert alert-danger alert-dismissable fade in">
        <strong>
        <p>Votre demande de modification de mot de passe est invalide ou corrompu, veuillez re-essayer.</p>
        <p>En cas de problème, veuillez contacter le support Aboo : <a href="mailto:contact@aboo.fr">contact@aboo.fr</a></p>
        </strong>	
    </div>
    <?php       
    } elseif ($affiche_mauvais_lien) {   
    ?>  
    <!-- Alert informant que le compte est déjà actif -->
    <div class="alert alert-danger alert-dismissable fade in">
        <strong>
        <p>Impossible d'utiliser ce formulaire sans être utilisateur du site.</p>
        <p>En cas de problème, veuillez contacter le support Aboo : <a href="mailto:contact@aboo.fr">contact@aboo.fr</a></p>
        </strong>   
    </div>
    <?php       
    }   
    ?>  

   </div> <!-- /container -->

    <?php require 'footer.php'; ?>
    
</body>
</html>