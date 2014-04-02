<?php

function CalculBilanMensuelAvecSocial($userid, $exerciceid, $exercicetreso, $exerciceprovision) {   
    //TableauBilan = array[1..12][assoc]
    //CA -> chiffre d'affaire total du mois
    //DEPENSE -> dépenses et charges courantes hors charges sociales
    //SOLDE -> Différence CA - DEPENSE
    //VENTIL -> Ventillation du CA abonnements sur le mois courant
    //PAIEMENT -> Total des paiement émis
    //ECHUS -> Total des créances de paiments
    //ENCAISSEMENT -> Total des encaissements (Recettes)
    //BENEFICE -> Différence ENCAISSEMENT - DEPENSE
    //TRESO -> TRESO avant prise de salaire
    //SALAIRE -> Salaire calculé par Aboo
    //SALAIRE_REEL -> Salaire distribué
    //REPORT_SALAIRE -> Report salaire vers mois M+1
    //REPORT_TRESO -> Report TRESO vers mois M+1
    //NON_DECLARE -> Total du CA Non déclaré
    //SALAIRE_COMMENTAIRE -> Commentaire sur distribution du salaire
    //CHARGES_PAYEES -> Charges sociales payes
    //CHARGES_CALCULEES -> Charges sociales calculées par Aboo (Ne dépend pas des capacités de TRESO)
    //PROVISION_CHARGES -> Provision sur charge calculée par Aboo
    //PROVISION_CHARGES_REEL -> Provision sur charge réelement mise en tréso
    //PROVISION_CHARGES_COMMENTAIRE -> Commentaire sur provision sur charges

// Initialisation de la base
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Requettes        
	// Requette pour calcul de la somme mensuelle des recettes     
    $sql1 = "SELECT SUM(montant) FROM recette WHERE
            user_id = :userid AND exercice_id = :exerciceid AND mois = :mois";   
    // Requette pour calcul de la somme mensuelle des depenses sauf charges sociales 
    $sql1bis = "SELECT SUM(montant) FROM depense WHERE
            user_id = :userid AND exercice_id = :exerciceid AND mois = :mois AND type <> 3;";  
    // Requette pour calcul de la somme mensuelle des depenses sauf charges sociales 
    $sql1ter = "SELECT SUM(montant) FROM depense WHERE
            user_id = :userid AND exercice_id = :exerciceid AND mois = :mois AND type = 3;";
			          
	// requette pour calcul des ventilations abo
    $sql2 = "SELECT SUM(mois_1),SUM(mois_2),SUM(mois_3),SUM(mois_4),SUM(mois_5),SUM(mois_6),SUM(mois_7),SUM(mois_8),SUM(mois_9),SUM(mois_10),SUM(mois_11),SUM(mois_12) FROM recette WHERE
            (user_id = :userid AND exercice_id = :exerciceid)";               
	// Requette pour calcul de la somme des encaissements mensuel
    $sql3 = "SELECT SUM(montant) FROM recette WHERE
            (user_id = :userid AND exercice_id = :exerciceid AND mois = :mois) AND
            paye = 1";
	// Requette pour calcul de la somme mensuelle des recettes non declarees     
    $sql6 = "SELECT SUM(montant) FROM recette WHERE
            (user_id = :userid AND exercice_id = :exerciceid AND mois = :mois) AND 
            declaration = 0";             

// Association des variables                            
    $q2 = array('userid' => $userid, 'exerciceid' => $exerciceid);     
    
    // Envoi des requettes 
    $req2 = $pdo->prepare($sql2);
    $req2->execute($q2);
    $data2 = $req2->fetch(PDO::FETCH_ASSOC);
        
    // Calcul des sommes mensuelles (boucle sur les mois relatifs)
    for ($m = 1; $m <= 12; $m++) {
        // Association des variables 
        $q = array('userid' => $userid, 'exerciceid' => $exerciceid, 'mois' => $m);
        // Envoi des requettes 
        $req1 = $pdo->prepare($sql1); $req1->execute($q); $data1 = $req1->fetch(PDO::FETCH_ASSOC);		        
        $req1bis = $pdo->prepare($sql1bis); $req1bis->execute($q); $data1bis = $req1bis->fetch(PDO::FETCH_ASSOC);
        $req1ter = $pdo->prepare($sql1ter); $req1ter->execute($q); $data1ter = $req1ter->fetch(PDO::FETCH_ASSOC);
        $req3 = $pdo->prepare($sql3); $req3->execute($q); $data3 = $req3->fetch(PDO::FETCH_ASSOC);
        $req6 = $pdo->prepare($sql6); $req6->execute($q); $data6 = $req6->fetch(PDO::FETCH_ASSOC);
		
        // Calcul CA, Depenses, Charges payées, Solde Brut et CA Non déclaré    
        $CA_{$m}= !empty($data1["SUM(montant)"]) ? $data1["SUM(montant)"] : 0;
        $DEPENSE_{$m}= !empty($data1bis["SUM(montant)"]) ? $data1bis["SUM(montant)"] : 0;
        $CHARGES_PAYEES_{$m}= !empty($data1ter["SUM(montant)"]) ? $data1ter["SUM(montant)"] : 0;		
        $SOLDE_{$m}= $CA_{$m} - $DEPENSE_{$m} - $CHARGES_PAYEES_{$m};
        $NON_DECLARE_{$m}= !empty($data6["SUM(montant)"]) ? $data6["SUM(montant)"] : 0;	
        
        // Calcul des sommes ventillées (grille annuelle)
        $VENTIL_{$m}= !empty($data2["SUM(mois_$m)"]) ? $data2["SUM(mois_$m)"] : 0;
        
        // Calcul des encaissements et bénéfice
        $ENCAISSEMENT_{$m}= !empty($data3["SUM(montant)"]) ? $data3["SUM(montant)"] : 0;
		$BENEFICE_{$m}= $ENCAISSEMENT_{$m} - $DEPENSE_{$m};
        
        // Calcul des paiements :
        // Requette pour calcul de la somme des paiement mensuelle          
        $sql4 = "SELECT SUM(P.mois_$m) FROM paiement P, recette A WHERE
                A.id = P.recette_id AND 
                A.user_id = :userid AND A.exercice_id = :exerciceid AND
                P.mois_$m <> 0
                ";
        // Requette pour calcul de la somme restant à mettre en recouvrement mensuelle          
        $sql5 = "SELECT SUM(P.mois_$m) FROM paiement P, recette A WHERE
                A.id = P.recette_id AND 
                A.user_id = :userid AND A.exercice_id = :exerciceid AND
                P.mois_$m <> 0 AND
                P.paye_$m = 0
                ";
		$sql7 ="SELECT salaire,commentaire from salaire WHERE user_id = :userid AND exercice_id = :exerciceid AND mois = :mois";
        $sql8 ="SELECT charges,commentaire from charges WHERE user_id = :userid AND exercice_id = :exerciceid AND mois = :mois";            		                         
        
        // Envoi des requettes 
        $req4 = $pdo->prepare($sql4); $req4->execute($q2); $data4 = $req4->fetch(PDO::FETCH_ASSOC);
        $req5 = $pdo->prepare($sql5); $req5->execute($q2); $data5 = $req5->fetch(PDO::FETCH_ASSOC);
        $req7 = $pdo->prepare($sql7); $req7->execute($q);  $data7 = $req7->fetch(PDO::FETCH_ASSOC);   
        $req8 = $pdo->prepare($sql8); $req8->execute($q);  $data8 = $req8->fetch(PDO::FETCH_ASSOC);
		                                           
        // Calcul des sommes 
        $PAIEMENT_{$m}= !empty($data4["SUM(P.mois_$m)"]) ? $data4["SUM(P.mois_$m)"] : 0; // Total des paiements émis dont créances et encaissé
        $ECHUS_{$m}= !empty($data5["SUM(P.mois_$m)"]) ? $data5["SUM(P.mois_$m)"] : 0; // Total des créances
        $ENCAISSEMENT_{$m}= $ENCAISSEMENT_{$m} + ( $PAIEMENT_{$m} - $ECHUS_{$m} ); // Total encaissé

        // Calcul de la TRESO DISPO
        $mois_relatif_prec = $m - 1;       
        if ($m == 1) { // Premier mois, cas particulier : on utilise la treso et la provision de début d'exercice
            $TRESO_{$m} = $exercicetreso + $ENCAISSEMENT_{$m} - $DEPENSE_{$m} - $CHARGES_PAYEES_{$m}; // TRESO négative possible!
        } else { // Tous les autres mois
            $TRESO_{$m} = $REPORT_TRESO_{$mois_relatif_prec} + $ENCAISSEMENT_{$m} - $DEPENSE_{$m} - $CHARGES_PAYEES_{$m}; // TRESO négative possible!
        }

        // Calcul des charges par type de régime fiscal
        $regime = $_SESSION['options']['regime_fiscal'];
        switch ($regime) {
			case '1': // AUTOENTREPRENEUR RSI
				$CHARGES_CALCULEES_{$m}= $ENCAISSEMENT_{$m} * 0.248; //24,6% + 0,2% formation Pro des recettes
				break;
			case '2': // AUTOENTREPRENEUR CIPAV 
				$CHARGES_CALCULEES_{$m}= $ENCAISSEMENT_{$m} * 0.215; //21,3% + 0,2% formation Pro des recettes
				break;
			case '3': // IE BNC Micro Services & Libéral
				$CHARGES_CALCULEES_{$m}= $ENCAISSEMENT_{$m} * 0.66 * 0.4; //TODO détail sur CA après abatement de 34%			
				break;
			case '4': // IE BNC REEL
				$CHARGES_CALCULEES_{$m}= $BENEFICE_{$m} * 0.4; //TODO détail sur Bénéfices				
				break;
        }
        if ( $TRESO_{$m} > $CHARGES_CALCULEES_{$m} ) {
            $PROVISION_CHARGES_{$m}=$CHARGES_CALCULEES_{$m};
        } else {
            $PROVISION_CHARGES_{$m}=0; // On a pas assez de tréso pour abonder la provision pour charges
        }
        // Lecture des charges reeles en BDD
        $PROVISION_CHARGES_REEL_{$m}= !empty($data8['charges']) ? $data8['charges'] : $PROVISION_CHARGES_{$m};
        $PROVISION_CHARGES_COMMENTAIRE_{$m}= !empty($data8['commentaire']) ? $data8['commentaire'] : '';

        // Calcul de la TRESO avant salaire
        $TRESO_{$m} = $TRESO_{$m} - $PROVISION_CHARGES_REEL_{$m}; // TRESO négative possible! 
		                
        // Calcul du salaire et de la provision sur charges
        if ($m == 1) { // Premier mois, cas particulier : on utilise la treso et la provision de début d'exercice
            $SALAIRE_{$m} = ($VENTIL_{$m} > $TRESO_{$m}) ? ($TRESO_{$m} - $DEPENSE_{$m} - $CHARGES_PAYEES_{$m}) : ( $VENTIL_{$m} - $DEPENSE_{$m} - $CHARGES_PAYEES_{$m});
            if ( ($TRESO_{$m} > 0 ) && ($VENTIL_{$m} > $TRESO_{$m}) ) { // Si on a de la treso ET que la ventilation est supérieur à la treso
               $REPORT_SALAIRE_{$m}= $VENTIL_{$m} - $TRESO_{$m};
            } else {
               $REPORT_SALAIRE_{$m}= 0;
            }
            $PROVISION_CHARGES_{$m}= $PROVISION_CHARGES_{$m} + $exerciceprovision - $CHARGES_PAYEES_{$m};
        } else { // Tous les autres mois
            $SALAIRE_{$m} = (($VENTIL_{$m} + $REPORT_SALAIRE_{$mois_relatif_prec}) > $TRESO_{$m}) ? ( $TRESO_{$m} - $DEPENSE_{$m} - $CHARGES_PAYEES_{$m}) : ( $VENTIL_{$m} + $REPORT_SALAIRE_{$mois_relatif_prec} - $DEPENSE_{$m} - $CHARGES_PAYEES_{$m} );    
            if ( ($TRESO_{$m} > 0 ) && (($VENTIL_{$m} + $REPORT_SALAIRE_{$mois_relatif_prec}) > $TRESO_{$m}) ) { // Si on a de la treso ET que la ventilation + report salaire est supérieur à la treso
               $REPORT_SALAIRE_{$m}= ($VENTIL_{$m} + $REPORT_SALAIRE_{$mois_relatif_prec}) - $TRESO_{$m};
            } else {
               $REPORT_SALAIRE_{$m}= 0;
            }
			$PROVISION_CHARGES_{$m}= $PROVISION_CHARGES_{$m} + $PROVISION_CHARGES_{$mois_relatif_prec} - $CHARGES_PAYEES_{$m};                   
        }
        if ($SALAIRE_{$m} <= 0) {$SALAIRE_{$m}=0;} // Pas de salaire négatif!
        // Lecture du Salaire réel en BDD
        $SALAIRE_REEL_{$m}= !empty($data7['salaire']) ? $data7['salaire'] : $SALAIRE_{$m};
        $SALAIRE_COMMENTAIRE_{$m}= !empty($data7['commentaire']) ? $data7['commentaire'] : '';

        // Calcul de la tréso finale
        $REPORT_TRESO_{$m}= $TRESO_{$m} - $SALAIRE_REEL_{$m}; // Prise en compte du salaire réél et des charges
        
        // Génération du Tableau :
        $TableauBilan[$m] = array (
            'CA' => $CA_{$m},                                                                   
            'DEPENSE' => $DEPENSE_{$m},                                                                   
            'SOLDE' => $SOLDE_{$m},                                                               
            'VENTIL' => $VENTIL_{$m},                                                                   
            'PAIEMENT' => $PAIEMENT_{$m},                                                                   
            'ECHUS' => $ECHUS_{$m},                                                             
            'ENCAISSEMENT' => $ENCAISSEMENT_{$m},
            'BENEFICE' => $BENEFICE_{$m},                                                                      
            'TRESO' => $TRESO_{$m},
            'SALAIRE' => $SALAIRE_{$m},        
            'SALAIRE_REEL' => $SALAIRE_REEL_{$m},    
            'SALAIRE_COMMENTAIRE' => $SALAIRE_COMMENTAIRE_{$m},
            'CHARGES_PAYEES' => $CHARGES_PAYEES_{$m},
            'CHARGES_CALCULEES' => $CHARGES_CALCULEES_{$m},                
            'PROVISION_CHARGES' => $PROVISION_CHARGES_{$m},        
            'PROVISION_CHARGES_REEL' => $PROVISION_CHARGES_REEL_{$m},    
            'PROVISION_CHARGES_COMMENTAIRE' => $PROVISION_CHARGES_COMMENTAIRE_{$m},                 
            'REPORT_SALAIRE' => $REPORT_SALAIRE_{$m},                                                                               
            'REPORT_TRESO' => $REPORT_TRESO_{$m},
            'NON_DECLARE' => $NON_DECLARE_{$m}
        ); 
   } // End for       

    Database::disconnect();       
    return $TableauBilan;
}

