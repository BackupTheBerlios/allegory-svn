<?php

$documentation[1][title] = "Installation";
$documentation[1][content] = '<p>Installing '.SCRIPT_TITLE.' is simple. Here is the basic procedure:
<ul>
<li>Upload the files to a directory called &quot;'.SCRIPT_TITLE.'&quot; on your server</li>
<li>Make sure all the files are readable</li>
<li>Make sure all files in data/ are writable (CHMOD 755, 666 or 777)</li>
<li>Point your browser to http://example.com/'.SCRIPT_TITLE.'/ and follow the install procedure</li></ul>
</p>';


$documentation[2][title] = "Variables that can be used when including Allegory";
$documentation[2][content] = '<p>The standard include-code used to display content is:
<pre>&lt;?php
	include("path/to/allegory/display_articles.php");
?&gt;</pre>

This code can be amended with quite a few variables. These variables are:<br />
<strong>$amount</strong>: Specifies the amount of articles to show in one go<br />
<strong>$from</strong>: Specifies how many articles to skip before showing any<br />
<strong>$cat</strong>: Display articles only from this category<br />
<strong>$static</strong>: Set to true to keep this include 100% static
<strong>$display</strong>: Specifies whether to display any specials</p>
<p>Example usage could be:
<pre>&lt;?php
	$amount = 10;
	$cat = 3;
	$static = true;
	include("path/to/allegory/display_articles.php");
&gt;</pre>
</p>';


?>