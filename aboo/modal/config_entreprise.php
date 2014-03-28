                    <!-- Modal -->
                    <div class="modal fade" id="modalConfigFormEntreprise" tabindex="-1" role="dialog" aria-labelledby="modalConfigFormEntrepriseLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <form role="form" class="form-horizontal">  
                        <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h3 class="modal-title" id="modalConfigFormEntrepriseLabel">Modification des informations de mon entreprise :</h3>
                              </div><!-- /.modal-header -->
                              <div class="modal-body">
                                  <div class="form-group <?php echo !empty($siretError)?'has-error':'';?>">
                                    <label for="siret" class="col-sm-3 control-label">SIRET</label>
                                    <div class="col-sm-6">
                                      <input name="siret" type="text" class="form-control" id="siret" placeholder="SIRET" value="<?php echo !empty($data['siret'])?$data['siret']:'';?>">
                                    </div>
                                  </div>  
                                  <div class="form-group <?php echo !empty($internetError)?'has-error':'';?>">
                                    <label for="internet" class="col-sm-3 control-label">Site Internet</label>
                                    <div class="col-sm-9">
                                      <input name="internet" type="url" class="form-control" id="internet" placeholder="Site Internet" value="<?php echo !empty($data['site_internet'])?$data['site_internet']:'';?>">
                                    </div>
                                  </div>  
                                  <div class="form-group">
                                    <label for="fiscal" class="col-sm-3 control-label">Régime fiscal</label>
                                    <div class="col-sm-9">                                      
                                        <select name="fiscal" id="fiscal" class="form-control">
                                        <?php
                                            foreach ($Liste_Regime_Fiscal as $r) {
                                        ?>
                                            <option value="<?php echo RegimeFiscalToNum($r);?>" <?php echo (RegimeFiscalToNum($r)==$data['regime_fiscal'])?'selected':'';?>><?php echo $r;?></option>    
                                        <?php 
                                            } // foreach   
                                        ?>
                                        </select>
                                    </div>                                  
                                  </div>
                                  <input type="hidden" name="action" value="options">
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