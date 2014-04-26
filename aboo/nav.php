<?php
    require_once('lib/fonctions.php');
    if (!isset($page_courante)) {
        $page_courante = null;
    }
    if (!isset($nom) || !isset($prenom)) {
        $prenom = 'utilisateur';
        $nom = 'inconnu';
    }    
?>

<!-- Affiche la navigation -->
<div class="navbar-wrapper">
    <div class="container">
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="container">          
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
                  <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-list"></span> Journal<b class="caret"></b></a>
                    <ul class="dropdown-menu">
	                  <li<?php echo ($page_courante == "recette.php")?' class="active"':'';?>><a href="recette.php"><span class="glyphicon glyphicon-plus-sign"></span> Recettes & Abonnements</a></li>
	                  <li<?php echo ($page_courante == "depense.php")?' class="active"':'';?>><a href="depense.php"><span class="glyphicon glyphicon-minus-sign"></span> Dépenses & Charges</a></li>
	                  <li<?php echo ($page_courante == "journal.php")?' class="active"':'';?>><a href="journal.php"><span class="glyphicon glyphicon-th-list"></span> Journal d'Activité Mensuel</a></li>
	                  <li<?php echo ($page_courante == "journal_annuel.php")?' class="active"':'';?>><a href="journal_annuel.php"><span class="glyphicon glyphicon-th-list"></span> Journal d'Activité Annuel</a></li>
	                  <li<?php echo ($page_courante == "journal_fiscal.php")?' class="active"':'';?>><a href="journal_fiscal.php"><span class="glyphicon glyphicon-th-list"></span> Journal Fiscal</a></li>
                      <li<?php echo ($page_courante == "journal_fiscal_bis.php")?' class="active"':'';?>><a href="journal_fiscal_bis.php"><span class="glyphicon glyphicon-th-list"></span> Journal Fiscal BIS</a></li>
                    </ul> 
                  </li>
                  <li<?php echo ($page_courante == "salaire.php")?' class="active"':'';?>><a href="salaire.php"><span class="glyphicon glyphicon-euro"></span> Salaire</a></li>
                  <?php if ($_SESSION['options']['gestion_social']) { ?>    
                  <li<?php echo ($page_courante == "charges.php")?' class="active"':'';?>><a href="charges.php"><span class="glyphicon glyphicon-bookmark"></span> Social</a></li>
                  <?php } ?>
                  <li<?php echo ($page_courante == "paiements.php")?' class="active"':'';?>><a href="paiements.php"><span class="glyphicon glyphicon-credit-card"></span> Paiements</a></li>
                  <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-calendar"></span> Bilan<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                          <li<?php echo ($page_courante == "tableau_de_bord.php")?' class="active"':'';?>><a href="tableau_de_bord.php"><span class="glyphicon glyphicon-dashboard"></span> Statistiques</a></li>
                          <li<?php echo ($page_courante == "bilan_mensuel.php")?' class="active"':'';?>><a href="bilan_mensuel.php"><span class="glyphicon glyphicon-th"></span> Bilan mensuel</a></li>
                          <li<?php echo ($page_courante == "bilan_annuel.php")?' class="active"':'';?>><a href="bilan_annuel.php"><span class="glyphicon glyphicon-calendar"></span> Bilan annuel</a></li>
                          <li<?php echo ($page_courante == "bilan_analyse.php")?' class="active"':'';?>><a href="bilan_analyse.php"><span class="glyphicon glyphicon-cog"></span> Analyse</a></li>
                          <li<?php echo ($page_courante == "fond_de_roulement.php")?' class="active"':'';?>><a href="fond_de_roulement.php"><span class="glyphicon glyphicon-repeat"></span> Fond de roulement</a></li>
                    </ul> 
                  </li>
                  <li<?php echo ($page_courante == "mesclients.php")?' class="active"':'';?>><a href="mesclients.php"><span class="glyphicon glyphicon-star"></span> Clients</a></li>                           
                  <li<?php echo ($page_courante == "paypal.php")?' class="active"':'';?>><a href="paypal.php"><span class="glyphicon glyphicon-euro"></span> Paypal</a></li>
                  <li class="dropdown">
                    <!-- Affiche le nom de l'utilisateur à droite de la barre de Menu -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="<?php echo (IsAdmin())?'text-danger':''; ?> glyphicon glyphicon-user"></span> <?php echo ucfirst($prenom) . ' ' . ucfirst($nom); ?><b class="caret"></b></a>
                    <ul class="dropdown-menu">
                      <?php if (IsAdmin()) { ?>
                      <li<?php echo ($page_courante == "user.php")?' class="active"':'';?>><a href="user.php"><span class="glyphicon glyphicon-tags"></span>  Gestion utilisateurs</a></li>                          
                      <li<?php echo ($page_courante == "backup.php")?' class="active"':'';?>><a href="lib/backup.php"><span class="glyphicon glyphicon-cog"></span>  Sauvegarde BDD</a></li>                          
                      <?php } ?>
                      <li<?php echo ($page_courante == "exercice.php")?' class="active"':'';?>><a href="exercice.php"><span class="glyphicon glyphicon-calendar"></span> Exercice</a></li>
                      <li<?php echo ($page_courante == "configuration.php")?' class="active"':'';?>><a href="configuration.php"><span class="glyphicon glyphicon-wrench"></span> Configuration</a></li>
                      <li<?php echo ($page_courante == "debug.php")?' class="active"':'';?>><a href="debug.php"><span class="glyphicon glyphicon-eye-open"></span> Debug</a></li>  
                      <li<?php echo ($page_courante == "infos.php")?' class="active"':'';?>><a href="infos.php"><span class="glyphicon glyphicon-info-sign"></span> Informations</a></li>  
                      <li><a href="deconnexion.php"><span class="glyphicon glyphicon-off"></span> Deconnexion</a></li>
                    </ul> 
                  </li>
                  <li><a href="deconnexion.php"><span class="glyphicon glyphicon-off"></span></a></li>      
                </ul>
              </div><!-- /.navbar-collapse -->
          </div><!-- /container -->
        </nav>
    </div><!-- /container -->
</div><!-- /.navbar-wrapper -->                  