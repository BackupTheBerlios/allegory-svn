<?php

	if (!defined( "KNIFE_PATH" )) {
    	define( "KNIFE_PATH", dirname(__FILE__)."/");	# Absolute path to current script
    	}

	include_once(KNIFE_PATH.'/inc/class.articles.php');
	include_once(KNIFE_PATH.'/inc/class.comments.php');
	include_once(KNIFE_PATH.'/inc/class.users.php');				# load userclass - can't live without
	include_once(KNIFE_PATH.'/inc/class.settings.php');
	
	include_once(KNIFE_PATH.'/inc/functions.php');
	include_once(KNIFE_PATH.'/plugins/markdown.php');
	include_once(KNIFE_PATH.'/plugins/live-comment-preview.php');

	$pathinfo_array	= explode("/",$_SERVER[PATH_INFO]);
	$ACDB 	= new KComments;
	$Userclass 		= new KUsers;
	$AADB 		= new KArticles;
	$Settings 		= new KSettings;
	
	$Settings->getCats();			#
	$Settings->getTemplates();		#	Initialize settings
	$Settings->getConfig();			#
	
	
	$settingsdatabase = new SettingsStorage('settings');
	$alltemplates = $Settings->te;
	$allcats = $Settings->ca;
	$Config = $Settings->co;
	$allusers = $Userclass->getusers();

	include_once(KNIFE_PATH.'/config.php');					# load temporary config

	#FIXME: Recognize cookies here
	require(KNIFE_PATH.'/lang/'.$Settings->co[general][defaultlanguage]);
#
#	Reset some variables
#
$timestamp = 0;

#
#	Display articles
#

if ($Settings->co[storage][backend] == "mysql") { define("KNIFESQL", "yes"); }
	
if ($template) {
	$template = $Settings->te[$template];
	}
else { 
	$template = $Settings->te[1];
	}
if (!$amount && isset($_GET[amount])) { 
	$amount = $_GET[amount];											#FIXME
	}
if (!$cat && isset($_GET[cat])) {
	$cat = "$_GET[cat]";
	}
if (!$from && isset($_GET[from])) {
	$from = "$_GET[from]";
	}
	
# Get all the articles...
if ($static) { $from = null; }
$allarticles = $AADB->listarticles($amount, $from);

if (!$_GET[k] and !$pathinfo_array[1] or $static) {
$i = 0;

foreach($allarticles as $date => $article) {
	# Destroy variables from last loop
	$output = $template[listing];
	# category stuff
		
		/*
			what should be done here is the following:
			grab categories (with index and name) from the settings database
			
			compare the !NUMBERS! in $article[category] with the database to find
			the relevant category names for use in {category}.
			
			display only items that match an array_key_exists($_GET[cat], $categories_arr)
			
			*/
			
	$catarray = explode(", ", $article[category]);
		
	foreach ($catarray as $null => $catarraycatid) {
		$newcatarray[$catarraycatid] = $catarraycatid;
		}

	# Replace the category numbers with their names
	foreach ($catarray as $null => $thiscatid) {
		$thiscatinfo = $allcats[$thiscatid];
		$catarray[$null] = $thiscatinfo[name];
		}
			
	$thiscatnamelisting = implode(", ", $catarray);
	
	#
	#	Functions that might skip the article
	#
	
	
#	$AADB->skip($article);
	
	if ((isset($cat) and array_key_exists($cat, $newcatarray))) {
		# great, the article belongs to the requested category
		}
	else { 
		if (!isset($cat)) {
			# display anything then
			}
		else {
			# curses, this doesn't fit. move on
			continue;
			}	
		}
	
	# skip draft articles
	$statusarray = explode("|", $article[status]);
	print_r($statusarray);
	if ($statusarray[0] == "draft") {
		echo "draft";
		continue;
		}
	

	# Actual parsing needs to be done by the parser class!
		
	# pre-parsing variable setup
	if (stristr($article[content], "<!--more-->")) {
		$article[content] = explode("<!--more-->", $article[content]);
		$article[content][0] = Markdown($article[content][0]);
		$article[content][1] = Markdown($article[content][1]);
		# start parsing template variables
		$output = str_replace("{content}", $article[content][0], $output);
		$output = str_replace("{extended}", $article[content][1], $output);
		}
		
	$output = str_replace("{title}", $article[title], $output);
	$article[content] = Markdown($article[content]);

	$output = str_replace("[link]","<a title=\"".htmlspecialchars($article[title])."\" href=\"$PHP_SELF?k=$date\">", $output);
	$output = str_replace("[/link]","</a>", $output);    		
	$output = str_replace("[friendlylink]","<a title=\"".htmlspecialchars($article[title])."\" href=\"$_SERVER[SCRIPT_NAME]/".$AADB->urlconstructor($article, $catarray)."\">", $output);
    $output = str_replace("[/friendlylink]","</a>", $output);
#    if ($article[status
	$output = str_replace("{content}", $article[content], $output);
	$output = str_replace("{extended}", "", $output);
	$output = str_replace("{author}", $article[author], $output);
	$output = str_replace("{category}", $thiscatnamelisting, $output);
	$output = str_replace("{date}", date($Settings->co[articles][dateformat], $date), $output);
		
	$articlescomments = $ACDB->articlecomments($date);
	if (is_array($articlescomments)) {
		krsort($articlescomments);
		reset($articlescomments);
		
		# get the latest
		$tempcomments = $articlescomments;
		$lastcomment = array_shift($tempcomments);
		unset($tempcomments);
		}
		
	$article[comments] = count($articlescomments);
	$output = str_replace("{latestcomment}", $lastcomment[name], $output);
	$output = str_replace("{comments}", $article[comments], $output);
	$article[views] = $AADB->articleupdate($date, "views", "noupdate");
	$output = str_replace("{views}", $article[views], $output);
	
	if ($article[lastedit]) {
		$output = str_replace("{lastedit}", $article[lastedit], $output);
		}
	else { 
		$output = str_replace("{lastedit}", "", $output);
		}

	echo $output;
	$i++;
	if ($i >= $amount) {
		break 1;
		}
	unset($catarray);
	unset($newcatarray);
	unset($lastcomment);
	unset($article);
	}
	# FIXME: This should be put in a seperate file ala cutenews that handles variable deaths, etc
}
	
	elseif (($_GET[k] or $pathinfo_array[1]) and !$static) {
		include("display_article.php");
		}
			
	if (!$_GET[debug] and !$static) {
				echo " (debug mode)<br /><pre>";
				print_r($_GET);
				echo "\n\n-----------&lt;- get  | post   -&gt;---------------\n\n";
				print_r($_POST);
				echo "\n\n-----------&lt;- post | cookie -&gt;---------------\n\n;";
				print_r($_COOKIE);
				echo "</pre>";
				}
				
	unset ($cat);
	unset ($allarticles);
	unset ($from);
	unset ($amount);
	unset ($template);

?>

<!--
    
    Content publishing: <?=SCRIPT_TITLE;?> <?=SCRIPT_VERSION;?> 
    	GPL-licensed by Øivind Hoel (http://appelsinjuice.org/)
    
-->