function CalculBilanMensuelSansSocial($userid, $exerciceid, $exercicetreso) {   
    //TableauBilan = array[1..12][assoc]
    //CA
    //DEPENSE
    //SOLDE
    //VENTIL
    //PAIEMENT
    //ECHUS
    //ENCAISSEMENT
    //TRESO
    //SALAIRE
    //SALAIRE_REEL
    //REPORT_SALAIRE
    //REPORT_TRESO
    //NON_DECLARE
    //SALAIRE_COMMENTAIRE

// Initialisation de la base
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
    // Requette pour calcul de la somme mensuelle des depenses et recettes     
    $sql1 = "(SELECT SUM(montant) FROM recette WHERE
            user_id = :userid AND exercice_id = :exerciceid AND mois = :mois)
            UNION
            (SELECT SUM(montant * -1) FROM depense WHERE
            user_id = :userid AND exercice_id = :exerciceid AND mois = :mois )
            ";             
// requette pour calcul des ventilations abo
    $sql2 = "SELECT SUM(mois_1),SUM(mois_2),SUM(mois_3),SUM(mois_4),SUM(mois_5),SUM(mois_6),SUM(mois_7),SUM(mois_8),SUM(mois_9),SUM(mois_10),SUM(mois_11),SUM(mois_12) FROM recette WHERE
            (user_id = :userid AND exercice_id = :exerciceid)
            ";               
