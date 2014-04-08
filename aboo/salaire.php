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
    
// Initialisation
    $affiche_formulaire = false;   
    $mois_choisi = null; 

// Lecture et validation du GET (J'ai une demande de modification)
    if ( !empty($sGET)) {
        $mois_choisi = $sGET['mois'];
        $affiche_formulaire = true;
        // On vérifie que le salaire peux être modifié
        $sql = "SELECT * FROM salaire WHERE user_id = ? AND exercice_id = ? AND mois = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($user_id, $exercice_id, $mois_choisi));
        $data = $q->fetch(PDO::FETCH_ASSOC);
        $count = $q->rowCount($sql);
        if ($count==1) { // On a un enregistrement de salaire
            $montant = $data['salaire'];
            $commentaire = $data['commentaire'];
        } else {
            $montant = 'Idem';
            $commentaire = '';            
        }
        $salaire = $montant;               
    }     

// Lecture du POST (J'ai une demande de suppression)
    if (isset($sPOST['id']) ) { // J'ai un POST
        $mois_choisi = $sPOST['id'];
        // On vérifie que le salaire peux être supprimé
        $sql = "SELECT * FROM salaire WHERE user_id = ? AND exercice_id = ? AND mois = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($user_id, $exercice_id, $mois_choisi));
        $data = $q->fetch(PDO::FETCH_ASSOC);
        $count = $q->rowCount($sql);
        if ($count==1) { // On a bien un enregistrement de salaire
            // suppression du salaire
            $sql2 = "DELETE FROM salaire WHERE user_id = ? AND exercice_id = ? AND mois = ?";
            $q = $pdo->prepare($sql2);
            $q->execute(array($user_id, $exercice_id, $mois_choisi));
        }   
    }

// Charge le Bilan    
    $TableauBilanMensuel = CalculBilanMensuel($user_id, $exercice_id, $exercice_treso);
    $TableauBilanAnnuel = CalculBilanAnnuel($user_id, $exercice_id, $TableauBilanMensuel);        
    
    if ($TableauBilanAnnuel==null) { // Il n'y a rien en base sur l'année (pas de dépenses et pas de recettes)
        $bilan = false;         
    } else {
            // On affiche le tableau
            $bilan = true;
    }
    			        
// Lecture du POST Formulaire
    $montant = null;
	$commentaire = null;
	$montantError = null;
    if (isset($sPOST['montant']) ) { // J'ai un POST de formulaire
        $montant = $sPOST['montant'];
		$commentaire = $sPOST['commentaire'];
        $mois_choisi = $sPOST['mois']; //Hidden
        $treso_dispo = $TableauBilanMensuel[$mois_choisi]['TRESO'];         

        // Lecture du salaire dans la base
        $sql2="SELECT * FROM salaire WHERE user_id = ? AND exercice_id = ? AND mois = ?";
        $q2=$pdo->prepare($sql2);
        $q2->execute(array($user_id, $exercice_id, $mois_choisi));
        $data2=$q2->fetch(PDO::FETCH_ASSOC);
        $count2 = $q2->rowCount($sql2);        
        if ($count2==0) { // Pas de salaire enregistré
            $salaire="Idem";
        } else {
            $salaire=$data2['salaire'];
        }		
		// validate input
        $affiche_formulaire = true;
		$valid = true;
		if ( !is_numeric($montant) || $montant < 0  ) {
			$montantError= "Veuillez entrer un montant de salaire positif.";
			$valid = false;
		} elseif ( $montant > $treso_dispo ) { // On vérifie que le montant est cohérent
			$montantError= "Le montant de salaire que vous avez entré est supérieur à votre trésorerie disponible ($treso_dispo €).";
			$valid = false;			
		}

		// insert data
		if ($valid && $salaire=="Idem") {
			$sql4 = "INSERT INTO salaire (user_id,exercice_id,mois,salaire,commentaire) values(?, ?, ?, ?, ?)";
			$q4 = $pdo->prepare($sql4);		
			$q4->execute(array($user_id, $exercice_id, $mois_choisi, $montant, $commentaire));
		} elseif ($valid && $salaire!="Idem") {
			$sql4 = "UPDATE salaire set salaire=?, commentaire=? WHERE user_id=? AND exercice_id=? AND mois=?";
			$q4 = $pdo->prepare($sql4);		
			$q4->execute(array($montant, $commentaire, $user_id, $exercice_id, $mois_choisi));
		}
		if ($valid) { // Réinitialise la page formulaire				
            Database::disconnect();
			header("Location: salaire.php");			
		}		
    } // If POST Formualire
    
    Database::disconnect();
?>

<!DOCTYPE html>
<html lang="fr">
<?php require 'head.php'; ?>

