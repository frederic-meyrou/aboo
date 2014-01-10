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
    
// Lecture du POST
    if (isset($sPOST['mois']) ) { // J'ai un POST sur le formulaire de mois
            $mois_choisi = $sPOST['mois'];
			$selection_active = false;
    } elseif (count($_POST) > 0) { // Je suis dans le cas du formulaire de selection
    		$selection_active = true;
			$mois_choisi = null;
	} else { // Je n'ai pas de POST
            $mois_choisi = null;
			$selection_active = false;
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
	// Calcul du mois relatif	
	$mois_choisi_relatif = MoisRelatif($mois_choisi,$exercice_mois);
	

// Jointure dans la base recette/paiement (join sur user_id et exercice_id) 
    $sql = "SELECT P.id,A.montant,A.commentaire,A.type,A.periodicitee,P.mois_$mois_choisi_relatif,P.paye_$mois_choisi_relatif FROM paiement P, recette A WHERE
    		A.id = P.recette_id AND 
    		A.user_id = :userid AND A.exercice_id = :exerciceid AND
    		P.mois_$mois_choisi_relatif <> 0
    		ORDER by P.paye_$mois_choisi_relatif,A.date_creation
    		";

// Requette pour calcul de la somme Totale			
    $sql2 = "SELECT SUM(P.mois_$mois_choisi_relatif) FROM paiement P, recette A WHERE
    		A.id = P.recette_id AND 
    		A.user_id = :userid AND A.exercice_id = :exerciceid AND
    		P.mois_$mois_choisi_relatif <> 0
    		";

// Requette pour calcul de la somme restant à mettre en recouvrement			
    $sql3 = "SELECT SUM(P.mois_$mois_choisi_relatif) FROM paiement P, recette A WHERE
    		A.id = P.recette_id AND 
    		A.user_id = :userid AND A.exercice_id = :exerciceid AND
    		P.mois_$mois_choisi_relatif <> 0 AND
    		P.paye_$mois_choisi_relatif = 0
    		";
    				
    $q = array('userid' => $user_id, 'exerciceid' => $exercice_id);
    
    $req = $pdo->prepare($sql);
    $req->execute($q);
    $data = $req->fetchAll(PDO::FETCH_ASSOC);
    $count = $req->rowCount($sql);
	
   	$req = $pdo->prepare($sql2);
	$req->execute($q);
	$data2 = $req->fetch(PDO::FETCH_ASSOC);
	
   	$req = $pdo->prepare($sql3);
	$req->execute($q);
	$data3 = $req->fetch(PDO::FETCH_ASSOC);	
	
	if ($count==0) { // Il n'y a rien à afficher
        $affiche = false;       
    } else {
    		// Calcul des sommes 
	        $total_mois_{$mois_choisi_relatif}= !empty($data2["SUM(P.mois_$mois_choisi_relatif)"]) ? $data2["SUM(P.mois_$mois_choisi_relatif)"] : 0;
	        $total_apayer_{$mois_choisi_relatif}= !empty($data3["SUM(P.mois_$mois_choisi_relatif)"]) ? $data3["SUM(P.mois_$mois_choisi_relatif)"] : 0;
	        
	        // On affiche le tableau
	        $affiche = true;
    }        

// Traitement du POST de Selection des lignes
	// Requette pour mettre à jour la selection des paiements			
    $sql4 = "UPDATE paiement SET paye_$mois_choisi_relatif=1 WHERE id = ?";		
    if ($selection_active) { // J'ai un POST de selection
    	$selection = array();
    	for ($i = 1; $i <= count($_POST); $i++) {
    		//$selection[$i] = $sPOST[$i];
			$id = $sPOST[$i];
            $req = $pdo->prepare($sql4);
            $req->execute(array($id));
    	}
	    // On reset le formulaire
		Database::disconnect();
	    header('Location:paiements.php');
    }
	Database::disconnect();
?>

<!DOCTYPE html>
<html lang="fr">
<?php require 'head.php'; ?>

<body>

    <?php $page_courante = "paiements.php"; require 'nav.php'; ?>
        
    <div class="container">
        <h2>Gestion des paiements étalés</h2>
        <br>
        
        <!-- Affiche le dropdown formulaire mois avec selection automatique du mois en cours de la session -->
        <form class="form-inline" role="form" action="paiements.php" method="post">      
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
			<!--<a href="???.php" class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span> ???</a>--> 
  			<a href="liste_paiements.php" class="btn btn-primary"><span class="glyphicon glyphicon-list-alt"></span> Journal Annuel des paiements</a>    						
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
            <div class="row">
                <h3>Journal des échéances du mois courant : <button type="button" class="btn btn-info"><?php echo NumToMois($abodep_mois); ?> : <span class="badge "><?php echo $count; ?></span></button></h3>
				<?php 
	 			if ($affiche) {
				?>
	            <form class="form-inline" role="form" action="paiements.php" method="post">            			
					<table class="table table-striped table-bordered table-hover success">
						<thead>
							<tr>
							  <th><span class="glyphicon glyphicon-ok-sign"></span></th>
							  <th>Echéance</th>						  
							  <th>Type</th>
							  <th>Montant</th>					  					  					  
							  <th>Périodicitée</th>					  
							  <th>Commentaire</th>			  
							</tr>
						</thead>
		                
						<tbody>
						<?php
							$i=1;	 
							foreach ($data as $row) {
						?>		
								<tr>
								<td width=30>			
									<label class="checkbox-inline">
								    	<input name="<?php echo $i; ?>" type="checkbox" value="<?php echo $row['id']; ?>" <?php echo ($row["paye_$mois_choisi_relatif"]==1)?'checked':'';?>>
								  	</label>
								</td>					
						<?php 	
								echo '<td>' . number_format($row["mois_$mois_choisi_relatif"],2,',','.') . ' €</td>';
								echo '<td>' . NumToTypeRecette($row['type']) . '</td>';
								echo '<td>' . number_format($row['montant'],2,',','.') . ' €</td>';
								echo '<td>' . NumToPeriodicitee($row['periodicitee']) . '</td>';	
								echo '<td>' . $row['commentaire'] . '</td>';
								echo '</tr>';
								$i++;
							}
						?>						 
		                </tbody>
		            </table>
		            
		            <!-- Affiche le bouton de formulaire --> 
		           	<button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-ok-sign"></span> Passer la sélection en statut encaissé</button>
	            </form>
	            
	            <!-- Affiche les sommmes -->        
				<p><br>
					<button type="button" class="btn btn-info">Total paiements à échéances : <?php echo $total_mois_{$mois_choisi_relatif}; ?> €</button>
					<button type="button" class="btn btn-info">Total paiements à recouvrer : <?php echo $total_apayer_{$mois_choisi_relatif}; ?> €</button>							
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