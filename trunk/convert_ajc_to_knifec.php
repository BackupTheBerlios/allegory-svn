<?php


	include("inc/functions.php");								# get needed functions/classes
	$ajforkdb = file("comments.txt");							# load ajfork database
	$knifedb = array();											# set up the knife database array
	
			$articledatabase = new ArticleStorage('storage');
			$allarticles = $articledatabase->settings['articles'];
	
	foreach ($ajforkdb as $null => $commentline) {	
		unset($comment);
		$commentline = trim($commentline);
		$commentline = explode("|>|", $commentline);
		$articleid = $commentline[0];
		if (array_key_exists($articleid, $allarticles)) {
		$comments = explode("||", $commentline[1]);
			foreach ($comments as $null => $comment) {
				unset($email);
				unset($url);
				$comment = explode("|", $comment);
				if ($comment[0] != "") {
					if ($comment[2] != "none") {
					if($comment[2] != "" and preg_match("/^[\.A-z0-9_\-]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z]{1,4}$/", $comment[2])){
						$email = $comment[2];
						}
					else {
						$url = $comment[2];
						}}
						
					$knifedb[$articleid][$comment[0]] = array(
						"parentcid" => "",
						"name" => $comment[1],
						"email" => $email,
						"url" => $url,	
						"ip" => $comment[3],	
						"browser" => "",
						"content" => $comment[4],						
						);
					}
				}
			}
		}
		
echo "<pre>";
print_r($knifedb);
echo "</pre>";

	$dataclass = new CommentStorage('comments');					# load a knife article class
	$dataclass->settings = $knifedb;				# overwrite the knife db with our db
	$dataclass->save();											# save it all

																# FINISHED
?>