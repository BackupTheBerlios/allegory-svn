<form id="searchform" action="" method="post">
	<fieldset>
		<legend>Search</legend>
		<p>
			<input type="text" name="search[terms]" id="search[terms]" />
			<label for="search[terms]">Terms</label>
		</p>
		<p>
			<input type="radio" name="search[where]" value="title" id="search_title" />
			<label for="search_title">Search in title</label><br />
			<input type="radio" name="search[where]" value="content" id="search_content" />
			<label for="search_content">Search in content</label>
		</p>
		<p>
		<input type="submit" name="search[submit]" value="Search" />
		</p>
	</fieldset>
</form>

<div id="searchresults">
<?php

if ($_POST[search]) {
	$allcats = $settingsdatabase->settings['categories'];
#	$Kaclass = new Karticles;
	$results = $KAclass->search($_POST[search][terms], $_POST[search][where]);
	$resultnumber = count($results);
	if ($resultnumber >= 1) {
	echo '<fieldset><legend>Search results <small>('.$resultnumber.')</small></legend>';
	foreach ($results as $date => $info) {
		unset ($url);
		unset ($cats);
		$cats = explode(", ", $info[category]);
		
		foreach ($cats as $null => $thiscatid) {
		$thiscatinfo = $allcats[$thiscatid];
		$cats[$null] = $thiscatinfo[name];
		}
		
		$url = $KAclass->urlconstructor($info, $cats);
		echo '<a href="'.$_SERVER[SCRIPT_NAME].'/'.$url.'">'.$info[title].'</a><br />';
		}
	echo '</fieldset>';
	}
}
?>
</div>