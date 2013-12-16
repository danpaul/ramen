<?php

echo 'foo';

class Email
{
	private $to_email;

	const MESSAGE_LENGTH = 80;
		
	public function __construct($to_email_in)
	{
		$this->to_email = $to_email_in;
	}

	public function send_password_reset($url)
	{
		require_once($GLOBALS['config']['content']. '/email.php');
		$message = $content_email_password_reset_body. "\r\n\r\n". $url;
		$message = wordwrap($message, self::MESSAGE_LENGTH, "\r\n");
		$header = 'From: '. $GLOBALS['config']['settings']['email'];
		return mail($this->to_email,
					$content_email_password_reset_subject,
					$message,
					$header);
	}

	public function send_verification($verification_code)
	{
		require_once($GLOBALS['config']['content']. '/email.php');
		$message = $content_email_registration_body. "\r\n\r\n". $this->make_verification_link($verification_code);
		$message = wordwrap($message, self::MESSAGE_LENGTH, "\r\n");
		$header = 'From: '. $GLOBALS['config']['settings']['email'];
		return mail($this->to_email,
					$content_email_registration_subject,
					$message,
					$header);
	}

	private function make_verification_link($verification_code)
	{
		return $GLOBALS['config']['verify_page']. "/$verification_code";
	}
}