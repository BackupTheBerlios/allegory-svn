<?php
	# auth.php
	
	if (!defined( "KNIFE_PATH" )) {
    	define( "KNIFE_PATH", dirname(__FILE__)."/");	# Absolute path to current script
    	}

	include_once(KNIFE_PATH.'/inc/class.users.php');				# load userclass - can't live without
	include_once(KNIFE_PATH.'/inc/class.settings.php');
	include_once(KNIFE_PATH.'/inc/functions.php');
	
	if (!$Settings) {
		$Settings = new KSettings;
		$Settings->getCats();			#
		$Settings->getTemplates();		#	Initialize settings
		$Settings->getConfig();			#
		}
	
	if (!$UserDB) {
		$UserDB = new KUsers;
		}

	echo '<script type="text/javascript">

function Allegory_Setcookie(name, value, expires, path, domain, secure) {
  var curCookie = name + "=" + escape(value) +
      ((expires) ? "; expires=" + expires.toGMTString() : "") +
      ((path) ? "; path=" + path : "") +
      ((domain) ? "; domain=" + domain : "") +
      ((secure) ? "; secure" : "");
  document.cookie = curCookie;
}

function Allegory_Getcookie(name) {
  var dc = document.cookie;
  var prefix = name + "=";
  var begin = dc.indexOf("; " + prefix);
  if (begin == -1) {
    begin = dc.indexOf(prefix);
    if (begin != 0) return null;
  } else
    begin += 2;
  var end = document.cookie.indexOf(";", begin);
  if (end == -1)
    end = dc.length;
  return unescape(dc.substring(begin + prefix.length, end));
}

function Allegory_Deletecookie(name, path, domain) {
  if (getCookie(name)) {
    document.cookie = name + "=" +
    ((path) ? "; path=" + path : "") +
    ((domain) ? "; domain=" + domain : "") +
    "; expires=Thu, 01-Jan-70 00:00:01 GMT";
  }
}

</script>';

	# FIXME: Not working
	if ($_GET[logout] == "y") { 
		$UserDB->logout("SentHeaders"); 
		}
	$UserDB->verify("headers");
	
	if (!$UserDB->username) {
		require(KNIFE_PATH.'/lang/'.$Settings->co[general][defaultlanguage]);
		echo i18n("visible_logon_noauth");
		
		$LoginForm = '
		<h1>'.i18n("login_Login").'</h1><form id="allegory_loginform" method="post" action=""><input type="hidden" name="panel" value="dashboard" />
		<p>
			<input class="inshort" type="text" name="username" id="login_username" /> 
			<label for="login_username">'.i18n("login_Username").'</label>
		</p>
		<p>
			<input class="inshort" type="password" name="password" id="login_password" /> 
			<label for="login_password">'.i18n("login_Password").'</label>
		</p>
		<p>
			<input type="submit" name="sendlogin" value="'.i18n("login_Login").'" />
		</p>'
			;
		echo $LoginForm;
		
		
		
		}
	else {
		if ($UserDB->language) {
			include_once(KNIFE_PATH.'/lang/'.$UserDB->language);
			}
		else {
			include_once(KNIFE_PATH.'/lang/'.$Settings->co[general][defaultlanguage]);
			}	
		# Great, some kind of login was successful - Tell this to the user	
		echo i18n("visible_logon_authed", $UserDB->nickname) . '<a href="?logout=y">logout</a>';	
	}
	
?>	