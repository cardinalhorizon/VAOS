<?php if(!defined('IN_PHPVMS') && IN_PHPVMS !== true) { die(); } ?>
<br /><div style="float: right;">
<form action="<?php echo url('/finances/viewreport'); ?>" method="get">
<strong>Select Report: </strong>
<?php
$years = StatsData::GetYearsSinceStart();
$months = StatsData::GetMonthsSinceStart();
$months = array_reverse($months, true);
?>
<select name="type">
	<option value="" <?php echo ($_GET['type']=='')?'selected="selected"':''?>>View Summary</option>
<?php
/*
 * Get the years since the VA started
 */
foreach($years as $yearname=>$timestamp)
{
	# Get the one that's currently selected
	if($_GET['type'] == 'y'.$timestamp)
		$selected = 'selected="selected"';
	else
		$selected = '';
	
?>
	<option value="<?php echo 'y'.$timestamp?>" <?php echo $selected?>>Yearly: <?php echo $yearname?></option>
	<?php
}

/*
 * Get all the months since the VA started
 */

foreach($months as $monthname=>$timestamp)
{
	# Get the one that's currently selected
	if($_GET['type'] == 'm'.$timestamp)
		$selected = 'selected="selected"';
	else
		$selected = '';
		
?>
	<option value="<?php echo 'm'.$timestamp?>" <?php echo $selected?>>Monthly: <?php echo $monthname?></option>
<?php
}
?>
</select>
<input type="submit" name="submit" value="View Report" />
</form>
</div><br />