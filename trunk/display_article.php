<?php

#
#		File loaded when displaying a single article
#
include_once(KNIFE_PATH.'/plugins/kses.php');
include_once(KNIFE_PATH.'/inc/class.parse.php');


# FIXME: NEEDS LOTS OF WORK
$currenturl = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . "/" . end(explode("/", $_SERVER['SCRIPT_NAME'])) . $_SERVER['PATH_INFO'];
global $currenturl;
$currentdir = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . "/";
$Parser 	= new Parser;


$k = $_GET[k];
#		if (!$k) { $k = $pathinfo_array[1]; }
if (!$k) { 
	$k = $KAclass->urldeconstructor($pathinfo_array, "title");
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

unset($allarticles);
$article = $KAclass->getarticle($k);
if (!$article) { 
	$article = $allarticles[$k];
	}
if ($article != "") {
	$valid = true;
	}
		
# date can come from two places
if ($timestamp) {
	$date = $timestamp;
	}
else {
	$date = $k;
	}
			
# select the current template
$output = $template[view];
# parse the listing template
		
if (stristr($article[content], "<!--more-->")) {
	$article[content] = explode("<!--more-->", $article[content]);
			
	$article[content][0] = Markdown($article[content][0]);
	$article[content][1] = Markdown($article[content][1]);
	
	$output = str_replace("{content}", $article[content][0], $output);
	$output = str_replace("{extended}", $article[content][1], $output);
	}		
$output = str_replace("{title}", $article[title], $output);
		
$article[content] = Markdown($article[content]);
		
$output = str_replace("{content}", $article[content], $output);
$output = str_replace("{extended}", "", $output);
$output = str_replace("{author}", $article[author], $output);
$output = str_replace("{category}", $article[category], $output);
$output = str_replace("{date}", date("dmy H:i", $date), $output);

$article[views] = $KAclass->articleupdate($date, "views", "update");
$output = str_replace("{views}", $article[views], $output);
		
echo $output;
		
		
#
#	Start showing comments
#	FIXME: If comments are disabled, don't show any of the following
		
echo '<div id="'.SCRIPT_TITLE.'_commentscontainer">';
$articlescomments = $commentsclass->articlecomments($date);
		
if (!$articlescomments or $articlescomments == "") {
	echo "No comments";
}
else {
	krsort($articlescomments);
	reset($articlescomments);
	$i = 1;
	foreach ($articlescomments as $commentid => $comment) {
		echo $Parser->Comment($template, $commentid, $comment, $articlescomments);
		$i++;
		}
}
echo '</div>';
		
#
#	If receiving a comment
#
		
if ($_POST[comment] && $valid) {
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
	$match = $Userclass->indatabase($allusers);

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
				<input type=\"hidden\" value=\"". $_POST[comment][content]. "\" name=\"comment[content]\" />
			<!--endhidden--></p>
			<p><input type=\"submit\" value=\"" . i18n("generic_add") . "\" /></p></form></li>";
			
			if ($_POST[comment][password]) {
				if ($match[type] = "nick") {
					$_POST[comment][name] = $match[user];
					}
				$null = $Userclass->verify();
				if ($Userclass->username) {
					$_POST[comment][name] = $match[name];
					# No error, we're good to go
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
		$commentsclass->add($date);
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
		
if ($Settings->co[comments][markdownpreview] == "yes") {
	echo '<div id="Commentpostpreview"><h1>'.i18n("visible_comment_preview").'</h1>';
	markdown_javascript($currentdir);
	markdown_add_preview_div();
	echo '</div>';
	}
	
echo $Parser->CommentForm($template);

?>