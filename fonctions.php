<?php

function IsDate( $Str )
{
  $Stamp = strtotime( $Str );
  $Month = date( 'm', $Stamp );
  $Day   = date( 'd', $Stamp );
  $Year  = date( 'Y', $Stamp );

  return checkdate( $Month, $Day, $Year );
}

function InitialsationVentilation() {
    global $ArrayVENTILLATION;
    $ArrayVENTILLATION = array (0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);    
}

function Ventillation($mois, $montant, $nombre) {
    // Vérifications
    if ($mois < 1 or $mois > 12) {
        error_log("Erreur : variable mois invalide.", 3, "./erreur.log");  
        return NULL;
    }
    if ($nombre < 0 or $nombre > 12 ) {
        error_log("Erreur : variable nombre invalide.", 3, "./erreur.log");  
        return NULL;
    }
    if (($nombre+$mois-1) > 12 ) {
        error_log("Erreur : variable nombre trop grande.", 3, "./erreur.log");  
        return NULL;
    }
    
    // Ventille le montant dans un tableau annuel 
    InitialsationVentilation();
    $MontantMensuel = $montant / $nombre;
    for ($i = $mois; $i <= ($nombre+$mois-1); $i++) {
        $ArrayVENTILLATION[$i] = $MontantMensuel;
    }
    // Debug
    print_r($ArrayVENTILLATION);
    // Retourne un Array 
    return $ArrayVENTILLATION; 
}

