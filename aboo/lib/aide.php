<!-- Variable PHP à définir avant incude : $IDModale -->
<?php 
    if (!isset($IDModale)) {
        $IDModale = "modalAide";
    } 
?>

<!-- Modal Aide-->
<div class="modal fade" id="<?php echo $IDModale; ?>" tabindex="-1" role="dialog" aria-labelledby="AideModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3 class="modal-title" id="AideModalLabel">Aide contextuelle</h3>
          </div><!-- /.modal-header -->
          <div class="modal-body">
<?php

switch ($IDModale) {
    case "modalAideActions":
        echo '<a href="#" class="btn btn-default btn-info glyphicon glyphicon-star" role="button"> </a>  ' . "Cette icône permet d'afficher le détail. <br>";
        echo '<br>';
        echo '<a href="#" class="btn btn-default btn-warning glyphicon glyphicon-edit" role="button"> </a>  ' . "Cette icône permet de modifier. <br>";
        echo '<br>';
        echo '<a href="#" class="btn btn-default btn-danger glyphicon glyphicon-trash" role="button"> </a>  ' . "Cette icône permet de supprimer.";
        break;
    case "modalAideFormRecette":
        ?>
            <h4>Le formulaire d'ajout de recette vous permet d'ajouter en séquence toutes vos recettes :</h4>
            
            <div class="panel-group" id="accordion">
			
			  <div class="panel panel-default">
			    <div class="panel-heading">
			      <h4 class="panel-title">
			        <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">
			          Type de recette :
			        </a>
			      </h4>
			    </div>
			    <div id="collapse1" class="panel-collapse collapse in">
			      <div class="panel-body">
		            <p>Vos recettes peuvent être de différentes natures :</p>
		            <ul>
		              <li><strong>Abonnement</strong> (Par défaut) : Recette liée à la vente d'un abonnement</li>
		              <li><strong>Revente</strong> : Achat / Revente</li>
		              <li><strong>Location</strong> : Si vous louez ou sous-louez votre local professionel</li>
		              <li><strong>Autre</strong> : autres types de revenu (intérêts, etc...)</li>
		            </ul>
			      </div>
			    </div>
			  </div>

			  <div class="panel panel-default">
			    <div class="panel-heading">
			      <h4 class="panel-title">
			        <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">
			          La périodicité de la recette :
			        </a>
			      </h4>
			    </div>
			    <div id="collapse2" class="panel-collapse collapse">
			      <div class="panel-body">
		            <p>Ce sélecteur sert uniquement aux recettes de type "Abonnement" peux avoir les périodicités suivantes :</p>
		            <ul>
		              <li><strong>Pontuel</strong> (Par défaut) : La recette ou l'abonnement est pour le mois courant</li>
		              <li><strong>Bi-Mensuel</strong> : L'abonnement est d'une durée de 2 mois</li>
		              <li><strong>Trimestriel</strong> : L'abonnement est d'une durée de 3 mois</li>
		              <li><strong>Semestriel</strong> : L'abonnement est d'une durée de 6 mois</li>
		              <li><strong>Annuel ou Lissé</strong> : L'abonnement est d'une durée de 12 mois ou alors réparti sur le nombre de mois restant de l'exercice</li>
		            </ul>
			      </div>
			    </div>
			  </div>

			  <div class="panel panel-default">
			    <div class="panel-heading">
			      <h4 class="panel-title">
			        <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">
			          Réglement :
			        </a>
			      </h4>
			    </div>
			    <div id="collapse3" class="panel-collapse collapse">
			      <div class="panel-body">
		            <p>Vous pouvez sélectionner le réglement de votre recette selon 3 modalités :</p>
		            <ul>
		              <li><strong>Réglé</strong> (Par défaut) : La recette est réglée au comptant</li>
		              <li><strong>A régler</strong> : La recette est en attente de réglement</li>
		              <li><p><strong>Paiement étalé</strong> : Le paiement de la recette est étalé dans le temps selon un calendrier. 
		              Lorsque ce choix est fait, un formulaire suplémentaire apparait après avoir appuyé sur le bouton "Ajout"</p></li>
		            </ul>
			      </div>
			    </div>
			  </div>

			  <div class="panel panel-default">
			    <div class="panel-heading">
			      <h4 class="panel-title">
			        <a data-toggle="collapse" data-parent="#accordion" href="#collapse4">
			          Choix du client :
			        </a>
			      </h4>
			    </div>
			    <div id="collapse4" class="panel-collapse collapse">
			      <div class="panel-body">
		            <p>Vous pouvez sélectionner le client associé à la recette dans une liste. Si votre client n'apparait pas dans la liste il convient de le créer dans la gestion des clients.</p>
			      </div>
			    </div>
			  </div>

			  <div class="panel panel-default">
			    <div class="panel-heading">
			      <h4 class="panel-title">
			        <a data-toggle="collapse" data-parent="#accordion" href="#collapse5">
			          Montant :
			        </a>
			      </h4>
			    </div>
			    <div id="collapse5" class="panel-collapse collapse">
			      <div class="panel-body">
		            <p>Le montant en Euros et centimes d'Euros, par exemple : 100.50 ou 100,50</p>
			      </div>
			    </div>
			  </div>

			  <div class="panel panel-default">
			    <div class="panel-heading">
			      <h4 class="panel-title">
			        <a data-toggle="collapse" data-parent="#accordion" href="#collapse6">
			          Commentaire :
			        </a>
			      </h4>
			    </div>
			    <div id="collapse6" class="panel-collapse collapse">
			      <div class="panel-body">
		            <p>Vous pouvez ajouter un commentaire qui décrira le détail de votre recette</p>
			      </div>
			    </div>
			  </div>

			  <div class="panel panel-default">
			    <div class="panel-heading">
			      <h4 class="panel-title">
			        <a data-toggle="collapse" data-parent="#accordion" href="#collapse7">
			          Horodatage :
			        </a>
			      </h4>
			    </div>
			    <div id="collapse7" class="panel-collapse collapse">
			      <div class="panel-body">
		            <p>Chaque recette est horadatée au momment de sa création</p>
			      </div>
			    </div>
			  </div>
			  			
			</div>
        <?php
        break;
    case "modalAideFormDepense":
        ?>
            <h4>Le formulaire d'ajout de depense vous permet d'ajouter en séquence toutes vos dépenses :</h4>
            <strong><span style="text-decoration: underline;">Type de dépense :</span></strong>
            <p>Vos dépenses peuvent être de différentes natures :</p>
            <ul>
              <li><strong>Frais</strong> (Par défaut) : Frais courants divers</li>
              <li><strong>Achat</strong> : Achat / Revente</li>
              <li><strong>Charges sociales</strong> : RSI / URSSAF / CIPAV / Etc...</li>
              <li><strong>Impôt</strong> : Impôts et Taxes</li>
            </ul>
            <strong><span style="text-decoration: underline;">Le montant de la dépense :</span></strong>
            <p>Le montant POSITIF en Euros et centimes d'Euros, par exemple : 100.50 ou 100,50.</p>
            <strong><span style="text-decoration: underline;">Commentaire :</span></strong>
            <p>Vous pouvez ajouter un commentaire qui décrira le détail de votre recette.</p>
            <strong><span style="text-decoration: underline;">Horodatage :</span></strong>
            <p>Chaque dépense est horadatée au momment de sa création.</p>
        <?php
        break;
    case "modalAideSalaire":
        ?>
            <h4>Le formulaire de saisie du salaire réellement versé : </h4>
            <p>Cet écran vous permet de modifier le montant du salaire calculé par Aboo et de saisir le salaire que vous vous êtes réelement servi.</p> 
            <p>Aboo calcul un salaire optimal pour vous, cependant, si vous souhaitez vous servir plus ou moins, il suffit de rentrer cette information
            dans cette page. Par défaut, Aboo considèrera que vous vous êtes versé le salaire qu'il calcule.</p>
            <br>
            <p>Le salaire moyen affiché en Orange en haut de la page vous donne une idée de ce que vous  pouvez vous servir en l'état de vos recettes sur l'année. Bien entendu ce montant est juste indicatif car il dépend de vos recettes et il est définitif qu'en fin d'exercice.</p>
            <p>Vous pouvez à tout moment revenir à la situation antérieure en supprimant la modification de salaire.</p>
            <br>
            <p><strong>ATTENTION : </strong>Vous ne pouvez pas vous servir plus que ce que dont vous disposez en trésorerie ! La colonne "trésorerie disponible" est colorée en fonction de ce critère : 
            	<li>Vert : vous avez de la tréso disponible, vous pouvez éventuellement vous servir d'avantage de salaire.</li>
            	<li>Orange : vous n'en avez pas assez de trésorerie pour augmenter votre salaire.</li>
            	<li>Rouge : votre trésorerie est négative, ce n'est pas normal, vous devez revoir votre distribution les mois précédents!</li></p>
            <p>Si l'information de trésorerie ne correspondait pas à la réalité de votre compte il faudrait alors corriger votre trésorerie initiale... ou comprendre d'où vient cet écart</p>
            <br>
            <p>Le montant du salaire doit être POSITIF en Euros et centimes d'Euros, par exemple : 1500.50</p>
            <p>Le commentaire vous permet de garder une trace de la raison qui à motivé la modification de votre salaire.</p>            
        <?php
        break;		
    default:
        echo "<center> Erreur : Il n'y a pas d'aide! </center>";        
}

?>                                    
          </div><!-- /.modal-body -->                                         
          <div class="modal-footer">
            <div class="form-actions pull-right">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><span class="glyphicon glyphicon-eject"></span> Retour</button>                                 
            </div>
          </div><!-- /.modal-footer -->
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->