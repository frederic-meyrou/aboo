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
              <a class="navbar-brand" href="http://www.aboo.fr">Aboo</a>
	      </div>     
	      <!-- Liens -->
	      <div class="collapse navbar-collapse" id="TOP">
	        <ul class="nav navbar-nav">
	        </ul>
	      </div><!-- /.navbar-collapse -->
      </div>	      
    </nav>    

    <div class="page-header">   
       <h2>Page d'erreur</h2>     
    </div>
    
        <div class="alert alert-danger alert-dismissable fade in">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <strong>Erreur 403 :</strong> La page que vous avez demandé est indisponible!
        </div> 

    </div> <!-- /container -->
       
    <?php require 'footer.php'; ?>    

  </body>
</html>