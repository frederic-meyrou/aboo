<!-- 
© Copyright : Aboo / www.aboo.fr : Frédéric MEYROU : tous droits réservés
-->
<?php
// Vérification de l'Authent
    session_start();
    require('authent.php');
    if( !Authent::islogged()){
        // Non authentifié on repart sur la HP
        header('Location:index.php');
    }

// Dépendances
	require_once('fonctions.php');

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
    include_once 'database.php';
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
// Lecture tableau de bord

	// Requette pour calcul de la somme Annuelle			
		$sql1 = "(SELECT SUM(montant) FROM abonnement WHERE
	    		user_id = :userid AND exercice_id = :exerciceid )
	    		UNION
	    		(SELECT SUM(montant * -1) FROM depense WHERE
	    		user_id = :userid AND exercice_id = :exerciceid )
	    		";
	// Requette pour calcul de la somme	du mois en cours		
		$sql2 = "(SELECT SUM(montant) FROM abonnement WHERE
	    		user_id = :userid AND exercice_id = :exerciceid AND mois = :mois)
	    		UNION
	    		(SELECT SUM(montant * -1) FROM depense WHERE
	    		user_id = :userid AND exercice_id = :exerciceid AND mois = :mois )
	    		";
	// requette pour calcul des ventilations abo Annuelle
	    $sql3 = "SELECT SUM(mois_1),SUM(mois_2),SUM(mois_3),SUM(mois_4),SUM(mois_5),SUM(mois_6),SUM(mois_7),SUM(mois_8),SUM(mois_9),SUM(mois_10),SUM(mois_11),SUM(mois_12) FROM abonnement WHERE
	    		(user_id = :userid AND exercice_id = :exerciceid)
	    		";
				
    $q1 = array('userid' => $user_id, 'exerciceid' => $exercice_id);	
    $q2 = array('userid' => $user_id, 'exerciceid' => $exercice_id, 'mois' => $abodep_mois);				
    $q3 = array('userid' => $user_id, 'exerciceid' => $exercice_id);
    
	$req = $pdo->prepare($sql1);
	$req->execute($q1);
	$data1 = $req->fetchAll(PDO::FETCH_ASSOC);
    $count = $req->rowCount($sql1);
	
	$req = $pdo->prepare($sql2);
	$req->execute($q2);
	$data2 = $req->fetchAll(PDO::FETCH_ASSOC);
	
	$req = $pdo->prepare($sql3);
	$req->execute($q3);
	$data3 = $req->fetch(PDO::FETCH_ASSOC);	
	
	if ($count==0) { // Il n'y a rien en base sur l'année
        $affiche = false;         
    } else {
    		// Calcul des sommes Annuelle
	        $total_recettes_annee= !empty($data1[0]["SUM(montant)"]) ? $data1[0]["SUM(montant)"] : 0;  
    		$total_depenses_annee= !empty($data1[1]["SUM(montant)"]) ? $data1[1]["SUM(montant)"] : 0;
	        $solde_annee = $total_recettes_annee + $total_depenses_annee;
			
    		// Calcul des sommes du mois en cours
	        $total_recettes= !empty($data2[0]["SUM(montant)"]) ? $data2[0]["SUM(montant)"] : 0;  
    		$total_depenses= !empty($data2[1]["SUM(montant)"]) ? $data2[1]["SUM(montant)"] : 0;
	        $solde = $total_recettes + $total_depenses;	        
			
			// Calcul des sommes ventillées
	        for ($i = 1; $i <= 12; $i++) { 
	        	$total_mois_{$i}= !empty($data3["SUM(mois_$i)"]) ? $data3["SUM(mois_$i)"] : 0;
			}	        
	        // On affiche le tableau
	        $affiche = true;
    }
	Database::disconnect();
	$infos = true;	
		
?>

<!DOCTYPE html>
<html lang="fr">
<?php require 'head.php'; ?>

