<?php if(!defined('IN_PHPVMS') && IN_PHPVMS !== true) { die(); } ?>
<?php
foreach($pilot_list as $pilot) {
?>
    <p>
        <a href="<?php echo url('/profile/view/'.$pilot->pilotid);?>">
            <?php echo PilotData::GetPilotCode($pilot->code, $pilot->pilotid).' '.$pilot->firstname.' '.$pilot->lastname ?>
        </a>
    </p>
<?php
}
?>