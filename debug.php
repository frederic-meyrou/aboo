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
        header('Location:index.php');
    }

// Mode Debug
	$debug = true;

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
	
// Lecture tableau de bord

	// Requette pour calcul de la somme Annuelle			
		$sql1 = "(SELECT SUM(montant) FROM recette WHERE
	    		user_id = :userid AND exercice_id = :exerciceid )
	    		UNION
	    		(SELECT SUM(montant * -1) FROM depense WHERE
	    		user_id = :userid AND exercice_id = :exerciceid )
	    		";
	// Requette pour calcul de la somme	du mois en cours		
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
    
	$req = $pdo->prepare($sql1);
	$req->execute($q1);
	$data1 = $req->fetchAll(PDO::FETCH_ASSOC);
    $count = $req->rowCount($sql1);
	
	$req = $pdo->prepare($sql3);
	$req->execute($q3);
	$data3 = $req->fetch(PDO::FETCH_ASSOC);	
	
	if ($count!=0) { // Il n'y a rien en base sur l'année     
			
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

    }
	Database::disconnect();
		
?>

<!DOCTYPE html>
<html lang="fr">
<?php require 'head.php'; ?>

<body>

    <?php $page_courante = "debug.php"; require 'nav.php'; ?>
        
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
            DATA1 Sommes annuelles:<br>
            <pre><?php var_dump($data1); ?></pre>
            DATA3 Ventilation:<br>
            <pre><?php var_dump($data3); ?></pre>                           
        </div>
        <?php       
        }   
        ?> 

             
    </div> <!-- /container -->
    
    <?php require 'footer.php'; ?>    

  </body>
</html>