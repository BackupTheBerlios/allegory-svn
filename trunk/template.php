<?php
if ($User->level < 4) { 
	die(i18n("login_noaccess"));
	}
	
include("options.php");
$moduletitle = i18n("templates_moduletitle");

function html2specialchars($str){
   $trans_table = array_flip(get_html_translation_table(HTML_SPECIALCHARS));
   return strtr($str, $trans_table);
}

#	Fetch and set up needed data

	$settingclass = new SettingsStorage('settings');
	$templates = $Settings->te;

if($_POST[template] && !$_POST["switch"]) {
	$id = sanitize_variables(stripslashes($_POST[template][id]));
	$templateid = sanitize_variables(stripslashes($_POST[template][id]));
	

	$data = array(
		"name"		=> html2specialchars(stripslashes($_POST[template][name])),
		"listing" 	=> html2specialchars(stripslashes($_POST[template][listing])),
		"view" 	=> html2specialchars(stripslashes($_POST[template][view])),
		"comment" 	=> html2specialchars(stripslashes($_POST[template][comment])),
		"quote" 	=> html2specialchars(stripslashes($_POST[template][quote])),
		"commentform" 	=> html2specialchars(stripslashes($_POST[template][commentform])),
		
		);		
		
	$settingclass->settings['templates'][$id] = $data;
	$settingclass->save();
	$statusmessage = "Template &quot;$data[name]&quot; updated <br /><a href=\"javascript:history.go(-1);\">Go back</a>";
	}

if($_POST[changet]) {


	$chtdo = $_POST[changet];
	
	#
	#	Delete template
	#
	if ($chtdo["delete"]) {
	
		$id = $_POST[id];
	
		$moduletitle = "Delete Template";
		$deletedtplname = $templates[$id][name];
	
		if ($deletedtplname != "Default") {
		$settingclass->delete("templates", $id);
		$statusmessage = "Template &quot;$deletedtplname&quot; deleted<br /><a href=\"javascript:history.go(-1);\">Go back</a>";
		}		
		else {
			$statusmessage = "The default template cannot be deleted!";
			}
		}
	elseif($chtdo["new"]) { 
	
		$id = $_POST[id];
		$templatebase = $templates[$id];
		
		$data = array(
		"name"		=> stripslashes($_POST[changet][name]),
		"listing" 	=> stripslashes($templatebase[listing]),
		"view" 	=> stripslashes($templatebase[view]),
		"comment" 	=> stripslashes($templatebase[comment]),
		"quote" 	=> stripslashes($templatebase[quote]),
		"commentform" 	=> stripslashes($templatebase[commentform]),
		);
		
		if ($data[name] && $data[name] != "") {
			$settingclass->settings['templates'][] = $data;
			$settingclass->save();
		
			$statusmessage = "New template created<br /><a href=\"javascript:history.go(-1);\">Go back</a>";
			}
		else {
			$statusmessage = "Template not created. All templates need a name<br /><a href=\"javascript:history.go(-1);\">Go back</a>";
			}
		}
		
	}


	
