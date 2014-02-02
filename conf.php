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

// Initialisation de la base
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
// Lecture du POST (Choix de l'exercice)
	if (! isset($liste_annee)) {
		$liste_annee = array();
	} 
    if (isset($sPOST['annee']) ) { // J'ai un POST
            $annee_exercice_choisie = $sPOST['annee'];
    } else { // Je n'ai pas de POST
            $annee_exercice_choisie = null;
    }

function MajListeAnnee() {
	global $user_id;
	global $liste_annee;
	global $pdo;
	
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
}

function ChargeSessionExerciceBDD($data) {
	// MaJ SESSION
    $_SESSION['exercice'] = array(
    'id' => $data['id'],
    'annee' => $data['annee_debut'],
    'mois' => $data['mois_debut'],
    'treso' => $data['montant_treso_initial']
    );					
}

// Lecture dans la base de l'exercice 
    $sql = "SELECT * FROM exercice WHERE user_id = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($user_id));
    $data = $q->fetch(PDO::FETCH_ASSOC);
    $count = $q->rowCount($sql);
    if ($count==0) { // Pas d'exercice ds la BDD c'est le premier passage sur le formulaire
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
                ChargeSessionExerciceBDD($data);
				$exercice_id = $data['id'];
    			$exercice_annee = $data['annee_debut'];
    			$exercice_mois = $data['mois_debut'];
    			$exercice_treso = $data['montant_treso_initial'];   			
                // Mise à jour de la liste du formulaire
                MajListeAnnee();
           }
        } else { // On a conservé la même année que la session
            MajListeAnnee();
        }
		// On met à jour la BDD pour les champs encours
        $sql = "UPDATE user SET exerciceid_encours=? WHERE id = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($exercice_id, $user_id));	
        // On affiche le formulaire et l'exercice en cours
        $affiche = true;
        $infos = true;         
	} else { // L'année n'est pas choisie et on a pas de session, on liste l'ensemble des années disponible ds la BDD pour afficher le formulaire
        $sql = "SELECT * FROM exercice WHERE user_id = ?"; 
		$q = $pdo->prepare($sql);
        $q->execute(array($user_id));
        $data = $q->fetch(PDO::FETCH_ASSOC);
        $count = $q->rowCount($sql);
        if ($count==1) { // On est ds le cas ou on a juste une valeure trouvée en base 
        		$liste_annee[0] = $data['annee_debut'];
                // MaJ SESSION
                ChargeSessionExerciceBDD($data);
				$exercice_id = $data['id'];				
    			$exercice_annee = $data['annee_debut'];
    			$exercice_mois = $data['mois_debut'];
    			$exercice_treso = $data['montant_treso_initial'];   								
				$affiche = true;
				$infos = true;
        } else { // On est ds le cas ou on a une liste de valeure en base
        	if ($exercice_annee != null) { // Ds le cas ou on a une session en cours
        		$sql = "SELECT * FROM exercice WHERE user_id = ? AND annee_debut = ?"; 
				$q = $pdo->prepare($sql);
	        	$q->execute(array($user_id, $exercice_annee));
        	} else {
        		$sql = "SELECT * FROM exercice WHERE user_id = ? AND annee_debut = YEAR(NOW())"; 
				$q = $pdo->prepare($sql);
	        	$q->execute(array($user_id));
        	}
			MajListeAnnee();
        	$data = $q->fetch(PDO::FETCH_ASSOC);
			$count = $q->rowCount($sql);
			if ($count==1) {
				// MaJ SESSION
                ChargeSessionExerciceBDD($data);
				$exercice_id = $data['id'];				
    			$exercice_annee = $data['annee_debut'];
    			$exercice_mois = $data['mois_debut'];
    			$exercice_treso = $data['montant_treso_initial'];   
				$infos = true;					
			} else {
				$infos = false;
			}	 			
		    $affiche = true;
		} 
    }
?>

<!DOCTYPE html>
<html lang="fr">
<?php require 'head.php'; ?>

