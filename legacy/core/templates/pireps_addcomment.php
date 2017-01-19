<?php if(!defined('IN_PHPVMS') && IN_PHPVMS !== true) { die(); } ?>
<h3>Add Comment to PIREP</h3>
<form action="<?php echo url('/pireps/viewpireps');?>" method="post">
<strong>Comment: </strong><br />
<textarea name="comment" style="width:90%; height: 150px"></textarea><br />

<input type="hidden" name="action" value="addcomment" />
<input type="hidden" name="pirepid" value="<?php echo $pirep->pirepid?>" />
<input type="submit" name="submit" value="Add Comment" />
</form>