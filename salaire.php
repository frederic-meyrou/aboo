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
    if(isset($_SESSION['exercice'])) {
        $exercice_id = $_SESSION['exercice']['id'];
        $exercice_annee = $_SESSION['exercice']['annee'];
        $exercice_mois = $_SESSION['exercice']['mois'];
        $exercice_treso = $_SESSION['exercice']['treso'];	
    } else { // On a pas de session on retourne vers la gestion d'exercice
    	header("Location: exercice.php");    	
    }

// Récupération des variables de session abodep
    $abodep_mois = null;
    if(!empty($_SESSION['abodep']['mois'])) {
        $abodep_mois = $_SESSION['abodep']['mois'];
    } else { // On a pas de session avec le mois on retourne d'ou on vient
    	header("Location: journal.php");
    }
	
// Initialisation de la base
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Lecture du POST (Choix du mois)
    if (isset($sPOST['mois']) ) { // J'ai un POST
            $mois_choisi = $sPOST['mois'];
    } else { // Je n'ai pas de POST
            $mois_choisi = null;
    }
	
// Selection du mois par défaut
	// On va lire le mois en cours en BDD si il exite
	if ($mois_choisi == null) {
	    $sql = "SELECT mois_encours FROM user WHERE id = ?";
	    $q = $pdo->prepare($sql);
	    $q->execute(array($user_id));
	    $data = $q->fetch(PDO::FETCH_ASSOC);	
	    $count = $q->rowCount($sql);
		if ($count==1) { // On a bien un mois en cours
			$mois_choisi = $data['mois_encours'];
		}
	}		
	if ($exercice_mois != null && ($mois_choisi == null && $abodep_mois == null)) {
		// On a pas de POST ni de SESSION mais on a un mois de debut d'exercice
		$mois_choisi = $exercice_mois;
	} elseif ($mois_choisi == null && $abodep_mois != null) {
		// On a dejà une session mais pas de POST
		$mois_choisi = $abodep_mois;
	} elseif ($mois_choisi == null) {
		// On a vraiment rien on prend le mois courant
		$mois_choisi = date('n');
	}
	$_SESSION['abodep']['mois'] = $mois_choisi;
    $abodep_mois = $mois_choisi;
	// On met à jour la BDD pour les champs encours
    $sql = "UPDATE user SET mois_encours=? WHERE id = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($mois_choisi, $user_id));	
    
// Lecture du POST Formulaire
    $montant = null;
	$commentaire = null;
	$montantError = null;	
    if (isset($sPOST['montant']) ) { // J'ai un POST
        $montant = $sPOST['montant'];
		$commentaire = $sPOST['commentaire'];
		
		// validate input
		$valid = true;
		
		if (empty($montant) || $montant < 0 || $montant == null) {
			$montantError= "Veuillez entrer un montant positif.";
			$valid = false;
		}

		// insert data
		if ($valid) {
			//$sql = "INSERT INTO depense (user_id,exercice_id,type,montant,mois,commentaire) values(?, ?, ?, ?, ?, ?)";
			//$q = $pdo->prepare($sql);		
			//$q->execute(array($user_id, $exercice_id, $type, $montant, MoisRelatif($abodep_mois,$exercice_mois), $commentaire));
		}
		
		// Réinitialise pour le formulaire		
		header("Location: salaire.php");
    } // If POST
	
// Charge le Bilan    
    $TableauBilanMensuel = CalculBilanMensuel($user_id, $exercice_id, $exercice_treso);      	
?>

<!DOCTYPE html>
<html lang="fr">
<?php require 'head.php'; ?>

<body>

    <?php $page_courante = "salaire.php"; require 'nav.php'; ?>
    
    <div class="container">
        <div class="page-header">           
            <h2>Gestion du salaire</h2>
        </div>

        <!-- Affiche le dropdown formulaire mois avec selection automatique du mois en cours de la session -->
        <form class="form-inline" role="form" action="salaire.php" method="post">      
            <select name="mois" class="form-control">
            <?php
                foreach ($Liste_Mois as $m) {
            ?>
                <option value="<?php echo MoisToNum($m);?>"<?php echo ($m==NumToMois($mois_choisi))?'selected':'';?>><?php echo "$m";?></option>    
            <?php       
                }   
            ?>    
            </select>
            <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-refresh"></span> Changer de mois</button>
            <!-- Affiche le bouton retour -->
            <a class="btn btn-primary" href="journal.php"><span class="glyphicon glyphicon-eject"></span> Retour</a>            
        </form>
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

        <!-- Affiche les sommmes -->       
        <div> 
	        <div class="btn-group btn-group-sm">
	            <button type="button" class="btn btn-info">Salaire calculé :</button>
	            <button type="button" class="btn btn-default"><?php echo $TableauBilanMensuel[MoisRelatif($abodep_mois, $exercice_mois)]['SALAIRE']; ?> €</button>
	        </div>
	        <div class="btn-group btn-group-sm">
	            <button type="button" class="btn btn-info">Trésorerie avant salaire :</button>               
	            <button type="button" class="btn btn-default"><?php echo $TableauBilanMensuel[MoisRelatif($abodep_mois, $exercice_mois)]['TRESO']; ?> €</button>             
	        </div>	         
	        <div class="btn-group btn-group-sm">
	            <button type="button" class="btn btn-info">Trésorerie de fin de mois :</button>               
	            <button type="button" class="btn btn-default"><?php echo $TableauBilanMensuel[MoisRelatif($abodep_mois, $exercice_mois)]['REPORT_TRESO']; ?> €</button>             
	        </div>
        </div>
        <br>
                        
		<!-- Affiche le formulaire inline ajout depense -->			               
        <?php 
            // Insère l'aide en ligne pour les actions
            $IDModale = "modalAideSalaire";
            include('lib/aide.php'); 
        ?>                
        
        <div class="panel panel-default">
            <div class="panel-heading"><strong>Saisie du salaire réellement versé pour <?php echo NumToMois($abodep_mois) . " $exercice_annee - " . ($exercice_annee +1); ?> :</strong><a href="#" class="pull-right" onclick="$('#modalAideSalaire').modal('show'); "><span class="glyphicon glyphicon-info-sign"></span></a></div>
            <div class="panel-body">
        
	            <form class="form-inline" role="form" action="salaire.php" method="post">
		            <?php function Affiche_Champ(&$champ, &$champError, $champinputname, $champplaceholder, $type) { ?>
		            		<div class="form-group <?php echo !empty($champError)?'has-error':'';?>">
		                    	<input name="<?php echo "$champinputname" ?>" id="<?php echo "$champinputname" ?>" type="<?php echo "$type" ?>" class="form-control" value="<?php echo !empty($champ)?$champ:'';?>" placeholder="<?php echo "$champplaceholder" ?>">		            
		                    <?php if (!empty($champError)): ?>
		                     		<span class="help-inline"><?php echo $champError;?></span>
		                    <?php endif; ?>		            
		       				</div>
		            <?php } ?>
                    <div class="form-group <?php echo !empty($montantError)?'has-error':'';?>">
                        <input name="montant" id="montant" type="text" class="form-control" value="<?php echo !empty($montant)?$montant:'';?>" placeholder="Montant €" required autofocus>                              
                    </div>                          	                  		            
		       		<?php Affiche_Champ($commentaire, $commentaireError, 'commentaire','Commentaire', 'text' ); ?>

	              	<button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-check"></span> Enregistrer</button>
	            </form>
            </div>  <!-- /panel-body -->                        
        </div>  <!-- /panel -->			
    
    </div> <!-- /container -->

    <?php require 'footer.php'; ?>
        
  </body>
</html>