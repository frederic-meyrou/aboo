<!-- 
© Copyright : Aboo / www.aboo.fr : Frédéric MEYROU : tous droits réservés
-->
<?php
// Vérification de l'Authent
    session_start();
    require('authent.php');
    if( !Authent::islogged()){
        // Non authentifié on repart sur la HP
        header('Location:index.php');
    }

// Dépendances
	require_once('fonctions.php');

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
    if(isset($_SESSION['exercice'])) {
        $exercice_id = $_SESSION['exercice']['id'];
        $exercice_annee = $_SESSION['exercice']['annee'];
        $exercice_mois = $_SESSION['exercice']['mois'];
        $exercice_treso = $_SESSION['exercice']['treso'];	
    } else { // On a pas de session on retourne vers la gestion d'exercice
    	header("Location: conf.php");    	
    }

// Récupération des variables de session abodep
    $abodep_mois = null;
    if(!empty($_SESSION['abodep']['mois'])) {
        $abodep_mois = $_SESSION['abodep']['mois'];
    } else { // On a pas de session avec le mois on retourne d'ou on vient
    	header("Location: journal.php");
    }
	
// Initialisation de la base
    include_once 'database.php';
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
// Lecture du POST (Choix du mois)
	$affiche_paiement_etale = false;
    $montant = null;
	$commentaire = null;
	$etale = null;
	$etaleError = null;
	$montantError = null;	
	$periodiciteeError = null;
	$type = null;
	
	if (isset($_POST['montant']) ) { // J'ai un POST
        $type = $sPOST['type'];        
        $montant = $sPOST['montant'];
        $periodicitee = $sPOST['periodicitee'];
		$commentaire = $sPOST['commentaire'];
		$etale = isset($_POST['etale']) ? $sPOST['etale'] : 0;
		
		// Validation du formulaire
		$valid = true;
		
		if (empty($montant) || $montant < 0 || $montant == null) {
			$montantError= "Veuillez entrer un montant positif ou nul.";
			$valid = false;
		}
		
		// Verification de la periodicitee
		if (($periodicitee == 12) && ($abodep_mois + $periodicitee - 1 ) > 12) { // On lisse la periodicitee
			$periodicitee = 12 - $abodep_mois + 1;
		}
		if (($abodep_mois + $periodicitee - 1 ) > 12) { // La périodicitee est superieure a l'année
			$periodiciteeError= "La périodicité de l'abonnement est trop grande pour l'exercice en cours.";		
			$valid = false;			
		}
		
		// Test de la checkbox paiement etalé
		if ($etale == 1 && NumToTypeRecette($type) == "Abonnement") {
			$affiche_paiement_etale = true;
			$valid = false;	
		} elseif ($etale == 1 && NumToTypeRecette($type) != "Abonnement") {
			$affiche_paiement_etale = false;
			$etaleError = "Seul un abonnement peut faire l'objet d'un etalement des paiements";
			$valid = false;	
		}
		
		// Vérification des paiements etalés
		if ($affiche_paiement_etale && isset($_POST['paiement_mois_1'])) { // On a un POST avec les paiements étalés
			$total = 0;
			for ($m = 1; $m <= 12; $m++) {
				$paiement_mois_{$m} = isset($_POST['paiement_mois_' . $m]) ? $sPOST['paiement_mois_' . $m] : 0;
				$total = $total + $paiement_mois_{$m};
			} // endfor
			if ($total == $montant) {
				$valid = true;
			} else {
				$etaleError = "Le total de vos paiements étalés est différent du montant de l'abonnement!";
				$valid = false;
			}			
		}
		
		// Calcul de la ventilation
		$ventillation = Ventillation($abodep_mois, $montant, $periodicitee);
		
		// On remet la périodicitée du POST pour enregistrement
		$periodicitee = $sPOST['periodicitee'];

		// insert data
		if ($valid) {
			$sql = "INSERT INTO abonnement (user_id,exercice_id,type,montant,mois,periodicitee,commentaire,mois_1,mois_2,mois_3,mois_4,mois_5,mois_6,mois_7,mois_8,mois_9,mois_10,mois_11,mois_12) values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
			$q = $pdo->prepare($sql);
			$q->execute(array($user_id, $exercice_id, $type, $montant, $abodep_mois, $periodicitee, $commentaire, $ventillation[1], $ventillation[2], $ventillation[3], $ventillation[4], $ventillation[5], $ventillation[6], $ventillation[7], $ventillation[8], $ventillation[9], $ventillation[10], $ventillation[11], $ventillation[12]));
			Database::disconnect();		
			// Réinitialise le formulaire		
			header("Location: abo.php");
		}
		
    } // If POST
	
	
// Lecture dans la base des abonnements (sur user_id et exercice_id et mois) 
    $sql = "SELECT * FROM abonnement WHERE
    		(user_id = :userid AND exercice_id = :exerciceid AND mois = :mois)
    		ORDER by date_creation
    		";
