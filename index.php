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
				echo 'index';
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
					case 'products':
						$admin->get_products();
						goto end;
					case 'taxonomies':
						$admin->get_taxonomies();
						goto end;
				}
				goto end;

			case 'error':
				require_once($config['controllers']. '/base.php');
				require_once($config['views']. '/error.php');
				goto end;


			case 'user':
				require_once($config['controllers']. '/user.php');
				$user = new User_controller();
				switch(get_param($params, 1))
				{
					case 'account':
						user_account();
						goto end;
					case 'login':
						$user->get_login();
						goto end;
					case 'reset-password':
						$user->get_login();
						goto end;
					case 'update-password':
						$user->get_update_password(get_param($params, 2));
						goto end;
					case 'verify':					
						$user->verify(get_param($params, 2));
						goto end;
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
				}
			case 'user':
				require_once($config['controllers']. '/user.php');
				$user = new User_controller();

				switch(get_param($params, 1))
				{
					case 'login':
						$user->post_login();
						goto end;
					case 'register':
						$user->register();
						goto end;
					case 'reset-password':
						$user->post_reset_password();
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
		return $params[$offset];
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
