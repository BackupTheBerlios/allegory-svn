<?php


// If you have changed the ID's on your form field elements
// You should make them match here

$commentFrom_commentID = 'commentcomment';
$commentFrom_authorID  = 'commentname';
$commentFrom_urlID     = 'commenturl';


// You shouldn't need to edit anything else.

$livePreviewDivAdded == false;

if( stristr($_SERVER['REQUEST_URI'], 'commentPreview.js') ) {
	header('Content-type: text/javascript');
	?>
function initLivePreview() {
	if(!document.getElementById) return false;	

	var commentArea = document.getElementById('<?php echo $commentFrom_commentID ?>');
	
	if ( commentArea ) {
		commentArea.onkeyup = function(){
			var commentString = this.value;
			<!--commentString = wpautop(wptexturize(commentString));-->
			commentString = Markdown(commentString);
			if(document.getElementById('<?php echo $commentFrom_authorID ?>')) {
				var pnme = document.getElementById('<?php echo $commentFrom_authorID ?>').value;
			}
			
			if(document.getElementById('<?php echo $commentFrom_urlID ?>')) {
				var purl = document.getElementById('<?php echo $commentFrom_urlID ?>').value;
			}
			
			var fullText = '<p><h1><?php echo $Preview; ?></h1></p><p>' + commentString + '</p>';
			document.getElementById('commentPreview').innerHTML = fullText;
			
		}	
	}
}

//========================================================
// Event Listener by Scott Andrew - http://scottandrew.com
// edited by Mark Wubben, <useCapture> is now set to false
//========================================================
function addEvent(obj, evType, fn){
	if(obj.addEventListener){
		obj.addEventListener(evType, fn, false); 
		return true;
	} else if (obj.attachEvent){
		var r = obj.attachEvent('on'+evType, fn);
		return r;
	} else {
		return false;
	}
}

addEvent(window, "load", initLivePreview);

<?php die(); }


function markdown_add_preview_div($post_id='') {
	global $commentFrom_commentID, $livePreviewDivAdded;
	if($livePreviewDivAdded == false) {
		echo $before.'<div id="commentPreview"></div>'.$after;
		$livePreviewDivAdded = true;
	}
	return $post_id;
}

function live_preview($before='', $after='') {
	global $livePreviewDivAdded;
	if($livePreviewDivAdded == false) {
		echo $before.'<div id="commentPreview"></div>'.$after;
		$livePreviewDivAdded = true;
	}
}

function markdown_javascript($wherewewanttogo="booly") {
	echo('<script src="'.$wherewewanttogo.'/inc/markdown.js" type="text/javascript"></script>');
	echo('<script src="'.$wherewewanttogo.'/plugins/live-comment-preview.php/commentPreview.js" type="text/javascript"></script>');
}

?>