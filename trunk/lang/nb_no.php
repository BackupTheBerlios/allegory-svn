<?php
/*

	Filename: nb_no.php
	Language National: Norsk Bokmål
	Language International: Norwegian
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

$lang->login_modtitle			= 'Autentisering påkrevd';
$lang->login_AuthReq  			= 'Du må oppgi gyldig brukernavn og passord for å få tilgang til dette systemet. <br />Dette krever at nettleseren din støtter cookies';
$lang->login_Username 			= 'Brukernavn';
$lang->login_Password 			= 'Passord';
$lang->login_Login				= 'Logg inn';
$lang->login_YouAre				= 'Du er logget inn, ';
$lang->login_noaccess			= 'Du har ikke tilgang til denne siden';
$lang->login_loggedout			= 'Cookies fjernet. Vil du <a href="index.php">logge inn på nytt?</a>';

$lang->menu_dashboard			= 'skrivebord';
$lang->menu_write				= 'skriv';
$lang->menu_edit				= 'rediger';
$lang->menu_options				= 'innstillinger';
$lang->menu_help				= 'hjelp';
$lang->menu_plugins				= 'tillegg';
$lang->menu_logout				= 'logg ut';

$lang->menu_users				= 'brukere';
$lang->menu_templates			= 'maler';
$lang->menu_categories			= 'kategorier';
$lang->menu_setup				= '-oppsett';


$lang->dashboard_moduletitle	= 'Skrivebord';
$lang->dashboard_Statistics		= 'Statistikk';
$lang->dashboard_Articles		= 'Artikler';
$lang->dashboard_Comments		= 'Kommentarer';
$lang->dashboard_Users			= 'Antall brukere';
$lang->dashboard_ACS			= 'Total artikkelstørrelse';
$lang->dashboard_SS				= 'Størrelse på innstillinger';
$lang->dashboard_DBI			= 'Avlusingsinfo';
$lang->dashboard_templates		= 'Maler';
$lang->dashboard_users			= 'Brukere';
$lang->dashboard_categories		= 'Kategorier';

$lang->write_mainmodtitle		= 'Skriv ny artikkel';
$lang->write_metainfo			= 'Generell informasjon';
$lang->write_content			= 'Innhold';
$lang->write_category			= 'Kategori(er)';
$lang->write_publish			= 'Publisèr artikkel';
$lang->write_published			= 'ble lagret';
$lang->write_meta_header		= 'Metainfo';

$lang->edit_module_edit			= 'Rediger';
$lang->edit_module_list			= 'Rediger artikler';
$lang->edit_save				= 'Lagre endringer';
$lang->edit_quickerase			= 'Slett';
$lang->edit_lastedit			= 'Sist redigert av';

$lang->options_moduletitle		= 'Alle innstillinger';
$lang->options_general			= 'Generelle innstillinger';
$lang->options_d_unique			= 'Unik installasjonsnøkkel. %1$s(Brukes til hardning av svake passord)%2$s'; # 1: small 2: /small
$lang->options_mysql_info		= 'MySQL-info';
$lang->options_mysql_username	= '%1$s-brukernavn'; # 1: mysql
$lang->options_mysql_password	= '%1$s-passord %2$s(la stå tomt for å ikke bruke passord)%3$s'; # 1: mysql 2: small 3: /small
$lang->options_mysql_host		= '%1$s-server %2$s(la stå tomt for å koble til &quot;localhost&quot;)%3$s'; # 1: mysql 2: small 3: /small
$lang->options_mysql_database	= '%1$s-database';# 1: mysql
$lang->options_default_lang		= 'Standardspråk';
$lang->options_emailspam		= 'Motarbeid emailspamming?';
$lang->options_storage_backend	= 'Databasemotor';
$lang->options_requireregister	= 'Bare registrerte brukere kan skrive kommentarer?';
$lang->options_markdownpreview	= 'Aktivèr forhåndsvisning';
$lang->options_markdownpreviewd = 'Kommentaren vil bli vist formatert med Markdown-filteret mens brukere <br />skriver. Denne funksjonaliteten er basert på javascript, og støttes bare av nyere Mozilla-nettlesere';

$lang->templates_moduletitle	= 'Rediger maler';
$lang->templates_current		= 'Nåværende mal';
$lang->templates_list			= 'Artikkelliste';
$lang->templates_view			= 'Artikkel';
$lang->templates_comment		= 'Kommentar';
$lang->templates_commentform	= 'Kommentarskjema';
$lang->templates_fillnew		= 'Nye maler vil bli basert på &quot;%1$s&quot;. Fyll inn det nye navnet under:'; # 1: Current template name
$lang->templates_newtemplate	= 'Lag ny mal';
$lang->templates_editname		= 'Endre malnavn';
$lang->templates_quote			= 'Sitat';

$lang->categories_current		= 'Eksisterende kategorier';
$lang->categories_add			= 'Legg til';
$lang->categories_defaulttpl	= 'Standardmal';

$lang->users_add				= 'Ny bruker';
$lang->users_existing			= 'Eksisterende brukere';
$lang->users_restrict			= 'Tillatte kategorier';
$lang->users_restrictdesc		= 'Om brukeren er journalist, kan den bare skrive artikler innen denne kategorien';

$lang->generic_title			= 'Tittel';
$lang->generic_article			= 'Artikkel';
$lang->generic_author			= 'Forfatter';
$lang->generic_date				= 'Dato';
$lang->generic_category			= 'Kategori';
$lang->generic_comments			= 'Kommentarer';
$lang->generic_actions			= 'Valg';
$lang->generic_do				= 'Utfør';
$lang->generic_save				= 'Lagre';
$lang->generic_edit				= 'Rediger';
$lang->generic_delete			= 'Slett';
$lang->generic_name				= 'Navn';
$lang->generic_add				= 'Legg til';
$lang->generic_nickname			= 'Klengenavn';
$lang->generic_email			= 'Epost';
$lang->generic_url				= 'Webside';
$lang->generic_profile			= 'Profil';
$lang->generic_level			= 'Nivå';
$lang->generic_regdate			= 'Registrasjonsdato';
$lang->generic_language			= 'Språk';
$lang->generic_preview			= 'Forhåndsvisning';
$lang->generic_click			= 'Klikk';
$lang->generic_error			= 'Feil';
$lang->generic_yes				= 'Ja';
$lang->generic_no				= 'Nei';
$lang->generic_general			= 'Generelt';

$lang->level_admin				= 'Administrator';
$lang->level_editor				= 'Redigerer';
$lang->level_journalist			= 'Journalist';
$lang->level_commenter			= 'Kommentator';

$lang->search_header			= 'Søkeresultat %1$s(%2$d)%3$s'; # 1: small 2: search hits 3: /small

$lang->visible_comment_error_info			= 'Det oppstod et problem under verifiseringen av kommentaren. De spesifikke problemene finner du under, og du oppfordres til  å rette opp i dem og prøve igjen:';
$lang->visible_comment_error_name			= 'Du må identifisere deg med et navn.';
$lang->visible_comment_error_email			= 'Du må oppgi en ekte emailadresse.';
$lang->visible_comment_error_url			= 'Du må oppgi en ekte URL. Gjorde du det? Sjekk at den starter med den nødvendige http://-delen.';
$lang->visible_comment_error_content		= 'En kommentar uten innhold er vel egentlig ikke særlig verdifull, er du ikke enig?';
$lang->visible_comment_error_registered		= 'Dette navnet er registrert.<br />Hvis det er ditt navn, vennligst send ditt passord under:';
$lang->visible_comment_error_onlyregistered	= 'Bare registrerte brukere kan legge inn kommentarer, og &quot;%1$s&quot; er ikke registrert.'; # 1: Name of submitter
$lang->visible_comment_error_requiremail	= 'Du må oppgi emailadressen din for å legge inn kommentarer.';
$lang->visible_comment_preview				= 'Kommentarforhåndsvisning';


$lang->date_month_long = array(
	"january" => "Januar",
	"february" => "Februar",
	"march" => "Mars",
	"april" => "April",
	"may" => "Mai",
	"june" => "Juni",
	"july" => "Juli",
	"august" => "August",
	"september" => "September",
	"october" => "Oktober",
	"november" => "November",
	"december" => "Desember",
	);

$lang->date_month_short = array(
	"jan" => "Jan",
	"feb" => "Feb",
	"mar" => "Mar",
	"apr" => "Apr",
	"may" => "Mai",
	"jun" => "Jun",
	"jul" => "Jul",
	"aug" => "Aug",
	"sep" => "Sep",
	"oct" => "Oct",
	"nov" => "Nov",
	"dec" => "Des",
	);
$lang->date_day_long = array(
	"monday" => "Mandag",
	"tuesdag" => "Tirsdag",
	"wednesday" => "Onsdag",
	"thursdag" => "Torsdag",
	"friday" => "Fredag",
	"saturday" => "Lørdag",
	"sunday" => "Søndag",
	);

$lang->date_day_short = array(
	"mon" => "Man",
	"tue" => "Tir",
	"wed" => "Ons",
	"thu" => "Tor",
	"fri" => "Fre",
	"sat" => "Lør",
	"sun" => "Søn",
	);
	
$lang->hhour 				= 'tt';		# hourhour (for date help) (ie: 24)
$lang->hyear 				= 'åååå';	# yearyearyear (for date help) (ie: 2005)
$lang->hmonth 				= 'mm';		# monthmonth (for date help) (ie: 12)
$lang->hday 				= 'dd';		# dayday (for date help) (ie: 31)
$lang->hminute				= 'mm';		# minuteminute (for date help) (ie: 59)

?>
