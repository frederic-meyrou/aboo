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

// Dépendances
	require_once('lib/fonctions.php');

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
    include_once 'lib/database.php';
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
// Requette pour calcul de la somme			
	$sql2 = "(SELECT SUM(montant) FROM recette WHERE
    		user_id = :userid AND exercice_id = :exerciceid AND mois = :mois)
    		UNION
    		(SELECT SUM(montant * -1) FROM depense WHERE
    		user_id = :userid AND exercice_id = :exerciceid AND mois = :mois )
    		";
// requette pour calcul des ventilations abo
    $sql3 = "SELECT SUM(mois_1),SUM(mois_2),SUM(mois_3),SUM(mois_4),SUM(mois_5),SUM(mois_6),SUM(mois_7),SUM(mois_8),SUM(mois_9),SUM(mois_10),SUM(mois_11),SUM(mois_12) FROM recette WHERE
    		(user_id = :userid AND exercice_id = :exerciceid)
    		";
				
    $q = array('userid' => $user_id, 'exerciceid' => $exercice_id, 'mois' => MoisRelatif($abodep_mois,$exercice_mois));
    $q3 = array('userid' => $user_id, 'exerciceid' => $exercice_id);
    
    $req = $pdo->prepare($sql);
    $req->execute($q);
    $data = $req->fetchAll(PDO::FETCH_ASSOC);
    $count = $req->rowCount($sql);
    
	$req = $pdo->prepare($sql2);
	$req->execute($q);
	$data2 = $req->fetchAll(PDO::FETCH_ASSOC);
	
	$req = $pdo->prepare($sql3);
	$req->execute($q3);
	$data3 = $req->fetch(PDO::FETCH_ASSOC);	
	
	if ($count==0) { // Il n'y a rien à afficher
        $affiche = false;       
    	$_SESSION['abodep']['total_recettes'] = 0;
		$_SESSION['abodep']['total_depenses'] = 0;
		$_SESSION['abodep']['solde'] = 0;       
    } else {
    		// Calcul des sommes
	        $total_recettes= !empty($data2[0]["SUM(montant)"]) ? $data2[0]["SUM(montant)"] : 0;  
    		$total_depenses= !empty($data2[1]["SUM(montant)"]) ? $data2[1]["SUM(montant)"] : 0;
	        $solde = $total_recettes + $total_depenses;
			$_SESSION['abodep']['total_recettes'] = $total_recettes;
			$_SESSION['abodep']['total_depenses'] = $total_depenses;
			$_SESSION['abodep']['solde'] = $solde;
			// Calcul des sommes ventillées
	        for ($i = 1; $i <= 12; $i++) { 
	        	$total_mois_{$i}= !empty($data3["SUM(mois_$i)"]) ? $data3["SUM(mois_$i)"] : 0;
			}	        
	        // On affiche le tableau
	        $affiche = true;
    }
	Database::disconnect();
	$infos = true;

// --- DEBUT STRUCTURE PDF

ob_start();

?> 

	<page>
		
        <h2>Journal des Recettes & Dépenses</h2>
        <h3>Journal du mois courant : <?php echo NumToMois($abodep_mois); ?> : <?php echo $count; ?></h3>
        
			<table>
				<thead>
					<tr>
					  <th>Date</th>
					  <th>Type</th>					  
					  <th>Montant</th>
					  <th>Commentaire</th>			  
					</tr>
				</thead>
                
				<tbody>
				<?php 			 
					foreach ($data as $row) {
						echo '<tr>';
					    echo '<td>' . date("d/m/Y H:i", strtotime($row['date_creation'])) . '</td>';
						if (!empty($row['periodicitee'])) {
					    	echo '<td>' . NumToTypeRecette($row['type']) . '</td>';
						} else {
					    	echo '<td>' . NumToTypeDepense($row['type']) . '</td>';							
						}						
						echo '<td>' . $row['montant'] . ' €</td>';
						echo '<td>' . $row['commentaire'] . '</td>';
						echo '</tr>';
					}
				?>						 
                </tbody>
            </table>

            <!-- Affiche les sommmes -->        
			<p>
				Total dépenses : <?php echo $total_depenses; ?> €
				Total recettes : <?php echo $total_recettes; ?> €
				Solde : <?php echo $solde; ?> €
				Total affecté au salaire : <?php echo $total_mois_{MoisRelatif($abodep_mois,$exercice_mois)}; ?> €
				Trésorerie : <?php echo ($solde - $total_mois_{MoisRelatif($abodep_mois,$exercice_mois)}); ?> €				
			</p>

	</page>
	
<?php
$content = ob_get_contents(); ob_clean();
// --- FIN STRUCTURE PDF

// Génération du PDF et envoi au navigateur

require('html2pdf/html2pdf.class.php');
try{
	$pdf = new HTML2PDF('P','A4','fr');
	//$pdf->setModeDebug();
	$pdf->writeHTML($content);
	$pdf->pdf->SetDisplayMode('fullpage');
	$pdf->pdf->SetTitle('Journal des Recettes & Dépenses');
	$pdf->Output('AbooJournal.pdf');
}catch(HTML2PDF_exception $Error){
	die($Error);
}
   
?>
        

                
  