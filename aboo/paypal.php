<!-- 
© Copyright : Aboo / www.aboo.fr : Frédéric MEYROU : tous droits réservés
-->

<?php

// Constantes et Variables de traitement de la notification IPN
	define("USE_SANDBOX", 1);
	if(USE_SANDBOX == true) {
		$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
		$paypal_ssl = 'ssl://www.sandbox.paypal.com';
	} else {
		$paypal_url = "https://www.paypal.com/cgi-bin/webscr";
		$paypal_ssl = 'ssl://www.paypal.com';	
	}

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

// Generation du token
    $token = sha1(uniqid(rand()));

// Initialisation de la base
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  
	
// MàJ du token dans la BDD
    $q = array($token, $user_id);
    $sql = 'UPDATE user set token=? WHERE id=?';
    $req = $pdo->prepare($sql);
    $req->execute($q);
 	Database::disconnect();

    $affiche_expire=false;    
    $datejour = date_create(date('Y-m-d'));
    $datefin = date_create($_SESSION['authent']['expiration']);
    if ( $datefin < $datejour ) {
        $affiche_expire=true;
    }
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

		<div class="row">

        <?php 
        if ($affiche_expire) {
        ?>			
		<div class="col-md-8 col-md-offset-1">
	        <div class="alert alert alert-danger alert-dismissable fade in">
	            <!--<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>-->
	
				<h3><p>Votre abonnement est expiré, <br>veuillez régler votre licence Aboo.</p></h3>
				<blockquote>
				  <p>Le montant est de 48€ annuel.</p>
				  <p>En cas de problème veuillez contacter le support Aboo : contact@aboo.fr</p>		  
				</blockquote>
	
	        </div>
       </div>
        <?php       
        } else {   
        ?> 
        <div class="col-md-8 col-md-offset-1">
            <div class="alert alert alert-info alert-dismissable fade in">
                <!--<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>-->
    
                <h3><p>Pour renouveller votre abonnement Aboo, <br>veuillez cliquer sur le lien Paypal suivant.</p></h3>
                <blockquote>
                  <p>Le montant est de 48€ annuel.</p>
                  <p> Vous pouvez régler par CB ou compte PayPal.</p>
                  <p>En cas de problème veuillez contacter le support Aboo : contact@aboo.fr</p>          
                </blockquote>
    
            </div>
       </div>   
        <?php       
        }   
        ?>                         
			<!--<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_top">
			<input type="hidden" name="cmd" value="_s-xclick">
			<input type="hidden" name="hosted_button_id" value="5H3HV5JR8R6AA">
			<input type="image" src="https://www.sandbox.paypal.com/fr_FR/FR/i/btn/btn_paynowCC_LG.gif" border="0" name="submit" alt="PayPal">
			<img alt="" border="0" src="https://www.sandbox.paypal.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
			</form>-->

		<div class="col-md-3">
			<br><br><br><br>
			<center>
			<form action="<?php echo $paypal_url; ?>/cgi-bin/webscr" method="post">
				<input name="amount" type="hidden" value="48" />
				<input name="currency_code" type="hidden" value="EUR" />
				<input name="shipping" type="hidden" value="0.00" />
				<input name="tax" type="hidden" value="0.00" />
				<input name="return" type="hidden" value="http://www.aboo.fr/aboo/paypal_retour.php" />
				<input name="cancel_return" type="hidden" value="http://www.aboo.fr/aboo/paypal_annulation.php" />
				<input name="notify_url" type="hidden" value="http://www.aboo.fr/aboo/paypal_ipn.php" />
				<input name="cmd" type="hidden" value="_xclick" />
				<input name="business" type="hidden" value="contact@aboo.fr" />
				<input name="item_name" type="hidden" value="Abonnement Aboo 1 an" />
				<input name="no_note" type="hidden" value="1" />
				<input name="lc" type="hidden" value="FR" />
				<input name="bn" type="hidden" value="PP-BuyNowBF" />
				<input name="custom" type="hidden" value="userid=<?php echo $user_id; ?>&token=<?php echo $token; ?>" />
				<input name="first_name" type="hidden" value="<?php echo $prenom; ?>" />
				<input name="last_name" type="hidden" value="<?php echo $nom; ?>" />							
				<input type="image" src="<?php echo $paypal_url; ?>/fr_FR/FR/i/btn/btn_paynowCC_LG.gif" border="0" name="submit" alt="PayPal">
				<img alt="" border="0" src="<?php echo $paypal_url; ?>/fr_FR/i/scr/pixel.gif" width="1" height="1">
			</form>
			</center>	
       	</div>

	  </div> <!-- row -->
              
    <?php require 'footer.php'; ?>   
    </div> <!-- /container -->

  </body>
</html>