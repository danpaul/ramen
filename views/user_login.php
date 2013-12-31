<?php

if( !$GLOBALS['ramen']['template_called'] )
{
	self::include_template('__main.php', __FILE__);
}

?>


<div class="small-12 medium-8 large-6 small-centered columns">

	<?php
	
		if($GLOBALS['FLASH_MESSAGE'])
		{
			echo '<h2>';

				if( count($GLOBALS['FLASH_MESSAGE']) > 1 )
				{
					echo 'The following errors occured:';

				}else{
					echo 'The following error occured:';
				}

			echo '</h2>';

			echo '<ul>';
				foreach ($GLOBALS['FLASH_MESSAGE'] as $message) {
					echo '<li class="error_message">'. $message. '</li>';
				}
			echo '</ul>';

			echo '<p>Forgot your username/password? <a href="'. $GLOBALS['config']['site_root_url']. '/user/reset-password'. '">Reset password.</a></p>';
		}
	
	?>


	<form action="<?php echo $GLOBALS['config']['login_page']; ?>" method="post">
		<fieldset>
			<legend>Login</legend>
			<label>Email</label>
			<input type="text" name="email">
			<label>Password</label>
			<input type="password" name="password">
			<input class="button small radius" type="submit" value="Login">
		</fieldset>
	</form>

	<form action="<?php echo $GLOBALS['config']['register_page']; ?>" method="post">
		<fieldset>
			<legend>Register</legend>
			<label>Email</label>
			<input type="text" name="email">
			<label>Password</label>
			<input type="password" name="password_1">
			<label>Re-enter password</label>
			<input type="password" name="password_2">
			<input class="button small radius" type="submit" value="Register">
		</fieldset>
	</form>
	
	<form action="<?php echo $GLOBALS['config']['reset_password_page']; ?>" method="post">
		<fieldset>
			<legend>Reset password</legend>
			<label>Email</labe>
			<input type="text" name="email">
			<input class="button small radius" type="submit" value="Submit">
		</fieldset>
	</form>
	
</div>
