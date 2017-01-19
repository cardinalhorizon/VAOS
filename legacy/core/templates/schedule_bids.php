<?php if(!defined('IN_PHPVMS') && IN_PHPVMS !== true) { die(); } ?>
<h3>My Flight Bids</h3>
<?php
if(!$bids)
{
	echo '<p align="center">You have not bid on any flights</p>';
	return;
}
?>
<table id="tabledlist" class="tablesorter">
<thead>
<tr>
	<th>Flight Number</th>
	<th>Route</th>
	<th>Aircraft</th>
	<th>Departure Time</th>
	<th>Arrival Time</th>
	<th>Distance</th>
	<th>Options</th>
</tr>
</thead>
<tbody>
<?php
foreach($bids as $bid)
{
?>
<tr id="bid<?php echo $bid->bidid ?>">
	<td><?php echo $bid->code . $bid->flightnum; ?></td>
	<td align="center"><?php echo $bid->depicao; ?> to <?php echo $bid->arricao; ?></td>
	<td align="center"><?php echo $bid->aircraft; ?> (<?php echo $bid->registration?>)</td>
	<td><?php echo $bid->deptime;?></td>
	<td><?php echo $bid->arrtime;?></td>
	<td><?php echo $bid->distance;?></td>
	<td><a href="<?php echo url('/pireps/filepirep/'.$bid->bidid);?>">File PIREP</a><br />
		<a id="<?php echo $bid->bidid; ?>" class="deleteitem" href="<?php echo url('/schedules/removebid');?>">Remove Bid *</a><br />
		<a href="<?php echo url('/schedules/brief/'.$bid->id);?>">Pilot Brief</a><br />
		<a href="<?php echo url('/schedules/boardingpass/'.$bid->id);?>" />Boarding Pass</a>
		
	</td>
</tr>
<?php
}
?>
</tbody>
</table>
<p align="right">* - double click</p>
<hr>