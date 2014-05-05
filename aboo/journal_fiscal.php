<!-- 
© Copyright : Aboo / www.aboo.fr : Frédéric MEYROU : tous droits réservés
-->
<?php
// Dépendances
	require_once('lib/fonctions.php');
    include_once('lib/database.php');
    include_once('lib/calcul_bilan.php');
	
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

// Charge le Tableau    
    $TableauFiscalAnnuel = CalculTableauFiscalAnnuel($user_id, $exercice_annee);          
    
    if ($TableauFiscalAnnuel==false) { // Il n'y a rien en base sur l'année (pas de dépenses et pas de recettes)
        $affiche = false;         
    } else {
            // On affiche le tableau
            $affiche = true;
    }
	
?>

<!DOCTYPE html>
<html lang="fr">
<?php require 'head.php'; ?>

<body>

    <?php $page_courante = "journal_fiscal.php"; require 'nav.php'; ?>
        
    <div class="container">
        <div class="page-header">           
            <h2>Journal Fiscal (Recettes & Dépenses)</h2>
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
            TableauFiscalAnnuel:<br>
            <pre><?php var_dump($TableauFiscalAnnuel); ?></pre>
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
                      <th>Date de création</th>					    
					  <th>Mois</th>	  						  
					  <th>Type</th>
					  <th>Montant</th>					  					  					  
					  <th>Périodicitée</th>					  
					  <th>Commentaire</th>			  
					</tr>
				</thead>
                <tbody>	
			<?php 

					foreach ($TableauFiscalAnnuel["TABLEAU"] as $row) {
						echo '<tr>';
                        echo '<td>' . $row['DATE'] . '</td>';								
						echo '<td>' . $row['MOIS'] . '</td>';
						echo '<td>' . $row['TYPE'] . '</td>';
                        if ($row['MONTANT'] < 0 ) { // Dépense
                            echo '<td class="danger">';
                        } else { // Recette
                            echo '<td class="success">';
                        }                       
						echo $row['MONTANT'] . ' €</td>';
						echo '<td>' . $row['PERIODICITE'] . '</td>';	
						echo '<td>' . $row['COMMENTAIRE'] . '</td>';
						echo '</tr>';
					} // foreach
			?>	                        
                </tbody>
            </table>
            </div> <!-- /table-responsive -->
            
	        <!-- Affiche les sommmes -->        
			<p>
                <button type="button" class="btn btn-default">Nombre d'opérations : <?php echo $TableauFiscalAnnuel["COUNT"]; ?></button>			    
				<button type="button" class="btn btn-info">Total Recettes : <?php echo $TableauFiscalAnnuel["RECETTES"]; ?> €</button>
				<button type="button" class="btn btn-info">Total Dépenses (hors charges) : <?php echo $TableauFiscalAnnuel["DEPENSES"]; ?> €</button>							
                <button type="button" class="btn btn-<?php echo ($TableauFiscalAnnuel["RECETTES"] < $TableauFiscalAnnuel["DEPENSES"])?'warning"> Déficit : ':'success"> Bénéfice : ' . ( $TableauFiscalAnnuel["RECETTES"] - $TableauFiscalAnnuel["DEPENSES"] ); ?> €</button>   
			</p>   

          </div>
        </div> <!-- /panel -->                		

    <?php require 'footer.php'; ?>   
    </div> <!-- /container -->

    <script>
        /* Table initialisation */
        $(document).ready(function() {
            $('.datatable').dataTable({
                "iDisplayLength": 10,
                "aoColumnDefs": [
                    { "sType": "date-euro", "aTargets": [ 0 ] },
                    { "sType": "enum", "aTargets": [ 1 ] },
                    { "asSorting": [ "asc", "desc" ], "aTargets": [ 1 ] },                                         
                    { "aDataSort": [ 1, 0 ], "aTargets": [ 0 ] },
                    { "sType": "numeric-comma", "aTargets": [ 3 ] },                    
                    { "bSortable": false, "aTargets": [ 5 ] }                        
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