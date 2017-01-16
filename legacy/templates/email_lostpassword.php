<?php if(!defined('IN_PHPVMS') && IN_PHPVMS !== true) { die(); } ?>
Dear <?php echo $firstname .' '. $lastname; ?>,

Your password was reset, it is: <?php echo $newpw?>

You can login with this new password and change it.

Thanks!
<?php echo SITE_NAME?> Staff