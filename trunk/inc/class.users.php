<?php

#
#	Users class
#

class KUsers {

	var $username;
	var $nickname;
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
	
function indatabase($allusers="FALSE") {
	if (!$allusers) {
		$allusers = KUsers::getusers();
		}
		
	if (array_key_exists(urlTitle($_POST[comment][name]), $allusers)) {
		$match = array(
			"match" => true,
			"type" => "name",
			"name" => $_POST[comment][name],
			);
		}
	else {
		$usernicks = KUsers::getnicks($allusers);
		if (array_key_exists($_POST[comment][name], $usernicks)) {
			$match = array(
				"match" => true,
				"type" => "nick",
				"name" => $_POST[comment][name],
				"user" => $usernicks[$_POST[comment][name]],
				);
			}
		}
	return $match;
	}
	
function collectlogin() {
	$checkpost = array();
	
	if ($_COOKIE[kusername] && $_COOKIE[kmd5password]) {
		$checkpost[username] = $_COOKIE[kusername];
		$checkpost[password] = $_COOKIE[kmd5password];
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
			
	elseif ($_COOKIE[klanguage]) {
			$checkpost[language] = $_COOKIE[klanguage];
			}
/*	$checkpost = array(
		"username" => $_POST[username],
		"password" => $_POST[password],
		"language" => $_POST[language],
		"cookiename" => $_COOKIE[kusername],
		"cookiepass" => $_COOKIE[kmd5password],
		"cookielang" => $_COOKIE[klanguage],
		);*/
		
	return $checkpost;
}

function verify() {
	$userdata = KUsers::collectlogin();
	
	if (!$userdata) { return false; }
	$users = KUsers::getusers();
	$unique = UNIQUE;
	$return = false;
	# $unique_password = $userdata[ . $unique;
	
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
				$this->status = "verified";
				$this->level = $thisuserdata[level];
				$this->language = $userdata[language];
				$this->type = $userdata[logintype];
				
				if ($userdata[logintype] == "standard") {
					setcookie("kusername", $thisuser, time()+3600);
					setcookie("kmd5password", $e_md5, time()+3600);	
					setcookie("klanguage", $userdata[language]);
					}
				}
			}
		}
	}

function logout() {
	setcookie("kusername", "", time() - 3600);
	setcookie("kmd5password", "", time() - 3600);
	}
}
?>