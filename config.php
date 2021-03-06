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
		
	case('samsung'):
		$config['db']['host'] = 'localhost';
		$config['db']['name'] = 'ramen';
		$config['db']['user'] = 'root';
		$config['db']['password'] = 'root';
		$config['site_root_url'] = 'http://localhost/ramen';

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
$config['settings']['session_expiration'] = 3600; //in seconds


/* 
	Directory/file locations
*/

// PATHS
$config['site_root_path'] = dirname(__FILE__);

$config['controllers'] = $config['site_root_path']. '/controllers';
$config['models'] = $config['site_root_path']. '/models';
$config['views'] = $config['site_root_path']. '/views';
$config['content'] = $config['site_root_path']. '/content';
$config['lib'] = $config['site_root_path']. '/lib';

$config['upload_path'] = $config['site_root_path']. '/assets/uploads';
$config['upload_url'] = $config['site_root_url']. '/assets/uploads';
//URLS
$config['assets_root_url'] = $config['site_root_url']. '/assets';
$config['error_page'] = $config['site_root_url']. '/error';

//Image sizes
$config['image_widths'] = array(150, 300, 600, 'full');

