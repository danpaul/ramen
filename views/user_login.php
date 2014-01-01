<?php

	if( !View::$template_called )
	{
		View::include_template('__main.php', __FILE__);
		return;
	}

?>

<div class="small-12 medium-8 large-6 small-centered columns" id="login-wrap">

	<?php require_once($GLOBALS['config']['views']. '/_flash_alerts.php'); ?>

	<?php if( View::$data['action'] === 'login' || View::$data['action'] === 'login_register') { ?>

		<form action="<?php echo $GLOBALS['config']['site_root_url']. '/user/login'; ?>" method="post">
			<fieldset>
				<legend>Login</legend>
				<label>Email</label>
				<input type="text" name="email">
				<label>Password</label>
				<input type="password" name="password">
				<input class="button small radius" type="submit" value="Login">
				<br>
				<small><a href="<?php echo $GLOBALS['config']['site_root_url']. '/user/reset-password'; ?>">Forgot your username/password?</a></small>
			</fieldset>
		</form>

	<?php } if( View::$data['action'] === 'register' || View::$data['action'] === 'login_register') { ?>

		<form action="<?php echo $GLOBALS['config']['site_root_url'].'/user/register'; ?>" method="post">
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

	<?php } if( View::$data['action'] === 'reset_password') { ?>
	
		<form action="<?php echo $GLOBALS['config']['site_root_url']. '/user/reset-password'; ?>" method="post">
			<fieldset>
				<legend>Reset password</legend>
				<label>Email</label>
				<input type="text" name="email">
				<input class="button small radius" type="submit" value="Submit">
			</fieldset>
		</form>

	<?php } if( View::$data['action'] === 'update_password') { ?>

		<form action="<?php echo $GLOBALS['config']['site_root_url']. '/user/update-password'; ?>" method="post">
			<fieldset>
				<legend>Update password</legend>
				<label>New password</label>
				<input type="password" name="password_1">
				<label>Retype new password</label>
				<input type="password" name="password_2">
				<input type="hidden" name="secret" value="<?php echo View::$data['secret']; ?>">
				<input class="button small radius" type="submit" value="Submit">
			</fieldset>
		</form>

	<?php } ?>
	
</div>
