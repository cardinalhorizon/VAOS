<?php if(!defined('IN_PHPVMS') && IN_PHPVMS !== true) { die(); } ?>
<h3>Your Stats</h3>

<?php
/*
	Added in 2.0!
*/
$chart_width = '800';
$chart_height = '250';

/* Don't need to change anything below this here */
?>
<div align="center" style="width: 100%;">
	<div align="center" id="months_data"></div>
</div>
<br />
<div align="center" style="width: 100%;">
	<div align="center" id="aircraft_data"></div>
</div>

<script type="text/javascript" src="<?php echo fileurl('/lib/js/ofc/js/swfobject.js')?>"></script>
<script type="text/javascript">
swfobject.embedSWF("<?php echo fileurl('/lib/js/ofc/open-flash-chart.swf');?>", 
	"months_data", "<?php echo $chart_width;?>", "<?php echo $chart_height;?>", 
	"9.0.0", "expressInstall.swf", 
	{"data-file":"<?php echo actionurl('/pilots/statsmonthsdata/'.$pilot->pilotid);?>"});
	
	
<?php
$chart_width = '800';
$chart_height = '300';

/* Don't need to change anything below this here */
?>
swfobject.embedSWF("<?php echo fileurl('/lib/js/ofc/open-flash-chart.swf');?>", 
	"aircraft_data", "<?php echo $chart_width;?>", "<?php echo $chart_height;?>", 
	"9.0.0", "expressInstall.swf", 
	{"data-file":"<?php echo actionurl('/pilots/statsaircraftdata/'.$pilot->pilotid);?>"});
</script>