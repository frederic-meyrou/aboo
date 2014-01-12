<!-- 
© Copyright : Aboo / www.aboo.fr : Frédéric MEYROU : tous droits réservés
-->
<?php
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
    	
// Récupère l'ID de l'exercice à supprimer en GET
	if ( !empty($sGET['id']) && !empty($sGET['annee'])) {
		$id = $sGET['id'];
		$annee = $sGET['annee'];
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
    include_once 'lib/database.php';
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
// Lecture et validation du POST
	if ( !empty($sPOST)) {
		// keep track post values
		$id = $sPOST['id'];
        $annee = $sPOST['annee'];

        // Suppression Exercice
        $sql = "DELETE FROM exercice WHERE id = ?";
        $req = $pdo->prepare($sql);
        $req->execute(array($id));   
        		
        // On test si les information courante de l'utilisateur sont toujours valides sinon on les effaces
        $sql2 = "SELECT * FROM user WHERE id = ?";
        $req = $pdo->prepare($sql2);
        $req->execute(array($user_id)); 
        $data2 = $req->fetch(PDO::FETCH_ASSOC);
        
        if ($data2['exerciceid_encours'] == $id) { 
            $sql3 = "UPDATE user SET exerciceid_encours = ?, mois_encours = ?  WHERE id = ?";
            $req = $pdo->prepare($sql3);
            $req->execute(array(NULL,NULL,$user_id));
            // On supprimer la session ABODEP
            $_SESSION['abodep'] = array();          
        }        
        
        // On supprime la session
        if ($annee == $exercice_annee) {
            $_SESSION['exercice'] = array();
        }
        
        // On retourne d'ou on vient
        Database::disconnect();                    
		header("Location: conf.php");
		
	} 
?>

<!DOCTYPE html>
<html lang="fr">
<?php require 'head.php'; ?>

<body>

    <?php $page_courante = "conf.php"; require 'nav.php'; ?>
        
    <div class="container">
        <h2>Suppression d'un exercice</h2>
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
        
		<div class="span10 offset1">
			<form class="form-horizontal" action="conf_delete.php" method="post">
			  <input type="hidden" name="id" value="<?php echo $id;?>"/>
              <input type="hidden" name="annee" value="<?php echo $annee;?>"/>			  
			  <p class="alert alert-danger">Confirmation de la suppression ?</p>
				<div class="form-actions">
				  <button type="submit" class="btn btn-danger">Oui</button>
				  <a class="btn" href="conf.php">Non</a>
				</div>
			</form>
			
		</div> <!-- /span10 -->		
    </div> <!-- /container -->

    <?php require 'footer.php'; ?>
        
  </body>
</html>