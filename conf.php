<?php
	require_once('fonctions.php');
	
// Vérification de l'Authent
    session_start();
    require('authent.php');
    if( !Authent::islogged()){
        // Non authentifié on repart sur la HP
        header('Location:index.php');
    }
// Récupération des variables de session d'Authent
    $user_id = $_SESSION['authent']['id']; 
    $nom = $_SESSION['authent']['nom'];
    $prenom = $_SESSION['authent']['prenom'];
    $nom = $_SESSION['authent']['nom'];

// Récupération des variables de session exercice
    $exercice_annee = null;
    $exercice_mois = null;
    $exercice_treso = null;
    if(isset($_SESSION['exercice'])) {
        $exercice_annee = $_SESSION['exercice']['annee'];
        $exercice_mois = $_SESSION['exercice']['mois'];
        $exercice_treso = $_SESSION['exercice']['treso'];
    }

// Initialisation de la base
    include_once 'database.php';
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
// Lecture du POST (Choix de l'exercice)
    $annee_error = null; 
    if (isset($_POST['annee']) ) { // J'ai un POST
            $annee_exercice_choisie = $_POST['annee'];
    } else { // Je n'ai pas de POST
            $annee_exercice_choisie = null;
    }

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
    } elseif (!empty($annee_exercice_choisie)) { // L'année est choisie
        // On va vérifier que l'année est dans la base et remplir la session, sauf si l'annee session est l'annee choisie
        if ($exercice_annee != $annee_exercice_choisie) {
            $sql = "SELECT * FROM exercice WHERE user_id = ? AND annee_debut = ?";
            $q = $pdo->prepare($sql);
            $q->execute(array($user_id, $annee_exercice_choisie));
            $data = $q->fetch(PDO::FETCH_ASSOC);
            $count = $q->rowCount($sql);
            if ($count==1) { // C'est bon on a trouvé l'année dans la base on charge la session
                $_SESSION['exercice'] = array(
                'annee' => $data['annee_debut'],
                'mois' => $data['mois_debut'],
                'treso' => $data['montant_treso_initial']
                );
    			$exercice_annee = $data['annee_debut'];
    			$exercice_mois = $data['mois_debut'];
    			$exercice_treso = $data['montant_treso_initial'];   			
                // Mise à jour de la liste du formulaire
                $liste_annee[0] = $data['annee_debut'];
           }
        } else {
            $liste_annee[0] = $exercice_annee;
        }
        // On affiche le formulaire et l'exercice en cours
        $affiche = true;
        $infos = true;         
    } else { // L'année n'est pas choisie, on liste l'ensemble des années disponible ds la BDD pour afficher le formulaire
        $sql = "SELECT annee_debut FROM exercice WHERE user_id = ?";
		$q = $pdo->prepare($sql);
        $q->execute(array($user_id));
        $data = $q->fetch(PDO::FETCH_ASSOC);
        $count = $q->rowCount($sql);
        if ($count==1) {
        		$liste_annee[0] = $data['annee_debut'];
				$affiche = true;
        } else {
        	$sql = "SELECT annee_debut FROM exercice WHERE user_id = $user_id";
		    $n = 0;
		    foreach ($pdo->query($sql) as $row) {
		    	if (date("Y") == $row['annee_debut']) {
		    		// L'année courante est dans la BDD
		    		$current_year=true;
		    	}
		        $liste_annee[$n] = $row['annee_debut'];
		        $n++;
	        }
		    $affiche = false;
		}
		$infos = false;  
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>GestAbo</title>
    <meta charset="utf-8">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" media="screen">
</head>

<body>

    <script src="bootstrap/js/jquery-2.0.3.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <div class="container">
        <h2>Console</h2>
        <ul class="nav nav-pills">
          <li><a href="home.php">Console</a></li>
          <li><a href="abodep.php">Editer abonnements et dépenses</a></li>
          <li><a href="meusuel.php">Bilan Mensuel</a></li>
          <li><a href="bilan.php">Bilan Annuel</a></li>
          <li><a href="encaissements.php">Encaissements</a></li>
          <li><a href="paiements.php">Paiements</a></li>
          <li class="active"><a href="conf.php">Configuration</a></li>
          <li><a href="deconnexion.php">Deconnexion</a></li>
        </ul>
        
        <form class="form-vertical" action="conf.php" method="post">      
            <select name="annee" class="form-control">
            <?php
                foreach ($liste_annee as $a) {
            ?>
                <option><?php echo "$a - " . ($a + 1);?></option>    
            <?php       
                }   
            ?>    
            </select>
            <div class="error"><?php if(isset($error_annee)){ echo $error_annee; } ?></div>
            <button type="submit" class="btn btn-success">Changer d'année</button>
			<a class="btn btn-success" href="conf_create.php">Créer un nouvel exercice</a>
        </form>

        <div class="alert alert alert-warning alert-dismissable fade in">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <strong><?php echo "Exercice $exercice_annee démarrant en " . NumToMois($exercice_mois) . ", tréso de $exercice_treso €"; ?> !</strong> 
        </div>
		
		<div class="span10">
			<?php 
 			if ($affiche) {
			?>
            <div class="row">
                <h3>Liste des exercices</h3>
            </div>			
			<table class="table table-striped table-bordered table-hover success">
				<thead>
					<tr>
					
					  <th>Années exercice</th>
					  <th>Mois de démarrage</th>
					  <th>Montant de trésorerie de départ</th>
					  <th>Action</th>
					  
					</tr>
				</thead>
                
				<tbody>
			<?php 
 			 
				$sql = "SELECT * FROM exercice WHERE user_id = $user_id ORDER by annee_debut";
				foreach ($pdo->query($sql) as $row) {
						echo '<tr>';
						echo '<td>'. $row['annee_debut'] . ' - ' . ($row['annee_debut'] + 1) . '</td>';
						echo '<td>'. NumToMois($row['mois_debut']) . '</td>';
						echo '<td>'. $row['montant_treso_initial'] . '</td>';
						echo '<td width=auto>';
						//echo '<a class="btn " href="conf_read.php?annee='.$row['annee_debut'].'">Lire</a>';
						//echo '&nbsp;';                                
						echo '<a class="btn btn-success" href="conf_update.php?id='.$row['id'].'">Modifier</a>';
						echo '&nbsp;';
						echo '<a class="btn btn-danger" href="conf_delete.php?id='.$row['id'].'">Supprimer</a>';
						echo '</td>';
						echo '</tr>';
				}
			}
			Database::disconnect();
			?>
                </tbody>
            </table>
            
			</div> 	<!-- /row -->
        </div>  <!-- /span -->        			
    
    </div> <!-- /container -->
  </body>
</html>