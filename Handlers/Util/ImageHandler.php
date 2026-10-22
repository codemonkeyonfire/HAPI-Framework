<?php
/**
 * Database Handler
* can only implement drivers
* can only use other handlers if they are passed by reference
* Do not reference Globals except for lib paths (explicitly pass values to functions)
* can implement base class if and only if base class used by more than one handlers
*
* @author
* @version 1.0
* @package insideArtCenter
* @subpackage common
* @date 6/14/12
*/
namespace Handlers\Util;

define("IPTC_OBJECT_NAME", "005");
define("IPTC_EDIT_STATUS", "007");
define("IPTC_PRIORITY", "010");
define("IPTC_CATEGORY", "015");
define("IPTC_SUPPLEMENTAL_CATEGORY", "020");
define("IPTC_FIXTURE_IDENTIFIER", "022");
define("IPTC_KEYWORDS", "025");
define("IPTC_RELEASE_DATE", "030");
define("IPTC_RELEASE_TIME", "035");
define("IPTC_SPECIAL_INSTRUCTIONS", "040");
define("IPTC_REFERENCE_SERVICE", "045");
define("IPTC_REFERENCE_DATE", "047");
define("IPTC_REFERENCE_NUMBER", "050");
define("IPTC_CREATED_DATE", "055");
define("IPTC_CREATED_TIME", "060");
define("IPTC_ORIGINATING_PROGRAM", "065");
define("IPTC_PROGRAM_VERSION", "070");
define("IPTC_OBJECT_CYCLE", "075");
define("IPTC_BYLINE", "080");
define("IPTC_BYLINE_TITLE", "085");
define("IPTC_CITY", "090");
define("IPTC_PROVINCE_STATE", "095");
define("IPTC_COUNTRY_CODE", "100");
define("IPTC_COUNTRY", "101");
define("IPTC_ORIGINAL_TRANSMISSION_REFERENCE", "103");
define("IPTC_HEADLINE", "105");
define("IPTC_CREDIT", "110");
define("IPTC_SOURCE", "115");
define("IPTC_COPYRIGHT_STRING", "116");
define("IPTC_CAPTION", "120");
define("IPTC_LOCAL_CAPTION", "121");

class ImageHandler
{
	private $meta = array();
	private $file = null;
	private $imageBuffer;
	private $imageWidth;
	private $imageHeight;
	private $imageMime;
	private $imageType;
	private $imageAttr;

	public $hasMetaData=false;

	function __construct($FILENAME="")
	{
		$this->file = $FILENAME;
		 
		if($FILENAME != "")
			$this->load_image($FILENAME);

		if($this->imageType == IMAGETYPE_JPEG)
			$this->load_image_meta();
	}

	public function load_image_meta($FILENAME="")
	{
		if($FILENAME != "")
			$this->file = $FILENAME;
			 
			$info = null;
			$size = getimagesize($this->file, $info);

			if(isset($info["APP13"]))
			{
				$this->hasMetaData = true;
				$this->meta = iptcparse($info["APP13"]);
			}
	}

	public function get_meta($TAG)
	{
		return isset($this->meta["2#$TAG"]) ? $this->meta["2#$TAG"][0] : "";
	}

	public function set_meta($TAG, $DATA)
	{
		$this->meta["2#$TAG"] = array($DATA);
		//$this->write_meta();
	}

	public function write_meta()
	{
		$mode = 0;
		$content = iptcembed($this->binary(), $this->file, $mode);
		$filename = $this->file;
		 
		if(file_exists($this->file))
			unlink($this->file);

			$fp = fopen($this->file, "w");
			fwrite($fp, $content);
			fclose($fp);
	}

	private function binary()
	{
		$data = "";
		 
		foreach(array_keys($this->meta) as $key)
		{
			$tag = str_replace("2#", "", $key);
			$data .= $this->make_tag(2, $tag, $this->meta[$key][0]);
		}
		 
		return $data;
	}

	private function make_tag($REC, $DATA, $VALUE)
	{
		$length = strlen($VALUE);
		$retVal = chr(0x1C) . chr($REC) . chr($DATA);
		 
		if($length < 0x8000)
		{
			$retVal .= chr($length >> 8) . chr($length & 0xFF);
		}else{
			$retVal .= chr(0x80) .
			chr(0x04) .
			chr(($length >> 24) & 0xFF) .
			chr(($length >> 16) & 0xFF) .
			chr(($length >> 8) & 0xFF) .
			chr($length & 0xFF);
		}
		 
		return $retVal . $VALUE;
	}

