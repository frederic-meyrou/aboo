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
    $affiche_erreur=false;  

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

// Initialisation de la base
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   
// POST              
    if ( !empty($_POST) && isset($_POST['action'])) {
        // keep track validation errors
        $mobileError = null;      
        $prenomError = null;
        $nomError = null;        
        $emailError = null;
        $societeError = null;
        $siretError = null;
        $internetError = null;
        $telephoneError = null;
        $adresse1Error = null;
        $adresse2Error = null;
        $cpError = null;
        $villeError = null;
        
        // keep track post values
        $action = $sPOST['action'];

        // Validation sur choix du contexte de formulaire
        $valid = true;               
        switch ($sPOST['action']) {
            case 'compte':
                $mobile = trim($sPOST['mobile']);
                $nom = trim($sPOST['nom']);   
                $prenom = trim($sPOST['prenom']);
                //$email = $sPOST['email'];
                if(empty($sPOST['nom'])){
                    $nomError = 'Veuillez renseigner votre nom';
                    $valid=false;
                }
                if(empty($sPOST['prenom'])){
                    $prenomError = 'Veuillez renseigner votre prénom';
                    $valid=false;
                }
                if(empty($sPOST['mobile'])){
                    $mobileError = 'Veuillez renseigner votre numéro de téléphone portable';
                    $valid=false;
                }       
                //if(!filter_var($sPOST['email'], FILTER_VALIDATE_EMAIL)){
                //    $emailError = 'Votre Email n\'est pas valide !';
                //    $validate=false;
                //}                             
                break;
            case 'coordonnees':
                $mobile = trim($sPOST['mobile']);                
                $telephone = trim($sPOST['telephone']);
                $site_internet = trim($sPOST['internet']);
                $adresse1 = trim($sPOST['adresse1']);
                $adresse2 = trim($sPOST['adresse2']);
                $cp = trim($sPOST['cp']);
                $ville = trim($sPOST['ville']);
                if (!filter_var($site_internet, FILTER_VALIDATE_URL)) {
                    $internetError = 'Le format de votre adresse Internet n\'est pas valide';
                    $valid=false;                  
                }
                break;
            case 'options':
                $option_gestion_social = ($sPOST['social'] == 'Oui')?1:0;
                break;
            case 'entreprise':
                $raison_sociale = trim($sPOST['societe']);
                $siret = trim($sPOST['siret']);
                $siret = preg_replace("/[^\d]+/", '', $siret);                
                $regime_fiscal = $sPOST['fiscal'];
                if (!empty($siret) && !checkLuhn($siret)) { // On vérifie la somme de controle du SIRET
                    $siretError = 'Votre numéro de SIRET est invalide';
                    $valid=false;                   
                }
                break;                
        }      
    
        // insert data
        if ($valid) {
            switch ($sPOST['action']) {
                case 'compte':
                    $sql = "UPDATE user set prenom=?,nom=?,mobile=? WHERE id = ?";
                    $q = array($prenom, $nom, $mobile, $user_id);
                    $_SESSION['authent']['nom'] = $nom;
                    $_SESSION['authent']['prenom'] = $prenom;
                    break;
                case 'coordonnees':
                    $sql = "UPDATE user set mobile=?,telephone=?,site_internet=?,adresse1=?,adresse2=?,cp=?,ville=? WHERE id = ?";
                    $q = array($mobile, $telephone, $site_internet, $adresse1, $adresse2, $cp, $ville, $user_id);                        
                    break;
                case 'options':
                    $sql = "UPDATE user set gestion_social=? WHERE id = ?";
                    $q = array($option_gestion_social, $user_id);                                    
                    break;
                case 'entreprise':
                    $sql = "UPDATE user set raison_sociale=?,siret=?,regime_fiscal=? WHERE id = ?";
                    $q = array($raison_sociale, $siret, $regime_fiscal, $user_id);  
                    break;                
            }            
            $req = $pdo->prepare($sql);
            $req->execute($q);
            Database::disconnect();
            // On retourne d'ou on vient
            header("Location: configuration.php");
        } else {
            $affiche_erreur=true;
        }
    }    
    
// Chargement en BDD des infos de la page
    
    // Lecture des données de la table user
    $sql = "SELECT * FROM user WHERE id = :id";
    $q = array('id' => $user_id);    
    $req = $pdo->prepare($sql);
    $req->execute($q);
    $data = $req->fetch(PDO::FETCH_ASSOC);
    Database::disconnect(); 
?>

<!DOCTYPE html>
<html lang="fr">
<?php require 'head.php'; ?>

