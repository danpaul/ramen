<?php

date_default_timezone_set('America/New_York');

$config = array();

//set to `FALSE` for production

$config['environment'] = 'imac';

/* 
	Environment
*/

$config['db'] = array();

switch($config['environment'])
{
	case('netbook'):
		$config['db']['host'] = 'localhost';
		$config['db']['name'] = 'super_ramen';
		$config['db']['user'] = 'root';
		$config['db']['password'] = '';
		$config['site_root_url'] = 'http://localhost';

		$config['debug'] = TRUE;

		$config['url_parse_offest'] = 1;
		break;

	case('imac'):
		$config['db']['host'] = 'localhost';
		$config['db']['name'] = 'super_ramen';
		$config['db']['user'] = 'root';
		$config['db']['password'] = 'root';
		$config['site_root_url'] = 'http://localhost:8888/ramen';

		$config['debug'] = TRUE;

		$config['url_parse_offest'] = 2;
		break;
}

if($config['debug']){
	error_reporting(E_ALL);
	ini_set('display_errors', 'on');
}

/* 
	Site settings
*/

$config['settings'] = array();
$config['settings']['site_name'] = 'Foo.com';
$config['settings']['email'] = 'foo@email.com';


/* 
	Directory/file locations
*/

// PATHS
$config['site_root_path'] = dirname(__FILE__);

$config['controllers'] = $config['site_root_path']. '/controllers';
$config['models'] = $config['site_root_path']. '/models';
$config['views'] = $config['site_root_path']. '/views';
$config['content'] = $config['site_root_path']. '/content';

//URLS
$config['assets_root_url'] = $config['site_root_url']. '/assets';
$config['login_page'] = $config['site_root_url']. '/user/login';
$config['reset_password_page'] = $config['site_root_url']. '/user/reset-password';
$config['register_page'] = $config['site_root_url']. '/user/register';
$config['update_password_page'] = $config['site_root_url']. '/user/update-password';
$config['verify_page'] = $config['site_root_url']. '/user/verify';

$config['error_page'] = $config['site_root_url']. '/error';

