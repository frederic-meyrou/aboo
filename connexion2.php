<!-- 
© Copyright : Aboo / www.aboo.fr : Frédéric MEYROU : tous droits réservés
-->
<?php
	session_start();

	// Dépendances
	include 'database.php';

	// Sécurisation POST & GET
    foreach ($_GET as $key => $value) {
        $sGET[$key]=htmlentities($value, ENT_QUOTES);
    }
    foreach ($_POST as $key => $value) {
        $sPOST[$key]=htmlentities($value, ENT_QUOTES);
    }

	// Init BDD
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);        

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
	        // On a bien l'utilisateur dans la base, on charge ses infos dans la session      
	        $_SESSION['authent'] = array(
	            'id' => $data['id'],
	            'email' => $email,
	            'password' => $password,
	            'nom' => $data['nom'],
	            'prenom' => $data['prenom'],
	            'expiration' => $data['expiration'],
	            'admin' => $data['administrateur']
	            );        
	        if ($_SESSION['authent']['admin']==1) {
		        // Cas ou l'utilisateur est Admin, redirection vers page admin
				Database::disconnect();     	
		        header('Location:admin/user.php');            
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
		                'treso' => $data2['montant_treso_initial']
	                );         
				}	
				Database::disconnect();     	
		        // Redirection vers la home sécurisé            
		        header('Location:home.php');
	        }
	    } else {
	        //Utilisateur inconnu
	        $error_unknown = 'Compte $email inconnu ou mot de passe invalide!';
	    }  
	}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>GestAbo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" media="screen">
    <link href="bootstrap/css/signin.css" rel="stylesheet">
</head>

<body>
    <script src="bootstrap/js/jquery-2.0.3.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="bootstrap/js/bootstrap-modal.js"></script>
    
      <?php if(isset($error_unknown)){ ?> 
      <div class="alert alert-fail alert-dismissable fade in">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <strong><?php echo "$error_unknown" ?>.</strong>
      </div>
      <?php  
      } ?>
    
    <a class="btn" data-toggle="modal" href="#login" >Connexion</a>  
    <div class="modal hide fade" id="login">
        <div class="modal-header">
            <a href="#" class="btn btn-success pull-right" data-dismiss="modal">×</a>
            <h2>Connectez-vous sur Aboo</h2>
        </div>
        <div class="modal-body">
            <form method="post" action='<?= base_url();?>/connexion2.php' name="loginForm" class="form-signin">
                <p><input name="email" type="text" class="form-control" placeholder="eMail" required autofocus></p>
                <p><input name="password" type="password" class="form-control" placeholder="Mot de passe" required></p>
                <div class="error"><?php if(isset($error_unknown)){ echo $error_unknown; } ?></div>
                <p><button type="submit" class="btn btn-lg btn-primary btn-block">Connexion</button> 
                
                <a href="<?= base_url();?>/register">Créer un compte?</a></p>
            </form>
        </div>                                                                  
    </div>
    
    <a class="btn pull-right" data-toggle="modal" href="#login2" >Login</a>
    
    <div class="modal hide fade" id="login2">
    <div class="modal-header">
        <a href="#" class="btn btn-success pull-right" data-dismiss="modal">×</a>
        <h4>Welcome to technicalkeeda.com</h4>
    </div>
    <div class="modal-body">
    <form method="post" action='<?= base_url();?>/login' name="loginForm">
        <p><input type="text" class="span3" name="email" id="email" placeholder="Enter Email" value=""></p>
        <p><input type="password" class="span3" id="loginPassword" name="loginPassword" placeholder="Enter Password"></p>
        <p><button type="submit" class="btn btn-success">Sign in</button> <a href="<?= base_url();?>/forgotpassword">Forgot Password</a></p>
    </form>
    </div>                                                                  
</div>


    
</body>
</html>