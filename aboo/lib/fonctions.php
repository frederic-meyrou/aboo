<?php

// Check si le user est Administrateur
function IsAdmin()
{
    if(isset($_SESSION['authent']) && isset($_SESSION['authent']['id'])) {
        // Initialisation de la base
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // requette pour calcul des ventilations abo
        $q = array('userid' => $_SESSION['authent']['id']);          
        $sql = "SELECT * FROM user WHERE id = :userid AND administrateur = '1'";       
        // Envoi des requettes 
        $req = $pdo->prepare($sql);
        $req->execute($q);
        //$data = $req->fetch(PDO::FETCH_ASSOC);
        $count = $req->rowCount($sql);
        if ($count==0) { // Le user n'est pas Admin
            return false;               
        } else { // Le user est Admin                       
            return true;   
        }
    } else { // Authent Ko
        return false;        
    }         
}

// Vérifie que la chaine est une date (Booléen)
function IsDate( $Str )
{
  $Stamp = strtotime( $Str );
  $Month = date( 'm', $Stamp );
  $Day   = date( 'd', $Stamp );
  $Year  = date( 'Y', $Stamp );

  return checkdate( $Month, $Day, $Year );
}

// Formate l'affichage d'une date issue de la BDD au format Français
function DateFr($date) {
    if (!empty($date)) {
        $localdate = date_create($date);
        return date_format($localdate, 'd/m/Y');
    } else {
        return '';
    }   
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
        error_log("Erreur NumToMois : variable invalide appelé depuis " . $_SERVER['PHP_SELF'] . "\n", 3, "./erreur.log");             
        return null;
    }		
}

// Transforme un nom de mois en numéro de mois
function MoisToNum($nom_mois)
{
    if (empty($nom_mois) || $nom_mois == null) {
        error_log("Erreur MoisToNum : variable invalide appelé depuis " . $_SERVER['PHP_SELF'] . "\n", 3, "./erreur.log");           
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
        error_log("Erreur NumToTypeRecette : variable invalide appelé depuis " . $_SERVER['PHP_SELF'] . "\n", 3, "./erreur.log");            
        return null;
    }		
}

// Transforme un numero de type recette en libellé
function TypeRecetteToNum($recette)
{
    if (empty($recette) || $recette == null) {
        error_log("Erreur TypeRecetteToNum : variable invalide appelé depuis " . $_SERVER['PHP_SELF'] . "\n", 3, "./erreur.log");    
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
        error_log("Erreur NumToTypeDepense : variable invalide appelé depuis " . $_SERVER['PHP_SELF'] . "\n", 3, "./erreur.log");            
        return null;
    }		
}

// Transforme un numero de type depense en libellé
function TypeDepenseToNum($depense)
{
    if (empty($depense) || $depense == null) {
        error_log("Erreur TypeDepenseToNum : variable invalide appelé depuis " . $_SERVER['PHP_SELF'] . "\n", 3, "./erreur.log");              
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
        error_log("Erreur NumToPeriodicitee : variable invalide appelé depuis " . $_SERVER['PHP_SELF'] . "\n", 3, "./erreur.log"); 
        return null;
    }		
}

// Transforme un numero de type recette en libellé
function PeriodiciteeToNum($periode)
{
    if (empty($periode) || $periode == null) {
        error_log("Erreur PeriodiciteeToNum : variable invalide appelé depuis " . $_SERVER['PHP_SELF'] . "\n", 3, "./erreur.log");         
        return null;
    } else {
    	global $Liste_Periodicitee;
		$array = array_keys($Liste_Periodicitee,$periode);
    	return $array[0]; 		
    }
}


// Tableau avec liste des statut fiscaux
$Liste_Regime_Fiscal = array(0 => 'Non défini', 1 => 'Auto-Entrepreneur RSI', 2 => 'Auto-Entrepreneur CIPAV', 3 => 'Entreprise Individuelle (IE) BNC régime Micro', 
                             4 => 'Entreprise Individuelle (IE) BNC Réel en franchine de TVA', 5 => 'Association en franchise de TVA' );

