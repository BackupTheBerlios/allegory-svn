<?php

#
#	Comment storage abstraction class
#
#
#	EXtensive restructuring going on



class KComments {

	var $last_query;
	var $queries;
	var $result;
	
	# ? ##################################################################
	#	List all comments
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
	
	# ? ##################################################################
	#	Get a specific articles comments
	
	function alquery($query) {
		$this->flush();
		$this->last_query = $query;
		$this->result = mysql_query($query, $this->dbc);
		if (!$this->result) {
			$this->error();
			return false;
			}
		mysql_close($this->dbc);
	}
	
	function articlecomments($timestamp) {
		if (defined("KNIFESQL")) {
			$this->connect();
			$query = "SELECT * FROM comments WHERE articleid = $timestamp";
			$this->alquery($query);
			if ($this->result) {
				while ($comment = mysql_fetch_assoc($this->result)) {
					$articlecomments[$comment[commentid]] = $comment;
					}
				$this->flush();
				return $articlecomments;
				}
			else { $this->error(); }
			}
		else {
			$allcomments = KComments::allcomments();
			$articlecomments = $allcomments[$timestamp];
			return $articlecomments;
			}
		}
		
	# ? ##################################################################
	#	Delete comments belonging to a specific article
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

	# ? ##################################################################
	#	Get a specific comment from an article
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

	# ? ##################################################################
	#	Get the latest few comments
	function latestcomments($number) {
		$allcomments = KComments::allcomments();
		$amount = 0;
		foreach ($allcomments as $newsid => $comments) {
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

	# ? ##################################################################
	#	Save a comment
	function add($articleid) {
		$newcommentid = time();
		$ip = $_SERVER["REMOTE_ADDR"];
		if (!validate_ip($ip)) { $ip = "127.0.0.2"; }
		$data = array(
			'parentcid' => stripslashes(sanitize_variables($_GET[replyto])),
			'name' => stripslashes(sanitize_variables($_POST[comment][name])),
			'email' => stripslashes(sanitize_variables($_POST[comment][email])),
			'url' => stripslashes(sanitize_variables($_POST[comment][url])),
			'ip' => $ip,
			'browser' => sanitize_variables($_SERVER["HTTP_USER_AGENT"]),
			'content' => stripslashes(sanitize_variables($_POST[comment][content])),
			);
		if (defined("KNIFESQL")) {
			$class = KComments::connect();			
			$write_sql = "INSERT INTO comments VALUES ('$articleid', '$newcommentid', '$data[parentcid]', '$data[name]', '$data[email]', '$data[url]', '$data[ip]', '$data[browser]', '$data[content]')";
			$result = mysql_query($write_sql) or die('Query failed: ' . mysql_error());
			return true;
		}
		else {		
			$class = $this->connect();
			$class->settings[$articleid][$newcommentid] = $data;
			$class->save();
			return true;
		}
	}

	# ? ##################################################################
	#	Connect to the comments database
	function connect() {
		if (defined("KNIFESQL")) {
			global $Settings;
			$Storage = $Settings->co[storage];
			$this->dbc = mysql_connect($Storage[mysqlhost], $Storage[mysqluser], $Storage[mysqlpass]);
			@mysql_select_db($Storage[mysqldatabase], $this->dbc);
			return $this->dbc;	
			}
		
		else {
			$dataclass = new CommentStorage('comments');
			return $dataclass;
			}			
		}

	function flush() {
		@mysql_free_result($this->result);
		$this->last_query = null;
		$this->result = null;
	}
	
	function error($str = '') {
		if (!$str) $str = mysql_error();
		array ('query' => $this->last_query, 'error_str' => $str);

		// Is error output turned on or not..
		if ( $this->show_errors ) {
			// If there is an error then take note of it
			print "<div id='error'>
			<h2>Allegory comment database error:</h2><p class=\"error\">[ $str ]</p>
			<p><code>$this->last_query</code></p>
			</div>";
		} else {
			return false;	
		}
	}
	
}

?>