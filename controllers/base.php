<?php

class Base_controller
{
	public function __construct()
	{
		require_once($GLOBALS['config']['views'].'/___view.php');
		if(session_id() == ''){ session_start(); }

		if (isset($_SESSION['flash_message']))
		{
			$GLOBALS['FLASH_MESSAGE'] = $_SESSION['flash_message'];
			$_SESSION['flash_message'] = NULL;
		}else{
			$GLOBALS['FLASH_MESSAGE'] = NULL;
		}

	}
}
