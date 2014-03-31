                    <!-- Modal -->
                    <div class="modal fade" id="modalConfigFormeMail" tabindex="-1" role="dialog" aria-labelledby="modalConfigFormeMailLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <form role="form" class="form-horizontal" action="configuration.php" method="post">  
                        <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h3 class="modal-title" id="modalConfigFormeMailLabel">Modification de mon eMail :</h3>
                              </div><!-- /.modal-header -->
                              <div class="modal-body">

                                   <div class="form-group <?php echo !empty($emailError)?'has-error':'';?>">
                                    <label for="email" class="col-sm-3 control-label">eMail</label>
                                    <div class="col-sm-6">
                                      <input name="email" type="mail" class="form-control" id="email" placeholder="eMail" value="<?php echo (!$affiche_erreur)?!empty($data['email'])?$data['email']:'':$email;?>" >
                                      <?php if (!empty($emailError)): ?>
                                        <span class="help-inline"><?php echo $emailError;?></span>
                                      <?php endif; ?>                                    
                                    </div>
                                  </div>   
                                                                                                    
                                  <input type="hidden" name="action" value="email">
                              </div><!-- /.modal-body -->                                         
                              <div class="modal-footer">
                                <div class="form-actions pull-right">
                                    <button type="button" class="btn btn-primary" data-dismiss="modal"><span class="glyphicon glyphicon-eject"></span> Retour</button>                                  
                                    <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-ok-sign"></span> Valider</button>
                                </div>
                              </div><!-- /.modal-footer -->
                        </div><!-- /.modal-content -->
                      </form>  
                      </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                </form>