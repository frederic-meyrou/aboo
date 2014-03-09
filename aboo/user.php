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

// Initialisation de la base
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);    

// Lecture du POST Formulaire
    $utilisateur = null;     
    if (isset($_POST['utilisateur']) ) { // J'ai un POST
        $utilisateur = $_POST['utilisateur'];
        $sql = "SELECT * FROM user where id = ?";    
        $req = $pdo->prepare($sql);
        $req->execute(array($utilisateur));
        $data = $req->fetch(PDO::FETCH_ASSOC);    
        $_SESSION['authent']['id']=$utilisateur;
        $_SESSION['authent']['nom']=$data['nom'];
        $_SESSION['authent']['prenom']=$data['prenom'];
    }    
    
// Lectures données dans la BDD
    $sql = 'SELECT * FROM user ORDER BY id DESC';    
    $req = $pdo->prepare($sql);
    $req->execute();
    $data = $req->fetchAll(PDO::FETCH_ASSOC);    
    $count = $req->rowCount($sql);
    Database::disconnect();
    
 // Liste des utilisateurs à afficher dans le select
    $Liste_Utilisateur = array();        
    if ($count!=0) {
        $i=0;
        foreach ($data as $row) {
            $Liste_Utilisateur[$i]['id']=$row['id'];
            $Liste_Utilisateur[$i]['prenometnom']=ucfirst($row['prenom']) . ' ' . ucfirst($row['nom']);
            $i++;
        }       
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

        <!-- Affiche le dropdown formulaire utilisateur -->
        <form class="form-inline" role="form" action="user.php" method="post">      
            <select name="utilisateur" class="form-control">
            <?php
                foreach ($Liste_Utilisateur as $u) {
            ?>  
                <option value="<?php echo $u['id'];?>" <?php echo ($u['id']==$user_id)?'selected':'';?>><?php echo $u['prenometnom'];?></option>                  
            <?php       
                }   
            ?>    
            </select>
            <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-refresh"></span> Simuler un utilisateur</button>

                <a href="user_create.php" class="btn btn-primary"><span class="glyphicon glyphicon-plus-sign"></span> Création d'un compte Utilisateur</a>
                <a href="user_excel.php" target="_blank" class="btn btn-primary"><span class="glyphicon glyphicon-list-alt"></span> Export Excel</a>                         
        </form>
        <br>  			
		
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
            DATA:<br>
            <pre><?php var_dump($data); ?></pre>
        </div>
       </div>
        <?php       
        }   
        ?>  
       				
        <!-- Affiche la table -->
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Liste des utilisateurs de Aboo : <span class="badge "><?php echo $count; ?></span></h3>
          </div>            
          <div class="panel-body">
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
 				   foreach ($data as $row) {
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
				  ?>
			      </tbody>
            </table>
          </div> <!-- /table-responsive -->         
          </div>
        </div> <!-- /panel -->
                        
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