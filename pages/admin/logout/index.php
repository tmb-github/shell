<?php

session_start([
	'name' => '__Secure-PHPSESSID',
	'cache_limiter' => 'private_no_expire:'
]);

$_SESSION = array();
session_destroy();

// BASE_PATH defined in .htaccess:
header('Location: ' . $_SERVER['BASE_PATH']);

?>