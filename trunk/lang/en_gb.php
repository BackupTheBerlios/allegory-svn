<?php
/*

	Filename: en_gb.php
	Language National: British
	Language International: English
	Version: 0.3
	Author: Øivind Overå Hoel
	Author URI: http://appelsinjuice.org/
	
*/


/*

	%d 	= integer
	%s  = string
	%1$d = first variable. decimal
	%3$s = third variable. string
*/

$lang->login_modtitle			= 'Authentication required';
$lang->login_AuthReq  			= 'You need to provide valid credentials to view any sections of this software.<br /> This requires a browser that supports cookies.';
$lang->login_Username 			= 'Username';
$lang->login_Password 			= 'Password';
$lang->login_Login				= 'Login';
$lang->login_YouAre				= 'You are logged in, ';
$lang->login_noaccess			= 'You do not have access to this panel';
$lang->login_loggedout			= 'Cookies removed. Do you want to <a href="index.php">log in again?</a>';

$lang->menu_dashboard			= 'dashboard';
$lang->menu_write				= 'write';
$lang->menu_edit				= 'edit';
$lang->menu_options				= 'options';
$lang->menu_help				= 'help';
$lang->menu_plugins				= 'plugins';
$lang->menu_logout				= 'logut';

$lang->menu_users				= 'users';
$lang->menu_templates			= 'templates';
$lang->menu_categories			= 'categories';
$lang->menu_setup				= ' setup';


$lang->dashboard_moduletitle	= 'Dashboard';
$lang->dashboard_Statistics		= 'Statistics';
$lang->dashboard_Articles		= 'Articles';
$lang->dashboard_Comments		= 'Comments';
$lang->dashboard_Users			= 'Number of users';
$lang->dashboard_ACS			= 'ArticleDB size';
$lang->dashboard_SS				= 'SettingsDB size';
$lang->dashboard_DBI			= 'Debug info';
$lang->dashboard_templates		= 'Templates';
$lang->dashboard_users			= 'Users';
$lang->dashboard_categories		= 'Categories';

$lang->write_mainmodtitle		= 'Write new article';
$lang->write_metainfo			= 'General information';
$lang->write_content			= 'Content';
$lang->write_category			= 'Categories';
$lang->write_publish			= 'Publish article';
$lang->write_published			= 'saved successfully.';

$lang->edit_module_edit			= 'Edit';
$lang->edit_module_list			= 'Edit articles';
$lang->edit_save				= 'Save changes';
$lang->edit_quickerase			= 'Quick-Erase';
$lang->edit_lastedit			= 'Last edited by';

$lang->options_moduletitle		= 'All options';
$lang->options_general			= 'General options';
$lang->options_d_unique			= 'Unique installkey. %1$s(Used to harden weak passwords)%2$s'; # 1: small 2: /small
$lang->options_mysql_info		= 'MySQL-info';
$lang->options_mysql_username	= '%1$s username'; # 1: mysql
$lang->options_mysql_password	= '%1$s password %2$s(leave empty for none)%3$s'; # 1: mysql 2: small 3: /small
$lang->options_mysql_host		= '%1$s server %2$s(leave empty for localhost)%3$s'; # 1: mysql 2: small 3: /small
$lang->options_mysql_database	= '%1$s database';# 1: mysql
$lang->options_default_lang		= 'Default language';
$lang->options_emailspam		= 'Prevent emailspam?';
$lang->options_storage_backend	= 'Storage backend';
$lang->options_requireregister	= 'Only allow registered users to post comments?';
$lang->options_markdownpreview	= 'Enable comment preview';
$lang->options_markdownpreviewd = 'Comments will be shown formatted using the markdown filter while users<br />still write. This functionality is based on javascript that only works on recent mozilla browsers';

$lang->templates_moduletitle	= 'Edit templates';
$lang->templates_current		= 'Current template';
$lang->templates_list			= 'Article list';
$lang->templates_view			= 'Article';
$lang->templates_comment		= 'Comment';
$lang->templates_commentform	= 'Comment form';
$lang->templates_fillnew		= 'New template will be based on &quot;%1$s&quot;. Enter the name below:'; # 1: Current template name
$lang->templates_newtemplate	= 'Create new template';
$lang->templates_editname		= 'Edit template name';
$lang->templates_quote			= 'Quote';

