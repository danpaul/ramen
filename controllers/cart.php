<?php

require_once($config['models']. '/cart.php');
require_once($config['controllers']. '/base.php');

class Cart_controller extends Base_controller
{
	public function __construct()
	{
		$this->cart_model = new Cart_model();
		parent::__construct();
	}

	public function add()
	{
		$product_id = $_POST['product_id'];
		$quantity = (int)$_POST['quantity'];
		$this->cart_model->upsert($product_id, $quantity);
		header('Location: '. $_SERVER['HTTP_REFERER']);
	}

	public function get_cart_order()
	{
		if( !$this->has_cart() )
		{
			View::$data['has_cart'] = FALSE;
		} else {
			View::$data['has_cart'] = TRUE;
			require_once($GLOBALS['config']['models']. '/order.php');
			$order_model = new Order_model();
			$order_model->get_cart_order();
			View::$data['cart_data'] = $order_model->get_cart_order();
		}
		require_once($GLOBALS['config']['views']. '/cart.php');
	}

	public function update_item_quantity($item_id, $quantity)
	{
		$this->cart_model->upsert($item_id, $quantity);
		header('Location: '. $GLOBALS['config']['site_root_url']. '/cart');
	}

	public function delete_item($item_id)
	{
		$user_id = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : NULL;
		$this->cart_model->delete_item($item_id, $user_id);
		header('Location: '. $GLOBALS['config']['site_root_url']. '/cart');
	}

}