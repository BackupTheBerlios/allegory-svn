<?php

/* Pure DATA storage-related functions */

function FileFolderList($path, $depth = 0, $current = '', $level=0, $ignore=FALSE) {
	if ($level==0 && !@file_exists($path))
		return false;
	if (is_dir($path)) {
		$handle = @opendir($path);
		if ($depth == 0 || $level < $depth)
			while($filename = @readdir($handle))
				if ($filename != '.' && $filename != '..')
					$current = @FileFolderList($path.'/'.$filename, $depth, $current, $level+1);
		@closedir($handle);
		$current[folder][] = $path.'/'.$filename;
	} else
		if (is_file($path))
			$current[file][] = $path;
	return $current;
}

function LoadArray($pathandfilename) {
	if (is_file($pathandfilename)) {
		@include($pathandfilename);
		return $array;
	}
	return array();
}

function WriteContents($contents,$filename) {
	if (file_exists($filename))
		if (!is_writable($filename))
			if (!chmod($filename, 0666))
				 return false;
	if (!$fp = @fopen($filename, 'wb+'))
		return false;
	if (@fwrite($fp, $contents) === false)
		return false;
	if (!@fclose($fp))
		return false;
	return true;
}

function GetContents($filename) {
	$file = @fopen($filename, 'rb');
	if ($file) {
		while (!feof($file)) $data .= fread($file, 1024);
		fclose($file);
	} else {
		return false;
	}
	return $data;
}

function SaveArray($array,$filename) {
	$contents = '<?php
$array = '. var_export($array,1) .';
?>';
	return WriteContents($contents, $filename);
}

/* Data Handling Classes */

class ArticleStorage {
	function ArticleStorage($plugin_name) {
		$this->name = $plugin_name;
		$this->all_settings = LoadArray(KNIFE_PATH.'/data/articles.php');
		$this->settings = $this->all_settings[$plugin_name];
	}

	function save() {
		$this->all_settings[$this->name] = $this->settings;
		return SaveArray($this->all_settings, KNIFE_PATH.'/data/articles.php');
	}

	function delete($article_id) {
		unset($this->settings['articles'][$article_id]);
		return $this->save();
	}
	
}

class CommentStorage {
	function CommentStorage($plugin_name) {
		$this->name = $plugin_name;
		$this->all_settings = LoadArray(KNIFE_PATH.'/data/comments.php');
		$this->settings = $this->all_settings[$plugin_name];
	}

	function save() {
		$this->all_settings[$this->name] = $this->settings;
		return SaveArray($this->all_settings, KNIFE_PATH.'/data/comments.php');
	}

	function delete($article_id, $comment_id) {
		unset($this->settings[$article_id][$comment_id]);
		return $this->save();
	}
	function deleteall($article_id) {
		unset($this->settings[$article_id]);
		return $this->save();
		}	

}

class SettingsStorage {
	function SettingsStorage($plugin_name) {
		$this->name = $plugin_name;
		$this->all_settings = LoadArray(KNIFE_PATH."/data/settings.php");
		$this->settings = $this->all_settings[$plugin_name];
	}

	function save() {
		$this->all_settings[$this->name] = $this->settings;
		return SaveArray($this->all_settings, KNIFE_PATH."/data/settings.php");
	}

	function delete($where, $id) {
		unset($this->settings[$where][$id]);
		return $this->save();
	}
}



/* Utility functions for admin interface */

function msg_status($message) {
		echo $message;
		}
		

/* Utility functions general*/	

/*
 *	Function: sanitize_variables("variable to replace, "template")
 *	by Øivind Hoel / eruin
 */
function sanitize_variables($variable, $template=false) {
		
		$search = array(
				"\'",
				"{",
				"}",
				"[",
				"]",
				);
				
		$replace = array(
				"&#39;",
				"&#123;",
				"&#125;",
				"&#91;",
				"&#93;",
				);

		$variable = str_replace($search, $replace, $variable);
		return $variable;
}

/*
 *	Function: urlTitle("title")
 *	by Wordpress team - expanded by Øivind Hoel
 */		
function urlTitle($title) {
	
    $title = strtolower($title);
    $title = str_replace("å", "aa", $title);
    $title = str_replace("ø", "o", $title);
    $title = str_replace("æ", "ae", $title);    
    $title = preg_replace('/&.+?;/', '', $title); // kill entities
    $title = preg_replace('/[^a-z0-9 _-]/', '', $title);
    $title = preg_replace('/\s+/', ' ', $title);
    $title = str_replace(' ', '-', $title);
    $title = preg_replace('|-+|', '-', $title);
    $title = trim($title, '-');

    return $title;
	}

