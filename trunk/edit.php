<?php
if ($User->level < 3) { 
	die(i18n("login_noaccess"));
	}

include_once(KNIFE_PATH.'/inc/class.articles.php');
include_once(KNIFE_PATH.'/inc/class.comments.php');

#
#	Show edit for for single article
#

$menus["sub_edit"] = "
<ul><li>proof</li><li>of</li><li>concept</li></ul>
";

if ($_GET[id] && !$_POST[id] && !$_GET[action]) {

	
	$settingsclass = new SettingsStorage('settings');
	$currentcats = $settingsclass->settings['categories'];
	
	$editentry = $Articles->getarticle($_GET[id]);
	$comments = $Comments->articlecomments($_GET[id]);
	
	$moduletitle = i18n("edit_module_edit"). " &quot;$editentry[title]&quot;";
	# form stuff here
	
	# set up category checkboxes
	$editcats = explode(", ", $editentry[category]);
		foreach ($editcats as $catkey => $catvalue) {
			$newcats["$catvalue"] = $catvalue;
			}
			
	foreach ($currentcats as $catid => $catinfo) {
		if (array_key_exists($catid, $newcats)) { $selected = 'checked="checked"'; }
		$catformfields .= "<input $selected type=\"checkbox\" name=\"article[category][]\" id=\"catbox$catid\" value=\"$catid\" />
							<label for=\"catbox$catid\">$catinfo[name]</label><br />";
							unset ($selected);
	}
	
$main_content .= '
<script src="inc/quicktags.js" language="JavaScript" type="text/javascript"></script>
<div id="edit_article_wrapper">
	<form id="edit_article_form" method="post">
	<div class="div_normal">
		<fieldset>
			<legend>'.i18n("write_metainfo").'</legend>
		<ul><li><strong>'.i18n("generic_author").'</strong>: '.$editentry[author].'</li><li><strong>'.i18n("generic_date").'</strong>: '.date("j F Y, H:i", $_GET[id]).'</li></ul>

		<input type="hidden" name="panel" value="edit" />
		<input type="hidden" name="article[views]" value="'.$editentry[views].'" />
		<input type="hidden" name="id" value="'.$_GET[id].'" />
		<p>
			<label for="edit_article_title">'.i18n("generic_title").'</label><br />
			<input class="inlong" value="'.$editentry[title].'" type="text" id="edit_article_title" name="article[title]" />
		</p>
		</fieldset>
		<fieldset>
			<legend>'.i18n("write_content").'</legend>
		<p>
			<script language="JavaScript" type="text/javascript">edToolbar();</script>
			<textarea class="tamedium" id="edit_article_content" name="article[content]">'.$editentry[content].'</textarea>
		</p>
		</fieldset>
		<p>
			<input type="submit" name="preview" value="'.i18n("generic_preview").'" /> <input type="submit" value="'.i18n("edit_save").'" />
		</p>
	</div>
	
	<script type="text/javascript" language="JavaScript">
	<!--
	edCanvas = document.getElementById(\'edit_article_content\');
	//-->
	</script>

	<div class="div_extended">
		<fieldset>
			<legend>'.i18n("write_category").'</legend>
			'.$catformfields.'
		</fieldset>
	</div>
	</form>
';
$comments = $Comments->articlecomments($_GET[id]);
	if ($comments) {
		$main_content .= "<div class=\"div_normal\"><form><fieldset><legend>Comments</legend><table><tr><th>Date</th><th>Name</th><th>Content</th></tr>";
		foreach ($comments as $commentid => $comment) {
			$main_content .= "<tr><td>".date("d/m/y H:i", $commentid)."</td><td>$comment[name]</td><td>$comment[content]</td></tr>";
			}
		$main_content .= '</table></fieldset></form></div>';
		}
$main_content .= '</div>';
}

#
#	Edit article routine
#

if ($_POST[id] && !$_POST[editlist][submit] && !$_POST[preview]) {
	# Give the user a status message
	$statusmessage = $Articles->edit($_POST[id], $User->username);
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
#	Show list of articles
#

if (!$_GET[id] && !$_POST[editlist]) {

	$moduletitle = i18n("edit_module_list");
	$main_content .= "
	<form id=\"edit_article_list\" method=\"post\" class=\"cpform\">
	<table>
		<tr>
			<th>".i18n("generic_title")."</th>
			<th>".i18n("generic_date")."</th>
			<th>".i18n("generic_comments")."</th>
			<th>".i18n("generic_category")."</th>
			<th>".i18n("generic_author")."</th>
			<th style=\"text-align: right;\">".i18n("generic_actions")."</th>
		</tr>";
	foreach($Articles->listarticles("", "") as $date => $article) {

		$catarray = explode(", ", $article[category]);
		$catamount = count($catarray);
	
		if($catamount == 1) { 
			$catrowcontent = $Settings->ca[$article[category]][name];
			}

		else {
			# Replace the category numbers with their names
			foreach ($catarray as $null => $thiscatid) {
				$thiscatinfo = $Settings->ca[$thiscatid];
				$catarray[$null] = $thiscatinfo[name];
				}
		
			$thiscatnamelisting = implode(", ", $catarray);
			$catrowcontent = "<acronym title=\"$thiscatnamelisting\">$article[category]</acronym>";
			}
		
		if ($article[lastedit]) { $article[author] = "$article[author] *"; }
		
		$main_content .= "<tr id=\"editlist$date\" onmousedown=\"knife_bgc(this, true);\">
			<td onmousedown=\"document.getElementById('del$date').checked = (document.getElementById('del$date').checked ? false : true);\"><a href=\"?panel=edit&amp;id=$date\">$one $article[title]</a></td>
			<td onmousedown=\"document.getElementById('del$date').checked = (document.getElementById('del$date').checked ? false : true);\">".date("d/m/y", $date)."</td>
			<td onmousedown=\"document.getElementById('del$date').checked = (document.getElementById('del$date').checked ? false : true);\">".count($Comments->articlecomments($date))."</td>
			<td onmousedown=\"document.getElementById('del$date').checked = (document.getElementById('del$date').checked ? false : true);\">$catrowcontent</td>
			<td onmousedown=\"document.getElementById('del$date').checked = (document.getElementById('del$date').checked ? false : true);\" title=\"".i18n("edit_lastedit")." $article[lastedit]\">$article[author]</td>
			<td style=\"text-align: right;\"><span class=\"delete\"><a href=\"?panel=edit&amp;id=$date&amp;action=delete\" title=\"".i18n("edit_quickerase")." $article[title] ?\">X</a></span> <input type=\"checkbox\" id=\"del$date\" name=\"id[]\" value=\"$date\" /></td>
			</tr>";	
		}
	$main_content .= "</table><div style=\"text-align: right;\"><br /><input type=\"submit\" name=\"editlist[submit]\" value=\"".i18n("generic_do")."\" /></div></form>";

}

#
#	Delete
#
if ($_GET[action] == "delete" || $_POST[editlist]) {

	$moduletitle = "Delete article";
	
	
	if ($_GET[action] == "delete") {
		$id = $_GET[id];
		$statusmessage = $Articles->delete($id, false);		
		}
		
	else {
		$id = $_POST[id];
		$statusmessage = $Articles->delete($id, true);
		}
}


?>