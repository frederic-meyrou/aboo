<!-- 
© Copyright : Aboo / www.aboo.fr : Frédéric MEYROU : tous droits réservés
-->
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
        error_log("Erreur NumToMois : variable invalide.", 3, "./erreur.log");             
        return null;
    }		
}

// Transforme un nom de mois en numéro de mois
function MoisToNum($nom_mois)
{
    if (empty($nom_mois) || $nom_mois == null) {
        error_log("Erreur MoisToNum : variable invalide.", 3, "./erreur.log");           
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
        error_log("Erreur NumToTypeRecette : variable invalide.", 3, "./erreur.log");            
        return null;
    }		
}

// Transforme un numero de type recette en libellé
function TypeRecetteToNum($recette)
{
    if (empty($recette) || $recette == null) {
        error_log("Erreur TypeRecetteToNum : variable invalide.", 3, "./erreur.log");    
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
        error_log("Erreur NumToTypeDepense : variable invalide.", 3, "./erreur.log");            
        return null;
    }		
}

// Transforme un numero de type depense en libellé
function TypeDepenseToNum($depense)
{
    if (empty($depense) || $depense == null) {
        error_log("Erreur TypeDepenseToNum : variable invalide.", 3, "./erreur.log");              
        return null;
    } else {
    	global $Liste_Depense;
		$array = array_keys($Liste_Depense,$depense);
    	return $array[0]; 		
    }
}

// Tableau avec liste des periodicitee
$Liste_Periodicitee = array(1 => 'Ponctuel', 2 => 'Bi-Mensuel', 3 => 'Trimestriel', 6 => 'Semestriel', 12 => 'Annuel ou Lissé');


// Transforme un numéro de type en libelle de recette 
function NumToPeriodicitee($num)
{
	if (!$num == null) {
        global $Liste_Periodicitee;
        return $Liste_Periodicitee[$num];
    } else {
        error_log("Erreur NumToPeriodicitee : variable invalide.", 3, "./erreur.log"); 
        return null;
    }		
}

// Transforme un numero de type recette en libellé
function PeriodiciteeToNum($periode)
{
    if (empty($periode) || $periode == null) {
        error_log("Erreur PeriodiciteeToNum : variable invalide.", 3, "./erreur.log");         
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
        error_log("Erreur Ventillation : variable mois invalide.", 3, "./erreur.log");  
        return NULL;
    }
    if ($periodicitee < 0 or $periodicitee > 12 ) {
        error_log("Erreur Ventillation : variable periodicitee invalide.", 3, "./erreur.log");  
        return NULL;
    }
    if (($periodicitee+$mois-1) > 12 ) {
        error_log("Erreur Ventillation : variable periodicitee trop grande.", 3, "./erreur.log");  
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

// Transforme le numero de mois de l'annnée en numero de mois relatif
function MoisRelatif($num_mois, $exercice_mois) {
    if ($exercice_mois < 1 or $exercice_mois > 12) {
        error_log("Erreur MoisRelatif : variable exercice_mois invalide.", 3, "./erreur.log");  
        return NULL;
    }      
    $num_mois_relatif = ($num_mois - $exercice_mois +1);
	if ($num_mois_relatif < 0) {
		$num_mois_relatif = ( 12 + $num_mois_relatif ); 
	} else {
		$num_mois_relatif = $num_mois_relatif % 12;
	}
    if ( $num_mois_relatif == 0 ) { $num_mois_relatif = 12; }
	return $num_mois_relatif;
}

// Transforme un numero de mois relatif en numero de mois de l'année
function MoisAnnee($num_mois_relatif, $exercice_mois) {
    if ($num_mois_relatif < 1 or $num_mois_relatif > 12) {
        error_log("Erreur MoisAnnee : variable mois_relatif invalide.", 3, "./erreur.log");  
        return NULL;
    }
    if ($exercice_mois < 1 or $exercice_mois > 12) {
        error_log("Erreur MoisAnnee : variable exercice_mois invalide.", 3, "./erreur.log");  
        return NULL;
    }    
    $num_mois = ( $num_mois_relatif + $exercice_mois -1 ) % 12;           
    if ( $num_mois == 0 ) { $num_mois = 12; }
	return $num_mois;
}

// Export Excel
class FichierExcel {
 
	private $csv = array();
 
	/**
	 * Permet d'insérer une colone (même chose qu'une ligne, mais fort pratique pour le compréhension)
	 *
	 * @param string $file
	 * @return array
	 */
	function colonne($file)
	{
		$this->csv[] = $file;
		return $this->csv;
	}
 
	/**
	 * Permet d'insérer des lignes.
	 *
	 * @param string $file
	 * @return array 
	 */
	function insertion($file)
	{
		$this->csv[] = $file;
		return $this->csv;
	}
 
	/**
	 * Permet la création du fichier
	 *
	 * @param string $NomFichier Correspond au nom du fichier
	 */
	function output($NomFichier)
	{
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment;filename=$name");
		header("Content-Transfer-Encoding: binary");
		header('Pragma: no-cache');
		header('Expires: 0');
		header("Content-disposition: attachment; filename=$NomFichier.csv");
		$content = mb_convert_encoding(implode("\n", $this->csv), "Windows-1252", "UTF-8");
		echo $content;	
		exit;
	}
}