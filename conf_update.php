<!-- 
© Copyright : Aboo / www.aboo.fr : Frédéric MEYROU : tous droits réservés
-->
<?php
// Dépendances
	require_once('lib/fonctions.php');
    include_once('lib/database.php');

// Vérification de l'Authent
    session_start();
    require('lib/authent.php');
    if( !Authent::islogged()){
        // Non authentifié on repart sur la HP
        header('Location:index.php');
    }

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
        require_once 'lib/database.php';
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
<?php require 'head.php'; ?>

<body>

    <?php $page_courante = "conf.php"; require 'nav.php'; ?>
        
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

		<div class="row">
 			 <div class="col-md-5 col-md-offset-1">
		        <!-- Formulaire -->                	
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
		                    <input name="annee_debut" class="form-control" type="text" value="<?php echo !empty($annee_debut)?$annee_debut:'';?>"  disabled>
		                </div>
		            </div>
		            
		            <div class="control-group">
		                <label class="control-label">Mois de démarage</label>
		                    <select name="mois_debut" class="form-control" >
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
		
		            <div class="control-group ">
		                <label class="control-label">Montant de votre trésorerie en début d'exercice</label>
		                <div class="controls">
		                    <input name="montant_treso_initial" class="form-control" type="text" value="<?php echo $montant_treso_initial;?>">
		                </div>
		            </div>
		            
		            <br>                                    
		            <div class="form-actions">
		              <button type="submit" class="btn btn-warning"><span class="glyphicon glyphicon-check"></span> Mise à jour</button>
		              <a class="btn btn-primary" href="conf.php"><span class="glyphicon glyphicon-chevron-up"></span> Retour</a>
		            </div>
		        </form>

	   		 </div> <!-- /col -->    			
	    </div> <!-- /row -->    
    </div> <!-- /container -->

    <?php require 'footer.php'; ?>
        
  </body>
</html>