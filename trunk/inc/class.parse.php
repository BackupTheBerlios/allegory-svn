<?php

/*
 *
 *	Parsing class
 *
 *
 */
 
class Parser {

	function Comment($template, $commentid, $comment, $articlescomments) {
		global $Settings, $ACDB;
		$Config = $Settings->co;
		$output = $template[comment];
		$output = str_replace("{number}", $i, $output);
		
		if ($comment[parentcid]) {
			$quotecomment = $articlescomments[$comment[parentcid]];
			$quoteout = $template[quote];
			$quoteout = str_replace("{name}", $quotecomment[name], $quoteout);
			$quoteout = str_replace("{quote}", Markdown(kses_filter($quotecomment[content])), $quoteout);
			$output = str_replace("{parentquote}", $quoteout, $output);
			}
		else { 
			$output = str_replace("{parentquote}", "", $output);
			}
			
		# date
		$dateregexp = '#\{date=(.*?)\}#i';
		preg_match_all($dateregexp,$output,$datematches,PREG_SET_ORDER);
		if (!empty($datematches)) {
			foreach ($datematches as $null => $match)
			$output = str_replace($match[0], date($match[1], $commentid + ($adjust*60)), $output);
			}
			
		$output = str_replace("{comment}", Markdown(kses_filter($comment[content])), $output);
		$output = str_replace("{ip}", $comment[ip], $output);
		$output = str_replace("{author}", $comment[name], $output);
		$output = str_replace("{url}", $comment[url], $output);
		# Output mail if given
		$comment[email] ? $output = preg_replace("/\[mail\=\"(.*)\"\]/ui", "<a href=\"mailto:$comment[email]\">\\1</a>", $output) : $output = preg_replace("/\[mail\=\"(.*)\"\]/ui", "", $output);
		$output = str_replace("{reply}", '<a href="'.$currenturl.'?replyto='.$commentid.'">reply</a>', $output);

		# The following will be set if the user is registered
		$checkuser = KUsers::indatabase(false, $comment[name]);
			
		if (eregi("{gravatar}", $output)) {
			if($comment[email]) {
				$gravatarid = trim(md5($comment[email]));
				$size = $Config[comments][avatar][size];
				$default = $Config[comments][avatar][defaulturl];
				$gravatarurl = "http://www.gravatar.com/avatar.php?gravatar_id=$gravatarid&amp;size=$size&amp;default=$default&amp;border=$border";
				$output = str_replace("{gravatar}", "<img src=\"$gravatarurl\" alt=\"$comment[name]_gravatar\" />", $output);
				}
			else {
				$output = str_replace("{gravatar}", "", $output);
				}
			}
		$output = make_clickable($output);
		return $output;
	}
	
	
	function CommentForm($template) {
		$output = '<form method="post" action="" id="'.SCRIPT_TITLE.'_addcommentform" name="comment">';
		$output .= $template[commentform];
		$output = preg_replace("/\[save\=\"(.*)\"\]/ui", "<input name=\"comment[save]\" type=\"submit\" value=\"\\1\" />", $output);
		$output = preg_replace("/\[preview\=\"(.*)\"\]/ui", "<input name=\"comment[preview]\" type=\"submit\" value=\"\\1\" />", $output);
		$output = str_replace("{allowedtags}", kses_filter("gettags"), $output);
		$output .= '</form>';
		return $output;
	
		}

	function Article() {
	
		# Needs cleanup! Stuff shouldnt have to be globalized! OMFG this is sad stuff!
		global $AADB, $ACDB, $UserDB, $Settings, $pathinfo_array, $allarticles, $template;
	
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

$article[views] = $AADB->articleupdate($date, "views", "update");
$output = str_replace("{views}", $article[views], $output);
		
echo $output;
		
		
#
#	Start showing comments
#	FIXME: If comments are disabled, don't show any of the following
		
echo '<div id="'.SCRIPT_TITLE.'_commentscontainer">';
$articlescomments = $ACDB->articlecomments($date);
		
if (!$articlescomments or $articlescomments == "") {
	echo i18n("visible_comment_none");
}
else {
	krsort($articlescomments);
	reset($articlescomments);
	$i = 1;
	foreach ($articlescomments as $commentid => $comment) {
		echo $this->Comment($template, $commentid, $comment, $articlescomments);
		$i++;
		}
}
echo '</div>';
		}
		
	function ArticleList() {
	
		}
}

?>