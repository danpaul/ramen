<?php

require_once($GLOBALS['config']['models']. '/base.php');
require_once($GLOBALS['config']['models']. '/product.php');


class Order_model extends Base_model
{
	public function __construct()
	{
		$this->product_model = new Product_model();
		parent::__construct();
	}

	public function get_cart_order()
	{
		if( !isset($_SESSION['cart']) ){ return NULL; }
		$product_ids = array();
		foreach( $_SESSION['cart'] as $prouct_id => $quantity )
		{
			array_push($product_ids, $prouct_id);
		}
		$products = $this->product_model->get_products_by_ids($product_ids);
		return $this->get_priced_cart($products, $_SESSION['cart']);
	}

	protected function get_priced_cart(&$products, &$cart)
	{
		$priced_cart = array();
		$priced_cart['total_price'] = 0.0;
		$priced_cart['items'] = array();
		foreach( $cart as $product_id => $quantity )
		{
			$product = $this->find_product_by_id($products, $product_id);
			$priced_cart['items'][$product_id] = array();
			$priced_cart['items'][$product_id]['quantity'] = $quantity;
			$priced_cart['items'][$product_id]['price'] = $product['price'];
			$priced_cart['items'][$product_id]['total_price'] = $product['price'] * $quantity;
			$priced_cart['total_price'] += $priced_cart['items'][$product_id]['total_price'];
		}
		return $priced_cart;
	}

	protected function find_product_by_id(&$products, $id)
	{
		foreach( $products as $product )
		{
			if( $product['id'] == $id )
			{
				return $product;
			}
		}
		return NULL;
	}

}