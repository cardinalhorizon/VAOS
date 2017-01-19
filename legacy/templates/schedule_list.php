<?php if(!defined('IN_PHPVMS') && IN_PHPVMS !== true) { die(); } ?>
<div id="scheduleresults">
<?php
if($schedule_list) {
    Template::ShowTemplate('schedule_results.tpl');
}
?>
</div>