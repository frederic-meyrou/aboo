<!-- 
© Copyright : Aboo / www.aboo.fr : Frédéric MEYROU : tous droits réservés
-->
<?php

function CalculBilanMensuel($userid, $exerciceid, $exercicetreso) {   
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
    //REPORT_SALAIRE
    //REPORT_TRESO
    //NON_DECLARE

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
        // Envoi des requettes 
        $req4 = $pdo->prepare($sql4);
        $req4->execute($q2);
        $data4 = $req4->fetch(PDO::FETCH_ASSOC);
        $req5 = $pdo->prepare($sql5);
        $req5->execute($q2);
        $data5 = $req5->fetch(PDO::FETCH_ASSOC);                            
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
        $REPORT_TRESO_{$m}= $TRESO_{$m} - $SALAIRE_{$m};
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
            'REPORT_SALAIRE' => $REPORT_SALAIRE_{$m},                                                                   
            'REPORT_TRESO' => $REPORT_TRESO_{$m},
            'NON_DECLARE' => $NON_DECLARE_{$m}
        ); 
   } // End for       

    Database::disconnect();       
    return $TableauBilan;
}

function SauveBilanMensuel($userid, $exerciceid, $BilanMensuel) {
    
// Initialisation de la base
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    for ($mois = 1; $mois <= 12; $mois++) {
        
        // On test que l'enregistrement existe
        $sql = "SELECT * FROM bilan WHERE user_id = ?, exercice_id = ?, mois = ?";
        $q = $pdo->prepare($sql);
        $data = $req->fetch(PDO::FETCH_ASSOC);
        $count = $req->rowCount($sql);

        if ($count==0) { // On crée l'enregistrement        
            $sql2 = "INSERT INTO bilan (user_id,exercice_id,mois,ca,depenses,ventilation,paiements,encaissements,echus,salaire,treso,report_treso,report_salaire) values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $q2 = array($userid, $exerciceid, $mois, $BilanMensuel[$mois]['CA'], $BilanMensuel[$mois]['DEPENSE'], $BilanMensuel[$mois]['VENTIL'], $BilanMensuel[$mois]['PAIEMENT'], $BilanMensuel[$mois]['ENCAISSEMENT'], $BilanMensuel[$mois]['ECHUS'], $BilanMensuel[$mois]['SALAIRE'], $BilanMensuel[$mois]['TRESO'], $BilanMensuel[$mois]['REPORT_TRESO'], $BilanMensuel[$mois]['REPORT_SALAIRE']);   
        } else { // On met à jour l'enregistrement
            $sql2 = "UPDATE bilan set ca=?,depenses=?,ventilation=?,paiements=?,encaissements=?,echus=?,salaire=?,treso=?,report_treso=?,report_salaire=? WHERE user_id = ? AND exercice_id = ? AND mois = ?";
            $q2 = array($BilanMensuel[$mois]['CA'], $BilanMensuel[$mois]['DEPENSE'], $BilanMensuel[$mois]['VENTIL'], $BilanMensuel[$mois]['PAIEMENT'], $BilanMensuel[$mois]['ENCAISSEMENT'], $BilanMensuel[$mois]['ECHUS'], $BilanMensuel[$mois]['SALAIRE'], $BilanMensuel[$mois]['TRESO'], $BilanMensuel[$mois]['REPORT_TRESO'], $BilanMensuel[$mois]['REPORT_SALAIRE'],$userid, $exerciceid, $mois);               
        }
        // On execute la requette                  
        $req2 = $pdo->prepare($sql2);
        $req2->execute($q2);
    }                 
    Database::disconnect();      
}


function CalculBilanAnnuel($userid, $exerciceid, $BilanMensuel) {
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
    
    // Calcul des sommes (boucle sur les mois relatifs)
    for ($m = 1; $m <= 12; $m++) {
        $VENTIL = $VENTIL + $BilanMensuel[$m]['VENTIL'];
        $PAIEMENT = $PAIEMENT + $BilanMensuel[$m]['PAIEMENT'];        
        $ECHUS = $ECHUS + $BilanMensuel[$m]['ECHUS'];
        $ENCAISSEMENT = $ENCAISSEMENT + $BilanMensuel[$m]['ENCAISSEMENT'];
        $SALAIRE = $SALAIRE + $BilanMensuel[$m]['SALAIRE'];
        $NON_DECLARE = $NON_DECLARE + $BilanMensuel[$m]['NON_DECLARE'];
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

?>