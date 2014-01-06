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

// Initialisation de la base
    include_once 'database.php';
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
// Vérification du GET
    $id = null;
    if ( !empty($sGET['id'])) {
        $id = $sGET['id'];
    }  	

// POST	        	
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
		$id = $sPOST['id'];
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
			$sql = "UPDATE client set prenom=?,nom=?,email=?,telephone=?,mobile=?,adresse1=?,adresse2=?,cp=?,ville=?,age=?,profession=?,description=? WHERE id = ?";
			$q = $pdo->prepare($sql);
			$q->execute(array($prenomclient, $nomclient, $email, $telephone, $mobile, $adresse1, $adresse2, $cp, $ville, $age, $profession, $description, $id));
			Database::disconnect();
			// On retourne d'ou on vient
			header("Location: mesclients.php");
		}

	} else { // Affichage des données du formulaire
		$sql = "SELECT * FROM client where id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($id));
		$data = $q->fetch(PDO::FETCH_ASSOC);

        $id = $data['id'];       
		$nomclient = $data['nom'];
		$prenomclient = $data['prenom'];
		$email = $data['email'];
		$telephone = $data['telephone'];
		$mobile = $data['mobile'];
		$age = $data['age'];
		$profession = $data['profession'];
		$adresse1 = $data['adresse1'];
		$adresse2 = $data['adresse2'];
		$cp = $data['cp'];
		$ville = $data['ville'];
		$description = $data['description'];
		Database::disconnect();
	} // If POST
	
?>


<!DOCTYPE html>
<html lang="fr">
<?php require 'head.php'; ?>

<body>
    
    <script src="bootstrap/js/jquery-2.0.3.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>

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
          <li><a href="journal.php"><span class="glyphicon glyphicon-th-list"></span> Recettes & Dépenses</a></li>
          <li><a href="bilan.php"><span class="glyphicon glyphicon-calendar"></span> Bilan</a></li>
          <li><a href="paiements.php"><span class="glyphicon glyphicon-euro"></span> Paiements</a></li>
          <li class="active"><a href="mesclients.php"><span class="glyphicon glyphicon-star"></span> Clients</a></li>                           
          <li class="dropdown">
	        <!-- Affiche le nom de l'utilisateur à droite de la barre de Menu -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> <?php echo ucfirst($prenom) . ' ' . ucfirst($nom); ?><b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li><a href="conf.php"><span class="glyphicon glyphicon-wrench"></span> Configuration</a></li>
              <li><a href="debug.php"><span class="glyphicon glyphicon-info-sign"></span> Debug</a></li>  
              <li><a href="deconnexion.php"><span class="glyphicon glyphicon-off"></span> Deconnexion</a></li>
            </ul> 
          </li>
          <li><a href="deconnexion.php"><span class="glyphicon glyphicon-off"></span></a></li>      
        </ul>
      </div><!-- /.navbar-collapse -->
    </nav>
        
    <div class="container">

		<h3>Modification d'un client</h3>

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
 			 			<form class="form-horizontal" action="client_update.php" method="post">
						
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
		
				       		<input type="hidden" name="id" value="<?php echo $id; ?>">							

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
							  <button type="submit" class="btn btn-warning"><span class="glyphicon glyphicon-check"></span> Mise à jour</button>
							  <a class="btn btn-primary" href="mesclients.php"><span class="glyphicon glyphicon-chevron-up"></span> Retour</a>
						    </div>
						</form>
	   		 </div> <!-- /col -->    
		</div> <!-- /row -->
    </div> <!-- /container -->
  </body>
</html>