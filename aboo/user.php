<!-- 
© Copyright : Aboo / www.aboo.fr : Frédéric MEYROU : tous droits réservés
-->
<?php
// Dépendances
	require_once('lib/fonctions.php');
	require_once('lib/database.php');
	
// Vérification de l'Authent
    session_start();
    require('lib/authent.php');
    if( !Authent::islogged()){
        // Non authentifié on repart sur la HP
        header('Location:../index.php');
    }
	
// Mode Debug
	$debug = false;	

// Récupération des variables de session d'Authent
    $user_id = $_SESSION['authent']['id']; 
    $nom = $_SESSION['authent']['nom'];
    $prenom = $_SESSION['authent']['prenom'];
	
// Sécurisation POST & GET
    foreach ($_GET as $key => $value) {
        $sGET[$key]=htmlentities($value, ENT_QUOTES);
    }
    foreach ($_POST as $key => $value) {
        $sPOST[$key]=htmlentities($value, ENT_QUOTES);
    }

// Check si le User n'est pas Admin et n'affiche pas la page
    if (!IsAdmin()) {
        header('Location:home.php');
    }
    
?>

<!DOCTYPE html>
<html lang="fr">
<?php require 'head.php'; ?>

<body>

    <?php $page_courante = "user.php"; require 'nav.php'; ?>  
    
    <div class="container">

       <div class="page-header">   
            <h2>Gestion des comptes utilisateur</h2>          
        </div>

		<div class="row">
			<p>
				<a href="user_create.php" class="btn btn-primary"><span class="glyphicon glyphicon-plus-sign"></span> Création d'un compte Utilisateur</a>
			</p>
			
	        <!-- Affiche les informations de debug -->
	        <?php 
	 		if ($debug) {
			?>
			<div class="span10 offset1">
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
	       </div>
	        <?php       
	        }   
	        ?>  
	       				
	        <div class="table-responsive">  		
			<table cellpadding="0" cellspacing="0" border="0" class="datatable table table-striped table-bordered table-hover success">
	              <thead>
	                <tr>
                      <th>eMail</th>
	                  <th>Mot de passe</th>
	                  <th>Nom</th>
					  <th>Prénom</th>
	                  <th>Téléphone</th>
	                  <th>Date inscription</th>
	                  <th>Date expiration</th>
	                  <th>Montant</th>
                      <th>Actif</th> 	                  
                      <th>Essai</th> 	                  
                      <th>Admin</th>  		                  
	                  <th>Action</th>
	                </tr>
	              </thead>
	              <tbody>
	              <?php 
				   $pdo = Database::connect();
				   $sql = 'SELECT * FROM user ORDER BY id DESC';
 				   foreach ($pdo->query($sql) as $row) {
					   		echo '<tr>';
                            echo '<td>'. $row['email'] . '</td>';
						   	echo '<td>'. $row['password'] . '</td>';
						   	echo '<td>'. ucfirst($row['nom']) . '</td>';
							echo '<td>'. ucfirst($row['prenom']) . '</td>';
							echo '<td>'. $row['telephone'] . '</td>';
						   	echo '<td>'. DateFr($row['inscription']) . '</td>';
							if ((!$row['expiration']==null ) && strtotime($row['expiration']) < strtotime(date("Y-m-d")) ) { // Expiré
								echo '<td class="danger">';
							} else { 
								echo '<td>';
							}							
							echo DateFr($row['expiration']) . '</td>';
						   	echo '<td class="text-right">'. number_format($row['montant'],2,',','.') . ' €</td>';
							if ($row['actif']==0 ) { // Actif
								echo '<td class="danger">';
							} else { 
								echo '<td>';
							}								
                            echo ($row['actif']==1)?'Oui':'Non' . '</td>';
							if ($row['essai']==1 ) { // Essai
								echo '<td class="danger">';
							} else { 
								echo '<td>';
							}							   
                            echo ($row['essai']==1)?'Oui':'Non' . '</td>';   														
							if ($row['administrateur']==1 ) { // Admin
								echo '<td class="info">';
							} else { 
								echo '<td>';
							}
							echo ($row['administrateur']==1)?'Oui':'Non' . '</td>';                                
						   	echo '<td width=90>';
				  ?>	
							<div class="btn-group btn-group-sm">
								  	<a href="user_update.php?id=<?php echo $row['id']; ?>" class="btn btn-default btn-sm btn-warning glyphicon glyphicon-edit" role="button"> </a>
								  	<!-- Le bonton Delete active la modal et modifie le champ value à la volée pour passer l'ID a supprimer en POST -->
								  	<a href="#" id="<?php echo $row['id']; ?>"
								  	   onclick="$('#DeleteInput').val('<?php echo $row['id']; ?>'); $('#modalDelete').modal('show'); "
								  	   class="btn btn-default btn-sm btn-danger glyphicon glyphicon-trash" role="button"> </a>								  	
							</div>
							
						   	</td>						
							</tr>
				  <?php								                             
				   }
				   Database::disconnect();
				  ?>
			      </tbody>
            </table>
            </div> <!-- /table-responsive -->              
	   	</div> <!-- /row -->
	   
        <!-- Modal delete-->                
        <?php include('modal/admin_delete.php'); ?>      
	
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