<?php

/*
 *	Set up page
 */
	$moduletitle 	= i18n("dashboard_moduletitle");
	$statusmessage 	= i18n("login_YouAre").$User->nickname;
	$statusmessage 	= randomquote();

/*
 *	Get settings
 */
	
	$totalarticles 		= count($Articles->listarticles());
	$totalusers 		= count($User->getusers());
	$totalcats			= count($Settings->ca);
	
	$main_content = "
	<div id=\"dashboard_wrapper\">
		<div class=\"div_extended\">
		<fieldset>
			<legend>".i18n("dashboard_Statistics")."</legend>
		".i18n("dashboard_Articles").": $totalarticles<br />
		".i18n("dashboard_Users").": $totalusers<br />
		".i18n("dashboard_ACS").": ".formatsize(filesize(KNIFE_PATH.'/data/articles.php'))."<br />
			<acronym title=\"".i18n("templates").", ".i18n("users").", etc\">".i18n("dashboard_SS")."</acronym>: ".formatsize(filesize(KNIFE_PATH.'/data/settings.php'))."
		</div>";
		
			
	
	#
	#	TODO. Remove later.
	#
   
	
	$main_content .= "
	<div class=\"div_normal\">
		<fieldset>
			<legend>Todo</legend>
		<ol>
			<li>Users<ul><li><del>Add</del></li><li><del>Edit</del></li><li><del>Delete</del></li><li><del>Passwords encrypted sha1(md5(password+unique_id_generated_on_install))</del></li><li>Non-Admin Profiles</li></ul></li>
			<li>Options</li>
			<li>Templates<ul><li><del>Add template</del></li><li><del>Edit template</del></li><li><del>Delete template</del></li></ul></li>
			<li><del>Login system</del>
				<ul>
					<li>Access levels
					<ul>
						<li>4 / Admin
							<ul><li>Can do anything including deleting other admins</li></ul></li>
						<li>3 / Editor
							<ul><li>Add posts</li>
								<li>Edit all posts</li>
								<li>Personal options</li>
								<li>Set up categories</li>
							</ul></li>
						<li>2 / Journalist
							<ul><li>Add posts</li>
								<li>Edit <strong>own</strong> posts</li>
							</ul></li>
						<li>4 / Commenter
							<ul><li>Nick will be registered for commenting</li>
								<li>Can view/edit personal options</li>
							</ul></li>
					</ul>
					</li>
					<li>Language selected on login, saved in expireless cookie?</li>
				</ul>
			</li>
			<li>Categories<ul><li>Add</li><li>Edit</li><li>Delete</li><li>Default template</li></ul></li>
			<li>Plugins</li>
			<li>Comments</li>
			<li><del>Write news</del><ul><li>Custom date</li></ul></li>
			<li><del>Edit news</del><ul><li>Custom date</li><li><del>Delete article</del></li><li><del>Delete articles</del></li></ul></li>
			<li>Generate archive</li>
			<li>Search!</li>
			<li>MySQL</li></ol>
		</fieldset>
		<fieldset>
			<legend>Musical drive</legend>
		<ol>
			<li>The Donnas ( Spend the night )</li>
			<li>Porcupine Tree ( In absentia )</li>
			<li>Ed Harcourt ( From every sphere )</li>
			<li>U2
				<ul><li>How to dismantle an atomic bomb</li>
				<li>All that you can't leave behind</li></ul></li>
			<li>Kent
				<ul><li>Hagnesta Hill</li><li>Isola</li></ul></li>
			<li>Kula Shaker ( K )</li>
			<li>Lisa Miskovsky<ul><li>Lisa Miskovsky</li><li>Fallingwater</li></ul></li>
			<li>Modest Mouse (Good news for people who love bad news)</li>
			</ol>
			</fieldset>
		</div>
	</div>";
	$main_content .= '<div class="div_normal"><fieldset><legend>Latest comments</legend>';
	$last = $Comments->latestcomments("5");
	foreach ($last as $commentid => $commentdata) {
		$article = $Articles->getarticle($commentdata[parent]);
		$title = $article[title];
		$main_content .= date("d/m ", $commentid) . $commentdata[name] . " <small>commenting $title</small> <blockquote>" . $commentdata[content] . "</blockquote><br />";
		}
	$main_content .= '</fieldset></div>';

?>
