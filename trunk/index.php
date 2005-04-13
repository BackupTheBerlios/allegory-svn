<?php

#
#	required init
#	

	if (!defined( "KNIFE_PATH" )) {
    	define( "KNIFE_PATH", dirname(__FILE__)."/");	# Absolute path to current script
    	}
    	
	include_once(KNIFE_PATH.'/inc/functions.php');			# load common functions
	include_once(KNIFE_PATH.'/inc/class.users.php');
	include_once(KNIFE_PATH.'/inc/class.settings.php');
	include_once(KNIFE_PATH.'/inc/class.comments.php');
	include_once(KNIFE_PATH.'/inc/class.articles.php');
	
/*
 *	Required setup
 */	
	$Settings 	= new KSettings;								# Initiate the settings class
	$Settings->getConfig();										# Load the configuration
	include_once(KNIFE_PATH.'/config.php');						# Include config file (FIXME)
	
	if ($Settings->co[storage][backend] == "mysql") { 
		define("KNIFESQL", "yes");								# Flat or mysql
		}

#
# 	Load the user class and run verify
$User = new KUsers;
$null = $User->verify();

# 	$User will be false if no login could be found
# 	User data is accessible via $Userclass->username, etc, or $User[username], etc.


#
#	Now, load the language file chosen or load default one
	if ($User->language) {
		include_once(KNIFE_PATH.'/lang/'.$User->language);
		}
	else {
		include_once(KNIFE_PATH.'/lang/'.$Settings->co[general][defaultlanguage]);
		}
#
#	We need to display the login form if no good login data is found
if (!$User->username) {

	$moduletitle = SCRIPT_TITLE . " - ". i18n("login_modtitle");
	$menus[0] = "";
	
	# FIXME: Insert menu filter?

	$i18nfiles = FileFolderList("./lang/", $depth = 1, $current = '', $level=0);
	$available_languages = available_languages($i18nfiles);

	foreach ($available_languages as $null => $languagedata) {
		unset($langchecked);
		if ($languagedata[file] == $_COOKIE[allegory_language]) { $langchecked = 'checked="checked" '; }
		$lang_input_fields .= '<input id="ls'.$languagedata[file].'" type="radio" name="language" value="'.$languagedata[file].'" '.$langchecked.'/> 
		<label for="ls'.$languagedata[file].'">'.$languagedata[langinternational].' ( '.$languagedata[langnational].' )</label><br />';
		}

	$main_content = '
	<div id="login_wrapper">
		<div class="div_normal">
		<fieldset>
		<legend>'.i18n("login_Login").'</legend><p>'.i18n("login_AuthReq").'</p>
	<form id="login" method="post" action="">
	<input type="hidden" name="panel" value="dashboard" />
<p><input class="inshort" type="text" name="username" id="login_username" /> <label for="login_username">'.i18n("login_Username").'</label></p>
<p><input class="inshort" type="password" name="password" id="login_password" /> <label for="login_password">'.i18n("login_Password").'</label></p>
</fieldset>
<p><input type="submit" name="sendlogin" value="'.i18n("login_Login").'" /></p>
</div>
<div class="div_extended">
<fieldset>
	<legend>'.i18n("generic_language").'</legend>
<p>'.$lang_input_fields.'</p>

	</div></form></div>';
	}

#
#	Oh, great. Somehow we're logged in.
if ($User->username) {

	$Comments	= new KComments;
	$Articles 	= new KArticles;
	
	$Settings->getCats();			#
	$Settings->getTemplates();		#	Initialize settings
	
#
#	Set up the first menu
	$menus[0] = "
	<ul>
		<li id=\"main_menu_dashboard\"><a href=\"index.php\">".i18n("menu_dashboard")."</a></li>
		<li id=\"main_menu_write\"><a href=\"?panel=write\">".i18n("menu_write")."</a></li>
		<li id=\"main_menu_edit\"><a href=\"?panel=edit\">".i18n("menu_edit")."</a></li>
		<li id=\"main_menu_options\"><a href=\"?panel=options\">".i18n("menu_options")."</a></li>
		<li id=\"main_menu_help\"><a href=\"?panel=help\">".i18n("menu_help")."</a></li>
		<li id=\"main_menu_plugins\"><a href=\"#\">".i18n("menu_plugins")."</a></li>
		<li id=\"main_menu_info\"><a href=\"?panel=logout\">$User->nickname (".i18n("menu_logout").")</a></li>
	</ul>
	";

	# FIXME: Insert menu filter?
	
	if($_POST[panel] == "write" || $_GET[panel] == "write") {
		include_once(KNIFE_PATH."/write.php");
	}

	if($_POST[panel] == "template" || $_GET[panel] == "template") {
		include_once(KNIFE_PATH."/template.php");
	}
	
	if($_POST[panel] == "edit" || $_GET[panel] == "edit") {
		include_once(KNIFE_PATH."/edit.php");
	}

	if($_POST[panel] == "users" || $_GET[panel] == "users") {
		include_once(KNIFE_PATH."/users.php");
	}

	if($_POST[panel] == "options" || $_GET[panel] == "options") {
		include_once(KNIFE_PATH."/options.php");
		}
		
	if($_POST[panel] == "help" || $_GET[panel] == "help") {
		include_once(KNIFE_PATH."/help.php");
		}
		
	if($_POST[panel] == "logout" || $_GET[panel] == "logout") {
		$menus[0] = "";									# kill menu
		$logout = $User->logout();					# kill user
		$moduletitle = "Logout";
		$statusmessage = "Successfully logged out.";
		$main_content = i18n("login_loggedout");
		
		#
		#	Redirect the user (hopefully)
		header("Location: http://" . $_SERVER['HTTP_HOST']
                     . dirname($_SERVER['PHP_SELF'])
                     . "/" . "index.php");
		}

	#
	#	Surrender, insert the dashboard
	if (!$_GET[panel] && !$_POST[panel] or $_POST[panel] == "dashboard") {
		include_once(KNIFE_PATH."/dashboard.php");
	}
}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="no" xml:lang="no">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo "$moduletitle ($User->username)"; ?></title>
<style type="text/css">

