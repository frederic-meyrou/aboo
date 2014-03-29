                    <!-- Modal -->
                    <div class="modal fade" id="modalConfigFormOptions" tabindex="-1" role="dialog" aria-labelledby="modalConfigFormOptionsLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <form role="form" class="form-inline" action="configuration.php" method="post">  
                        <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h3 class="modal-title" id="modalConfigFormOptionsLabel">Modifications des options :</h3>
                              </div><!-- /.modal-header -->
                              <div class="modal-body">
                                        <center>
                                        <li class="list-group-item">    
                                        <div class="radio">
                                          <label>
                                            <strong>Option Aboo Social : </strong>   
                                            <input type="radio" name="social" id="socialnon" value="Non" <?php echo ($data['gestion_social'] == 0)?'checked':'';?>>
                                            Non
                                          </label>
                                        </div>
                                        <div class="radio">
                                          <label>
                                            <input type="radio" name="social" id="socialoui" value="Oui" <?php echo ($data['gestion_social'] == 1)?'checked':'';?>>
                                            Oui
                                          </label>
                                        </div>
                                        </li>
                                        </center>
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