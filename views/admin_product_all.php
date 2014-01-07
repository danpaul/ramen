<?php


if( !View::$template_called )
{
	View::include_template('__admin_main.php', __FILE__);
	return;
}

echo '<div class="small-12 medium-8 large-6 small-centered columns" id="small-page-wrap">';

	if($GLOBALS['FLASH_MESSAGE'])
	{
		foreach ($GLOBALS['FLASH_MESSAGE'] as $message)
		{
			View::make_alert($message);
		}
	}

	if ( !empty(View::$data['products']) ) 

	{

		echo '<table>';
				echo '<thead>';
					echo '<tr>';
						foreach (View::$data['products'][0] as $key => $value )
						{
							echo '<th>'. $key. '</th>';
						}

						echo '<th>edit</th>';
						echo '<th>delete</th>';
					echo '</tr>';
				echo '</thead>';

				foreach (View::$data['products'] as $product)
				{
					echo '<tr>';
						foreach($product as $key => $value)
						{
							echo '<td>'. $value. '</td>';
						}
						echo '<td><a href="'. $GLOBALS['config']['site_root_url']. '/admin/edit-product/'. $product['id']. '">edit</a></td>';
						echo '<td><a href="'. $GLOBALS['config']['site_root_url']. '/admin/delete-product/'. $product['id']. '">delete</a></td>';
					echo '</tr>';
				}

		echo '</table>';

	}else{

		View::make_alert('No products.');
	}

echo '</div>';