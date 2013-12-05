<?php
// Vérification de l'Authent
    session_start();
    require('authent.php');
    if( !Authent::islogged()){
        // Non authentifié on repart sur la HP
        header('Location:index.php');
    }

// Mode Debug
	$debug = true;
	
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
        if (empty($montant_treso_initial)) {
			$montant_treso_initial = 0;
		}
        
        // Verif que l'année n'est pas déjà en base!
            $sql = "SELECT COUNT(*) FROM exercice WHERE user_id=? AND annee_debut=?";
            $q = $pdo->prepare($sql);         
            $q->execute(array($user_id, $annee_debut));
            $data = $q->fetch(PDO::FETCH_ASSOC);
            if (!$data["COUNT(*)"]==0) {
                $annee_debutError = 'Vous avez déjà créé cet exercice';
                $valid = false;
            }
	
		// Creation des données en base et redirection vers appelant
		if ($valid) {
			$sql = "INSERT INTO exercice (user_id,mois_debut,montant_treso_initial,annee_debut) values(?, ?, ?, ?)";
			$q = $pdo->prepare($sql);
			$q->execute(array($user_id, $mois_debut, $montant_treso_initial, $annee_debut));
			Database::disconnect();
            // On modifie la valeure de la session
            $_SESSION['exercice'] = array(
            'annee' => $data['annee_debut'],
            'mois' => $data['mois_debut'],
            'treso' => $data['montant_treso_initial']
            );            
			header("Location: conf.php");
		}       
	} else {
	    // Valeures par défaut
	    $annee_debut = date('Y');
        $mois_debut = date('n');
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

        <!-- Affiche les informations de debug -->
        <?php 
 		if ($debug) {
		?>
        <div class="alert alert alert-error alert-dismissable fade in">
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

		<div class="span10 offset1">
			<div class="row">
    			<h3>Création d'un exercice</h3>
    		</div>

			<form class="form-horizontal" action="conf_create.php" method="post">
			
			<?php function Affiche_Champ(&$champ, &$champError, $champinputname, $champplaceholder, $type) { ?>
			<div class="control-group <?php echo !empty($champError)?'error':'';?>">
			    <label class="control-label"><?php echo "$champplaceholder" ?></label>
			    <div class="controls">
			      	<input name="<?php echo "$champinputname" ?>" type="<?php echo "$type" ?>" value="<?php echo !empty($champ)?$champ:'';?>">
			      	<?php if (!empty($champError)): ?>
			      		<span class="help-inline"><?php echo $champError;?></span>
			      	<?php endif; ?>
			    </div>
			</div>
			<?php } ?>

            <div class="control-group <?php echo !empty($annee_debutError)?'error':'';?>">
                <label class="control-label">Année de départ</label>
                <div class="controls">
                    <input name="annee_debut" type="number" value="<?php echo !empty($annee_debut)?$annee_debut:'';?>">
                    <?php if (!empty($annee_debutError)): ?>
                        <span class="help-inline"><?php echo $annee_debutError;?></span>
                    <?php endif; ?>
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
                                                
		    <div class="form-actions">
			  <button type="submit" class="btn btn-success">Créer</button>
			  <a class="btn btn-success" href="conf.php">Retour</a>
			</div>
		    </form>
		</div> <!-- /span -->
				
    </div> <!-- /container -->
  </body>
</html>