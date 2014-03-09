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

// Mode Debug
	$debug = true;

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
 		
?>

<!DOCTYPE html>
<html lang="fr">
<?php require 'head.php'; ?>

<body>

    <?php $page_courante = "infos.php"; require 'nav.php'; ?>
        
    <div class="container">

        <div class="page-header">   
            <h2>Informations</h2>        
        </div>
        
		<!-- Infos de version -->
		<div class="panel-group" id="accordion">

		  <div class="panel panel-default">
		    <div class="panel-heading">
		      <h4 class="panel-title">
		        <a data-toggle="collapse" data-parent="#accordion" href="#collapse4">
		          <strong>Aboo</strong> <span class="badge ">V0.4</span>
		        </a>
		      </h4>
		    </div>
		    <div id="collapse4" class="panel-collapse collapse in">
		      <div class="panel-body">
		        <li> MàJ Schema BDD pour gestion salaire et la double gestion du CA</li>
		        <li> Ajout de la page de gestion du salaire</li>
		        <li> Ajout de la page calcul fond de roulement</li>
		        <li> Ajout de la page journal annuel</li>
		        <li> Ajout de la page information</li>
		        <li> Ajout des pages de création de compte, Activation de compte et oubli de mot de passe</li>			        			        		        
		        <li> Ajout de la double gestion du CA (déclaré / non déclaré)</li>
		        <li> Ajout de l'affichage des charts (page tableau de bord)</li>
		        <li> Ajout test d'export CSV</li>		        		        
		        <li> Externalisation des modales</li>
		        <li> Corrections bug d'affichage clients dans formulaire recette</li>
		        <li> Correction bug modale formulaire recette</li>
		        <li> Renommage des pages Exercice</li>
		        <li> Ajout du footer</li>
		        <li> Ajout des FavIcon tout supports</li>		        
		        <li> Upgrade BootStrap</li>
		        <li> MàJ plugIn WP</li>
		      </div>
		    </div>
		  </div>
			
		  <div class="panel panel-default">
		    <div class="panel-heading">
		      <h4 class="panel-title">
		        <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">
		          <strong>Aboo</strong> <span class="badge ">V0.3</span>
		        </a>
		      </h4>
		    </div>
		    <div id="collapse3" class="panel-collapse collapse">
		      <div class="panel-body">
		        <li> MàJ Schema BDD</li>
		        <li> Ajout de l'aide contextuelle</li>
		        <li> Ajout de la page Analyse du bilan</li>
		        <li> Ajoute du module dataTable dans toutes les pages avec des tables</li>
		        <li> Mise à niveau du design de toutes les pages</li>
		        <li> Modif navigation interne journal/depense/recette</li>
		      </div>
		    </div>
		  </div>
		  
		  <div class="panel panel-default">
		    <div class="panel-heading">
		      <h4 class="panel-title">
		        <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">
		          <strong>Aboo</strong> <span class="badge ">V0.2</span>
		        </a>
		      </h4>
		    </div>
		    <div id="collapse2" class="panel-collapse collapse">
		      <div class="panel-body">
		        <li> Ajout de popup modales sur suppresion</li>
		        <li> Fonctions de calcul du Bilan mensuel et annuel</li>
		        <li> Google Fonts pour Logo, Ajout Font Titillium contenu et menus</li>
		        <li> Montée de version WP 3.8.1 et Plugins</li>
		        <li> Pages Bilan annuel et Mensuel</li>
		        <li> Correctif de calcul du salaire</li>
		      </div>
		    </div>
		  </div>
		  
		  <div class="panel panel-default">
		    <div class="panel-heading">
		      <h4 class="panel-title">
		        <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">
		          <strong>Aboo</strong> <span class="badge ">V0.1</span>
		        </a>
		      </h4>
		    </div>
		    <div id="collapse1" class="panel-collapse collapse">
		      <div class="panel-body">
		        <strong>Première version stable :</strong>
		        <li> Trigger de suppresion des recettes/paiements</li>
		        <li> Mise en place de popup modales</li>
		        <li> Mise en commun de la navigation / header / footer</li>
		        <li> Ajout module d'export PDF</li>
		        <li> Gestion des clients</li>
		        <li> Gestion des paiements</li>
		        <li> Gestion des utilisateurs</li>
		        <li> Formulaire de paiement étalé</li>
		        <li> Bilan Mensuel</li>
		        <li> Gestion de la ventillation des  abonnements</li>
		        <li> Formaulaires Abonnements et Dépenses</li>
		        <li> Gestion Authentification</li>
		        <li> Ajout du FrameWork BootStrap</li>
		        <li> Crèation du site WordPress vitrine temporaire (template / plugin / contenu)</li>
		        <li> Modèlisation BDD</li>
		      </div>
		    </div>
		  </div>
		  
		</div>     
        <br>   

		<!-- Specs de Aboo -->
		<div class="panel panel-info">
			  <div class="panel-heading"><h4>Spécifications : </h4></div>
	              <div class="panel-body">
	                    <li> CA(M) = Recettes(M) : Chiffre d'affaire</li>
	                    <li> Dépenses(M) = Dépenses</strong>
	                    <li> Solde Brut (M) = CA (M) - Dépenses (M)</li>
	                    <li> Ventillation (M) = Recettes de l'exercice ventillées sur le mois courant</li>
	                    <li> Paiement (M) = Total des echéances des abonements étalés sur le mois courant + Recettes à régler</li>
	                    <li> Paiement echus (M) = Total des paiements à encaisser sur le mois courant</li>
	                    <li> Encaissement (M) = Total des paiements encaissés + recettes du mois courant payées</li>
	                    <li> Salaire (M) = Montant affecté au salaire = [Trésorerie (M-1) + Encaissement (M)] - [Ventilation (M) - Dépenses (M)]</li>
	                    <li> Trésorerie (M) = Montant de la trésorerie avant salaire = Encaissement (M)  - Dépenses (M)  + Trésorerie (M-1) </li>
	                    <li> Report Tréso (M) = Montant de la trésorerie de fin de mois = Trésorerie (M) - Salaire (M)</li>
	                    <li> Report Salaire (M) = Salaire non versé reporté au mois suivant</li>
	              </div>
	          </div>        
	    </div>       
        <br>   

             
    </div> <!-- /container -->
    
    <?php require 'footer.php'; ?>    

  </body>
</html>