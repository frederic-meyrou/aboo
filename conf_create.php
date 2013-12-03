<?php
// Vérification de l'Authent
    session_start();
    require('authent.php');
    if( !Authent::islogged()){
        // Non authentifié on repart sur la HP
        header('Location:index.php');
    }
// Récupération des variables de session d'Authent
    $user_id = $_SESSION['authent']['id'];
 
// Lecture et validation du POST
	if ( !empty($_POST)) {

        // Init base
        require_once 'database.php';
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    
		// keep track validation errors
		$annee_debutError = null;
        $mois_debutError = null;
        $montant_treso_initialError = null;
                		
		// keep track post values
		$annee_debut = $_POST['annee_debut'];
		$mois_debut = $_POST['mois_debut'];
        $montant_treso_initial = $_POST['montant_treso_initial'];
        
		// validate input
		$valid = true;
        if (empty($annee_debut)) {
			$annee_debutError = 'Veuillez entrer l\'année de démarrage de votre exercice';
			$valid = false;
		}
        if ($annee_debut < 2000 || $annee_debut > 2100) {
            $annee_debutError = 'Veuillez entrer une année correcte';
            $valid = false;
        }
        if (empty($mois_debut)) {
            $mois_debutError = 'Veuillez entrer le mois de démarrage de votre exercice';
            $valid = false;
        }
        if ($mois_debut < 1 || $mois_debut > 12) {
            $mois_debutError = 'Veuillez entrer un mois compris entre 1 et 12';
            $valid = false;
        }
        if (empty($montant_treso_initial)) {
			$montant_treso_initial = 0;
		}
        
        // Verif que l'année n'est pas déjà en base!
            $sql = "SELECT * from exercice WHERE 'user_id'=? AND 'annee_debut'=?";
            $q = $pdo->prepare($sql);         
            $q->execute(array($user_id, $annee_debut));
            $data = $q->fetch(PDO::FETCH_ASSOC);
            $count = $q->rowCount($sql);
            if (!$count==0) {
                $annee_debutError = 'Vous avez déjà créé cet exercice';
                $valid = false;                
            }
	
		// Creation des données en base et redirection vers appelant
		if ($valid) {
			$sql = "INSERT INTO exercice (user_id,mois_debut,montant_treso_initial,annee_debut) values(?, ?, ?, ?)";
			$q = $pdo->prepare($sql);
			$q->execute(array($user_id, $mois_debut, $montant_treso_initial, $annee_debut));
			Database::disconnect();
			header("Location: conf.php");
		}       
	} else {
	    // Valeures par défaut
	    $annee_debut = 2013;
        $mois_debut = 1;
        $montant_treso_initial = 0; 
	}
	
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <title>GestAbo</title>
    <meta charset="utf-8">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" media="screen">
</head>

<body>

    <script src="bootstrap/js/jquery-2.0.3.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <div class="container">
        <h2>Console</h2>
        <ul class="nav nav-pills">
          <li><a href="home.php">Console</a></li>
          <li><a href="abodep.php">Editer abonnements et dépenses</a></li>
          <li><a href="meusuel.php">Bilan Mensuel</a></li>
          <li><a href="bilan.php">Bilan Annuel</a></li>
          <li><a href="encaissements.php">Encaissements</a></li>
          <li><a href="paiements.php">Paiements</a></li>
          <li class="active"><a href="conf.php">Configuration</a></li>
          <li><a href="deconnexion.php">Deconnexion</a></li>
        </ul>

		<div class="span10 offset1">
			<div class="row">
    			<h3>Création d'un exercice</h3>
    		</div>

			<form class="form-horizontal" action="conf_create.php" method="post">
			
			<?php function Affiche_Champ(&$champ, &$champError, $champinputname, $champplaceholder, $type) { ?>
			<div class="control-group <?php echo !empty($champ)?'error':'';?>">
			    <label class="control-label"><?php echo "$champplaceholder" ?></label>
			    <div class="controls">
			      	<input name="<?php echo "$champinputname" ?>" type="<?php echo "$type" ?>" placeholder="<?php echo "$champplaceholder" ?>" value="<?php echo !empty($champ)?$champ:'';?>">
			      	<?php if (!empty($champError)): ?>
			      		<span class="help-inline"><?php echo $champError;?></span>
			      	<?php endif; ?>
			    </div>
			</div>
			<?php } ?>

            <?php Affiche_Champ($annee_debut, $annee_debutError, 'annee_debut','Année de votre exercice', 'number' ); ?>
		    <?php Affiche_Champ($mois_debut, $mois_debutError, 'mois_debut','Mois débutant votre exercice', 'number' ); ?>
		    <?php Affiche_Champ($montant_treso_initial, $montant_treso_initialError, 'montant_treso_initial','Montant de votre trésorerie en début d\'exercice', 'text' ); ?>
		  
		    <div class="form-actions">
			  <button type="submit" class="btn btn-success">Créer</button>
			  <a class="btn btn-success" href="conf.php">Retour</a>
			</div>
		    </form>
		</div> <!-- /span -->
				
    </div> <!-- /container -->
  </body>
</html>