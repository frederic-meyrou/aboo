<!-- 
© Copyright : Aboo / www.aboo.fr : Frédéric MEYROU : tous droits réservés
-->

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
        echo '<a href="#" class="btn btn-default btn-info glyphicon glyphicon-star" role="button"> </a> : ' . "Cette icône permet d'afficher le détail. <br>";
        echo '<br>';
        echo '<a href="#" class="btn btn-default btn-warning glyphicon glyphicon-edit" role="button"> </a> : ' . "Cette icône permet de modifier. <br>";
        echo '<br>';
        echo '<a href="#" class="btn btn-default btn-danger glyphicon glyphicon-trash" role="button"> </a> : ' . "Cette icône permet de supprimer.";
        break;
    case "modalAideFormRecette":
        ?>
            <h4>Le formulaire d'ajout de recette vous permet d'ajouter en séquence toutes vos recettes :</h4>
            <strong><span style="text-decoration: underline;">Type de recette :</span></strong>
            <p>     Vos recettes peuvent être de différentes natures :</p>
            <ul>
              <li><span style="font-weight: bold;">Abonnement </span><span style="font-weight: bold;"></span>(Par
                défaut)<span style="font-weight: bold;"> </span>: Recette liée à la vente d'un abonnement<br />
              </li>
              <li><span style="font-weight: bold;">Revente </span>: Achat / Revente<br />
              </li>
              <li style="font-weight: bold;">Location <span style="font-weight: normal;">:
                  Si vous louez ou sous-louez votre local professionel</span><br />
              </li>
              <li><span style="font-weight: bold;">Autre</span> : autres types de revenu
                (intérêts, etc...)</li>
            </ul>
            <strong><span style="text-decoration: underline;">Le montant de la recette :</span></strong>
            <p>    Le montant en Euros et centimes d'Euros, par exemple : 100.50 ou 100,50.</p>
            <strong><span style="text-decoration: underline;">La périodicité de la recette :</span></strong>
            <p>    Ce sélecteur sert uniquement aux recettes de type "Abonnement" peux avoir les périodicités suivantes :</p>
            <ul>
              <li><span style="font-weight: bold;">Pontuel </span>(Par défaut) : La
                recette ou l'abonnement est pour le mois courant<br />
              </li>
              <li><span style="font-weight: bold;">Bi-Mensuel </span>: L'abonnement est d'une durée de 2 mois.<br />
              </li>
              <li style="font-weight: bold;">Trimestriel <span style="font-weight: normal;">:
                  L'abonnement est d'une durée de 3 mois.</span> </li>
              <li><span style="font-weight: bold;">Semestriel </span>: <span style="font-weight: normal;">L'abonnement
                  est d'une durée de 6 mois.</span><span style="font-weight: bold;"></span></li>
              <li><span style="font-weight: bold;">Annuel ou Lissé </span>: <span style="font-weight: normal;">L'abonnement
                  est d'une durée de 12 mois ou alors réparti sur le nombre de mois restant de l'exercice.</span></li>
            </ul>
            <strong><span style="text-decoration: underline;">Réglement :</span></strong>
            <p>     Vous pouvez sélectionner le réglement de votre recette selon 3 modalités :</p>
            <ul>
              <li><span style="font-weight: bold;">Réglé </span><span style="font-weight: bold;"></span>(Par
                défaut)<span style="font-weight: bold;"> </span>: La recette est réglée au comptant</li>
              <li><span style="font-weight: bold;">A régler </span>: La recette est en attente de réglement<br />
              </li>
              <li style="font-weight: bold;">Paiement étalé <span style="font-weight: normal;">:
                  <span style="font-weight: bold;"></span>Le paiement de la recette est
                  étalé dans le temps selon un calendrier. Lorsque ce choix est fait un formulaire suplémentaire est affiché après avoir appuyé sur le bouton "Ajout".<br />
                </span></li>
            </ul>
            <strong><span style="text-decoration: underline;">Choix du client :</span></strong>
            <p>     Vous pouvez sélectionner le client associé à la recette dans une
              liste. Si votre client n'apparait pas dans la liste il convient de le créer dans la gestion des clients.<br />
            </p>
            <strong><span style="text-decoration: underline;">Commentaire :</span></strong>
            <p>     Vous pouvez ajouter un commentaire qui décrira le détail de votre recette.</p>
            <strong><span style="text-decoration: underline;">Horodatage :</span></strong>
            <p>    Chaque recette est horadatée au momment de sa création.</p>
        <?php
        break;
    case "modalAideFormDepense":
        ?>
            <h4>Le formulaire d'ajout de depense vous permet d'ajouter en séquence toutes vos dépenses :</h4>
            <strong><span style="text-decoration: underline;">Type de dépense :</span></strong>
            <p>     Vos dépenses peuvent être de différentes natures :</p>
            <ul>
              <li><span style="font-weight: bold;">Frais </span><span style="font-weight: bold;"></span>(Par
                défaut)<span style="font-weight: bold;"> </span>: Frais courants divers<br />
              </li>
              <li><span style="font-weight: bold;">Achat </span>: Achat / Revente<br />
              </li>
              <li style="font-weight: bold;">Charges sociales <span style="font-weight: normal;">:
                  RSI / URSSAF / CIPAV / Etc... </span><br />
              </li>
              <li><span style="font-weight: bold;">Impôt</span> : Impôts et Taxes</li>
            </ul>
            <strong><span style="text-decoration: underline;">Le montant de la dépense :</span></strong>
            <p>    Le montant POSITIF en Euros et centimes d'Euros, par exemple : 100.50 ou 100,50.</p>
            <strong><span style="text-decoration: underline;">Commentaire :</span></strong>
            <p>     Vous pouvez ajouter un commentaire qui décrira le détail de votre recette.</p>
            <strong><span style="text-decoration: underline;">Horodatage :</span></strong>
            <p>    Chaque dépense est horadatée au momment de sa création.</p>
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