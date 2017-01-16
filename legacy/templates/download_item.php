<?php if(!defined('IN_PHPVMS') && IN_PHPVMS !== true) { die(); } ?>
<div align="center">
<p>Your download will start in a few seconds, or <a href="<?php echo $download->link;?>">click here to manually start.</a></p>
</div>

<script type="text/javascript"> 
    window.location = "<?php echo $download->link;?>";
</script>