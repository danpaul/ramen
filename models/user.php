<?php

//require_once $config['models']. '/base.php';

require_once($GLOBALS['config']['models']. '/base.php');

class User_model extends Base_model
{
	private $logged_in;
	private $user;
	private $error_message;

	public $verification_code;

	const STATEMENT_GET_USER = 'SELECT * FROM Users WHERE email=:email LIMIT 1';
	const STATEMENT_GET_VALIDATION = 'SELECT * FROM EmailVerifications WHERE code=:code LIMIT 1';
	const STATEMENT_GET_PASSWORD_RESET = 'SELECT * FROM PasswordResets WHERE secret=:secret LIMIT 1';
	const STATEMENT_INSERT_PASSWORD_RESET = 'INSERT INTO PasswordResets(time, secret, email) VALUES (:time, :secret, :email)';
	const STATEMENT_INSERT_USER = 'INSERT INTO Users(email, password, salt) VALUES (:email, :password, :salt)';
	const STATEMENT_INSERT_VERIFICATION = 'INSERT INTO EmailVerifications(code, email) VALUES (:code, :email)';
	// const STATEMENT_UPDATE_USER = 'UPDATE * Users SET WHERE id=:id LIMIT 1';


const STATEMENT_UPDATE_USER_PASSWORD = 'UPDATE Users SET salt=:salt, password=:password WHERE email=:email LIMIT 1';


	const ERROR_DATABASE = 'A database error occured';
	const ERROR_INVALID_EMAIL = 'The email address is not valid.';
	const ERROR_INVALID_VALIDATION_CODE = 'This validation code does not correspond to a user.';
	const ERROR_LOGIN = 'Invalid email address/password combination.';
	const ERROR_PASSWORD_NO_SPECIAL_CHARACTER = 'Password must contain at least one special (non-alphanumeric) character.';
	const ERROR_PASSWORD_MISMATCH = 'Passwords need to match.';
	const ERROR_PASSWORD_TOO_SHORT = 'Password must be at least 8 characters.';
	const ERROR_PASSWORD_TOO_LONG = 'Password can not be more than 255 characters.';
	const ERROR_PASSWORD_RESET_INVALID = 'Your password reset code is not valid. It may have expired. Please request a new code.';
	const ERROR_USER_DOESNT_EXIST = 'This user no longer exists.';
	const ERROR_USER_EXISTS = 'A user with this email already exists.';

	const PASSWORD_MINIMUM_LENGTH = 8;
	const PASSWORD_RESET_TIME_LIMIT = 7200; //2 hours

	public function __construct()
	{
		$this->logged_in = FALSE;
		$this->user = NULL;
		$this->error_message = array();
		$this->verification_code = NULL;

		parent::__construct();
	}

	public function login($email, $password)
	{

// echo $password;
// die();

		if( !$this->set_user($email) )
		{
			array_push($this->error_message, self::ERROR_LOGIN);
			return FALSE;
		}

		$password = $this->hash_password($password, $this->user['salt']);

// echo $password;
// echo '<br>';
// echo $this->user['password'];
// die();

		if( $password === $this->user['password'] ){
			$this->logged_in = TRUE;
			return TRUE;
		}else{
			array_push($this->error_message, self::ERROR_LOGIN);
			return FALSE;
		}
	}

	public function register($email, $password_1, $password_2)
	{

		if( !$this->validate_registration_information($email, $password_1, $password_2) )
		{
			return FALSE;
		}

		$params['email'] = $email;
		$params['salt'] = $this->get_salt();
		$params['password'] = $this->hash_password($password_1, $params['salt']);

		$statement = $this->db->prepare(self::STATEMENT_INSERT_USER);
		
		if(!$statement->execute($params))
		{
			array_push($this->error_message, self::ERROR_DATABASE);
			return FALSE;
		}

		$code = $this->get_code($email);
		$this->verification_code = $code;
		$verification_params = array('code' => $code, 'email' => $params['email']);
		$statement = $this->db->prepare(self::STATEMENT_INSERT_VERIFICATION);

		if(!$statement->execute($verification_params))
		{
			array_push($this->error_message, self::ERROR_DATABASE);
			return FALSE;
		}

		$this->set_user($email);

		return TRUE;
	}

	public function get_error_messages()
	{
		return $this->error_message;
	}

	public function get_email()
	{
		return $this->user['email'];
	}