	public function dump_meta_data()
	{
		echo "<pre>".print_r($this->meta, true)."</pre>";
	}

	public function remove_all_meta()
	{
		$this->meta = array();
		 
		$img = imagecreatefromstring(implode(file($this->file)));
		 
		if(file_exists($this->file))
			unlink($this->file);
			 
			imagejpeg($img, $this->file, 100);
	}

	public function load_image($FILE_PATH)
	{
		$buffer = getimagesize($FILE_PATH);
		$this->imageWidth = $buffer[0];
		$this->imageHeight = $buffer[1];
		$this->imageType = $buffer[2];
		$this->imageAttr = $buffer[3];
		$this->imageMime = $buffer["mime"];
		//die("<pre>".print_r($buffer, true)."</pre>");
		$this->imageBuffer = $this->fetch_image($FILE_PATH, $buffer);
		$this->file = $FILE_PATH;
	}

	public function fetch_image($FILE_PATH, $F_INFO="")
	{
		if(empty($F_INFO))
			$F_INFO = getimagesize($FILE_PATH);
			 
			switch($F_INFO[2])
			{
				case IMAGETYPE_PNG:
					$imageBuffer = @imagecreatefrompng($FILE_PATH);
					break;
				case IMAGETYPE_GIF:
					$imageBuffer = @imagecreatefromgif($FILE_PATH);
					break;
				case IMAGETYPE_BMP:
					$imageBuffer = $this->imagecreatefrombmp($FILE_PATH);
					break;
				case IMAGETYPE_JPEG:
					$imageBuffer = @imagecreatefromjpeg($FILE_PATH);
					break;
			}
	
			if(!$imageBuffer){
				/* Create a black image */
				$imageBuffer  = imagecreatetruecolor(150, 30);
				$bgc = imagecolorallocate($imageBuffer, 255, 255, 255);
				$tc  = imagecolorallocate($imageBuffer, 0, 0, 0);
				imagefilledrectangle($imageBuffer, 0, 0, 150, 30, $bgc);
			
				/* Output an error message */
				imagestring($imageBuffer, 1, 5, 5, 'Error loading ' . $FILE_PATH, $tc);
			}
			
			return $imageBuffer;
	}

	public function render_image(&$IMAGE_BUFFER=NULL, $NEW_PATH=NULL, $IMAGE_TYPE="", $QUALITY=80)
	{
		$map = array(IMAGETYPE_PNG => "png", IMAGETYPE_GIF => "gif", IMAGETYPE_JPEG => "jpg");
		 
		if(empty($IMAGE_BUFFER))
			$IMAGE_BUFFER = &$this->imageBuffer;
			 
			if($IMAGE_TYPE == "")
				$IMAGE_TYPE = $map[$this->imageType];
				 
				switch($IMAGE_TYPE)
				{
					case "png":
						if(!empty($QUALITY)) $QUALITY = round(10 - ($QUALITY / 10)); 		// is compression level for PNGs
						$result = imagepng($IMAGE_BUFFER, $NEW_PATH, $QUALITY);
						break;
					case "jpg":
						$result = imagejpeg($IMAGE_BUFFER, $NEW_PATH, $QUALITY);
						break;
					case "gif":
						$result = imagegif($IMAGE_BUFFER, $NEW_PATH);
						break;
				}
				 
				return $result;
	}

	public function resize($NEW_WIDTH, $NEW_HEIGHT)
	{
		$old_x          =   $this->imageWidth;
		$old_y          =   $this->imageHeight;

		if($old_x > $old_y)
		{
			$NEW_HEIGHT    =   $NEW_WIDTH * ($old_y/$old_x);
		}

		if($old_x < $old_y)
		{
			$NEW_WIDTH    =   $NEW_HEIGHT * ($old_x/$old_y);
		}

		$x_mid = $NEW_WIDTH/2;  //horizontal middle
		$y_mid = $NEW_HEIGHT/2; //vertical middle

		$new_img        =   ImageCreateTrueColor($NEW_WIDTH, $NEW_HEIGHT);

		imagecopyresampled($new_img, $this->imageBuffer, 0, 0, 0, 0, $NEW_WIDTH, $NEW_HEIGHT, $old_x, $old_y);
		 
		imagedestroy( $this->imageBuffer);
		$this->imageBuffer = $new_img;
	}

