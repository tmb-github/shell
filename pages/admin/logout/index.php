<?php

session_start([
	'name' => '__Secure-PHPSESSID',
	'cache_limiter' => 'private_no_expire:'
]);

$_SESSION = array();
session_destroy();

/*

// Part of attempt to make JS/MJS of newly created pages run
// upon first navigation to the page:

$absolute_root = $_SERVER['ABSOLUTE_ROOT'];

// Create cache_control_on
$cache_control_on = $absolute_root . 'cache_control_on';
if (!file_exists($cache_control_on)) {
	file_put_contents($cache_control_on, '');
}

// Delete cache_control_off
$cache_control_off = $absolute_root . 'cache_control_off';
if (file_exists($cache_control_off)) {
	unlink($cache_control_off);
}
*/

// BASE_PATH defined in .htaccess:
header('Location: ' . $_SERVER['BASE_PATH']);

