<?php 

foreach( View::$data['featured_products'] as &$product )
{
	$product_link = $GLOBALS['config']['site_root_url']. '/product/'. $product['id'];
	$add_to_cart_link = $GLOBALS['config']['site_root_url']. '/product/add-to-cart/'. $product['id'];

	echo '<div class="small-12 medium-4 large-3 columns">';
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
		echo '<a href="'. $add_to_cart_link. '" class="button small">Add to cart</a>';
	echo '</div>';
}