	public function create_thumbnail($THUMBNAIL_PATH, $NEW_WIDTH, $NEW_HEIGHT, $CROP=false)
	{
		$old_x          =   $this->imageWidth;
		$old_y          =   $this->imageHeight;

		// resize 		########################################################################################################
		if($old_x < $old_y)
		{
			$thumb_w    =   $NEW_WIDTH;
			$thumb_h    =   $NEW_WIDTH*($old_y/$old_x);
		}
	  

		if($old_x > $old_y)
		{
			$thumb_w    =   $NEW_HEIGHT*($old_x/$old_y);
			$thumb_h    =   $NEW_HEIGHT;
		}

		if($old_x == $old_y)
		{
			$thumb_w    =   $NEW_WIDTH;
			$thumb_h    =   $NEW_HEIGHT;
		}
	  
		$resize_img        =   ImageCreateTrueColor($thumb_w, $thumb_h);
		imagecopyresampled($resize_img, $this->imageBuffer, 0, 0, 0, 0, $thumb_w, $thumb_h, $old_x, $old_y);
	  
		// crop 		########################################################################################################
		if($CROP)
		{
			$srcY = 0;
			$srcX = 0;
			$srcH = $NEW_HEIGHT;
			$srcW = $NEW_WIDTH;
			 
			if($thumb_h > $NEW_HEIGHT)
			{
				$srcY = ($thumb_h - $NEW_HEIGHT) / 2;
			}
			 
			if($thumb_w > $NEW_WIDTH)
			{
				$srcX = ($thumb_w - $NEW_HEIGHT) / 2;
			}
			 
			$crop_img  =   ImageCreateTrueColor($NEW_WIDTH, $NEW_HEIGHT);
			imagecopyresampled($crop_img, $resize_img, 0, 0, $srcX, $srcY, $NEW_WIDTH, $NEW_HEIGHT, $srcW, $srcH);
		}
	  
		// finish 		########################################################################################################

		if(!empty($THUMBNAIL_PATH))
		{
			if($CROP)
			{
				$result = $this->render_image($crop_img, $THUMBNAIL_PATH, "", 80);
				imagedestroy($crop_img);
			}else{
				$result = $this->render_image($resize_img, $THUMBNAIL_PATH, "", 80);
			}
	   
			imagedestroy($resize_img);
			return $result;
		}else{
			if($CROP)
				return $crop_img;
				else
					return $resize_img;
		}
	}

	function create_jpg_thumbnail($THUMBNAIL_PATH, $NEW_WIDTH, $NEW_HEIGHT, $CROP=false)
	{

		$old_x          =   $this->imageWidth;
		$old_y          =   $this->imageHeight;

		// resize 		########################################################################################################
		if($old_x < $old_y)
		{
			$thumb_w    =   $NEW_WIDTH;
			$thumb_h    =   $NEW_WIDTH * ($old_y/$old_x);
		}

		if($old_x > $old_y)
		{
			$thumb_w    =   $NEW_HEIGHT*($old_x/$old_y);
			$thumb_h    =   $NEW_HEIGHT;
		}

		if($old_x == $old_y)
		{
			$thumb_w    =   $NEW_WIDTH;
			$thumb_h    =   $NEW_HEIGHT;
		}

		$resize_img        =   ImageCreateTrueColor($thumb_w, $thumb_h);
		imagecopyresampled($resize_img, $this->imageBuffer, 0, 0, 0, 0, $thumb_w, $thumb_h, $old_x, $old_y);

		// crop 		########################################################################################################
		if($CROP)
		{
			$srcY = 0;
			$srcX = 0;
			$srcH = $NEW_HEIGHT;
			$srcW = $NEW_WIDTH;

			if($thumb_h > $NEW_HEIGHT)
			{
				$srcY = ($thumb_h - $NEW_HEIGHT) / 2;
			}

			if($thumb_w > $NEW_WIDTH)
			{
				$srcX = ($thumb_w - $NEW_HEIGHT) / 2;
			}

			$crop_img  =   ImageCreateTrueColor($NEW_WIDTH, $NEW_HEIGHT);
			imagecopyresampled($crop_img, $resize_img, 0, 0, $srcX, $srcY, $NEW_WIDTH, $NEW_HEIGHT, $srcW, $srcH);
		}

		if(!empty($THUMBNAIL_PATH))
		{
			if($CROP)
			{
				$result = $this->render_image($crop_img, $THUMBNAIL_PATH, "jpg", 70);
				imagedestroy($crop_img);
			}else{
				$result = $this->render_image($resize_img, $THUMBNAIL_PATH, "jpg", 70);
			}
				
			imagedestroy($resize_img);
			return $result;

		}else{
			if($CROP)
				return $crop_img;
				else
					return $resize_img;
		}
	}

