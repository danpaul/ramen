<?php

require_once($config['controllers']. '/base.php');
require_once($config['models']. '/order.php');

class Order_controller extends Base_controller
{
	public function __construct()
	{

		$this->order_model = new Order_model();
		parent::__construct();
	}

	public function checkout()
	{
		if( !$this->user_is_logged_in() )
		{
			$this->add_flash_message('Please log in or register to continue.');
			header('Location: '. $GLOBALS['config']['site_root_url']. '/user/login-register');
		}

		if( !$this->has_cart() )
		{
			header('Location: '. $GLOBALS['config']['site_root_url']. '/cart');
		} else {
			require_once $GLOBALS['config']['models']. '/user.php';
			require_once($GLOBALS['config']['models']. '/order.php');

			$user = new User_model();			
			$order_model = new Order_model();

			$order_model->get_cart_order();
			View::$data['cart_data'] = $order_model->get_cart_order();
			View::$data['addresses'] = $user->get_addresses($_SESSION['user']['id']);
// var_dump(View::$data['addresses']);
// die();
		}
		require_once($GLOBALS['config']['views']. '/order_checkout.php');
	}

}