// Requette pour calcul de la somme des encaissements mensuel
    $sql3 = "SELECT SUM(montant) FROM recette WHERE
            (user_id = :userid AND exercice_id = :exerciceid AND mois = :mois) AND
            paye = 1
            ";
// Requette pour calcul de la somme mensuelle des recettes non declarees     
    $sql6 = "SELECT SUM(montant) FROM recette WHERE
            (user_id = :userid AND exercice_id = :exerciceid AND mois = :mois) AND 
            declaration = 0
            ";             
// Association des variables                            
    $q2 = array('userid' => $userid, 'exerciceid' => $exerciceid);     
    
    // Envoi des requettes 
    $req2 = $pdo->prepare($sql2);
    $req2->execute($q2);
    $data2 = $req2->fetch(PDO::FETCH_ASSOC);
        
    // Calcul des sommes mensuelles (boucle sur les mois relatifs)
    for ($m = 1; $m <= 12; $m++) {
        // Association des variables 
        $q = array('userid' => $userid, 'exerciceid' => $exerciceid, 'mois' => $m);
        // Envoi des requettes 
        $req1 = $pdo->prepare($sql1);
        $req1->execute($q);
        $data1 = $req1->fetchAll(PDO::FETCH_ASSOC);     
        $req3 = $pdo->prepare($sql3);
        $req3->execute($q); 
        $data3 = $req3->fetch(PDO::FETCH_ASSOC);
        $req6 = $pdo->prepare($sql6);
        $req6->execute($q); 
        $data6 = $req6->fetch(PDO::FETCH_ASSOC);                                
        // Calcul CA, Depenses et Solde Brut    
        $CA_{$m}= !empty($data1[0]["SUM(montant)"]) ? $data1[0]["SUM(montant)"] : 0; 
        $DEPENSE_{$m}= !empty($data1[1]["SUM(montant)"]) ? $data1[1]["SUM(montant)"] : 0;
        $SOLDE_{$m}= $CA_{$m} + $DEPENSE_{$m};
        $NON_DECLARE_{$m}= !empty($data6["SUM(montant)"]) ? $data6["SUM(montant)"] : 0;  
        // Calcul des sommes ventillées (grille annuelle)
        $VENTIL_{$m}= !empty($data2["SUM(mois_$m)"]) ? $data2["SUM(mois_$m)"] : 0;
        // Calcul des encaissements
        $ENCAISSEMENT_{$m}= !empty($data3["SUM(montant)"]) ? $data3["SUM(montant)"] : 0;
        // Calcul des paiements :
        // Requette pour calcul de la somme des paiement mensuelle          
        $sql4 = "SELECT SUM(P.mois_$m) FROM paiement P, recette A WHERE
                A.id = P.recette_id AND 
                A.user_id = :userid AND A.exercice_id = :exerciceid AND
                P.mois_$m <> 0
                ";
        // Requette pour calcul de la somme restant à mettre en recouvrement mensuelle          
        $sql5 = "SELECT SUM(P.mois_$m) FROM paiement P, recette A WHERE
                A.id = P.recette_id AND 
                A.user_id = :userid AND A.exercice_id = :exerciceid AND
                P.mois_$m <> 0 AND
                P.paye_$m = 0
                ";
        $sql7 ="SELECT salaire,commentaire from salaire WHERE user_id = :userid AND exercice_id = :exerciceid AND mois = :mois";                                          
        // Envoi des requettes 
        $req4 = $pdo->prepare($sql4);
        $req4->execute($q2);
        $data4 = $req4->fetch(PDO::FETCH_ASSOC);
        $req5 = $pdo->prepare($sql5);
        $req5->execute($q2);
        $data5 = $req5->fetch(PDO::FETCH_ASSOC);
        $req7 = $pdo->prepare($sql7);
        $req7->execute($q);
        $data7 = $req7->fetch(PDO::FETCH_ASSOC);                                           
        // Calcul des sommes 
        $PAIEMENT_{$m}= !empty($data4["SUM(P.mois_$m)"]) ? $data4["SUM(P.mois_$m)"] : 0;
        $ECHUS_{$m}= !empty($data5["SUM(P.mois_$m)"]) ? $data5["SUM(P.mois_$m)"] : 0; 
        $ENCAISSEMENT_{$m}= $ENCAISSEMENT_{$m} + ( $PAIEMENT_{$m} - $ECHUS_{$m} );
        
        // Mois relatif précédent
        $mois_relatif_prec = $m - 1;
        
        if ($m == 1) { // Premier mois, cas particulier : on utilise la treso de début d'exercice
            $TRESO_{$m} = $exercicetreso + $ENCAISSEMENT_{$m} + $DEPENSE_{$m};
            $SALAIRE_{$m} = ($VENTIL_{$m} > $TRESO_{$m}) ? ($TRESO_{$m} + $DEPENSE_{$m} ) : ( $VENTIL_{$m} + $DEPENSE_{$m} );
            if ( ($TRESO_{$m} > 0 ) && ($VENTIL_{$m} > $TRESO_{$m}) ) { // Si on a de la treso ET que la ventilation est supérieur à la treso
               $REPORT_SALAIRE_{$m}= $VENTIL_{$m} - $TRESO_{$m};
            } else {
               $REPORT_SALAIRE_{$m}= 0;
            }    
        } else { // Tous les autres mois
            $TRESO_{$m} = $REPORT_TRESO_{$mois_relatif_prec} + $ENCAISSEMENT_{$m} + $DEPENSE_{$m};
            $SALAIRE_{$m} = (($VENTIL_{$m} + $REPORT_SALAIRE_{$mois_relatif_prec}) > $TRESO_{$m}) ? ( $TRESO_{$m} + $DEPENSE_{$m} ) : ( $VENTIL_{$m} + $REPORT_SALAIRE_{$mois_relatif_prec} + $DEPENSE_{$m} );    
            if ( ($TRESO_{$m} > 0 ) && (($VENTIL_{$m} + $REPORT_SALAIRE_{$mois_relatif_prec}) > $TRESO_{$m}) ) { // Si on a de la treso ET que la ventilation + report salaire est supérieur à la treso
               $REPORT_SALAIRE_{$m}= ($VENTIL_{$m} + $REPORT_SALAIRE_{$mois_relatif_prec}) - $TRESO_{$m};
            } else {
               $REPORT_SALAIRE_{$m}= 0;
            }                      
        }
        if ($SALAIRE_{$m} <= 0) {$SALAIRE_{$m}=0;} // Pas de salaire négatif!
        // Lecture du Salaire réel en BDD
        $SALAIRE_REEL_{$m}= !empty($data7['salaire']) ? $data7['salaire'] : $SALAIRE_{$m};
        $SALAIRE_COMMENTAIRE_{$m}= !empty($data7['commentaire']) ? $data7['commentaire'] : '';
        // Calcul de la tréso finale
        $REPORT_TRESO_{$m}= $TRESO_{$m} - $SALAIRE_REEL_{$m}; // Prise en compte du salaire réél
        // Génération du Tableau :
        $TableauBilan[$m] = array (
            'CA' => $CA_{$m},                                                                   
            'DEPENSE' => $DEPENSE_{$m},                                                                   
            'SOLDE' => $SOLDE_{$m},                                                               
            'VENTIL' => $VENTIL_{$m},                                                                   
            'PAIEMENT' => $PAIEMENT_{$m},                                                                   
            'ECHUS' => $ECHUS_{$m},                                                             
            'ENCAISSEMENT' => $ENCAISSEMENT_{$m},                                                                   
            'TRESO' => $TRESO_{$m},
            'SALAIRE' => $SALAIRE_{$m},        
            'SALAIRE_REEL' => $SALAIRE_REEL_{$m},    
            'SALAIRE_COMMENTAIRE' => $SALAIRE_COMMENTAIRE_{$m},               
            'REPORT_SALAIRE' => $REPORT_SALAIRE_{$m},                                                                               
            'REPORT_TRESO' => $REPORT_TRESO_{$m},
            'NON_DECLARE' => $NON_DECLARE_{$m}
        ); 
   } // End for       

    Database::disconnect();       
    return $TableauBilan;
}

