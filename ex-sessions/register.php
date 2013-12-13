<?php require_once 'cnx.php'; ?>
<?php
if(!empty($_POST) && strlen($_POST['prenom'])>4 && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $prenom = addslashes($_POST['prenom']);
    $email = addslashes($_POST['email']);
    $password = sha1($_POST['password']);
    $token = sha1(uniqid(rand()));

    $q = array('prenom'=>$prenom, 'email'=>$email, 'password'=>$password, 'token'=>$token);
    $sql = 'INSERT INTO user (prenom, nom, email, telephone, password, inscription, token) VALUES (:prenom, :nom, :email, :telephone, :password, :inscription :token)';
    $req = $cnx->prepare($sql);
    $req->execute($q);
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

    mail($to,$sujet,$body,$entete);
} else {
    if(!empty($_POST[nom])){
        $error_nom = 'Veuillez renseigner votre nom';
    }
    if(!empty($_POST[prenom])){
        $error_prenom = 'Veuillez renseigner votre prénom';
    }	
    if(!empty($_POST) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
        $error_email = ' Votre Email n\'est pas valide !';
    }

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//FR" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
    <title>Créer un Espace Membre en PHP</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <link rel="stylesheet" href="style.css" type="text/css" charset="utf-8"/>
</head>
<body>

    <div class="header">
        <div class="wrap">
            <h1>Créer un Espace Membre en PHP</h1>
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

                    <label for="email">Email : </label><br/>
                    <input type="text" name="email"/><br/>
                    <div class="error"><?php if(isset($error_email)){ echo $error_email; } ?></div>

                    <label for="password">Password : </label><br/>
                    <input type="password" name="password" value=""/><br/><br/>
                    
                    <input type="submit" value="S'inscrire"/>
                    
                </form><br/>
           </div>
        </div>
    </div>

</body>
</html>