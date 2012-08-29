<?php /** * @package Spider Catalog * @author Web-Dorado * @copyright (C) 2012 Web-Dorado. All rights reserved. * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html **/ // This php file returnes image in image/jpeg format therefore direct access must be allowed
   header('Content-Type: image/jpeg');
   class SimpleImage {     var $image;   var $image_type;    function load($filename) {      $image_info = getimagesize($filename);      $this->image_type = $image_info[2];      if( $this->image_type == IMAGETYPE_JPEG ) {         $this->image = imagecreatefromjpeg($filename);      } elseif( $this->image_type == IMAGETYPE_GIF ) {         $this->image = imagecreatefromgif($filename);      } elseif( $this->image_type == IMAGETYPE_PNG ) {         $this->image = imagecreatefrompng($filename);      }   }   function save($filename, $image_type=IMAGETYPE_JPEG, $compression=75, $permissions=null) {      if( $image_type == IMAGETYPE_JPEG ) {         imagejpeg($this->image,$filename,$compression);      } elseif( $image_type == IMAGETYPE_GIF ) {         imagegif($this->image,$filename);               } elseif( $image_type == IMAGETYPE_PNG ) {         imagepng($this->image,$filename);      }         if( $permissions != null) {         chmod($filename,$permissions);      }   }   function output($image_type=IMAGETYPE_JPEG) {      if( $image_type == IMAGETYPE_JPEG ) {         imagejpeg($this->image);      } elseif( $image_type == IMAGETYPE_GIF ) {         imagegif($this->image);               } elseif( $image_type == IMAGETYPE_PNG ) {         imagepng($this->image);      }      }   function getWidth() {      return imagesx($this->image);   }   function getHeight() {      return imagesy($this->image);   }   function resizeToHeight($height) {      $ratio = $height / $this->getHeight();      $width = $this->getWidth() * $ratio;      $this->resize($width,$height);   }   function resizeToWidth($width) {      $ratio = $width / $this->getWidth();      $height = $this->getheight() * $ratio;      $this->resize($width,$height);   }   function scale($scale) {      $width = $this->getWidth() * $scale/100;      $height = $this->getheight() * $scale/100;       $this->resize($width,$height);   }   function resize($width,$height) {      $new_image = imagecreatetruecolor($width, $height);      imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());      $this->image = $new_image;      }      }
   $image = new SimpleImage();
   $image->load($_GET['url']);
   if(isset($_GET['width']) and isset($_GET['height']))
   	{
   		if( $_GET['width'] / $image->getWidth() < $_GET['height'] / $image->getHeight())
			{			if(isset($_GET['reverse']) and $_GET['reverse']==1)   				$image->resizeToHeight($_GET['height']);			else
   				$image->resizeToWidth($_GET['width']);
			}
		else
			{			if(isset($_GET['reverse']) and $_GET['reverse']==1)   				$image->resizeToWidth($_GET['width']);			else
   				$image->resizeToHeight($_GET['height']);			
			}	
   	}
	else
	{
		if(isset($_GET['width']))
			{
   				$image->resizeToWidth($_GET['width']);
			}
		else
			{
   				$image->resizeToHeight($_GET['height']);			
			}	
	}
   $image->output();
?>