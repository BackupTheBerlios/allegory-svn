<?php

#
#		File loaded when displaying a single article
#
include(KNIFE_PATH.'/plugins/kses.php');
		$k = $_GET[k];
#		if (!$k) { $k = $pathinfo_array[1]; }
		if (!$k) { $k = $KAclass->urldeconstructor($pathinfo_array, "title"); }
		if (eregi("[a-z]", $k)) {
			# if $k is alpha , find the timestamp for this article
			foreach ($allarticles as $timestamp => $article) {
				if (urlTitle($article[title]) == $k) {
					$k = $timestamp;
					break 1;
					}
				}
			}
		unset($allarticles);
		$article = $KAclass->getarticle($k);
		if (!$article) { $article = $allarticles[$k]; }
		if ($article != "") { $valid = true; }
		
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
		#
		
		echo '<div id="'.SCRIPT_TITLE.'_commentscontainer">';
		$articlescomments = $commentsclass->articlecomments($date);
		
	if (!$articlescomments or $articlescomments == "") {
		echo "no comments";
	}
	else {
		krsort($articlescomments);
		reset($articlescomments);
		$i = 1;
		foreach ($articlescomments as $commentid => $comment) {
			$output = $template[comment];
			$output = str_replace("{number}", $i, $output);
			
			if ($comment[parentcid]) {
				$quotecomment = $articlescomments[$comment[parentcid]];
				$quoteout = $template[quote];
				$quoteout = str_replace("{name}", $quotecomment[name], $quoteout);
				$quoteout = str_replace("{quote}", Markdown(kses_filter($quotecomment[content])), $quoteout);
				
				$output = str_replace("{parentquote}", $quoteout, $output);
				}
			else { $output = str_replace("{parentquote}", "", $output); }
			
			$output = str_replace("{comment}", Markdown(kses_filter($comment[content])), $output);
			$output = str_replace("{ip}", $comment[ip], $output);
			$output = str_replace("{author}", $comment[name], $output);
			$output = str_replace("{date}", date("d/m/y H:i", $commentid), $output);
			$output = str_replace("{url}", $comment[url], $output);
			$output = str_replace("{email}", $comment[mail], $output);
			$output = str_replace("{reply}", '<a href="'.$_SERVER[PHP_SELF].'?replyto='.$commentid.'">reply</a>', $output);
			echo $output;
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
		if ($_POST[comment][url] and !preg_match('#^http\\:\\/\\/[a-z0-9\-]+\.([a-z0-9\-]+\.)?[a-z]+#i', $_POST[comment][url])) {
			$errors .= "<li><p>" . i18n("visible_comment_error_url") . "</p></li>";
			}
		if (!$_POST[comment][content] or $_POST[comment][content] == "") {
			$errors .= "<li><p>" . i18n("visible_comment_error_content") . "</p></li>";
			}

	if (!$errors) {
	$_POST[comment][name] = trim($_POST[comment][name]);		# clean the submitted name for db lkp
	$match = $Userclass->indatabase($allusers);
		
		if ($match[match]) {
				$userverifymessage = "<li><p>" . i18n("visible_comment_error_registered") . "</p>
				<form method=\"post\" action=\"\"><p><input type=\"text\" name=\"comment[password]\" /></p>
				<p><!--hidden-->
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
		
		# Save the comment if no errors occurred.
		if (!$errors) {
			$commentsclass->add($date);
			#FIXME: Redirect javascript doesn't work on all servers
			echo "<script type=\"text/javascript\">self.location.href='http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}';</script>";
			}
		
		# Or show the errors
		else {
			echo "<div id=\"Commentposterrors\"><h1>". i18n("generic_error") ."</h1><p>". i18n("visible_comment_error_info"). "</p><ol>$errors</ol></div>";
			}
		}

		#	Show the comment form
		#		
		$output = '<form method="post" action="" id="'.SCRIPT_TITLE.'_addcommentform">';
		$output .= $template[commentform];
		$output = str_replace("{allowedtags}", kses_filter("gettags"), $output);
		$output .= '</form>';
		echo $output;
?>