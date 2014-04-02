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
	
// Lecture BDD
	
	// Lecture du detail client
	$sql = "SELECT * FROM client WHERE
    		id = :id
    		";
    $q = array('id' => $id);	
	$req = $pdo->prepare($sql);
	$req->execute($q);
	$data = $req->fetch(PDO::FETCH_ASSOC);
	// Lecture du CA 
	$sql2 = "SELECT SUM(montant) FROM recette WHERE
    		client_id = :id AND user_id = :user_id
    		";
    $q2 = array('id' => $id, 'user_id' => $user_id);	
	$req2 = $pdo->prepare($sql2);
	$req2->execute($q2);
	$data2 = $req2->fetch(PDO::FETCH_ASSOC);
	// Lecture du nombre d'abonnements
	$sql3 = "SELECT * FROM recette WHERE
    		client_id = :id AND user_id = :user_id AND type = '1'
    		";
    $q3 = array('id' => $id, 'user_id' => $user_id);	
	$req3 = $pdo->prepare($sql3);
	$req3->execute($q3);
	//$data3 = $req3->fetchAll(PDO::FETCH_ASSOC);
	$count3 = $req3->rowCount($sql3);
	// Lecture du nombre de reventes
	$sql4 = "SELECT * FROM recette WHERE
    		client_id = :id AND user_id = :user_id AND type = '2'
    		";
    $q4 = array('id' => $id, 'user_id' => $user_id);	
	$req4 = $pdo->prepare($sql4);
	$req4->execute($q4);
	//$data4 = $req4->fetchAll(PDO::FETCH_ASSOC);
	$count4 = $req4->rowCount($sql4);
    // Lecture de toutes les ventes clients
    $sql5 = "SELECT * FROM recette WHERE
            client_id = :id AND user_id = :user_id
            ";
    $q5 = array('id' => $id, 'user_id' => $user_id);    
    $req5 = $pdo->prepare($sql5);
    $req5->execute($q5);
    $data5 = $req5->fetchAll(PDO::FETCH_ASSOC);	
    $count5 = $req5->rowCount($sql5);	
	
	Database::disconnect();		
?>

<!DOCTYPE html>
<html lang="fr">
<?php require 'head.php'; ?>

<body>

    <?php $page_courante = "mesclients.php"; require 'nav.php'; ?>
        
    <div class="container">

        <div class="page-header">           
            <h2>Gestion de mes clients</h2>       
        </div>
            	
		<p>
	 	    <a href="mesclients.php" class="btn btn-sm btn-primary" ><span class="glyphicon glyphicon-eject"></span> Retour</a>			
  			<a href="#" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-send"></span> Envoi d'un eMail</a>			
  			<a href="#" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-send"></span> Envoi d'un eMail de relance</a>			
  			<a href="#" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-briefcase"></span> Export PDF</a>
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
            POST:<br>
            <pre><?php var_dump($_POST); ?></pre>
            GET:<br>
            <pre><?php var_dump($_GET); ?></pre>
        </div>
        <?php       
        }   
        ?> 

		<!-- Affiche le detail d'un client -->
        <h2>Détails de : <?php echo ucfirst($data['prenom']) . ' '. ucfirst($data['nom']); ?></h2>       

    	<div class="row">

			<div class="col-sm-4">
				
            <!-- Coordonnées -->    				
              <div class="panel panel-success">
                <div class="panel-heading">
                  <h3 class="panel-title">Coordonnées</h3>
                </div>
                <div class="panel-body">
			          <?php	
								
								echo 'eMail : '. $data['email'] . '<br>';
								echo 'Téléphone Fixe : '. $data['telephone'] . '</br>'; 
								echo 'Mobile : '. $data['mobile']; 
					  ?>
                </div>
              </div>
           
              <!-- Adresse -->
              <div class="panel panel-success">
                <div class="panel-heading">
                  <h3 class="panel-title">Adresse</h3>
                </div>
                <div class="panel-body">
			          <?php	
								echo $data['adresse1'] . '</br>' . $data['adresse2'] . '</br>';
							   	echo $data['cp'] . '  '. $data['ville'];
					  ?>
                </div>
              </div>
              
            </div> <!-- /col -->  
                 			
			<div class="col-sm-4 col-md-offset-0">

              <!-- Statistiques -->
              <div class="panel panel-warning">
                <div class="panel-heading">
                  <h3 class="panel-title">Statistiques</h3>
                </div>
                <div class="panel-body">
			          <?php
			          		// CA
			          		echo 'CA : ';
							echo ($data2["SUM(montant)"] == null)?'0':$data2["SUM(montant)"];
							echo ' € <br>';
							// Nombre d'recettes
							echo "Nombre d'abonnements : $count3" .	'<br>';
							// Nombre de reventes
							echo "Nombre de ventes : $count4" .	'<br>';
					  ?>
                </div>
              </div>
              
              <!-- Information diverses -->
              <div class="panel panel-info">
                <div class="panel-heading">
                  <h3 class="panel-title">Informations diverses</h3>
                </div>
                <div class="panel-body">
			          <?php	
								echo 'Age : '. $data['age'] . '<br>';
							   	echo 'Profession : '. $data['profession'] . '<br>';
							   	echo 'Description : '. $data['description'];
					  ?>
                </div>
              </div>
              
            </div> <!-- /col -->
		</div> 	<!-- /row -->

        <!-- Affiche la table -->
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Liste des ventes au clients : <span class="badge "><?php echo $count5; ?></span>
            <div class="btn-group btn-group-xs pull-right">
                <a href="csv/export_ventes_client.php" target="_blank" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-list-alt"></span> Export Excel</a>
                <a href="#" target="_blank" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-briefcase"></span> Export PDF</a>                            
            </div>          
            </h3>
          </div>            
          <div class="panel-body">
            <div class="table-responsive">          
            <table cellpadding="0" cellspacing="0" border="0" class="datatable table table-striped table-bordered table-hover success">
                <thead>
                    <tr class="active">
                      <th>Date</th>
                      <th>Type</th>                   
                      <th>Montant</th>
                      <th>Périodicitée</th>                      
                      <th>Commentaire</th>            
                    </tr>
                </thead>
                
                <tbody>
                <?php            
                    foreach ($data5 as $row) {
                        echo '<tr>';
                        echo '<td>' . date("d/m/Y H:i", strtotime($row['date_creation'])) . '</td>';
                        echo '<td>' . NumToTypeRecette($row['type']) . '</td>';
                        echo '<td>' . number_format($row['montant'],2,',','.') . ' €</td>';
                        echo '<td>' . NumToPeriodicitee($row['periodicitee']) . '</td>';                        
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