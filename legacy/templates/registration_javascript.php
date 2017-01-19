<?php if(!defined('IN_PHPVMS') && IN_PHPVMS !== true) { die(); } ?>
<script type="text/javascript" src="<?php echo fileurl('/lib/js/jquery.pstrength.js'); ?>"></script>
			
<script type="text/javascript">
$(document).ready(function(){
	$('#password').pstrength();
});
</script>