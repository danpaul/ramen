<?php

$foo = 'bar';


if(session_id() == ''){ session_start(); }

$flash_message = 'flash';

// $flash_message = NULL;

// if (isset($_SESSION['flash_message']))
// {
// 	$flash_message = $_SESSION['flash_message'];
// 	$_SESSION['flash_message'] = NULL;
// }

//$_SESSION['flash_message'] = 'foo';

