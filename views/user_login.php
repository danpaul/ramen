<?php

if( !$GLOBALS['ramen']['template_called'] )
{
	self::include_template('__main.php', __FILE__);
}


?>


<!--  <div class="small-4 centered"><small class="error">test</small></div> -->

<!-- <class small="error">test2</small> -->

<div class="small-12 medium-8 large-6 small-centered columns">

	<?php
	
		if($GLOBALS['FLASH_MESSAGE'])
		{
			foreach ($GLOBALS['FLASH_MESSAGE'] as $message) {
				echo '<p>'. $message. '</p>';
			}
		}
	
	?>

	<form action="<?php echo $GLOBALS['config']['login_page']; ?>" method="post">
		<fieldset>
			<legend>Login</legend>
			<label>Email</label>
			<input type="text" name="email">
			<!-- <small class="error">test<br><br>test 2</small> -->
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
