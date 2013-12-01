<?php

require_once($config['controllers']. '/base.php');

class User_controller extends Base_controller
{
	private $user;

	const ERROR_USER_DOES_NOT_EXIST = 'A user with this email does not exists.';

	public function __construct()
	{
		require_once($GLOBALS['config']['models']. '/user.php');
		$this->user = new User_model();
		parent::__construct();
	}

	public function get_login()
	{
		require_once($GLOBALS['config']['views']. '/user_login.php');
	}

	public function get_update_password($secret)
	{
		require_once $GLOBALS['config']['views']. '/user_update_password.php';
	}

	public function post_login()
	{
		if($this->user->login($_POST['email'], $_POST['password']))
		{
			echo 'logged in';
		}else{
			//set the error messages and redirect
			$_SESSION['flash_message'] = $this->user->get_error_messages();
			header('Location: '. $GLOBALS['config']['login_page']);
		}
	}

	public function post_reset_password()
	{
		if(!$this->user->verify_user_exists($_POST['email']))
		{
			$_SESSION['flash_message'] = array(self::ERROR_USER_DOES_NOT_EXIST);
			header('Location: '. $GLOBALS['config']['reset_password_page']);
		}else{
			require_once $GLOBALS['config']['models']. '/email.php';

			$url = $this->user->generate_password_reset_url($_POST['email']);
			if($url != FALSE)
			{
				require_once $GLOBALS['config']['models']. '/email.php';

				$email = new Email($_POST['email']);
				if($email->send_password_reset($url))
				{
					require_once $GLOBALS['config']['views']. '/user_password_reset_sent.php';
					return;
				}
			}
			require_once $GLOBALS['config']['views']. '/error.php';
		}
	}

	public function post_update_password($secret)
	{
		
	}

	public function register()
	{
		if($this->user->register($_POST['email'], $_POST['password_1'], $_POST['password_2']))
		{
			require_once $GLOBALS['config']['models']. '/email.php';
			$email = new Email($_POST['email']);
			$email->send_verification($this->user->verification_code);
			echo 'succesful registration';
		}else{
			$_SESSION['flash_message'] = $this->user->get_error_messages();
			header('Location: '. $GLOBALS['config']['login_page']);
		}
	}

	public function verify($code)
	{
		if($this->user->verify($code)){
			echo 'user validated';
		}else{
			echo 'user not validated';
		}
	}

}