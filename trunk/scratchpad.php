Default release should contain:

	Default category (id = 0)	- undeletable ("general"?)
	Default template (id = 0)	- undeletable 
	
	
New name: "Allegori"


Features

	Config:
		Txp:
			Comments disabled after set time
			Preview
				Box where you can define some css rules?
				
			
	{latestcommentby}
			
	Photoblogging capability
			(exif support)
			
	display_articles needs a wrapper that can destroy variables and do basic config
	helper tools ala plog
	restrict users to certain categories
	gravatars
	favatars
	comment-user avatars
			
			
			
			
			







tables:

1 article table
1 comment table

	? 1 templates table
	? 1 users table
	- OR we could just use flat files here since they should be fast enough for anyone




































#
#	Image stuff:
#

$image = exif_thumbnail("image.jpg", $width, $height, $type);
if ($image!==false) {
   header('Content-type: ' .image_type_to_mime_type($type));
   echo $image;
   }
   
   
	$exifdata = exif_read_data("image.jpg", "IFD0, EXIF");
	
	$img_data = array(
	
	"Make" => $exifdata[Make],
	"Model" => $exifdata[Model],
	"Exposure" => $exifdata[ExposureTime],
	"ISO" => $exifdata[ISOSpeedRatings],
	"Flash" => $exifdata[Flash],
	"Aperture" => $exifdata[COMPUTED][ApertureFNumber],
	"Blackorwhite" => $exifdata[COMPUTED][IsColor],
	"Height" => $exifdata[COMPUTED][Height],
	"Width" => $exifdata[COMPUTED][Width],
	"Size" => formatsize($exifdata[FileSize]),
	);
	
	foreach ($img_data as $d => $i) {
		echo "$d: $i<br />";
		}