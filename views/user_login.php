<?php

require_once($GLOBALS['config']['views']. '/_head.php');

if($GLOBALS['FLASH_MESSAGE'])
{
	foreach ($GLOBALS['FLASH_MESSAGE'] as $message) {
		echo '<p>'. $message. '</p>';
	}
}

?>

<h1>log in</h1>
<!-- <form action="<?php echo $config['login_page']; ?>" method="post"> -->
<form action="<?php echo $GLOBALS['config']['login_page']; ?>" method="post">
	Email: <input type="text" name="email"><br>
	Password: <input type="password" name="password"><br>
	<input type="submit" value="submit"><br>
</form>

<h1>register</h1>
<form action="<?php echo $GLOBALS['config']['register_page']; ?>" method="post">
	Email: <input type="text" name="email"><br>
	Password: <input type="password" name="password_1"><br>
	Re-enter password: <input type="password" name="password_2"><br>
	<input type="submit" value="submit"><br>
</form>

<h1>reset password</h1>
<form action="<?php echo $GLOBALS['config']['reset_password_page']; ?>" method="post">
	Email: <input type="text" name="email"><br>
	<input type="submit" value="submit"><br>
</form>