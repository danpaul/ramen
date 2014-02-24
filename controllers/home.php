<?php

require_once($config['controllers']. '/base.php');

class Home_controller extends Base_controller
{
	public function get_home()
	{	
		require_once($GLOBALS['config']['models']. '/product.php');
		require_once $GLOBALS['config']['models']. '/upload.php';

		$product = new Product_model();
		View::$data['featured_products'] = $product->get_products_in_type_by_tag('product', 'featured', TRUE);
		View::$data['upload_model'] = new Upload_model();
		require_once($GLOBALS['config']['views']. '/home.php');
	}

}
