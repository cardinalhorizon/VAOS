<?php if(!defined('IN_PHPVMS') && IN_PHPVMS !== true) { die(); } ?>
<form action="<?php echo url('/login/forgotpassword');?>" method="post">
<p><strong>Enter your email address to get a new password: </strong>
	<input type="text" name="email" />
</p>
<p><input type="hidden" name="action" value="resetpass" />
   <input type="submit" name="submit" value="Request New Password" />
</p>
</form>