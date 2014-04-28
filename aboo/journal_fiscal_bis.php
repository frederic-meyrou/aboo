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

// Récupération des variables de session
	include_once('lib/var_session.php');
	
// Mode Debug
	$debug = false;

// Sécurisation POST & GET
    foreach ($_GET as $key => $value) {
        $sGET[$key]=htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
    foreach ($_POST as $key => $value) {
        $sPOST[$key]=htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
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
            <h2>Journal Fiscal (Recettes / Dépenses)</h2>
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
                
		<!-- Affiche la table en base -->
        <div class="panel panel-default">
          <div class="panel-heading">
            <p>         
            <h3 class="panel-title">Journal fiscal : <button type="button" class="btn btn-sm btn-info"><?php echo "$exercice_annee"; ?></button>
                <div class="btn-group pull-right">                
                    <a href="#" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-list-alt"></span> Export Excel</a>
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
                      <th>Date</th>					    
					  <th>Mois</th>	  						  
					  <th>Type</th>
					  <th>Montant</th>					  					  					  
					  <th>Périodicitée</th>					  
					  <th>Commentaire</th>			  
					</tr>
				</thead>
                <tbody>	
			<?php 
			$total_recettes = 0;
			$total_depenses = 0;
			
 			for ($num_mois = 1; $num_mois <= 12; $num_mois++) {
 				
				// Jointure dans la base recette/paiement (join sur user_id et exercice_id) trie sur les encaissements
				// Union avec les recettes payees
                // Union avec les dépenses
                // trie sur la date de création

                $sql = "(SELECT paiement.date_creation,recette.montant,recette.commentaire,recette.type,recette.periodicitee,paiement.mois_$num_mois,paiement.paye_$num_mois 
                         FROM paiement,recette,exercice 
                         WHERE
                            exercice.annee_debut = (:annee - 1) AND exercice.user_id = :userid  
                            AND recette.exercice_id = exercice.id AND recette.user_id = :userid
                            AND recette.id = paiement.recette_id
                            AND paiement.mois_$num_mois <> 0 AND paiement.paye_$num_mois = 1                             
                            AND :mois > (13 - exercice.mois_debut)
                            OR
                            exercice.annee_debut = :annee AND exercice.user_id = :userid
                            AND recette.exercice_id = exercice.id AND recette.user_id = :userid
                            AND :mois <= (13 - exercice.mois_debut)
                            AND recette.id = paiement.recette_id
                            AND paiement.mois_$num_mois <> 0 AND paiement.paye_$num_mois = 1
                         ORDER BY paiement.date_creation)
                        UNION
                        (SELECT recette.date_creation,recette.montant,recette.commentaire,recette.type,recette.periodicitee,0,recette.paye 
                        FROM recette,exercice 
                        WHERE
                           exercice.annee_debut = (:annee - 1) AND exercice.user_id = :userid  
                           AND recette.exercice_id = exercice.id AND recette.user_id = :userid
                           AND recette.mois = :mois
                           AND recette.mois > (13 - exercice.mois_debut) 
                           AND recette.paye = 1
                           OR
                           exercice.annee_debut = :annee AND exercice.user_id = :userid
                           AND recette.exercice_id = exercice.id AND recette.user_id = :userid
                           AND recette.mois = :mois                                                     
                           AND recette.mois <= (13 - exercice.mois_debut)
                           AND recette.paye = 1                           
                        ORDER BY date_creation)
                        UNION
                        (SELECT depense.date_creation, depense.montant * -1, depense.commentaire, depense.type, depense.periodicitee, 0, 1 
                        FROM depense,exercice  
                        WHERE
                           exercice.annee_debut = (:annee - 1) AND exercice.user_id = :userid  
                           AND depense.exercice_id = exercice.id AND depense.user_id = :userid
                           AND depense.mois = :mois
                           AND depense.mois > (13 - exercice.mois_debut) 
                           OR
                           exercice.annee_debut = :annee AND exercice.user_id = :userid
                           AND depense.exercice_id = exercice.id AND depense.user_id = :userid
                           AND depense.mois = :mois                                                     
                           AND depense.mois <= (13 - exercice.mois_debut)
                        ORDER BY date_creation)                         
                        ";
              
                $sql5 = "(SELECT P.date_creation,A.montant,A.commentaire,A.type,A.periodicitee,P.mois_$num_mois,P.paye_$num_mois FROM paiement P, recette A, exercice E WHERE
			    		A.id = P.recette_id AND 
			    		A.user_id = :userid AND A.exercice_id = :exerciceid AND
			    		P.mois_$num_mois <> 0 AND P.paye_$num_mois = 1
                        ORDER BY P.date_creation)
                        UNION
                        (SELECT date_creation,montant,commentaire,type,periodicitee,0,paye FROM recette WHERE
                        user_id = :userid AND exercice_id = :exerciceid AND
                        mois = $num_mois AND
                        paye = 1
                        ORDER BY date_creation)
                        UNION
                        (SELECT date_creation, montant * -1, commentaire, type, periodicitee, 0, 1 FROM depense WHERE
                         user_id = :userid AND exercice_id = :exerciceid AND
                         mois = $num_mois
                         ORDER BY date_creation) 
			    		";
			
				// Requette pour calcul de la somme des encaissements			
			    $sql2 = "SELECT SUM(P.mois_$num_mois) FROM paiement P, recette A WHERE
			    		A.id = P.recette_id AND 
			    		A.user_id = :userid AND A.exercice_id = :exerciceid AND
			    		P.mois_$num_mois <> 0 AND 
			    		P.paye_$num_mois = 1
			    		";

                // Requette pour calcul de la somme des recettes payees
                $sql3 = "SELECT SUM(montant) FROM recette WHERE
                        user_id = :userid AND exercice_id = :exerciceid AND
                        mois = $num_mois AND
                        paye = 1
                        ";
			
				// Requette pour calcul des dépenses			
			    $sql4 = "SELECT SUM(montant) FROM depense WHERE
			    		user_id = :userid AND exercice_id = :exerciceid AND
			    		mois = $num_mois
			    		";			
							
			    $q = array('userid' => $user_id, 'exerciceid' => $exercice_id);
			    $qbis = array('userid' => $user_id, 'annee' => $exercice_annee, 'mois' => $num_mois); 				

			    $req = $pdo->prepare($sql);
			    $req->execute($qbis);
			    $data = $req->fetchAll(PDO::FETCH_ASSOC);
			    $count = $req->rowCount($sql);
				
			   	$req = $pdo->prepare($sql2);
				$req->execute($q);
				$data2 = $req->fetch(PDO::FETCH_ASSOC);
				
			   	$req = $pdo->prepare($sql3);
				$req->execute($q);
				$data3 = $req->fetch(PDO::FETCH_ASSOC);	

                $req = $pdo->prepare($sql4);
                $req->execute($q);
                $data4 = $req->fetch(PDO::FETCH_ASSOC); 
				
	    		// Calcul des sommes 
		        $total_recettes_mois_{$num_mois} = !empty($data2["SUM(P.mois_$num_mois)"]) ? $data2["SUM(P.mois_$num_mois)"] : 0;
		        $total_recettes_mois_{$num_mois} = $total_recettes_mois_{$num_mois} + (!empty($data3["SUM(montant)"]) ? $data3["SUM(montant)"] : 0);
		        $total_depenses_{$num_mois} = !empty($data4["SUM(montant)"]) ? $data4["SUM(montant)"] : 0;

				if ($count != 0) { // Il y a un résultat ds la base	 				
					foreach ($data as $row) {
						echo '<tr>';
                        echo '<td>' . date("d/m/Y H:i", strtotime($row['date_creation'])) . '</td>';								
						echo '<td>' . NumToMois(MoisAnnee($num_mois,$exercice_mois)) . '</td>';
						echo '<td>';
						echo ($row['montant']<0)?NumToTypeDepense($row['type']):NumToTypeRecette($row['type']);
                        echo '</td>';
                        if ($row['montant'] < 0 ) { // Dépense
                            echo '<td class="danger">';
                        } else { // Recette
                            echo '<td class="success">';
                        }                       
						echo ($row["mois_$num_mois"] == 0 )?number_format($row['montant'],2,',','.'):number_format($row["mois_$num_mois"],2,',','.');
						echo ' €</td>';
						echo '<td>' . NumToPeriodicitee($row['periodicitee']) . '</td>';	
						echo '<td>' . $row['commentaire'] . '</td>';
						echo '</tr>';
					}
				} // if
					$total_recettes = $total_recettes + $total_recettes_mois_{$num_mois};
				 	$total_depenses = $total_depenses + $total_depenses_{$num_mois};
			} // for
			Database::disconnect();
			?>	                        
                </tbody>
            </table>
            </div> <!-- /table-responsive -->
            
	        <!-- Affiche les sommmes -->        
			<p>
				<button type="button" class="btn btn-info">Total Recettes : <?php echo $total_recettes; ?> €</button>
				<button type="button" class="btn btn-info">Total Dépenses : <?php echo $total_depenses; ?> €</button>							
			</p>   

          </div>
        </div> <!-- /panel -->                		

    <?php require 'footer.php'; ?>   
    </div> <!-- /container -->


    <script>  
        /* Table initialisation */
        $(document).ready(function() {
            $('.datatable').dataTable({
                "sPaginationType": "bs_full",
                 "aaSorting": [ [1, null] ]
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