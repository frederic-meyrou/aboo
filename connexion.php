<?php
session_start();
?>
<?php
include 'database.php';
// Le Formulaire est rempli
if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    // Lecture dans la base
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM user WHERE email = ? AND password = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($email,$password));
    $data = $q->fetch(PDO::FETCH_ASSOC);
    $count = $q->rowCount($sql);
    Database::disconnect();   
    if ($count==1) {
        // On a bien l'utilisateur dans la base, on charge ses infos dans la session      
        $_SESSION['authent'] = array(
            'id' => $data['id'],
            'email' => $email,
            'password' => $password,
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'expiration' => $data['expiration'],
            'admin' => $data['administrateur']
            );        
        if ($_SESSION['authent']['admin']==1) {
        // Cas ou l'utilisateur est Admin, redirection vers page admin
        header('Location:admin/user.php');            
        } else {
        // Redirection vers la home sécurisé            
        header('Location:home.php');
        }
    } else {
        //Utilisateur inconnu
        $error_unknown = 'Compte $email inconnu ou mot de passe invalide!';
    }  
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>GestAbo</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" media="screen">
    <link href="bootstrap/css/signin.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>

<body>
    <script src="bootstrap/js/jquery-2.0.3.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <div class="container">
      <?php if(isset($error_unknown)){ ?> 
      <div class="alert alert alert-fail alert-dismissable fade in">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <strong><?php echo "$error_unknown" ?>.</strong>
      </div>
      <?php  
      } ?>
      <form class="form-signin" action="connexion.php" method="post">
        <h2 class="form-signin-heading">Connectez-vous</h2>

        <input name="email" type="text" class="form-control" placeholder="Email" required autofocus>
        <input name="password" type="password" class="form-control" placeholder="Mot de passe" required>
           <div class="error"><?php if(isset($error_unknown)){ echo $error_unknown; } ?></div>

        <button class="btn btn-lg btn-primary btn-block" type="submit">Connexion</button>
      </form>
    
    <a href="register.php">Vous souhaitez créer un compte ?</a><br/>
    <a href="oubli.php">Vous avez oublié votre mot de passe ?</a><br/>

    </div> <!-- /container -->
</body>
</html>