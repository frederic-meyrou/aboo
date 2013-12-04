<?php

// Vérifie que la chaine est une date (Booléen)
function IsDate( $Str )
{
  $Stamp = strtotime( $Str );
  $Month = date( 'm', $Stamp );
  $Day   = date( 'd', $Stamp );
  $Year  = date( 'Y', $Stamp );

  return checkdate( $Month, $Day, $Year );
}

// Transforme un numéro de mois en nom de mois 
function NumToMois( $mumois )
{
	$mois = array(1 => 'Janvier','Février','Mars','Avril','Mais','Juin','Juiller','Août','Septembre','Octobre','Novembre','Décembre');
	return $mois[$nummois]; 		
}

// Transforme un nom de mois en numéro de mois
function MoisToNum( $nommois )
{
	$array = array(1 => 'Janvier','Février','Mars','Avril','Mais','Juin','Juiller','Août','Septembre','Octobre','Novembre','Décembre');
	return array_keys($array,$nomois); 		
}

// Transforme un numéro de mois en nom de mois (null si mois invalide)
function Mois( $mumois )
{
	switch ($mumois) {
	    case 1:
	        return 'Janvier';
	    case 2:
	        return 'Février';
	    case 3:
	        return 'Mars';    
	    case 4:
	        return 'Avril';
	    case 5:
	        return 'Mais';
	    case 6:
	        return 'Juin';
	    case 7:
	        return 'Juiller';
	    case 8:
	        return 'Août';
	    case 9:
	        return 'Septembre';																					
	    case 10:
	        return 'Octobre';
	    case 11:
	        return 'Novembre';
	    case 12:
	        return 'Décembre';
		default:
			return null;	
	}		
}

// Initialise un tableau de ventilation sur 12 mois
function InitialsationVentilation() {
    global $ArrayVENTILLATION;
    $ArrayVENTILLATION = array (0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);    
} 

// 
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

