<?php

function datedropdown($name, $id, $type) {
	$out = "<select name=\"$name\" id=\"$id\">";
	if ($type == "day") {
		$i = 1;
		$currentday = date("j", time());
		while ($i <= 31) {
			if ($i == $currentday) { $out .= "<option selected=\"selected\" value=\"$i\">$i</option>"; }
			else { $out .= "<option value=\"$i\">$i</option>"; }
			$i++;
			}
		$out .= "</select>\n";
		}
	elseif ($type == "year") {
		$i = date("Y", time());
		$e = $i + 10;
		$currentyear = $i;
		while ($i <= $e) {
			if ($i == $currentyear) { $out .= "<option selected=\"selected\" value=\"$i\">$i</option>"; }
			else { $out .= "<option value=\"$i\">$i</option>"; }
			$i++;
			}
		$out .= "</select>\n";	
	
	}
		
	return $out;
}



if ($User->level < 2) { 
	die(i18n("login_noaccess"));
	}
	
	#
	#	Setup
	#
	$moduletitle 		= i18n("write_mainmodtitle");
	$settingsclass 		= new SettingsStorage('settings');
	$currentcats 		= $Settings->ca;
	

#
#	If article submitted
#

if($_POST[article] && !$_POST[preview]) {
	$statusmessage = $Articles->add($User->username);
}


#
#	If preview
#

if ($_POST[preview]) {

	include("plugins/markdown.php");
	$main_content = '
	
	<div id="article_preview_wrapper">
		<div class="div_normal">
			<fieldset>
				<legend>Article content preview:</legend>	
			'.Markdown($_POST[article][content]).'
			</fieldset>
		</div>
	
	</div>';

}

#
#	If nothing
#

if (!$_POST[article]) {

	# set up category checkboxes
	foreach ($currentcats as $catid => $catinfo) {
	
		# get a config for default cat select
		if ($catid == "0") { $checked = "checked=\"checked\" "; } else { $checked = ""; }
		$catformfields .= "<input type=\"checkbox\" name=\"article[category][]\" id=\"catbox$catid\" value=\"$catid\" $checked/>
							<label for=\"catbox$catid\">$catinfo[name]</label><br />";
	}

	$main_content = '
<script src="inc/quicktags.js" language="JavaScript" type="text/javascript"></script>
<div id="add_article_wrapper">
	<form id="add_article_form" class="cpform" method="post">
	<div class="div_normal">
		<fieldset>
			<legend>'.i18n("write_metainfo").'</legend>
		<input type="hidden" name="panel" value="write" />
		<p>
			<label for="add_article_title">'.i18n("generic_title").'</label><br />
			<input class="inlong" type="text" id="add_article_title" name="article[title]" />
		</p>
		</fieldset>
		<fieldset>
			<legend>'.i18n("write_content").'</legend>
		<p>	
			<script language="JavaScript" type="text/javascript">edToolbar();</script>
			<textarea class="tamedium" id="add_article_content" name="article[content]"></textarea>
		</p>
		</fieldset>
		<p>
			<input type="submit" name="preview" value="'.i18n("generic_preview").'" /><input type="submit" value="'.i18n("write_publish").'" />
		</p>
	</div>
	
	<script type="text/javascript" language="JavaScript">
	<!--
	edCanvas = document.getElementById(\'add_article_content\');
	//-->
	</script>


	<div class="div_extended">
		<fieldset>
			<legend>'.i18n("write_meta_header").'</legend>
				<div id="post_status_setting">
					<fieldset><legend>Status</legend>
					<input id="post_status_pub" type="radio" name="article[status]" value="pub"> 
						<label for="post_status_pub">Published</label><br />
					<input id="post_status_draft" type="radio" name="article[status]" value="draft"> 
						<label for="post_status_draft">Draft</label><br />
					<input disabled="disabled" id="post_status_priv" type="radio" name="article[status]" value="priv"> 
						<label for="post_status_priv">Private</label><br />
					</fieldset>
					
					<fieldset>
						<legend class="link">
						<label onclick="toggleDisplay(\'start_date_div\');" for="start_date_set">Start date</label>
						<input type="checkbox" id="start_date_set" name="article[start_date_set]" value="true"/>
						</legend>
						<div id="start_date_div">
							<input type="text" size="5" name="article[start_time]" value="'.date("H:i").'" id="start_time"/>
							<label for="start_time"> ('.i18n("hhour").':'.i18n("hminute").')</label><br />
							'. datedropdown("article[start_day]", "start_day", "day") .'
							'. htmldropdown($lang->date_month_short, "article[start_month]", strtolower(date("M", time()))) .'
							'. datedropdown("article[start_year]", "start_year", "year") .'
						</div>
					</fieldset>
					
					<fieldset>
						<legend class="link">
						<label onclick="toggleDisplay(\'stop_date_div\');" for="stop_date_set">Stop date</label>
						<input type="checkbox" id="stop_date_set" name="article[stop_date_set]" value="true"/>
						</legend>
						<div id="stop_date_div">
							<input type="text" size="5" name="article[stop_time]" value="'.date("H:i").'" id="stop_time"/>
							<label for="stop_time"> ('.i18n("hhour").':'.i18n("hminute").')</label><br />
							'. datedropdown("article[stop_day]", "stop_day", "day") .'
							'. htmldropdown($lang->date_month_short, "article[stop_month]", strtolower(date("M", time()))) .'
							'. datedropdown("article[stop_year]", "stop_year", "year") .'
						</div>
					</fieldset>
				</div>
		</fieldset>
	</div>	

	<div class="div_extended">
		<fieldset>
			<legend>'.i18n("write_category").'</legend>
			'.$catformfields.'
		</fieldset>';
# Run filter:		$ext_extended = run_filters('write-extended-fieldset', $ext_extended);
		$main_content .= $ext_extended .'
		
	</div>
	
	<div class="div_extended">
		<fieldset>
			<legend onclick="toggleDisplay(\'markdown_help\');">Markdown Help <small>('.i18n("generic_click").')</small></legend>
				<div id="markdown_help">
					<p>here is some nice markdown<br />syntax help 
					for you... say thanks!</p>
				</div>
		</fieldset>
	</div>	
</form>

</div>';
	
	}

?>