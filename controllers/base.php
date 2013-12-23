<?php

class Base_controller
{
	public function __construct()
	{
		if(session_id() == ''){ session_start(); }

		if (isset($_SESSION['flash_message']))
		{
			$GLOBALS['FLASH_MESSAGE'] = $_SESSION['flash_message'];
			$_SESSION['flash_message'] = NULL;
		}else{
			$GLOBALS['FLASH_MESSAGE'] = NULL;
		}

		//initalize global var for flags
		$GLOBALS['ramen'] = array();
		$GLOBALS['ramen']['template_called'] = FALSE;
	}

	public static function get_menu_data()
	{
		return 'foo';
	}

	public static function include_template($template, $callback_file)
	{
		if( !$GLOBALS['ramen']['template_called'] )
		{
			$GLOBALS['ramen']['template_callback'] = __FILE__; 
			require_once($GLOBALS['config']['views']. '/__main.php');
}

echo __FILE__;
die();

	}


}