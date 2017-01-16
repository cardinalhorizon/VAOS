<?php if(!defined('IN_PHPVMS') && IN_PHPVMS !== true) { die(); } ?>
<h3>PIREPs List</h3>
<p><?php if(isset($descrip)) { echo $descrip; }?></p>
<?php
if(!$pirep_list) {
	echo '<p>No reports have been found</p>';
	return;
}
?>
<table id="tabledlist" class="tablesorter">
<thead>
<tr>
	<th>Flight Number</th>
	<th>Departure</th>
	<th>Arrival</th>
	<th>Aircraft</th>
	<th>Flight Time</th>
	<th>Submitted</th>
	<th>Status</th>
	<?php
	// Only show this column if they're logged in, and the pilot viewing is the
	//	owner/submitter of the PIREPs
	if(Auth::LoggedIn() && Auth::$pilot->pilotid == $pilot->pilotid) {
		echo '<th>Options</th>';
	}
	?>
</tr>
</thead>
<tbody>
<?php
foreach($pirep_list as $pirep) {
?>
<tr>
	<td align="center">
		<a href="<?php echo url('/pireps/view/'.$pirep->pirepid);?>"><?php echo $pirep->code . $pirep->flightnum; ?></a>
	</td>
	<td align="center"><?php echo $pirep->depicao; ?></td>
	<td align="center"><?php echo $pirep->arricao; ?></td>
	<td align="center"><?php echo $pirep->aircraft . " ($pirep->registration)"; ?></td>
	<td align="center"><?php echo $pirep->flighttime; ?></td>
	<td align="center"><?php echo date(DATE_FORMAT, $pirep->submitdate); ?></td>
	<td align="center">
		<?php
		
		if($pirep->accepted == PIREP_ACCEPTED) {
            echo '<div id="success">Accepted</div>';
		} elseif($pirep->accepted == PIREP_REJECTED) {
            echo '<div id="error">Rejected</div>';
		} elseif($pirep->accepted == PIREP_PENDING) {
            echo '<div id="error">Approval Pending</div>';
		} elseif($pirep->accepted == PIREP_INPROGRESS) {
            echo '<div id="error">Flight in Progress</div>';
		}
			
		
		?>
	</td>
	<?php
	// Only show this column if they're logged in, and the pilot viewing is the
	//	owner/submitter of the PIREPs
	if(Auth::LoggedIn() && Auth::$pilot->pilotid == $pirep->pilotid) {
		?>
	<td align="right">
		<a href="<?php echo url('/pireps/addcomment?id='.$pirep->pirepid);?>">Add Comment</a><br />
		<a href="<?php echo url('/pireps/editpirep?id='.$pirep->pirepid);?>">Edit PIREP</a>
	</td>
	<?php
	}
	?>
</tr>
<?php
}
?>
</tbody>
</table>