// Transforme un numéro de regime fiscal en libelle 
function NumToRegimeFiscal($num)
{
    if ($num == 0 || $num == '0') {
        global $Liste_Regime_Fiscal;
        return $Liste_Regime_Fiscal[0];        
    } elseif (!$num == null) {
        global $Liste_Regime_Fiscal;
        return $Liste_Regime_Fiscal[$num];
    } else {
        error_log("Erreur NumToRegimeFiscal : variable invalide appelé depuis " . $_SERVER['PHP_SELF'] . "\n", 3, "./erreur.log"); 
        return null;
    }       
}

function RegimeFiscalToNum($regime)
{
    if (empty($regime) || $regime == null) {
        error_log("Erreur RegimeFiscalToNum : variable invalide appelé depuis " . $_SERVER['PHP_SELF'] . "\n", 3, "./erreur.log");    
        return null;
    } else {
        global $Liste_Regime_Fiscal;
        $array = array_keys($Liste_Regime_Fiscal,$regime);
        return $array[0]; 
    }      
}

// Ventille le montant d'un abonnement périodique depusi le mois courant sur le nombre de mois de la periodicitee 
function Ventillation($mois, $montant, $periodicitee) {
    // Vérifications
    if ($mois < 1 or $mois > 12) {
        error_log("Erreur Ventillation : variable mois invalide appelé depuis " . $_SERVER['PHP_SELF'] . "\n", 3, "./erreur.log");  
        return NULL;
    }
    if ($periodicitee < 0 or $periodicitee > 12 ) {
        error_log("Erreur Ventillation : variable periodicitee invalide appelé depuis " . $_SERVER['PHP_SELF'] . "\n", 3, "./erreur.log");  
        return NULL;
    }
    if (($periodicitee+$mois-1) > 12 ) {
        error_log("Erreur Ventillation : variable periodicitee trop grande appelé depuis " . $_SERVER['PHP_SELF'] . "\n", 3, "./erreur.log");  
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
        error_log("Erreur MoisRelatif : variable exercice_mois invalide appelé depuis " . $_SERVER['PHP_SELF'] . "\n", 3, "./erreur.log");  
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
        error_log("Erreur MoisAnnee : variable mois_relatif invalide appelé depuis " . $_SERVER['PHP_SELF'] . "\n", 3, "./erreur.log");  
        return NULL;
    }
    if ($exercice_mois < 1 or $exercice_mois > 12) {
        error_log("Erreur MoisAnnee : variable exercice_mois invalide appelé depuis " . $_SERVER['PHP_SELF'] . "\n", 3, "./erreur.log");  
        return NULL;
    }    
    $num_mois = ( $num_mois_relatif + $exercice_mois -1 ) % 12;           
    if ( $num_mois == 0 ) { $num_mois = 12; }
	return $num_mois;
}

// MyBaseURL
function MyBaseURL()
{
    /* First we need to get the protocol the website is using */
    $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"], 0, 5)) == 'https' ? 'https://' : 'http://';

    /* returns /myproject/index.php */
    $path = $_SERVER['PHP_SELF'];

    /*
     * returns an array with:
     * Array (
     *  [dirname] => /myproject/
     *  [basename] => index.php
     *  [extension] => php
     *  [filename] => index
     * )
     */
    $path_parts = pathinfo($path);
    $directory = $path_parts['dirname'];
    /*
     * If we are visiting a page off the base URL, the dirname would just be a "/",
     * If it is, we would want to remove this
     */
    $directory = ($directory == "/") ? "" : $directory;

    /* Returns localhost OR mysite.com */
    $host = $_SERVER['HTTP_HOST'];

    /*
     * Returns:
     * http://localhost/mysite
     * OR
     * https://mysite.com
     */
    return $protocol . $host . $directory;
}

// Vérification avec la méthode de Luhn (Vérif SIRET et CB)
function checkLuhn($val) {
    $len = strlen($val);
    $total = 0;
    for ($i = 1; $i <= $len; $i++) {
        $chiffre = substr($val,-$i,1);
        if($i % 2 == 0) {
            $total += 2 * $chiffre;
            if((2 * $chiffre) >= 10) $total -= 9;
            }
        else $total += $chiffre;
        }
        if($total % 10 == 0) return true; else return false;
}

// SIREN = 9 premiers chiffres du SIRET
function nSIREN($siret) {
    return substr($siret,0,9);
}
 
// N°TVA = FR + clé + SIREN
function nTVA($siren) {
    return "FR" . (( 12 + 3 * ( $siren % 97 ) ) % 97 ) . $siren;
}