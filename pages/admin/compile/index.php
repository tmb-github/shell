<?php

session_start([
	'name' => '__Secure-PHPSESSID',
	'cache_limiter' => 'private_no_expire:'
]);

if (isset($_SESSION['authenticated']) && ($_SESSION['authenticated'] == 'true')) {

	$absolute_root = $_SERVER['ABSOLUTE_ROOT'];

	include 'index-main-vars.php';

	include $absolute_root . 'includes/common/routines.php';
	include $absolute_root . 'includes/components/html.php';

} else {

// BASE_PATH defined in .htaccess:
	header('Location: ' . $_SERVER['BASE_PATH'] . 'admin/login/');

}