<body>

    <?php $page_courante = "salaire.php"; require 'nav.php'; ?>
    
    <div class="container">
        <div class="page-header">           
            <h2>Gestion du salaire : <button type="button" class="btn btn-lg btn-info"><?php echo "$exercice_annee - " . ($exercice_annee +1); ?></h2>
        </div>

        <!-- Affiche les sommmes -->
        <div>      
	        <div class="btn-group btn-group-sm">
	            <button type="button" class="btn btn-info">Salaire Annuel :</button>                             
	            <button type="button" class="btn btn-default"><?php echo number_format($TableauBilanAnnuel['SALAIRE'],2,',','.'); ?> €</button>                            
	        </div>
	        <div class="btn-group btn-group-sm">
	            <button type="button" class="btn btn-warning">Salaire Moyen :</button>                             
	            <button type="button" class="btn btn-default"><?php echo number_format($TableauBilanAnnuel['SALAIRE'] / 12,2,',','.'); ?> €</button>                            
	        </div>
	        <div class="btn-group btn-group-sm">
	            <button type="button" class="btn btn-info">Trésorerie en début d'année :</button>               
	            <button type="button" class="btn btn-default"><?php echo number_format($exercice_treso,2,',','.'); ?> €</button>             
	        </div>	            
	        <div class="btn-group btn-group-sm">
	            <button type="button" class="btn btn-info">Trésorerie en fin d'année :</button>               
	            <button type="button" class="btn btn-default"><?php echo number_format($TableauBilanAnnuel['REPORT_TRESO'],2,',','.'); ?> €</button>             
	        </div>
	        <div class="btn-group btn-group-sm pull-right">
            	<button type="button" class="btn btn-sm btn-default" onclick="$('#modalAideSalaire').modal('show'); ">Aide <span class="glyphicon glyphicon-info-sign"></span></a></button>             
			</div>
	        
		</div>
        <br>

        <!-- Affiche la table des exercices en base sous condition -->
        <?php 
            // Insère l'aide en ligne pour les actions
            $IDModale = "modalAideActions";
            include('lib/aide.php');
            // Insère l'aide en ligne pour les actions
            $IDModale = "modalAideSalaire";
            include('lib/aide.php');                             
        ?>
                    
        <div class="table-responsive">  
        <table class="table table-striped table-bordered table-condensed table-hover success">
            <thead>
                <tr class="active">
                  <th>Mois</th>
                  <th>Recettes</th>
                  <th>Bénéfice</th>
                  <th>Trésorerie disponible</th>
                  <th>Salaire calculé</th>
                  <th>Salaire réel</th>
                  <th>Commentaire</th>                  
                  <th>Action <a href="#" onclick="$('#modalAideActions').modal('show'); "><span class="glyphicon glyphicon-info-sign"></span></a></th>                  
                </tr>
            </thead>
            
            <tbody>
        <?php 
         // Calcul des sommes (boucle sur les mois relatifs)
         for ($m = 1; $m <= 12; $m++) {
            echo '<tr>';
            echo '<td>'. $m . " : " . NumtoMois(MoisAnnee($m, $exercice_mois)) . '</td>';
            echo '<td>'. number_format($TableauBilanMensuel[$m]['ENCAISSEMENT'],2,',','.') . ' €</td>';
            if ($TableauBilanMensuel[$m]['BENEFICE'] < 0 ) { // Pb Bénéfice !
                echo '<td class="danger">';
            } else { 
                echo '<td>';
            }
            echo number_format($TableauBilanMensuel[$m]['BENEFICE'],2,',','.') . ' €</td>';          
			if ($TableauBilanMensuel[$m]['TRESO'] < 0 ) { // Pb Tréso !
				echo '<td class="danger">';
			} elseif ($TableauBilanMensuel[$m]['TRESO'] > $TableauBilanMensuel[$m]['SALAIRE']) { // Treso dispo
				echo '<td class="success">';
			} else { // TReso indispo
				echo '<td class="warning">';
			}				
            echo number_format($TableauBilanMensuel[$m]['TRESO'],2,',','.') . ' €</td>';
            echo '<td>'. number_format($TableauBilanMensuel[$m]['SALAIRE'],2,',','.') . ' €</td>';
			if ($TableauBilanMensuel[$m]['SALAIRE']!=$TableauBilanMensuel[$m]['SALAIRE_REEL']) { // Salaire modifié
				echo '<td class="info">';
			} else { // Salaire non modifié
				echo '<td>';
			}			                         
            echo ($TableauBilanMensuel[$m]['SALAIRE']!=$TableauBilanMensuel[$m]['SALAIRE_REEL'])?number_format($TableauBilanMensuel[$m]['SALAIRE_REEL'],2,',','.'):'Idem';
			if ($TableauBilanMensuel[$m]['SALAIRE']!=$TableauBilanMensuel[$m]['SALAIRE_REEL']) { // Salaire modifié
				echo ' €</td>';
			} else { // Salaire non modifié
				echo '</td>';
			}			            
            echo '<td>'. $TableauBilanMensuel[$m]['SALAIRE_COMMENTAIRE'] . '</td>';
            echo '<td width=70>';
        ?>
                        <div class="btn-group btn-group-xs">
                                <a href="salaire.php?mois=<?php echo $m ?>" class="btn btn-default btn-xs btn-warning glyphicon glyphicon-edit" role="button"> </a>                                                                
                                <!-- Le bouton Delete active la modal et modifie le champ value à la volée pour passer le mois a supprimer en POST -->
                                <a href="#" id="<?php echo $m; ?>"
                                   <?php if ($TableauBilanMensuel[$m]['SALAIRE']!=$TableauBilanMensuel[$m]['SALAIRE_REEL']) { ?>
                                   onclick="$('#DeleteInput').val('<?php echo $m; ?>'); $('<?php echo '#modalDelete'; ?>').modal('show'); "
                                   <?php } else { ?>
                                   onclick="$('#DeleteInput').val('<?php echo $m; ?>'); $('<?php echo '#modalImpossible'; ?>').modal('show'); "
                                   <?php } ?>
                                   class="btn btn-default btn-sm btn-danger glyphicon glyphicon-trash" role="button"> </a>
                        </div>                                                      
                    </td>
                </tr>
        <?php
        }
        ?>
            </tbody>
        </table>
        </div> <!-- /table-responsive -->  

        <!-- Modal delete-->                
        <?php include('modal/salaire_delete.php'); ?>
        <!-- Modal impossible-->                
        <?php include('modal/salaire_impossible.php'); ?>            
                                
        <!-- Affiche les informations de debug -->
        <?php 
 		if ($debug) {
		?>
        <br>
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

       <!-- Formulaire de modif salaire-->  
        <?php 
        if ($affiche_formulaire) {
        ?>
        <br>  
        <div class="panel panel-default">
            <div class="panel-heading"><strong>Saisie du salaire réellement versé pour <?php echo NumToMois(MoisAnnee($mois_choisi, $exercice_mois)) . " $exercice_annee - " . ($exercice_annee +1); ?> :</strong><a href="#" class="pull-right" onclick="$('#modalAideSalaire').modal('show'); "><span class="glyphicon glyphicon-info-sign"></span></a></div>
            <div class="panel-body">

                <!-- Affiche les sommmes -->       
                <div> 
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-info">Salaire calculé :</button>
                        <button type="button" class="btn btn-default"><?php echo number_format($TableauBilanMensuel[$mois_choisi]['SALAIRE'],2,',','.'); ?> €</button>
                    </div>
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-warning">Salaire versé :</button>
                        <button type="button" class="btn btn-default"><?php echo number_format($TableauBilanMensuel[$mois_choisi]['SALAIRE_REEL'],2,',','.'); ?> €</button>
                    </div>     
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-info">Trésorerie avant salaire :</button>               
                        <button type="button" class="btn btn-default"><?php echo number_format($TableauBilanMensuel[$mois_choisi]['TRESO'],2,',','.'); ?> €</button>             
                    </div>           
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-info">Trésorerie de fin de mois :</button>               
                        <button type="button" class="btn btn-default"><?php echo number_format($TableauBilanMensuel[$mois_choisi]['REPORT_TRESO'],2,',','.'); ?> €</button>             
                    </div>
                </div>
                <br>

                <form class="form-inline" role="form" action="salaire.php" method="post">
                    <input name="mois" type="hidden" value="<?php echo $mois_choisi; ?>"/>
                    <div class="form-group <?php echo !empty($montantError)?'has-error':''; ?>">
                        <input name="montant" id="montant" type="text" class="form-control" value="<?php echo !empty($montant)?$montant:''; ?>" placeholder="Montant €" required autofocus>                                                      
                    </div>                                                                  
                    <div class="form-group <?php echo !empty($commentaireError)?'has-error':''; ?>">
                        <input name="commentaire" id="commentaire" type="text" class="form-control" value="<?php echo !empty($commentaire)?$commentaire:''; ?>" placeholder="Commentaire">                                                      
                    </div>
                    <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-check"></span> Enregistrer</button>
                    <?php if (!empty($montantError)) { ?>
                        <span class="help-inline text-danger"><?php echo $montantError; ?></span>
                    <?php } ?>
                    <!-- Affiche le bouton retour -->
                    <a class="btn btn-primary" href="salaire.php"><span class="glyphicon glyphicon-eject"></span> Retour</a><br>                       
                </form>

            </div> <!-- /Panel-body -->
        </div> <!-- /Panel -->
        <?php       
        }   
        ?>

    </div> <!-- /container -->

    <?php require 'footer.php'; ?>   
        
  </body>
</html>