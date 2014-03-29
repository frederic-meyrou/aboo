                    <!-- Modal -->
                    <div class="modal fade" id="modalConfigFormCoordonnees" tabindex="-1" role="dialog" aria-labelledby="modalConfigFormCoordonneesLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <form role="form" class="form-horizontal" action="configuration.php" method="post">  
                        <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h3 class="modal-title" id="modalConfigFormCoordonneesLabel">Modifications des coordonnées :</h3>
                              </div><!-- /.modal-header -->
                              <div class="modal-body">
                                
                                  <div class="form-group <?php echo !empty($mobileError)?'has-error':'';?>">
                                    <label for="mobile" class="col-sm-3 control-label">Téléphone Mobile</label>
                                    <div class="col-sm-6">
                                      <input name="mobile" type="tel" class="form-control" id="mobile" placeholder="Mobile" value="<?php echo !empty($data['mobile'])?$data['mobile']:'';?>">
                                    </div>
                                  </div>
                                  <div class="form-group <?php echo !empty($telephoneError)?'has-error':'';?>">
                                    <label for="telephone" class="col-sm-3 control-label">Téléphone Fixe</label>
                                    <div class="col-sm-6">
                                      <input name="telephone" type="tel" class="form-control" id="telephone" placeholder="Téléphone fixe" value="<?php echo !empty($data['telephone'])?$data['telephone']:'';?>">
                                    </div>
                                  </div>
                                  <div class="form-group <?php echo !empty($internetError)?'has-error':'';?>">
                                    <label for="internet" class="col-sm-3 control-label">Site Internet</label>
                                    <div class="col-sm-9">
                                      <input name="internet" type="url" class="form-control" id="internet" placeholder="Site Internet" value="<?php echo !empty($data['site_internet'])?$data['site_internet']:'';?>">
                                    </div>
                                  </div>                                  
                                  <div class="form-group <?php echo !empty($adresse1Error)?'has-error':'';?>">
                                    <label for="adresse1" class="col-sm-3 control-label">Adresse ligne 1</label>
                                    <div class="col-sm-6">
                                      <input name="adresse1" type="text" class="form-control" id="adresse1" placeholder="Adresse ligne 1" value="<?php echo !empty($data['adresse1'])?$data['adresse1']:'';?>">
                                    </div>
                                  </div>  
                                  <div class="form-group <?php echo !empty($adresse2Error)?'has-error':'';?>">
                                    <label for="adresse2" class="col-sm-3 control-label">Adresse ligne 2</label>
                                    <div class="col-sm-6">
                                      <input name="adresse2" type="text" class="form-control" id="adresse2" placeholder="Adresse ligne 2" value="<?php echo !empty($data['adresse2'])?$data['adresse2']:'';?>">
                                    </div>
                                  </div>   
                                  <div class="form-group <?php echo !empty($cpError)?'has-error':'';?>">
                                    <label for="cp" class="col-sm-3 control-label">Code Postal</label>
                                    <div class="col-sm-6">
                                      <input name="cp" type="text" class="form-control" id="cp" placeholder="CP" value="<?php echo !empty($data['cp'])?$data['cp']:'';?>">
                                    </div>
                                  </div> 
                                  <div class="form-group <?php echo !empty($villeError)?'has-error':'';?>">
                                    <label for="ville" class="col-sm-3 control-label">Ville</label>
                                    <div class="col-sm-6">
                                      <input name="ville" type="text" class="form-control" id="ville" placeholder="Ville" value="<?php echo !empty($data['ville'])?$data['ville']:'';?>">
                                    </div>
                                  </div>                                                                                                                                                                            
                                  <input type="hidden" name="action" value="coordonnees">
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