<body>

    <?php $page_courante = "configuration.php"; require 'nav.php'; ?>
    
    <div class="container">

        <div class="page-header">           
            <h2>Configuration de mon compte et des informations de mon entreprise</h2>
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

        <!-- Affiche les informations de debug -->
        <?php 
        if ($affiche_erreur) {
        ?>
        <div class="alert alert alert-danger alert-dismissable fade in">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <strong>Formulaire en erreur </strong><br>           
        </div>
        <?php       
        }   
        ?> 

        <div class="row">

            <div class="col-sm-5">
                
            <!-- Mon compte -->                    
              <div class="panel panel-success">
                <div class="panel-heading">
                  <h3 class="panel-title"><span class="glyphicon glyphicon-user"></span> Mon compte Aboo
                  <div class="btn-group btn-group-xs pull-right">
                        <a href="#" class="btn btn-xs btn-primary" onclick="$('#modalConfigFormCompte').modal('show'); "><span class="glyphicon glyphicon-edit"></span> Modifier</a>                         
                  </div>
                  </h3>
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
                                echo 'Inscription sur Aboo : ' . date("d/m/Y", strtotime($data['inscription'])) . '<br>';
                                echo '<span class="glyphicon glyphicon-calendar"></span> : ';                                
                                echo 'Expiration de mon abonnement Aboo : ' . date("d/m/Y", strtotime($data['expiration'])). '<br>';

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

              <!-- Options diverses -->
              <div class="panel panel-warning">
                <div class="panel-heading">
                  <h3 class="panel-title"><span class="glyphicon glyphicon-wrench"></span> Configuration options Aboo
                  <div class="btn-group btn-group-xs pull-right">
                        <a href="#" class="btn btn-xs btn-primary" onclick="$('#modalConfigFormOptions').modal('show'); "><span class="glyphicon glyphicon-edit"></span> Modifier</a>                         
                  </div>
                  </h3>                  
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
                            
            <div class="col-sm-5 col-md-offset-0">

              <!-- Entreprise -->
              <div class="panel panel-info">
                <div class="panel-heading">
                  <h3 class="panel-title"><span class="glyphicon glyphicon-briefcase"></span> Entreprise
                  <div class="btn-group btn-group-xs pull-right">
                        <a href="#" class="btn btn-xs btn-primary " onclick="$('#modalConfigFormEntreprise').modal('show'); "><span class="glyphicon glyphicon-edit"></span> Modifier</a>                         
                  </div>
                  </h3>                
                </div>
                <div class="panel-body">
                      <?php
                                echo '<span class="glyphicon glyphicon-flag"></span> : Raison Sociale : ';                                
                                echo $data['raison_sociale'] . '<br>';
                                
                                echo '<span class="glyphicon glyphicon-tag"></span> : SIRET : ';                       
                                echo $data['siret'] . '<br>';
                                
                                echo '<span class="glyphicon glyphicon-tag"></span> : SIREN : ';                                 
                                echo !empty($data['siret'])?nSIREN($data['siret']):'';
                                echo '<br>';
                                
                                echo '<span class="glyphicon glyphicon-tag"></span> : TVA : ';  
                                echo !empty($data['siret'])?nTVA(nSIREN($data['siret'])):'';
                                echo '<br>';
                                
                                echo '<span class="glyphicon glyphicon-warning-sign"></span> : Régime fiscal : ';                                 
                                echo NumToRegimeFiscal($data['regime_fiscal']) . '<br>';
                                
                      ?>
                </div>
              </div>
              
              <!-- Adresse -->
              <div class="panel panel-info">
                <div class="panel-heading">
                  <h3 class="panel-title"><span class="glyphicon glyphicon-map-marker"></span> Coordonnées pour facturation et eMails
                  <div class="btn-group btn-group-xs pull-right">
                        <a href="#" class="btn btn-xs btn-primary" onclick="$('#modalConfigFormCoordonnees').modal('show'); "><span class="glyphicon glyphicon-edit"></span> Modifier</a>                         
                  </div>
                  </h3>               
                </div>
                <div class="panel-body">
                      <?php 
                                echo '<span class="glyphicon glyphicon-phone"></span> : ';                                
                                echo $data['mobile'] . '</br>';
                                echo '<span class="glyphicon glyphicon-phone-alt"></span> : ';                                    
                                echo $data['telephone'] . '</br>';
                                echo '<span class="glyphicon glyphicon-globe"></span> : ';                                 
                                echo 'Site Internet : ' . $data['site_internet'] . '</br>';                                
                                echo '<span class="glyphicon glyphicon-map-marker"></span> Adresse professionelle :</br>'; 
                                echo $data['adresse1'] . '</br>' . $data['adresse2'] . '</br>';
                                echo $data['cp'] . '  '. $data['ville'];
                      ?>
                </div>
              </div>
              
            </div> <!-- /col -->
        </div>  <!-- /row -->

        <!-- Modal Formulaires -->
        <?php include('modal/config_compte.php'); ?>
        <?php include('modal/config_coordonnees.php'); ?>
        <?php include('modal/config_entreprise.php'); ?>                        
        <?php include('modal/config_options.php'); ?>
                                    

    <?php require 'footer.php'; ?>
        
    </div> <!-- /container -->
        
  </body>
</html>