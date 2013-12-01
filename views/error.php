<?php

require_once($GLOBALS['config']['views']. '/_head.php');

//Error messages should be stored in `FLASH_MESSAGE` as an array
if(isset($GLOBALS['FLASH_MESSAGE']))
{
	foreach ($GLOBALS['FLASH_MESSAGE'] as $error){
		echo '<p>'. $error. '</p>';
	}
}else{
	echo '<p>An error occured while processing your request. Please try again.</p>';
}