<form id="searchform" action="" method="post">
	<fieldset>
		<legend>Search</legend>
		<p>
			<label for="search[terms]">Terms</label><br />
			<input type="text" name="search[terms]" id="search[terms]" />
			<input type="checkbox" name="search[regexp]" value="yes" id="search_regexp"/>
			<label for="search_regexp"><small>regexp</small></label>
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

#
#	FIXME: This entire file should probably be moved into display_articles
#

if ($_POST[search]) {
	$allcats = $settingsdatabase->settings['categories'];
	$results = $KAclass->search($_POST[search][terms], $_POST[search][where], $_POST[search][regexp]);
	$resultnumber = count($results);
	if ($resultnumber >= 1) {
	echo '<fieldset><legend>'.i18n("search_header", "<small>,$resultnumber,</small>").'</legend>';
	$results = multi_sort($results, "relevance");
	foreach ($results as $date => $info) {
		unset ($url);
		unset ($cats);
		$cats = explode(", ", $info[category]);
		
		foreach ($cats as $null => $thiscatid) {
		$thiscatinfo = $allcats[$thiscatid];
		$cats[$null] = $thiscatinfo[name];
		}
		
		$url = $KAclass->urlconstructor($info, $cats);
		echo '<a href="'.$_SERVER[SCRIPT_NAME].'/'.$url.'">'.$info[title].'</a>('.$info[relevance].')<br />';
		}
	echo '</fieldset>';
	}
}
?>
</div>