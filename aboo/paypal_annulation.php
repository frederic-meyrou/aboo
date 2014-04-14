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
        <div class="alert alert alert-danger alert-dismissable fade in">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

			<h3><p>Vous avez annulé la transaction ou elle s'est terminée en erreur.</p></h3>
			<blockquote>
			  <p>Votre compte n'a pas été débité.</p>
			  <p>En cas de problème veuillez contacter le support Aboo : contact@aboo.fr</p>		  
			</blockquote>

        </div>
       </div>


       
    <?php require 'footer.php'; ?>   
    </div> <!-- /container -->

  </body>
</html>