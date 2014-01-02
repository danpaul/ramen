<?php

	if( !View::$template_called )
	{
		View::include_template('__main.php', __FILE__);
		return;
	}

	echo '<div class="small-12 medium-8 large-6 small-centered columns" id="small-page-wrap">';
		require_once($GLOBALS['config']['views']. '/_alerts.php');
	echo '</div>';

?>