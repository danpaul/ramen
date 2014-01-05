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

		$this->check_session_expiration();

	}

	private function check_session_expiration()
	{
		if( $this->user_is_logged_in() )
		{
			if( time() - $_SESSION['user']['last_activity'] 
				> $GLOBALS['config']['settings']['session_expiration'] )
			{
				require_once($GLOBALS['config']['models']. '/user.php');
				$user = new User_model();
				$user->logout();
			}else{
				$_SESSION['user']['last_activity'] = time();
			}
		}
	}

	protected function user_is_logged_in()
	{
		return( isset($_SESSION['user']['logged_in']) 
			&& $_SESSION['user']['logged_in'] === TRUE );
	}

	protected function user_is_admin()
	{
		if(    ($this->user_is_logged_in())
			&& (isset($_SESSION['user']['is_admin']))
			&& ($_SESSION['user']['is_admin'] === TRUE) )
		{
			return TRUE;
		}
		return FALSE;
	}

	//Checks if user is admin, redirect to login page if not
	protected function admin_check()
	{
		if( !$this->user_is_admin() )
		{
			header('Location: '. $GLOBALS['config']['site_root_url']. '/user/login');
			die();
		}
		return TRUE;
	}
}
