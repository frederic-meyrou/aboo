<?php require_once 'cnx.php'; ?>
<?php
$token = $_GET['token'];
$email = $_GET['email'];
if(!empty($_GET)){
    $q = array('email'=>$email,'token'=>$token);
    $sql = 'SELECT email,token FROM users WHERE email = :email AND token = :token';
    $req = $cnx->prepare($sql);
    $req->execute($q);
    $count = $req->rowCount($sql);
    if($count == 1){
        $v = array('email'=>$email,'activer'=>'1');
        //Verfier si l'utilisateur est actif
        $sql = 'SELECT email,activer FROM users WHERE email = :email AND activer = :activer';
        $req = $cnx->prepare($sql);
        $req->execute($v);
        $dejactif = $req->rowCount($sql);
        if($dejactif == 1){
            $error_actif = 'Utilisateur deja actif !';
        }else{
            //Sinon on active l'utilisateur
            $u = array('email'=>$email,'activer'=>'1');
            $sql = 'UPDATE users SET activer = :activer WHERE email = :email';
            $req = $cnx->prepare($sql);
            $req->execute($u);
            $activated = 'Votre compte est desormais actif !';
        }
    }else{
        //Utilisateur incconu
        $prob_token = 'Mauvais Token';
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
                <div class="error"><?php if(isset($error_actif)){ echo $error_actif; } ?></div>
                <div class="error"><?php if(isset($activated)){ echo $activated; } ?></div>
                <div class="error"><?php if(isset($prob_token)){ echo $prob_token; } ?></div>
           </div>
        </div>
    </div>

</body>
</html>
