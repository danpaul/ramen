<?php

require_once($GLOBALS['config']['views']. '/_head.php');

?>

<p>Are you sure you want to delete: <i><?php echo $_POST['name'] ?></b></i></p>

<form action="<?php echo $GLOBALS['config']['site_root_url']. '/admin/taxonomy-delete-tag'; ?>" method="post">
	<input type="hidden" name="type" value="<?php echo $_POST['type'] ?>" />
	<input type="hidden" name="name" value="<?php echo $_POST['name'] ?>" />
	<input type="hidden" name="confirmed" value="TRUE" />
	<input type="submit" value="yes" />
</form>

<p><a href="<?php echo $GLOBALS['config']['site_root_url']. '/admin/taxonomies'; ?>">cancel</a></p>