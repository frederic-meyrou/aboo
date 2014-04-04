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
	
// Mode Debug
	$debug = false;
    $affiche_erreur=false;  

// Sécurisation POST & GET
    foreach ($_GET as $key => $value) {
        $sGET[$key]=htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
    foreach ($_POST as $key => $value) {
        $sPOST[$key]=htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
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
		$socialError = null;
        
        // keep track post values
        $action = $sPOST['action'];

		// Lecture des données de la table user pour traitement formulaire
	    $sql = "SELECT * FROM user WHERE id = :id";
	    $q = array('id' => $user_id);    
	    $req = $pdo->prepare($sql);
	    $req->execute($q);
	    $data = $req->fetch(PDO::FETCH_ASSOC);			

        // Validation sur choix du contexte de formulaire
        $valid = true;               
        switch ($sPOST['action']) {
            case 'compte':
                $mobile = preg_replace("/[^\d]+/", '', trim($sPOST['mobile'])); 
                $nom = ucfirst(mb_strtolower($sPOST['nom'], 'UTF-8'));   
                $prenom = ucfirst(trim(mb_strtolower($sPOST['prenom'], 'UTF-8')));
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
                if(!empty($mobile) && !preg_match('/^[0-9]{10,14}$/', $mobile)) {
                    $telephoneError = 'Le numéro de téléphone mobile n\'est pas valide.';
                    $valid=false;
                }                                                    
                break;
            case 'email':
                $email = trim($sPOST['email']);
				$token = sha1(uniqid(rand())); // Generation du token
                if(!filter_var($sPOST['email'], FILTER_VALIDATE_EMAIL)){
                    $emailError = 'Votre Email n\'est pas valide !';
                    $valid=false;
                }
			    $q = array('email'=>$email);
			    $sql = "SELECT * from user WHERE email=:email";
			    $req = $pdo->prepare($sql);
			    $req->execute($q);		
			    $count = $req->rowCount($sql);
				if ($count!=0) { // Le user existe déjà
			        $emailError = 'Votre adresse eMail existe déjà dans Aboo!';
					$valid=false;     
				}								                             
                break;
            case 'motdepasse':
				$password = $sPOST['password'];
				$password2 = $sPOST['password2'];
				if ($password != $password2) {
                    $motdepasseError = 'Votre mot de passe n\'est pas identique!';
                    $valid=false;					
				}                
				if (empty($password) || strlen($password) < 6) {
                    $motdepasseError = 'Votre mot de passe ne peut être inférieur à 6 caractères';
                    $valid=false;						
				}
                break;									
            case 'coordonnees':
                $mobile = (!isset($_POST['mobile']))?preg_replace("/[^\d]+/", '', trim($sPOST['mobile'])):null;                
                $telephone = (!isset($_POST['telephone']))?preg_replace("/[^\d]+/", '', trim($sPOST['telephone'])):null;
                $site_internet = (!isset($_POST['internet']))?trim($sPOST['internet']):null;
                $adresse1 = (!isset($_POST['adresse1']))?trim($sPOST['adresse1']):null;
                $adresse2 = (!isset($_POST['adresse2']))?trim($sPOST['adresse2']):null;
                $cp = (!isset($_POST['cp']))?trim($sPOST['cp']):null;
                $ville = (!isset($_POST['ville']))?ucfirst(strtolower(trim($sPOST['ville']))):null;
                if(!empty($mobile) && !preg_match('/^[0-9]{10,14}$/', $mobile)) {
                    $telephoneError = 'Le numéro de téléphone mobile n\'est pas valide.';
                    $valid=false;
                }                  
                if(!empty($telephone) && !preg_match('/^[0-9]{10,14}$/', $telephone)) {
                    $telephoneError = 'Le numéro de téléphone fixe n\'est pas valide.';
                    $valid=false;
                }  
                if (!empty($site_internet) && !filter_var($site_internet, FILTER_VALIDATE_URL)) {
                    $internetError = 'Le format de votre adresse Internet n\'est pas valide';
                    $valid=false;                  
                }
                if (!empty($cp) && !preg_match('/^[0-9]{5}$/', $cp)) {
                    $cpError = 'Le format du code postal n\'est pas valide';
                    $valid=false;                      
                }
                break;
            case 'options':
                $option_gestion_social = ($sPOST['social'] == 'Oui')?1:0;
				if ($option_gestion_social && $data['regime_fiscal'] == 0) {
                    $socialError = 'Il n\'est pas possible d\'activer cette option tant que vous n\'avez pas choisis de statut fiscal pour votre entreprise.';
                    $valid=false;					
				}
				if ($option_gestion_social && $data['regime_fiscal'] == 5) {
                    $socialError = 'L\'option de gestion des charges sociales pour une association n\'est pas encore disponible sur Aboo.';
                    $valid=false;					
				}				
                break;
            case 'entreprise':
                $raison_sociale = trim($sPOST['societe']);
                $siret = trim($sPOST['siret']);
                $siret = preg_replace("/[^\d]+/", '', $siret);                
                $regime_fiscal = $sPOST['fiscal'];
				$option_gestion_social = $data['regime_fiscal'];
                if (!empty($siret) && !checkLuhn($siret)) { // On vérifie la somme de controle du SIRET
                    $siretError = 'Votre numéro de SIRET est invalide';
                    $valid=false;                   
                }
				if ($data['gestion_social'] == 1 && $raison_sociale == 0) { // Option sociale incompatible avec ce regime fiscal
					$option_gestion_social = 0;
				}
				if ($data['gestion_social'] == 1 && $raison_sociale == 5) { // Option sociale incompatible avec ce regime fiscal
					$option_gestion_social = 0;					
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
                case 'email':
                    $sql = "UPDATE user set token=? WHERE id = ?"; // On ne met à jour que le token en BDD pour validation utltérieur par line eMail dans la BAL de l'utilisatateur
                    $q = array($token, $user_id);
				    //Envoyer un mail pour la validation du compte
				    $TO = $email;
				    $SUBJECT = 'Modification de votre eMail';
				    
				   	$EOL="\r\n";
					$FROM="contact@aboo.fr";
					$BCC="frederic@meyrou.com";
					
					//Création du Header eMail
					$header= "From: [Aboo] <$FROM>".$EOL;
					$header.= "Reply-To: <$FROM>".$EOL;
					$header.= "Bcc: <$BCC>".$EOL;
					$header.= "Return-path:<$FROM>".$EOL;		
					$header.= "MIME-Version: 1.0".$EOL;
					$header.= "Content-Type: text/html; charset=utf-8".$EOL;
					//$header.= "Content-Transfer-Encoding: quoted-printable".$EOL;
					$header.= "Subject: {$SUBJECT}".$EOL;
					$header.= "X-Mailer: PHP/".phpversion().$EOL;
					$header.= "X-Originating-IP: ".$_SERVER['SERVER_ADDR'];
			
				    $body='
			        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
			        <html xmlns="http://www.w3.org/1999/xhtml">
			        <head>
			            <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
			            <title>[Aboo] Confirmation de la modification de votre adresse eMail principale</title>
			            <style type="text/css">
			                body, table, p, h1,h2,h3,h4,h5 {font-family:Verdana, Arial, Helvetica, sans-serif;}
			                body, table, p {font-size:14px;}
			                #outlook a {padding:0;} /* Force Outlook to provide a "view in browser" menu link. */
			                body{width:100% !important; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; margin:0; padding:0;}
			                .ExternalClass {width:100%;} /* Force Hotmail to display emails at full width */
			                .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;} 
			                #backgroundTable {margin:0; padding:0; width:100% !important; line-height: 100% !important;}
			                img {outline:none; text-decoration:none; -ms-interpolation-mode: bicubic;}
			                a img {border:none;}
			                .image_fix {display:block;}
			                p {margin: 1em 0;}
			                h1, h2, h3, h4, h5, h6 {color: black !important;}
			                h1 a, h2 a, h3 a, h4 a, h5 a, h6 a {color: blue !important;}
			                h1 a:active, h2 a:active,  h3 a:active, h4 a:active, h5 a:active, h6 a:active {
			                    color: red !important; 
			                }
			                h1 a:visited, h2 a:visited,  h3 a:visited, h4 a:visited, h5 a:visited, h6 a:visited {
			                    color: purple !important;
			                }
			                table td {border-collapse: collapse;}
			                table { border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; }
			                a {color: orange; text-decoration: none;}
			        
			                a:link { color: orange; }
			                a:visited { color: blue; }
			                a:hover { color: gray; }
			
			            </style>
			        </head>
			        <body>
			            <table cellpadding="0" cellspacing="0" border="0" id="backgroundTable">
			            <tr>
			                <td>
			                    <h3>Bonjour!</h3>
			                    <p><em>Nous avons bien reçu votre demande de modification pour l\'adresse de messagerie : '.$email.'</em></p><br>
			                    <table><tbody>
			                    <td>Veuillez valider cette denande : </td><td><a target="_blank" href="'. MyBaseURL() . '/modif_email.php?uid='.$user_id.'&token='.$token.'&email='.$TO.'" style="font-family:lucida grande, tahoma, verdana, arial, sans-serif;
			                    line-height:2em; 
			                    color:orange; 
			                    text-decoration:none; 
			                    font-size:13px; 
			                    -moz-text-shadow:0px 1px #ffffff; 
			                    -webkit-text-shadow:0px 1px #ffffff; 
			                    text-shadow:0px 1px #ffffff; 
			                    display:block; 
			                    padding:1px 6px 3px 6px; 
			                    border-top:1px solid #ffffff; 
			                    white-space:nowrap; 
			                    -webkit-border-radius:2px; 
			                    -moz-border-radius:2px; 
			                    border-radius:2px;
			                    background-color: #E9E9E9;
			                    border: 1px solid #CCCCCC;
			                    border-radius: 2px;">Validation</a></td></tbody></table>
			                    <br>
			                    <em>L\'équipe Aboo vous remerçie.</em>
			                 </td>
			            </tr>
			            </table>
			        </body>
			        </html>
				    '.$EOL;
					// Envoi eMail
				    $statut = mail($TO,$SUBJECT,$body,$header);
					// On affiche un retour à l'utilisateur
					if ($statut) {
						$envoi_email=true;
					} else {
						$envoi_email=false;	
					} 				
                    break;	
                case 'motdepasse':
                    $sql = "UPDATE user set password=? WHERE id = ?";
                    $q = array($password, $user_id);
					$_SESSION['authent']['password'] = $password;
                    break;									
                case 'coordonnees':
                    $sql = "UPDATE user set mobile=?,telephone=?,site_internet=?,adresse1=?,adresse2=?,cp=?,ville=? WHERE id = ?";
                    $q = array($mobile, $telephone, $site_internet, $adresse1, $adresse2, $cp, $ville, $user_id);                        
                    break;
                case 'options':
                    $sql = "UPDATE user set gestion_social=? WHERE id = ?";
                    $q = array($option_gestion_social, $user_id);
                    $_SESSION['options']['gestion_social'] = $option_gestion_social;                                             
                    break;
                case 'entreprise':
                    $sql = "UPDATE user set raison_sociale=?, gestion_social=?, siret=?,regime_fiscal=? WHERE id = ?";
                    $q = array($raison_sociale, $option_gestion_social, $siret, $regime_fiscal, $user_id);
					$_SESSION['options']['regime_fiscal'] = $regime_fiscal;
                    break;
				default:
		            // On retourne d'ou on vient car le POST est invalide
		            header("Location: configuration.php");					                
            }            
            $req = $pdo->prepare($sql);
            $req->execute($q);
            Database::disconnect();
            // On retourne d'ou on vient
            header("Location: configuration.php");
        } else { // Re-affiche le formulaire de saisie en erreur
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

	    <?php       
	    if (isset($envoi_email) && $envoi_email) {  
	    ?>  
	    <!-- Alert informant que l'utilisateur est bien créé -->
	    <div class="alert alert alert-success alert-dismissable fade in">
	        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	        <strong>
	        <p>Le compte utilisateur pour "<?php echo $email ; ?>" à bien été créé, il est en attente de validation.</p>
	        <p>Un eMail vous a été envoyé pour le valider, veuillez surveiller votre messagerie électronique.</p>
	        </strong>	
	
	    </div>
	    <?php
		}       
	    if (isset($envoi_email) && !$envoi_email) {
	    ?>  
	    <!-- Alert informant que l'utilisateur créé mais que le message n'est pas parti -->
	    <div class="alert alert alert-warning alert-dismissable fade in">
	        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	        <strong>
	        <p>Le compte utilisateur pour "<?php echo $email ; ?>" à bien été créé, il est en attente de validation.</p>
	        <p>Cependant un eMail n'a pu vous être envoyé pour le valider, veuillez contacter le support Aboo : <a href="mailto:contact@aboo.fr">contact@aboo.fr</a></p>
	        </strong>	
	
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
                                echo '<span class="glyphicon glyphicon-user"></span> : <strong>';
                                echo $data['prenom'] . ' ' . $data['nom'] . '</strong><br>';
                                
                                echo '<span class="glyphicon glyphicon-envelope"></span> : <strong>';
                                echo $data['email'] . '</strong><br>';

                                echo '<span class="glyphicon glyphicon-phone"></span> : <strong>';                                
                                echo $data['mobile'] . '</strong><br>'; 
                                
                                echo '<span class="glyphicon glyphicon-calendar"></span> : ';                                                                                                 
                                echo 'Inscription sur Aboo : <strong>' . date("d/m/Y", strtotime($data['inscription'])) . '</strong><br>';
                                echo '<span class="glyphicon glyphicon-calendar"></span> : ';                                
                                echo 'Expiration de mon abonnement Aboo : <strong>' . date("d/m/Y", strtotime($data['expiration'])). '</strong><br>';

                                echo '<span class="glyphicon glyphicon-copyright-mark"></span> : ';
                                echo 'Abonnement Aboo : <strong>';
                                if ($data['essai'] == 1) {
                                    echo 'License d\'essai';
                                } elseif ($data['actif'] == 1) {
                                    echo 'License en cours de validité';
                                } else {
                                    echo 'Inconnu, contacter le support Aboo SVP';
                                }
                                echo '</strong><br>'                       
                      ?>
                  <hr>    
                  <div class="btn-group btn-group-sm btn-group-justified">
                        <a href="#" class="btn btn-xs btn-default" onclick="$('#modalConfigFormeMail').modal('show'); "><span class="glyphicon glyphicon-edit"></span> Changer votre eMail</a>                         
                        <a href="#" class="btn btn-xs btn-default" onclick="$('#modalConfigFormMotdepasse').modal('show'); "><span class="glyphicon glyphicon-edit"></span> Changer votre mot de passe</a>                         
                  </div>                      
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
                                echo 'Option Aboo Social : <strong>'; 
                                if ($data['gestion_social'] == 0) { echo 'Non'; } else { echo 'Oui'; }
                                echo '</strong></br>';
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
                                echo '<span class="glyphicon glyphicon-flag"></span> : Raison Sociale : <strong>';                                
                                echo $data['raison_sociale'] . '</strong><br>';
                                
                                echo '<span class="glyphicon glyphicon-tag"></span> : SIRET : <strong>';                       
                                echo $data['siret'] . '</strong><br>';
                                
                                echo '<span class="glyphicon glyphicon-tag"></span> : SIREN : <strong>';                                 
                                echo !empty($data['siret'])?nSIREN($data['siret']):'';
                                echo '</strong><br>';
                                
                                echo '<span class="glyphicon glyphicon-tag"></span> : TVA : <strong>';  
                                echo !empty($data['siret'])?nTVA(nSIREN($data['siret'])):'';
                                echo '</strong><br>';
                                
                                echo '<span class="glyphicon glyphicon-warning-sign"></span> : Régime fiscal : <strong>';                                 
                                echo NumToRegimeFiscal($data['regime_fiscal']) . '</strong><br>';
                                
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
                                echo 'Site Internet : <strong>' . $data['site_internet'] . '</strong></br>';                                
                                echo '<span class="glyphicon glyphicon-map-marker"></span> Adresse professionelle :</br>';
                                echo '<div class="well well-sm">'; 
                                echo $data['adresse1'] . '</br>' . $data['adresse2'] . '</br>';
                                echo $data['cp'] . '  '. $data['ville'];
                                echo '</div>';
                      ?>
                </div>
              </div>
              
            </div> <!-- /col -->
        </div>  <!-- /row -->

        <!-- Modal Formulaires -->
        <?php include('modal/config_compte.php'); ?>
        <?php include('modal/config_motdepasse.php'); ?>
        <?php include('modal/config_email.php'); ?>                
        <?php include('modal/config_coordonnees.php'); ?>
        <?php include('modal/config_entreprise.php'); ?>                        
        <?php include('modal/config_options.php'); ?>
                                    

    <?php require 'footer.php'; ?>
        
    </div> <!-- /container -->

    <?php 
    if ($affiche_erreur) { // Lancement de Modal pour activer les formulaires en erreur conditionné par PHP
    ?>  
        <script>
            $(document).ready(function(){ // Le DOM est chargé
            <?php
            switch ($sPOST['action']) {
                case 'compte':
                    echo '$(\'#modalConfigFormCompte\').modal(\'show\'); // Lance la modale';
                    break;
                case 'email':
                    echo '$(\'#modalConfigFormMail\').modal(\'show\'); // Lance la modale';
                    break;
                case 'motdepasse':
                    echo '$(\'#modalConfigFormMotdepasse\').modal(\'show\'); // Lance la modale';
                    break;										
                case 'coordonnees':
                    echo '$(\'#modalConfigFormCoordonnees\').modal(\'show\'); // Lance la modale';                   
                    break;
                case 'options':
                    echo '$(\'#modalConfigFormOptions\').modal(\'show\'); // Lance la modale';                                 
                    break;
                case 'entreprise':
                    echo '$(\'#modalConfigFormEntreprise\').modal(\'show\'); // Lance la modale';
                    break;                
            }                 
            ?>      
            });
        </script>
    <?php                                       
    } // endif
    ?>  
        
  </body>
</html>