<body>

    <?php $page_courante = "conf.php"; require 'nav.php'; ?>
    
    <div class="container">

        <div class="page-header">           
            <h2>Configuration de l'exercice</h2>
        </div>
        
        <!-- Affiche le dropdown formulaire année avec selection automatique de l'année en cours de la session -->
        <form class="form-inline" role="form" action="conf.php" method="post">      
            <select name="annee" class="form-control">
            <?php
                foreach ($liste_annee as $a) {
            ?>
                <option value="<?php echo "$a";?>"<?php echo ($a==$exercice_annee)?'selected':'';?>><?php echo "$a - " . ($a + 1);?></option>    
            <?php       
                } // foreach   
            ?>    
            </select>
            <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-refresh"></span> Changer d'année</button>
			<a class="btn btn-primary" href="conf_create.php"><span class="glyphicon glyphicon-plus-sign"></span> Créer un nouvel exercice</a>
        </form>
        <br>
         
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
        
		<!-- Affiche les informations de session -->      		
		<?php 
 		if ($infos) {
		?>
        <div class="alert alert alert-info alert-dismissable fade in">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <strong><?php echo "Exercice Courant : $exercice_annee démarrant en " . NumToMois($exercice_mois) . ", tréso de $exercice_treso €"; ?> !</strong> 
        </div>
	    <?php       
        }   
        ?>  
        
		<!-- Affiche la table des exercices en base sous condition -->
			<?php 
 			if ($affiche) {
                // Insère l'aide en ligne pour les actions
                $IDModale = "modalAideActions";
                include('lib/aide.php'); 			    
			?>
            <h3>Liste des exercices</h3>
            			
	        <div class="table-responsive">  
			<table class="table table-striped table-bordered table-hover success">
				<thead>
					<tr>
					
					  <th>Années exercice</th>
					  <th>Mois de démarrage</th>
					  <th>Montant de trésorerie de départ</th>
					  <th>Action <a href="#" onclick="$('#modalAideActions').modal('show'); "><span class="glyphicon glyphicon-info-sign"></span></a></th>
					  
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
						echo '<td width=90>';
			?>
						<div class="btn-group btn-group-sm">
							  	<a href="conf_update.php?id=<?php echo $row['id']; ?>" class="btn btn-default btn-sm btn-warning glyphicon glyphicon-edit" role="button"> </a>
							  	<!-- Le bonton Delete active la modal et modifie le champ value à la volée pour passer l'ID a supprimer en POST -->
							  	<a href="#" id="<?php echo $row['id']; ?>"
							  	   onclick="$('#DeleteInput').val('<?php echo $row['id']; ?>'); $('#AnneeInput').val('<?php echo $row['annee_debut']; ?>'); $('<?php echo ($row['annee_debut']==$exercice_annee) ? '#modalImpossible' : '#modalDelete'; ?>').modal('show'); "
							  	   class="btn btn-default btn-sm btn-danger glyphicon glyphicon-trash" role="button"> </a>
						</div>						                                
			<?php
						echo '</td>';
						echo '</tr>';
				}
			}
			Database::disconnect();
			?>
                </tbody>
            </table>
            </div> <!-- /table-responsive -->            
				
            <!-- Modal Delete -->
            <div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-labelledby="DeleteModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                    <form class="form-horizontal" action="conf_delete.php" method="post">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h3 class="modal-title" id="DeleteModalLabel">Suppression d'une exercice :</h3>
                      </div><!-- /.modal-header -->
                      <div class="modal-body">
                          <center><strong>
                           <p class="alert alert-danger">Confirmez-vous la suppression ?</p>
                           <p class="alert alert-warning">Attention cette action supprimera aussi tous recette et dépenses associées.</p>
                           <input id="DeleteInput" type="hidden" name="id" value=""/>
                           <input id="AnneeInput" type="hidden" name="annee" value=""/>
                          </strong></center>
                      </div><!-- /.modal-body -->                                         
                      <div class="modal-footer">
                        <div class="form-actions">                              
                            <button type="submit" class="btn btn-danger pull-right"><span class="glyphicon glyphicon-trash"></span> Suppression</button>
                            <button type="button" class="btn btn-primary pull-left" data-dismiss="modal"><span class="glyphicon glyphicon-eject"></span> Retour</button>                                  
                        </div>
                      </div><!-- /.modal-footer -->
                    </form>                   
                </div><!-- /.modal-content -->
              </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->	
            <!-- Modal Impossible -->
            <div class="modal fade" id="modalImpossible" tabindex="-1" role="dialog" aria-labelledby="ImpossibleModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h3 class="modal-title" id="ImpossibleModalLabel">Suppression d'une exercice :</h3>
                      </div><!-- /.modal-header -->
                      <div class="modal-body">
                          <center><strong>
                           <p class="alert alert-warning">Impossible de supprimer l'exercice courant.</p>
                          </strong></center>
                      </div><!-- /.modal-body -->                                         
                      <div class="modal-footer">                       
                            <button type="button" class="btn btn-primary pull-right" data-dismiss="modal"><span class="glyphicon glyphicon-eject"></span> Retour</button>                                  
                      </div><!-- /.modal-footer -->                 
                </div><!-- /.modal-content -->
              </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->	
    
    </div> <!-- /container -->

    <?php require 'footer.php'; ?>
        
  </body>
</html>