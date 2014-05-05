<!-- 
© Copyright : Aboo / www.aboo.fr : Frédéric MEYROU : tous droits réservés
-->
<?php
// Dépendances
	require_once('../lib/fonctions.php');
    include_once('../lib/database.php');  
    include_once('../lib/calcul_bilan.php');
    
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
       	
// Charge le Tableau    
    $TableauFiscalAnnuel = CalculTableauFiscalAnnuel($user_id, $exercice_annee);  
	    
	if ($TableauFiscalAnnuel==false) { // Il n'y a rien à afficher
        exit;      
    } 

// --- DEBUT STRUCTURE CSV

$file = getcwd() . '/' . uniqid() . '.csv';
$handle = fopen($file, 'w'); //fichier temp

$colonnes= array('Date de saisie','mois','Type','Montant','Périodicitée','Commentaire');
fputcsv($handle, $colonnes, ';', '"'); // Ajout des colonnes

foreach ($TableauFiscalAnnuel["TABLEAU"] as $row) { // Ajout des lignes, lecture BDD
		$ligne= array($row['DATE'], $row['MOIS'], $row['TYPE'], str_replace(".", "", $row['MONTANT']), $row['PERIODICITE'], $row['COMMENTAIRE']);
		fputcsv($handle, $ligne, ';', '"' );
}

ob_clean(); // On enlève tout ce qui traine du buffer écran!

header('Content-Description: File Transfer'); // Mode download
header('Content-Encoding: UTF-8');
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename='."Export-Journal-Annuel-" . $exercice_annee . "-aboo.csv"); // Nommage du fichier en sortie
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