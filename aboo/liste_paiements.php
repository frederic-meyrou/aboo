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

// Mode Debug
	$debug = false;

// Sécurisation POST & GET
    foreach ($_GET as $key => $value) {
        $sGET[$key]=htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
    foreach ($_POST as $key => $value) {
        $sPOST[$key]=htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
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
    
?>

<!DOCTYPE html>
<html lang="fr">
<?php require 'head.php'; ?>

<body>

    <?php $page_courante = "paiements.php"; require 'nav.php'; ?>
        
    <div class="container">
        <div class="page-header">           
            <h2>Journal Annuel des paiements étalés</h2>
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

        <!-- Affiche les sommmes -->        
		<p>
		    <a href="paiements.php" class="btn btn-primary"><span class="glyphicon glyphicon-eject"></span> Retour</a>						
		</p>   
                
		<!-- Affiche la table en base -->
        <div class="panel panel-default">
          <div class="panel-heading">
            <p>         
            <h3 class="panel-title">Journal annuel : <button type="button" class="btn btn-sm btn-info"><?php echo "$exercice_annee - " . ($exercice_annee +1); ?></button>
                <div class="btn-group pull-right">                
                    <a href="csv/export_liste_paiements.php" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-list-alt"></span> Export Excel</a>
                    <a href="#" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-briefcase"></span> Export PDF</a>
                </div>              
            </h3>
            </p>                  
          </div>            
          <div class="panel-body">		
            <div class="table-responsive">       
			<table cellpadding="0" cellspacing="0" border="0" class="datatable table table-bordered table-hover success">
				<thead>
					<tr class="active">
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
								echo '<tr>';
								echo '<td>' . NumToMois(MoisAnnee($num_mois,$exercice_mois)) . '</td>';
								echo '<td>' . number_format($row["mois_$num_mois"],2,',','.') . ' €</td>';
								if ($row["paye_$num_mois"] == 1 ) { //Encaissé
									echo '<td class="success">';
									echo 'Oui';
								} else { // Non encaissé
									echo '<td class="danger">';
									echo 'non';
								}
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
			Database::disconnect();
			?>	                        
                		</tbody>
            </table>
            </div> <!-- /table-responsive -->
            
	        <!-- Affiche les sommmes -->        
			<p>
				<button type="button" class="btn btn-info">Total paiements à échéances : <?php echo $total; ?> €</button>
				<button type="button" class="btn btn-info">Total paiements à recouvrer : <?php echo $total_apayer; ?> €</button>							
			</p>   

          </div>
        </div> <!-- /panel -->                		


    
    </div> <!-- /container -->

    <?php require 'footer.php'; ?>

    <script>  
        /* Table initialisation */
        $(document).ready(function() {
            $('.datatable').dataTable({
                "sPaginationType": "bs_full"
            });
            $('.datatable').each(function(){
                var datatable = $(this);
                // SEARCH - Add the placeholder for Search and Turn this into in-line form control
                var search_input = datatable.closest('.dataTables_wrapper').find('div[id$=_filter] input');
                search_input.attr('placeholder', 'Rechercher');
                search_input.addClass('form-control input-sm');
                // LENGTH - Inline-Form control
                var length_sel = datatable.closest('.dataTables_wrapper').find('div[id$=_length] select');
                length_sel.addClass('form-control input-sm');
            });             
        });
    </script>
        
  </body>
</html>