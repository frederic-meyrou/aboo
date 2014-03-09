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


// Calcul du Bilan
    $TableauBilanMensuel = CalculBilanMensuel($user_id, $exercice_id, $exercice_treso);
    $TableauBilanAnnuel = CalculBilanAnnuel($user_id, $exercice_id, $TableauBilanMensuel);        
    
    if ($TableauBilanAnnuel==null) { // Il n'y a rien en base sur l'année (pas de dépenses et pas de recettes)
        $affiche = false;         
    } else {
            // On affiche le tableau
            $affiche = true;
    }

?>

<!DOCTYPE html>
<html lang="fr">
<?php require 'head.php'; ?>

<body>

    <?php $page_courante = "bilan_annuel.php"; require 'nav.php'; ?>
        
    <div class="container">
     
       
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
            Tableau:<br>            
            <pre><?php var_dump($TableauBilanMensuel); ?></pre>
            <br>
            Tableau:<br>            
            <pre><?php var_dump($TableauBilanAnnuel); ?></pre>
            <br>                                                      
        </div>
        <?php       
        }   
        ?> 

        <div class="page-header">          
            <h2>Calcul du fond de roulement : <button type="button" class="btn btn-lg btn-info"><?php echo "$exercice_annee - " . ($exercice_annee +1); ?></button></h2>  
        </div>
 
        <!-- Affiche sous condition -->
        <div class="span10">
            <?php 
            if ($affiche) {
            ?>
            <!-- Affiche les sommmes -->
            <center>
            <table width="80%"> 
                <tr>                                                            
                    <td align="right"><button type="button" class="btn btn-info">Moyenne mensuelle des Charges : </button></td> 
                    <td align="left"><button type="button" class="btn btn-default"><?php echo number_format($TableauBilanAnnuel['DEPENSE']/12,2,',','.'); ?> €</button></td> 
                </tr>
                <tr>                                                             
                    <td align="right"><button type="button" class="btn btn-info">Salaire mensuel moyen : </button></td>                     
                    <td align="left"><button type="button" class="btn btn-default"><?php echo number_format($TableauBilanAnnuel['SALAIRE']/12,2,',','.'); ?> €</button></td>                     
                </tr>
                <tr>                                                            
                    <td align="right"><button type="button" class="btn btn-warning">Fond de roulement (trésorerie) : </button></td>   
                    <td align="left"><button type="button" class="btn btn-default"><?php echo number_format(($TableauBilanAnnuel['SALAIRE']/12 - $TableauBilanAnnuel['DEPENSE']/12),0,',','.'); ?> €</button></td>   
                </tr>          
	            <tr> 
    	        </tr>	               
                <tr>                                                            
                    <td align="right"><button type="button" class="btn btn-info">Trésorerie initiale : </button></td>    
                    <td align="left"><button type="button" class="btn btn-default"><?php echo number_format($exercice_treso,2,',','.'); ?> €</button></td>    
                </tr>
                <tr>                                                            
                    <td align="right"><button type="button" class="btn btn-info">Trésorerie finale : </button></td>   
                    <td align="left"><button type="button" class="btn btn-default"><?php echo number_format($TableauBilanAnnuel['REPORT_TRESO'],2,',','.'); ?> €</button></td>   
                </tr>

            </table></center>
            <br> 
                                  
            </div>  <!-- /row -->
            <?php   
            } // if
            ?>
        </div>  <!-- /span -->                  
             
    </div> <!-- /container -->

    <?php require 'footer.php'; ?>
        
  </body>
</html>