/*
	Tag redefinition
*/

html,body {
	color: #333;
	font: 0.87em "Trebuchet MS";
	margin: 0;
}

body {
	background: #fffaee url(graphics/talk.png) top right no-repeat;
}

a {
	text-decoration: none;
	display: inline-block;
	color: #f32988;
	}
	
a:hover {
	}

h1, h2, h3 {
	margin-top: 3px;
/*	font-family: "Georgia";*/
	}
/*
	Major ID's
*/

#body {
	margin: auto;
	background: #fff;
	width: 910px;
	border: 5px solid #f3eedc;
	}
#mainframe {
	padding: 0 5px 5px 5px;
	border: 1px solid #e6d69e;
	}
	
#header h1 {
	font-size: 23px;
}

#header h1:first-letter {
	color: #f32988;
	}
	
/*
	Menus
*/
	
#menu li a {
	padding-left: 25px;
	}

#menu li {
	display: inline;
	margin: 0 8px 0 0;
	padding: 3px;
	border-bottom: 3px solid #f1f3d8;
	}
#menu li:hover {
	background: #fff9e2;
	cursor: pointer;
	border-bottom: 3px solid #819faf;
	}
	
li#main_menu_dashboard {
	background: url(graphics/icons/dashboard.png) no-repeat top left;
	}
	li#main_menu_dashboard:hover {
	background-image: url(graphics/icons/dashboard.png);
	background-repeat: no-repeat;
	}
	
li#main_menu_write {
	background: url(graphics/icons/write.png) no-repeat top left;
	}
	li#main_menu_write:hover {
	background-image: url(graphics/icons/write.png);
	background-repeat: no-repeat;
	}
	
li#main_menu_edit {
	background: url(graphics/icons/edit.png) no-repeat top left;
	}
	li#main_menu_edit:hover {
	background-image: url(graphics/icons/edit.png);
	background-repeat: no-repeat;
	}
	
li#main_menu_help {
	background: url(graphics/icons/help.png) no-repeat top left;
	}
	li#main_menu_help:hover {
	background-image: url(graphics/icons/help.png);
	background-repeat: no-repeat;
	}
	
li#main_menu_options {
	background: url(graphics/icons/options.png) no-repeat top left;
	}
	li#main_menu_options:hover {
	background-image: url(graphics/icons/options.png);
	background-repeat: no-repeat;
	}
	
li#main_menu_plugins {
	background: url(graphics/icons/plugins.png) no-repeat top left;
	}
	li#main_menu_plugins:hover {
	background-image: url(graphics/icons/plugins.png);
	background-repeat: no-repeat;
	}
	
li#main_menu_info {
	background: url(graphics/icons/logout.png) no-repeat top left;
	}
	li#main_menu_info:hover {
	background-image: url(graphics/icons/logout.png);
	background-repeat: no-repeat;
	}

/*

	Options submenu
*/


li#options_menu_users {
	background: url(graphics/icons/users.png) no-repeat top left;
	}
	li#options_menu_users:hover {
	background-image: url(graphics/icons/users.png);
	background-repeat: no-repeat;
	}
	
li#options_menu_templates {
	background: url(graphics/icons/templates.png) no-repeat top left;
	}
	li#options_menu_templates:hover {
	background-image: url(graphics/icons/templates.png);
	background-repeat: no-repeat;
	}
		
li#options_menu_categories {
	background: url(graphics/icons/categories.png) no-repeat top left;
	}
	li#options_menu_categories:hover {
	background-image: url(graphics/icons/categories.png);
	background-repeat: no-repeat;
	}
	
li#options_menu_utils {
	background: url(graphics/icons/utils.png) no-repeat top left;
	}
	li#options_menu_utils:hover {
	background-image: url(graphics/icons/utils.png);
	background-repeat: no-repeat;
	}
		
