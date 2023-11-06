<?php

session_start([
	'name' => '__Secure-PHPSESSID',
	'cache_limiter' => 'private_no_expire:'
]);

$_SESSION = array();
session_destroy();

header("Location: ../");

?>