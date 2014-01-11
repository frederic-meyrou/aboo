<?php
session_start();
require_once 'cnx.php'; ?>
<?php

if(!empty($_POST)){
    $email = $_POST['email'];
    $password = sha1($_POST['password']);

    $q = array('email'=>$email, 'password'=>$password);
    $sql = 'SELECT email,password FROM users WHERE email = :email AND password = :password';
    $req = $cnx->prepare($sql);
    $req->execute($q);
    $count = $req->rowCount($sql);
    if($count == 1){
        //Verifier si l'utilisateur est actif
        $sql = 'SELECT email,password FROM users WHERE email = :email AND password = :password AND activer = 1';
        $req = $cnx->prepare($sql);
        $req->execute($q);
        $actif = $req->rowCount($sql);
        if($actif == 1){
            $_SESSION['Auth'] = array(
                'email' => $email,
                'password' => $password
            );
            header('Location:private.php');
        }else{
            $error_actif = 'Votre compte n\'est pas actif ! Verifier vos mails pour activer votre compte !';
        }
    }else{
        //Si utilisateur inconnu
        $error_unknown = 'Utilisateur inexistant !';
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
                <h2>Formulaire de connexion :</h2>
                <form action="index.php" method="POST">

                    <label for="email">Email : </label><br/>
                    <input type="text" name="email"/><br/>

                    <label for="password">Password : </label><br/>
                    <input type="password" name="password" value=""/><br/><br/>

                    <input type="submit" value="Se connecter"/>
                         <div class="error"><?php if(isset($error_actif)){ echo $error_actif; } ?></div>
                         <div class="error"><?php if(isset($error_unknown)){ echo $error_unknown; } ?></div>
                </form><br/>
                <a href="register.php">Vous n'avez pas de compte ?</a><br/>
           </div>
        </div>
    </div>

</body>
</html>