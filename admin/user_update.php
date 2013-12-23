<!-- 
© Copyright : Aboo / www.aboo.fr : Frédéric MEYROU : tous droits réservés
-->
<?php 
// Vérification de l'Authent
    session_start();
    require('../authent.php');
    if( !Authent::islogged()){
        // Non authentifié on repart sur la HP
        header('Location:../index.php');
    }
			
// Dépendances
	require_once('../fonctions.php');
	require_once('../database.php');
	
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
        $adminError = null;
        		
		// keep track post values
		$password = $sPOST['password'];
		$nom = $sPOST['nom'];
		$prenom = $sPOST['prenom'];
		$email = $sPOST['email'];
		$telephone = $sPOST['telephone'];
		$inscription = $sPOST['inscription'];
		$expiration = $sPOST['expiration'];
		$montant = $sPOST['montant'];
        $administrateur = $sPOST['administrateur'];
		
		// validate input
		$valid = true;		
		
		// update data
		if ($valid) {
			$pdo = Database::connect();
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = "UPDATE user set prenom=?,nom=?,email=?,telephone=?,password=?,inscription=?,montant=?,expiration=?,administrateur=? WHERE id = ?";
			$q = $pdo->prepare($sql);
			$q->execute(array($prenom, $nom, $email, $telephone, $password, $inscription, $montant, $expiration, $administrateur, $id));
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
		
		Database::disconnect();
	}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Aboo</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" media="screen">
    <link href="../bootstrap/css/aboo.css" rel="stylesheet">
    <link rel='stylesheet' id='google_fonts-css'  href='http://fonts.googleapis.com/css?family=PT+Sans|Lato:300,400|Lobster|Quicksand' type='text/css' media='all' />
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>

<body>
    
    <script src="../bootstrap/js/jquery-2.0.3.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>

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
          <a class="navbar-brand" href="home.php">Aboo</a>
      </div>     
      <!-- Liens -->
      <div class="collapse navbar-collapse" id="TOP">
        <ul class="nav navbar-nav">
          <li class="active"><a href="user.php"><span class="glyphicon glyphicon-wrench"></span> Gestion utilisateurs</a></li>                 
          <li class="dropdown">
	        <!-- Affiche le nom de l'utilisateur à droite de la barre de Menu -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> <?php echo ucfirst($prenom) . ' ' . ucfirst($nom); ?><b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li><a href="../deconnexion.php"><span class="glyphicon glyphicon-off"></span> Deconnexion</a></li>  
            </ul> 
          </li>
          <li><a href="../deconnexion.php"><span class="glyphicon glyphicon-off"></span></a></li>      
        </ul>
      </div><!-- /.navbar-collapse -->
    </nav>    

        
    <div class="container">   
		<h3>Mise à jour utilisateur</h3>
        
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
					
	                <?php Affiche_Champ($email, $emailError, 'email','eMail', 'mail' ); ?>
					<?php Affiche_Champ($password, $passwordError, 'password','Mot de passe', 'password' ); ?>
					<?php Affiche_Champ($nom, $nomError, 'nom','Nom', 'text' ); ?>
					<?php Affiche_Champ($prenom, $prenomError, 'prenom','Prénom', 'text' ); ?>
					<?php Affiche_Champ($telephone, $telephoneError, 'telephone','Téléphone', 'tel' ); ?>
					<?php Affiche_Champ($inscription, $inscriptionError, 'inscription','Inscription', 'date' ); ?>
					<?php Affiche_Champ($expiration, $expirationError, 'expiration','Expiration', 'date' ); ?>
					<?php Affiche_Champ($montant, $montantError, 'montant','Montant', 'text' ); ?>
	                <?php Affiche_Champ($administrateur, $adminError, 'administrateur','Administrateur', 'number' ); ?>
	                					
				    <div class="form-actions">
				      <br>  
			              <button type="submit" class="btn btn-warning"><span class="glyphicon glyphicon-check"></span> Mise à jour</button>
			              <a class="btn btn-primary" href="user.php"><span class="glyphicon glyphicon-chevron-up"></span> Retour</a>
					</div>
				</form>
				
	   		 </div> <!-- /col -->    			
	    </div> <!-- /row -->   
				
    </div> <!-- /container -->
  </body>
</html>