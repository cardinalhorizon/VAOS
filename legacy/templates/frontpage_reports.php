<?php if(!defined('IN_PHPVMS') && IN_PHPVMS !== true) { die(); } ?>
<?php
if(!$pirep_list) {
	echo 'No reports have been filed';
	return;
}

foreach($pirep_list as $pirep) {
?>
    <p>
        <a href="<?php echo url('/pireps/viewreport/'.$pirep->pirepid);?>">
            #<?php echo $pirep->pirepid.' - '.$pirep->code.$pirep->flightnum?>
        </a> - 
        <a href="<?php echo url('/profile/view/'.$pirep->pilotid);?>">
            <?php echo $pirep->firstname . ' ' . $pirep->lastname?>
        </a>
    </p>
<?php
}
?>