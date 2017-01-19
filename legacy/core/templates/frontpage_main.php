<?php if(!defined('IN_PHPVMS') && IN_PHPVMS !== true) { die(); } ?>
<div id="mainbox">
<?php

	// Show the News module, call the function ShowNewsFront
	//	This is in the modules/Frontpage folder
	
	MainController::Run('News', 'ShowNewsFront', 5);
    
    
    // Show the activity feed
    MainController::Run('Activity', 'Frontpage', 20);
?>
</div>
<div id="sidebar">
	<h3>Recent Reports</h3>
	
	<?php MainController::Run('PIREPS', 'RecentFrontPage', 5); ?>

	<h3>Newest Pilots</h3>
	
	<?php MainController::Run('Pilots', 'RecentFrontPage', 5); ?>
	
	<h3>Users Online</h3>
	<p><i>There have been <?php echo count($usersonline)?> user(s), and <?php echo count($guestsonline);?> guest(s) online in the past <?php echo Config::Get('USERS_ONLINE_TIME')?> minutes.</i></p>
	
	<?php
	/* $usersonline also has the list of users -
		really simple example
		
		Or if you're not on the frontpage:
		$usersonline = StatsData::UsersOnline();
		
	
	foreach($usersonline as $pilot)	
	{
		echo "{$pilot->firstname} {$pilot->lastname}<br />";
	}
	*/
	?>
	
</div>