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
    
?>

<!DOCTYPE html>
<html lang="fr">
<?php require 'head.php'; ?>

<body>

    <?php $page_courante = "paiements.php"; require 'nav.php'; ?>
        
    <div class="container">
        <h2>Journal Annuel des paiements étalés</h2>
        <br>
        
        <!-- Affiche les boutons de créations -->      
		<div class="btn-group">
  			<a href="paiements.php" class="btn btn-primary"><span class="glyphicon glyphicon-chevron-up"></span> Retour</a>  	
  			<a href="#" class="btn btn-primary"><span class="glyphicon glyphicon-list-alt"></span> Export Excel</a>
  			<a href="#" class="btn btn-primary"><span class="glyphicon glyphicon-briefcase"></span> Export PDF</a>			  						
		</div>  
        <br><br>
                
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
                
		<!-- Affiche la table en base -->
		<div class="span10">
            <div class="row">

			<table class="table table-striped table-bordered table-hover success">
				<thead>
					<tr>
					  <th>Mois</th>
					  <th>Echéance</th>
					  <th>Encaissé</th>						  						  
					  <th>Type</th>
					  <th>Montant</th>					  					  					  
					  <th>Périodicitée</th>					  
					  <th>Commentaire</th>			  
					</tr>
				</thead>
	
			<?php 
			$total = 0;
			$total_apayer = 0;
			
 			for ($num_mois = 1; $num_mois <= 12; $num_mois++) {
 				
				// Jointure dans la base recette/paiement (join sur user_id et exercice_id) 
			    $sql = "SELECT P.id,A.montant,A.commentaire,A.type,A.periodicitee,P.mois_$num_mois,P.paye_$num_mois FROM paiement P, recette A WHERE
			    		A.id = P.recette_id AND 
			    		A.user_id = :userid AND A.exercice_id = :exerciceid AND
			    		P.mois_$num_mois <> 0
			    		ORDER by P.paye_$num_mois,A.date_creation
			    		";
			
				// Requette pour calcul de la somme Totale			
			    $sql2 = "SELECT SUM(P.mois_$num_mois) FROM paiement P, recette A WHERE
			    		A.id = P.recette_id AND 
			    		A.user_id = :userid AND A.exercice_id = :exerciceid AND
			    		P.mois_$num_mois <> 0
			    		";
			
				// Requette pour calcul de la somme restant à mettre en recouvrement			
			    $sql3 = "SELECT SUM(P.mois_$num_mois) FROM paiement P, recette A WHERE
			    		A.id = P.recette_id AND 
			    		A.user_id = :userid AND A.exercice_id = :exerciceid AND
			    		P.mois_$num_mois <> 0 AND
			    		P.paye_$num_mois = 0
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
				
	    		// Calcul des sommes 
		        $total_mois_{$num_mois} = !empty($data2["SUM(P.mois_$num_mois)"]) ? $data2["SUM(P.mois_$num_mois)"] : 0;
		        $total_apayer_{$num_mois} = !empty($data3["SUM(P.mois_$num_mois)"]) ? $data3["SUM(P.mois_$num_mois)"] : 0;

				if ($count != 0) { // Il y a un résultat ds la base	 				
					?>		                
						<tbody>
						<?php
							$i=1;	 
							foreach ($data as $row) {
								echo '<td>' . NumToMois(MoisAnnee($num_mois,$exercice_mois)) . '</td>';
								echo '<td>' . number_format($row["mois_$num_mois"],2,',','.') . ' €</td>';
								echo '<td>';
								echo ($row["paye_$num_mois"] == 1 ) ? 'Oui' : 'Non';
								echo '</td>';
								echo '<td>' . NumToTypeRecette($row['type']) . '</td>';
								echo '<td>' . number_format($row['montant'],2,',','.') . ' €</td>';
								echo '<td>' . NumToPeriodicitee($row['periodicitee']) . '</td>';	
								echo '<td>' . $row['commentaire'] . '</td>';
								echo '</tr>';
								$i++;
							}
						?>						 

			<?php
				} // if
							 	$total = $total + $total_mois_{$num_mois};
				 	$total_apayer = $total_apayer + $total_apayer_{$num_mois};
			} // for
			?>	                        
                		</tbody>
            </table>
            
            <!-- Affiche les sommmes -->        
			<p><br>
				<button type="button" class="btn btn-info">Total paiements à échéances : <?php echo $total; ?> €</button>
				<button type="button" class="btn btn-info">Total paiements à recouvrer : <?php echo $total_apayer; ?> €</button>							
			</p>
			          
			</div> 	<!-- /row -->

        </div>  <!-- /span -->        			
    
    </div> <!-- /container -->

    <?php require 'footer.php'; ?>
        
  </body>
</html>