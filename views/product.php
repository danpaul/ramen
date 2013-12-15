<?php

require_once($GLOBALS['config']['views']. '/_head.php');

// echo var_dump($_product);
// die();

?>

<h2><?php echo $_product['name']?></h2>
<ul>
	<li><?php echo $_product['description']?></li>
	<li><?php echo $_product['price']?></li>
</ul>