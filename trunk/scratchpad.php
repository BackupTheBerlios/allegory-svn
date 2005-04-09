General:
========

Default release should contain:

	Default category (id = 0)	- undeletable ("general"?)
	Default template (id = 0)	- undeletable 
	
	
New name: "Allegori"

Feature hightlights:
====================

	- Dual backend.
		* mySQL and var_dump (flat files)
	- Write / Edit / Delete articles
	- Comment articles
		* Ability to require email
		* Gravatar (http://gravatar.com) support
	- Standards-compliant interface and output	
	- Search articles
		* Results ranked by relevance
		* Strict match for all words

Feature wishlist:
=================


	- Post status
		* Published
			- Lifetime
				* Start date
				* End date
		* Draft
		* Private
		
		- Should have icons in edit list
		
	- Comments can be disabled after set time
	- Comment and article preview
		* Preview can be forced
	- Photoblogging capability w/exif support
	- Display_articles needs a wrapper that can destroy variables and do basic config
	- Utility page
		* Import AJ-Fork to Allegory
		* Import Cutenews to Allegory
		* Import Wordpress to Allegory
		* Reset everything to defaults
	
	- RSS Feeds
		* For every category
		
	- Restrict users to certain categories
#	- Gravatars
	- Favatars
#	- Typekey support
	- Let users register themselves
	- Fetch IP when adding a comment




Data structure:
===============

	Articles => 1 Table or File	(inc/articles.php)
	Comments => 1 Table or File	(inc/comments.php)
	Settings => 1 File			(inc/settings.php)
		- Users
		- Templates
		- Categories
		- Configuration



























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