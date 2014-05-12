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

// Vérification du GET
    $id = null;
    if ( !empty($sGET['id'])) {
        $id = $sGET['id'];
    }  	

// Initialisation de la base
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
// Lecture BDD : Jointure dans la base recette/paiement (join sur user_id et exercice_id) 
    $sql = "SELECT * FROM paiement,recette WHERE
    		recette.id = :id   		
    		AND recette.id = paiement.recette_id    		
    		";
    $sql2 = "SELECT * FROM client,recette WHERE
    		recette.id = :id   		
    		AND client.id = recette.client_id    		
    		";

    $q = array('id' => $id);	
	$req = $pdo->prepare($sql);
	$req->execute($q);
	$data = $req->fetch(PDO::FETCH_ASSOC);
	$count = $req->rowCount($sql);

	$req = $pdo->prepare($sql2);
	$req->execute($q);
	$data2 = $req->fetch(PDO::FETCH_ASSOC);
	$count2 = $req->rowCount($sql2);
	
	Database::disconnect();		
?>

<!DOCTYPE html>
<html lang="fr">
<?php require 'head.php'; ?>

<body>

    <?php $page_courante = "recette_details.php"; require 'nav.php'; ?>
        
    <div class="container">

        <div class="page-header">           
            <h2>Recettes : détail des paiements étalés</h2>       
        </div>
            	
		<p>
	 	    <a href="recette.php" class="btn btn-sm btn-primary" ><span class="glyphicon glyphicon-eject"></span> Retour</a>			
		</p>
				             
        <!-- Affiche les informations de debug -->
        <?php 
 		if ($debug) {
		?>
        <div class="alert alert alert-danger alert-dismissable fade in">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <strong>Informations de Debug : </strong><br>
            SESSION:<br>
            <pre><?php var_dump($_SESSION); ?></pre>
            DATA:<br>
            <pre><?php var_dump($data); ?></pre>
            GET:<br>
            <pre><?php var_dump($_GET); ?></pre>
        </div>
        <?php       
        }   
        ?> 

		<!-- Affiche le detail d'une Recette -->   
    	<div class="row">

			<div class="col-sm-6">
				
            <!-- Coordonnées -->    				
              <div class="panel panel-success">
                <div class="panel-heading">
                  <h3 class="panel-title"><?php echo NumToTypeRecette($data['type']); ?></h3>
                </div>
                <div class="panel-body">
			          <?php	
								
								echo 'Date de création : '. date("d/m/Y H:i", strtotime($data['date_creation'])) . '<br>';
								echo 'Montant : '. number_format($data['montant'],2,',','.') . ' €</br>'; 							
								echo 'Périodicité : '. NumToPeriodicitee($data['periodicitee']) . '<br>'; 							
								echo 'Client : '. ucfirst($data2['prenom']) . ' ' . ucfirst($data2['nom']) . '<br>'; 								
								echo 'Description : '. $data['commentaire'] . '<br>';							
					  ?>
                </div>
              </div>
              
            </div> <!-- /col -->  
                 			
		</div> 	<!-- /row -->

        <!-- Affiche la table -->
        <?php 
 		if ($count > 0) {
		?>        
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Liste des paiements étalés :
            <div class="btn-group btn-group-xs pull-right">
                <a href="csv/export_ventes_client.php?id=<?php echo $id; ?>" target="_blank" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-list-alt"></span> Export Excel</a>
                <a href="#" target="_blank" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-briefcase"></span> Export PDF</a>                            
            </div>          
            </h3>
          </div>            
          <div class="panel-body">
            <div class="table-responsive">          
            <table cellpadding="0" cellspacing="0" border="0" class="datatable table table-striped table-bordered table-hover success">
                <thead>
					<tr class="active">			    
					  <th>Mois</th>
					  <th>Echéance</th>
					  <th>Encaissé</th>						  						  
					</tr>
                </thead>
                
                <tbody>
			<?php 
			$total = 0;
			$total_apayer = 0;
			
 			for ($num_mois = 1; $num_mois <= 12; $num_mois++) {
 				
				// Jointure dans la base recette/paiement (join sur user_id et exercice_id) 
			    $sql = "SELECT paiement.mois_$num_mois,paiement.paye_$num_mois FROM paiement,recette  WHERE
			    		recette.id = :id AND
			    		recette.id = paiement.recette_id
			    		";
			
				// Requette pour calcul de la somme Totale			
			    $sql2 = "SELECT SUM(P.mois_$num_mois) FROM paiement P, recette A WHERE
			    		A.id = :id AND
			    		A.id = P.recette_id AND 
			    		P.mois_$num_mois <> 0
			    		";
			
				// Requette pour calcul de la somme restant à mettre en recouvrement			
			    $sql3 = "SELECT SUM(P.mois_$num_mois) FROM paiement P, recette A WHERE
			    		A.id = :id AND
			    		A.id = P.recette_id AND
			    		P.mois_$num_mois <> 0 AND
			    		P.paye_$num_mois = 0
			    		";			
							
			    $q = array('id' => $id); 				

			    $req = $pdo->prepare($sql);
			    $req->execute($q);
			    $data = $req->fetch(PDO::FETCH_ASSOC);
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

						<?php 
							// Ecriture d'une ligne
								echo '<tr>';
								echo '<td>' . NumToMois(MoisAnnee($num_mois,$exercice_mois)) . '</td>';
								echo '<td>' . number_format($data["mois_$num_mois"],2,',','.') . ' €</td>';
								if ($data["mois_$num_mois"] <> 0 && $data["paye_$num_mois"] == 1 ) { //Encaissé
									echo '<td class="success">';
									echo 'Oui';
								} elseif ($data["mois_$num_mois"] <> 0 && $data["paye_$num_mois"] == 0) { // Non encaissé
									echo '<td class="danger">';
									echo 'Non';
								} elseif ($data["mois_$num_mois"] == 0) { //Montant null
									echo '<td>';
									echo 'S/O';
								}
								echo '</td>';
								echo '</tr>';
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
				<button type="button" class="btn <?php echo ($total_apayer > 0)?'btn-warning':'btn-success'; ?>">Total paiements à recouvrer : <?php echo $total_apayer; ?> €</button>							
			</p>
			                  
          </div>
        </div> <!-- /panel -->
        <?php       
        }   
        ?>         

    <?php require 'footer.php'; ?>             
    </div> <!-- /container -->

    <script>  
        /* Table initialisation */
        $(document).ready(function(){
            $('.datatable').dataTable({
                "iDisplayLength": 12,
                "aoColumnDefs": [        
                    { "sType": "enum", "aTargets": [ 0 ] },
                    { "asSorting": [ "asc", "desc" ], "aTargets": [ 0 ] },                                                
                    { "sType": "numeric-comma", "aTargets": [ 1 ] }                      
                    ]
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