if (!$_POST[template] && !$_POST[changet] || $_POST[tswitch][submit]) {

	
	if ($_GET[id]) {
		$templateid = $_GET[id];
		}
	elseif ($_POST[id]) {
		$templateid = $_POST[id];
		}
	else {
		$templateid = 1;
		}


#	load selected template
	$template = $templates[$templateid];
#	set status message
	$statusmessage = "Working with template &quot;$template[name]&quot;";
#	load enabled variables

$tvars_listing = array(
	"{title}" => "Display Article Title",
	"{content}" => "Displays article content",
	"{extended}" => "Displays extended article content (if the &quot;more&quot; button was used)",
#	"{author}" => "Displays a link to the author's email",
	"{lastedit}" => "Displays the name of the last editor for this article",
	"{date}" => "Displays the article's date (check system config for formatting)",
	"{category}" => "Displays the name of the article's category",
	"{category-id}" => "Displays the integer ID of the article's category",
	"[link]...[/link]" => "Displays a permanent link to the article",
	"[friendlylink]...[/friendlylink]" => "Displays a permanent link to the article using its title",
	"[com-link]...[/com-link]" => "Displays a link to the article (only if comments are enabled for it)",
	);
	ksort($tvars_listing);

$tvars_view = array(
	"{title}" => "Display Article Title",
	"{content}" => "Displays article content",
	"{extended}" => "Displays extended article content (if the &quot;more&quot; button was used)",
	"{author}" => "Displays a link to the author's email",
	"{author-name}" => "Displays the author's name in plain text",
	"{date}" => "Displays the article's date (check system config for formatting)",
	"{category}" => "Displays the name of the article's category",
	"{category-id}" => "Displays the integer ID of the article's category",
	);
	ksort($tvars_view);
	
$tvars_comment = array(
	"{none}" => "Display nothing",
	);
	ksort($tvars_comment);
	
$tvars_quote = array(
	"{name}" => "Name of person being quoted",
	"{quote}" => "Text being quoted",
	);
	ksort($tvars_quote);
	
$tvars_commentform = array(
	"{none}" => "Display nothing",
	);
	ksort($tvars_commentform);

#
	$main_content .= '
	<div id="edit_templates_wrapper">
	<div class="div_normal templates_fields">
       <form method="post">
			<fieldset>
				<legend>'.i18n("templates_current").' ('.$template[name].')</legend>
			<input type="hidden" name="template[id]" value="'.$templateid.'" />
			<input type="hidden" name="panel" value="template" />
			
			<p>
				Unlike CuteNews/AJ-Fork, we will explain templates here<br />
				eventually
			</p>
			
			<label for="edit_template_articlelist"><h3>'.i18n("templates_list").'</h3></label>
			<table>';
	
	foreach ($tvars_listing as $variable => $description) {
		$main_content .= "
				<tr>
					<td><span class=\"vinfo\" title=\"$description\">$variable</span></td>
					<td>$description</td>
				</tr>";
		}
	$template[listing] = htmlspecialchars($template[listing]);
	$template[view] = htmlspecialchars($template[view]);
	$template[comment] = htmlspecialchars($template[comment]);
	$template[quote] = htmlspecialchars($template[quote]);
	$template[commentform] = htmlspecialchars($template[commentform]);		
	$main_content .= '
			</table>
			<textarea class="tamedium" id="edit_template_articlelist" name="template[listing]">'.$template[listing].'</textarea>
			
			<label for="edit_template_view"><h3>'.i18n("templates_view").'</h3></label>
			<table>';
	
	foreach ($tvars_view as $variable => $description) {
		$main_content .= "
				<tr>
					<td><span class=\"vinfo\" title=\"$description\">$variable</span></td>
					<td>$description</td>
				</tr>";
		}
		
	$main_content .= '
			</table>
			<textarea class="tamedium" id="edit_template_view" name="template[view]">'.$template[view].'</textarea>
			
			<label for="edit_template_comment"><h3>'.i18n("templates_comment").'</h3></label>
			<table>';
	
	foreach ($tvars_comment as $variable => $description) {
		$main_content .= "
				<tr>
					<td><span class=\"vinfo\" title=\"$description\">$variable</span></td>
					<td>$description</td>
				</tr>";
		}
		
	$main_content .= '
			</table>
			<textarea class="tasmall" id="edit_template_comment" name="template[comment]">'.$template[comment].'</textarea>
			<label for="edit_template_quote"><h3>'.i18n("templates_quote").'</h3></label>
			<table>';

	foreach ($tvars_quote as $variable => $description) {
		$main_content .= "
				<tr>
					<td><span class=\"vinfo\" title=\"$description\">$variable</span></td>
					<td>$description</td>
				</tr>";
		}
		
	$main_content .= '
			</table>
			<textarea class="tasmall" id="edit_template_quote" name="template[quote]">'.$template[quote].'</textarea>
			<label for="edit_template_commentform"><h3>'.i18n("templates_commentform").'</h3></label>
			<table>';
	
	foreach ($tvars_commentform as $variable => $description) {
		$main_content .= "
				<tr>
					<td><span class=\"vinfo\" title=\"$description\">$variable</span></td>
					<td>$description</td>
				</tr>";
		}		
	$main_content .= '
			</table>
			<textarea class="tasmall" id="edit_template_commentform" name="template[commentform]">'.$template[commentform].'</textarea>
			
			<fieldset>
				<legend>'.i18n("templates_editname").'</legend>
				<input type="text" class="inshort" id="edit_template_name" name="template[name]" value="'.$template[name].'"/>
			</fieldset>

			
			
			<p><input type="submit" value="'.i18n("generic_save").'" /></p>
			</fieldset>
		</form>
	</div>
	<div class="div_extended templates_options">
		<form id="edit_template_switch" method="post">
			<fieldset>
				<legend>'.i18n("generic_actions").'</legend>
				<p>';
					$main_content .= makeDropDown($templates, "id", $templateid);
					$main_content .= '</p><p>
					<input type="submit" name="tswitch[submit]" value="'.i18n("generic_edit").'" /> 
					<input type="submit" class="delete" name="changet[delete]" value="'.i18n("generic_delete").'" />
				</p>
				<p>
					'.i18n("templates_fillnew").'
				</p>
				<p>
					<input type="text" name="changet[name]" class="inshort" id="changetname" /> 
					<br />
					<input type="submit" name="changet[new]" value="'.i18n("templates_newtemplate").'" />
				</p>
			</fieldset>
		</form>	
	</div>
	';
}
?>