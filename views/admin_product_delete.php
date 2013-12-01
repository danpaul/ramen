<?php

require_once($GLOBALS['config']['views']. '/_head.php');

?>

<p>Are you sure you want to delete: <i><?php echo $_product['name'] ?></b></i></p>

<form action="<?php echo $GLOBALS['config']['site_root_url']. '/admin/delete-product'; ?>" method="post">
	<input type="hidden" name="id" value="<?php echo $_product['id'] ?>" />
	<input type="submit" value="yes" />
</form>

<p><a href="<?php echo $GLOBALS['config']['site_root_url']. '/admin/products'; ?>">cancel</a></p>