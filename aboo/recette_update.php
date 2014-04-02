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
    
// Vérification du GET
    $id = null;
    if ( !empty($sGET['id'])) {
        $id = $sGET['id'];
    }   
        
// Lecture et validation du POST
    if ( !empty($sPOST)) {

        // Init base
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // keep track validation errors
        $montantError = null;
		$commentaireError = null;
                        
        // keep track post values
        $id = $sPOST['id']; 
        //$type = $sPOST['type'];        
        $montant = $sPOST['montant'];
        $periodicitee = $sPOST['periodicitee'];
		$commentaire = $sPOST['commentaire'];
		$paiement = isset($_POST['paiement']) ? $sPOST['paiement'] : 0;
		$client_id = isset($_POST['client']) ? $sPOST['client'] : null;
				
		// validate input
		$valid = true;
		
		if (empty($montant) || $montant < 0 || $montant == null) {
			$montantError= "Veuillez entrer un montant positif ou nul.";
			$valid = false;
		}

		// Verification de la periodicitee		
		
           
        // Modif des données en base et redirection vers appelant
        if ($valid) {
            $sql = "UPDATE recette SET montant=?,commentaire=?, client_id=? WHERE id = ?";
            $q = $pdo->prepare($sql);
            $q->execute(array($montant, $commentaire, $client_id, $id));
            Database::disconnect();        
            header("Location: recette.php");
        }       
    } else {
        // Lecture des infos ds la base
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM recette where id = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
        $data = $q->fetch(PDO::FETCH_ASSOC);
        $id = $data['id'];   		
		$montant = $data['montant'];
        $commentaire = $data['commentaire'];
		$type = $data['type'];
		$periodicitee = $data['periodicitee'];
		$client_id = $data['client_id'];
		
		// Lecture dans la base des clients (sur user_id) 
    	$sql3 = "SELECT id,prenom,nom FROM client WHERE
    		(user_id = :userid)
    		";
	    $q3 = array('userid' => $user_id);
		$req3 = $pdo->prepare($sql3);
	    $req3->execute($q3);	
	    $data3 = $req3->fetchAll(PDO::FETCH_ASSOC);
		$count3 = $req3->rowCount($sql3);	    			

		// Liste des clients à afficher
		$Liste_Client = array();    	
	    if ($count3!=0) {
	    	$i=0;
	    	foreach ($data3 as $row3) {
				$Liste_Client[$i]['id']=$row3['id'];
				$Liste_Client[$i]['prenometnom']=ucfirst($row3['prenom']) . ' ' . ucfirst($row3['nom']);
				$i++;
			}   	
	    } 			
				     
        Database::disconnect();                
    }
    
?>

<!DOCTYPE html>
<html lang="fr">
<?php require 'head.php'; ?>

<body>

    <?php $page_courante = "journal.php"; require 'nav.php'; ?>
        
    <div class="container">
         <div class="page-header">   
            <h2>Recettes & Abonnements</h2>
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
        </div>
        <?php       
        }   
        ?>  
                
		<div class="row">
 			 <div class="col-md-5 col-md-offset-1">
                <h3>Modification d'une recette <button type="button" class="btn btn-info"><?php echo NumToMois($abodep_mois); ?></button></h3>
                
		        <!-- Formulaire -->  
	            <form class="form-horizontal" action="recette_update.php" method="post">
	            	
	            	<div class="form-group">
		                <label class="control-label">Type</label><br>
		                <button type="button" class="control-label btn btn-default"><?php echo NumToTypeRecette($type); ?></button>
		            </div>
	            
		            <?php function Affiche_Champ(&$champ, &$champError, $champinputname, $champplaceholder, $type) { ?>
		            <div class="form-group <?php echo !empty($champError)?'has-error':'';?>">
		                <label class="control-label"><?php echo "$champplaceholder" ?></label>
		                <div class="controls">
		                    <input name="<?php echo "$champinputname" ?>" class="form-control" type="<?php echo "$type" ?>" value="<?php echo !empty($champ)?$champ:'';?>">
		                    <?php if (!empty($champError)): ?>
		                        <span class="help-inline"><?php echo $champError;?></span>
		                    <?php endif; ?>
		                </div>
		            </div>
		            <?php } ?>
		       		
		       		<input type="hidden" name="id" value="<?php echo $id; ?>">
		       		
		       		<?php Affiche_Champ($montant, $montantError, 'montant','Montant €', 'text' ); ?>
					<div class="form-group">
						<label class="control-label">Client</label>
		                    <select name="client" id="client" class="form-control">
				            	<option value="0">N/C</option>
				            <?php
				            	foreach ($Liste_Client as $c) {
				            ?>
				                <option value="<?php echo $c['id'];?>" <?php echo ($c['id']==$client_id)?'selected':'';?>><?php echo $c['prenometnom'];?></option>    
				            <?php
				                } // foreach   
				            ?>			                				                    
		                    </select>
		            </div>		            			   
		    		<?php Affiche_Champ($commentaire, $commmentaireError, 'commentaire','Commentaire', 'text' ); ?>
		    		<br>
		                                                
		            <div class="form-actions">
		              <button type="submit" class="btn btn-warning"><span class="glyphicon glyphicon-check"></span> Mise à jour</button>
		              <a class="btn btn-primary" href="recette.php"><span class="glyphicon glyphicon-eject"></span> Retour</a>
		            </div>
	            </form>
	   		 </div> <!-- /col -->    			
	    </div> <!-- /row -->   			
    
    </div> <!-- /container -->

    <?php require 'footer.php'; ?>
        
  </body>
</html>