<?php
/* This template is for the flight plan details, contains the data
	about all the flight plan points which were passed by the flight,
	and details about that point.
*/
?>
<div class="fsfk_section_title">Flight Plan Details</div>
<table class="fsfk_flightplan">
<thead>
<tr>
	<td>#</td>
	<td>Name</td>
	<td>Type</td>
	<td>Time</td>
	<td>Fuel (lbs)</td>
	<td>IAS (kts)</td>
	<td>Altitude (ft)</td>
	<td>Heading</td>
	<td>Wind</td>
	<td>OAT</td>
</tr>
</thead>
<tbody>
<?php 

foreach($lines as $point)
{
	// Data about each point is separated by a |
	$point = explode('|', $point);
	
	echo '<tr>';
	foreach($point as $info)
	{
		echo '<td>'.$info.'</td>';
	}
	echo '</tr>';
}
?>
</tbody>
</table>
<br />