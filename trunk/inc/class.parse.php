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


}

?>