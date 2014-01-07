<?php

	require_once($GLOBALS['config']['views']. '/___view.php');
	require_once($GLOBALS['config']['views']. '/_head.php');
	require_once($GLOBALS['config']['views']. '/_admin_menu.php');

	echo '<div class="row">';
		require(View::$template_callback);
	echo '</div>';

	require_once($GLOBALS['config']['views']. '/_foot.php');