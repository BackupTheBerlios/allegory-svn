<?php

$moduletitle = i18n("options_moduletitle");

$menus["sub_options"] = '

<ul style="margin: 20px 0 15px 45px;">
	<li id="options_menu_users"><a href="?panel=users">'.i18n("menu_users").'</a></li>
	<li id="options_menu_templates"><a href="?panel=template">'.i18n("menu_templates").'</a></li>
	<li id="options_menu_categories"><a href="?panel=options&amp;screen=categories">'.i18n("menu_categories").'</a></li>
	<li id="options_menu_setup"><a href="?panel=options&amp;screen=setup"><span style="color: #f32988;">k</span>nife'.i18n("menu_setup").'</a></li>
</ul>
';

	$settingclass = new SettingsStorage('settings');
	$currentcats = $settingclass->settings['categories'];
	$alltemplates = $settingclass->settings['templates'];

#
# Knife setup
#

function makeField($type, $name, $id, $value, $label) {
	return "<input type=\"$type\" name=\"$name\" id=\"$id\" value=\"$value\" /> <label for=\"$id\">$label</label>";
}

if ($_GET[screen] == "setup") {

	$main_content = makeField("text", "something", "theid", "something", "Label");
	$main_content .= "
	
	
	";

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