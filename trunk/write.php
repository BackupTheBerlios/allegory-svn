<?php
if ($User->level < 2) { 
	die(i18n("login_noaccess"));
	}
	
	#
	#	Setup
	#
	$moduletitle 		= i18n("write_mainmodtitle");
	$settingsclass 		= new SettingsStorage('settings');
	$currentcats 		= $settingsclass->settings['categories'];

	include(KNIFE_PATH.'/inc/class.articles.php');
	

#
#	If article submitted
#

if($_POST[article] && !$_POST[preview]) {
	$KAclass = new KArticles;
	$statusmessage = $KAclass->add($User->username);
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