<body>    

    <?php require 'nav.php'; ?>
        
    <div class="container">
        <h2>Tableau de bord</h2>       
        <br>
       
		<!-- Affiche les informations de session -->      		
		<?php 
 		if ($infos) {
		?>
        <div class="alert alert alert-info alert-dismissable fade in">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <strong><?php echo "Exercice Courant : $exercice_annee démarrant en " . NumToMois($exercice_mois) . ", tréso de $exercice_treso €"; ?></strong><br> 
        </div>
        <div class="alert alert alert-success alert-dismissable fade in">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <strong>- CA(M) = Recettes(M) : Chiffre d'affaire</strong><br>
            <strong>- Dépenses(M) = Dépenses</strong><br>
            <strong>- Solde Brut (M) = CA (M) - Dépenses (M)</strong><br>
            <strong>- Ventillation (M) = Recettes de l'exercice ventillées sur le mois courant</strong><br>
            <strong>- Paiement (M) = Total des echéances des abonements étalés sur le mois courant</strong><br>
            <strong>- Encaissement (M) = Total des paiements encaissés + recettes du mois courant payées</strong><br>
            <strong>- Salaire (M) = Montant affecté au salaire = [Trésorerie (M-1) + Encaissement (M)] - [Ventilation (M) - Dépenses (M)]</strong><br>
            <strong>- Trésorerie (M) = Montant de la trésorerie = Encaissement (M)  - Dépenses (M)  + Trésorerie (M-1) </strong><br>
            <strong>- A trésoriser (M) = Montant à mettre de côté</strong><br>             
        </div>
        <br>        
	    <?php       
        }   
        ?>          
        
        <!-- Affiche les informations de debug -->
        <?php 
 		if ($debug) {
		?>
        <div class="alert alert alert-danger alert-dismissable fade in">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <strong>Informations de Debug : </strong><br>
            SESSION:<br>
            <pre><?php var_dump($_SESSION); ?></pre>
            POST:<br>
            <pre><?php var_dump($_POST); ?></pre>
            GET:<br>
            <pre><?php var_dump($_GET); ?></pre>
        </div>
        <?php       
        }   
        ?> 

		<!-- Affiche la table en base sous condition -->
		<div class="span10">
			<?php 
 			if ($affiche) {
			?>
            <!-- Affiche les sommmes -->        
			<p>
				<button type="button" class="btn btn-primary">Mois : <?php echo NumToMois($abodep_mois); ?></button>
				<button type="button" class="btn btn-info">Total dépenses : <?php echo $total_depenses; ?> €</button>
				<button type="button" class="btn btn-info">Total recettes : <?php echo $total_recettes; ?> €</button>
				<button type="button" class="btn btn-info">Solde : <?php echo $solde; ?> €</button>
				<button type="button" class="btn btn-info">Total affecté au salaire : <?php echo $total_mois_{$abodep_mois}; ?> €</button>
				<button type="button" class="btn btn-info">Trésorerie : <?php echo ($solde - $total_mois_{$abodep_mois}); ?> €</button>				
			</p>
			<p>
				<button type="button" class="btn btn-primary">Exercice : <?php echo "$exercice_annee - " . ($exercice_annee +1); ?></button>
				<button type="button" class="btn btn-info">Total dépenses : <?php echo $total_depenses_annee; ?> €</button>
				<button type="button" class="btn btn-info">Total recettes : <?php echo $total_recettes_annee; ?> €</button>
				<button type="button" class="btn btn-info">Solde : <?php echo $solde_annee; ?> €</button>		
			</p>			
			<p>
				<?php
				for ($i = 1; $i <= 12; $i++) {
				?> 
				<button type="button" class="btn btn-default"><?php echo "$i : " . $total_mois_{$i}; ?> €</button>
				<?php
				}
				?>
			</p> 			          
			</div> 	<!-- /row -->
			<?php 	
			} // if
			?> 
		
        </div>  <!-- /span -->    
			           
    </div> <!-- /container -->
        
    <?php require 'footer.php'; ?>
    
    <script>
	    $(document).ready(function(){ // Le DOM est chargé
	        // Nada!
		});
	</script>
        
  </body>
</html>