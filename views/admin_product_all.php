<?php

	require_once($GLOBALS['config']['views']. '/_head.php');

	if($GLOBALS['FLASH_MESSAGE'])
	{
		foreach ($GLOBALS['FLASH_MESSAGE'] as $message) {
			echo '<p>'. $message. '</p>';
		}
	}

?>

<?php if ( !empty($_products) ) { ?>

	<table>

		<?php
			foreach ($_products[0] as $key => $value ) {
				echo '<th>'. $key. '</th>';
			}

			echo '<th>edit</edit>';

			foreach ($_products as $product){
				echo '<tr>';
					foreach ($product as $key => $value) {
						echo '<td>'. $value. '</td>';
					}
					echo '<td><a href="'. $GLOBALS['config']['site_root_url']. '/admin/edit-product/'. $product['id']. '">edit</a></td>';
				echo '</tr>';
			}
		?>

	</table>

<?php }else{ ?>
	
	<p>no products!</p>

<?php } ?>