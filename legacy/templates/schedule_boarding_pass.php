<?php if(!defined('IN_PHPVMS') && IN_PHPVMS !== true) { die(); } ?>
<?php
# This will only show this message if it's not a popup window
# Ends on line 13-15
if(!isset($_GET['newwindow']))
{
?>
<h3>Requirements for Online Checkin</h3>

<p>To proceed through Security Checkpoint, you will need a government-issued photo ID and either a Boarding Pass or Security Document. Customers under 18 years of age are not required to show government-issued photo ID.</p>

<p><a href="#" 
	onclick="window.open('<?php echo actionurl('/schedules/boardingpass/'.$schedule->id.'?newwindow');?>'); return false;">Open in new window for printing</a></p>
<?php
}
?>

<style>
/* Some integrated styles here, for the popup */
.boardingpass {
	font-family: Tahoma, Verdana;
	font-size: 14px;
}
.boardingpass h3 {
	background: none;
	padding-left: 3px;
	padding-bottom: 2px;
}
.boardingpass .thickline
{
	background: #333;
	height: 2px;
}
</style>
<table width="90%" class="boardingpass">
	<tr>
		<td width="1%"><img src="<?php echo SITE_URL?>/lib/images/barcode.png" /></td>
		<td align="left"><h3><?php echo SITE_NAME;?></h3></td>
	</tr>
	<tr>
		<td colspan="2"><h3>Boarding Pass</h3></td>
	</tr>
	<tr class="thickline">
		<td colspan="2"></td>
	</tr>
	<tr>
		<td valign="top">
			<table class="boardingpass">
				<tr>
				<td>
					<strong>Date:</strong> <br />
					<strong>Name: </strong> <br />
					<strong>Frequent Flier Number: </strong> <br />
					<strong>Boarding Pass Number:</strong> 
				</td>
				<td>
					<?php echo date('Y-m-d'); ?><br />
					<?php echo Auth::$userinfo->firstname.' '.Auth::$userinfo->lastname?><br />
					<?php echo Auth::$userinfo->code.strtoupper(substr(md5(Auth::$userinfo->pilotid), 0, 6))?><br />
					<?php echo $schedule->bidid; ?><br />
				</td>
				</tr>
			</table>
		</td>
		<td valign="top">
			<strong>Gate:</strong> <?php # We are gonna get a random gate
				echo chr(rand(65, 90)); // echo's a random letter between A and Z
				echo rand(1, 30);		// gate # (between 1 and 30)
			?><br />
			<strong>Confirmation:</strong>
			<?php 
				# Generate a hash from the bid id, and get the first 6 characters
				# That'll be used for our confirmation number, and upper-case them
				echo strtoupper(substr(md5($schedule->bidid), 0, 6));
			?>
		</td>
	</tr>
	<tr class="thickline">
		<td colspan="2"></td>
	</tr>
	<tr>
		<td valign="top">
			<strong>Flight: </strong><?php echo $schedule->code.$schedule->flightnum?><br />
			<strong>Depart: </strong><?php echo $schedule->deptime; ?><br />
			<strong>Arrive: </strong><?php echo $schedule->arrtime;?><br />
		</td>
		<td valign="top">
			<strong>Aircraft: </strong><?php echo $schedule->aircraft?> <br />	
			<?php echo "$schedule->depname ($schedule->depicao)";?><br />
			<?php echo "$schedule->arrname ($schedule->arricao)"; ?><br />
		</td>
	</tr>
</table>