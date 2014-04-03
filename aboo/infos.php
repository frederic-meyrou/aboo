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
		    <div id="collapse4" class="panel-collapse collapse">
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
			  <div class="panel-heading"><h4>Vocabulaire & Spécifications : </h4></div>
	              <div class="panel-body">
	                    <li> CA : Chiffre d'Affaire </li>
	                    <li> Recettes : Paiements étalés encaissés + Recettes au contant (payées)</li>
	                    <li> Dépenses : Dépenses / Frais / Taxes du mois courant (hors charges sociales)</li>
	                    <li> Charges  : Charges sociales payées</li>                    
	                    <li> Solde Brut : CA - Dépenses - Charges </li>
	                    <li> Bénéfice : Recettes - Dépenses </li>
	                    <li> Ventillation : CA des abonnements ventillés sur le mois courant</li>
	                    <li> Paiement : Echéances des abonnements en paiement étalé sur le mois courant + Recettes à régler</li>
	                    <li> Paiement echus : Créances de paiement sur le mois courant (non encaissé)</li>
	                    <li> Trésorerie : Trésorerie disponible avant paiement du salaire</li> 
	                    <li> Salaire : Salaire calculé automatiquement par Aboo en fonction de la ventilation, des dépenses et de la trésorerie</li>
	                    <li> Salaire Réel : Salaire réelement versé</li>
	                    <li> Charges calculées : Charges sociales calculées par Aboo en fonction du statut fiscal de l'entreprise et si l'option "Charges sociales" est activée</li>
	                    <li> Provisions pour Charges : Charges sociales provisionnées calculées par Aboo en fonction de la trésorerie disponible</li>
	                    <li> Provisions pour Charges Réelles : Charges sociales réellement provisionnées</li> 
	                    <li> Report Tréso : Trésorerie disponible après paiement du salaire</li>
	                    <li> Report Salaire : Salaire non versé reporté au mois suivant</li> 
						<hr>
						<li> Trésorerie = Trésorerie(M-1) + Recettes(M) - Dépenses(M) - Charges(M) - ProvisionReelles(M) </li>	                  
	                    <li> Salaire : Si Ventil(M) > (Trésorerie(M) <br>
	                    	           Alors : Salaire = Trésorerie(M) - ( Dépenses(M) + Charges(M) )<br>
	                    	 		   Sinon : Salaire = Ventil(M) - ( Dépenses(M) + Charges(M) )
	                    </li>
	                    <li> Report Salaire : Si ( Ventil(M) + Report_Salaire(M-1) ) > Trésorerie(M) <br> 
	                    	           Alors : Report_Salaire(M) = 0 <br>
	                    	 		   Sinon : Report_Salaire(M) = Report_Salaire(M-1) + ( Ventil(M) - Trésorerie(M) )
	                    </li>	                    
	                    <li> Report Tréso = Trésorerie(M) - Salaire_Reel(M)</li>
	              </div>
	          </div>        
	    </div>       
        <br>   

             
    </div> <!-- /container -->
    
    <?php require 'footer.php'; ?>    

  </body>
</html>