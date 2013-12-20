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

// Sécurisation POST & GET
    foreach ($_GET as $key => $value) {
        $sGET[$key]=htmlentities($value, ENT_QUOTES);
    }
    foreach ($_POST as $key => $value) {
        $sPOST[$key]=htmlentities($value, ENT_QUOTES);
    }
        	
// Récupération des variables de session d'Authent
    $user_id = $_SESSION['authent']['id']; 
    $nom = $_SESSION['authent']['nom'];
    $prenom = $_SESSION['authent']['prenom'];
    $nom = $_SESSION['authent']['nom'];

// Récupération des variables de session exercice
    if(isset($_SESSION['exercice'])) {
        $exercice_id = $_SESSION['exercice']['id'];
        $exercice_annee = $_SESSION['exercice']['annee'];
        $exercice_mois = $_SESSION['exercice']['mois'];
        $exercice_treso = $_SESSION['exercice']['treso'];	
    } else { // On a pas de session on retourne vers la gestion d'exercice
    	header("Location: conf.php");    	
    }

// Récupération des variables de session abodep
    $abodep_mois = null;
    if(!empty($_SESSION['abodep']['mois'])) {
        $abodep_mois = $_SESSION['abodep']['mois'];
    } else { // On a pas de session avec le mois on retourne d'ou on vient
    	header("Location: journal.php");
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
        
// Lecture et validation du POST
    if ( !empty($sPOST)) {

        // Init base
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // keep track validation errors
        $montantError = null;
		$commentaireError = null;
                        
        // keep track post values
        $id = $sPOST['id']; 
        $montant = $sPOST['montant']; 
        $commentaire = $sPOST['commentaire'];
		$type = $sPOST['type'];
		
		// validate input
		$valid = true;
		
		if (empty($montant) || $montant < 0 || $montant == null) {
			$montantError= "Veuillez entrer un montant positif.";
			$valid = false;
		}
           
        // Modif des données en base et redirection vers appelant
        if ($valid) {
            $sql = "UPDATE depense SET montant=?,commentaire=?, type=? WHERE id = ?";
            $q = $pdo->prepare($sql);
            $q->execute(array($montant, $commentaire, $type, $id));
            Database::disconnect();        
            header("Location: dep.php");
        }       
    } else {
        // Lecture des infos ds la base
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM depense where id = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
        $data = $q->fetch(PDO::FETCH_ASSOC);
        $id = $data['id'];   		
		$montant = $data['montant'];
        $commentaire = $data['commentaire'];
		$type = $data['type'];     
        Database::disconnect();                
    }
    
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Aboo</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" media="screen">
    <link href="bootstrap/css/aboo.css" rel="stylesheet">
    <link rel='stylesheet' id='google_fonts-css'  href='http://fonts.googleapis.com/css?family=PT+Sans|Lato:300,400|Lobster|Quicksand' type='text/css' media='all' />
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>

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
          <li class="active"><a href="journal.php"><span class="glyphicon glyphicon-th-list"></span> Recettes & Dépenses</a></li>
          <li><a href="bilan.php"><span class="glyphicon glyphicon-calendar"></span> Bilan</a></li>
          <li><a href="encaissements.php"><span class="glyphicon glyphicon-credit-card"></span> Encaissements</a></li>
          <li><a href="paiements.php"><span class="glyphicon glyphicon-euro"></span> Paiements</a></li>
          <li><a href="mesclients.php"><span class="glyphicon glyphicon-star"></span> Clients</a></li>                           
          <li class="dropdown">
	        <!-- Affiche le nom de l'utilisateur à droite de la barre de Menu -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> <?php echo ucfirst($prenom) . ' ' . ucfirst($nom); ?><b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li><a href="conf.php"><span class="glyphicon glyphicon-wrench"></span> Configuration</a></li>
              <li><a href="deconnexion.php"><span class="glyphicon glyphicon-off"></span> Deconnexion</a></li>  
            </ul> 
          </li>
          <li><a href="deconnexion.php"><span class="glyphicon glyphicon-off"></span></a></li>      
        </ul>
      </div><!-- /.navbar-collapse -->
    </nav>
        
    <div class="container">
        
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
                <h3>Modification d'une dépense</h3>
		        <!-- Formulaire -->  
	            <form class="form-horizontal" action="dep_update.php" method="post">
	            
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
		       		
		       		<input type="hidden" name="id" value="<?php echo $id; ?>">
		       		
		            <div class="form-group">
		            		<label class="control-label">Type</label>
		                    <select name="type" class="form-control">
				            <?php
				                foreach ($Liste_Depense as $d) {
				            ?>
				                <option value="<?php echo TypeDepenseToNum($d);?>"<?php echo (TypeDepenseToNum($d)==$type)?'selected':'';?>> 
				                	<?php echo $d;?>
				                </option>    
				            <?php
				                } // foreach   
				            ?>
		                    </select>
		            </div>	
        		    <?php Affiche_Champ($montant, $montantError, 'montant','Montant €', 'text' ); ?>
		       		<?php Affiche_Champ($commentaire, $commmentaireError, 'commentaire','Commentaire', 'text' ); ?>
		                                                
		            <div class="form-actions">
		              <button type="submit" class="btn btn-warning"><span class="glyphicon glyphicon-check"></span> Mise à jour</button>
		              <a class="btn btn-primary" href="dep.php"><span class="glyphicon glyphicon-chevron-up"></span> Retour</a>
		            </div>
	            </form>
	            
	   		 </div> <!-- /col -->    			
	    </div> <!-- /row -->     			
    
    </div> <!-- /container -->
  </body>
</html>