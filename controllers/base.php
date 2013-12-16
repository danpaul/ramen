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
	}

	public function get_menu_data()
	{
		return 'foo';
	}
}