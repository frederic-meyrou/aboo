                    <!-- Modal paiement-->
                    <div class="modal fade" id="modalPaiement" tabindex="-1" role="dialog" aria-labelledby="PaiementModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h3 class="modal-title" id="PaiementModalLabel">Saisie des paiements étalés :</h3>
                              </div><!-- /.modal-header -->
                              <div class="modal-body">
                                <?php 
                                if ($affiche_paiement_etale) { // On affiche le formulaire que si nécessaire
                                    echo "Répartissez votre " . NumToTypeRecette($type) . " de $montant € sur les mois suivants : "; 
                                    echo '<div class="panel panel-default"><div class="panel-body">';                                         
                                    echo '<dl class="dl-horizontal">';                      
                                    for ($m = $mois_choisi_relatif; $m <= 12; $m++) { // Affiche les champs paiements
                                        echo '<dt>';
                                        echo '<dt>' . NumToMois(MoisAnnee($m,$exercice_mois)) . ' : </dt>';
                                        echo '<dd>';
                                        ?>
                                        <div class="form-group  <?php echo !empty($paiement_mois_Error)?'has-error':'';?>">
                                            <input name="paiement_mois_" id="paiement_mois_" type="text" class="form-control" value="<?php echo !empty($paiement_mois_{$m})?$paiement_mois_{$m}:'';?>" placeholder="<?php echo NumToMois(MoisAnnee($m,$exercice_mois));?> €" >                              
                                        </div>
                                        <?php                                         
                                        echo '</dd>';
                                    } // endfor
                                    echo '</dl>';
                                    echo '</div></div>';
                                    echo '<input type="hidden" name="etale" value="1">'; //Flag pour traitement du formulaire
                                    echo '<div class="help-block has-error">';
                                    if (!empty($paiement_mois_Error)) {
                                        echo '<span class="help-block has-error">' . $paiement_mois_Error . '</span>';
                                    echo '</div>';
                                    } // If
                                } // IF
                                ?>                                                         
                              </div><!-- /.modal-body -->                                         
                              <div class="modal-footer">
                                <div class="form-actions pull-right">
                                    <button type="button" class="btn btn-primary" data-dismiss="modal"><span class="glyphicon glyphicon-eject"></span> Retour</button>                                  
                                    <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-plus-sign"></span> Ajout des paiements</button>
                                </div>
                              </div><!-- /.modal-footer -->
                        </div><!-- /.modal-content -->
                      </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                </form>