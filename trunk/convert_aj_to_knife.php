<?php

#
#	This script will convert an AJ-Fork v.2.1 news.txt to
#	knife's internal articles database format.
#
#	This is the flat-file version, meaning the knife database will
#	be saved in data/articles.php
#

	if (!defined( "KNIFE_PATH" )) {
    	define( "KNIFE_PATH", dirname(__FILE__)."/");	# Absolute path to current script
    	}

	include("inc/functions.php");								# get needed functions/classes
	include("config.php");
	
if ($_GET[go]) {
	$ajforkdb = file("news.txt");								# load ajfork database
	$knifedb = array();											# set up the knife database array
	foreach ($ajforkdb as $null => $article) {					# traverse the ajfork db entry by entry
		$article = explode("|", $article);						# split up the ajfork entry fields
		$article[3] = str_replace("{nl}", "\n", $article[3]);	# remove the silly {nl} in content
		$knifedb[$article[0]] = array(							# start writing to the array
			"content" => $article[3],							# fill knife content
			"title" => $article[2],								# fill knife title
			"author" => $article[1],
			"lastedit" => $article[1],
			"category" => $article[6],
			"views" => 0,										# set views to neutral
			);
		}
if ($_GET[storage] == "mysql") {
		$mysql_id = mysql_connect(KNIFE_SQL_SERVER, KNIFE_SQL_USER, KNIFE_SQL_PASSWORD);
		mysql_select_db(KNIFE_SQL_DATABASE, $mysql_id);
		
		#
		# get old db
		
		$mysql_query = 'SELECT * FROM `articles`';
		$result = mysql_query($mysql_query) or die('Query failed: ' . mysql_error());
		while ($article = mysql_fetch_assoc($result)) {
			$allarticles["$article[articleid]"] = $article;
			}
		
		if ($allarticles) {
			$knifedb = $allarticles + $knifedb;
			}
			
		krsort($knifedb);
		reset($knifedb);
		#
		#	FIXME: Truncate db and pray here
		#
		foreach ($knifedb as $date => $data) {
			if (!$data[views]) { $data[views] = "0"; }
			foreach ($data as $key => $value) {
				$value = str_replace("'", "&apos;", $value);
				$data[$key] = $value;
				}
			$write_sql = "INSERT INTO articles VALUES ('$date', '$data[category]', '$data[author]', '$data[title]', '$data[content]', '$data[views]')";
			$result = mysql_query($write_sql) or die('Query failed: ' . mysql_error());
			}
		echo "<p>MYSQL selected</p>";
}
else {
	$dataclass = new ArticleStorage('storage');					# load a knife article class
	$olddb = $dataclass->settings['articles'];
	if ($olddb) {
		$knifedb = $olddb + $knifedb;
		krsort($knifedb);
		reset($knifedb);
		echo "<p>Old article database found. Merging with AJ-Fork database.</p>";
		}
$dataclass->settings['articles'] = $knifedb;				# overwrite the knife db with our db
$dataclass->save();											# save it all
}
		echo "<p>Database saved</p>";

																# FINISHED
}

else { 
	echo "<h1>This tool will attempt to convert an AJ-Fork database to Allegory</h1>";
	echo "<p>ALPHA QUALITY</p>
	echo "<p>Convert -> mysql: <a href=\"$_SERVER[SCRIPT_NAME]?go=1&storage=mysql\">start</a></p>";
	echo "<p>Convert -> flatfiles: <a href=\"$_SERVER[SCRIPT_NAME]?go=1&storage=flat\">start</a></p>";
	}
?>