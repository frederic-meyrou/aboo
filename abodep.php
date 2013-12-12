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
    
// Lecture du POST (Choix du mois)
    if (isset($sPOST['mois']) ) { // J'ai un POST
            $mois_choisi = $sPOST['mois'];
    } else { // Je n'ai pas de POST
            $mois_choisi = null;
    }
	
// Selection du mois par défaut
	if ($exercice_mois != null && ($mois_choisi == null && $abodep_mois == null)) {
		$mois_choisi = $exercice_mois;
	} elseif ($mois_choisi == null && $abodep_mois != null) {
		$mois_choisi = $abodep_mois;
	} elseif ($mois_choisi == null) {
		$mois_choisi = date('n');
	}
	$_SESSION['abodep']['mois'] = $mois_choisi;

// Lecture dans la base des abonnements et des dépenses (join sur user_id et exercice_id et mois) 
    $sql = "(SELECT date_creation, type, montant, commentaire, periodicitee FROM abonnement WHERE
    		user_id = :userid AND exercice_id = :exerciceid AND mois = :mois)
    		UNION
    		(SELECT date_creation, type, montant * -1, commentaire, periodicitee FROM depense WHERE
    		user_id = :userid AND exercice_id = :exerciceid AND mois = :mois )
    		ORDER BY date_creation
    		";
// Requette pour calcul de la somme			
	$sql2 = "(SELECT SUM(montant) FROM abonnement WHERE
    		user_id = :userid AND exercice_id = :exerciceid AND mois = :mois)
    		UNION
    		(SELECT SUM(montant * -1) FROM depense WHERE
    		user_id = :userid AND exercice_id = :exerciceid AND mois = :mois )
    		";
    $q = array('userid' => $user_id, 'exerciceid' => $exercice_id, 'mois' => $mois_choisi);
    $req = $pdo->prepare($sql);
    $req->execute($q);
    $data = $req->fetchAll(PDO::FETCH_ASSOC);
    $count = $req->rowCount($sql);
	$req = $pdo->prepare($sql2);
	$req->execute($q);
	$data2 = $req->fetchAll(PDO::FETCH_ASSOC);
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
	        
	        // On affiche le tableau
	        $affiche = true;
    }
	Database::disconnect();
	$infos = true;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>GestAbo</title>
    <meta charset="utf-8">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" media="screen">
</head>

<body>

    <script src="bootstrap/js/jquery-2.0.3.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    
    <div class="container">
        <h2>Abonnement & Dépenses</h2>
        
        <!-- Affiche la navigation -->
        <ul class="nav nav-pills">
          <li><a href="home.php">Console</a></li>
          <li class="active"><a href="abodep.php">Abonnements & Dépenses</a></li>
          <li><a href="meusuel.php">Bilan Mensuel</a></li>
          <li><a href="bilan.php">Bilan Annuel</a></li>
          <li><a href="encaissements.php">Encaissements</a></li>
          <li><a href="paiements.php">Paiements</a></li>
          <li><a href="conf.php">Configuration</a></li>
          <li><a href="deconnexion.php">Deconnexion</a></li>
        </ul>
        <br>
        
        <!-- Affiche le dropdown formulaire mois avec selection automatique du mois en cours de la session -->
        <form class="form-inline" role="form" action="abodep.php" method="post">      
            <select name="mois" class="form-control">
            <?php
                foreach ($Liste_Mois as $m) {
            ?>
                <option value="<?php echo MoisToNum($m);?>"<?php echo ($m==NumToMois($mois_choisi))?'selected':'';?>><?php echo "$m";?></option>    
            <?php       
                }   
            ?>    
            </select>
            <button type="submit" class="btn btn-success">Changer de mois</button>
        </form>
        <br>
        
        <!-- Affiche les boutons de créations -->        
		<p>
			<a href="abo.php" class="btn btn-success">Création/Modification Abonnements</a>
  			<a href="dep.php" class="btn btn-success">Création/Modification Dépenses</a>
		</p>
        <br>
        
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
        <br>
        <?php       
        }   
        ?>  
        
		<!-- Affiche les informations de session -->      		
		<?php 
 		if ($infos) {
		?>
        <div class="alert alert alert-info alert-dismissable fade in">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <strong><?php echo "Exercice Courant : $exercice_annee démarrant en " . NumToMois($exercice_mois) . ", tréso de $exercice_treso €"; ?></strong><br> 
            <strong><?php echo "Mois courant : " . NumToMois($mois_choisi); ?></strong> 
        </div>
        <br>
	    <?php       
        }   
        ?>  
        
		<!-- Affiche la table en base sous condition -->
		<div class="span10">
			<?php 
 			if ($affiche) {
			?>
            <div class="row">
                <h3>Liste des abonnements et des dépenses du mois courant : <button type="button" class="btn btn-info"><?php echo NumToMois($abodep_mois); ?></button></h3>
            </div>			
			<table class="table table-striped table-bordered table-hover success">
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
						//if () {} test si abo ou dep, on gere seulement 3 colonne en fonction du resultat ds $data?
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
				<button type="button" class="btn btn-info">Total dépenses : <?php echo $total_depenses; ?> €</button>
				<button type="button" class="btn btn-info">Total recettes : <?php echo $total_recettes; ?> €</button>
				<button type="button" class="btn btn-info">Solde : <?php echo $solde; ?> €</button>
			</p>          
			</div> 	<!-- /row -->
			<?php 	
			} // if
			?>
        </div>  <!-- /span -->        			
    
    </div> <!-- /container -->
  </body>
</html>