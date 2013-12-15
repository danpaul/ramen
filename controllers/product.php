<?php

require_once($config['controllers']. '/base.php');

class Product_controller extends Base_controller
{
	private $product;

	public function __construct()
	{
		require_once($GLOBALS['config']['models']. '/product.php');
		$this->product = new Product_model();
		parent::__construct();
	}

	public function get_category($category_name)
	{
		$_products = $this->product->get_products_in_category($category_name);
		require_once($GLOBALS['config']['views']. '/product_category.php');
// echo var_dump($_products);
	}

	public function get_product($id)
	{
		$_product = $this->product->get_product($id);
		require_once($GLOBALS['config']['views']. '/product.php');
	}

}