li#options_menu_setup {
	background: url(graphics/icons/setup.png) no-repeat top left;
	}
	li#options_menu_setup:hover {
	background-image: url(graphics/icons/setup.png);
	background-repeat: no-repeat;
	}
		


#status {
	margin-left: auto;
	margin-right: 10px;
	text-align: right;
	width: 100%;
	background: #e5f363;
	border: 1px solid #e5f3c3;
	padding: 0px;
	margin-bottom: 10px;
}

.div_normal {
	float: left;
	min-width: 670px;
}

.div_extended {
	padding-right: 10px;
	float: right;
	min-width: 210px;
}

#footer {
	opacity: 0.2;
	border-top: 1px solid #dae4ea;
	border-bottom: 1px solid #dae4ea;
	margin-top: 20px;
	clear: both;
	}

/*
	buttons and form stuff
*/

span.delete a {
	color: fff;
	padding: 0 3px 0 3px;
	text-align: center;
	background: #d94848;
	color: #fff;
	margin-bottom: 2px;
	}

input.delete {
	height: 22px;
	background: #d94848 url(graphics/icons/delete.png) no-repeat;
	color: #fff;
	padding: 0 0 0 25px; 
	border-left: 3px solid #333;
	}

input, textarea {
	background: #f6f7f8;
	border: 1px solid #dae4ea;
	margin: 0 5px 1px 0;
	}

input:focus, textarea:focus {
	background: #fff;
	}
	
input.save {
	height: 22px;
	background: url(graphics/icons/save.png) no-repeat; 
	padding: 0 0 0 25px; 
	border-left: 3px solid #333;
	}
input.preview {
	height: 22px;
	background: url(graphics/icons/preview.png) no-repeat; 
	padding: 0 0 0 25px; 
	border-left: 3px solid #333;
	}
input.edit {
	height: 22px;
	background: url(graphics/icons/edit.png) no-repeat; 
	padding: 0 0 0 25px; 
	border-left: 3px solid #333;
	}
input.add {
	height: 22px;
	background: url(graphics/icons/add.png) no-repeat; 
	padding: 0 0 0 25px; 
	border-left: 3px solid #333;
	}

textarea {
	width: 640px;
	}

.inshort {
	width: 150px;
}
.inmedium {
	width: 250px;
}
.inlong {
	width: 350px;
}

.tasmall {
	height: 150px;
}
.tamedium {
	height: 350px;
}
.talarge {
	height: 550px;
}
	
fieldset {
	border: 1px solid #f3e3ac;
	-moz-border-radius: 5px;
	padding: 5px;
}

fieldset fieldset {
	margin: 3px 0 5px 10px;
	width: 82%;
	border: 1px solid #dae4ea;
	}
fieldset fieldset legend {
	font-size: 0.89em;
	}
fieldset legend {
	font-weight: bold;
	font-size: 130%;
	}
	
legend.link {
	border-bottom: 2px solid #f1f3d8;
	}



/*
	tables
*/

table {
	width: 100%;
	font-size: 0.87em;
	text-align: left;
	}
td {
	padding: 1px 25px 1px 1px;
}
th {
	font-size: 120%;
}



.options_categorylist {
	width: 60%;
	}

.templates_options {
	width: 210px;
	}
	
.users_options {
	width: 210px;
}
	
	
	
/* 
	Hidden layers
*/

#markdown_help {
	display: none;
	}
#stop_date_div {
	display: none;
	}
#start_date_div {
	display: none;
	}

</style>

<script type="text/javascript" src="inc/utility.js"></script>

</head>

<body>
<div id="body">
<div id="mainframe">
	<div id="header">
	<h1><?php echo $moduletitle; ?>	</h1>
		<div id="menu">
		<?php
#			$menu = run_filters('admin-menu-content',$menu);
			foreach ($menus as $menuname => $menucontent) {
				print $menucontent;
			}
			?>
		</div>
	</div>
	
	<div id="status">
		<span class=\"message\">
			<?php msg_status("$statusmessage"); ?></span>
	</div>
	
	<div id="content">
<?php

	echo $main_content;

?>

	</div>
	
	<div id="footer">
		<?=SCRIPT_TITLE;?> <?=SCRIPT_VERSION;?> &quot;cutting edge personal publishing&quot; - Licensed under the <strong>GPL</strong>
		<?php 
			if (!$_GET[debug]) { 
				$_GET[debug] = 1;
				} 
			if ($_GET[debug] == 1) {
				echo "<br /><fieldset><legend>".i18n("dashboard_DBI")."<legend><pre>";
				print_r($_GET);
				echo "\n\n-----------&lt;- get  | post   -&gt;---------------\n\n";
				print_r($_POST);
				echo "\n\n-----------&lt;- post | cookie -&gt;---------------\n\n;";
				print_r($_COOKIE);
				print_r($User->collectlogin());
				echo "</pre></fieldset>";
				}
				?>
	</div>

</div>
</div>
</body>
</html>