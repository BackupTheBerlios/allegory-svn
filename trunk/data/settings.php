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

<div style="background: #333; padding: 3px; border-bottom: 5px solid #fffeee;">
<h2>Comments</h2>
</div>',
        'comment' => '<div class="comment">
<div class="commentheader">
<a href="{url}">{author}</a> <small>({date} - <a href="mailto:{email}">@</a> {reply})</small>
</div>
{parentquote}
{comment}
</div>',
        'quote' => '<blockquote><p>Quoting <strong>{name}</strong>:</p>{quote}</blockquote>',
        'commentform' => '<fieldset><legend>Add comment?</legend>
<input type="text" name="comment[parent]" /> Parent<br />
<input type="text" name="comment[name]" /> Name<br />
<input type="text" name="comment[email]" /> Email<br />
<input type="text" name="comment[url]" /> URL<br /><br />
Comment <small>(allowed html: {allowedtags})</small><br />
<textarea name="comment[content]" rows="7" cols="50"></textarea>
<p><input type="submit" name="comment[submit]" value="Add" /></p>
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
      ),
      'commenter' => 
      array (
        'registered' => '1107808829',
        'nickname' => 'commenterrr',
        'password' => '35a5aa9ebdfe1ad9afdcb323ffb3fb05341eb6b5',
        'email' => '',
        'url' => '',
        'profile' => '',
        'level' => '1',
      ),
      'jubbag' => 
      array (
        'registered' => '1107811415',
        'nickname' => 'Jared Judge',
        'password' => '4749709942162cd47a8a71020b6446efe3cfad79',
        'email' => '',
        'url' => '',
        'profile' => '',
        'level' => '4',
      ),
      'yoda' => 
      array (
        'registered' => '1107889400',
        'nickname' => 'The German',
        'password' => 'db11d626556186900a1cee5692406255e104a540',
        'email' => '',
        'url' => '',
        'profile' => '',
        'level' => '4',
      ),
      'stealtheye' => 
      array (
        'registered' => '1107983191',
        'nickname' => 'StealthEye van Holland',
        'password' => 'dfe642fd06030c47d3c2ea15d28589bc9ce1fca0',
        'email' => '',
        'url' => '',
        'profile' => '',
        'level' => '4',
      ),
      'admin' => 
      array (
        'registered' => '1107983473',
        'nickname' => 'like.. an admin',
        'password' => '17dc22e4ab03b081598ea4b1d273cdc6327b942a',
        'email' => '',
        'url' => '',
        'profile' => '',
        'level' => '4',
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
  ),
);
?>