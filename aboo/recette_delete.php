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
        header('Location:../index.php');
    }

// Récupération des variables de session
	include_once('lib/var_session.php');
	
// Mode Debug
	$debug = false;

// Sécurisation POST & GET
    foreach ($_GET as $key => $value) {
        $sGET[$key]=htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
    foreach ($_POST as $key => $value) {
        $sPOST[$key]=htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

// Initialisation de la base
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
// Lecture et validation du POST
	if ( !empty($sPOST)) {
		// keep track post values
		$id = $sPOST['id'];

        // Suppression des paiements associés (par trigger)
        //$sql1 = "DELETE FROM paiement WHERE recette_id = ?";
        //$q = $pdo->prepare($sql1);
        //$q->execute(array($id));  
        		
		// suppression de la recette
		$sql2 = "DELETE FROM recette WHERE id = ?";
		$q = $pdo->prepare($sql2);
		$q->execute(array($id));
                
		Database::disconnect();
	} 

    // On repart d'ou on viens
    header("Location: recette.php");    
?>