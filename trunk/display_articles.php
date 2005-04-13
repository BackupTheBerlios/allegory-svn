<?php

	if (!defined( "KNIFE_PATH" )) {
    	define( "KNIFE_PATH", dirname(__FILE__)."/");	# Absolute path to current script
    	}

	include_once(KNIFE_PATH.'/inc/class.articles.php');
	include_once(KNIFE_PATH.'/inc/class.comments.php');
	include_once(KNIFE_PATH.'/inc/class.users.php');				# load userclass - can't live without
	include_once(KNIFE_PATH.'/inc/class.settings.php');
	include_once(KNIFE_PATH.'/inc/class.parse.php');
	
	include_once(KNIFE_PATH.'/inc/functions.php');
	include_once(KNIFE_PATH.'/plugins/markdown.php');

	$pathinfo_array	= explode("/",$_SERVER[PATH_INFO]);
	$ACDB 	= new KComments;
	$UserDB 		= new KUsers;
	$AADB 		= new KArticles;
	$Settings 		= new KSettings;
	$Parser = new Parser;
	
	$Settings->getCats();			#
	$Settings->getTemplates();		#	Initialize settings
	$Settings->getConfig();			#
	
	
	$settingsdatabase = new SettingsStorage('settings');
	$alltemplates = $Settings->te;
	$allcats = $Settings->ca;
	$Config = $Settings->co;
	$allusers = $UserDB->getusers();
#	$null = $UserDB->verify();

	include_once(KNIFE_PATH.'/config.php');					# load temporary config

	#FIXME: Recognize cookies here
	require(KNIFE_PATH.'/lang/'.$Settings->co[general][defaultlanguage]);
#
#	Reset some variables
#
$timestamp = 0;

#
#	Display articles
#

if ($Settings->co[storage][backend] == "mysql") { define("KNIFESQL", "yes"); }
	
if ($template) {
	$template = $Settings->te[$template];
	}
else { 
	$template = $Settings->te[1];
	}
if (!$amount && isset($_GET[amount])) { 
	$amount = $_GET[amount];											#FIXME
	}
if (!$cat && isset($_GET[cat])) {
	$cat = "$_GET[cat]";
	}
if (!$from && isset($_GET[from])) {
	$from = "$_GET[from]";
	}
	
# Get all the articles... # ??? FIXME-remove?
if ($static) { $from = null; }
$allarticles = $AADB->listarticles($amount, $from);



if ($static === true) {

	if ($select_article) {
		#$Parser->Article();
		include(KNIFE_PATH."/display_article.php");
		}
	else {
		$Parser->Articlelist($from, $amount, $static);
		
		}	
	}
	
else {	

	if (!$_GET[k] and !$pathinfo_array[1]) {
		$Parser->Articlelist($from, $amount, $static);
		}
	else {
		#$Parser->Article();
		include(KNIFE_PATH."/display_article.php");
		}
}



/*


if (!$_GET[k] and !$pathinfo_array[1] or $static) {
	$Parser->ArticleList($from, $amount);
}
	
	elseif (($_GET[k] or $pathinfo_array[1]) and !$static) {
		include(KNIFE_PATH."/display_article.php");
		}*/
			
	if ($_GET[debug] and !$static) {
					echo "<br /><fieldset><legend>".i18n("dashboard_DBI")."<legend><pre>";
				print_r($_GET);
				echo "\n\n-----------&lt;- get  | post   -&gt;---------------\n\n";
				print_r($_POST);
				echo "\n\n-----------&lt;- post | cookie -&gt;---------------\n\n;";
				print_r($_COOKIE);
				print_r($UserDB->collectlogin());
				echo "</pre></fieldset>";
				}
	unset ($static);
	unset ($cat);
	unset ($allarticles);
	unset ($from);
	unset ($amount);
	unset ($template);

?>

<!--
    
    	Content publishing: <?=SCRIPT_TITLE;?> <?=SCRIPT_VERSION;?> 
    	GPL-licensed by Øivind Hoel ( http://appelsinjuice.org/ - allegory at appelsinjuice org )
    
-->