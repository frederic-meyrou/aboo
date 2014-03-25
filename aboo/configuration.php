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

// Mode Debug
	$debug = false;
    $affiche = false;    

// Sécurisation POST & GET
    foreach ($_GET as $key => $value) {
        $sGET[$key]=htmlentities($value, ENT_QUOTES);
    }
    foreach ($_POST as $key => $value) {
        $sPOST[$key]=htmlentities($value, ENT_QUOTES);
    }
        	
// Récupération des variables de session d'Authent
    $user_id = $_SESSION['authent']['id']; 
    $nom = $_SESSION['authent']['nom'];
    $prenom = $_SESSION['authent']['prenom'];
    $nom = $_SESSION['authent']['nom'];

// Récupération des variables de session exercice
    $exercice_id = null;
    $exercice_annee = null;
    $exercice_mois = null;
    $exercice_treso = null;
    if(isset($_SESSION['exercice'])) {
        $exercice_id = $_SESSION['exercice']['id'];
        $exercice_annee = $_SESSION['exercice']['annee'];
        $exercice_mois = $_SESSION['exercice']['mois'];
        $exercice_treso = $_SESSION['exercice']['treso'];
    }
   
// Lecture du POST 

// Initialisation de la base
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
// Lecture BDD
    
    // Lecture des données de la table user
    $sql = "SELECT * FROM user WHERE id = :id";
    $q = array('id' => $user_id);    
    $req = $pdo->prepare($sql);
    $req->execute($q);
    $data = $req->fetch(PDO::FETCH_ASSOC);

    Database::disconnect();

    $affiche = false;       
?>

<!DOCTYPE html>
<html lang="fr">
<?php require 'head.php'; ?>

<body>

    <?php $page_courante = "configuration.php"; require 'nav.php'; ?>
    
    <div class="container">

        <div class="page-header">           
            <h2>Configuration de mes informations et des informations de ma société</h2>
        </div>
                
        <!-- Affiche les informations de debug -->
        <?php 
 		if ($debug) {
		?>
        <div class="alert alert alert-danger alert-dismissable fade in">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <strong>Informations de Debug : </strong><br>
            SESSION:<br>
            <pre><?php var_dump($_SESSION); ?></pre>
            POST:<br>
            <pre><?php var_dump($_POST); ?></pre>
            GET:<br>
            <pre><?php var_dump($_GET); ?></pre>
            DATA:<br>
            <pre><?php var_dump($data); ?></pre>            
        </div>
        <?php       
        }   
        ?>  

        <div class="row">

            <div class="col-sm-4">
                
            <!-- Mon compte -->                    
              <div class="panel panel-success">
                <div class="panel-heading">
                  <h3 class="panel-title"><span class="glyphicon glyphicon-user"></span> Mon compte Aboo</h3>
                </div>
                <div class="panel-body">
                      <?php                                
                                echo '<span class="glyphicon glyphicon-user"></span> : ';
                                echo ucfirst($data['prenom']) . ' ' . ucfirst($data['nom']) . '<br>';
                                
                                echo '<span class="glyphicon glyphicon-envelope"></span> : ';
                                echo $data['email'] . '<br>';

                                echo '<span class="glyphicon glyphicon-phone"></span> : ';                                
                                echo $data['mobile'] . '<br>'; 
                                
                                echo '<span class="glyphicon glyphicon-calendar"></span> : ';                                                                                                 
                                echo 'Inscription sur Aboo : ' . $data['inscription']. '<br>';
                                echo '<span class="glyphicon glyphicon-calendar"></span> : ';                                
                                echo 'Expiration de mon abonnement Aboo : ' . $data['expiration']. '<br>';

                                echo '<span class="glyphicon glyphicon-copyright-mark"></span> : ';
                                echo 'Abonnement Aboo : ';
                                if ($data['essai'] == 1) {
                                    echo 'License d\'essai<br>';
                                } elseif ($data['actif'] == 1) {
                                    echo 'License en cours de validité<br>';
                                } else {
                                    echo 'Inconnu, contacter le support Aboo SVP<br>';
                                }                       
                      ?>
                </div>
              </div>
           
              <!-- Adresse -->
              <div class="panel panel-success">
                <div class="panel-heading">
                  <h3 class="panel-title"><span class="glyphicon glyphicon-map-marker"></span> Coordonnées de ma société pour factures</h3>
                </div>
                <div class="panel-body">
                      <?php 
                                echo '<span class="glyphicon glyphicon-flag"></span> : ';                                
                                echo 'Raison Sociale : ' . $data['raison_sociale'] . '</br>';
                                echo '<span class="glyphicon glyphicon-phone"></span> : ';                                
                                echo $data['mobile'] . '</br>';
                                echo '<span class="glyphicon glyphicon-phone-alt"></span> : ';                                    
                                echo $data['telephone'] . '</br>';
                                
                                echo '<span class="glyphicon glyphicon-map-marker"></span> Adresse professionelle :</br>'; 
                                echo $data['adresse1'] . '</br>' . $data['adresse2'] . '</br>';
                                echo $data['cp'] . '  '. $data['ville'];
                      ?>
                </div>
              </div>
              
            </div> <!-- /col -->  
                            
            <div class="col-sm-4 col-md-offset-0">

              <!-- Entreprise -->
              <div class="panel panel-warning">
                <div class="panel-heading">
                  <h3 class="panel-title"><span class="glyphicon glyphicon-briefcase"></span> Entreprise</h3>
                </div>
                <div class="panel-body">
                      <?php
                                echo '<span class="glyphicon glyphicon-tag"></span> : ';                       
                                echo 'SIRET : ' . $data['siret'] . '</br>';
                                echo '<span class="glyphicon glyphicon-globe"></span> : ';                                 
                                echo 'Site Internet : ' . $data['site_internet'] . '</br>';
                                echo '<span class="glyphicon glyphicon-warning-sign"></span> : ';                                 
                                echo 'Régime fiscal : ' . NumToRegimeFiscal($data['regime_fiscal']) . '</br>';
                                
                      ?>
                </div>
              </div>
              
              <!-- Information diverses -->
              <div class="panel panel-info">
                <div class="panel-heading">
                  <h3 class="panel-title"><span class="glyphicon glyphicon-wrench"></span> Configuration options Aboo</h3>
                </div>
                <div class="panel-body">
                      <?php 
                                echo '<span class="glyphicon glyphicon-cog"></span> : '; 
                                echo 'Option Aboo Social : '; 
                                if ($data['gestion_social'] == 0) { echo 'Non'; } else { echo 'Oui'; }
                                echo '</br>';
                      ?>
                </div>
              </div>
              
            </div> <!-- /col -->
        </div>  <!-- /row -->
                
		<!-- Affiche sous condition -->
			<?php 
 			if ($affiche) {
			?>


			<?php
			}
			?>

    <?php require 'footer.php'; ?>
        
    </div> <!-- /container -->
        
  </body>
</html>