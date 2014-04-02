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
		
		// delete data : supprime l'enregistrement de la table client
		$sql = "DELETE FROM client WHERE id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($id));
		
		// update recette (Supprime le client_id de tout la table recette pour le user courant)
		$sql2 = "UPDATE recette set client_id=null WHERE client_id = ? AND user_id = ?";
		$q2 = $pdo->prepare($sql2);
		$q2->execute(array($id,$user_id));					
	} 
	Database::disconnect();
		
// On repart d'ou on viens
	header("Location: mesclients.php");	
?>