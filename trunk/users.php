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
			<p><select name="edituser[level]">';
		
	if($usertoedit[level] == 4){$main_content .= ' <option value="4" selected="selected">Admin</option>';}
		else {$main_content .= ' <option value="4">Admin</option>';}
	if($usertoedit[level] == 3){$main_content .= ' <option value="3" selected="selected">Editor</option>';}
		else {$main_content .= ' <option value="3">Editor</option>';}
	if($usertoedit[level] == 2){$main_content .= ' <option value="2" selected="selected">Journalist</option>';}
		else {$main_content .= ' <option value="2">Journalist</option>';}
	if($usertoedit[level] == 1){$main_content .= ' <option value="1" selected="selected">Commenter</option>';}
		else {$main_content .= ' <option value="1">Commenter</option>';}
		
		
		$main_content .='
			</select></p><p><input class="inshort" type="text" id="edit_user_nname" name="edituser[nickname]" value="'.$usertoedit[nickname].'" /><label for="edit_user_nname">'.i18n("generic_nickname").'</label></p>
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
	
	$leveltotext = array(
		4 => "Admin",
		3 => "Editor",
		2 => "Journalist",
		1 => "Commenter",
		);

	$main_content = '
	<div id="manage_users_wrapper">
	<div class="div_normal">
		<fieldset>
		<legend>'.i18n("users_add").'</legend>
		
	<form id="add_user_form" class="cpform" method="post">
		<input type="hidden" name="	panel" value="users" />
		<p><label for="add_user_name">'.i18n("login_Username").'</label><br /><input class="inshort" type="text" id="add_user_name" name="adduser[name]" /> 
		<select id="add_user_level" name="adduser[level]">
			<option value="4">Admin</option>
			<option value="3">Editor</option>
			<option value="2">Journalist</option>
			<option value="1">Commenter</option>
		</select> <label for="add_user_level">'.i18n("generic_level").'</label></p>
		<p><input class="inshort" type="text" id="add_user_nname" name="adduser[nickname]" /><label for="add_user_nname">'.i18n("generic_nickname").'</label></p>
		<p><input class="inshort" type="text" id="add_user_password" name="adduser[password]" /><label for="add_user_password">'.i18n("login_Password").'</label></p>
		<p><input class="inmedium" type="text" id="add_user_email" name="adduser[email]" /><label for="add_user_email">'.i18n("generic_email").'</label></p>
		<p><input class="inmedium" type="text" id="add_user_url" name="adduser[url]" /><label for="add_user_url">'.i18n("generic_url").'</label></p>
		<p><label for="add_user_profile">'.i18n("generic_profile").'</label><br /><textarea class="tamedium" id="add_user_profile" name="adduser[profile]"></textarea></p>
		</fieldset>
		<p><input type="submit" value="'.i18n("generic_add").'" /></p>
	</form>
	
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
		$level = $userdata[level];
		$level = $leveltotext[$level];
		$main_content .= "<tr><form method=\"get\"><input type=\"hidden\" name=\"panel\" value=\"users\" /><input type=\"hidden\" name=\"edit\" value=\"$username\" /><td>$username</td><td>$level</td><td>".date("j. F Y", $userdata[registered])."</td><td></td><td><input type=\"submit\" value=\"".i18n("generic_edit")."\" /><input type=\"submit\" name=\"action[delete]\" class=\"delete\" value=\"".i18n("generic_delete")."\" /></td></form></tr>";
		}
	$main_content .='
	</table>
	</fieldset>
	</div><div class="div_extended"><p>Extended options</p></div>';
	
	}

#
#	Delete
#
if ($_GET[action][delete]) {
	$statusmessage = $User->drop();
	
}

?>