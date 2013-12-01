<?php

require_once($GLOBALS['config']['views']. '/_head.php');

?>

<h1>edit product</h1>
<form action="<?php echo $GLOBALS['config']['site_root_url']. '/admin/edit-product/'. $_product['id']; ?>" method="post">
	Name: <input type="text" name="name" value="<?php echo $_product['name']; ?>"><br>
	Description: <textarea name="description"><?php echo $_product['description']; ?></textarea><br>
	Price: <input type="text" name="price" value="<?php echo $_product['price']; ?>"><br>
	Inventory: <input type="text" name="inventory" value="<?php echo $_product['inventory']; ?>"><br>
	<input type="submit" value="submit"><br>
</form>