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
        header('Location:../index.php');
    }

// Récupération des variables de session
	include_once('lib/var_session.php');
	
// Mode Debug
	$debug = false;
       	
// Initialisation de la base
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
// Lecture dans la base des recettes de l'année en fiscal de début d'exercice
    $sql = "(SELECT recette.date_creation, type, montant, commentaire, periodicitee, mois FROM recette,exercice WHERE
            (exercice.annee_debut = :annee 
            AND exercice.user_id = :userid
            AND recette.exercice_id = exercice.id
            AND recette.user_id = exercice.user_id
            AND recette.mois > (13 - exercice.mois_debut) )
            AND recette.mois <= 12 
            OR (exercice.annee_debut = (:annee - 1)
            AND exercice.user_id = :userid
            AND recette.exercice_id = exercice.id
            AND recette.user_id = exercice.user_id
            AND recette.mois >= 1
            AND recette.mois <= (13 - exercice.mois_debut) ) 
            ORDER BY recette.date_creation )
            UNION
            (SELECT depense.date_creation, type, montant * -1, commentaire, periodicitee, mois FROM depense,exercice WHERE
            (exercice.annee_debut = :annee 
            AND exercice.user_id = :userid
            AND depense.exercice_id = exercice.id
            AND depense.user_id = exercice.user_id
            AND depense.mois > (13 - exercice.mois_debut) )
            AND depense.mois <= 12 
            OR (exercice.annee_debut = (:annee - 1)
            AND exercice.user_id = :userid
            AND depense.exercice_id = exercice.id
            AND depense.user_id = exercice.user_id
            AND depense.mois >= 1
            AND depense.mois <= (13 - exercice.mois_debut) )
            ORDER BY depense.date_creation )
            ";
         
    $q = array('userid' => $user_id, 'annee' => $exercice_annee);
    
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

$colonnes= array('Date de sasie','mois','Type','Montant','Commentaire');
fputcsv($handle, $colonnes, ';', '"'); // Ajout des colonnes

foreach ($data as $row) { // Ajout des lignes, lecture BDD
		$ligne= array(date("d/m/Y H:i", strtotime($row['date_creation'])), NumToMois($row['mois']), NumToTypeRecette($row['type']), number_format($row['montant'],2,',',''), $row['commentaire']);
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