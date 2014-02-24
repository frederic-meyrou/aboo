<?php
    if (!isset($page_courante)) {
        $page_courante = null;
    }
    if (!isset($nom) || !isset($prenom)) {
        $prenom = 'utilisateur';
        $nom = 'inconnu';
    }    
?>

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
      <li<?php echo ($page_courante == "journal.php")?' class="active"':'';?>><a href="journal.php"><span class="glyphicon glyphicon-th-list"></span> Recettes & Dépenses</a></li>
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-calendar"></span> Bilan<b class="caret"></b></a>
        <ul class="dropdown-menu">
              <li<?php echo ($page_courante == "bilan_mensuel.php")?' class="active"':'';?>><a href="bilan_mensuel.php"><span class="glyphicon glyphicon-calendar"></span> Tableau de bord mensuel</a></li>
              <li<?php echo ($page_courante == "bilan_annuel.php")?' class="active"':'';?>><a href="bilan_annuel.php"><span class="glyphicon glyphicon-calendar"></span> Bilan annuel</a></li>
              <li<?php echo ($page_courante == "bilan_analyse.php")?' class="active"':'';?>><a href="bilan_analyse.php"><span class="glyphicon glyphicon-calendar"></span> Analyse</a></li>
              <li<?php echo ($page_courante == "fond_de_roulement.php")?' class="active"':'';?>><a href="fond_de_roulement.php"><span class="glyphicon glyphicon-calendar"></span> Fond de roulement</a></li>
        </ul> 
      </li>
      <li<?php echo ($page_courante == "paiements.php")?' class="active"':'';?>><a href="paiements.php"><span class="glyphicon glyphicon-euro"></span> Paiements</a></li>
      <li<?php echo ($page_courante == "mesclients.php")?' class="active"':'';?>><a href="mesclients.php"><span class="glyphicon glyphicon-star"></span> Clients</a></li>                           
      <li class="dropdown">
        <!-- Affiche le nom de l'utilisateur à droite de la barre de Menu -->
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> <?php echo ucfirst($prenom) . ' ' . ucfirst($nom); ?><b class="caret"></b></a>
        <ul class="dropdown-menu">
          <li<?php echo ($page_courante == "exercice.php")?' class="active"':'';?>><a href="exercice.php"><span class="glyphicon glyphicon-wrench"></span> Exercice</a></li>
          <li<?php echo ($page_courante == "debug.php")?' class="active"':'';?>><a href="debug.php"><span class="glyphicon glyphicon-eye-open"></span> Debug</a></li>  
          <li<?php echo ($page_courante == "infos.php")?' class="active"':'';?>><a href="infos.php"><span class="glyphicon glyphicon-info-sign"></span> Informations</a></li>  
          <li><a href="deconnexion.php"><span class="glyphicon glyphicon-off"></span> Deconnexion</a></li>
        </ul> 
      </li>
      <li><a href="deconnexion.php"><span class="glyphicon glyphicon-off"></span></a></li>      
    </ul>
  </div><!-- /.navbar-collapse -->
</nav>