function CalculBilanMensuel($userid, $exerciceid, $exercicetreso) {   

    $TableauBilan = array();

    if ($_SESSION['options']['gestion_social']) {
        $TableauBilan = CalculBilanMensuelAvecSocial($userid, $exerciceid, $exercicetreso, $_SESSION['exercice']['provision']);
    } else {
        $TableauBilan = CalculBilanMensuelSansSocial($userid, $exerciceid, $exercicetreso);
    }   
    return $TableauBilan;
}


function CalculBilanAnnuelAvecSocial($userid, $exerciceid, $BilanMensuel) {
    //TableauBilan = array[assoc]
    //CA
    //DEPENSE
    //SOLDE
    //VENTIL
    //PAIEMENT
    //ECHUS
    //ENCAISSEMENT
    //TRESO
    //SALAIRE
    //SALAIRE_REEL
    //REPORT_SALAIRE
    //REPORT_TRESO
    //ACHAT
    //VENTE
    //BENEFICE
    //CHARGE
    //LOCATION
    //IMPOT
    //NON_DECLARE
    //DECLARE        
    //PROVISION_CHARGES
    //PROVISION_CHARGES_REEL
    
// Initialisation de la base
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);    

// Requette pour calcul de la somme Annuelle            
    $sql1 = "(SELECT SUM(montant) FROM recette WHERE
            user_id = :userid AND exercice_id = :exerciceid )
            UNION
            (SELECT SUM(montant * -1) FROM depense WHERE
            user_id = :userid AND exercice_id = :exerciceid )
            ";

