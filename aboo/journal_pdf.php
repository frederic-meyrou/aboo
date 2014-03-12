<!-- 
© Copyright : Aboo / www.aboo.fr : Frédéric MEYROU : tous droits réservés
-->
<?php
// Dépendances
	require_once('lib/fonctions.php');
    include_once('lib/database.php');
    include_once('lib/calcul_bilan.php');    
	
// Vérification de l'Authent
    session_start();
    require('lib/authent.php');
    if( !Authent::islogged()){
        // Non authentifié on repart sur la HP
        header('Location:../index.php');
    }

// Mode Debug
	$debug = false;
        	
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
    
    if ($count==0) { // Il n'y a rien à afficher
        $affiche = false;       
    } else {
            // On affiche le tableau
            $affiche = true;
    }
    Database::disconnect();
    $infos = true;

// Charge le Bilan    
    $TableauBilanMensuel = CalculBilanMensuel($user_id, $exercice_id, $exercice_treso);

// --- DEBUT STRUCTURE PDF

ob_start();

?> 
    <page>
        <style type="text/css">
        <!--
            table
            {
                width:  100%;
                border: solid 1px black;
            }

            th
            {
                text-align: center;
                border: solid 1px gray;
                background: gray;
            }

            tr
            {
                border: solid 1px gray;
            }            
            
            td
            {
                text-align: center;

            }          
        -->    
        </style>

        <h3>Journal des Recettes & Dépenses : <?php echo NumToMois($abodep_mois); ?></h3>
        
			<table>
                <col style="width: 25%">
                <col style="width: 25%">
                <col style="width: 25%">
                <col style="width: 25%">			    

				<tr>
				  <th>Date</th>
				  <th>Type</th>					  
				  <th>Montant</th>
				  <th>Commentaire</th>			  
				</tr>
                <?php            
                    foreach ($data as $row) {
                        echo '<tr>';
                        echo '<td >' . date("d/m/Y H:i", strtotime($row['date_creation'])) . '</td>';
                        if (!empty($row['periodicitee'])) {
                            echo '<td >' . NumToTypeRecette($row['type']) . '</td>';
                        } else {
                            echo '<td >' . NumToTypeDepense($row['type']) . '</td>';                         
                        }                       
                        echo '<td >' . number_format($row['montant'],2,',','.') . ' €</td>';
                        echo '<td >' . $row['commentaire'] . '</td>';
                        echo '</tr>';
                    }
                ?>                       
                <tr>
                    <th style="border: none;font-size: 14px; background:none; border: none;">
                        <?php echo $count; ?> Lignes. 
                    </th>                          
                    <th style="font-size: 16px; text-align: right; background:none; border: none;">
                        Solde : 
                    </th>                        
                    <th style="font-size: 16px; text-align: center; border: none;">
                        <?php echo $TableauBilanMensuel[MoisRelatif($abodep_mois, $exercice_mois)]['SOLDE']; ?> €
                    </th>
                </tr>
            </table>

            <!-- Affiche les sommmes -->        
			<p>
				CA : <?php echo $TableauBilanMensuel[MoisRelatif($abodep_mois, $exercice_mois)]['CA']; ?> €
                CA Non déclaré : <?php echo $TableauBilanMensuel[MoisRelatif($abodep_mois, $exercice_mois)]['NON_DECLARE']; ?> €
                Dépenses : <?php echo $TableauBilanMensuel[MoisRelatif($abodep_mois, $exercice_mois)]['DEPENSE']; ?> €
				Salaire Réel : <?php echo $TableauBilanMensuel[MoisRelatif($abodep_mois, $exercice_mois)]['SALAIRE_REEL']; ?> €
				Trésorerie : <?php echo $TableauBilanMensuel[MoisRelatif($abodep_mois, $exercice_mois)]['REPORT_TRESO']; ?> €				
			</p>

	</page>
	
<?php
$content = ob_get_contents(); ob_clean();
// --- FIN STRUCTURE PDF

// Génération du PDF et envoi au navigateur

require('lib/html2pdf/html2pdf.class.php');
try{
	$pdf = new HTML2PDF('P','A4','fr');
	//$pdf->setModeDebug();
	$pdf->writeHTML($content, $debug);
	$pdf->pdf->SetDisplayMode('fullpage');
	$pdf->pdf->SetTitle('Journal des Recettes & Dépenses');
	$pdf->Output('AbooJournal.pdf');
}catch(HTML2PDF_exception $Error){
	die($Error);
}
   
?>
        

                
  