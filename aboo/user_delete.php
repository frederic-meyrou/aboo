<!-- 
© Copyright : Aboo / www.aboo.fr : Frédéric MEYROU : tous droits réservés
-->
<?php 
// Dépendances
	require_once('lib/database.php');
	require_once('lib/fonctions.php');

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
	        	
// POST	
	if ( !empty($sPOST)) {
		// keep track post values
		$id = $sPOST['id'];
		
		// delete data
		$sql = "DELETE FROM user WHERE id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($id));
	} 
// On repart d'ou on vient
	Database::disconnect();
	header("Location: user.php");	
?>