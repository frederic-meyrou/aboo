<!-- 
© Copyright : Aboo / www.aboo.fr : Frédéric MEYROU : tous droits réservés
-->
<?php
// Dépendances
	require_once('../lib/fonctions.php');
	require_once('../lib/database.php');
	
// Vérification de l'Authent
    session_start();
    require('../lib/authent.php');
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
?>

<!DOCTYPE html>
<html lang="fr">
<?php require 'head.php'; ?>

<body>

    <?php $page_courante = "user.php"; require 'nav.php'; ?>  
    
    <div class="container">
        <h2>Gestion des comptes utilisateur</h2>  	
    	<br>
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
	       				
			
			<table class="table table-striped table-bordered table-hover success">
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
						   	echo '<td>'. $row['nom'] . '</td>';
							echo '<td>'. $row['prenom'] . '</td>';
							echo '<td>'. $row['telephone'] . '</td>';
						   	echo '<td>'. $row['inscription'] . '</td>';
							echo '<td>'. $row['expiration'] . '</td>';
						   	echo '<td>'. number_format($row['montant'],2,',','.') . '</td>';
                            echo '<td>'; echo ($row['administrateur']==1)?'Oui':'Non' . '</td>';                                
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
	   	</div> <!-- /row -->
	   
		<!-- Modal Delete -->
		<div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-labelledby="DeleteModalLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content">
		        <form class="form-horizontal" action="user_delete.php" method="post">
		          <div class="modal-header">
		            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		            <h3 class="modal-title" id="DeleteModalLabel">Suppression Utilisateur :</h3>
		          </div><!-- /.modal-header -->
		          <div class="modal-body">
		              <center><strong>
		               <p class="alert alert-danger">Confirmez-vous la suppression ?</p>
		               <!--<p class="alert alert-warning">Attention cette action supprimera aussi tout les enregistrements associées.</p>-->
		               <input id="DeleteInput" type="hidden" name="id" value=""/>
		              </strong></center>
		          </div><!-- /.modal-body -->                                         
		          <div class="modal-footer">
		            <div class="form-actions">                              
		                <button type="submit" class="btn btn-danger pull-right"><span class="glyphicon glyphicon-trash"></span> Suppression</button>
		                <button type="button" class="btn btn-primary pull-left" data-dismiss="modal"><span class="glyphicon glyphicon-chevron-up"></span> Retour</button>                                  
		            </div>
		          </div><!-- /.modal-footer -->
		        </form>                   
		    </div><!-- /.modal-content -->
		  </div><!-- /.modal-dialog -->
		</div><!-- /.modal -->	   
	
	</div> <!-- /container -->
    
    <?php require 'footer.php'; ?>    
    
  </body>
</html>