<?php 

foreach( View::$data['featured_products'] as &$product )
{
	$product_link = $GLOBALS['config']['site_root_url']. '/product/'. $product['id'];
	$add_to_cart_link = $GLOBALS['config']['site_root_url']. '/cart/add';

	echo '<div class="small-6 medium-4 large-3 columns product-box" >';
		echo '<a href="'. $product_link. '">';
			echo '<h3>'. $product['name']. '</h3>';
		echo '</a>';
		if( !empty($product['featured_image']) )
		{
			echo '<a href="'. $product_link. '">';
				echo '<img src="'. View::get_sized_image($product['featured_image'], 150). '">';
			echo '</a>';
		}
		echo '<p>'. $product['description']. '</p>';
		echo '<p>'. $product['price']. '</p>';
		echo '<form action="'. $add_to_cart_link .'" method="post">';
			echo '<label>Quantity</label><input type="text" value="1" name="quantity"><br>';
			echo '<input type="hidden" name="product_id" value="'. $product['id']. '">';
			echo '<input class="button small radius" type="submit" value="Add To Cart">';
		echo '</form>';
	echo '</div>';
}