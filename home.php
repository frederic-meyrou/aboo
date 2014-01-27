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
        header('Location:index.php');
    }

// Mode Debug
	$debug = false;

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

// Récupération des variables de session abodep
    $abodep_mois = null;
    if(isset($_SESSION['abodep'])) {
        $abodep_mois = $_SESSION['abodep']['mois'];
    }

// Initialisation de la base
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function ChargeSessionExerciceBDD($data) {
    // MaJ SESSION
    $_SESSION['exercice'] = array(
    'id' => $data['id'],
    'annee' => $data['annee_debut'],
    'mois' => $data['mois_debut'],
    'treso' => $data['montant_treso_initial']
    );                  
}

// Initialisation des information des session
    if ($exercice_id == null) { // On a pas de session exercice  

        // Lecture dans la base de l'exercice 
        $sql = "SELECT * FROM exercice WHERE user_id = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($user_id));
        $data = $q->fetch(PDO::FETCH_ASSOC);
        $count = $q->rowCount($sql);
        
        if ($count==0) { // Pas d'exercice ds la BDD
            Database::disconnect();
            // Redirection pour creation d'exercice
            header('Location:conf_create.php');                
        }    
    
        // On cherche si on a l'exercice en cours d'utilisation dans la table user 
        $sql = "SELECT * FROM user WHERE id = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($user_id));
        $data = $q->fetch(PDO::FETCH_ASSOC);
        $exercice_id = $data['exerciceid_encours'];        

        if ($exercice_id==NULL) { // Pas d'exercice en cours
            Database::disconnect();
            // Redirection pour creation d'exercice
            header('Location:conf.php');                
        } else {
            $sql = "SELECT * FROM exercice WHERE user_id = ? AND id = ?";
            $q = $pdo->prepare($sql);
            $q->execute(array($user_id, $exercice_id));
            $data = $q->fetch(PDO::FETCH_ASSOC);
            ChargeSessionExerciceBDD($data);
            $exercice_id = $data['id'];
            $exercice_annee = $data['annee_debut'];
            $exercice_mois = $data['mois_debut'];
            $exercice_treso = $data['montant_treso_initial'];                    
        }    
    } // EndIf
    
// Lecture tableau de bord
	$infos = true;			
?>

<!DOCTYPE html>
<html lang="fr">
<?php require 'head.php'; ?>

<body>    

    <?php require 'nav.php'; ?>
        
    <div class="container">
        <h2>Tableau de bord</h2>       
        <br>
       
		<!-- Affiche les informations de session -->      		
		<?php 
 		if ($infos) {
		?>
        <div class="alert alert alert-info alert-dismissable fade in">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <strong><?php echo "Exercice Courant : $exercice_annee démarrant en " . NumToMois($exercice_mois) . ", tréso de $exercice_treso €"; ?></strong><br> 
        </div>

        <div class="alert alert-success alert-dismissable fade in">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>                
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Spécifications : </h3>
              </div>
              <div class="panel-body">
                    <strong>- CA(M) = Recettes(M) : Chiffre d'affaire</strong><br>
                    <strong>- Dépenses(M) = Dépenses</strong><br>
                    <strong>- Solde Brut (M) = CA (M) - Dépenses (M)</strong><br>
                    <strong>- Ventillation (M) = Recettes de l'exercice ventillées sur le mois courant</strong><br>
                    <strong>- Paiement (M) = Total des echéances des abonements étalés sur le mois courant + Recettes à régler</strong><br>
                    <strong>- Paiement echus (M) = Total des paiements à encaisser sur le mois courant</strong><br>
                    <strong>- Encaissement (M) = Total des paiements encaissés + recettes du mois courant payées</strong><br>
                    <strong>- Salaire (M) = Montant affecté au salaire = [Trésorerie (M-1) + Encaissement (M)] - [Ventilation (M) - Dépenses (M)]</strong><br>
                    <strong>- Trésorerie (M) = Montant de la trésorerie = Encaissement (M)  - Dépenses (M)  + Trésorerie (M-1) </strong><br>
                    <strong>- Report Tréso (M) = Montant à mettre de côté</strong><br> 
                    <strong>- Report Salaire (M) = Salaire non versé reporté au mois suivant</strong><br> 
              </div>
            </div>        
        </div>
        <br>        
	    <?php       
        }   
        ?>          
        
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
        </div>
        <?php       
        }   
        ?> 

    </div> <!-- /container -->
    
    <?php require 'footer.php'; ?>
        
  </body>
</html>