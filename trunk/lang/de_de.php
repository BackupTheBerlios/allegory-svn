<?php
/*

	Filename: de_de.php
	Language National: Deutsch
	Language International: German
	Version: 0.3
	Author: Lars Wohlfahrt
	Author URI: http://www.digitalday.de
	
*/


/*

	%d 	= integer
	%s  = string
	%1$d = first variable. decimal
	%3$s = third variable. string
*/

$lang->login_modtitle			= 'Anmeldung erforderlich';
$lang->login_AuthReq  			= 'Sie benötigen einen Browser der Cookies unterstüzt';
$lang->login_Username 			= 'Benutzername';
$lang->login_Password 			= 'Passwort';
$lang->login_Login				= 'Anmeldung';
$lang->login_YouAre				= 'Sie sind angemeldet, ';
$lang->login_noaccess			= 'Sie haben hier für keine Zugriffsrechte';
$lang->login_loggedout			= 'Cookies gelöscht. Wollen Sie sich erneut <a href="index.php">anmelden?</a>';

$lang->menu_dashboard			= 'Schreibtisch';
$lang->menu_write				= 'Schreiben';
$lang->menu_edit				= 'Bearbeiten';
$lang->menu_options				= 'Einstellungen';
$lang->menu_help				= 'Hilfe';
$lang->menu_plugins				= 'Erweiterungen';
$lang->menu_logout				= 'Abmelden';

$lang->menu_users				= 'Benutzer';
$lang->menu_templates			= 'Vorlagen';
$lang->menu_categories			= 'Rubriken';
$lang->menu_setup				= ' Setup';


$lang->dashboard_moduletitle		= 'Schreibtisch';
$lang->dashboard_Statistics		= 'Statistik';
$lang->dashboard_Articles		= 'Artikel';
$lang->dashboard_Comments		= 'Kommentare';
$lang->dashboard_Users			= 'Anzahl der Benutzer';
$lang->dashboard_ACS			= 'Größe der Artikeldatenbank';
$lang->dashboard_SS			= 'Größe der Einstellungen';
$lang->dashboard_DBI			= 'Debug Informationen';
$lang->dashboard_templates		= 'Vorlagen';
$lang->dashboard_users			= 'Benutzer';
$lang->dashboard_categories		= 'Rubriken';

$lang->write_mainmodtitle		= 'Neuen Artikel verfassen';
$lang->write_metainfo			= 'Allgemeine Informationen';
$lang->write_content			= 'Inhalt';
$lang->write_category			= 'Rubriken';
$lang->write_publish			= 'Artikel veröffentlichen';
$lang->write_published			= 'erfolgreich gespeichert.';

$lang->edit_module_edit			= 'Bearbeiten';
$lang->edit_module_list			= 'Artikel bearbeiten';
$lang->edit_save				= 'Änderungen speichern';
$lang->edit_quickerase			= 'schnell Löschen';
$lang->edit_lastedit			= 'Zuletzt bearbeitet von';

$lang->options_moduletitle		= 'Alle Einstellungen';
$lang->options_general			= 'Allgemeine Einstellungen';
$lang->options_d_unique			= 'Einzigartiger Installationsschlüssel. %1$s(wird benutzt um schwache Passwörter zu stärken)%2$s'; # 1: small 2: /small
$lang->options_mysql_info		= 'MySQL-Information';
$lang->options_mysql_username	= '%1$s Benutzername'; # 1: mysql
$lang->options_mysql_password	= '%1$s Passwort %2$s(falls kein Passwort verwendet wird, bitte leer lassen)%3$s'; # 1: mysql 2: small 3: /small
$lang->options_mysql_host		= '%1$s Server %2$s(für localhost bitte leer lassen)%3$s'; # 1: mysql 2: small 3: /small
$lang->options_mysql_database	= '%1$s Datenbank';# 1: mysql
$lang->options_default_lang		= 'Sprache (Standart)';
$lang->options_emailspam		= 'E-Mailspam verhindern?';
$lang->options_storage_backend	= 'Speicherart';
$lang->options_requireregister	= 'Nur registrierten Benutzern das Kommentieren erlauben?';
$lang->options_markdownpreview	= 'Kommentarvorschau aktivieren';
$lang->options_markdownpreviewd = 'Kommentare werden mit Markdown formatiert angezeigt, während die Benutzer<br />noch schreiben. Diese Funktion basiert auf JavaScript und funktioniert nur in neuen Mozilla Browsern.';

$lang->templates_moduletitle	= 'Vorlagen bearbeiten';
$lang->templates_current		= 'Vorlage (normal)';
$lang->templates_list			= 'Artikelliste';
$lang->templates_view			= 'Artikel';
$lang->templates_comment		= 'Kommentar';
$lang->templates_commentform	= 'Kommentarfeld';
$lang->templates_fillnew		= 'Die neue Vorlage wird auf der ausgewählten basieren. Geben Sie unten den neuen Namen ein:'; # 1: Current template name
$lang->templates_newtemplate	= 'Neue Vorlage hinzufügen';
$lang->templates_editname		= 'Namen der Vorlage ändern';
$lang->templates_quote			= 'Zitat';

