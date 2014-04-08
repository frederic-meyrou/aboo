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
        	
// Initialisation de la base
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
// Lecture dans la base des recettes de l'année en fiscal de début d'exercice
    $sql = "(SELECT recette.date_creation, type, montant, commentaire, periodicitee, mois FROM recette,exercice WHERE
            (exercice.annee_debut = :annee 
            AND exercice.user_id = :userid
            AND recette.exercice_id = exercice.id
            AND recette.user_id = exercice.user_id
            AND recette.mois > (13 - exercice.mois_debut) )
            AND recette.mois <= 12 
            OR (exercice.annee_debut = (:annee - 1)
            AND exercice.user_id = :userid
            AND recette.exercice_id = exercice.id
            AND recette.user_id = exercice.user_id
            AND recette.mois >= 1
            AND recette.mois <= (13 - exercice.mois_debut) ) 
            ORDER BY recette.date_creation )
            UNION
            (SELECT depense.date_creation, type, montant * -1, commentaire, periodicitee, mois FROM depense,exercice WHERE
            (exercice.annee_debut = :annee 
            AND exercice.user_id = :userid
            AND depense.exercice_id = exercice.id
            AND depense.user_id = exercice.user_id
            AND depense.mois > (13 - exercice.mois_debut) )
            AND depense.mois <= 12 
            OR (exercice.annee_debut = (:annee - 1)
            AND exercice.user_id = :userid
            AND depense.exercice_id = exercice.id
            AND depense.user_id = exercice.user_id
            AND depense.mois >= 1
            AND depense.mois <= (13 - exercice.mois_debut) )
            ORDER BY depense.date_creation )
            ";
         
    $q = array('userid' => $user_id, 'annee' => $exercice_annee);
    
    $req = $pdo->prepare($sql);
    $req->execute($q);
    $data = $req->fetchAll(PDO::FETCH_ASSOC);
    $count = $req->rowCount($sql);
    
	if ($count==0) { // Il n'y a rien à afficher
        $affiche = false;       
    } else {
	        // On affiche le tableau
	        $affiche = true;
    }
	Database::disconnect();
	$infos = true;

// Charge le Bilan    
    //$TableauBilanMensuel = CalculBilanMensuel($user_id, $exercice_id, $exercice_treso);    
    $TableauBilanAnnuel = CalculBilanAnnuelFiscal($user_id, $exercice_annee);        
    
    if ($TableauBilanAnnuel==null) { // Il n'y a rien en base sur l'année (pas de dépenses et pas de recettes)
        $bilan = false;         
    } else {
            // On affiche le tableau
            $bilan = true;
    }
?>

<!DOCTYPE html>
<html lang="fr">
<?php require 'head.php'; ?>

<body>
       
    <?php $page_courante = "journal_fiscal.php"; require 'nav.php'; ?>
        
    <div class="container">
        <div class="page-header">          
            <h2>Journal sur l'année fiscale des Recettes & Dépenses</h2>
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
            data:<br>
            <pre><?php var_dump($data); ?></pre>
        </div>
        <br>
        <?php       
        }   
        ?>  

        <!-- Affiche les sommmes -->
        <div>      
            <div class="btn-group btn-group-sm">
                <button type="button" class="btn btn-info">CA :</button>
                <button type="button" class="btn btn-default"><?php echo $TableauBilanAnnuel['CA']; ?> €</button>
            </div>    
            <div class="btn-group btn-group-sm">
                <button type="button" class="btn btn-info">Non déclaré :</button>
                <button type="button" class="btn btn-default"><?php echo $TableauBilanAnnuel['NON_DECLARE']; ?> €</button>
            </div> 
            <div class="btn-group btn-group-sm">
                <button type="button" class="btn btn-info">Dépenses :</button>
                <button type="button" class="btn btn-default"><?php echo $TableauBilanAnnuel['DEPENSE']; ?> €</button>
            </div>    
            <div class="btn-group btn-group-sm">
                <button type="button" class="btn btn-warning">Bénéfices :</button>
                <button type="button" class="btn btn-default"><?php echo $TableauBilanAnnuel['BENEFICE']; ?> €</button>
            </div>    
            <div class="btn-group btn-group-sm">
                <button type="button" class="btn btn-info">Charges :</button>                             
                <button type="button" class="btn btn-default"><?php echo $TableauBilanAnnuel['CHARGE']; ?> €</button>                            
            </div>    
            <div class="btn-group btn-group-sm">
                <button type="button" class="btn btn-info">Impôts & Taxes :</button>               
                <button type="button" class="btn btn-default"><?php echo $TableauBilanAnnuel['IMPOT']; ?> €</button>             
            </div>
        </div>
        <br>
               
		<!-- Affiche la table -->
		<div class="panel panel-default">
		  <div class="panel-heading">
	        <h3 class="panel-title">Journal Année fiscale : <button type="button" class="btn btn-sm btn-info"><?php echo "$exercice_annee "; ?> : <span class="badge "><?php echo $count; ?></span></button>
			<div class="btn-group btn-group-sm pull-right">
	            <a href="csv/export_journal_annuel.php" target="_blank" class="btn btn-primary"><span class="glyphicon glyphicon-list-alt"></span> Export Excel</a>
	            <a href="journal_annuel_pdf.php" target="_blank" class="btn btn-primary"><span class="glyphicon glyphicon-briefcase"></span> Export PDF</a>                            
	        </div>	        
	        </h3>
		  </div>			
		  <div class="panel-body">
		    <div class="table-responsive">    		
			<table cellpadding="0" cellspacing="0" border="0" class="datatable table table-striped table-bordered table-hover success">
				<thead>
					<tr class="active">
					  <th>Date</th>
                      <th>Mois</th>                   
					  <th>Type</th>					  
					  <th>Montant</th>
					  <th>Commentaire</th>			  
					</tr>
				</thead>
	            
				<tbody>
				<?php 			 
					foreach ($data as $row) {
						echo '<tr>';
					    echo '<td>' . date("d/m/Y H:i", strtotime($row['date_creation'])) . '</td>';
                        echo '<td>' . NumtoMois(MoisAnnee($row['mois'],$exercice_mois)) . '</td>';
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
          </div> <!-- /table-responsive -->	        
		  </div>
		</div> <!-- /panel -->
    
    <?php require 'footer.php'; ?>  			    
    </div> <!-- /container -->

	<script>  
		/* Table initialisation */
		$(document).ready(function() {
			$('.datatable').dataTable();
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