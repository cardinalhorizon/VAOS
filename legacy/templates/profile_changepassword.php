<?php if(!defined('IN_PHPVMS') && IN_PHPVMS !== true) { die(); } ?>
<h3>Change Password</h3>
<form action="<?php echo url('/profile');?>" method="post">
<dl>

	<dt>Enter your new password</dt>
	<dd><input type="password" id="password" name="password1" value="" /></dd>
	
	<dt>Enter your new password again</dt>
	<dd><input type="password" name="password2" value="" /></dd>
	
	<dt>Enter your old password</dt>
	<dd><input type="password" name="oldpassword" /></dd>
	
	<dt></dt>
	<dd><input type="hidden" name="action" value="changepassword" />
		<input type="submit" name="submit" value="Save Password" />
	</dd>
</dl>
</form>