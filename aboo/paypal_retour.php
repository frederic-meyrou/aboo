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

// Initialisation de la base
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   
	
// On recharge la SESSION avec la nouvelle valeure d'expiration
    $sql = "SELECT * FROM user where id = ?";    
    $req = $pdo->prepare($sql);
    $req->execute(array($user_id));
    $data = $req->fetch(PDO::FETCH_ASSOC);
    Database::disconnect();	
    $_SESSION['authent']['expiration'] = $data['expiration'];  
?>

<!DOCTYPE html>
<html lang="fr">
<?php require 'head.php'; ?>

<body>

    <?php require 'nav.php'; ?>  
    	
	<div class="container">

        <div class="page-header">   
            <h2>Paiement de votre abonnement</h2>        
        </div>    

        <!-- Affiche le message de retour -->
		<div class="span10 offset1">
        <div class="alert alert alert-success alert-dismissable fade in">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<h3><p>Votre transaction s'est terminée avec succès.</p></h3>            
			<blockquote>
			  <p>Votre license Aboo est maintenant valide jusqu'au <strong><?php echo date("d/m/Y", strtotime($_SESSION['authent']['expiration'])); ?></strong>.</p>
			  <p>L'équipe Aboo vous remerçie <?php echo $prenom; ?>.</p>
			</blockquote>
			
        </div>
       </div>

       
    <?php require 'footer.php'; ?>   
    </div> <!-- /container -->

  </body>
</html>