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
	<div class="small-12 medium-8 large-8 columns left">
		Name: <input type="text" name="product[name]" value="<?php echo View::$data['product']['name']; ?>"><br>
		Description: <textarea name="product[description]"><?php echo View::$data['product']['description']; ?></textarea><br>
		Price: <input type="text" name="product[price]" value="<?php echo View::$data['product']['price']; ?>"><br>
		Inventory: <input type="text" name="product[inventory]" value="<?php echo View::$data['product']['inventory']; ?>">
	</div>

	<div class="small-12 medium-4 large-3 columns right">
		<h3>Categories:</h3>
		<?php View::display_categories(View::$data['categories'], View::$data['product_categories']); ?>
		<hr>
		<h3>Tags:</h3>
		<?php View::display_tags() ?>
	</div>

	<div class="small-12 medium-8 large-9 columns left">
		<table>
			<thead>
				<th>Featured</th>
				<th>Image</th>
				<th>Remove image</th>
			</thead>
			<tbody> <?php
				foreach( View::$data['images'] as $image )
				{
// echo var_dump($image['file_name']);
// die();
$image_path = View::$data['upload_model']->get_sized_image($image, 250);
					// $image_path = View::$data['upload_model']->get_sized_image($image['file_name'], 250);
					$featured_checked = '';
					if( $image['featured'] === '1' )
					{
						$featured_checked = 'checked';
					}
					echo '<tr>';
						echo '<td>';
							echo '<input type=checkbox name="featured_image['. $image['id']. ']" '. $featured_checked. '/>';
							echo '</td>';
						echo '<td>';
							echo '<img src="'. $image_path. '"/>';
						echo '</td>';
						echo '<td>';
							echo '<input type=checkbox name="remove_image['. $image['id']. ']" />';
						echo '</td>';
					echo '</tr>';
				} ?>
			</tbody>
		</table>
		<input class="button small radius" type="submit" value="Submit">
	</div>
	
</form>