// Requette pour calcul de la somme Annuelle des achats/ventes           
    $sql2 = "SELECT SUM(montant) FROM recette WHERE
            user_id = :userid AND exercice_id = :exerciceid AND type = 2
            ";	
			
    $sql3 = "SELECT SUM(montant) FROM depense WHERE
            user_id = :userid AND exercice_id = :exerciceid AND type = 2 
            ";		

// Requette pour calcul de la somme Annuelle des location           
    $sql4 = "SELECT SUM(montant) FROM recette WHERE
            user_id = :userid AND exercice_id = :exerciceid AND type = 3
            ";	

// Requette pour calcul de la somme Annuelle des charges 			
    $sql5 = "SELECT SUM(montant) FROM depense WHERE
            user_id = :userid AND exercice_id = :exerciceid AND type = 3 
            ";	

// Requette pour calcul de la somme Annuelle des impots 			
    $sql6 = "SELECT SUM(montant) FROM depense WHERE
            user_id = :userid AND exercice_id = :exerciceid AND type = 4 
            ";	            
             
// Association des variables                            
    $q = array('userid' => $userid, 'exerciceid' => $exerciceid);                    
    
// Envoi des requettes    
    $req = $pdo->prepare($sql1);
    $req->execute($q);
    $data1 = $req->fetchAll(PDO::FETCH_ASSOC);
    $count = $req->rowCount($sql1);
	
    $req2 = $pdo->prepare($sql2);
    $req2->execute($q);
    $data2 = $req2->fetch(PDO::FETCH_ASSOC);	
    $req3 = $pdo->prepare($sql3);
    $req3->execute($q);
    $data3 = $req3->fetch(PDO::FETCH_ASSOC);
    $req4 = $pdo->prepare($sql4);
    $req4->execute($q);
    $data4 = $req4->fetch(PDO::FETCH_ASSOC);			
    $req5 = $pdo->prepare($sql5);
    $req5->execute($q);
    $data5 = $req5->fetch(PDO::FETCH_ASSOC);	        
    $req6 = $pdo->prepare($sql6);
    $req6->execute($q);
    $data6 = $req6->fetch(PDO::FETCH_ASSOC);	

    if ($count==0) { // Il n'y a rien en base sur l'année (pas de dépenses et pas de recettes)
        return null;         
    } else {
            // Calcul des sommes Annuelle
            $CA= !empty($data1[0]["SUM(montant)"]) ? $data1[0]["SUM(montant)"] : 0;  
            $DEPENSE= !empty($data1[1]["SUM(montant)"]) ? $data1[1]["SUM(montant)"] : 0;
            $SOLDE= $CA + $DEPENSE;
            $ACHAT= !empty($data3["SUM(montant)"]) ? $data3["SUM(montant)"] : 0;  
            $VENTE= !empty($data2["SUM(montant)"]) ? $data2["SUM(montant)"] : 0;
            $BENEFICE= $VENTE - $ACHAT;
			$LOCATION= !empty($data4["SUM(montant)"]) ? $data4["SUM(montant)"] : 0;
			$CHARGE= !empty($data5["SUM(montant)"]) ? $data5["SUM(montant)"] : 0;
			$IMPOT= !empty($data6["SUM(montant)"]) ? $data6["SUM(montant)"] : 0;
    }
   
    $VENTIL = 0;
    $PAIEMENT = 0;
    $ECHUS = 0;
    $ENCAISSEMENT = 0;
    $TRESO = 0;
    $SALAIRE = 0;
    $NON_DECLARE = 0;
	$SALAIRE_REEL = 0;
    $PROVISION_CHARGES = 0;    
    $PROVISION_CHARGES_REEL = 0;    
        
    // Calcul des sommes (boucle sur les mois relatifs)
    for ($m = 1; $m <= 12; $m++) {
        $VENTIL = $VENTIL + $BilanMensuel[$m]['VENTIL'];
        $PAIEMENT = $PAIEMENT + $BilanMensuel[$m]['PAIEMENT'];        
        $ECHUS = $ECHUS + $BilanMensuel[$m]['ECHUS'];
        $ENCAISSEMENT = $ENCAISSEMENT + $BilanMensuel[$m]['ENCAISSEMENT'];
        $SALAIRE = $SALAIRE + $BilanMensuel[$m]['SALAIRE'];
		$SALAIRE_REEL = $SALAIRE_REEL + $BilanMensuel[$m]['SALAIRE_REEL'];
        $PROVISION_CHARGES = $PROVISION_CHARGES + $BilanMensuel[$m]['PROVISION_CHARGES'];
        $PROVISION_CHARGES_REEL = $PROVISION_CHARGES_REEL + $BilanMensuel[$m]['PROVISION_CHARGES_REEL'];
                
    }
    
    // On garde que les derniers reports
    $TRESO = $BilanMensuel[12]['TRESO'];
    $REPORT_SALAIRE = $BilanMensuel[12]['REPORT_SALAIRE'];
    $REPORT_TRESO = $BilanMensuel[12]['REPORT_TRESO'];
        
    // Génération du Tableau :
    $TableauBilan = array (
        'CA' => $CA,                                                                   
        'DEPENSE' => $DEPENSE,                                                                   
        'SOLDE' => $SOLDE,                                                               
        'VENTIL' => $VENTIL,                                                                   
        'PAIEMENT' => $PAIEMENT,                                                                   
        'ECHUS' => $ECHUS,                                                             
        'ENCAISSEMENT' => $ENCAISSEMENT,                                                                   
        'TRESO' => $TRESO,
        'PROVISION_CHARGES' => $PROVISION_CHARGES,        
        'PROVISION_CHARGES_REEL' => $PROVISION_CHARGES_REEL,        
        'SALAIRE' => $SALAIRE,        
        'SALAIRE_REEL' => $SALAIRE_REEL,
        'REPORT_SALAIRE' => $REPORT_SALAIRE,                                                                   
        'REPORT_TRESO' => $REPORT_TRESO,
        'ACHAT' => $ACHAT,
        'VENTE' => $VENTE,
        'BENEFICE' => $BENEFICE,
        'LOCATION' => $LOCATION,         
        'CHARGE' => $CHARGE,
        'IMPOT' => $IMPOT,
        'NON_DECLARE' => $NON_DECLARE,
        'DECLARE' => ($CA - $NON_DECLARE)
    ); 

    Database::disconnect();    
    return $TableauBilan;
}


