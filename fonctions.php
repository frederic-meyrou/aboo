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

// Tableau avec liste des mois de l'année
$Liste_Mois = array(1 => 'Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre');

// Transforme un numéro de mois en nom de mois 
function NumToMois($num_mois)
{
	if (!$num_mois == null) {
        global $Liste_Mois;
        return $Liste_Mois[$num_mois];
    } else {
        return null;
    }		
}

// Transforme un nom de mois en numéro de mois
function MoisToNum($nom_mois)
{
    if (empty($nom_mois) || $nom_mois == null) {
        return null;
    } else {
    	global $Liste_Mois;
		$array = array_keys($Liste_Mois,$nom_mois);
    	return $array[0]; 		
    }
}    

// Tableau avec liste des recettes
$Liste_Recette = array(1 => 'Abonnement','Revente','Location','Autre recette');


// Transforme un numéro de type en libelle de recette 
function NumToTypeRecette($type)
{
	if (!$type == null) {
        global $Liste_Recette;
        return $Liste_Recette[$type];
    } else {
        return null;
    }		
}

// Transforme un numero de type recette en libellé
function TypeRecetteToNum($recette)
{
    if (empty($recette) || $recette == null) {
        return null;
    } else {
    	global $Liste_Recette;
		$array = array_keys($Liste_Recette,$recette);
    	return $array[0]; 		
    }
}

// Tableau avec liste des depenses
$Liste_Depense = array(1 => 'Frais','Achat','Charges sociales','Impôt');

// Transforme un numéro de type en libelle de depense 
function NumToTypeDepense($type)
{
	if (!$type == null) {
        global $Liste_Depense;
        return $Liste_Depense[$type];
    } else {
        return null;
    }		
}

// Transforme un numero de type depense en libellé
function TypeDepenseToNum($depense)
{
    if (empty($depense) || $depense == null) {
        return null;
    } else {
    	global $Liste_Depense;
		$array = array_keys($Liste_Depense,$depense);
    	return $array[0]; 		
    }
}

// Tableau avec liste des periodicitee
$Liste_Periodicitee = array(1 => 'Ponctuel', 2 => 'Bi-Mensuel', 3 => 'Trimestriel', 6 => 'Semestriel', 12 => 'Annuel');


// Transforme un numéro de type en libelle de recette 
function NumToPeriodicitee($num)
{
	if (!$num == null) {
        global $Liste_Periodicitee;
        return $Liste_Periodicitee[$num];
    } else {
        return null;
    }		
}

// Transforme un numero de type recette en libellé
function PeriodiciteeToNum($periode)
{
    if (empty($periode) || $periode == null) {
        return null;
    } else {
    	global $Liste_Periodicitee;
		$array = array_keys($Liste_Periodicitee,$periode);
    	return $array[0]; 		
    }
}

// Ventille le montant d'un abonnement périodique depusi le mois courant sur le nombre de mois de la periodicitee 
function Ventillation($mois, $montant, $periodicitee) {
    // Vérifications
    if ($mois < 1 or $mois > 12) {
        error_log("Erreur : variable mois invalide.", 3, "./erreur.log");  
        return NULL;
    }
    if ($periodicitee < 0 or $periodicitee > 12 ) {
        error_log("Erreur : variable periodicitee invalide.", 3, "./erreur.log");  
        return NULL;
    }
    if (($periodicitee+$mois-1) > 12 ) {
        error_log("Erreur : variable periodicitee trop grande.", 3, "./erreur.log");  
        return NULL;
    }	
    $ArrayVENTILLATION = array (1 => 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);        
    // Ventille le montant dans un tableau annuel 
    $MontantMensuel = $montant / $periodicitee;
    for ($i = $mois; $i <= ($periodicitee+$mois-1); $i++) {
        $ArrayVENTILLATION[$i] = $MontantMensuel;
    }
    // Retourne un Array 
    return $ArrayVENTILLATION; 
}

