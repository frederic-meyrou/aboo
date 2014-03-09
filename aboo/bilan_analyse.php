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

    $danger = array();
    $warning = array();
    $ok = array();
    $indice_danger=0;
    $indice_warning=0;
    $indice_ok=0;           

// Analyse du bilan Mensuel
        
    for ($mois = 1; $mois <= 12; $mois++) {
        $num_mois = MoisAnnee($mois, $exercice_mois);
        // On check la treso
        if ($TableauBilanMensuel[$mois]['REPORT_TRESO'] < 0) {
            $danger[$indice_danger]="Votre trésorerie de fin de mois en " . NumToMois($num_mois) . " est négative (". number_format($TableauBilanMensuel[$mois]['REPORT_TRESO'],2,',','.') . "€)."; $indice_danger++;
        }
        // On check la dispo salaire
        if ( ($TableauBilanMensuel[$mois]['REPORT_TRESO'] + $TableauBilanMensuel[$mois]['ENCAISSEMENT'] ) < ( $TableauBilanMensuel[$mois]['VENTIL'] + $TableauBilanMensuel[$mois]['DEPENSE']) ) {
            $danger[$indice_danger]="Il n'y a pas assez de trésorerie disponible ou d'encaissement pour vous verser entièrement votre salaire en " . NumToMois($num_mois) . "."; $indice_danger++;
        }
        // On check la dispo depense
        if ( ($TableauBilanMensuel[$mois]['REPORT_TRESO'] + $TableauBilanMensuel[$mois]['ENCAISSEMENT'] ) < ( $TableauBilanMensuel[$mois]['DEPENSE'] ) * -1 ) {
            $danger[$indice_danger]="Il n'y a pas assez de trésorerie disponible ou d'encaissement pour régler vos dépenses en " . NumToMois($num_mois) . "."; $indice_danger++;
        }        
        // On check le Solde
        if ($TableauBilanMensuel[$mois]['SOLDE'] < 0) {
            $warning[$indice_warning]="Vos dépenses de " . NumToMois($num_mois) . " sont supérieures aux recettes."; $indice_warning++;
        }
        // On check les paiements echus
        if ($TableauBilanMensuel[$mois]['ECHUS'] > 0) {
            $warning[$indice_warning]="Vous avez des paiements non encaissés en " . NumToMois($num_mois) . ", pensez à faire vos encaissements ou vos relances."; $indice_warning++;
        }
    } // For            

// Analyse du bilan Annuel

    // On check la treso
    if ($TableauBilanAnnuel['REPORT_TRESO'] < 0) {
        $danger[$indice_danger]="Votre trésorerie de fin d'année est négative (". number_format($TableauBilanAnnuel['REPORT_TRESO'],2,',','.') . "€), vous devez rembourser sur votre compte professionel de la même somme."; $indice_danger++;
    }
    // On check le report salaire
    if ($TableauBilanAnnuel['REPORT_SALAIRE'] < 0) {
        $danger[$indice_danger]="Problème, le report de salaire de fin d'année est négatif : (". number_format($TableauBilanAnnuel['REPORT_SALAIRE'],2,',','.') . "€), veuillez vérifier le bilan pour voir d'où vient ce problème."; $indice_danger++;
    }
    // On check le salaire
    if ($TableauBilanAnnuel['SOLDE'] > 0 && $TableauBilanAnnuel['SALAIRE'] = 0) {
        $danger[$indice_danger]="Malgrès votre activité, vous ne pouvez pas vour verser de salaire cette année."; $indice_danger++;
    }
    // On check le Solde
    if ($TableauBilanAnnuel['SOLDE'] < 0) {
        $danger[$indice_danger]="Vos dépenses sont supérieures à vos recettes cette année."; $indice_danger++;
    }    
    // On check le report salaire
    if ($TableauBilanAnnuel['REPORT_SALAIRE'] > 0) {
        $warning[$indice_warning]="Il y a un report de salaire de fin d'année : (". number_format($TableauBilanAnnuel['REPORT_SALAIRE'],2,',','.') . "€), vous n'avez pas eu assez de trésorerie pour vous servir sur le dernier mois..."; $indice_warning++;
    }   
    // On check les paiements echus
    if ($TableauBilanAnnuel['ECHUS'] > 0) {
        $warning[$indice_warning]="Vous avez des paiements non encaissés sur l'année, pensez à faire vos encaissements ou vos relances."; $indice_warning++;
    }
    // On check la treso
    if ($TableauBilanAnnuel['TRESO'] > $exercice_treso) {
        $ok[$indice_ok]="Félicitation, vous avez une trésorerie de fin d'année supérieur à celle de début d'année."; $indice_ok++;
    }
    
    
?>

<!DOCTYPE html>
<html lang="fr">
<?php require 'head.php'; ?>

<body>

    <?php $page_courante = "bilan_analyse.php"; require 'nav.php'; ?>
        
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

        <!-- Affiche le header -->
        <div class="page-header">          
            <h2>Analyse de votre bilan  : <button type="button" class="btn btn-lg btn-info"><?php echo "$exercice_annee - " . ($exercice_annee +1); ?></button></h2>  
        </div>
        
        <div class="panel panel-success">
            <div class="panel-heading">
              <h3 class="panel-title">Bravo!</h3>
            </div>
            <div class="panel-body">
            <ul class="lead">    
            <?php    
            foreach ($ok as $ligne) {
                echo '<li>' . $ligne . '</li>'; 
            }
            ?>
            </ul>
            </div>
        </div>

        <div class="panel panel-warning">
            <div class="panel-heading">
              <h3 class="panel-title">Problèmes</h3>
            </div>
            <div class="panel-body">
            <ul class="lead">                   
            <?php    
            foreach ($warning as $ligne) {
                echo '<li>' . $ligne . '</li>'; 
            }
            ?>
            </ul>
            </div>
        </div>

        <div class="panel panel-danger">
            <div class="panel-heading">
              <h3 class="panel-title">Alertes</h3>
            </div>
            <div class="panel-body">
            <ul class="lead">                   
            <?php    
            foreach ($danger as $ligne) {
                echo '<li>' . $ligne . '</li>'; 
            }
            ?>
            </ul>
            </div>
        </div>
        <hr>

    </div> <!-- /container -->

    <?php require 'footer.php'; ?>
        
  </body>
</html>