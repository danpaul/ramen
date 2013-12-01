<?php

require_once($GLOBALS['config']['views']. '/_head.php');

?>

<h2>Rename: <?php echo $_POST['name'] ?></h2>

<form action="<?php echo $GLOBALS['config']['site_root_url']. '/admin/taxonomy-rename-category'; ?>" method="post">
	<input type="hidden" name="id" value="<?php echo $_POST['id'] ?>" />
	<input type="hidden" name="confirmed" value="TRUE" />
	new name: <input type="text" name="new_name">
	<input type="submit" value="rename" />
</form>