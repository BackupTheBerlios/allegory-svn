<?php

#
#	Comment storage abstraction class
#



class KComments {

	#
	#	Construct a list of all available comments
	function allcomments($limit=false) {
		if (defined("KNIFESQL")) {
			if ($limit) {
				$limit = 'LIMIT 0,'.$limit;
				}
			$class = KComments::connect();
			$mysql_query = 'SELECT * FROM `comments` ';#.$limit;
			$result = mysql_query($mysql_query) or die('Query failed: ' . mysql_error());
			while ($comment = mysql_fetch_assoc($result)) {
				$allcomments["$comment[articleid]"][$comment[commentid]] = $comment;
				}
			return $allcomments;
			}
		else {
			$commentsclass = new CommentStorage('comments');
			$allcomments = $commentsclass->settings;
			krsort($allcomments);
			reset($allcomments);
			return $allcomments;
			}
		}
	
	#
	#	Get a specific articles comments
	function articlecomments($timestamp) {
		if (defined("KNIFESQL")) {
			$class = KComments::connect();
			$mysql_query = "SELECT * FROM comments WHERE articleid = $timestamp";
			$result = mysql_query($mysql_query) or die('Query failed: ' . mysql_error());
			while ($comment = mysql_fetch_assoc($result)) {
				$articlecomments[$comment[commentid]] = $comment;
				}
			return $articlecomments;
			}
		else {
			$allcomments = KComments::allcomments();
			$articlecomments = $allcomments[$timestamp];
			return $articlecomments;
			}
		}
		
	function articlecommentsdelete($article) {
		$commentsclass = new CommentStorage('comments');
		if (!is_array($article)) {
			$commentsclass->deleteall($article);
			return true;
			}
		else {
			foreach ($article as $null => $thisid) {
				$commentsclass->deleteall($thisid);
				}
			return true;
			}
		}
	
	function getcomment($article, $comment) {
		if (defined("KNIFESQL")) {
			$class = KComments::connect();
			$mysql_query = "SELECT * FROM comments WHERE commentid = $comment";
			$result = mysql_query($mysql_query) or die('getcomment() Query failed: Comment not found: ' . mysql_error());
			$comment = mysql_fetch_assoc($result);
			return $comment;
			}
		else {
			$comments = KComments::articlecomments($article);
			$comment = $comments[$comment];
			return $comment;
			}
		}
			
	function latestcomments($number) {
		$allcomments = KComments::allcomments();
		$amount = 0;
		foreach ($allcomments as $newsid => $comments) {
			krsort($comments);
			reset($comments);
			foreach ($comments as $commentid => $commentdata) {
				$latestcomments[$commentid] = $commentdata;
				$latestcomments[$commentid][parent] = $newsid;
				$amount++;
				if ($amount >= $number) { 
					break 2;
					}
				}
			}
		krsort($latestcomments);
		reset($latestcomments);
		return $latestcomments;
		}	
	
	function add($articleid) {
		$newcommentid = time();
		$ip = $_SERVER["REMOTE_ADDR"];
		if (!validate_ip($ip)) { $ip = "127.0.0.2"; }
		$data = array(
			'parentcid' => stripslashes($_GET[replyto]),
			'name' => stripslashes($_POST[comment][name]),
			'email' => stripslashes($_POST[comment][email]),
			'url' => stripslashes($_POST[comment][url]),
			'ip' => $ip,
			'browser' => $_SERVER["HTTP_USER_AGENT"],
			'content' => stripslashes($_POST[comment][content]),
			);
		if (defined("KNIFESQL")) {
			$class = KComments::connect();			
			$write_sql = "INSERT INTO comments VALUES ('$articleid', '$newcommentid', '$data[parentcid]', '$data[name]', '$data[email]', '$data[url]', '$data[ip]', '$data[browser]', '$data[content]')";
			$result = mysql_query($write_sql) or die('Query failed: ' . mysql_error());
			return true;
		}
		else {
			$class = KComments::connect();
			$class->settings[$articleid][$newcommentid] = $data;
			$class->save();
			return true;
		}
	}
		
	function connect() {
		if (defined("KNIFESQL")) {
			$mysql_id = mysql_connect(KNIFE_SQL_SERVER, KNIFE_SQL_USER, KNIFE_SQL_PASSWORD);
			mysql_select_db(KNIFE_SQL_DATABASE, $mysql_id);
			return $mysql_id;	
			}
		
		else {
			$dataclass = new CommentStorage('comments');
			return $dataclass;
			}			
		}
}

?>