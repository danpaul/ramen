<?php

	if( !View::$template_called )
	{
		View::include_template('__admin_main.php', __FILE__);
		return;
	}

?>