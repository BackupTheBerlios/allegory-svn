<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="no" xml:lang="no">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Allegory Example-page</title>
<style type="text/css">

/*
	Tag redefinition
*/

html,body {
	color: #333;
	font: 0.87em "Trebuchet MS";
	margin: 0;
}

body {
	background: #fffaee url(graphics/talk.png) top right no-repeat;
}

a {
	text-decoration: none;
	display: inline-block;
	color: #f32988;
	}
	
a:hover {
	}

h1, h2, h3 {
	margin-top: 3px;
/*	font-family: "Georgia";*/
	}
/*
	Major ID's
*/

#body {
	margin: auto;
	background: #fff;
	width: 910px;
	border: 5px solid #f3eedc;
	}
	
#mainframe {
	padding: 0 5px 5px 5px;
	border: 1px solid #e6d69e;
	}
	
#header h1 {
	font-size: 23px;
}

#header h1:first-letter {
	color: #f32988;
	}
	
/*
	Menus
*/
	
#menu li a {
	padding-left: 25px;
	}

#menu li {
	display: inline;
	margin: 0 8px 0 0;
	padding: 3px;
	border-bottom: 3px solid #f1f3d8;
	}
#menu li:hover {
	background: #fff9e2;
	cursor: pointer;
	border-bottom: 3px solid #819faf;
	}

#status {
	margin-left: auto;
	margin-right: 10px;
	text-align: right;
	width: 100%;
	background: #e5f363;
	border: 1px solid #e5f3c3;
	padding: 0px;
	margin-bottom: 10px;
}

.div_normal {
	float: left;
	min-width: 670px;
}

.div_extended {
	padding-right: 10px;
	float: right;
	min-width: 210px;
	max-width: 210px;
}

#footer {
	opacity: 0.2;
	border-top: 1px solid #dae4ea;
	border-bottom: 1px solid #dae4ea;
	margin-top: 20px;
	clear: both;
	}

input.delete {
	background: #d94848;
	color: #fff;
	}

input, textarea {
	background: #f6f7f8;
	border: 1px solid #dae4ea;
	margin: 0 5px 1px 0;
	}

input:focus, textarea:focus {
	background: #fff;
	}

textarea {
	width: 540px;
	}

.inshort {
	width: 150px;
}
.inmedium {
	width: 250px;
}
.inlong {
	width: 350px;
}

.tasmall {
	height: 150px;
}
.tamedium {
	height: 350px;
}
.talarge {
	height: 550px;
}
	
fieldset {
	border: 1px solid #f3e3ac;
	-moz-border-radius: 5px;
	padding: 5px;
}
fieldset legend {
	font-weight: bold;
	font-size: 130%;
	}

.comment {
	margin: 0 0 10px 30px;
	border-bottom: 1px dotted #999;
	padding-bottom: 10px;
}

.commentheader {
	margin: 3px 0 3px -20px;
	}
	
blockquote {
	padding-left: 10px;
	border-left: 2px solid #333;
	}
h2 {
color: #f32988;
}

</style>
</head>
<body>
<div id="body">
<div id="mainframe">
	<div id="header">
	<h1></h1>
		<div id="menu">
		<?php
			$menus["main"] = "<ul><li><a href=\"$_SERVER[SCRIPT_NAME]\">Main site</a></li></ul>";
			foreach ($menus as $menuname => $menucontent) {
				print $menucontent;
			}
			?>
		</div>
	</div>
	
	<div id="status">
		<span class="message">
			A simple example of Allegory usage</span>
	</div>
	
	<div id="content">

	<div class="div_normal" style="width: 60%;">
	<?php if (!$_SERVER[PATH_INFO][1] and !$_GET[k] and !$_GET[display]) {
	echo "<h1>Articles from a specific category</h1>";
	}
	?>
	<?php
		include("config.php");
		include("documentation.php");
		if ($_GET[location]) {
			$location = $_GET[location];
			$item = $documentation[$location];
			echo "<h1>$item[title]</h1>\n<div>$item[content]</div>";
			}
		else {
			$cat = 3;
			$amount = 7;
			include("display_articles.php");
			}
		?>
	</div>
	<div class="div_extended">
	<h3>Latest articles</h3>
	<ul>
	<?php		
		$static = true;
		$amount = 10;
		$template = "2";
		include("display_articles.php");
		?>
	</ul>
	<h3>Documentation</h3>
	<ul>
	<?php
	foreach ($documentation as $key => $info) {
		echo "<li><a href=\"?display=documentation&amp;location=$key\">$info[title]</a></li>";
		}
	?> 
	</ul>
	<h3>Links</h3>
	<ul>
	<li><a href="<?=dirname($_SERVER[SCRIPT_NAME]);?>/index.php">Admin panel</a></li>
	</ul>
	<?php
		include("search.php");
	?>
	</div>

	</div>
	
	<div id="footer">
	<?=SCRIPT_TITLE;?> using <?php if(defined("KNIFESQL")) { echo "mysql"; } else { echo "flat (var_dump)"; } ?> backbone
	</div>
	
</div>
</div>
</body>
</html>