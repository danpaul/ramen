<?php

$GLOBALS['ramen']['template_called'] = TRUE;
require_once($GLOBALS['config']['views']. '/_head.php');

echo '<body>';
	require_once($GLOBALS['config']['views']. '/_menu.php');
	echo '<div class="row">';
		require_once($GLOBALS['ramen']['template_callback']);
		require_once($GLOBALS['config']['views']. '/_foot.php');
	echo '</div>';
echo '</body>';
