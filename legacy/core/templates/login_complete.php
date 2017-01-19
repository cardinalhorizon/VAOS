<?php if(!defined('IN_PHPVMS') && IN_PHPVMS !== true) { die(); } ?>
<?php
	if($redir == '')
		$redir = SITE_URL;
		
?><div align="center">
<p>You will be forwarded in a few seconds, or click below to be forwarded.</p>
<p><a href="<?php echo $redir;?>">Click here to be redirected</a></p>
</div>

<script type="text/javascript"> 
    window.location = "<?php echo $redir;?>";
</script>