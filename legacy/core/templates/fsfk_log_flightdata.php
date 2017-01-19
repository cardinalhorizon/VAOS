<?php if(!defined('IN_PHPVMS') && IN_PHPVMS !== true) { die(); } ?>
<div class="fsfk_section_title" >Flight Details</div>
<table class="fsfk_flightplan">
<thead>
<tr>
	<td>#</td>
	<td>Name</td>
	<td>Type</td>
</tr>
</thead>
<tbody>

<?php 

/* $data is all the ACARS data */
$i=1;
foreach($data as $name => $value) {

	/* $name is the name of the parameter (like "TOIAS")
	   $value is 160 */
	echo "<tr>
			<td>{$i}</td>
			<td>{$name}</td>
			<td>{$value}</td>
		  </tr>";
	
	$i++;
}
?>

</tbody>
</table>
<br />