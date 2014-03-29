                    <!-- Modal -->
                    <div class="modal fade" id="modalConfigFormCompte" tabindex="-1" role="dialog" aria-labelledby="modalConfigFormCompteLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <form role="form" class="form-horizontal" action="configuration.php" method="post">  
                        <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h3 class="modal-title" id="modalConfigFormCompteLabel">Modification de mon compte utilisateur :</h3>
                              </div><!-- /.modal-header -->
                              <div class="modal-body">

                                  <div class="form-group <?php echo !empty($prenomError)?'has-error':'';?>">
                                    <label for="prenom" class="col-sm-3 control-label">Prénom</label>
                                    <div class="col-sm-6">
                                      <input name="prenom" type="text" class="form-control" id="prenom" placeholder="Prénom" value="<?php echo !empty($data['prenom'])?$data['prenom']:'';?>">
                                    </div>
                                  </div>  
                                  <div class="form-group <?php echo !empty($nomError)?'has-error':'';?>">
                                    <label for="nom" class="col-sm-3 control-label">Nom</label>
                                    <div class="col-sm-6">
                                      <input name="nom" type="text" class="form-control" id="nom" placeholder="Nom" value="<?php echo !empty($data['nom'])?$data['nom']:'';?>">
                                    </div>
                                  </div>  
                                  <div class="form-group <?php echo !empty($emailError)?'has-error':'';?>">
                                    <label for="email" class="col-sm-3 control-label">eMail</label>
                                    <div class="col-sm-6">
                                      <input name="email" type="mail" class="form-control" id="email" placeholder="eMail" value="<?php echo !empty($data['email'])?$data['email']:'';?>" disabled>
                                    </div>
                                  </div>                                                                     
                                  <div class="form-group <?php echo !empty($mobileError)?'has-error':'';?>">
                                    <label for="mobile" class="col-sm-3 control-label">Téléphone Mobile</label>
                                    <div class="col-sm-6">
                                      <input name="mobile" type="tel" class="form-control" id="mobile" placeholder="Mobile" value="<?php echo !empty($data['mobile'])?$data['mobile']:'';?>">
                                    </div>
                                  </div>  
                                  <input type="hidden" name="action" value="compte">
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