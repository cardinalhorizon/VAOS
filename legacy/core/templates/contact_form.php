<?php if(!defined('IN_PHPVMS') && IN_PHPVMS !== true) { die(); } ?>
<h3>Contact Us</h3>
<form method="post" action="<?php echo url('/contact'); ?>">
  <table width='100%' border='0'>
    <tr>
      <td><strong>Name:</strong></td>
      <td>
		<?php
		if(Auth::LoggedIn())
		{
			echo Auth::$userinfo->firstname .' '.Auth::$userinfo->lastname;
			echo '<input type="hidden" name="name"
					value="'.Auth::$userinfo->firstname
							.' '.Auth::$userinfo->lastname.'" />';
		}
		else
		{
		?>
			<input type="text" name="name" value="" />
			<?php
		}
		?>
      </td>
    </tr>
    <tr>
		<td width="1%" nowrap><strong>E-Mail Address:</strong></td>
		<td>
		<?php
		if(Auth::LoggedIn())
		{
			echo Auth::$userinfo->email;
			echo '<input type="hidden" name="name"
					value="'.Auth::$userinfo->email.'" />';
		}
		else
		{
		?>
			<input type="text" name="email" value="" />
			<?php
		}
		?>
		</td>
	</tr>

	<tr>
		<td><strong>Subject: </strong></td>
		<td><input type="text" name="subject" value="<?php echo $_POST['subject'];?>" /></td>

	</tr>
    <tr>
      <td><strong>Message:</strong></td>
      <td>
		<textarea name="message" cols='45' rows='5'><?php echo $_POST['message'];?></textarea>
      </td>
    </tr>

    <tr>
		<td width="1%" nowrap><strong>Captcha</strong></td>
		<td>
                    <?php if(isset($captcha_error)){echo '<p class="error">'.$captcha_error.'</p>';} ?>
                    <div class="g-recaptcha" data-sitekey="<?php echo $sitekey;?>"></div>
                    <script type="text/javascript" src="https://www.google.com/recaptcha/api.js?hl=<?php echo $lang;?>">
                    </script>
		</td>
	</tr>

    <tr>
		<td>
			<input type="hidden" name="loggedin" value="<?php echo (Auth::LoggedIn())?'true':'false'?>" />
		</td>
		<td>
          <input type="submit" name="submit" value='Send Message'>
		</td>
    </tr>
  </table>
</form>