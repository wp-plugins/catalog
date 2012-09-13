<?php
   header('Content-Type: image/jpeg');
   
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