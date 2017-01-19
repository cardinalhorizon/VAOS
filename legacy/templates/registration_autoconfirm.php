<?php if(!defined('IN_PHPVMS') && IN_PHPVMS !== true) { die(); } ?>
<h3>Registration Confirmation</h3>
<p>Thanks for joining us! Your registration has been completed! You can login using your pilot ID (<?php echo PilotData::GetPilotCode($pilot->code, $pilot->pilotid);?>), and the password you used during registration.</p>