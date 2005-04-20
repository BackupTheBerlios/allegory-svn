<?php

#
#		File loaded when displaying a single article
#
include_once(KNIFE_PATH.'/plugins/kses.php');


		$UserDB->verify("HeadersSent");
		$k = $_GET[k];
		
		if (!$k) { 
			$k = $AADB->urldeconstructor($pathinfo_array, "title");
			}
			
		if (eregi("[a-z]", $k)) {
			# if $k is alpha , find the timestamp for this article
			foreach ($allarticles as $timestamp => $article) {
				if (urlTitle($article[title]) == $k) {
					$k = $timestamp;
					print_r($next);
					break 1;
					}
				}
			}
			
		
		# FIXME: The following seems a bit inappropriate - resource-hog: Have a variable and reconstructing it...
		unset($allarticles);
		$article = $AADB->getarticle($k);
		
		# FIXME: Is this needed?
		#if (!$article) { 
		#	$article = $allarticles[$k];
		#	}
		
		if (is_array($article) and $article != "") {

			$valid = true;
			}
		else { exit(i18n("visible_article_invalid", $Settings->co[general][adminmail])); }

	# rough copy from article(S)
	# skip draft articles
	$statusarray = explode("|", $article[status]);
	if ($statusarray[0] == "draft") {
		continue;
		}
	if ($statusarray[0] == "priv") {
		if (!$UserDB->username) {
			if ($static != true) {
				exit("This article (<strong>&quot;$article[title]&quot;</strong>) is marked private.  You have to login, etc to view it.");
				}
			
			}
		}

	# date can come from two places
	if ($timestamp) {
		$date = $timestamp;
		}
	else {
		$date = $k;
		}

#
# We got here for some reason. Display the bloody article, already...
#

$Valid = $Parser->Article($article, $date);

#
#	If receiving a comment
#
		
if ($_POST[comment] && $Valid === true) {
	if (!$_POST[comment][name] or $_POST[comment][name] == "") {
		$errors .= "<li><p>" . i18n("visible_comment_error_name") . "</p></li>";
		}
	if ($_POST[comment][email] and !preg_match("/^[\.A-z0-9_\-]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z]{1,4}$/", $_POST[comment][email])) {
		$errors .= "<li><p>" . i18n("visible_comment_error_email") . "</p></li>";
		}
	if ($Settings->co[comments][requiremail] == "yes" and !$_POST[comment][email]) {
		$errors .= "<li><p>" . i18n("visible_comment_error_requiremail") . "</p></li>";
		}
	if ($_POST[comment][url] and !preg_match('#^http\\:\\/\\/[a-z0-9\-]+\.([a-z0-9\-]+\.)?[a-z]+#i', $_POST[comment][url])) {
		$errors .= "<li><p>" . i18n("visible_comment_error_url") . "</p></li>";
		}
	if (!$_POST[comment][content] or $_POST[comment][content] == "") {
		$errors .= "<li><p>" . i18n("visible_comment_error_content") . "</p></li>";
		}


	$_POST[comment][name] = trim($_POST[comment][name]);		# clean the submitted name for db lkp
	$match = $UserDB->indatabase($allusers);

	if ($Settings->co[comments][requireregister] == "yes" and !$match[match]) {
		$errors .= "<li><p>" . i18n("visible_comment_error_onlyregistered", $_POST[comment][name]) . "</p></li>";
		}		

	if (!$errors && !$_POST[comment][preview]) {
			
		if ($match[match]) {	
			$userverifymessage = "<li><p>" . i18n("visible_comment_error_registered") . "</p>
			<form method=\"post\" action=\"\"><p><input type=\"text\" name=\"comment[password]\" /></p>
			<p><!--hidden-->
				<input type=\"hidden\" value=\"". $_POST[comment][parentcid]. "\" name=\"comment[parentcid]\" />
				<input type=\"hidden\" value=\"". $_POST[comment][name]. "\" name=\"comment[name]\" />
				<input type=\"hidden\" value=\"". $_POST[comment][email]. "\" name=\"comment[email]\" />
				<input type=\"hidden\" value=\"". $_POST[comment][url]. "\" name=\"comment[url]\" />
				<input type=\"hidden\" value=\"". htmlspecialchars($_POST[comment][content]). "\" name=\"comment[content]\" />
			<!--endhidden--></p>
			<p><input type=\"submit\" value=\"" . i18n("generic_add") . "\" /></p></form></li>";
			
			if ($_POST[comment][password]) {
				if ($match[type] = "nick") {
					$_POST[comment][name] = $match[user];
					}
				$null = $UserDB->verify();
				if ($UserDB->username) {
					$_POST[comment][name] = $match[name];
					# No error, we're good to go - but first - make sure the stuff we're saving is okay...
					$_POST[comment][content] = html2specialchars($_POST[comment][content]);
					}
				else {
					$errors .= $userverifymessage;
					}
				}
			
			else {
				$errors .= $userverifymessage;
				}
			}
		}
		
		# Save the comment if no errors occurred and we didnt request a preview
	if (!$errors and !$_POST[comment][preview]) {
		$ACDB->add($date);
		#FIXME: Redirect javascript doesn't work on all servers	
		echo "<script type=\"text/javascript\">self.location.href='http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}';</script>";
		}
		
	# Show the errors if any
	if ($errors) {
		echo "<div id=\"Commentposterrors\"><h1>". i18n("generic_error") ."</h1><p>". i18n("visible_comment_error_info"). "</p><ol>$errors</ol></div>";
		}
			
	if ($_POST[comment][preview] && !$errors) {
		#
		# FIXME: ADD COMMENT PREVIEW ROUTINE HERE
		$output = '<div id="Commentpostpreview"><h1>'.i18n("comment_preview").'</h1>';
		$output .= Markdown($_POST[comment][content]);
		$output .= '</div>';
		echo $Parser->Comment($template, time(), $_POST[comment], $currenturl);
		}
	}
	
	
#	Show the comment form
#	FIXME: If comments are disabled, don't show this
		
	
echo $Parser->CommentForm($template);

?>