// Requette pour calcul de la somme	
    $sql2 = "SELECT SUM(montant),SUM(mois_1),SUM(mois_2),SUM(mois_3),SUM(mois_4),SUM(mois_5),SUM(mois_6),SUM(mois_7),SUM(mois_8),SUM(mois_9),SUM(mois_10),SUM(mois_11),SUM(mois_12) FROM abonnement WHERE
    		(user_id = :userid AND exercice_id = :exerciceid AND mois = :mois)
    		";
    					
    $q = array('userid' => $user_id, 'exerciceid' => $exercice_id, 'mois' => $abodep_mois);
    $req = $pdo->prepare($sql);
    $req->execute($q);
    $data = $req->fetchAll(PDO::FETCH_ASSOC);
    $req->execute($q);
    $count = $req->rowCount($sql);
	
	$req = $pdo->prepare($sql2);
    $req->execute($q);	
    $data2 = $req->fetch(PDO::FETCH_ASSOC);

    if ($count==0) { // Il n'y a rien à afficher
        $affiche = false;              
    } else {
    		// Calcul des sommes
	        $total_recettes= !empty($data2["SUM(montant)"]) ? $data2["SUM(montant)"] : 0;
	        for ($i = 1; $i <= 12; $i++) { 
	        	$total_mois_{$i}= !empty($data2["SUM(mois_$i)"]) ? $data2["SUM(mois_$i)"] : 0;
			}
	        // On affiche le tableau
	        $affiche = true;
    }
	Database::disconnect();
	$infos = true;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Aboo</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" media="screen">
    <link href="bootstrap/css/aboo.css" rel="stylesheet">
    <link rel='stylesheet' id='google_fonts-css'  href='http://fonts.googleapis.com/css?family=PT+Sans|Lato:300,400|Lobster|Quicksand' type='text/css' media='all' />
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>

