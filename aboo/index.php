<!-- 
© Copyright : Aboo / www.aboo.fr : Frédéric MEYROU : tous droits réservés
-->

<!DOCTYPE html>
<html lang="fr">
<?php require 'head.php'; ?>

<body>
	
	<div class="container">
    
    <!-- Affiche la navigation -->
    <nav class="navbar navbar-inverse" role="navigation">   
       <div class="container">      	   
	      <div class="navbar-header">
	          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
	            <span class="sr-only">Toggle navigation</span>
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	          </button>
	          <!-- Marque -->
	          <a class="navbar-brand" href="../index.php">Aboo</a>
	      </div>     
	      <!-- Liens -->
	      <div class="collapse navbar-collapse" id="TOP">
	        <ul class="nav navbar-nav">
	          <li><a href="connexion.php"><span class="glyphicon glyphicon-off"></span> Connexion Classique</a></li>
	          <li><a href="connexion2.php"><span class="glyphicon glyphicon-off"></span> Connexion Modale</a></li>                                    
	        </ul>
	      </div><!-- /.navbar-collapse -->
      </div>	      
    </nav>    


        <div class="alert alert alert-success alert-dismissable fade in">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <strong>Bonjour!</strong> Bienvenue sur l'application Aboo!
        </div>
        
    <!--<p class="test"> Aboo </p>-->    
       
    <?php require 'footer.php'; ?>   
    </div> <!-- /container -->

  </body>
</html>