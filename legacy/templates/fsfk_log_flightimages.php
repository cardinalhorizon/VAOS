<?php if(!defined('IN_PHPVMS') && IN_PHPVMS !== true) { die(); } ?>
<div class="fsfk_section_title">Flight Images</div>
<?php
/*
	$images contains an array with all of the images
		which were sent to this site
		
	Basically we are doing a loop and outputting an image tag 
		with each image which is there...
*/

foreach($images as $type => $image)
{
	
	/* $type contains the image type, which is one of:
	
		FlightMap
		FlightMapWeather
		FlightMapTaxiOut
		FlightMapTaxiIn
		FlightMapVerticalProfile
		FlightMapLandingProfile
		
		Right now $image is just the name of the image file, 
		we have to provide it with the path to the image file, which 
		is in out FSFK_IMAGE_PATH setting (which is a directory from
		the base URL, default is /lib/fsfk). Then we pass it to the
		fileurl() function which will return the full URL to it
	*/
	
	
	/* Here, just did some titles which will show up above the image */
	$type = strtolower($type);
	if($type == 'flightmap')
	{
		$title = 'Route Map';
	}
	elseif($type == 'flightmapweather')
	{
		$title = 'Route Weather';
	}
	elseif($type == 'flightmaptaxiout')
	{
		$title = 'Taxi Out Path';
	}
	elseif($type == 'flightmaptaxiin')
	{
		$title = 'Taxi In Path';
	}
	elseif($type == 'flightmapverticalprofile')
	{
		$title = ' Vertical Profile';
	}
	elseif($type == 'flightmaplandingprofile')
	{
		$title = 'Landing Profile';
	}
	
		
?>
	<strong><?php echo $title ?></strong><br />
	<img src="<?php echo fileurl(Config::Get('FSFK_IMAGE_PATH')).'/'.$image; ?>" alt="<?php echo $type;?>" />
	<br />
<?php
}