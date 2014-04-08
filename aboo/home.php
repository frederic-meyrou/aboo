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

// Récupération des variables de session
	include_once('lib/var_session.php');

// Mode Debug
	$debug = false;

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

function ChargeSessionExerciceBDD($data) {
	// MaJ SESSION
    $_SESSION['exercice'] = array(
    'id' => $data['id'],
    'annee' => $data['annee_debut'],
    'mois' => $data['mois_debut'],
    'treso' => $data['montant_treso_initial'],
    'provision' => $data['montant_provision_charges']
    );	                
}

// Flags
	$affiche_Ok = false;
	$affiche_premiere_visite = false;

// Initialisation des information des session
    if ($exercice_id == null) { // On a pas de session exercice  

        // Recherche en base des exercices de l'utilisateur 
        $sql = "SELECT * FROM exercice WHERE user_id = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($user_id));
        //$data = $q->fetch(PDO::FETCH_ASSOC);
        $count = $q->rowCount($sql);
        
        if ($count==0) { // Pas d'exercice ds la BDD, c'est la première visite.
            Database::disconnect();
			$affiche_premiere_visite = true;
            // Redirection pour creation d'exercice
            //header('Location:exercice_create.php');                
        }  else {  
    
	        // On cherche si on a l'exercice en cours d'utilisation dans la table user 
	        $sql = "SELECT * FROM user WHERE id = ?";
	        $q = $pdo->prepare($sql);
	        $q->execute(array($user_id));
	        $data = $q->fetch(PDO::FETCH_ASSOC);
	        $exercice_id = $data['exerciceid_encours'];        
	
	        if ($exercice_id==NULL) { // Pas d'exercice en cours, il faut en choisir un
	            Database::disconnect();
	            // Redirection pour creation d'exercice
	            header('Location:exercice.php');                
	        } else { // C'est bon on a un exercice en cours
	            $sql = "SELECT * FROM exercice WHERE user_id = ? AND id = ?";
	            $q = $pdo->prepare($sql);
	            $q->execute(array($user_id, $exercice_id));
	            $data = $q->fetch(PDO::FETCH_ASSOC);
	            ChargeSessionExerciceBDD($data);
	            $exercice_id = $data['id'];
	            $exercice_annee = $data['annee_debut'];
	            $exercice_mois = $data['mois_debut'];
	            $exercice_treso = $data['montant_treso_initial'];
    			$exercice_provision = $data['montant_provision_charges'];		            
				$affiche_Ok = true;                    
	        }    
		}
    } else { // On a une session d'exercice active
		$affiche_Ok = true; 
    }             		
?>

<!DOCTYPE html>
<html lang="fr">
<?php require 'head.php'; ?>

<body>    

    <?php require 'nav.php'; ?>
        
    <div class="container">

		<!-- Affiche les informations de première visite -->      		
		<?php 
 		if ($affiche_premiere_visite) {
		?>

        <div class="page-header">          
            <h2>Bienvenue sur Aboo!</h2>
        </div>
		
		<div class="panel panel-success">
		  <div class="panel-heading">
		  	<h3>
			<?php echo "Bienvenue <strong>$prenom</strong>, c'est votre première visite sur Aboo!"; ?>
			</h3> 
			<h5 class="text-info">Ci-dessous votre démarrage sous Aboo en 3 points :</h5>		  		
            
		  </div>
		</div>
		<blockquote class="text-primary">
		  <p>(1) -> Vous devez configurer votre 1er exercice, cet exercice (1 an) doit correspondre à votre activité saisonière.</p>
 		  <em>Sous-menu Exercice : </em><a href="exercice_create.php" target="_blank" class="btn btn-sm btn-info"><span class="glyphicon glyphicon-leaf"></span> Création exercice</a>  
		</blockquote>
		<blockquote class="text-primary">
		  <p>(2) -> Vous devez configurer les informations de votre entreprise et activer les options d'Aboo que vous souhaitez utiliser.</p>
		  <em>Sous-menu Configuration : </em><a href="configuration.php" target="_blank" class="btn btn-sm btn-info"><span class="glyphicon glyphicon-leaf"></span> Configuration</a> 
		</blockquote>
		<blockquote class="text-primary">
		  <p>(3) -> Ensuite vous pouvez commencer à saisir vos Recettes et Dépenses.</p>
		  <em>Menu Journal : </em><a href="recette.php" target="_blank" class="btn btn-sm btn-info"><span class="glyphicon glyphicon-plus-sign"></span> Ajout-Edition de vos Recettes</a><a href="recette.php" target="_blank" class="btn btn-sm btn-info"><span class="glyphicon glyphicon-plus-sign"></span> Ajout-Edition de vos Recettes</a> 		  
		</blockquote>
		 
	    <?php       
        }   
        ?> 

		<!-- Affiche les informations de principales -->      		
		<?php 
 		if ($affiche_Ok) {
		?>

        <div class="page-header">          
            <h2>Tableau de bord Aboo</h2>
        </div>
		
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
		<?php 
 		if ($affiche_Ok) {
		?>
        <center>
        <a href="recette.php" class="btn btn-lg btn-success"><span class="glyphicon glyphicon-plus-sign"></span> Ajout-Edition de vos Recettes</a>
        <a href="depense.php" class="btn btn-lg btn-success"><span class="glyphicon glyphicon-minus-sign"></span> Ajout-Edition de vos Dépenses</a>
        <br><br>
        <a href="journal_annuel.php" class="btn btn-lg btn-primary"><span class="glyphicon glyphicon-list-alt"></span> Journal Annuel</a>                            
        <a href="tableau_de_bord.php" class="btn btn-lg btn-primary"><span class="glyphicon glyphicon-signal"></span> Statistiques</a>                            
        </center>
        <br> <br>         
	    <?php       
        }   
        ?> 

    <?php require 'footer.php'; ?>
        
    </div> <!-- container -->   
     
  </body>
</html>