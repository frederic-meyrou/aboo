<!-- 
© Copyright : Aboo / www.aboo.fr : Frédéric MEYROU : tous droits réservés
-->
<?php
// Dépendances
    require_once('lib/fonctions.php');
    include_once('lib/database.php');
    include_once('lib/calcul_bilan.php');

// Vérification de l'Authent
    session_start();
    require('lib/authent.php');
    if( !Authent::islogged()){
        // Non authentifié on repart sur la HP
        header('Location:../index.php');
    }

// Récupération des variables de session
	include_once('lib/var_session.php');
	
// Mode Debug
    $debug = false;

// Sécurisation POST & GET
    foreach ($_GET as $key => $value) {
        $sGET[$key]=htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
    foreach ($_POST as $key => $value) {
        $sPOST[$key]=htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

// Calcul du Bilan
    $TableauBilanMensuel = CalculBilanMensuelFiscal($user_id, $exercice_id, $exercice_treso);
    $TableauBilanAnnuel = CalculBilanAnnuelFiscal($user_id, $exercice_id, $TableauBilanMensuel);        
    
    if ($TableauBilanAnnuel==null) { // Il n'y a rien en base sur l'année (pas de dépenses et pas de recettes)
        $affiche = false;         
    } else {
            // On affiche le tableau
            $affiche = true;
    }

?>

<!DOCTYPE html>
<html lang="fr">
<?php require 'head.php'; ?>

<body>

    <?php $page_courante = "bilan_annuel.php"; require 'nav.php'; ?>
        
    <div class="container">
     
       
        <!-- Affiche les informations de debug -->
        <?php 
        if ($debug) {
        ?>
        <div class="alert alert alert-danger alert-dismissable fade in">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <strong>Informations de Debug : </strong><br>
            SESSION:<br>
            <pre><?php var_dump($_SESSION); ?></pre>
            POST:<br>
            <pre><?php var_dump($_POST); ?></pre>
            GET:<br>
            <pre><?php var_dump($_GET); ?></pre>
            Tableau:<br>            
            <pre><?php var_dump($TableauBilanMensuel); ?></pre>
            <br>
            Tableau:<br>            
            <pre><?php var_dump($TableauBilanAnnuel); ?></pre>
            <br>                                                      
        </div>
        <?php       
        }   
        ?> 

        <div class="page-header">          
            <h2>Bilan annuel : <button type="button" class="btn btn-lg btn-info"><?php echo "$exercice_annee - " . ($exercice_annee +1); ?></button></h2>  
        </div>
 
        <!-- Affiche sous condition -->
            <?php 
            if ($affiche) {
            ?>

            <div class="row">
            <div class="col-sm-5">

            <!-- Affiche le CA -->                    
              <div class="panel panel-success">
                <div class="panel-heading">
                  <h3 class="panel-title"><span class="glyphicon glyphicon-pushpin"></span> Bilan financier de l'exercice</h3>
                </div>
                <div class="panel-body">
                    <center>
                    <table width="80%"> 
                    <tr>                                    
                        <td align="right"><button type="button" class="btn btn-info">CA : </button></td>
                        <td align="left"><button type="button" class="btn btn-default"><?php echo number_format($TableauBilanAnnuel['CA'],2,',','.'); ?> €</button></td>
                    </tr>
                    <tr>                                    
                        <td align="right"><button type="button" class="btn btn-info">CA déclaré : </button></td>
                        <td align="left"><button type="button" class="btn btn-default"><?php echo number_format($TableauBilanAnnuel['DECLARE'],2,',','.'); ?> €</button></td>
                    </tr>
                    <tr>                                    
                        <td align="right"><button type="button" class="btn btn-info">CA non-déclaré : </button></td>
                        <td align="left"><button type="button" class="btn btn-default"><?php echo number_format($TableauBilanAnnuel['NON_DECLARE'],2,',','.'); ?> €</button></td>
                    </tr>
                    <tr>                                    
                        <td align="right"><button type="button" class="btn btn-info">Dépenses : </button></td>
                        <td align="left"><button type="button" class="btn btn-default"><?php echo number_format($TableauBilanAnnuel['DEPENSE'],2,',','.'); ?> €</button></td>
                    </tr>
                    <tr>                                    
                        <td align="right"><button type="button" class="btn btn-info">Solde brut (CA-Dépenses) : </button></td>
                        <td align="left"><button type="button" class="btn btn-default"><?php echo number_format($TableauBilanAnnuel['SOLDE'],2,',','.'); ?> €</button></td>
                    </tr>
                    <tr>                                    
                        <td align="right"><button type="button" class="btn btn-info">Bénéfice : </button></td>
                        <td align="left"><button type="button" class="btn btn-default"><?php echo number_format($TableauBilanAnnuel['BENEFICE'],2,',','.'); ?> €</button></td>
                    </tr>
                    </table></center>
                </div>
              </div>

                <!-- Affiche le Salaire -->                    
                  <div class="panel panel-success">
                    <div class="panel-heading">
                      <h3 class="panel-title"><span class="glyphicon glyphicon-pushpin"></span> Salaire</h3>
                    </div>
                    <div class="panel-body">
                        <center>
                        <table width="80%">
                        <tr>                                                            
                            <td align="right"><button type="button" class="btn btn-info">Salaire : </button></td> 
                            <td align="left"><button type="button" class="btn btn-default"><?php echo number_format($TableauBilanAnnuel['SALAIRE_REEL'],2,',','.'); ?> €</button></td> 
                        </tr>
                        <tr>                                                             
                            <td align="right"><button type="button" class="btn btn-info">Salaire mensuel moyen : </button></td>                     
                            <td align="left"><button type="button" class="btn btn-default"><?php echo number_format($TableauBilanAnnuel['SALAIRE_REEL']/12,2,',','.'); ?> €</button></td> 
                        </tr>
                        <tr>                                                            
                            <td align="right"><button type="button" class="btn btn-info">Dernier report Salaire : </button></td>    
                            <td align="left"><button type="button" class="btn btn-default"><?php echo number_format($TableauBilanAnnuel['REPORT_SALAIRE'],2,',','.'); ?> €</button></td>    
                        </tr>
                        </table></center>
                    </div>
                  </div>
                           

                <!-- Affiche la TRESO -->                    
                  <div class="panel panel-warning">
                    <div class="panel-heading">
                      <h3 class="panel-title"><span class="glyphicon glyphicon-pushpin"></span> Bilan de la Trésorerie</h3>
                    </div>
                    <div class="panel-body">
                        <center>
                        <table width="80%">
                        <tr>                                                            
                            <td align="right"><button type="button" class="btn btn-info">Trésorerie démarrage : </button></td>   
                            <td align="left"><button type="button" class="btn btn-default"><?php echo number_format($exercice_treso,2,',','.'); ?> €</button></td>   
                        </tr>
                        <tr>                                                            
                            <td align="right"><button type="button" class="btn btn-info">Trésorerie finale : </button></td>   
                            <td align="left"><button type="button" class="btn btn-default"><?php echo number_format($TableauBilanAnnuel['REPORT_TRESO'],2,',','.'); ?> €</button></td>   
                        </tr>
                        <tr>                                                            
                            <td align="right"><button type="button" class="btn btn-info">Provision pour charge initiale : </button></td>   
                            <td align="left"><button type="button" class="btn btn-default"><?php echo number_format($exercice_provision,2,',','.'); ?> €</button></td>   
                        </tr>
                        <tr>                                                            
                            <td align="right"><button type="button" class="btn btn-info">Provision pour charge finale : </button></td>   
                            <td align="left"><button type="button" class="btn btn-default"><?php echo number_format($TableauBilanMensuel[12]['CUMUL_PROVISION_CHARGES'],2,',','.'); ?> €</button></td>   
                        </tr>
                        </table></center>
                    </div>
                  </div>


            </div> <!-- /col -->               

            <div class="col-sm-6 col-md-offset-0">

            <!-- Affiche les charges -->                    
              <div class="panel panel-warning">
                <div class="panel-heading">
                  <h3 class="panel-title"><span class="glyphicon glyphicon-pushpin"></span> Charges sociales</h3>
                </div>
                <div class="panel-body">
                    <center>
                    <table width="80%">
                    <tr>                                                          
                        <td align="right"><button type="button" class="btn btn-info">Statut fiscal : </button></td>   
                        <td align="left"><button type="button" class="btn btn-default"><?php echo NumToRegimeFiscal($regime_fiscal); ?> €</button></td>                          
                    </tr>                         
                    <tr>                                                          
                        <td align="right"><button type="button" class="btn btn-info">Charges Payées : </button></td>   
                        <td align="left"><button type="button" class="btn btn-default"><?php echo number_format($TableauBilanAnnuel['CHARGE'],2,',','.'); ?> €</button></td>                          
                    </tr>
                    <tr>                                                          
                        <td align="right"><button type="button" class="btn btn-info">Charges Calculées : </button></td>   
                        <td align="left"><button type="button" class="btn btn-default"><?php echo number_format($TableauBilanAnnuel['CHARGES_CALCULEES'],2,',','.'); ?> €</button></td>                          
                    </tr>
                    <tr>                                                          
                        <td align="right"><button type="button" class="btn btn-info">Provision pour charges : </button></td>   
                        <td align="left"><button type="button" class="btn btn-default"><?php echo number_format($TableauBilanAnnuel['PROVISION_CHARGES_REEL'],2,',','.'); ?> €</button></td>                          
                    </tr>
                    </table></center>
                </div>
              </div>



                <!-- Affiche les HA -->                    
                  <div class="panel panel-info">
                    <div class="panel-heading">
                      <h3 class="panel-title"><span class="glyphicon glyphicon-pushpin"></span> Bilan activité revente</h3>
                    </div>
                    <div class="panel-body">
                        <center>
                        <table width="80%">
                        <tr>                                                                                            
                            <td align="right"><button type="button" class="btn btn-info">Achats : </button></td>   
                            <td align="left"><button type="button" class="btn btn-default"><?php echo number_format($TableauBilanAnnuel['ACHAT'],2,',','.'); ?> €</button></td>   
                        </tr>
                        <tr>                                    
                            <td align="right"><button type="button" class="btn btn-info">Ventes : </button></td>   
                            <td align="left"><button type="button" class="btn btn-default"><?php echo number_format($TableauBilanAnnuel['VENTE'],2,',','.'); ?> €</button></td>   
                        </tr>
                        <tr>                                    
                            <td align="right"><button type="button" class="btn btn-info">Bénéfice revente : </button></td>   
                            <td align="left"><button type="button" class="btn btn-default"><?php echo number_format($TableauBilanAnnuel['BENEFICE_REVENTE'],2,',','.'); ?> €</button></td>   
                        </tr>
                        </table></center>
                    </div>
                  </div>

            <!-- Affiche les paiements -->                    
              <div class="panel panel-info">
                <div class="panel-heading">
                  <h3 class="panel-title"><span class="glyphicon glyphicon-pushpin"></span> Paiements</h3>
                </div>
                <div class="panel-body">
                    <center>
                    <table width="80%">
                    <tr>                                                        
                        <td align="right"><button type="button" class="btn btn-info">Total paiements différés : </button></td>                                                  
                        <td align="left"><button type="button" class="btn btn-default"><?php echo number_format($TableauBilanAnnuel['PAIEMENT'],2,',','.'); ?> €</button></td> 
                    </tr>                        
                    <tr>                                                         
                        <td align="right"><button type="button" class="btn btn-info">Total des encaissements : </button></td>
                        <td align="left"><button type="button" class="btn btn-default"><?php echo number_format($TableauBilanAnnuel['ENCAISSEMENT'],2,',','.'); ?> €</button></td>
                    </tr>
                    <tr>                                                          
                        <td align="right"><button type="button" class="btn btn-info">Total des paiements à encaisser : </button></td>   
                        <td align="left"><button type="button" class="btn btn-default"><?php echo number_format($TableauBilanAnnuel['ECHUS'],2,',','.'); ?> €</button></td>
                    </tr>
                    </table></center>
                </div>
              </div>   

                <!-- Affiche les divers -->                    
                  <div class="panel panel-info">
                    <div class="panel-heading">
                      <h3 class="panel-title"><span class="glyphicon glyphicon-pushpin"></span> Divers</h3>
                    </div>
                    <div class="panel-body">
                        <center>
                        <table width="80%">
                        <tr>                                                                                            
                            <td align="right"><button type="button" class="btn btn-info">Sous location : </button></td>   
                            <td align="left"><button type="button" class="btn btn-default"><?php echo number_format($TableauBilanAnnuel['LOCATION'],2,',','.'); ?> €</button></td>                      
                        </tr>
                        <tr>                                    
                            <td align="right"><button type="button" class="btn btn-info">Impôts & Taxes : </button></td>   
                            <td align="left"><button type="button" class="btn btn-default"><?php echo number_format($TableauBilanAnnuel['IMPOT'],2,',','.'); ?> €</button></td>                                                                        
                        </tr>
                        </table></center>
                    </div>
                  </div>


                
                </div> <!-- /col -->
            </div>  <!-- /row -->
            <?php   
            } // if
            ?>             

    <?php require 'footer.php'; ?>             
    </div> <!-- /container -->

        
  </body>
</html>