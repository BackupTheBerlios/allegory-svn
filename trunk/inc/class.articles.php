<?php

#
#	Article storage abstraction class
#



class KArticles {
	
	
	function connect() {
		if (defined("KNIFESQL")) {
			$mysql_id = mysql_connect(KNIFE_SQL_SERVER, KNIFE_SQL_USER, KNIFE_SQL_PASSWORD);
			mysql_select_db(KNIFE_SQL_DATABASE, $mysql_id);
			return $mysql_id;	
			}
		
		else {
			$dataclass = new ArticleStorage('storage');
			return $dataclass;
			}			
		}
		
	function disconnect($id) {
		if (defined("KNIFESQL")) {
			mysql_close($id);
			return true;
			}
		}
	#
	#	Add new article
	function add($author) {
		
		# Get current time
		$now = time();
		
		# Remove dangerous stuff
		$_POST[article][content] = sanitize_variables($_POST[article][content]);
		$_POST[article][title] = sanitize_variables($_POST[article][title]);
		$_POST[article][category] = sanitize_variables($_POST[article][category]);
		
		# Implode the category array
		$savecats = implode(", ", $_POST[article][category]);

		# Enter it all into an array for use later
		$data = array(
			"timestamp" => $now,
			"content" 	=> stripslashes($_POST[article][content]),
			"title" 	=> stripslashes($_POST[article][title]),
			"author" 	=> stripslashes($author),
			"category" 	=> stripslashes($savecats),
			"views"		=> "0",
			);
			
		# hook to add custom fields here.
		#	$data = run_filters('admin-new-savedata', $data);
			
		if (defined("KNIFESQL")) {
			$dataclass = KArticles::connect();			
			$write_sql = "INSERT INTO articles VALUES ('$data[timestamp]', '$data[category]', '$data[author]', '$data[title]', '$data[content]', '$data[views]')";
			$result = mysql_query($write_sql) or die('Query failed: ' . mysql_error());
			$statusmessage = i18n("generic_article"). " &quot;$data[title]&quot; ". i18n("write_published");
			return $statusmessage;
			}

		else {
			$dataclass = KArticles::connect();
			$dataclass->settings['articles'][$now] = $data;
			$dataclass->save();

			# Give the user a status message
			$statusmessage = i18n("generic_article"). " &quot;$data[title]&quot; ". i18n("write_published");
			return $statusmessage;
			}
		}
	
	function edit($timestamp) {
	
		}
	#
	#	Delete article(s)
	function delete($timestamp, $multiple="FALSE") {
		if (!$multiple) {
			#	This means we're deleting a single entry
			if (defined("KNIFESQL")) {
				$dataclass = KArticles::connect();			
				$sql = "DELETE FROM articles WHERE articleid = '$timestamp'";
				$result = mysql_query($sql) or die ('Query failed: ' . mysql_error());
				$statusmessage = "Article deleted";
				return $statusmessage;
				}
			else {
				$dataclass = KArticles::connect();
				$dataclass->delete($timestamp);
				$statusmessage = "Article deleted";
				return $statusmessage;
				}
			}
		else {
			#	This means we're deleting more than one entry
			if (defined("KNIFESQL")) {
				$dataclass = KArticles::connect();				
				foreach ($timestamp as $null => $thisid) {
					unset($sql);
					$sql = "DELETE FROM articles WHERE articleid = '$thisid'";
					$result = mysql_query($sql) or die ('Query failed: ' . mysql_error());
					}
				$statusmessage = "All selected articles deleted";
				return $statusmessage;
				}
			else {			
				$dataclass = KArticles::connect();
				foreach ($timestamp as $null => $thisid) {
					$dataclass->delete($thisid);
					}
				}
			}
		}
	
	
	#
	#	Construct a list of all available articles
	function listarticles($limit="false", $from="false", $cat="false") {
		if (defined("KNIFESQL")) {
			$class = KArticles::connect();
			$mysql_query = "SELECT * FROM articles";
			$result = mysql_query($mysql_query) or die('Query failed: ' . mysql_error());
			while ($article = mysql_fetch_assoc($result)) {
				$allarticles["$article[articleid]"] = $article;
				}
			$closed = KArticles::disconnect($class);
			return $allarticles;
			}
		else {
			$dataclass = KArticles::connect();
			$allarticles = $dataclass->settings['articles'];
			krsort($allarticles);
			reset($allarticles);
			if ($from) {
					$allarticles = array_slice_key($allarticles, $from);
				}
			return $allarticles;
			}
		}
	
	#
	#	Get a specific article based on its timestamp id
	function getarticle($timestamp) {
			if (defined("KNIFESQL")) {
				$class = KComments::connect();
				$mysql_query = "SELECT * FROM articles WHERE articleid = $timestamp";
				$result = mysql_query($mysql_query) or die('<h1>SQL query failed.</h1><p>Looks like the timestamp is invalid or not supplied...:</p> ' . mysql_error());
				$article = mysql_fetch_assoc($result);
				return $article;
				}
			
			else {
				$allarticles = KArticles::listarticles();
				$article = $allarticles[$timestamp];
				unset($allarticles);
				return $article;
				}
		}
		
	#
	#	Bump article metainfo
	function articleupdate($timestamp, $method, $modifier) {
		if ($method == "views") {
			# this method increments views by one
			# and returns the current amount of views
			if (defined("KNIFESQL")) {
				$dataclass = KArticles::connect();
				$mysql_query = "SELECT views FROM articles WHERE articleid = $timestamp";
				$result = mysql_query($mysql_query) or die("Failed: " . mysql_error());
				$views = mysql_fetch_assoc($result);
				$views = $views[views];
				if ($modifier == "update") {
					$views++;
					$mysql_query = "UPDATE articles SET views = $views WHERE articleid = $timestamp";
					$result = mysql_query($mysql_query) or die("Failed: " . mysql_error());
					}
				return $views;
				}
			else {
				$dataclass = KArticles::connect();
				$views = $dataclass->settings['articles'][$timestamp][views];
				if ($modifier == "update") {
					$views++;
					$dataclass->settings['articles'][$timestamp][views] = $views;
					$dataclass->save();
					}
				return $views;
				}
			}
		}
	function urlconstructor($article, $categories) {
		global $config_urlstyle;
		
		unset($config_urlstyle[0]);
		$constructor = $config_urlstyle;
		# get the keys
		$titlekey = array_search("title", $config_urlstyle);
		$catkey = array_search("category", $config_urlstyle);
		
		# right, now populate the array
		$constructor[$titlekey] = urlTitle($article[title]);
		if ($catkey) { $constructor[$catkey] = urlTitle(implode("_", $categories)); }
		
		$return = implode("/", $constructor);
		return $return;
		}
		
	function urldeconstructor($array, $method="title") {
		global $config_urlstyle;
	
		if ($method == "title") {
			$titlekey = array_search("title", $config_urlstyle);
			$k = $array[$titlekey];
			return $k;
			}
		if ($method == "category") {
			$catkey = array_search("category", $config_urlstyle);
			$category = $array[$catkey];
			return $categories;
			}
		}

	function search($term, $where=false) {
		# VERY crude search tool. Single arg
		#
		# FIXME:
		#	Accept multiple needles
		#	Allow limiting searches to title or content
		if (!$where) { $where = "content"; }
		$haystack = KArticles::listarticles();
		foreach ($haystack as $date => $article) {
			if (preg_match("/$term/i", $article[$where])) {
				$matches[$date] = array(
					"title" => $article[title],
					"category" => $article[category],
					);
				}
			}
		$matching = array_search($term, $haystack);
		return $matches;	
	}
}

?>