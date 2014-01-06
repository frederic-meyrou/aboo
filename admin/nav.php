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
      <a class="navbar-brand" href="../home.php">Aboo</a>
  </div>     
  <!-- Liens -->
  <div class="collapse navbar-collapse" id="TOP">
    <ul class="nav navbar-nav">
      <li<?php echo ($page_courante == "user.php")?' class="active"':'';?>><a href="user.php"><span class="glyphicon glyphicon-th-list"></span> Gestion utilisateurs</a></li>                          
      <li class="dropdown">
        <!-- Affiche le nom de l'utilisateur à droite de la barre de Menu -->
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> <?php echo ucfirst($prenom) . ' ' . ucfirst($nom); ?><b class="caret"></b></a>
        <ul class="dropdown-menu">
          <li><a href="../deconnexion.php"><span class="glyphicon glyphicon-off"></span> Deconnexion</a></li>
        </ul> 
      </li>
      <li><a href="../deconnexion.php"><span class="glyphicon glyphicon-off"></span></a></li>      
    </ul>
  </div><!-- /.navbar-collapse -->
</nav>