$lang->categories_current		= 'Erstellte Rubriken';
$lang->categories_add			= 'Rubrik hinzufügen';
$lang->categories_defaulttpl	= 'Vorlage (normal)';

$lang->users_add				= 'Benutzer hinzufügen';
$lang->users_existing			= 'Erstellte Benutzer';
$lang->users_restrict			= 'Erlaubte Rubriken';
$lang->users_restrictdesc		= 'Wenn der Benutzer ein Journalist ist, kann er nur Artikel in den markieren Rubriken schreiben';

$lang->generic_title			= 'Titel';
$lang->generic_article			= 'Artikel';
$lang->generic_author			= 'Autor';
$lang->generic_date				= 'Datum';
$lang->generic_category			= 'Rubrik';
$lang->generic_actions			= 'Aktionen';
$lang->generic_do				= 'OK';
$lang->generic_save				= 'Speichern';
$lang->generic_edit				= 'Bearbeiten';
$lang->generic_delete			= 'Löschen';
$lang->generic_name				= 'Name';
$lang->generic_add				= 'Hinzufügen';
$lang->generic_nickname			= 'Nickname';
$lang->generic_email			= 'Email';
$lang->generic_url				= 'Webseite';
$lang->generic_profile			= 'Profil';
$lang->generic_level			= 'Level';
$lang->generic_regdate			= 'Registrationsdatum';
$lang->generic_language			= 'Sprache';
$lang->generic_preview			= 'Vorschau';
$lang->generic_click			= 'Klick';
$lang->generic_error			= 'Fehler';
$lang->generic_yes				= 'Ja';
$lang->generic_no				= 'Nein';
$lang->generic_general			= 'Allgemein';

$lang->level_admin				= 'Administrator';
$lang->level_editor				= 'Redakteur';
$lang->level_journalist			= 'Journalist';
$lang->level_commenter			= 'Kommentator';

$lang->search_header			= 'Suchergebnisse %1$s(%2$d)%3$s'; # 1: small 2: search hits 3: /small

$lang->visible_comment_error_info			= 'Ein oder mehrere Fehler sind während der Kommentarverifikation aufgetreten. Die genauen Probleme sind unten aufgeführt.';
$lang->visible_comment_error_name			= 'Das Namensfeld ist leer - wir brauchen aber den Namen.';
$lang->visible_comment_error_email			= 'Sie brauchen eine _echte_ E-Mailadresse';
$lang->visible_comment_error_url			= 'Nur echte URLs werden akzeptiert. Prüfen Sie ob ihre mit http:// startet.';
$lang->visible_comment_error_content		= 'Ein leerer Kommentar ist irgendwie sinnlos... Bitte ergänzen Sie den Inhalt.';
$lang->visible_comment_error_registered		= 'Dieser Name ist reserviert.<br />Falls der Name zu Ihnen gehört, bitte geben Sie das Passwort ein:';
$lang->visible_comment_error_onlyregistered	= 'Nur registrierte Benutzer können Kommentare abgeben und &quot;%1$s&quot; ist kein registrierter Benutzer.'; # 1: Name of submitter
$lang->visible_comment_error_requiremail	= 'Um einen Kommentar abzugeben benötigen Sie eine E-Mailadresse.';
$lang->visible_comment_preview				= 'Kommentarvorschau';


$lang->date_january				= 'Januar';
$lang->date_februray			= 'Februar';
$lang->date_march				= 'März';
$lang->date_april				= 'April';
$lang->date_may					= 'Mai';
$lang->date_june				= 'Juni';
$lang->date_july				= 'Juli';
$lang->date_august				= 'August';
$lang->date_september			= 'September';
$lang->date_october				= 'Oktober';
$lang->date_november			= 'November';
$lang->date_december			= 'Dezember';

$lang->date_jan					= 'Jan';
$lang->date_feb					= 'Feb';
$lang->date_mar					= 'Mär';
$lang->date_apr					= 'Apr';
$lang->date_may					= 'Mai';
$lang->date_jun					= 'Jun';
$lang->date_jul					= 'Jul';
$lang->date_aug					= 'Aug';
$lang->date_sep					= 'Sep';
$lang->date_oct					= 'Okt';
$lang->date_nov					= 'Nov';
$lang->date_dec					= 'Dez';

$lang->date_monday				= 'Montag';
$lang->date_tuesday				= 'Dienstag';
$lang->date_wednesday			= 'Mittwoch';
$lang->date_thursday			= 'Donnerstag';
$lang->date_friday				= 'Freitag';
$lang->date_saturday			= 'Samstag';
$lang->date_sunday				= 'Sonntag';

$lang->date_mon					= 'Mo';
$lang->date_tue					= 'Di';
$lang->date_wed					= 'Mi';
$lang->date_thu					= 'Do';
$lang->date_fri					= 'Fr';
$lang->date_sat					= 'Sa';
$lang->date_sun					= 'So';
?>
