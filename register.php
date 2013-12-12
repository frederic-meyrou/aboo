<?php

// Mode Debug
	$debug = false;

// Sécurisation POST & GET
    foreach ($_GET as $key => $value) {
        $sGET[$key]=htmlentities($value, ENT_QUOTES);
    }
    foreach ($_POST as $key => $value) {
        $sPOST[$key]=htmlentities($value, ENT_QUOTES);
    }

// Initialisation de la base
    include_once 'database.php';
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if(!empty($_POST) && filter_var($sPOST['email'], FILTER_VALIDATE_EMAIL)) {
	
	// Récupération des variables du POST
    $prenom = $sPOST['prenom'];
	$nom = $sPOST['nom'];
    $email = $sPOST['email'];
    $password = $sPOST['password'];
    $telephone = $sPOST['telephone'];	
	$inscription = date("Y-m-d");
	// Generation du token
    $token = sha1(uniqid(rand()));

	// MàJ de la BDD
    $q = array('prenom'=>$prenom, 'nom'=>$nom, 'email'=>$email, 'telephone'=>$telephone, 'password'=>$password, 'inscription'=>$inscription, 'token'=>$token);
    $sql = 'INSERT INTO user (prenom, nom, email, telephone, password, inscription, token) VALUES (:prenom, :nom, :email, :telephone, :password, :inscription, :token)';
    $req = $pdo->prepare($sql);
    $req->execute($q);
    Database::disconnect(); 

    //Envoyer un mail pour la validation du compte
    $to = $email;
    $sujet = 'Activation de votre compte';
    $body = '
    Bonjour, veuillez activer votre compte en cliquant ici ->
    <a href="http://localhost/gestabo/activate.php?token='.$token.'&email='.$to.'">Activation du compte</a>';
    $entete = "MIME-Version: 1.0\r\n";
    $entete .= "Content-type: text/html; charset=UTF-8\r\n";
    $entete .= 'From: CreatiQ.FR ::' . "\r\n" .
    'Reply-To: contact@creatiq.fr' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

	// Envoi eMail
    mail($to,$sujet,$body,$entete);
	
	// Retour
	header('Location: index.php');
} else {
	// On traite les erreur de formulaire 
    if(!empty($sPOST['nom'])){
        $error_nom = 'Veuillez renseigner votre nom';
    }
    if(!empty($sPOST['prenom'])){
        $error_prenom = 'Veuillez renseigner votre prénom';
    }
    if(!empty($sPOST['password'])){
        $error_password = 'Veuillez renseigner un mot de passe';
    }
    if(!empty($sPOST['telephone'])){
        $error_telephone = 'Veuillez renseigner votre téléphone';
    }		
    if(!empty($_POST) && !filter_var($sPOST['email'], FILTER_VALIDATE_EMAIL)){
        $error_email = ' Votre Email n\'est pas valide !';
    }

}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>GestAbo</title>
    <meta charset="utf-8">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" media="screen">
</head>

<body>

    <script src="bootstrap/js/jquery-2.0.3.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
       	
    <div class="header">
        <div class="wrap">
            <h1>Créer un Compte utilisateur</h1>
        </div>
   </div>

    <div class="wrap">
        <div class="content">
            <div class="exemple">
                <h2>Formulaire d'inscription :</h2>
                <form action="register.php" method="POST">

                    <label for="prenom">Prenom : </label><br/>
                    <input type="text" name="prenom"/><br/>
                    <div class="error"><?php if(isset($error_prenom)){ echo $error_prenom; } ?></div>

                    <label for="nom">Prenom : </label><br/>
                    <input type="text" name="nom"/><br/>
                    <div class="error"><?php if(isset($error_nom)){ echo $error_nom; } ?></div>

                    <label for="email">Email : </label><br/>
                    <input type="email" name="email"/><br/>
                    <div class="error"><?php if(isset($error_email)){ echo $error_email; } ?></div>

                    <label for="nom">Téléphone : </label><br/>
                    <input type="phone" name="telephone"/><br/>
                    <div class="error"><?php if(isset($error_telephone)){ echo $error_telephone; } ?></div>
                    
                    <label for="password">Password : </label><br/>
                    <input type="password" name="password" value=""/><br/><br/>
                    
                    <input type="submit" value="S'inscrire"/>
                    
                </form><br/>
           </div>
        </div>
    </div>

</body>
</html>