/*
 *	Function: makeDropDown("array options", "name of select", "selected option")
 *	by Øivind Hoel / eruin
 */
function makeDropDown($options, $name, $selected="FALSE")
    {
		$output = "<select name=\"$name\">\r\n";

#	array_multisort($options, SORT_DESC, $options);
        foreach($options as $value => $description)
        {
          $output .= "<option value=\"$value\"";
          if($selected == $value){ $output .= " selected=\"selected\" "; }
          $output .= ">$description[name]</option>\n";
        }
		$output .= "</select>";
    	return $output;
    }

/*
 *	Function: htmldropdown("array options", "name of select", "selected option")
 *	
 *	by Øivind Hoel / eruin
 */
function htmldropdown($options, $name, $selected="FALSE")
    {
		$output = "<select size=1 name=\"$name\">\r\n";

	array_multisort($options, SORT_DESC, $options);
        foreach($options as $value=>$description)
        {
          $output .= "<option value=\"$value\"";
          if($selected == $value){ $output .= " selected "; }
          $output .= ">$description</option>\n";
        }
		$output .= "</select>";
    	return $output;
    }


/*
 *	Function: formatsize("file size in bits")
 *	Properly formats a filesize
 *	by Georgi Amarov
 */
function formatsize($file_size){
	if($file_size >= 1073741824)
		{$file_size = round($file_size / 1073741824 * 100) / 100 . "GB";}
    elseif($file_size >= 1048576)
    	{$file_size = round($file_size / 1048576 * 100) / 100 . "MB";}
    elseif($file_size >= 1024)
    	{$file_size = round($file_size / 1024 * 100) / 100 . "KB";}
    else{$file_size = $file_size . "b";}
return $file_size;
}

/*
 *	Function: i18n("string identifier", "multi variables")
 *	Displays internationalized messages. Takes an array or string of variables to put in the int. msg.
 *	by Øivind Hoel
 */
function i18n( $messageid, $variable=false ) {
	global $lang;
	
	if ($lang->$messageid) {
		$variable = explode(",", $variable);
		return vsprintf($lang->$messageid, $variable);
		}
	else {
		$return = 'i18nFIXME-'.$messageid;
		return $return;
		}
}

function available_languages($ffl) {
	$languages = $ffl[file];
	if (!empty($languages))
		foreach ($languages as $null => $languagefile) {
			if (stristr($pluginfile, ".htaccess")) { continue; }
			
			$language_data = GetContents($languagefile);
			preg_match("{Filename:(.*)}i", $language_data, $language[name]);
			preg_match("{Language National:(.*)}i", $language_data, $language[lang_nat]);
			preg_match("{Language International:(.*)}i", $language_data, $language[lang_int]);
			preg_match("{Author:(.*)}i", $language_data, $language[author]);
			preg_match("{Author URI:(.*)}i", $language_data, $language[author_uri]);
			preg_match("{Version:(.*)}i", $language_data, $language[version]);

			$available_languages[] = array(
				name		=> trim($language[name][1]),
				langnational => trim($language[lang_nat][1]),
				author		=> trim($language[author][1]),
				author_uri	=> trim($language[author_uri][1]),
				version		=> trim($language[version][1]),
				langinternational	=> trim($language[lang_int][1]),
				file		=> basename($languagefile),
			);
		}
	else
		$available_languages = array();
	return $available_languages;
}


function array_slice_key($array, $offset, $len=-1){

   if (!is_array($array))
       return FALSE;

   $length = $len >= 0? $len: count($array);
   $keys = array_slice(array_keys($array), $offset, $length);
   foreach($keys as $key) {
       $return[$key] = $array[$key];
   }
 
   return $return;
}

function randomquote() {
	$quotes = file(KNIFE_PATH.'/inc/quotes.txt');
	$max = count($quotes);
	$max--;
	$n = rand(0, $max);
	$quote = $quotes[$n];
	return $quote;
	}
	
function validate_ip($ip){
   $return = TRUE;
   $tmp = explode(".", $ip);
   if(count($tmp) < 4){
      $return = FALSE;
   } else {
      foreach($tmp AS $sub){
         if($return != FALSE){
            if(!eregi("^([0-9])", $sub)){
               $return = FALSE;
            } else {
               $return = TRUE;
            }
         }
      }
   }
   return $return;
}

/*
 *	Function: html2specialchars("string")
 *	For use when editing templates -> converts.
 *	from PHP manual contrib
 */
