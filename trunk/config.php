<?php
// ** MySQL settings ** //

$cp->unique = 'b83b9c064c365cc68d675c8e1ca986904159a9f0';
define('CPATH', dirname(__FILE__) . '/' );
define('UNIQUE', 'b83b9c064c365cc68d675c8e1ca986904159a9f0');
define('SCRIPT_TITLE', 'Allegory');
define('SCRIPT_VERSION', '0.3.1');

#    define( "KNIFESQL", "yes");							# Comment this to use flatfiles
	define( "KNIFE_SQL_SERVER", "localhost");			# mySQL server
	define( "KNIFE_SQL_USER", "root");					# mySQL username
	define( "KNIFE_SQL_PASSWORD", "");					# mySQL password
    define( "KNIFE_SQL_DATABASE", "ajfork");			# mySQL database
    define( "KNIFE_SQL_TBL_PREFIX", "knife_");			# mySQL table prefix (unused)

	define( "DEFAULT_LANGUAGE", "nb_no.php");

$config_urlstyle = array(
	0 => "null",
	1 => "category",
	2 => "title",
	);
?>
