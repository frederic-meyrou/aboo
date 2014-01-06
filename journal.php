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
    
// Lecture du POST (Choix du mois)
    if (isset($sPOST['mois']) ) { // J'ai un POST
            $mois_choisi = $sPOST['mois'];
    } else { // Je n'ai pas de POST
            $mois_choisi = null;
    }
	
// Selection du mois par défaut
	// On va lire le mois en cours en BDD si il exite
	if ($mois_choisi == null) {
	    $sql = "SELECT mois_encours FROM user WHERE id = ?";
	    $q = $pdo->prepare($sql);
	    $q->execute(array($user_id));
	    $data = $q->fetch(PDO::FETCH_ASSOC);	
	    $count = $q->rowCount($sql);
		if ($count==1) { // On a bien un mois en cours
			$mois_choisi = $data['mois_encours'];
		}
	}		
	if ($exercice_mois != null && ($mois_choisi == null && $abodep_mois == null)) {
		// On a pas de POST ni de SESSION mais on a un mois de debut d'exercice
		$mois_choisi = $exercice_mois;
	} elseif ($mois_choisi == null && $abodep_mois != null) {
		// On a dejà une session mais pas de POST
		$mois_choisi = $abodep_mois;
	} elseif ($mois_choisi == null) {
		// On a vraiment rien on prend le mois courant
		$mois_choisi = date('n');
	}
	$_SESSION['abodep']['mois'] = $mois_choisi;
    $abodep_mois = $mois_choisi;
	// On met à jour la BDD pour les champs encours
    $sql = "UPDATE user SET mois_encours=? WHERE id = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($mois_choisi, $user_id));	

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
// requette pour calcul des ventilations abo
    $sql3 = "SELECT SUM(mois_1),SUM(mois_2),SUM(mois_3),SUM(mois_4),SUM(mois_5),SUM(mois_6),SUM(mois_7),SUM(mois_8),SUM(mois_9),SUM(mois_10),SUM(mois_11),SUM(mois_12) FROM abonnement WHERE
    		(user_id = :userid AND exercice_id = :exerciceid)
    		";
				
    $q = array('userid' => $user_id, 'exerciceid' => $exercice_id, 'mois' => MoisRelatif($mois_choisi,$exercice_mois));
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
?>

<!DOCTYPE html>
<html lang="fr">
<?php require 'head.php'; ?>

<body>
       
    <?php $page_courante = "journal.php"; require 'nav.php'; ?>
        
    <div class="container">
        <h2>Journal des Recettes & Dépenses</h2>
        <br>
        
        <!-- Affiche le dropdown formulaire mois avec selection automatique du mois en cours de la session -->
        <form class="form-inline" role="form" action="journal.php" method="post">      
            <select name="mois" class="form-control">
            <?php
                foreach ($Liste_Mois as $m) {
            ?>
                <option value="<?php echo MoisToNum($m);?>"<?php echo ($m==NumToMois($mois_choisi))?'selected':'';?>><?php echo "$m";?></option>    
            <?php       
                }   
            ?>    
            </select>
            <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-refresh"></span> Changer de mois</button>
        </form>
        <br>
        
        <!-- Affiche les boutons de créations -->      
		<div class="btn-group">
			<a href="abo.php" class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span> Recettes</a>
  			<a href="dep.php" class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span> Dépenses</a>
  			<a href="#" class="btn btn-primary"><span class="glyphicon glyphicon-list-alt"></span> Export Excel</a>
  			<a href="journal_pdf.php" class="btn btn-primary"><span class="glyphicon glyphicon-briefcase"></span> Export PDF</a>	  						
		</div>  
        
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
                
		<!-- Affiche la table en base sous condition -->
		<div class="span10">
			<?php 
 			if ($affiche) {
			?>
            <div class="row">
                <h3>Journal du mois courant : <button type="button" class="btn btn-info"><?php echo NumToMois($abodep_mois); ?> : <span class="badge "><?php echo $count; ?></span></button></h3>
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
						echo '<td>' . number_format($row['montant'],2,',','.') . ' €</td>';
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
				<button type="button" class="btn btn-info">Total affecté au salaire : <?php echo $total_mois_{MoisRelatif($abodep_mois,$exercice_mois)}; ?> €</button>
				<button type="button" class="btn btn-info">Trésorerie : <?php echo ($solde - $total_mois_{MoisRelatif($abodep_mois,$exercice_mois)}); ?> €</button>				
			</p>
			<!--<p>
				<?php
				for ($i = 1; $i <= 12; $i++) {
				?> 
				<button type="button" class="btn btn-default"><?php echo "$i : " . $total_mois_{$i}; ?> €</button>
				<?php
				}
				?>
			</p>--> 			          
			</div> 	<!-- /row -->
			<?php 	
			} // if
			?>
        </div>  <!-- /span -->        			
    
    </div> <!-- /container -->
    
    <?php require 'footer.php'; ?>
    
  </body>
</html>