<?php
if ($User->level < 4) { 
	die(i18n("login_noaccess"));
	}
	
include("options.php");
$moduletitle = "Manage users";

if($_POST[adduser]) {

#
#	Add a new user (Routine)
	$statusmessage = $User->add();
}


if($_POST[edituser]) {

#
#	Edit user (Routine)
#

	$now = time();
	$dataclass = new SettingsStorage('settings');
	$currentusers = $dataclass->settings['users'];
	
	

	# Remove unwanted stuff!
	$_POST[edituser][name] = sanitize_variables($_POST[edituser][name]);
	$_POST[edituser][password] = sanitize_variables($_POST[edituser][password]);
		
	$_POST[edituser][email] = sanitize_variables($_POST[edituser][email]);
	$_POST[edituser][url] = sanitize_variables($_POST[edituser][url]);
	$_POST[edituser][profile] = sanitize_variables($_POST[edituser][profile]);
	
	$adduserkey = urlTitle($_POST[edituser][name]);
	
	if (!array_key_exists($adduserkey, $currentusers)) { 
		$statusmessage = "User &quot;$adduserkey&quot; does not exist.<br /><a href=\"javascript:history.go(-1);\">How about choosing another name?</a>";
		}
		
	# if the name is available
	else {
	
		$olduser = $currentusers[$adduserkey];
		# has the password changed?
		
		if ($_POST[edituser][password] != "") {
			$_POST[edituser][password] = md5($_POST[edituser][password]);
			$_POST[edituser][password] = sha1($_POST[edituser][password].UNIQUE);
			$passchange = "y";
			}
		# guess not
		else {
			$_POST[edituser][password] = $olduser[password];
			}
				
		$data = array(
		"registered"=> stripslashes($olduser[registered]),
		"nickname"	=> stripslashes($_POST[edituser][nickname]),
		"password" 	=> stripslashes($_POST[edituser][password]),
		"email" 	=> stripslashes($_POST[edituser][email]),
		"url" 		=> stripslashes($_POST[edituser][url]),
		"profile" 	=> stripslashes($_POST[edituser][profile]),
		"level"		=> stripslashes($_POST[edituser][level]),
		);
	
	$dataclass->settings['users'][$adduserkey] = $data;
	$dataclass->save();
	
	# Give the user a status message
	$statusmessage = "User &quot;$adduserkey&quot; edited";
	if ($passchange == "y") {
		$statusmessage = "User &quot;$adduserkey&quot; edited - password changed";
		}
	}
}

if($_GET[edit] && !$_POST[edituser] && !$_GET[action]) {

#
#	Edit a user
#

	$now = time();
	$dataclass = new SettingsStorage('settings');
	$currentusers = $User->getusers();
	
	$usertoedit = urlTitle($_GET[edit]);
	if (array_key_exists($usertoedit, $currentusers)) {
		$userkey = $usertoedit;
		$usertoedit = $currentusers[$userkey];

		$main_content = '
	<div id="manage_users_wrapper">
		<div class="div_normal">
			<fieldset>
				<legend>'.i18n("generic_edit").' <u>'.$userkey.'</u> ('.$usertoedit[nickname].')</legend>
				
			<form id="edit_user_form" class="cpform" method="post">
			<input type="hidden" name="panel" value="users" />
			<input type="hidden" name="edituser[name]" value="'.$userkey.'" />
			<p>';
			
		$main_content .= createSelectBox(array("4" => i18n("level_admin"), "3" => i18n("level_editor"), "2" => i18n("level_journalist"), "1" => i18n("level_commenter")), "userlevelselect", "edituser[level]", $usertoedit[level]);
		
		$main_content .='
			</p><p><input class="inshort" type="text" id="edit_user_nname" name="edituser[nickname]" value="'.$usertoedit[nickname].'" /><label for="edit_user_nname">'.i18n("generic_nickname").'</label></p>
			<p><input class="inshort" type="text" id="edit_user_password" name="edituser[password]" /><label for="edit_user_password">'.i18n("login_Password").'</label></p>
			<p><input class="inmedium" type="text" id="edit_user_email" name="edituser[email]" value="'.$usertoedit[email].'" /><label for="edit_user_email">'.i18n("generic_email").'</label></p>
			<p><input class="inmedium" type="text" id="edit_user_url" name="edituser[url]" value="'.$usertoedit[url].'" /><label for="edit_user_url">'.i18n("generic_url").'</label></p>
			<p><label for="edit_user_profile">'.i18n("generic_profile").'</label><br /><textarea class="tamedium" id="edit_user_profile" name="edituser[profile]">'.$usertoedit[profile].'</textarea></p>
			</fieldset>
			<p><input type="submit" value="'.i18n("generic_save").'" /></p>
			</form></div><div class="div_extended"><p>Extended options</p></div></div>';
			}
	
	else {
	
		$statusmessage = "User &quot;$usertoedit&quot; does not exist in the database";
		}
}

