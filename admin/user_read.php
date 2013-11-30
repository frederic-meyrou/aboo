<!DOCTYPE html>
<html lang="fr">
<head>
    <title>GestAbo</title>
    <meta charset="utf-8">
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" media="screen">
</head>

<body>
    <script src="../bootstrap/js/jquery-2.0.3.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <div class="container">
        <h2>Gestion des comptes utilisateur</h2>    
        <ul class="nav nav-pills">
          <li class="active"><a href="../index.php">HomePage</a></li>
          <li><a href="../crud/index.php">Exemple CRUD</a></li>
        </ul>
        <div class="span10 offset1">
            <div class="row">
                <h3>Consultation d'un compte Utilisateur</h3>
            </div>    
            <div class="row">
               
                <table class="table table-striped table-bordered table-hover success">
                      <thead>
                        <tr>
                          <th>Identifiant</th>
                          <th>Mot de passe</th>
                          <th>Nom</th>
                          <th>Prénom</th>
                          <th>eMail</th>
                          <th>Téléphone</th>
                          <th>Date inscription</th>
                          <th>Date expiration</th>
                          <th>Montant abonnement</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php 
                       $id = null;
                       if ( !empty($_GET['id'])) {
                            $id = $_REQUEST['id'];
                       }                      
                       if ( null==$id ) {
                            header("Location: user.php");
                       } else {
                            include '../database.php';
                            $pdo = Database::connect();
                            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            $sql = "SELECT * FROM user where id = ?";
                            $q = $pdo->prepare($sql);
                            $q->execute(array($id));
                            $data = $q->fetch(PDO::FETCH_ASSOC);
                       }

                                echo '<tr>';
                                echo '<td>'. $data['identifiant'] . '</td>';
                                echo '<td>'. $data['password'] . '</td>';
                                echo '<td>'. $data['nom'] . '</td>';
                                echo '<td>'. $data['prenom'] . '</td>';
                                echo '<td>'. $data['email'] . '</td>';
                                echo '<td>'. $data['telephone'] . '</td>';
                                echo '<td>'. $data['inscription'] . '</td>';
                                echo '<td>'. $data['expiration'] . '</td>';
                                echo '<td>'. $data['montant'] . '</td>';
                                echo '</tr>';
                       Database::disconnect();
                      ?>
                      </tbody>
                </table>
            </div> 	<!-- /row -->
        </div>  <!-- /span -->        			
    </div> <!-- /container -->
  </body>
</html>