<?php if(!defined('IN_PHPVMS') && IN_PHPVMS !== true) { die(); } ?>
Hi <?php echo $pilot->firstname.' '.$pilot->lastname?>,

You have been marked as retired because you have been inactive for more than <?php echo Config::Get('PILOT_INACTIVE_TIME')?> days. To be un-retired, you must file a PIREP.


Thanks,
The <?php echo SITE_NAME; ?> Management