$lang->categories_current		= 'Existing categories';
$lang->categories_add			= 'Add category';
$lang->categories_defaulttpl	= 'Default template';

$lang->users_add				= 'Add user';
$lang->users_existing			= 'Existing users';
$lang->users_restrict			= 'Allowed categories';
$lang->users_restrictdesc		= 'If the user is a journalist, it can only add articles in selected categories';

$lang->generic_title			= 'Title';
$lang->generic_article			= 'Article';
$lang->generic_author			= 'Author';
$lang->generic_date				= 'Date';
$lang->generic_category			= 'Category';
$lang->generic_comments			= 'Comments';
$lang->generic_actions			= 'Actions';
$lang->generic_do				= 'Do';
$lang->generic_save				= 'Save';
$lang->generic_edit				= 'Edit';
$lang->generic_delete			= 'Delete';
$lang->generic_name				= 'Name';
$lang->generic_add				= 'Add';
$lang->generic_nickname			= 'Nickname';
$lang->generic_email			= 'Email';
$lang->generic_url				= 'Website';
$lang->generic_profile			= 'Profile';
$lang->generic_level			= 'Level';
$lang->generic_regdate			= 'Registration date';
$lang->generic_language			= 'Language';
$lang->generic_preview			= 'Preview';
$lang->generic_click			= 'Click';
$lang->generic_error			= 'Error';
$lang->generic_yes				= 'Yes';
$lang->generic_no				= 'No';
$lang->generic_general			= 'General';

$lang->level_admin				= 'Administrator';
$lang->level_editor				= 'Editor';
$lang->level_journalist			= 'Journalist';
$lang->level_commenter			= 'Commenter';

$lang->search_header			= 'Search results %1$s(%2$d)%3$s'; # 1: small 2: search hits 3: /small

$lang->visible_comment_error_info			= 'One or more problems occured during comment verification. The problems are detailed below, and you are encouraged to fix them and try again:';
$lang->visible_comment_error_name			= 'You left the name field blank - we need your name.';
$lang->visible_comment_error_email			= 'You have to supply a _real_ email address.';
$lang->visible_comment_error_url			= 'Only real urls are accepted. Check that yours starts with the required http:// part.';
$lang->visible_comment_error_content		= 'A comment without content is quite useless, you know... Please supply an actual comment.';
$lang->visible_comment_error_registered		= 'This is a protected name.<br />If it\'s your name, please supply your password below:';
$lang->visible_comment_error_onlyregistered	= 'Only registered users can add comments, and &quot;%1$s&quot; is not a registered users.'; # 1: Name of submitter
$lang->visible_comment_error_requiremail	= 'You have to supply your email to add a comment.';
$lang->visible_comment_preview				= 'Comment preview';


$lang->date_january				= 'January';
$lang->date_februray			= 'February';
$lang->date_march				= 'March';
$lang->date_april				= 'April';
$lang->date_may					= 'May';
$lang->date_june				= 'June';
$lang->date_july				= 'July';
$lang->date_august				= 'August';
$lang->date_september			= 'September';
$lang->date_october				= 'October';
$lang->date_november			= 'November';
$lang->date_december			= 'December';

$lang->date_jan					= 'Jan';
$lang->date_feb					= 'Feb';
$lang->date_mar					= 'Mar';
$lang->date_apr					= 'Apr';
$lang->date_may					= 'May';
$lang->date_jun					= 'Jun';
$lang->date_jul					= 'Jul';
$lang->date_aug					= 'Aug';
$lang->date_sep					= 'Sep';
$lang->date_oct					= 'Oct';
$lang->date_nov					= 'Nov';
$lang->date_dec					= 'Dec';

$lang->date_monday				= 'Monday';
$lang->date_tuesday				= 'Tuesday';
$lang->date_wednesday			= 'Wednesday';
$lang->date_thursday			= 'Thursday';
$lang->date_friday				= 'Friday';
$lang->date_saturday			= 'Saturday';
$lang->date_sunday				= 'Sunday';

$lang->date_mon					= 'Mon';
$lang->date_tue					= 'Tue';
$lang->date_wed					= 'Wed';
$lang->date_thu					= 'Thu';
$lang->date_fri					= 'Fri';
$lang->date_sat					= 'Sat';
$lang->date_sun					= 'Sun';
?>