function html2specialchars($str){
	$trans_table = array_flip(get_html_translation_table(HTML_SPECIALCHARS));
	return strtr($str, $trans_table);
	}

/*
 *	Function: multi_sort("array data", "string key to sort by")
 *	Displays internationalized messages. Takes an array or string of variables to put in the int. msg.
 *	by http://no2.php.net/usort (todor at todor dot net) updated by Øivind Hoel for int-assoc article arrays
 */
function multi_sort($array,$key){
	$compare = create_function('$a,$b','if ($a["'.$key.'"] == $b["'.$key.'"]) {return 0;}else {return ($a["'.$key.'"] > $b["'.$key.'"]) ? -1 : 1;}');
	uasort($array,$compare) ;
	return $array ;
	}
	
	
		function createSelectBox($options, $id, $name, $selected) {
			$out = '<select name="'.$name.'" id="'.$id.'">';
			foreach ($options as $value => $description) {
				if ($value == $selected) {
					$out .= '<option value="'.$value.'" selected="selected">'.$description.'</option>';
					}
				else {
					$out .= '<option value="'.$value.'">'.$description.'</option>';
					}
				}
			$out .= '</select>';
			return $out;
			}
			
function easy_date_format_replace($template,$date,$adjust=false) {
	global $Settings;
	
	// Create a regular expression
	$find = '#\{date=(.*?)\}#i';
	
	// Find all occurences of the regular expression
	preg_match_all($find,$template,$matches,PREG_SET_ORDER);
	
	
	// If matches were found
	if (!empty($matches))
		// Loop through all the matches
		foreach ($matches as $null => $match)
			// Replaces current match with the correct date format
			$template = str_replace($match[0], date($match[1], $date + ($adjust*60)), $template);

	// return the newly formatted entry
	return $template;
}


# off bbpress, updated by me
function make_clickable($ret) {
	$ret = ' ' . $ret . ' ';
	$ret = preg_replace("#([\s>])(https?)://([^\s<>{}()]+[^\s.,<>{}()])#i", "$1<a href=\"$2://$3\">$2://$3</a>", $ret);
	$ret = preg_replace("#(\s)www\.([a-z0-9\-]+)\.([a-z0-9\-.\~]+)((?:/[^ <>{}()\n\r]*[^., <>{}()\n\r]?)?)#i", "$1<a href=\"http://www.$2.$3$4\">www.$2.$3$4</a>", $ret);
	$ret = preg_replace("#(\s)([a-z0-9\-_.]+)@([^,< \n\r]+)#i", "$1<a href=\"mailto:$2@$3\">$2@$3</a>", $ret);
	$ret = trim($ret);
	return $ret;
}

/*
 *	Form-creating functions
 *
 */
 
function makeField($type, $name, $id, $value, $label, $class=false, $pos=false) {
	if(!$class) { $class = "inshort"; }
	$label = "<label for=\"$id\">$label</label>";
		switch ($pos) {
		case false:
			$right = " " . $label;
			break 1;
		case "top":
			$top = $label . "<br />";
			break 1;
		case "bottom":
			$bottom = "<br />" . $label;
			break 1;
		case "left":
			$left = $label . " ";
			break 1;
			}
		
	return "
	<p>
		$top
		$left<input type=\"$type\" name=\"$name\" id=\"$id\" value=\"$value\" class=\"$class\" />$right
		$bottom
		</p>";
}

function formGroup($executes, $name) {
	$out = '<fieldset><legend>'.$name.'</legend>';
	if (is_array($executes)) {
		unset($executes[name]);
		foreach ($executes as $null => $exec) {
			$out .= $exec;
			}
		}
	else {
		$out .= $executes;
		}
	$out .= '</fieldset>';
	return $out;
	}



function radioGroup($values, $id, $name, $label, $current=false, $moreinfo=false) {
	$out = '<fieldset><legend>'.$label.'</legend><p>';
	if ($moreinfo) { $out .= '<p>'.$moreinfo.'</p>'; }
	foreach ($values as $value => $description) {
		if (isset($current) and ($current == $value)) {
			$out .= '<input id="'.$name . $value.'" type="radio" name="'.$name.'" value="'.$value.'" checked="checked" />
					<label for="'.$name . $value.'">'.$description.'</label><br />';
			}
		else {
			$out .= '<input id="'.$name . $value.'" type="radio" name="'.$name.'" value="'.$value.'" />
					<label for="'.$name . $value.'">'.$description.'</label><br />';
			}
		}
	$out .= '</p></fieldset>';
	return $out;
}

?>
