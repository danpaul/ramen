<?php	
	if( $GLOBALS['FLASH_MESSAGE'] )
	{
		if( is_array($GLOBALS['FLASH_MESSAGE']))
		{
			foreach ($GLOBALS['FLASH_MESSAGE'] as $message)
			{
				echo '<div data-alert class="alert-box">';
					echo $message;
					echo '<a href="#" class="close">&times;</a>';
				echo '</div>';
			}			
		}else{
			echo '<div data-alert class="alert-box">';
				echo $GLOBALS['FLASH_MESSAGE'];
				echo '<a href="#" class="close">&times;</a>';
			echo '</div>';
		}

	}	
?>