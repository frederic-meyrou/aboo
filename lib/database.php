<!-- 
© Copyright : Aboo / www.aboo.fr : Frédéric MEYROU : tous droits réservés
-->
<?php

define('DB_HOST', 'localhost');
define('DB_SCHEMA', 'dbaboo');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_ENCODING', 'utf8');

class Database
{
    private static $dbName = DB_SCHEMA ;
    private static $dbHost = DB_HOST ;
    private static $dbUsername = DB_USER;
    private static $dbUserPassword = DB_PASSWORD;
    private static $dbUserEncoding = DB_ENCODING;	
     
    private static $cont  = null;
     
    public function __construct() {
        die('Init function is not allowed');
    }
     
    public static function connect()
    {
       // One connection through whole application
       if ( null == self::$cont )
       {     
        try
        {
          self::$cont =  new PDO( "mysql:host=".self::$dbHost.";"."dbname=".self::$dbName, self::$dbUsername, self::$dbUserPassword); 
        }
        catch(PDOException $e)
        {
          die($e->getMessage()); 
        }
       }
       return self::$cont;
    }
     
    public static function disconnect()
    {
        self::$cont = null;
    }
}
?>

<?php
/**
$dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_SCHEMA;
$options = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
);

if( version_compare(PHP_VERSION, '5.3.6', '<') ){
    if( defined('PDO::MYSQL_ATTR_INIT_COMMAND') ){
        $options[PDO::MYSQL_ATTR_INIT_COMMAND] = 'SET NAMES ' . DB_ENCODING;
    }
}else{
    $dsn .= ';charset=' . DB_ENCODING;
}

$conn = @new PDO($dsn, DB_USER, DB_PASSWORD, $options);

if( version_compare(PHP_VERSION, '5.3.6', '<') && !defined('PDO::MYSQL_ATTR_INIT_COMMAND') ){
    $sql = 'SET NAMES ' . DB_ENCODING;
    $conn->exec($sql);
}
**/
?>
