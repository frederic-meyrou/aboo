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
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
// Lecture dans la base des recettes et des dépenses (join sur user_id et exercice_id ) 
    $sql = "(SELECT date_creation, type, montant, commentaire, periodicitee,mois FROM recette WHERE
    		user_id = :userid AND exercice_id = :exerciceid)
    		UNION
    		(SELECT date_creation, type, montant * -1, commentaire, periodicitee,mois FROM depense WHERE
    		user_id = :userid AND exercice_id = :exerciceid)
    		ORDER BY date_creation
    		";
				
    $q = array('userid' => $user_id, 'exerciceid' => $exercice_id);
    
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
    $TableauBilanMensuel = CalculBilanMensuel($user_id, $exercice_id, $exercice_treso);    
    $TableauBilanAnnuel = CalculBilanAnnuel($user_id, $exercice_id, $TableauBilanMensuel);        
    
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
       
    <?php $page_courante = "journal.php"; require 'nav.php'; ?>
        
    <div class="container">
        <div class="page-header">          
            <h2>Journal des Recettes & Dépenses</h2>
        </div>
        
        <!-- Affiche les boutons de créations -->      
        <div class="btn-group">
            <a href="recette.php" class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span> Recettes</a>
            <a href="depense.php" class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span> Dépenses</a>
        </div>
        <!-- Affiche le bouton retour -->
        <a class="btn btn-primary" href="journal.php"><span class="glyphicon glyphicon-eject"></span> Retour</a>   

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
	            <button type="button" class="btn btn-info">Solde brut :</button>
	            <button type="button" class="btn btn-default"><?php echo $TableauBilanAnnuel['SOLDE']; ?> €</button>
	        </div>    
	        <div class="btn-group btn-group-sm">
	            <button type="button" class="btn btn-info">Salaire Moyen :</button>                             
	            <button type="button" class="btn btn-default"><?php echo $TableauBilanAnnuel['SALAIRE'] / 12; ?> €</button>                            
	        </div>    
	        <div class="btn-group btn-group-sm">
	            <button type="button" class="btn btn-info">Trésorerie :</button>               
	            <button type="button" class="btn btn-default"><?php echo $TableauBilanAnnuel['REPORT_TRESO']; ?> €</button>             
	        </div>
		</div>
        <br>
                
		<!-- Affiche la table -->
		<div class="panel panel-default">
		  <div class="panel-heading">
	        <h3 class="panel-title">Journal Annuel : <button type="button" class="btn btn-sm btn-info"><?php echo "$exercice_annee - " . ($exercice_annee +1); ?> : <span class="badge "><?php echo $count; ?></span></button>
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
  			    
    </div> <!-- /container -->
    
    <?php require 'footer.php'; ?>

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