<!-- 
© Copyright : Aboo / www.aboo.fr : Frédéric MEYROU : tous droits réservés
-->

<!DOCTYPE html>
<html lang="fr">
<?php require 'head.php'; ?>
<body>
    
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
          <a class="navbar-brand" href="aboo.php">Aboo</a>
      </div>     
      <!-- Liens -->
      <div class="collapse navbar-collapse" id="TOP">
        <ul class="nav navbar-nav">
          <li><a href="connexion.php"><span class="glyphicon glyphicon-off"></span> Connexion Classique</a></li>
          <li><a href="connexion2.php"><span class="glyphicon glyphicon-off"></span> Connexion Modale</a></li>                                    
        </ul>
      </div><!-- /.navbar-collapse -->
    </nav>    
	

    <div class="container">

        <div class="alert alert alert-success alert-dismissable fade in">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <strong>Bonjour!</strong> Bienvenue sur l'application Aboo!
        </div>

    </div> <!-- /container -->
       
    <?php require 'footer.php'; ?>    

  </body>
</html>