	public function add_alpha_mask($MASK_PATH)
	{
		// Get sizes and set up new picture
		$newPicture = imagecreatetruecolor( $this->imageWidth, $this->imageHeight );
		imagesavealpha( $newPicture, true );
		imagefill( $newPicture, 0, 0, imagecolorallocatealpha( $newPicture, 0, 0, 0, 127 ) );

		$maskBuffer = $this->fetch_image($MASK_PATH);
		// Resize mask if necessary
		if( $this->imageWidth != imagesx( $maskBuffer ) || $this->imageHeight != imagesy( $maskBuffer ) ) {
			$tempPic = imagecreatetruecolor( $this->imageWidth, $this->imageHeight );
			imagecopyresampled( $tempPic, $maskBuffer, 0, 0, 0, 0, $this->imageWidth, $this->imageHeight, imagesx( $maskBuffer ), imagesy( $maskBuffer ) );
			imagedestroy( $maskBuffer );
			$maskBuffer = $tempPic;
		}

		// Perform pixel-based alpha map application
		for( $x = 0; $x < $this->imageWidth; $x++ ) {
			for ($y = 0; $y < $this->imageHeight; $y++) {
				$alpha = imagecolorsforindex($maskBuffer, imagecolorat($maskBuffer, $x, $y));
				$alpha = 127 - floor($alpha['red'] / 2);
				if (127 == $alpha) {
					continue;
				}
				$color = imagecolorsforindex($this->imageBuffer, imagecolorat($this->imageBuffer, $x, $y));
				imagesetpixel($newPicture, $x, $y, imagecolorallocatealpha(	$newPicture, $color['red'], $color['green'], $color['blue'], $alpha));
			}
		}
	  
		imagedestroy( $this->imageBuffer);
		$this->imageBuffer = $newPicture;
	}

	function imagecreatefrombmp( $FILE_PATH )
	{
		$file = fopen( $filename, "rb" );
		$read = fread( $file, 10 );
		while( !feof( $file ) && $read != "" )
		{
			$read .= fread( $file, 1024 );
		}
		$temp = unpack( "H*", $read );
		$hex = $temp[1];
		$header = substr( $hex, 0, 104 );
		$body = str_split( substr( $hex, 108 ), 6 );
		if( substr( $header, 0, 4 ) == "424d" )
		{
			$header = substr( $header, 4 );
			// Remove some stuff?
			$header = substr( $header, 32 );
			// Get the width
			$width = hexdec( substr( $header, 0, 2 ) );
			// Remove some stuff?
			$header = substr( $header, 8 );
			// Get the height
			$height = hexdec( substr( $header, 0, 2 ) );
			unset( $header );
		}
		$x = 0;
		$y = 1;
		$image = imagecreatetruecolor( $width, $height );
		foreach( $body as $rgb )
		{
			$r = hexdec( substr( $rgb, 4, 2 ) );
			$g = hexdec( substr( $rgb, 2, 2 ) );
			$b = hexdec( substr( $rgb, 0, 2 ) );
			$color = imagecolorallocate( $image, $r, $g, $b );
			imagesetpixel( $image, $x, $height-$y, $color );
			$x++;
			if( $x >= $width )
			{
				$x = 0;
				$y++;
			}
		}
		return $image;
	}

	public function Get_Width()
	{
		return $this->imageWidth;
	}

	public function Get_Height()
	{
		return $this->imageHeight;
	}

	public function Get_Type()
	{
		return $this->imageType;
	}

	public function Get_Mime()
	{
		return $this->imageMime;
	}

	public function Get_HTML_Attr()
	{
		return $this->imageAttr;
	}

}