function CalculBilanAnnuelSansSocial($userid, $exerciceid, $BilanMensuel) {
    //TableauBilan = array[assoc]
    //CA
    //DEPENSE
    //SOLDE
    //VENTIL
    //PAIEMENT
    //ECHUS
    //ENCAISSEMENT
    //TRESO
    //SALAIRE
    //SALAIRE_REEL
    //REPORT_SALAIRE
    //REPORT_TRESO
    //ACHAT
    //VENTE
    //BENEFICE
    //CHARGE
    //LOCATION
    //IMPOT
    //NON_DECLARE
    //DECLARE
    
// Initialisation de la base
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);    

// Requette pour calcul de la somme Annuelle            
    $sql1 = "(SELECT SUM(montant) FROM recette WHERE
            user_id = :userid AND exercice_id = :exerciceid )
            UNION
            (SELECT SUM(montant * -1) FROM depense WHERE
            user_id = :userid AND exercice_id = :exerciceid )
            ";

// Requette pour calcul de la somme Annuelle des achats/ventes           
    $sql2 = "SELECT SUM(montant) FROM recette WHERE
            user_id = :userid AND exercice_id = :exerciceid AND type = 2
            ";  
            
    $sql3 = "SELECT SUM(montant) FROM depense WHERE
            user_id = :userid AND exercice_id = :exerciceid AND type = 2 
            ";      

