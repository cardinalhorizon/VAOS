<?php if(!defined('IN_PHPVMS') && IN_PHPVMS !== true) { die(); } ?>
<?php
if(!$pilot) {
	echo '<h3>This pilot does not exist!</h3>';
	return;
}
?>
<h3>Profile For <?php echo $pilot->firstname . ' ' . $pilot->lastname?></h3>
<table>
	<tr>
		<td align="center" valign="top">
			<?php
			if(!file_exists(SITE_ROOT.AVATAR_PATH.'/'.$pilotcode.'.png')) {
				echo 'No avatar';
			} else {
				echo '<img src="'.SITE_URL.AVATAR_PATH.'/'.$pilotcode.'.png'.'" alt="No Avatar" /> ';
			}
			?>
			<br /><br />
			<img src="<?php echo $pilot->rankimage?>"  alt="" />
		</td>
		<td valign="top">
			<ul>
				<li><strong>Pilot ID: </strong><?php echo $pilotcode ?></li>
				<li><strong>Rank: </strong><?php echo $pilot->rank;?></li>
				<li><strong>Total Flights: </strong><?php echo $pilot->totalflights?></li>
				<li><strong>Total Hours: </strong><?php echo Util::AddTime($pilot->totalhours, $pilot->transferhours); ?></li>
				<li><strong>Location: </strong>
					<img src="<?php echo Countries::getCountryImage($pilot->location);?>"
								alt="<?php echo Countries::getCountryName($pilot->location);?>" />
					<?php echo Countries::getCountryName($pilot->location);?>
				</li>

				<?php
				// Show the public fields
				if($allfields) {
					foreach($allfields as $field) {
						echo "<li><strong>$field->title: </strong>$field->value</li>";
					}
				}
				?>
			</ul>

			<p>
			<strong>Awards</strong>
			<?php
			if(is_array($allawards)) {
			?>
			<ul>
				<?php
                foreach($allawards as $award) {
					/* To show the image:

						<img src="<?php echo $award->image?>" alt="<?php echo $award->descrip?>" />
					*/
				?>
					<li><?php echo $award->name ?></li>
				<?php } ?>
			</ul>
			<?php
			}
			?>
		</p>
		</td>

	</tr>
</table>

<!-- Google Chart Implementation - OFC Replacement - simpilot -->
<img src="<?php echo $chart_url  ?>" alt="Pirep Chart" />
