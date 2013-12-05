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
	$debug = true;
	
// Récupération des variables de session d'Authent
    $user_id = $_SESSION['authent']['id']; 

// Récupération des variables de session exercice
    $exercice_annee = null;
    $exercice_mois = null;
    $exercice_treso = null;
    if(isset($_SESSION['exercice'])) {
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
    if ( !empty($_GET['id'])) {
        $id = $_REQUEST['id'];
    }   
        
// Lecture et validation du POST
    if ( !empty($_POST)) {

        // Init base
        require_once 'database.php';
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // keep track validation errors
        $mois_debutError = null;
        $montant_treso_initialError = null;
                        
        // keep track post values
        $id = $_POST['id']; 
        $annee_debut = $_POST['annee_debut']; 
        $mois_debut = $_POST['mois_debut'];
        $montant_treso_initial = $_POST['montant_treso_initial'];
        
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
                <h3>Modification d'un exercice</h3>
            </div>

            <form class="form-horizontal" action="conf_update.php" method="post">
            
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
                                                
            <div class="form-actions">
              <button type="submit" class="btn btn-success">Mise à jour</button>
              <a class="btn btn-success" href="conf.php">Retour</a>
            </div>
            </form>
        </div> <!-- /span -->      			
    
    </div> <!-- /container -->
  </body>
</html>