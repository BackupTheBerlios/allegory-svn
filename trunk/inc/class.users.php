<?php

#
#	Users class
#

class KUsers {

	var $username;
	var $nickname;
	var $email;
	var $url;
	var $status;
	var $level;
	var $language;
	var $logintype;

#
#	We need
#
#	info()
#	verify()
#	add()
#	delete()
#	edit()

function add() {
	global $Settings;
	$now = time();
	$db = KUsers::connect();
	$currentusers = KUsers::getusers();

	# Remove unwanted stuff!
	$_POST[adduser][name] 		= sanitize_variables($_POST[adduser][name]);
	$_POST[adduser][password] 	= sanitize_variables($_POST[adduser][password]);	
	$_POST[adduser][password] 	= md5($_POST[adduser][password]);
	$_POST[adduser][password] 	= sha1($_POST[adduser][password].$Settings->unique);
	$savecats 					= implode(", ", $_POST[adduser][category]);
	$_POST[adduser][email] 		= sanitize_variables($_POST[adduser][email]);
	$_POST[adduser][url] 		= sanitize_variables($_POST[adduser][url]);
	$_POST[adduser][profile] 	= sanitize_variables($_POST[adduser][profile]);
	$adduserkey 				= urlTitle($_POST[adduser][name]);
	
	if (array_key_exists($adduserkey, $currentusers)) { 
		$statusmessage = "User &quot;$adduserkey&quot; already exists in the database!<br /><a href=\"javascript:history.go(-1);\">How about choosing another name?</a>";
		}
		
	# if the name is available
	else {
		$data = array(
		"registered"=> stripslashes($now),
		"lastlogin" => "",
		"nickname"	=> stripslashes($_POST[adduser][nickname]),
		"password" 	=> stripslashes($_POST[adduser][password]),
		"email" 	=> stripslashes($_POST[adduser][email]),
		"url" 		=> stripslashes($_POST[adduser][url]),
		"profile" 	=> stripslashes($_POST[adduser][profile]),
		"level"		=> stripslashes($_POST[adduser][level]),
		"cats"		=> stripslashes($savecats),				# should only be used to restrict journalists
		);
	
	$db->settings['users'][$adduserkey] = $data;
	$db->save();
	
	# Give the user a status message
	$statusmessage = "User &quot;$adduserkey&quot; successfully added";
	}	
	return $statusmessage;
}

function drop() {
	$userkey = $_GET[edit];
	$db = KUsers::connect();
	$db->delete("users", $userkey);
	return "User &quot;$userkey&quot; dropped";
}

function connect() {
	$settingclass = new SettingsStorage('settings');
	return $settingclass;
}

function getusers() {
	$settingclass = KUsers::connect();
	$users = $settingclass->settings['users'];
	return $users;
}

function getnicks($allusers="FALSE") {
	if ($allusers) {
		$allusers = KUsers::getusers();
		}
	
	foreach ($allusers as $username => $userdata) {
		$username = trim($username);
		$userdata[nickname] = trim($userdata[nickname]);
		$usernicks[$userdata[nickname]] = $username;
		}
		
	return $usernicks;
	}
	
function indatabase($allusers="FALSE", $user=false) {
	if (!$allusers) {
		$allusers = KUsers::getusers();
		}
	
	if ($user) { $checkuser = $user; }
	else { $checkuser = $_POST[comment][name]; }
	
	if (array_key_exists(urlTitle($checkuser), $allusers)) {
		$match = array(
			"match" => true,
			"type" => "name",
			"name" => $checkuser,
			"avatar" => $allusers[$checkuser][avatar],
			);
		}
	else {
		$usernicks = KUsers::getnicks($allusers);
		if (array_key_exists($checkuser, $usernicks)) {
			$match = array(
				"match" => true,
				"type" => "nick",
				"name" => $checkuser,
				"user" => $usernicks[$checkuser],
				"avatar" => $allusers[$usernicks[$checkuser]][avatar],
				);
			}
		}
	return $match;
	}
	
function collectlogin() {
	$checkpost = array();
	
	if ($_COOKIE[allegory_username] && $_COOKIE[allegory_md5password]) {
		$checkpost[username] = $_COOKIE[allegory_username];
		$checkpost[password] = $_COOKIE[allegory_md5password];
		$checkpost[logintype] = "cookie";
		}

	elseif ($_POST[username] && $_POST[password]) {
		$checkpost[username] = $_POST[username];
		$checkpost[password] = $_POST[password];
		$checkpost[logintype] = "standard";
		}
		
	elseif ($_POST[comment][password] && $_POST[comment][name]) {
		$checkpost[password] = $_POST[comment][password];
		$checkpost[username] = $_POST[comment][name];
		$checkpost[logintype] = "comment";
		}
			
	if ($_POST[language]) {
			$checkpost[language] = $_POST[language];
			}
			
	elseif ($_COOKIE[allegory_language]) {
			$checkpost[language] = $_COOKIE[allegory_language];
			}
	
	else { $checkpost[language] = "en_gb.php"; }
/*	$checkpost = array(
		"username" => $_POST[username],
		"password" => $_POST[password],
		"language" => $_POST[language],
		"cookiename" => $_COOKIE[kusername],
		"cookiepass" => $_COOKIE[kmd5password],
		"cookielang" => $_COOKIE[klanguage],
		);*/
		
		# FIXME: Needs to verify language / Set default!
		
	return $checkpost;
}

function verify($SentHeaders = false) {
	global $Settings;
	$userdata = KUsers::collectlogin();
	
	if (!$userdata) { return false; }
	$users = KUsers::getusers();
	$unique = $Settings->unique;
	$return = false;
	
	if ($userdata[logintype] == "standard") {
		$e_md5 = md5($userdata[password]);
		$e_given = sha1($e_md5.$unique);
	}
	elseif ($userdata[logintype] == "comment") {
		$e_md5 = md5($userdata[password]);
		$e_given = sha1($e_md5.$unique);
	}
	else {
		$e_given = sha1($userdata[password].$unique);
		}
	
	foreach ($users as $thisuser => $thisuserdata) {
		if (urlTitle($userdata[username]) == urlTitle($thisuser)) {
			if ($e_given == $thisuserdata[password]) {
				$this->username = $thisuser;
				$this->nickname = $thisuserdata[nickname];
				$this->url = $thisuserdata[url];
				$this->email = $thisuserdata[email];
				$this->status = "verified";
				$this->level = $thisuserdata[level];
				$this->language = $userdata[language];
				$this->type = $userdata[logintype];
				
				if ($userdata[logintype] == "standard") {
					if (!$SentHeaders) {
						setcookie("allegory_username", $thisuser, time()+14400, "/");
						setcookie("allegory_md5password", $e_md5, time()+14400, "/");	
						setcookie("allegory_language", $userdata[language], time()+100000, "/");
						}
					else {
						# Set the cookies via echoing a javascript here.
						# Will probably also need a JS refresh ( FIXME )
						echo "<script type=\"text/javascript\">
						var now = new Date();
						now.setTime(now.getTime() + 365 * 24 * 60 * 60 * 1000);
						Allegory_Setcookie(\"allegory_username\", \"$thisuser\", now, \"/\");
						Allegory_Setcookie(\"allegory_md5password\", \"$e_md5\", now, \"/\");
						</script>";				
						}
					}
				}
			}
		}
	}

function logout($SentHeaders = false) {
	if (!$SentHeaders) {
		setcookie("allegory_username", "", time() - 7200, "/");
		setcookie("allegory_md5password", "", time() - 7200, "/");
		}
	else {
	
		# FIXME: This really isn't working :-P
		echo '<script type="text/javascript">
		var now = new Date();
		now.setTime(now.getTime() + 365 * 24 * 60 * 60 * 1000);
		Allegory_Deletecookie("allegory_username");
		Allegory_Deletecookie("allegory_md5password");
		window.location = "NewPage.aspx";
		document.write("moooooooooooooooooop");
		</script>';			
		}
	}
	
function convertlevel($level) {
	switch ($level) {
		case 1:
			$return = i18n("level_commenter");
			break;
		case 2:
			$return = i18n("level_journalist");
			break;
		case 3:
			$return = i18n("level_editor");
			break;
		case 4:
			$return = i18n("level_admin");
			break;
		}
		return $return;
	}
	
}
?>