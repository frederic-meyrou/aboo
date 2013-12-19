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

// Récupération des variables de session exercice
    $exercice_id = null;
    $exercice_annee = null;
    $exercice_mois = null;
    $exercice_treso = null;
    if(isset($_SESSION['exercice'])) {
        $exercice_id = $_SESSION['exercice']['id'];
        $exercice_annee = $_SESSION['exercice']['annee'];
        $exercice_mois = $_SESSION['exercice']['mois'];
        $exercice_treso = $_SESSION['exercice']['treso'];
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
        require_once 'database.php';
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // keep track validation errors
        $mois_debutError = null;
        $montant_treso_initialError = null;
                        
        // keep track post values
        $id = $sPOST['id']; 
        $annee_debut = $sPOST['annee_debut']; 
        $mois_debut = $sPOST['mois_debut'];
        $montant_treso_initial = $sPOST['montant_treso_initial'];
        
        // validate input
        $valid = true;
        if (empty($montant_treso_initial)) {
            $montant_treso_initial = 0;
        }
           
        // Creation des données en base et redirection vers appelant
        if ($valid) {
            $sql = "UPDATE exercice SET mois_debut=?,montant_treso_initial=? WHERE id = ?";
            $q = $pdo->prepare($sql);
            $q->execute(array($mois_debut, $montant_treso_initial, $id));
            Database::disconnect();
            // On modifie la session
            if ($data['annee_debut'] == $exercice_annee) {
                $_SESSION['exercice'] = array(
                'id' => $exercice_id,
                'annee' => $exercice_annee,
                'mois' => $data['mois_debut'],
                'treso' => $data['montant_treso_initial']
                );
            }            
            header("Location: conf.php");
        }       
    } else {
        // Lecture des infos ds la base
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM exercice where id = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
        $data = $q->fetch(PDO::FETCH_ASSOC);
		$annee_debut = $data['annee_debut'];
        $mois_debut = $data['mois_debut'];
        $montant_treso_initial = $data['montant_treso_initial'];         
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
      <!-- Marque -->
      <div class="navbar-header">
        <a class="navbar-brand" href="home.php">Aboo</a>
      </div>     
      <!-- Liens -->
      <div class="collapse navbar-collapse" id="TOP">
        <ul class="nav navbar-nav">
          <li><a href="abodep.php">Recettes & Dépenses</a></li>
          <li><a href="meusuel.php">Bilan Mensuel</a></li>
          <li><a href="bilan.php">Bilan Annuel</a></li>
          <li><a href="encaissements.php">Encaissements</a></li>
          <li><a href="paiements.php">Paiements</a></li>
          <li class="active"><a href="conf.php">Configuration</a></li>
          <li><a href="deconnexion.php">Deconnexion</a></li>                    
           <!--<li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Menu Dropdown <b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li><a href="#">Action</a></li>
              <li class="divider"></li>
              <li><a href="#">Action</a></li>
            </ul>
          </li>-->
        </ul>
          <!-- Affiche le nom de l'utilisateur à droite de la barre de Menu -->
          <p class="navbar-text navbar-right"><span class="glyphicon glyphicon-user"></span> <?php echo ucfirst($prenom) . ' ' . ucfirst($nom); ?></p>
      </div><!-- /.navbar-collapse -->
    </nav>
        
    <div class="container">
        <h2>Modification d'un exercice</h2>
        <br>
        
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
                	
        <form class="form-horizontal" action="conf_update.php" method="post">
        
            <?php function Affiche_Champ(&$champ, &$champError, $champinputname, $champplaceholder, $type) { ?>
            <div class="control-group <?php echo !empty($champError)?'has-error':'';?>">
                <label class="control-label"><?php echo "$champplaceholder" ?></label>
                <div class="controls">
                    <input name="<?php echo "$champinputname" ?>" type="<?php echo "$type" ?>" value="<?php echo !empty($champ)?$champ:'';?>">
                    <?php if (!empty($champError)): ?>
                        <span class="help-inline"><?php echo $champError;?></span>
                    <?php endif; ?>
                </div>
            </div>
            <?php } ?>
       		
       		<input type="hidden" name="id" value="<?php echo $id; ?>">
       		
       		<div class="control-group">
                <label class="control-label">Année de départ</label>
                <div class="controls">
                    <input name="annee_debut" type="text" value="<?php echo !empty($annee_debut)?$annee_debut:'';?>" readonly="readonly">
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label">Mois de démarage</label>
                <div class="controls">
                    <select name="mois_debut" >
                        <option value="1" <?php echo ($mois_debut==1)?'selected':'';?>>Janvier</option>
                        <option value="2" <?php echo ($mois_debut==2)?'selected':'';?>>Février</option>
                        <option value="3" <?php echo ($mois_debut==3)?'selected':'';?>>Mars</option>
                        <option value="4" <?php echo ($mois_debut==4)?'selected':'';?>>Avril</option>
                        <option value="5" <?php echo ($mois_debut==5)?'selected':'';?>>Mai</option>
                        <option value="6" <?php echo ($mois_debut==6)?'selected':'';?>>Juin</option>
                        <option value="7" <?php echo ($mois_debut==7)?'selected':'';?>>Juillet</option>                    
                        <option value="8" <?php echo ($mois_debut==8)?'selected':'';?>>Août</option>
                        <option value="9" <?php echo ($mois_debut==9)?'selected':'';?>>Septembre</option>
                        <option value="10" <?php echo ($mois_debut==10)?'selected':'';?>>Octobre</option>
                        <option value="11" <?php echo ($mois_debut==11)?'selected':'';?>>Novembre</option>
                        <option value="12" <?php echo ($mois_debut==12)?'selected':'';?>>Décembre</option>
                    </select>
                </div>
            </div>

            <div class="control-group ">
                <label class="control-label">Montant de votre trésorerie en début d'exercice</label>
                <div class="controls">
                    <input name="montant_treso_initial" type="text" value="<?php echo $montant_treso_initial;?>">
                </div>
            </div>
            
            <br>                                    
            <div class="form-actions">
              <button type="submit" class="btn btn-success">Mise à jour</button>
              <a class="btn btn-success" href="conf.php">Retour</a>
            </div>
        </form>
  			
    
    </div> <!-- /container -->
  </body>
</html>