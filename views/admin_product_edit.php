<?php

require_once($GLOBALS['config']['views']. '/_head.php');

?>


<?php

function display_categories($categories, $top_level = TRUE){

	echo '<ul>';
		foreach ($categories as $category_data) {
			if( ($top_level && $category_data['parent'] ===  NULL) || !$top_level )
			{
				echo '<li>';
					echo '<input type="checkbox" name="categories['. $category_data['id']. ']" id="category_'. $category_data['id']. '"  value="'. $category_data['id']. '"/>';
					echo '<label for="category_'. $category_data['id']. '">';
						echo $category_data['name'];
					echo '</label>';

					if( !empty($category_data['subcategories']) )
					{
						display_categories($category_data['subcategories'], FALSE);
					}
				echo '</li>';
			}
		}
	echo '</ul>';
}

?>

<h1>edit product</h1>
<form action="<?php echo $GLOBALS['config']['site_root_url']. '/admin/edit-product/'. $_product['id']; ?>" method="post">
	Name: <input type="text" name="name" value="<?php echo $_product['name']; ?>"><br>
	Description: <textarea name="description"><?php echo $_product['description']; ?></textarea><br>
	Price: <input type="text" name="price" value="<?php echo $_product['price']; ?>"><br>
	Inventory: <input type="text" name="inventory" value="<?php echo $_product['inventory']; ?>"><br>
	<input type="submit" value="submit"><br>

	<h2>categories:</h2>

	<?php display_categories($_categories); ?>

	<h2>tags:</h2>

	<?php foreach ($_tags as $type => $members) { ?>

		<h3>Type: <?php echo $type ?></h3>
		<ul>
			<?php foreach ($members as $tag) {
				$checked = FALSE;
				if( in_array($tag['id'], $_product_tags) )
				{
					$checked = TRUE;
				}

				?>
				<li>

					<input type="checkbox" <?php if( $checked ){ echo 'checked="TRUE"';} ?> name="tags[<?php echo $tag['id']; ?>]" id="<?php echo 'tag_'. $tag['id']; ?>" value="<?php echo $tag['id'] ?>"/>
					<label for="<?php echo 'tag_'. $tag['id']; ?>">
						<?php echo $tag['name']; ?>
					</label>
				</li>

			<?php } ?>

		</ul>

	<?php } ?>

	<input type="submit" value="submit"><br>
</form>