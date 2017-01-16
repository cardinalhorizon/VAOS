<?php if(!defined('IN_PHPVMS') && IN_PHPVMS !== true) { die(); } ?>
<h3>Pilot Badge</h3>
<p align="center">
	<img src="<?php echo $badge_url ?>" />
</p>
<p>
	<strong>Direct Link:</strong>
	<input onclick="this.select()" type="text" value="<?php echo $badge_url ?>" style="width: 100%" />
	<br /><br />
	<strong>Image Link:</strong>
	<input onclick="this.select()" type="text" value='<img src="<?php echo $badge_url ?>" />' style="width: 100%" />
	<strong>BBCode:</strong>
	<input onclick="this.select()" type="text" value='[img]<?php echo $badge_url ?>[/img]' style="width: 100%" />
</p>