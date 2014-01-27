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
    case "modalAide2":
        echo "";
        break;
    case "modalAide3":
        echo "";
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