<?php

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


function msg_status($message) {
		echo $message;
		}
		
function sanitize_variables($variable) {
		
		$search = array(
				"\'",
				);
				
		$replace = array(
				"&#39;",
				);

		$variable = str_replace($search, $replace, $variable);
		return $variable;
}

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

#
# 	Bad dropdown
#
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

#
# 	Good dropdown
#

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


#
#	Function: 		formatsize
#	Description:	Properly formats the size of a file
#	Credit:			Flexer
#
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

#
#	knife Login function
#

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

function html2specialchars($str){
	$trans_table = array_flip(get_html_translation_table(HTML_SPECIALCHARS));
	return strtr($str, $trans_table);
	}

# http://no2.php.net/usort (todor at todor dot net)
# updated for int-assoc article arrays
function multi_sort($array,$key){
	$compare = create_function('$a,$b','if ($a["'.$key.'"] == $b["'.$key.'"]) {return 0;}else {return ($a["'.$key.'"] > $b["'.$key.'"]) ? -1 : 1;}');
	uasort($array,$compare) ;
	return $array ;
	}
?>
