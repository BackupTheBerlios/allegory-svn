<?php
$array = array (
  'settings' => 
  array (
    'templates' => 
    array (
      1 => 
      array (
        'name' => 'Default',
        'listing' => '<div class="article" style="margin-bottom: 40px;">

<div class="articlemeta">
<h1>[friendlylink]{title}[/friendlylink] {comments}</h1>
<p>
<strong><small>Published by: {author} (last edit by {lastedit}) around {date}<br />
Filed in: {category}<br />
Views: {views}<br />
Latest comment by: {latestcomment}<br />
[friendlylink]Read all about it[/friendlylink]<br />
[link]Read all about the old link[/link]</small></strong>
</p>
</div>

<div class="article_text">
{content}
</div>

</div>',
        'view' => '<div class="article" style="margin-bottom: 40px;">
<h1>{title}</h1>
<strong>{date}</strong>
<div class="article_text">
{content}

{extended}
<p>views: {views}</p>
</div>
</div>

<h1>Comments</h1>',
        'comment' => '<div class="comment">
<div class="commentheader">
{gravatar} <a href="{url}">{author}</a> <small>({date} - [mail="mail"] {reply}) {ip}</small>
</div>
{parentquote}
{comment}{date=d/m/y}
</div>',
        'quote' => '<blockquote><p>Quoting <strong>{name}</strong>:</p>{quote}</blockquote>',
        'commentform' => '<fieldset><legend>Add comment?</legend>
<input type="text" name="comment[parentcid]" /> Parent<br />
<input type="text" name="comment[name]" id="commentame" /> Name<br />
<input type="text" name="comment[email]" id="commentemail" /> Email<br />
<input type="text" name="comment[url]" id="commenturl" /> URL<br /><br />
Comment <small>(allowed html: {allowedtags})</small><br />
<textarea onkeyup="markUp(\'\', false);" name="comment[content]" rows="7" cols="50" id="commentcomment"></textarea>
<p>[preview="Forhåndsvis"] [save="Send"]</p>
</fieldset>',
      ),
      2 => 
      array (
        'name' => 'NotDefault',
        'listing' => '<li>[friendlylink]{title}[/friendlylink]</li>',
        'view' => '<div class="article" style="margin-bottom: 40px;">
<h1>{title}</h1>
<strong>{date}</strong>
<div class="article_text">
{content}

{extended}
</div>',
        'comment' => '',
        'quote' => '',
        'commentform' => '',
      ),
    ),
    'users' => 
    array (
      'eruin' => 
      array (
        'registered' => '1107710351',
        'nickname' => 'Øivind Hoel',
        'password' => '03e6b9d67854d8c1265927e3322f77cdcd55e995',
        'email' => 'oivind.hoel@appelsinjuice.org',
        'url' => 'http://appelsinjuice.org',
        'profile' => 'Min profil
Del to
Fire
Fem
Seks
Sju
Åtte',
        'level' => '4',
        'avatar' => 'http://localhost/knife/source/trunk/graphics/talk.png',
      ),
      'admin' => 
      array (
        'registered' => '1110301615',
        'lastlogin' => '',
        'nickname' => 'Random Administrator',
        'password' => '290cce4f9f84d616b8c58b5a702e29fa636d7f09',
        'email' => '',
        'url' => '',
        'profile' => '',
        'level' => '4',
        'cats' => '0',
      ),
    ),
    'categories' => 
    array (
      0 => 
      array (
        'name' => 'Stilig',
        'template' => '',
      ),
      2 => 
      array (
        'name' => 'Webdesign',
        'template' => '',
      ),
      3 => 
      array (
        'name' => 'Weblogg',
        'template' => '',
      ),
      5 => 
      array (
        'name' => 'TempTesttwo',
        'template' => '1',
      ),
      6 => 
      array (
        'name' => 'PHP',
        'template' => '1',
      ),
      7 => 
      array (
        'name' => 'Life',
        'template' => '1',
      ),
    ),
    'configuration' => 
    array (
      'storage' => 
      array (
        'backend' => 'flat',
        'mysqluser' => 'appelsinjuice_o',
        'mysqlpass' => '',
        'mysqlhost' => '',
        'mysqldatabase' => 'appelsinjuice_o',
      ),
      'articles' => 
      array (
        'dateformat' => 'd/m/Y',
      ),
      'comments' => 
      array (
        'requireregister' => 'yes',
        'markdownpreview' => 'yes',
        'dateformat' => 'd/m/Y H:i:s',
        'avatar' => 
        array (
          'size' => '20',
          'defaulturl' => 'http://appelsinjuice.org/cutepress/graphics/icons/users.png',
        ),
        'requiremail' => 'yes',
      ),
      'general' => 
      array (
        'uniquekey' => 'b83b9c064c365cc68d675c8e1ca986904159a9f0',
        'typekey' => 'WRbB3duitFfHK13T072K',
        'dateoffset' => '0',
        'emailspam' => 'yes',
        'defaultlanguage' => 'nb_no.php',
      ),
    ),
  ),
);
?>