<body>
    
    <script src="bootstrap/js/jquery-2.0.3.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>

    <!-- Affiche la navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">      
      <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <!-- Marque -->
          <a class="navbar-brand" href="home.php">Aboo</a>
      </div>     
      <!-- Liens -->
      <div class="collapse navbar-collapse" id="TOP">
        <ul class="nav navbar-nav">
          <li class="active"><a href="journal.php"><span class="glyphicon glyphicon-th-list"></span> Recettes & Dépenses</a></li>
          <li><a href="bilan.php"><span class="glyphicon glyphicon-calendar"></span> Bilan</a></li>
          <li><a href="encaissements.php"><span class="glyphicon glyphicon-credit-card"></span> Encaissements</a></li>
          <li><a href="paiements.php"><span class="glyphicon glyphicon-euro"></span> Paiements</a></li>
          <li><a href="mesclients.php"><span class="glyphicon glyphicon-star"></span> Clients</a></li>                           
          <li class="dropdown">
	        <!-- Affiche le nom de l'utilisateur à droite de la barre de Menu -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> <?php echo ucfirst($prenom) . ' ' . ucfirst($nom); ?><b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li><a href="conf.php"><span class="glyphicon glyphicon-wrench"></span> Configuration</a></li>
              <li><a href="deconnexion.php"><span class="glyphicon glyphicon-off"></span> Deconnexion</a></li>  
            </ul> 
          </li>
          <li><a href="deconnexion.php"><span class="glyphicon glyphicon-off"></span></a></li>      
        </ul>
      </div><!-- /.navbar-collapse -->
    </nav>
        
    <div class="container">
        <h2>Recettes & Abonnements</h2>
        <br>
                      
        <!-- Affiche les informations de debug -->
        <?php 
 		if ($debug) {
		?>
		<div class="span10 offset1">
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
       </div>
        <?php       
        }   
        ?>  
        
		<!-- Affiche la table des abonnements en base sous condition -->
		<div class="span10">
			<?php 
 			if ($affiche) {
			?>
	            <div class="row">
	                <h3>Liste des recettes du mois courant : <button type="button" class="btn btn-info"><?php echo NumToMois($abodep_mois); ?> : <span class="badge "><?php echo $count; ?></span></button></h3>
			
					<table class="table table-striped table-bordered table-hover success">
						<thead>
							<tr>
							  <th>Type</th>
							  <th>Montant</th>
							  <th>Périodicitée</th>
							  <th>Commentaire</th>
							  <th>Action</th>
							</tr>
						</thead>
		                
						<tbody>
						<?php		 
							foreach ($data as $row) {
								echo '<tr>';					
								echo '<td>' . NumToTypeRecette($row['type']) . '</td>';
								echo '<td>' . $row['montant'] . ' €</td>';
								echo '<td>' . NumToPeriodicitee($row['periodicitee']) . '</td>';						
								echo '<td>' . $row['commentaire'] . '</td>';
							   	echo '<td width=90>';
						?>		
								<div class="btn-group btn-group-sm">
									  	<a href="abo_update.php?id=<?php echo $row['id']; ?>" class="btn btn-default btn-sm btn-warning glyphicon glyphicon-edit" role="button"> </a>
									  	<a href="abo_delete.php?id=<?php echo $row['id']; ?>" class="btn btn-default btn-sm btn-danger glyphicon glyphicon-trash" role="button"> </a>
								</div>
		
							   	</td>						
								</tr>
					<?php
						   } // Foreach	
					?>
		                </tbody>
		            </table>
		            <!-- Affiche les sommmes -->        
					<p>
						<button type="button" class="btn btn-info">Total recettes : <?php echo $total_recettes; ?> €</button>
						<button type="button" class="btn btn-info">Total affecté au salaire : <?php echo $total_mois_{$abodep_mois}; ?> €</button>
						<button type="button" class="btn btn-info">Trésorerie : <?php echo ($total_recettes - $total_mois_{$abodep_mois}); ?> €</button>
						
					</p>             
				</div> 	<!-- /row -->
			<?php
			} // If Affiche
			?>
			<!-- Affiche le formulaire inline ajout abonnement -->			
            <div class="row">
                <h3>Ajout d'un abonnement :</h3>
	            <form class="form-inline" role="form" action="abo.php" method="post">
	            
		            <?php function Affiche_Champ(&$champ, &$champError, $champinputname, $champplaceholder, $type) { ?>
		            		<div class="form-group <?php echo !empty($champError)?'has-error':'';?>">
		                    	<input name="<?php echo "$champinputname" ?>" id="<?php echo "$champinputname" ?>" type="<?php echo "$type" ?>" class="form-control" value="<?php echo !empty($champ)?$champ:'';?>" placeholder="<?php echo "$champplaceholder" ?>">		                      
		       				</div>
		            <?php } ?>
		            
					<!-- Formulaire principal -->
		            <div class="form-group">
		                    <select name="type" class="form-control">
				            <?php
				                foreach ($Liste_Recette as $r) {
				            ?>
				                <option value="<?php echo TypeRecetteToNum($r);?>" <?php echo (TypeRecetteToNum($r)==$type)?'selected':'';?>><?php echo $r;?></option>    
				            <?php 
				                } // foreach   
				            ?>
		                    </select>
		            </div>		      
		       		<?php Affiche_Champ($montant, $montantError, 'montant','Montant €', 'text' ); ?>
		            <div class="form-group <?php echo !empty($periodiciteeError)?'has-error':'';?>">
		                    <select name="periodicitee" class="form-control">
				            <?php
				                foreach ($Liste_Periodicitee as $p) {
				            ?>
				                <option value="<?php echo PeriodiciteeToNum($p);?>"><?php echo $p;?></option>    
				            <?php
				                } // foreach   
				            ?>
		                    </select>
		            </div>			       		
		       		<?php Affiche_Champ($commentaire, $commentaireError, 'commentaire','Commentaire', 'text' ); ?>
		       		
		       		<button class="btn btn-default form-group <?php echo !empty($etaleError)?'has-error':'';?>"><span class="glyphicon glyphicon-calendar"></span> Paiement étalé?
		       		<div class="checkbox form-group">
					  <label class="checkbox-inline">
					    <input name="etale" type="checkbox" value="1" <?php echo ($etale==1)?'checked':'';?>>
					  </label>	
					</div>
					</button>

	              	<button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-plus-sign"></span> Ajout</button><br>

					<!-- Formulaire conditionnel -->
					<?php 
 					if ($affiche_paiement_etale) {
						$paiement_mois_Error = null; 						
 						for ($m = $abodep_mois; $m <= 12; $m++) {
							Affiche_Champ($paiement_mois_{$m}, $paiement_mois_Error, 'paiement_mois_' . $m, NumToMois($m) . ' €', 'text' );
						} // endfor
						echo '<br>';	 
 					} // endif
					?>
	
  					<!-- Affiche les erreurs -->
					<?php if (!empty($montantError)): ?>
					<span class="has-error"><?php echo $montantError;?></span>
					<?php endif; ?>
					<?php if (!empty($periodiciteeError)): ?>
					<span class="has-error"><?php echo $periodiciteeError;?></span>
					<?php endif; ?>						
					<?php if (!empty($commentaireError)): ?>
					<span class="has-error"><?php echo $commentaireError;?></span>
					<?php endif; ?>
					<?php if (!empty($etaleError)): ?>
					<span class="has-error"><?php echo $etaleError;?></span>
					<?php endif; ?>
	            </form>
            </div> 	<!-- /row -->
			<script type="text/javascript">
				document.getElementById('montant').focus(); 
			</script>
			<!-- Affiche le bouton retour -->
			<br>        
			<p>
				<a class="btn btn-primary" href="journal.php"><span class="glyphicon glyphicon-chevron-up"></span> Retour</a>
			</p>
			
        </div>  <!-- /span -->        			
    
    </div> <!-- /container -->
  </body>
</html>