#
#	Display add user form and current users
#
if (!$_POST[adduser] && !$_GET[edit]) {
	

	foreach ($Settings->ca as $catid => $catinfo) {
	
		# get a config for default cat select
		if ($catid == "0") { $checked = "checked=\"checked\" "; } else { $checked = ""; }
		$catformfields .= "<input type=\"checkbox\" name=\"adduser[category][]\" id=\"catbox$catid\" value=\"$catid\" $checked/>
							<label for=\"catbox$catid\">$catinfo[name]</label><br />";
	}

	$main_content = '
	<div id="manage_users_wrapper">
	<form id="add_user_form" class="cpform" method="post">
	<div class="div_normal">
		<fieldset>
		<legend>'.i18n("users_add").'</legend>
		<input type="hidden" name="	panel" value="users" />
		<p><label for="add_user_name">'.i18n("login_Username").'</label><br /><input class="inshort" type="text" id="add_user_name" name="adduser[name]" />';

		$main_content .= createSelectBox(array("4" => i18n("level_admin"), "3" => i18n("level_editor"), "2" => i18n("level_journalist"), "1" => i18n("level_commenter")), "add_user_level", "adduser[level]", $Settings->co[general][defaultuserlevel]);
$main_content .= ' <label for="add_user_level">'.i18n("generic_level").'</label></p>
		<p><input class="inshort" type="text" id="add_user_nname" name="adduser[nickname]" /><label for="add_user_nname">'.i18n("generic_nickname").'</label></p>
		<p><input class="inshort" type="text" id="add_user_password" name="adduser[password]" /><label for="add_user_password">'.i18n("login_Password").'</label></p>
		<p><input class="inmedium" type="text" id="add_user_email" name="adduser[email]" /><label for="add_user_email">'.i18n("generic_email").'</label></p>
		<p><input class="inmedium" type="text" id="add_user_url" name="adduser[url]" /><label for="add_user_url">'.i18n("generic_url").'</label></p>
		<p><label for="add_user_profile">'.i18n("generic_profile").'</label><br /><textarea class="tamedium" id="add_user_profile" name="adduser[profile]"></textarea></p>
		</fieldset>
		<p><input type="submit" value="'.i18n("generic_add").'" /></p>
	</div>
	<div class="div_extended users_options">
		<fieldset>
			<legend>'.i18n("users_restrict").'</legend>
			<p>'.i18n("users_restrictdesc").'</p>
			'.$catformfields.'
		</fieldset>
	</div>
	</form>
	<div class="div_normal">
	
	<fieldset>
		<legend>'.i18n("users_existing").'</legend>
	<table>
		<thead>
			<tr>
				<th>'.i18n("login_Username").'</th>
				<th>'.i18n("generic_level").'</th>
				<th>'.i18n("generic_regdate").'</th>
				<th>'.i18n("dashboard_Articles").'</th>
				<th>'.i18n("generic_actions").'</th>
			</tr>
		</thead>';
	
	foreach ($User->getusers() as $username => $userdata) {
		$level = $User->convertlevel($userdata[level]);
		$main_content .= "<tr><form method=\"get\"><input type=\"hidden\" name=\"panel\" value=\"users\" /><input type=\"hidden\" name=\"edit\" value=\"$username\" /><td>$username</td><td>$level</td><td>".date("j. F Y", $userdata[registered])."</td><td></td><td><input type=\"submit\" value=\"".i18n("generic_edit")."\" /><input type=\"submit\" name=\"action[delete]\" class=\"delete\" value=\"".i18n("generic_delete")."\" /></td></form></tr>";
		}
	$main_content .='
	</table>
	</fieldset></div>';
	
	}

#
#	Delete
#
if ($_GET[action][delete]) {
	$statusmessage = $User->drop();
	
}

?>