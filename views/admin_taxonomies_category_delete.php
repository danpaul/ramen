<?php

require_once($GLOBALS['config']['views']. '/_head.php');

?>

<p>Are you sure you want to delete the following category and all its children: <i><?php echo $_POST['name'] ?></b></i></p>

<form action="<?php echo $GLOBALS['config']['site_root_url']. '/admin/taxonomy-delete-category'; ?>" method="post">
	<input type="hidden" name="id" value="<?php echo $_POST['id'] ?>" />
	<input type="hidden" name="confirmed" value="TRUE" />
	<input type="submit" value="yes" />
</form>

<p><a href="<?php echo $GLOBALS['config']['site_root_url']. '/admin/taxonomies'; ?>">cancel</a></p>