<!-- 
© Copyright : Aboo / www.aboo.fr : Frédéric MEYROU : tous droits réservés
-->
<?php
// Dépendances
	require_once('../lib/fonctions.php');
    include_once('../lib/database.php');
	
// Vérification de l'Authent
    session_start();
    require('../lib/authent.php');
    if( !Authent::islogged()){
        // Non authentifié on repart sur la HP
        header('Location:../../index.php');
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

// Récupération des variables de session abodep
    $abodep_mois = null;
    if(isset($_SESSION['abodep'])) {
        $abodep_mois = $_SESSION['abodep']['mois'];
    }
	
// Initialisation de la base
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
// Lecture BDD		
	$sql = "SELECT * FROM client WHERE
    		user_id = :userid
    		";
    $q = array('userid' => $user_id);	
    
	$req = $pdo->prepare($sql);
	$req->execute($q);
	$data = $req->fetchAll(PDO::FETCH_ASSOC);
    $count = $req->rowCount($sql);
	Database::disconnect();		
	    	
	if ($count==0) { // Il n'y a rien à afficher
        exit;           
    }
	
// --- DEBUT STRUCTURE CSV

$file = getcwd() . '/' . uniqid() . '.csv';
$handle = fopen($file, 'w'); //fichier temp

$colonnes= array('Prénom','Nom','eMail','Téléphone Fixe','Téléphone mobile','Age','Profession','Adresse ligne 1','Adresse ligne 2','CP','Ville','Description','Date de création');
fputcsv($handle, $colonnes, ';', '"'); // Ajout des colonnes

foreach ($data as $row) { // Ajout des lignes, lecture BDD
		$ligne= array(ucfirst($row['prenom']), ucfirst($row['nom']), $row['email'], "=\"".$row['telephone']."\"" , "=\"".$row['mobile']."\"" , $row['age'],
		$row['profession'], $row['adresse1'], $row['adresse2'], $row['cp'], $row['ville'], $row['description'], date("d/m/Y H:i", strtotime($row['date_creation'])));
		fputcsv($handle, $ligne, ';', '"' );
}

ob_clean(); // On enlève tout ce qui traine du buffer écran!

header('Content-Description: File Transfer'); // Mode download
header('Content-Encoding: UTF-8');
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename='."Export-Clients-Aboo.csv"); // Nommage du fichier en sortie
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0'); // désactivation du cache
header('Pragma: public');
header('Content-Length: ' . filesize($file)); // Taille du fichier à télécharger
echo "\xEF\xBB\xBF"; // UTF-8 BOM

readfile($file); // Envoi le fichier
fclose($handle);
unlink($file); // efface le fichier temporaire

exit;
// --- FIN STRUCTURE CSV
?>