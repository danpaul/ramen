<?php

require_once($GLOBALS['config']['views']. '/_head.php');

?>

<h1>add products</h1>
<form action="<?php echo $GLOBALS['config']['site_root_url']. '/admin/add-products'; ?>" method="post">
	Name: <input type="text" name="name"><br>
	Description: <textarea name="description"></textarea>
	<br>
	Price: <input type="text" name="price"><br>
	Inventory: <input type="text" name="inventory"><br>
	<input type="submit" value="submit"><br>
</form>