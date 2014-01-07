<?php

if( !View::$template_called )
{
	View::include_template('__admin_main.php', __FILE__);
	return;
}

?>

	
<form action="<?php echo $GLOBALS['config']['site_root_url']. '/admin/edit-product/'. View::$data['product']['id']; ?>" method="post">
	<div class="small-12 columns">
		<h2>Edit <?php echo View::$data['product']['name']; ?>: </h2>
	</div>
	<div class="small-12 medium-8 large-9 columns">
		Name: <input type="text" name="product[name]" value="<?php echo View::$data['product']['name']; ?>"><br>
		Description: <textarea name="product[description]"><?php echo View::$data['product']['description']; ?></textarea><br>
		Price: <input type="text" name="product[price]" value="<?php echo View::$data['product']['price']; ?>"><br>
		Inventory: <input type="text" name="product[inventory]" value="<?php echo View::$data['product']['inventory']; ?>">
		<input class="button small radius" type="submit" value="Submit">
	</div>

	<div class="small-12 medium-4 large-3 columns">


		<h3>Categories:</h3>

		<?php View::display_categories(View::$data['categories'], View::$data['product_categories']); ?>

		<hr>

		<h3>Tags:</h3>

		<?php View::display_tags() ?>

	</div>
	
</form>