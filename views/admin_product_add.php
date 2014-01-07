<?php

if( !View::$template_called )
{
	View::include_template('__admin_main.php', __FILE__);
	return;
}

?>

<form action="<?php echo $GLOBALS['config']['site_root_url']. '/admin/add-products'; ?>" method="post">
	<div class="small-12 columns">
		<h2>Add new product: </h2>
	</div>

	<div class="small-12 medium-8 large-9 columns">
		Name: <input type="text" name="product[name]"><br>
		Description: <textarea name="product[description]"></textarea>
		<br>
		Price: <input type="text" name="product[price]"><br>
		Inventory: <input type="text" name="product[inventory]"><br>
		<input class="button small radius" type="submit" value="Submit">
	</div>

	<div class="small-12 medium-4 large-3 columns">

		<h2>categories:</h2>

		<?php View::display_categories(View::$data['categories']); ?>

		<h2>tags:</h2>

		<?php View::display_tags(); ?>

	</div>

</form>