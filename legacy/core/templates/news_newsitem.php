<?php if(!defined('IN_PHPVMS') && IN_PHPVMS !== true) { die(); } ?>
<h3><?php echo $subject;?></strong></h3>
<p>Posted by <?php echo $postedby;?> on <?php echo $postdate;?></p>
<p><?php echo html_entity_decode($body);?></p>
<hr>