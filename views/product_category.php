<?php

require_once($GLOBALS['config']['views']. '/_head.php');

?>

<?php foreach ($_products as $product) { ?>

	<h2><?php echo $product['name']?></h2>
	<ul>
		<li><?php echo $product['description']?></li>
		<li><?php echo $product['price']?></li>
	</ul>
	
<?php } ?>
