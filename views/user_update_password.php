<?php

require_once($GLOBALS['config']['views']. '/_head.php');

if($GLOBALS['FLASH_MESSAGE'])
{
	foreach ($GLOBALS['FLASH_MESSAGE'] as $message) {
		echo '<p>'. $message. '</p>';
	}
}

?>

<h1>update password</h1>

<form action="<?php echo $GLOBALS['config']['site_root_url'] .'/user/update-password'; ?>" method="post">
	New password: <input type="password" name="password_1"><br>
	Retype new password: <input type="password" name="password_2"><br>
	<input type="hidden" name="secret" value="<?php echo $secret; ?>">
	<input type="submit" value="submit"><br>
</form>