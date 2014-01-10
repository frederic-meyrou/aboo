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
		$sql1 = "(SELECT SUM(montant) FROM recette WHERE
	    		user_id = :userid AND exercice_id = :exerciceid )
	    		UNION
	    		(SELECT SUM(montant * -1) FROM depense WHERE
	    		user_id = :userid AND exercice_id = :exerciceid )
	    		";
	// Requette pour calcul de la somme	mensuelle		
		$sql2 = "(SELECT SUM(montant) FROM recette WHERE
	    		user_id = :userid AND exercice_id = :exerciceid AND mois = :mois)
	    		UNION
	    		(SELECT SUM(montant * -1) FROM depense WHERE
	    		user_id = :userid AND exercice_id = :exerciceid AND mois = :mois )
	    		";
	// requette pour calcul des ventilations abo Annuelle
	    $sql3 = "SELECT SUM(mois_1),SUM(mois_2),SUM(mois_3),SUM(mois_4),SUM(mois_5),SUM(mois_6),SUM(mois_7),SUM(mois_8),SUM(mois_9),SUM(mois_10),SUM(mois_11),SUM(mois_12) FROM recette WHERE
	    		(user_id = :userid AND exercice_id = :exerciceid)
	    		";		
				
    $q1 = array('userid' => $user_id, 'exerciceid' => $exercice_id);				
    $q3 = array('userid' => $user_id, 'exerciceid' => $exercice_id);
    $q4 = array('userid' => $user_id, 'exerciceid' => $exercice_id);
        
	$req = $pdo->prepare($sql1);
	$req->execute($q1);
	$data1 = $req->fetchAll(PDO::FETCH_ASSOC);
    $count = $req->rowCount($sql1);
	
	$req = $pdo->prepare($sql3);
	$req->execute($q3);
	$data3 = $req->fetch(PDO::FETCH_ASSOC);	
	
	if ($count==0) { // Il n'y a rien en base sur l'année (pas de dépenses et pas de recettes)
        $affiche = false;         
    } else {
    		// Calcul des sommes Annuelle
	        $total_recettes_annee= !empty($data1[0]["SUM(montant)"]) ? $data1[0]["SUM(montant)"] : 0;  
    		$total_depenses_annee= !empty($data1[1]["SUM(montant)"]) ? $data1[1]["SUM(montant)"] : 0;
	        $solde_annee = $total_recettes_annee + $total_depenses_annee;
			
    		// Calcul des sommes de tout les mois
            for ($i = 1; $i <= 12; $i++) {
                // Requette BDD
                $q2 = array('userid' => $user_id, 'exerciceid' => $exercice_id, 'mois' => $i);
                $req = $pdo->prepare($sql2);
                $req->execute($q2);
                $data2 = $req->fetchAll(PDO::FETCH_ASSOC);
                // Calcul     
                $total_recettes_mois_{$i}= !empty($data2[0]["SUM(montant)"]) ? $data2[0]["SUM(montant)"] : 0; 
                $total_depenses_mois_{$i}= !empty($data2[1]["SUM(montant)"]) ? $data2[1]["SUM(montant)"] : 0;
                $solde_mois_{$i}= $total_recettes_mois_{$i} + $total_depenses_mois_{$i};               
            }    		      
			
			// Calcul des sommes ventillées
	        for ($i = 1; $i <= 12; $i++) { 
	        	$total_mois_{$i}= !empty($data3["SUM(mois_$i)"]) ? $data3["SUM(mois_$i)"] : 0;
			}	  
			
			// Calcul des paiements
			$q = array('userid' => $user_id, 'exerciceid' => $exercice_id);
	        for ($m = 1; $m <= 12; $m++) { 
				// Requette pour calcul de la somme des paiement mensuelle			
			    $sql4 = "SELECT SUM(P.mois_$m) FROM paiement P, recette A WHERE
			    		A.id = P.recette_id AND 
			    		A.user_id = :userid AND A.exercice_id = :exerciceid AND
			    		P.mois_$m <> 0
			    		";
			
				// Requette pour calcul de la somme restant à mettre en recouvrement mensuelle			
			    $sql5 = "SELECT SUM(P.mois_$m) FROM paiement P, recette A WHERE
			    		A.id = P.recette_id AND 
			    		A.user_id = :userid AND A.exercice_id = :exerciceid AND
			    		P.mois_$m <> 0 AND
			    		P.paye_$m = 0
			    		";	

			   	$req4 = $pdo->prepare($sql4);
				$req4->execute($q);
				$data4 = $req4->fetch(PDO::FETCH_ASSOC);
				
			   	$req5 = $pdo->prepare($sql5);
				$req5->execute($q);
				$data5 = $req5->fetch(PDO::FETCH_ASSOC);							

	    		// Calcul des sommes 
		        $total_paiement_mois_{$m}= !empty($data4["SUM(P.mois_$m)"]) ? $data4["SUM(P.mois_$m)"] : 0;
		        $total_apayer_mois_{$m}= !empty($data5["SUM(P.mois_$m)"]) ? $data5["SUM(P.mois_$m)"] : 0;						

	        } //End for			   
	           
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

    <?php $page_courante = "bilan.php"; require 'nav.php'; ?>
        
    <div class="container">
     
       
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

        <!-- Affiche les 12 Mois -->
        <div class="page-header">          
            <h2>Tableau de bord : <button type="button" class="btn btn-lg btn-info"><?php echo "$exercice_annee - " . ($exercice_annee +1); ?></button></h2>  
        </div>
        
        <?php       
        function AffichePanel($mois_relatif) {
            global $total_mois_;
            global $total_recettes_mois_;
            global $total_depenses_mois_;
            global $solde_mois_;
            global $exercice_mois;
            global $total_paiement_mois_;
            global $total_apayer_mois_;
            
            $num_mois = MoisAnnee($mois_relatif, $exercice_mois);
        ?>
              <div class="panel panel-success">
                <div class="panel-heading">
                  <h3 class="panel-title"><?php echo $mois_relatif . ' : ' .  NumToMois($num_mois); ?></h3>
                </div>
                <div class="panel-body">
                    <li>CA : <?php echo number_format($total_recettes_mois_{$mois_relatif},2,',','.') . ' €'; ?></li>
                    <li>Dépenses : <?php echo number_format($total_depenses_mois_{$mois_relatif},2,',','.') . ' €'; ?></li>
                    <li>Solde brut : <?php echo number_format($solde_mois_{$mois_relatif},2,',','.') . ' €'; ?></li> 
                    <li>Salaire : <?php echo number_format($total_mois_{$mois_relatif},2,',','.') . ' €'; ?></li>
                    <li>A trésoriser : <?php echo number_format(($total_recettes_mois_{$mois_relatif} - $total_mois_{$mois_relatif} ),2,',','.') . ' €'; ?></li>
                    <li>Tréso réele : <?php echo number_format(($solde_mois_{$mois_relatif} - $total_mois_{$mois_relatif} ),2,',','.') . ' €'; ?></li>
                    <li>Encaissé : <?php echo number_format($total_paiement_mois_{$mois_relatif},2,',','.') . ' €'; ?></li>
                    <li>A encaisser : <?php echo number_format($total_apayer_mois_{$mois_relatif},2,',','.') . ' €'; ?></li>
                </div>
              </div>
        <?php    
        }   
        ?> 

        <div class="row">
            <div class="col-sm-4">
              <!-- Mois 1 -->  
              <?php AffichePanel(1); ?>
              <!-- Mois 4 -->
              <?php AffichePanel(4); ?>
              <!-- Mois 7 -->
              <?php AffichePanel(7); ?>
              <!-- Mois 10 -->
              <?php AffichePanel(10); ?>
            </div><!-- /.col-sm-4 -->
            
            <div class="col-sm-4">
              <!-- Mois 2 -->  
              <?php AffichePanel(2); ?>
              <!-- Mois 5 -->
              <?php AffichePanel(5); ?>
              <!-- Mois 8 -->
              <?php AffichePanel(8); ?>
              <!-- Mois 11 -->
              <?php AffichePanel(11); ?>
            </div><!-- /.col-sm-4 -->
            
            <div class="col-sm-4">
              <!-- Mois 3 -->  
              <?php AffichePanel(3); ?>
              <!-- Mois 6 -->
              <?php AffichePanel(6); ?>
              <!-- Mois 9 -->
              <?php AffichePanel(9); ?>
              <!-- Mois 12 -->
              <?php AffichePanel(12); ?>
            </div><!-- /.col-sm-4 -->
        </div>
        <hr>

		<!-- Affiche la table en base sous condition -->
		<div class="span10">
			<?php 
 			if ($affiche) {
			?>
            <!-- Affiche les sommmes -->        
			<p>
				<button type="button" class="btn btn-primary">Exercice : <?php echo "$exercice_annee - " . ($exercice_annee +1); ?></button>
				<button type="button" class="btn btn-info">Total dépenses : <?php echo number_format($total_depenses_annee,2,',','.'); ?> €</button>
				<button type="button" class="btn btn-info">Total recettes : <?php echo number_format($total_recettes_annee,2,',','.'); ?> €</button>
				<button type="button" class="btn btn-info">Solde : <?php echo number_format($solde_annee,2,',','.'); ?> €</button>		
                <button type="button" class="btn btn-info">Salaire mensuel moyen : <?php echo number_format(( $solde_annee / 12 ),2,',','.'); ?> €</button>   
 			</p>			
			          
			</div> 	<!-- /row -->
			<?php 	
			} // if
			?>
        </div>  <!-- /span -->        	        
             
    </div> <!-- /container -->

    <?php require 'footer.php'; ?>
        
  </body>
</html>