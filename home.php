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

// Charge le Bilan    
    $TableauBilanMensuel = CalculBilanMensuel($user_id, $exercice_id, $exercice_treso);

    for ($mois = 1; $mois <= 12; $mois++) {
        $num_mois = MoisAnnee($mois, $exercice_mois); 
        $MOIS[$mois-1]= NumToMois($num_mois);
        $CA[$mois-1]=$TableauBilanMensuel[$mois]['CA'];
        $DEPENSE[$mois-1]=$TableauBilanMensuel[$mois]['DEPENSE'] * -1;
        $SALAIRE[$mois-1]=$TableauBilanMensuel[$mois]['SALAIRE'];
        $TRESO[$mois-1]=$TableauBilanMensuel[$mois]['REPORT_TRESO'];        
    } // For
                  
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
        <div class="alert alert-info">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <strong><?php echo "Exercice Courant : $exercice_annee démarrant en " . NumToMois($exercice_mois) . ", trésorerie de départ de $exercice_treso €"; ?></strong><br> 
        </div>   
	    <?php       
        }   
        ?>          
        
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
            Tab:<br>
            <pre><?php var_dump($TableauBilanMensuel); ?></pre>    
            JSON:<br>
            <pre><?php var_dump(json_encode($CA,JSON_NUMERIC_CHECK  )); ?></pre>             
                   
        </div>
        <?php       
        }   
        ?> 
        
        <!-- Affiche un chart Bar -->
        <div align="center">        
            <canvas id="canvasBar" height="300" width="800">
                <div class="alert alert alert-info alert-dismissable fade in">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <strong>Votre navigateur est obsolète pour visualiser les graphiques, veuiller utiliser un navigateur plus réçent.</strong><br>
                    <a href="http://browsehappy.com/" target="_blank" class="btn btn-primary" role="button">Choisir un nouveau Navigateur</a>  
                 </div>
            </canvas>
        </div>
        <table align="center" style="margin-top:20px;">
            <tr>
              <td width="18" bgcolor="#DCDCDC">&nbsp;</td><td>&nbsp;CA</td><td width="20">&nbsp;</td>
              <td width="18" bgcolor="#8B008B">&nbsp;</td><td>&nbsp;Dépenses</td><td width="20">&nbsp;</td>
              <td width="18" bgcolor="#32CD32">&nbsp;</td><td>&nbsp;Salaire</td><td width="20">&nbsp;</td>              
              <td width="18" bgcolor="#97BBCD">&nbsp;</td><td>&nbsp;Tréso</td>              
            </tr>
        </table>    

        <!-- Affiche un chart Line -->
        <div align="center">        
            <canvas id="canvasLine1" height="300" width="800">
                <div class="alert alert alert-info alert-dismissable fade in">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <strong>Votre navigateur est obsolète pour visualiser les graphiques, veuiller utiliser un navigateur plus réçent.</strong><br>
                    <a href="http://browsehappy.com/" target="_blank" class="btn btn-primary" role="button">Choisir un nouveau Navigateur</a>  
                 </div>
            </canvas>
        </div>
        <table align="center" style="margin-top:20px;">
            <tr>
              <td width="18" bgcolor="#DCDCDC">&nbsp;</td><td>&nbsp;CA</td><td width="20">&nbsp;</td>
            
            </tr>
        </table>  

        <!-- Affiche un chart Line -->
        <div align="center">        
            <canvas id="canvasLine2" height="300" width="800">
                <div class="alert alert alert-info alert-dismissable fade in">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <strong>Votre navigateur est obsolète pour visualiser les graphiques, veuiller utiliser un navigateur plus réçent.</strong><br>
                    <a href="http://browsehappy.com/" target="_blank" class="btn btn-primary" role="button">Choisir un nouveau Navigateur</a>  
                 </div>
            </canvas>
        </div>
        <table align="center" style="margin-top:20px;">
            <tr>

              <td width="18" bgcolor="#8B008B">&nbsp;</td><td>&nbsp;Dépenses</td><td width="20">&nbsp;</td>
             
            </tr>
        </table>          


        <!-- Affiche un chart Line -->
        <div align="center">        
            <canvas id="canvasLine3" height="300" width="800">
                <div class="alert alert alert-info alert-dismissable fade in">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <strong>Votre navigateur est obsolète pour visualiser les graphiques, veuiller utiliser un navigateur plus réçent.</strong><br>
                    <a href="http://browsehappy.com/" target="_blank" class="btn btn-primary" role="button">Choisir un nouveau Navigateur</a>  
                 </div>
            </canvas>
        </div>
        <table align="center" style="margin-top:20px;">
            <tr>
 
              <td width="18" bgcolor="#32CD32">&nbsp;</td><td>&nbsp;Salaire</td><td width="20">&nbsp;</td>              
             
            </tr>
        </table>  
        
        <!-- Affiche un chart Line -->
        <div align="center">        
            <canvas id="canvasLine4" height="300" width="800">
                <div class="alert alert alert-info alert-dismissable fade in">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <strong>Votre navigateur est obsolète pour visualiser les graphiques, veuiller utiliser un navigateur plus réçent.</strong><br>
                    <a href="http://browsehappy.com/" target="_blank" class="btn btn-primary" role="button">Choisir un nouveau Navigateur</a>  
                 </div>
            </canvas>
        </div>
        <table align="center" style="margin-top:20px;">
            <tr>
           
              <td width="18" bgcolor="#97BBCD">&nbsp;</td><td>&nbsp;Tréso</td>              
            </tr>
        </table>  
    </div> <!-- /container -->
    
    <?php require 'footer.php'; ?>

    <!-- Description et donnees des charts -->
    <script>

        var barChartData = {
            labels : <?php echo json_encode($MOIS); ?>,      
            datasets : [
                {
                    fillColor : "rgba(220,220,220,0.5)",
                    strokeColor : "rgba(220,220,220,1)",
                    data : <?php echo json_encode($CA, JSON_NUMERIC_CHECK ); ?>
                },
                {
                    fillColor : "rgba(139,0,139,0.5)",
                    strokeColor : "rgba(139,0,139,1)",
                    data : <?php echo json_encode($DEPENSE, JSON_NUMERIC_CHECK ); ?>
                },
                {
                    fillColor : "rgba(50,205,50,0.5)",
                    strokeColor : "rgba(50,205,50,1)",
                    data : <?php echo json_encode($SALAIRE, JSON_NUMERIC_CHECK ); ?>
                },
                {
                    fillColor : "rgba(151,187,205,0.5)",
                    strokeColor : "rgba(151,187,205,1)",
                    data : <?php echo json_encode($TRESO, JSON_NUMERIC_CHECK ); ?>
                }
            ]
            
        }

        var lineChartData1 = {
            labels : <?php echo json_encode($MOIS); ?>,      
            datasets : [
                {
                    fillColor : "rgba(220,220,220,0.5)",
                    strokeColor : "rgba(220,220,220,1)",
                    pointColor : "rgba(220,220,220,1)",
                    pointStrokeColor : "#fff",
                    data : <?php echo json_encode($CA, JSON_NUMERIC_CHECK ); ?>
                }
            ]
            
        }
        var lineChartData2 = {
            labels : <?php echo json_encode($MOIS); ?>,      
            datasets : [
                {
                    fillColor : "rgba(139,0,139,0.5)",
                    strokeColor : "rgba(139,0,139,1)",
                    pointColor : "rgba(139,0,139,1)",
                    pointStrokeColor : "#fff",                    
                    data : <?php echo json_encode($DEPENSE, JSON_NUMERIC_CHECK ); ?>
                }
            ]
            
        }
        var lineChartData3 = {
            labels : <?php echo json_encode($MOIS); ?>,      
            datasets : [
                {
                    fillColor : "rgba(50,205,50,0.5)",
                    strokeColor : "rgba(50,205,50,1)",
                    pointColor : "rgba(50,205,50,1)",
                    pointStrokeColor : "#fff",
                    data : <?php echo json_encode($SALAIRE, JSON_NUMERIC_CHECK ); ?>
                }
            ]
            
        }
        var lineChartData4 = {
            labels : <?php echo json_encode($MOIS); ?>,      
            datasets : [
                {
                    fillColor : "rgba(151,187,205,0.5)",
                    strokeColor : "rgba(151,187,205,1)",
                    pointColor : "rgba(151,187,205,1)",
                    pointStrokeColor : "#fff",
                    data : <?php echo json_encode($TRESO, JSON_NUMERIC_CHECK ); ?>
                }
            ]
            
        }
        
    var myBarChart = new Chart(document.getElementById("canvasBar").getContext("2d")).Bar(barChartData);
    var myLineChart1 = new Chart(document.getElementById("canvasLine1").getContext("2d")).Line(lineChartData1);
    var myLineChart2 = new Chart(document.getElementById("canvasLine2").getContext("2d")).Line(lineChartData2);    
    var myLineChart3 = new Chart(document.getElementById("canvasLine3").getContext("2d")).Line(lineChartData3);    
    var myLineChart4 = new Chart(document.getElementById("canvasLine4").getContext("2d")).Line(lineChartData4);                    
   
    </script>    
        
  </body>
</html>