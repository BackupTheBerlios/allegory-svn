<?php
if ($User->level < 4) { 
	die(i18n("login_noaccess"));
	}

$moduletitle = i18n("options_moduletitle", "12twelve");

$menus["sub_options"] = '

<ul style="margin: 20px 0 15px 45px;">
	<li id="options_menu_users"><a href="?panel=users">'.i18n("menu_users").'</a></li>
	<li id="options_menu_templates"><a href="?panel=template">'.i18n("menu_templates").'</a></li>
	<li id="options_menu_categories"><a href="?panel=options&amp;screen=categories">'.i18n("menu_categories").'</a></li>
	<li id="options_menu_setup"><a href="?panel=options&amp;screen=setup">'.i18n("menu_setup", SCRIPT_TITLE).'</a></li>
</ul>
';

	$settingclass = new SettingsStorage('settings');
	
	$currentcats = $Settings->ca;
	$alltemplates = $Settings->te;

#
# Knife setup
#


if ($_GET[screen] == "setup") {

	if ($_POST[configsave]) {
		foreach ($_POST[config] as $where => $value) {
			$Settings->saveConfig($where, $value);
			}
		}
	
	else {
	
		/*
		 *	Setup option values;
		 */
		 
		$i18nfiles = FileFolderList("./lang/", $depth = 1, $current = '', $level=0);
		$available_languages = available_languages($i18nfiles);
		
		foreach ($available_languages as $null => $language) {
			$options_languages[$language[file]] = "$language[langinternational] ($language[langnational])";
			}
		
		$options_yesno = array(
			"yes" => i18n("generic_yes"),
			"no" => i18n("generic_no"),
			);
		
		$options_storage = array(
			"mysql" => "mySQL Database", 
			"flat" => "Flat file (var_dump) DB",
			);

		$options_comment_requirepreview = array(
			"yes" => "Require preview before posting",
			"no" => "Do not require preview",
			);
			
		$options_mysql_info = array(
				makeField("text", "config[storage][mysqluser]", "mysqluser", $Settings->co[storage][mysqluser], i18n("options_mysql_username", "MySQL"), "inshort"),
				makeField("text", "config[storage][mysqlpass]", "mysqlpass", $Settings->co[storage][mysqlpass], i18n("options_mysql_password", "MySQL,<small>,</small>"), "inshort"),
				makeField("text", "config[storage][mysqlhost]", "mysqlhost", $Settings->co[storage][mysqlhost], i18n("options_mysql_host", "MySQL,<small>,</small>"), "inshort"),
				makeField("text", "config[storage][mysqldatabase]", "mysqldb", $Settings->co[storage][mysqldatabase], i18n("options_mysql_database", "MySQL"), "inshort"),
			);
			
		$settingfields = array(
			"general" => array(
				"name" => i18n("options_general"),
				makeField("text", "config[general][uniquekey]", "uniquekey", $Settings->co[general][uniquekey], i18n("options_d_unique", "<small>,</small>"), "inlong", "top"), 
				makeField("text", "config[general][typekey]", "typekey", $Settings->co[general][typekey], "TypeKey", "inmedium", "top"),
				makeField("text", "config[general][dateoffset]", "dateoffset", $Settings->co[general][dateoffset], i18n("options_dateoffset"), "inshort"),
				formGroup($options_mysql_info, i18n("options_mysql_info")),
				radioGroup($options_yesno, "emailspam", "config[general][emailspam]", i18n("options_emailspam"), $Settings->co[general][emailspam]),
				radioGroup($options_languages, "defaultlanguage", "config[general][defaultlanguage]", i18n("options_default_lang"), $Settings->co[general][defaultlanguage]),
				),
			"articles" => array (
				"name" => i18n("dashboard_Articles"),
				makeField("text", "config[articles][dateformat]", "articledateformat", $Settings->co[articles][dateformat], "Articles date format", $group),
				),
			"comments" => array(
				"name" => i18n("dashboard_Comments"),
				radioGroup($options_yesno, "requireregister", "config[comments][requireregister]", i18n("options_requireregister"), $Settings->co[comments][requireregister]),
				radioGroup($options_yesno, "markdownpreview", "config[comments][markdownpreview]", i18n("options_markdownpreview"), $Settings->co[comments][markdownpreview], i18n("options_markdownpreviewd")),
				makeField("text", "config[comments][dateformat]", "commentdateformat", $Settings->co[comments][dateformat], "Comments date format"),
				makeField("text", "config[comments][avatar][size]", "gravatarsize", $Settings->co[comments][avatar][size], "Gravatar size (in pixels)"),
				makeField("text", "config[comments][avatar][defaulturl]", "gravatardefault", $Settings->co[comments][avatar][defaulturl], "Default gravatar (url)", "inlong"),
				radioGroup($options_yesno, "requireemail", "config[comments][requiremail]", "Require email?", $Settings->co[comments][requiremail]),
				),
			);
			
	$main_content .= '<form id="config" method="post" action="">';
	$main_content .= '<div id="storage_select" class="div_extended">';
	$main_content .= radioGroup($options_storage, "storage", "config[storage][backend]", "Database backend", $Settings->co[storage][backend]);
	$main_content .= '</div><div class="div_normal">';
	foreach ($settingfields as $class => $fields) {
		$main_content .= formGroup($fields, $fields[name]);
		}
	$main_content .= '<p><input type="submit" value="Save" name="configsave" /></p></div>';
	$main_content .= '</form>';
	
	
	}
}


