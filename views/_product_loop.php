<?php 

foreach( View::$data['featured_products'] as &$product )
{
echo var_dump($product);
die();
	echo '<div class="small-12 medium-4 large-3">';
		if( !empty($product['images']) )
		{
			$featured_image = View::get_sized_image($product['featured']['id'], 150);
echo var_dump($featured_image);
die();
		}
	echo '</div>';
}