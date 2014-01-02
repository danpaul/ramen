<?php

	$messages = isset(View::$data['messages']) ? View::$data['messages'] : $GLOBALS['FLASH_MESSAGE'];

	if( $messages )
	{
		if( is_array($messages) )
		{
			foreach ($messages as $message)
			{
				echo '<div data-alert class="alert-box">';
					echo $message;
					echo '<a href="#" class="close">&times;</a>';
				echo '</div>';
			}			
		}else{
			echo '<div data-alert class="alert-box">';
				echo $messages;
				echo '<a href="#" class="close">&times;</a>';
			echo '</div>';
		}
	}

?>