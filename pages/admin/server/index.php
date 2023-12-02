<?php

session_start([
	'name' => '__Secure-PHPSESSID',
	'cache_limiter' => 'private_no_expire:'
]);

if (isset($_SESSION['authenticated']) && ($_SESSION['authenticated'] == 'true')) {

	echo print_r($_SERVER, true);

} else {

// BASE_PATH defined in .htaccess:
	header('Location: ' . $_SERVER['BASE_PATH'] . 'admin/login/');

}
