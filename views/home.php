<?php

	if( !View::$template_called )
	{
		View::include_template('__main.php', __FILE__);
		return;
	}

	require_once $GLOBALS['config']['views']. '/_product_loop.php';

?>