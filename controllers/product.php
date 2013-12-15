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
		require_once($GLOBALS['config']['views']. '/products.php');
	}

	public function get_categories($categories)
	{
		$_products = $this->product->get_products_in_categories($categories);
		require_once($GLOBALS['config']['views']. '/products.php');
	}

	public function get_product($id)
	{
		$_product = $this->product->get_product($id);
		require_once($GLOBALS['config']['views']. '/product.php');
	}

	public function get_type($type, $tag)
	{
		$_products = $this->product->get_products_in_type_by_tag($type, $tag);
		require_once($GLOBALS['config']['views']. '/products.php');
	}

	//key should by type value should be tag array
	public function get_types($type_array)
	{
		$_products = $this->product->get_products_by_types($type_array);
		require_once($GLOBALS['config']['views']. '/products.php');
	}
}