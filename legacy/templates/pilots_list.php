<?php if(!defined('IN_PHPVMS') && IN_PHPVMS !== true) { die(); } ?>
<h3><?php echo $title?></h3>

<?php
if(!$pilot_list) {
	echo 'There are no pilots!';
	return;
}
?>
<table id="tabledlist" class="tablesorter">
<thead>
<tr>
	<th>Pilot ID</th>
	<th>Name</th>
	<th>Rank</th>
	<th>Flights</th>
	<th>Hours</th>
</tr>
</thead>
<tbody>
<?php
foreach($pilot_list as $pilot)
{
	/* 
		To include a custom field, use the following example:

		<td>
			<?php echo PilotData::GetFieldValue($pilot->pilotid, 'VATSIM ID'); ?>
		</td>

		For instance, if you added a field called "IVAO Callsign":

			echo PilotData::GetFieldValue($pilot->pilotid, 'IVAO Callsign');		
	 */
	 
	 // To skip a retired pilot, uncomment the next line:
	 //if($pilot->retired == 1) { continue; }
?>
<tr>
	<td width="1%" nowrap><a href="<?php echo url('/profile/view/'.$pilot->pilotid);?>">
			<?php echo PilotData::GetPilotCode($pilot->code, $pilot->pilotid)?></a>
	</td>
	<td>
		<img src="<?php echo Countries::getCountryImage($pilot->location);?>" 
			alt="<?php echo Countries::getCountryName($pilot->location);?>" />
			
		<?php echo $pilot->firstname.' '.$pilot->lastname?>
	</td>
	<td><img src="<?php echo $pilot->rankimage?>" alt="<?php echo $pilot->rank;?>" /></td>
	<td><?php echo $pilot->totalflights?></td>
	<td><?php echo Util::AddTime($pilot->totalhours, $pilot->transferhours); ?></td>
<?php
}
?>
</tbody>
</table>