<?php if(!defined('IN_PHPVMS') && IN_PHPVMS !== true) { die(); } ?>
Dear <?php echo $pilot->firstname.' '.$pilot->lastname?>,
Your registration for <?php echo SITE_NAME; ?> was denied. Please contact an admin at <a href="<?php echo url('/');?>"><?php echo url('/');?></a> to dispute this. 
				
Thanks!
<?php echo SITE_NAME; ?> Staff