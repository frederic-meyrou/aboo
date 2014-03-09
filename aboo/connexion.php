<!-- 
© Copyright : Aboo / www.aboo.fr : Frédéric MEYROU : tous droits réservés
-->
<?php
	session_start();

	// Dépendances
	include 'lib/database.php';

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
		                'treso' => $data2['montant_treso_initial']
	                );         
				}	
				Database::disconnect();     	
		        // Redirection vers la home sécurisé            
		        header('Location:home.php');
	        }
	    } else {
	        //Utilisateur inconnu
	        $error_unknown = "Compte $email inconnu ou mot de passe invalide!";
	    }  
	}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Aboo</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" media="screen">
    <link href="../bootstrap/css/aboo.css" rel="stylesheet">
    <link href="../bootstrap/css/signin.css" rel="stylesheet">
    <link rel='stylesheet' id='google_fonts-css'  href='http://fonts.googleapis.com/css?family=PT+Sans|Lato:300,400|Lobster|Quicksand' type='text/css' media='all' />
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>

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
       </div>                   
      <!-- Liens -->
      <div class="collapse navbar-collapse" id="TOP">
        <ul class="nav navbar-nav">                              
        </ul>
      </div><!-- /.navbar-collapse -->
    </nav>    
   
    
	<!-- Affiche le formulaire de login --> 
	  <form class="form-signin" action="connexion.php" method="post">
	    <h3 class="form-signin-heading">Connectez-vous :</h3>
	
		<div class="control-group <?php echo !empty($error_unknown)?'has-error':'';?>">
	        <input name="email" type="text" class="form-control" placeholder="eMail" required autofocus>
	        <input name="password" type="password" class="form-control" placeholder="Mot de passe" required>
	        		            <?php if (isset($error_unknown)): ?>
			                        <span class="help-inline"><?php echo $error_unknown;?></span>
			                    <?php endif; ?>
		</div>
		<br>
	    <button class="btn btn-lg btn-success" type="submit"><span class="glyphicon glyphicon-log-in"> Connexion</button>
	    <a href="index.php" class="btn btn-lg btn-primary"><span class="glyphicon glyphicon-eject"></span> Retour</a>
	    
	    <br><br>
	    <span class="glyphicon glyphicon-user"></span> <a href="inscription.php"> Vous souhaitez créer un compte ?</a><br/>
	    <span class="glyphicon glyphicon-lock"></span> <a href="oubli.php"> Vous avez oublié votre mot de passe ?</a><br/>
	
	  </form>

    </div> <!-- /container -->
    
    <?php require 'footer.php'; ?>    

</body>
</html>