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
    $affiche_ok = false;
	$affiche_inactif = false;
    $affiche_mauvais_token = false;	
    $affiche_mauvais_lien = false;

// Validation du lien entrant
	if(!empty($_GET)) {
		$user_id = $sGET['uid'];		
		$token = $sGET['token'];
		$email = $sGET['email'];
		// Recherche ds la BDD
	    $q = array('email'=>$email,'token'=>$token,'id'=>$user_id);
	    $sql = 'SELECT * FROM user WHERE token = :token AND id = :id';
	    $req = $pdo->prepare($sql);
		$req->execute($q);
		$data = $req->fetch(PDO::FETCH_ASSOC);
	    $count = $req->rowCount($sql);
	    if($count == 1) { // Trouvé!
	        // Verifier si l'utilisateur est actif
	        if ($data['actif']==1) {        	
	            $affiche_ok = true;
				// On met à jour l'eMail
                    $sql2 = "UPDATE user set token='', email=? WHERE id = ?"; // On ne met à jour que le token en BDD pour validation utltérieur par line eMail dans la BAL de l'utilisatateur
                    $q2 = array($email, $user_id);
		            $req2 = $pdo->prepare($sql2);
		            $req2->execute($q2);									
	        } else { 
	        	$affiche_inactif = false;
	        }
	    } else { //Utilisateur inconu        
	        $affiche_mauvais_token = true;
	    }
	} else {
       $affiche_mauvais_lien = true;
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
       <h2>Activation du compte utilisateur</h2>     
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

    <?php       
    if ($affiche_ok) {  
    ?>  
    <!-- Alert informant que l'utilisateur est bien activé -->
    <div class="alert alert-success alert-dismissable fade in">
        <strong>
        <p>La nouvelle adresse eMail "<?php echo $email ; ?>" à bien été validé.</p>
        <p>Vous pouvez dès maintenant vous re-connecter sur Aboo.</p>
        </strong>
    </div>
    <?php       
    } elseif (!$affiche_inactif) {   
    ?>  
    <!-- Alert informant que le compte est déjà actif -->
    <div class="alert alert-warning alert-dismissable fade in">
        <strong>
        <p>Le compte utilisateur pour "<?php echo $email ; ?>" est inactif, vous ne pouvez pas changer l'adresse eMail.</p>
        <p>En cas de problème, veuillez contacter le support Aboo : <a href="mailto:contact@aboo.fr">contact@aboo.fr</a></p>
        </strong>	
    </div>
    <?php       
    } elseif ($affiche_mauvais_token || $affiche_mauvais_lien) {   
    ?>  
    <!-- Alert informant que le compte est déjà actif -->
    <div class="alert alert-danger alert-dismissable fade in">
        <strong>
        <p>Validation rejetée ou compte inconnu.</p>
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