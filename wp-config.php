<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier contient les réglages de configuration suivants : réglages MySQL,
 * préfixe de table, clefs secrètes, langue utilisée, et ABSPATH.
 * Vous pouvez en savoir plus à leur sujet en allant sur 
 * {@link http://codex.wordpress.org/fr:Modifier_wp-config.php Modifier
 * wp-config.php}. C'est votre hébergeur qui doit vous donner vos
 * codes MySQL.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d'installation. Vous n'avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en "wp-config.php" et remplir les
 * valeurs.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define('DB_NAME', 'WPaboo');

/** Utilisateur de la base de données MySQL. */
define('DB_USER', 'root');

/** Mot de passe de la base de données MySQL. */
define('DB_PASSWORD', '');

/** Adresse de l'hébergement MySQL. */
define('DB_HOST', 'localhost');

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define('DB_CHARSET', 'utf8');

/** Type de collation de la base de données. 
  * N'y touchez que si vous savez ce que vous faites. 
  */
define('DB_COLLATE', '');

/**#@+
 * Clefs uniques d'authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant 
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clefs secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n'importe quel moment, afin d'invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         ']:D8duX7m/z;b#Ch#[8WF+&7|Bi1H9~1|$f)i53=[X7tVv=vCBr-01jYEk.sx$O&');
define('SECURE_AUTH_KEY',  '|c{Vj7|}@4IU}Gbx.h<J^ ]H3{0:se2vy2C$5)|E&dh@c+-~2,sCwJ^5g:;P*)wJ');
define('LOGGED_IN_KEY',    'Dg}Ab|TWk}|az|i~+4l39vp8-@%q]uxT-eV4P0{FVp{I]!Q8:=_Sw!.Z5 ?RxBtw');
define('NONCE_KEY',        'io:fzkpx=|^F|1H%7_WNG%M/akuUj;E+YHcAzieUXEvcT uZWJ/FbS(xD<.c[iEw');
define('AUTH_SALT',        '& WDZA4<TYN<:-ojQ@F7[L3#L8~dMz4]6P<P~ri55kaT0w6~N-+<x2=INf$#K2^%');
define('SECURE_AUTH_SALT', 'A]>GLx=*?r}R*]A:=?WWK8|E):o18|OgtSdo8zbl63[D) gai)? `PXi.9G%w:CE');
define('LOGGED_IN_SALT',   'W?}@3{yQ|hI7hA?;zD td~|I1+ft@l*|=On e9BHWn%Ed4C(Fs4oGt:6J ?Yv7pt');
define('NONCE_SALT',       'H;T@OK]^TnJsegMLFmRj4VE(s?{Z&9!e,RwuH79v>Zd/8oGp|rRaEHhgf#tByO+|');
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique. 
 * N'utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés!
 */
$table_prefix  = 'wp_';

/**
 * Langue de localisation de WordPress, par défaut en Anglais.
 *
 * Modifiez cette valeur pour localiser WordPress. Un fichier MO correspondant
 * au langage choisi doit être installé dans le dossier wp-content/languages.
 * Par exemple, pour mettre en place une traduction française, mettez le fichier
 * fr_FR.mo dans wp-content/languages, et réglez l'option ci-dessous à "fr_FR".
 */
define('WPLANG', 'fr_FR');

/** 
 * Pour les développeurs : le mode deboguage de WordPress.
 * 
 * En passant la valeur suivante à "true", vous activez l'affichage des
 * notifications d'erreurs pendant votre essais.
 * Il est fortemment recommandé que les développeurs d'extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de 
 * développement.
 */ 
define('WP_DEBUG', false); 

/* C'est tout, ne touchez pas à ce qui suit ! Bon blogging ! */

/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');