#
#	Add / List categories
#

if ($_GET[screen] == "categories" && !$_POST[addcat]) {

	$moduletitle = i18n("dashboard_categories");
	$statusmessage = i18n("dashboard_categories");

	
	$main_content = '
	<div id="manage_cats_wrapper">
	<div class="div_normal options_categorylist">
	<fieldset>
		<legend>'.i18n("categories_current").'</legend>
	<table>
		<thead>
		<tr>
			<th>ID</th>
			<th>'.i18n("generic_name").'</th>
			<th>'.i18n("categories_defaulttpl").'</th>
			<th>'.i18n("generic_actions").'</th>
		</tr>
		</thead>';
	
	foreach ($currentcats as $catid => $catinfo) {
		$thistemplate = $alltemplates[$catinfo[template]];
		$main_content .= "
		<tr>
			<form method=\"get\">
				<input type=\"hidden\" name=\"panel\" value=\"options\" />
				<input type=\"hidden\" name=\"screen\" value=\"categories\" />
				<input type=\"hidden\" name=\"catid\" value=\"$catid\" />
				<td>$catid</td>
				<td>$catinfo[name]</td>
				<td>$thistemplate[name]</td>
				<td><input type=\"submit\" value=\"".i18n("generic_edit")."\" /><input type=\"submit\" name=\"action\" class=\"delete\" value=\"".i18n("generic_delete")."\" /></td>
			</form>
		</tr>";
		}
		
	$main_content .='
	</table>
	</fieldset>
	</div>
	<div class="div_extended">
		<form id="add_cat_form" class="cpform" method="post">
			<fieldset>
				<legend>'.i18n("categories_add").'</legend>
					<input type="hidden" name="	panel" value="options" />
					<p>
						<label for="add_cat_name">'.i18n("generic_name").'</label><br />
						<input class="inshort" type="text" id="add_cat_name" name="addcat[name]" /><br />
						
					</p>
					<p>';
	
	
	$main_content .= makeDropDown($alltemplates, "addcat[template]", "");

	$main_content .= ' <label>'.i18n("categories_defaulttpl").'</label>
					</p>
					<p>
						<input type="submit" value="'.i18n("categories_add").'" />
					</p>
			</fieldset>
		</form>	
	</div>
	</div>';
	
	}
	
if ($_POST[addcat]) {

#
#	Add a new category (Routine)
#
	$now = time();
	
	# Remove unwanted stuff!
	$_POST[addcat][name] = sanitize_variables($_POST[addcat][name]);
	$_POST[addcat][template] = sanitize_variables($_POST[addcat][template]);
		
		$data = array(
		"name"	=> stripslashes($_POST[addcat][name]),
		"template" 	=> stripslashes($_POST[addcat][template]),
		);
	
	$settingclass->settings['categories'][] = $data;
	$settingclass->save();
	
	# Give the user a status message
	$statusmessage = "Category &quot;$data[name]&quot; added";
	}

#
#	Delete a category (Routine)
#

if	($_GET[action] && $_GET[catid]) {

	

}

?>