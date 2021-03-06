<?php


require_once 'config.php';

$params = explode_url();

switch($_SERVER['REQUEST_METHOD'])
{

/*******************************************************************************

								GET ROUTES

*******************************************************************************/

	case('GET'):
	{
		switch($params[0])
		{

			case '':
				require_once($config['controllers']. '/home.php');
				$home = new Home_controller();
				$home->get_home();
				goto end;

			case 'admin':
				require_once($config['controllers']. '/admin.php');
				$admin =new Admin_controller();

				switch(get_param($params, 1))
				{
					case 'add-product':					
						$admin->get_add_product();
						goto end;
					case 'edit-product':
						$admin->get_edit_product(get_param($params, 2));
						goto end;
					case 'delete-product':
						$admin->get_delete_product(get_param($params, 2));
						goto end;
					case 'products':
						$admin->get_products();
						goto end;
					case 'taxonomies':
						$admin->get_taxonomies();
						goto end;
					default:
						$admin->get_home();
						goto end;
				}


			case 'cart':
				require_once($config['controllers']. '/cart.php');
				$cart = new Cart_controller();
				switch(get_param($params, 1))
				{
					case '':
						$cart->get_cart_order();
						goto end;
				}

			case 'error':
				require_once($config['controllers']. '/base.php');
				new Base_controller();
				require_once($config['views']. '/error.php');
				goto end;

			case 'order':
				require_once($config['controllers']. '/order.php');
				$order = new Order_controller();
				switch(get_param($params, 1))
				{
					case 'checkout':
						$order->checkout();
						goto end;
				}

			case 'user':
				require_once($config['controllers']. '/user.php');
				$user = new User_controller();
				switch(get_param($params, 1))
				{
					case 'account':
						user_account();
						goto end;
					case 'login':
						$user->get_login('login');
						goto end;
					case 'logout':
						$user->get_logout();
						goto end;
					case 'register':
						$user->get_login('register');
						goto end;
					case 'login-register':
						$user->get_login('login_register');
						goto end;
					case 'reset-password':
						$user->get_login('reset_password');
						goto end;
					case 'update-password':
						$user->get_update_password(get_param($params, 2));
						goto end;
					case 'verify':					
						$user->verify(get_param($params, 2));
						goto end;
				}
			case 'product':
			{
				require_once($config['controllers']. '/product.php');
				$product = new Product_controller();
				$product->get_product(get_param($params, 1));
				goto end;
			}
			case 'products':
			{
				require_once($config['controllers']. '/product.php');
				$product = new Product_controller();
				switch(get_param($params, 1))
				{
					case 'category':
						$product->get_category(get_param($params, 2));
						goto end;
					case 'categories':
						$categories = isset($_GET['categories']) ? $_GET['categories'] : array();
						$product->get_categories($categories);
						goto end;
					case 'type':
						$type = get_param($params, 2);
						$tag = get_param($params, 3);
						$product->get_type($type, $tag);
						goto end;
					case 'types':
						$product->get_types($_GET);
						goto end;
					//case 'query':
				}
			}
		}
	}

/*******************************************************************************

								POST ROUTES

*******************************************************************************/

	case('POST'):
	{
		switch($params[0])
		{
			case 'admin':
				require_once($config['controllers']. '/admin.php');
				$admin = new Admin_controller();
				switch(get_param($params, 1))
				{
					case 'add-products':
						$admin->post_add_products();
						goto end;
					case 'edit-product':
						$admin->post_edit_product(get_param($params, 2));
						goto end;
					case 'delete-product':
						$admin->post_delete_product();
						goto end;
					case 'taxonomy-add-category':
						$admin->add_category();
						goto end;
					case 'taxonomy-rename-category':
						$admin->rename_category();
						goto end;
					case 'taxonomy-delete-category':
						$admin->delete_category();
						goto end;
					case 'taxonomy-move-category':
						$admin->move_category();
						goto end;						
					case 'taxonomy-add-tag':
						$admin->add_tag();
						goto end;
					case 'taxonomy-delete-tag':
						$admin->delete_tag();
						goto end;
					case 'taxonomy-rename-tag':
						$admin->rename_tag();
						goto end;
					case 'upload':
						$admin->upload();
						goto end;
				}
			case 'cart':
				require_once($config['controllers']. '/cart.php');
				$cart = new Cart_controller();

				switch(get_param($params, 1))
				{
					case 'add':
						$cart->add();
						goto end;
					case 'update-item-quantity':
						$cart->update_item_quantity(get_param($params, 2), $_POST['quantity']);
						goto end;
					case 'delete-item':
						$cart->delete_item(get_param($params, 2));
						goto end;
				}

			case 'user':
				require_once($config['controllers']. '/user.php');
				$user = new User_controller();

				switch(get_param($params, 1))
				{

					case 'add-address':
						$user->add_address();
						goto end;
					case 'login':
						$user->post_login('login');
						goto end;
					case 'register':
						$user->register('register');
						goto end;
					case 'reset-password':
						$user->post_reset_password('reset_password');
						goto end;
					case 'update-password':
						$user->post_update_password();
						goto end;
				}
		}
	}
}
echo '404';
end: ;

function get_param($params, $offset)
{
	if(isset($params[$offset]))
	{
		return urldecode($params[$offset]);
	}else{
		return '';
	}
}

function explode_url()
{
	global $config;

	$params = explode('?', $_SERVER['REQUEST_URI']);
	$params = explode('/', $params[0]);
	array_splice($params, 0, $config['url_parse_offest']);
	return($params);
}
