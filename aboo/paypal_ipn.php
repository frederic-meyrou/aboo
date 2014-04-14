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

// Constantes et Variables de traitement de la notification IPN
	define("USE_SANDBOX", 1);
	define("LOG_FILE", "paypal_ipn.log");
	define("PRIX_ABO", 48);
	$email_account = "contact@aboo.fr";
	$req = 'cmd=_notify-validate';
	$timeout = 30;
	if(USE_SANDBOX == true) {
		$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
		$paypal_ssl = 'ssl://www.sandbox.paypal.com';
	} else {
		$paypal_url = "https://www.paypal.com/cgi-bin/webscr";
		$paypal_ssl = 'ssl://www.paypal.com';	
	}

// Read POST data : Transforme le POST serialisé
	foreach ($_POST as $key => $value) {
	    $value = urlencode(stripslashes($value));
	    $req .= "&$key=$value";
	}

// Initilise le log
	file_put_contents(LOG_FILE, "--- Debut IPN : " . date('d/m/Y H:i:s') .  " ---\n", FILE_APPEND);

// Lit les variables POST et prépare la requette de validation (req)
	if (isset($_POST['txn_id'])) {
		$header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
		$header .= "Host: " . $paypal_url . "\r\n";
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
		$fp = fsockopen ($paypal_ssl, 443, $errno, $errstr, $timeout); // Connexion au serveur Paypal
		$item_name = $_POST['item_name'];
		$item_number = $_POST['item_number'];
		$payment_status = $_POST['payment_status']; // IMPORTANT
		$payment_amount = $_POST['mc_gross']; // IMPORTANT
		$payment_currency = $_POST['mc_currency'];
		$txn_id = $_POST['txn_id']; // ID de la transaction
		$receiver_email = $_POST['receiver_email']; // IMPORTANT 
		$payer_email = $_POST['payer_email']; // A sauvegarder
		$transaction_subject = $_POST['transaction_subject'];
		parse_str($_POST['custom'],$custom); // Permet de passer une variable en paramêtre ds la transacation depuis le formulaire (UserID et token)
		if ( isset($custom['userid'])) { $userid=$custom['userid']; } else $userid=null;
		if ( isset($custom['token'])) { $token=$custom['token']; } else $token=null;
	} else {
		file_put_contents(LOG_FILE, "ERREUR : pas de POST!\n", FILE_APPEND);	
		exit(1);	
	}	

// Vérification userID et token
	// Lecture token en BDD
    $sql = "SELECT token FROM user where id = ?";    
    $req = $pdo->prepare($sql);
    $req->execute(array($user_id));
    $data = $req->fetch(PDO::FETCH_ASSOC);
	if ( $userid==$user_id && $token==$data['token']) {
		file_put_contents(LOG_FILE, "... User_ID et Token Ok.\n", FILE_APPEND);   		
	} else {
		file_put_contents(LOG_FILE, "ERREUR : User_ID ($userid) ou Token ($token) Ko : tentative de hacking?\n", FILE_APPEND);
		exit(1);   		
	}
	
// Traitement IPN
	if (!$fp && $errno == 0) { // Test la connexion, si FALSE la connexion est Ko
		echo "Erreur de connexion à " . $paypal_ssl . " : " .  $errstr; 
	    file_put_contents(LOG_FILE, "Erreur de connexion à " . $paypal_ssl . " : " .  $errstr . "\n", FILE_APPEND);
	} else { // Test le retour de la requette
		// Envoi le requette de validation
		fputs ($fp, $header . $req);
		while (!feof($fp)) {
		    $res = fgets ($fp, 1024);
		    if (strcmp ($res, "VERIFIED") == 0) { // Transaction valide
		        // vérifier que payment_status a la valeur Completed
		        if ($payment_status == "Completed") {
		               if ($email_account == $receiver_email) { 
			                file_put_contents(LOG_FILE, print_r($_POST, true), FILE_APPEND);
							// TODO : récupérer le prix de l'abo en base
							if ($payment_amount = PRIX_ABO)	{ // Le prix est le bon
								file_put_contents(LOG_FILE, "... Update date d'expiration utilisateur : $user_id.\n", FILE_APPEND);   								
							    $q = array($user_id);
							    $sql = 'UPDATE user set token=NULL, expiration=DATE_ADD(NOW(), INTERVAL 1 YEAR) WHERE id=?';
							    $req = $pdo->prepare($sql);
							    $req->execute($q);
							 	Database::disconnect();							
								// TODO : Inserer un enregistrement ds la Table "orders" : INSERT INTO orders SET user_id=$user_id, amount=$payment_amount, txn_id=$txn_id created=NOW()
								// ou $infos = serialize($_POST); + INSERT INTO infos=$infos
							} else {
								file_put_contents(LOG_FILE, "ERREUR : Le prix de transaction est incorrect ($payment_amount) : tentative de hacking?\n", FILE_APPEND);              	
							}		 
			           } else {
			           		file_put_contents(LOG_FILE, "ERREUR : Le compte Paypal cible est incorect ($receiver_email) : tentative de hacking?\n", FILE_APPEND);              	
			           }						
		        } else { // Statut de paiement: Echec
	             	file_put_contents(LOG_FILE, "ERREUR : Le paiement n'a pas été validé. Status : $payment_status.\n", FILE_APPEND);
				}
				exit;		                
		    } elseif (strcmp ($res, "INVALID") == 0) { // Transaction invalide
	             	file_put_contents(LOG_FILE, "ERREUR : La transaction est invalide ou refusée.\n", FILE_APPEND);					
		    }
			fclose ($fp);
		}	
	}

// Finalise le log
	file_put_contents(LOG_FILE, "--- Fin IPN : " . date('d/m/Y H:i:s') .  " ---\n", FILE_APPEND);

?>