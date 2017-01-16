<?php if(!defined('IN_PHPVMS') && IN_PHPVMS !== true) { die(); } ?>
<div id="mainbox">
<h3>Pilot Center</h3>
<div class="indent">
<p><strong>Welcome back <?php echo $pilot->firstname . ' ' . $pilot->lastname; ?>!</strong></p>
<table>
<tr>
	<td valign="top" align="center">
		<img src="<?php echo PilotData::getPilotAvatar($pilotcode); ?>" />
		<br /><br />
		<img src="<?php echo $pilot->rankimage ?>" />
	</td>
	<td valign="top">
		<ul style="margin-top: 0px;">
			<li><strong>Your Pilot ID: </strong> <?php echo $pilotcode; ?></li>
			<li><strong>Your Rank: </strong><?php echo $pilot->rank;?></li>
			<?php
			if($report) { ?>
				<li><strong>Latest Flight: </strong><a 
						href="<?php echo url('pireps/view/'.$report->pirepid); ?>">
						<?php echo $report->code . $report->flightnum; ?></a>
				</li>
			<?php
			}
			?>
			
			<li><strong>Total Flights: </strong><?php echo $pilot->totalflights?></li>
			<li><strong>Total Hours: </strong><?php echo $pilot->totalhours; ?></li>
			<li><strong>Total Transfer Hours: </strong><?php echo $pilot->transferhours?></li>
			<li><strong>Total Money: </strong><?php echo FinanceData::formatMoney((floatval($pilot->totalpay) + floatval($pilot->payadjust))) ?></li>
		
			<?php
			if($nextrank)
			{
			?>
				<p>You have <?php echo ($nextrank->minhours - $pilot_hours)?> hours 
					left until your promotion to <?php echo $nextrank->rank?></p>
			<?php
			}
			?>
		</ul>

	</td>
</tr>
</table>
	<table>
	<tr>
	<td valign="top" nowrap>
		<p>
			<strong>Profile Options</strong>
			<ul>
				<li><a href="<?php echo url('/profile/editprofile'); ?>">Edit My Profile, Email and Avatar</a></li>
				<li><a href="<?php echo url('/profile/changepassword'); ?>">Change my Password</a></li>
				<li><a href="<?php echo url('/profile/badge'); ?>">View my Badge</a></li>
				<li><a href="<?php echo url('/profile/stats'); ?>">My Stats</a></li>
				<li><a href="<?php echo url('/downloads'); ?>">View Downloads</a></li>
			</ul>
		</p>
		<p>
			<strong>Flight Operations</strong>
			<ul>
				<li><a href="<?php echo url('/pireps/mine');?>">View my PIREPs</a></li>
				<li><a href="<?php echo url('/pireps/routesmap');?>">View a map of all my flights</a></li>
				<li><a href="<?php echo url('/pireps/filepirep');?>">File a Pilot Report</a></li>
				<li><a href="<?php echo url('/schedules/view');?>">View Flight Schedules</a></li>
				<li><a href="<?php echo url('/schedules/bids');?>">View my flight bids</a></li>		
				<li><a href="<?php echo url('/finances');?>">View VA Finances</a></li>
			</ul>	
		</p>
		<p>
			<strong>My Awards</strong><br />
			<?php
			if(!$allawards) {
				echo 'No awards yet';
			} else {	
			
				/* To show the image:
					<img src="<?php echo $award->image?>" alt="<?php echo $award->descrip?>" />
				*/
							
			?>
			<ul>
				<?php foreach($allawards as $award){ ?>
				<li><?php echo $award->name ?></li>
				<?php } ?>
				
				
			</ul>	
			<?php
			}
			?>
		</p>
		<p>
			<strong>ACARS Config</strong>
			<ul>
				<li><a href="<?php echo actionurl('/acars/fspaxconfig');?>">Download FSPax Config</a></li>
				<li><a href="<?php echo actionurl('/acars/xacarsconfig');?>">Download XAcars Config</a></li>
				<li><strong>For FSFK, you need the following (Place in Documents/FS Flight Keeper/Templates): </strong></li>
				<li><a href="<?php echo actionurl('/fsfk/vaconfig_template');?>">VA-Template.txt</a></li>
				
			</ul>
		</p>
	</td>
	<td valign="top">
		
	</td>
	</tr></table>
</div>
</div>
<br />