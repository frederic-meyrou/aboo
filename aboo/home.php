<!-- 
© Copyright : Aboo / www.aboo.fr : Frédéric MEYROU : tous droits réservés
-->
<?php
// Dépendances
	require_once('lib/fonctions.php');
    include_once('lib/database.php');
    include_once('lib/calcul_bilan.php');  
    	
// Vérification de l'Authent
    session_start();
    require('lib/authent.php');
    if( !Authent::islogged()){
        // Non authentifié on repart sur la HP
        header('Location:../index.php');
    }

// Mode Debug
	$debug = false;

// Sécurisation POST & GET
    foreach ($_GET as $key => $value) {
        $sGET[$key]=htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
    foreach ($_POST as $key => $value) {
        $sPOST[$key]=htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
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
            header('Location:exercice_create.php');                
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
            header('Location:exercice.php');                
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
        <div class="page-header">          
            <h2>Bienvenue sur Aboo!</h2>
        </div>
               
		<!-- Affiche les informations de session -->      		
		<?php 
 		if ($infos) {
		?>
		<div class="panel panel-info">
		  <div class="panel-heading">
		  	<h4>
            <?php echo "Exercice Courant : <strong>$exercice_annee</strong> démarrant en <strong>" . NumToMois($exercice_mois) . "</strong>, trésorerie de départ de <strong>$exercice_treso €</strong>."; ?>
            <br><br>
            <?php echo "Mois de travail en cours : <strong> " . NumToMois($abodep_mois) . "</strong>."; ?>
            </h4>             
		  </div>
		</div> 
	    <?php       
        }   
        ?>      
        <!-- Affiche les boutons principaux -->
        <center>
        <a href="recette.php" class="btn btn-lg btn-success"><span class="glyphicon glyphicon-plus-sign"></span> Ajout-Edition de vos Recettes</a>
        <a href="depense.php" class="btn btn-lg btn-success"><span class="glyphicon glyphicon-minus-sign"></span> Ajout-Edition de vos Dépenses</a>
        <br><br>
        <a href="journal_annuel.php" class="btn btn-lg btn-primary"><span class="glyphicon glyphicon-list-alt"></span> Journal Annuel</a>                            
        <a href="tableau_de_bord.php" class="btn btn-lg btn-primary"><span class="glyphicon glyphicon-signal"></span> Tableau de bord</a>                            
        </center>
        <br> <br>         
    
    </div>
    
    <?php require 'footer.php'; ?>
     
  </body>
</html>