	public function generate_password_reset_url($email)
	{
		$params = array(
			'time' => time(),
			'email' => $email,
			'secret' => $this->get_code($email)
		);
		$statement = $this->db->prepare(self::STATEMENT_INSERT_PASSWORD_RESET);
		if($statement->execute($params))
		{
			return $GLOBALS['config']['update_password_page'] ."/$params[secret]";
		}
		return FALSE;
	}

	public function update_password($password_1, $password_2, $secret)
	{
		//check secret and ensure it hasn't expired
		$statement = $this->db->prepare(self::STATEMENT_GET_PASSWORD_RESET);
		if( !$statement->execute(array('secret' => $secret)) )
		{
			array_push($this->error_message, self::ERROR_DATABASE);
			return FALSE;
		}

		$password_reset = $statement->fetch();

 		if( $password_reset === FALSE || ((time() - $password_reset['time']) > self::PASSWORD_RESET_TIME_LIMIT) )
 		{
 			array_push($this->error_message, self::ERROR_PASSWORD_RESET_INVALID);
 			return FALSE;
 		}

 		if( !$this->validate_password($password_1, $password_2) )
 		{
 			return FALSE;
 		}

 		$salt = $this->get_salt();

 		$params = array(
 			'email' => $password_reset['email'],
 			'salt' => $salt,
 			'password' => $this->hash_password($password_1, $salt)
 		);

 		$statement = $this->db->prepare(self::STATEMENT_UPDATE_USER_PASSWORD);

 		if( !$statement->execute($params) )
 		{
			array_push($this->error_message, self::ERROR_DATABASE);
			return FALSE;	
 		}
 		return TRUE;
	}

	public function verify($code)
	{
		$params = array('code' => $code);
		$statement = $this->db->prepare(self::STATEMENT_GET_VALIDATION);
		$statement->execute($params);
		$verification = $statement->fetch();
		if($verification)
		{
			$this->user = $this->get_user($verification['email']);
			if($this->user)
			{
				$this->update_user('verified', TRUE);
				return TRUE;
			}else{
				array_push($this->error_message, self::ERROR_USER_DOESNT_EXIST);
				return FALSE;
			}
		}else{
			array_push($this->error_message, self::ERROR_INVALID_VALIDATION_CODE);
			return FALSE;
		}
	}

	public function verify_user_exists($email)
	{
		if($this->get_user($email) != FALSE){ return TRUE; }
		return FALSE;
	}

	private function get_code($email)
	{
		return hash('sha256', time(). rand(). $email);
	}

	private function get_salt()
	{
		return hash('md5', rand());
	}

	private function get_user($email)
	{
		$statement = $this->db->prepare(self::STATEMENT_GET_USER);
		$statement->execute(array('email' => $email));
		return $statement->fetch();
	}	

	private function hash_password($password, $salt)
	{
		// return hash('md5', crypt($password, $salt));
		// return md5(crypt($password, $salt));
		return md5(crypt($password, $salt));
	}

	private function set_user($email)
	{
		$statement = $this->db->prepare(self::STATEMENT_GET_USER);
		$statement->execute(array('email' =>$email));
		$this->user = $statement->fetch();
		if($this->user){ return TRUE; }else{ return FALSE; }
	}

	private function update_user($element, $new_value)
	{
		$id = $this->user['id'];
		$data = array($new_value);
		$sql = "UPDATE Users SET $element=? WHERE id=$id";
		$statement = $this->db->prepare($sql);
		$statement->execute($data);
	}

	private function validate_registration_information($email, $password_1, $password_2)
	{
		$valid = TRUE;

		if( $this->get_user($email) )
		{
			$valid = FALSE;
			array_push($this->error_message, self::ERROR_USER_EXISTS);
		}

		if( !filter_var($email, FILTER_VALIDATE_EMAIL ))
		{
			$valid = FALSE;
			array_push($this->error_message, self::ERROR_INVALID_EMAIL);
		}

		if( !$this->validate_password($password_1, $password_2) )
		{
			$valid = FALSE;
		}

		return $valid;
	}

	private function validate_password($password_1, $password_2)
	{
		$valid = TRUE;

		if( $password_1 != $password_2 )
		{
			$valid = FALSE;
			array_push($this->error_message, self::ERROR_PASSWORD_MISMATCH);
		}

		if( strlen($password_1) < self::PASSWORD_MINIMUM_LENGTH )
		{
			$valid = FALSE;
			array_push($this->error_message, self::ERROR_PASSWORD_TOO_SHORT);
		}

		return $valid;

	}
}