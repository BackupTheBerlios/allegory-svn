<?php
// ** MySQL settings ** //
#$cp->unique = 'b83b9c064c365cc68d675c8e1ca986904159a9f0';
define('CPATH', dirname(__FILE__) . '/' );
#define('UNIQUE', 'b83b9c064c365cc68d675c8e1ca986904159a9f0');
define('SCRIPT_TITLE', 'Allegory');
define('SCRIPT_VERSION', '0.3.1');

/*	
	DEEEEEEEEEEEEEEEEEEEEEEEEPRECATED
	
	define( "KNIFE_SQL_SERVER", $Settings->co[storage][mysqlhost]);			# mySQL server
	define( "KNIFE_SQL_USER", $Settings->co[storage][mysqluser]);					# mySQL username
	define( "KNIFE_SQL_PASSWORD", $Settings->co[storage][mysqlpass]);					# mySQL password
    define( "KNIFE_SQL_DATABASE", $Settings->co[storage][mysqldatabase]);			# mySQL database
    define( "KNIFE_SQL_TBL_PREFIX", "knife_");			# mySQL table prefix (unused)
    
    
    */

	define( "DEFAULT_LANGUAGE", "nb_no.php");

$config_urlstyle = array(
	0 => "null",
	1 => "category",
	2 => "title",
	);
	
	
$config_avatardimensions = array(
	"comments" => array(
		"width" => 20,
		"height" => 20,
		),
		
	"articles" => array(
		"width" => 30,
		"height" => 30,
		),
	);
?>
