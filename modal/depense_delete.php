            <!-- Modal Delete -->
            <div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-labelledby="DeleteModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                    <form class="form-horizontal" action="depense_delete.php" method="post">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h3 class="modal-title" id="DeleteModalLabel">Suppression d'une dépense :</h3>
                      </div><!-- /.modal-header -->
                      <div class="modal-body">
                          <strong>
                           <p class="alert alert-danger">Confirmez-vous la suppression ?</p>
                           <input id="DeleteInput" type="hidden" name="id" value=""/>
                          </strong>
                      </div><!-- /.modal-body -->                                         
                      <div class="modal-footer">
                        <div class="form-actions">                              
                            <button type="submit" class="btn btn-danger pull-right"><span class="glyphicon glyphicon-trash"></span> Suppression</button>
                            <button type="button" class="btn btn-primary pull-left" data-dismiss="modal"><span class="glyphicon glyphicon-eject"></span> Retour</button>                                  
                        </div>
                      </div><!-- /.modal-footer -->
                    </form>                   
                </div><!-- /.modal-content -->
              </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->              
          </div>
        </div>  <!-- /panel --> 