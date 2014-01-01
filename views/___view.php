<?php

class View
{
	public static $template_called = FALSE;
	public static $template_callback = NULL;
	public static $data = array();

	public static function include_template($template, $callback_file)
	{
		self::$template_called = TRUE;
		self::$template_callback = $callback_file;
		require_once($GLOBALS['config']['views']. '/'. $template);
	}

	public static function get_menu_data()
	{
		return 'foo';
	}

}