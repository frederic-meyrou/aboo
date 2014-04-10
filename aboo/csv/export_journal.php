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

// Récupération des variables de session
	include_once('../lib/var_session.php');
	
// Mode Debug
	$debug = false;

// Initialisation de la base
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
// Lecture dans la base des recettes et des dépenses (join sur user_id et exercice_id et mois) 
    $sql = "(SELECT date_creation, type, montant, commentaire, periodicitee FROM recette WHERE
    		user_id = :userid AND exercice_id = :exerciceid AND mois = :mois)
    		UNION
    		(SELECT date_creation, type, montant * -1, commentaire, periodicitee FROM depense WHERE
    		user_id = :userid AND exercice_id = :exerciceid AND mois = :mois )
    		ORDER BY date_creation
    		";
						
    $q = array('userid' => $user_id, 'exerciceid' => $exercice_id, 'mois' => MoisRelatif($abodep_mois,$exercice_mois));
    
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

$colonnes= array('Date de saisie','Type','Montant','Commentaire');
fputcsv($handle, $colonnes, ';', '"'); // Ajout des colonnes

foreach ($data as $row) { // Ajout des lignes, lecture BDD
		$ligne= array(date("d/m/Y H:i", strtotime($row['date_creation'])), NumToTypeRecette($row['type']), number_format($row['montant'],2,',',''), $row['commentaire']);
		fputcsv($handle, $ligne, ';', '"' );
}

ob_clean(); // On enlève tout ce qui traine du buffer écran!

header('Content-Description: File Transfer'); // Mode download
header('Content-Encoding: UTF-8');
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename='."Export-Journal-Mensuel-" . NumToMois($abodep_mois) . "-" . $exercice_annee . "-aboo.csv"); // Nommage du fichier en sortie
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