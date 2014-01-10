<?php
session_start();
require('auth.php');
if(Auth::islog()){

}else{
    header('Location:index.php');
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
                <h1>Private Page</h1>
                <a href="logout.php">Se déconnecter !</a>
           </div>
        </div>
    </div>

</body>
</html>