                    <!-- Modal -->
                    <div class="modal fade" id="modalConfigFormMotdepasse" tabindex="-1" role="dialog" aria-labelledby="modalConfigFormMotdepasseLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <form role="form" class="form-horizontal" action="configuration.php" method="post">  
                        <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h3 class="modal-title" id="modalConfigFormMotdepasseLabel">Modification de mon mot de passe :</h3>
                              </div><!-- /.modal-header -->
                              <div class="modal-body">
                                                                                                   
                                   <div class="form-group <?php echo !empty($motdepasseError)?'has-error':'';?>">
                                    <label for="password" class="col-sm-3 control-label">Mot de passe</label>
                                    <div class="col-sm-6">
                                      <input name="password" type="password" class="form-control" id="password" placeholder="Mot de Passe" value="<?php echo (!$affiche_erreur)?'':$password;?>">                                
                                    </div>
                                  </div> 
                                  
                                   <div class="form-group <?php echo !empty($motdepasseError)?'has-error':'';?>">
                                    <label for="password2" class="col-sm-3 control-label">Confirmez le mot de passe</label>
                                    <div class="col-sm-6">
                                      <input name="password2" type="password" class="form-control" id="password2" placeholder="Mot de Passe" value="<?php echo (!$affiche_erreur)?'':$password2;?>">
                                      <?php if (!empty($motdepasseError)): ?>
                                        <span class="help-inline"><?php echo $motdepasseError;?></span>
                                      <?php endif; ?>                                    
                                    </div>
                                  </div>                                  
 
                                  <input type="hidden" name="action" value="motdepasse">
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