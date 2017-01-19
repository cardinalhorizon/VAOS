<?php if(!defined('IN_PHPVMS') && IN_PHPVMS !== true) { die(); } ?>
[Server]
Address = <?php echo SITE_URL?>/action.php/acars/xacars/acars

PIREP = <?php echo SITE_URL?>/action.php/acars/xacars/pirep

FlightInfo = <?php echo SITE_URL?>/action.php/acars/xacars/data

User = <?php echo $pilotcode?>

[ACARS]
POSReportTime = 1
EnableLiveACARS = 1
EnablePIREP = 1
AutoPIREP = 1