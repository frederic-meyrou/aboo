        <!-- Modal eMail -->
        <div class="modal fade" id="modalEmail" tabindex="-1" role="dialog" aria-labelledby="EmailModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
                <form class="form-horizontal" action="mesclients.php" method="post">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3 class="modal-title" id="EmailModalLabel">Envoi d'un eMail de relance :</h3>
                  </div><!-- /.modal-header -->
                  <div class="modal-body">
                  <div class="panel panel-success">
                      <div class="panel-heading">
                        <h3 class="panel-title">Liste des client à relancer</h3>
                      </div>
                      <div class="panel-body">
                        <table class="table table-condensed table-bordered">
                        <?php
                        for ($i=0; $i<count($selection); $i++) {
                            echo '<tr>';
                            echo '<p><td>' . $data2['ID'.$selection[$i]][0] . '</td><td>' . $data2['ID'.$selection[$i]][1] . '</td><td>' . $data2['ID'.$selection[$i]][2] . '</td></p>';
                            echo '</tr>';
                        } // for
                        ?>
                        </table>                        
                      </div>
                  </div>                            
                  <strong>
                   <p class="alert alert-warning">Confirmez-vous l'envoi ?</p>
                   <input id="emailok" type="hidden" name="emailok" value="1"/>
                  </strong>
                  </div><!-- /.modal-body -->                                         
                  <div class="modal-footer">
                    <div class="form-actions">                              
                        <button type="submit" class="btn btn-danger pull-right"><span class="glyphicon glyphicon-envelope"></span> Envoyer</button>
                        <button type="button" class="btn btn-primary pull-left" data-dismiss="modal"><span class="glyphicon glyphicon-eject"></span> Retour</button>                                  
                    </div>
                  </div><!-- /.modal-footer -->
                </form>                   
            </div><!-- /.modal-content -->
          </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->    