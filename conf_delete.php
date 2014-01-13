<!-- 
© Copyright : Aboo / www.aboo.fr : Frédéric MEYROU : tous droits réservés
-->
<?php
// Dépendances
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
    
// Lecture et validation du POST
	if ( !empty($sPOST)) {
		// keep track post values
		$id = $sPOST['id'];
        $annee = $sPOST['annee'];

		if ($exercice_annee != $annee) {
			
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
	
	        // Suppression depenses et recettes
	        $sql3 = "DELETE FROM recette WHERE exercice_id = ?";
	        $req = $pdo->prepare($sql3);
	        $req->execute(array($id));
	
	        $sql4 = "DELETE FROM depense WHERE exercice_id = ?";
	        $req = $pdo->prepare($sql4);
	        $req->execute(array($id));
	
	        // 	Suppression des paiements (par trigger)	
		}
	} 

// On retourne d'ou on vient
    Database::disconnect();                    
	header("Location: conf.php");
?>