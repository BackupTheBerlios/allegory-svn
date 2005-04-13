<?php
	# auth.php
	
	if (!defined( "KNIFE_PATH" )) {
    	define( "KNIFE_PATH", dirname(__FILE__)."/");	# Absolute path to current script
    	}

	include_once(KNIFE_PATH.'/inc/class.users.php');				# load userclass - can't live without
	include_once(KNIFE_PATH.'/inc/functions.php');
	
	
	
	if (!$UserDB) {
		$UserDB = new KUsers;
		}
		
	$UserDB->verify();
	
	if (!$UserDB->username) {
		require(KNIFE_PATH.'/lang/'.$Settings->co[general][defaultlanguage]);
		echo "You are not logged in. Display login form here later... (./auth.php)";
		}
	else {
		if ($UserDB->language) {
			include_once(KNIFE_PATH.'/lang/'.$UserDB->language);
			}
		else {
			include_once(KNIFE_PATH.'/lang/'.$Settings->co[general][defaultlanguage]);
			}	
		# Great, some kind of login was successful - Tell this to the user	
		echo "Logged in as $UserDB->nickname";	
	}
?>	