// Requette pour calcul de la somme Annuelle des location           
    $sql4 = "SELECT SUM(montant) FROM recette WHERE
            user_id = :userid AND exercice_id = :exerciceid AND type = 3
            ";  

// Requette pour calcul de la somme Annuelle des charges            
    $sql5 = "SELECT SUM(montant) FROM depense WHERE
            user_id = :userid AND exercice_id = :exerciceid AND type = 3 
            ";  

// Requette pour calcul de la somme Annuelle des impots             
    $sql6 = "SELECT SUM(montant) FROM depense WHERE
            user_id = :userid AND exercice_id = :exerciceid AND type = 4 
            ";              
             
// Association des variables                            
    $q = array('userid' => $userid, 'exerciceid' => $exerciceid);                    
    
// Envoi des requettes    
    $req = $pdo->prepare($sql1);
    $req->execute($q);
    $data1 = $req->fetchAll(PDO::FETCH_ASSOC);
    $count = $req->rowCount($sql1);
    
    $req2 = $pdo->prepare($sql2);
    $req2->execute($q);
    $data2 = $req2->fetch(PDO::FETCH_ASSOC);    
    $req3 = $pdo->prepare($sql3);
    $req3->execute($q);
    $data3 = $req3->fetch(PDO::FETCH_ASSOC);
    $req4 = $pdo->prepare($sql4);
    $req4->execute($q);
    $data4 = $req4->fetch(PDO::FETCH_ASSOC);            
    $req5 = $pdo->prepare($sql5);
    $req5->execute($q);
    $data5 = $req5->fetch(PDO::FETCH_ASSOC);            
    $req6 = $pdo->prepare($sql6);
    $req6->execute($q);
    $data6 = $req6->fetch(PDO::FETCH_ASSOC);    

    if ($count==0) { // Il n'y a rien en base sur l'année (pas de dépenses et pas de recettes)
        return null;         
    } else {
            // Calcul des sommes Annuelle
            $CA= !empty($data1[0]["SUM(montant)"]) ? $data1[0]["SUM(montant)"] : 0;  
            $DEPENSE= !empty($data1[1]["SUM(montant)"]) ? $data1[1]["SUM(montant)"] : 0;
            $SOLDE= $CA + $DEPENSE;
            $ACHAT= !empty($data3["SUM(montant)"]) ? $data3["SUM(montant)"] : 0;  
            $VENTE= !empty($data2["SUM(montant)"]) ? $data2["SUM(montant)"] : 0;
            $BENEFICE= $VENTE - $ACHAT;
            $LOCATION= !empty($data4["SUM(montant)"]) ? $data4["SUM(montant)"] : 0;
            $CHARGE= !empty($data5["SUM(montant)"]) ? $data5["SUM(montant)"] : 0;
            $IMPOT= !empty($data6["SUM(montant)"]) ? $data6["SUM(montant)"] : 0;
    }
   
    $VENTIL = 0;
    $PAIEMENT = 0;
    $ECHUS = 0;
    $ENCAISSEMENT = 0;
    $TRESO = 0;
    $SALAIRE = 0;
    $NON_DECLARE = 0;
    $SALAIRE_REEL = 0;
        
    // Calcul des sommes (boucle sur les mois relatifs)
    for ($m = 1; $m <= 12; $m++) {
        $VENTIL = $VENTIL + $BilanMensuel[$m]['VENTIL'];
        $PAIEMENT = $PAIEMENT + $BilanMensuel[$m]['PAIEMENT'];        
        $ECHUS = $ECHUS + $BilanMensuel[$m]['ECHUS'];
        $ENCAISSEMENT = $ENCAISSEMENT + $BilanMensuel[$m]['ENCAISSEMENT'];
        $SALAIRE = $SALAIRE + $BilanMensuel[$m]['SALAIRE'];
        $SALAIRE_REEL = $SALAIRE_REEL + $BilanMensuel[$m]['SALAIRE_REEL'];         
    }
    
    // On garde que les derniers reports
    $TRESO = $BilanMensuel[12]['TRESO'];
    $REPORT_SALAIRE = $BilanMensuel[12]['REPORT_SALAIRE'];
    $REPORT_TRESO = $BilanMensuel[12]['REPORT_TRESO'];
        
    // Génération du Tableau :
    $TableauBilan = array (
        'CA' => $CA,                                                                   
        'DEPENSE' => $DEPENSE,                                                                   
        'SOLDE' => $SOLDE,                                                               
        'VENTIL' => $VENTIL,                                                                   
        'PAIEMENT' => $PAIEMENT,                                                                   
        'ECHUS' => $ECHUS,                                                             
        'ENCAISSEMENT' => $ENCAISSEMENT,                                                                   
        'TRESO' => $TRESO,     
        'SALAIRE' => $SALAIRE,        
        'SALAIRE_REEL' => $SALAIRE_REEL,
        'REPORT_SALAIRE' => $REPORT_SALAIRE,                                                                   
        'REPORT_TRESO' => $REPORT_TRESO,
        'ACHAT' => $ACHAT,
        'VENTE' => $VENTE,
        'BENEFICE' => $BENEFICE,
        'LOCATION' => $LOCATION,         
        'CHARGE' => $CHARGE,
        'IMPOT' => $IMPOT,
        'NON_DECLARE' => $NON_DECLARE,
        'DECLARE' => ($CA - $NON_DECLARE)
    ); 

    Database::disconnect();    
    return $TableauBilan;
}             

function CalculBilanAnnuel($userid, $exerciceid, $BilanMensuel) {   

    $TableauBilan = array();

    if ($_SESSION['options']['gestion_social']) {
        $TableauBilan = CalculBilanAnnuelAvecSocial($userid, $exerciceid, $BilanMensuel);
    } else {
        $TableauBilan = CalculBilanAnnuelSansSocial($userid, $exerciceid, $BilanMensuel);
    }   
    return $TableauBilan;
}             