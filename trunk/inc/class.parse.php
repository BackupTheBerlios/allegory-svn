<?php

/*
 *
 *	Parsing class
 *
 *
 */
 
class Parser {

	function Comment($template, $commentid, $comment) {
		global $config_avatardimensions, $config_avatardefaulturl;
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
			
		$output = str_replace("{comment}", Markdown(kses_filter($comment[content])), $output);
		$output = str_replace("{ip}", $comment[ip], $output);
		$output = str_replace("{author}", $comment[name], $output);
		$output = str_replace("{date}", date("d/m/y H:i", $commentid), $output);
		$output = str_replace("{url}", $comment[url], $output);
		$output = str_replace("{email}", $comment[email], $output);
		$output = str_replace("{reply}", '<a href="'.$_SERVER[PHP_SELF].'?replyto='.$commentid.'">reply</a>', $output);
		$checkuser = KUsers::indatabase(false, $comment[name]);
		
		if (eregi("{avatar}", $output)) {
			if ($checkuser) {
				$output = str_replace("{avatar}", '<img width="'.$config_avatardimensions[comments][width].'" height="'.$config_avatardimensions[comments][height].'" src="'.$checkuser[avatar].'" alt="'.$checkuser[name].'_avatar" />', $output);
				}
			else {
				$output = str_replace("{avatar}", "", $output);
				}
			}
			
		if (eregi("{gravatar}", $output)) {
			if($comment[email]) {
				$gravatarid = trim(md5($comment[email]));
				$size = $config_avatardimensions[comments][width];
				$default = $config_avatardefaulturl;
				$gravatarurl = "http://www.gravatar.com/avatar.php?gravatar_id=$gravatarid&amp;size=$size&amp;default=$default&amp;border=$border";
				$output = str_replace("{gravatar}", "<img src=\"$gravatarurl\" alt=\"$comment[name]_gravatar\" />", $output);
				}
			else {
				$output = str_replace("{gravatar}", "", $output);
				}
			}

		return $output;
	}


}

?>