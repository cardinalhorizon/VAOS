<?php if(!defined('IN_PHPVMS') && IN_PHPVMS !== true) { die(); } ?>
<h3>Schedule Details</h3>
<div class="indent">
<strong>Flight Number: </strong> <?php echo $schedule->code.$schedule->flightnum ?><br />
<strong>Departure: </strong><?php echo $schedule->depname ?> (<?php echo $schedule->depicao ?>) at <?php echo $schedule->deptime ?><br />
<strong>Arrival: </strong><?php echo $schedule->arrname ?> (<?php echo $schedule->arricao ?>) at <?php echo $schedule->arrtime ?><br />
<?php
if($schedule->route!='')
{ ?>
<strong>Route: </strong><?php echo $schedule->route ?><br />
<?php
}?>
<br />
<strong>Weather Information</strong>
<div id="<?php echo $schedule->depicao ?>" class="metar">Getting current METAR information for <?php echo $schedule->depicao ?></div>
<div id="<?php echo $schedule->arricao ?>" class="metar">Getting current METAR information for <?php echo $schedule->arricao ?></div>
<br />
<strong>Schedule Frequency</strong>
<div align="center">
<?php
/*
	Added in 2.0!
*/
$chart_width = '800';
$chart_height = '170';

/* Don't need to change anything below this here */
?>
<div align="center" style="width: 100%;">
	<div align="center" id="pireps_chart"></div>
</div>

<script type="text/javascript" src="<?php echo fileurl('/lib/js/ofc/js/swfobject.js')?>"></script>
<script type="text/javascript">
swfobject.embedSWF("<?php echo fileurl('/lib/js/ofc/open-flash-chart.swf');?>", 
	"pireps_chart", "<?php echo $chart_width;?>", "<?php echo $chart_height;?>", 
	"9.0.0", "expressInstall.swf", 
	{"data-file":"<?php echo actionurl('/schedules/statsdaysdata/'.$schedule->id);?>"});
</script>
<?php
/* End added in 2.0
*/
?>
</div>