<?php

// To capture the existing session
session_start([
	'name' => '__Secure-PHPSESSID',
	'cache_limiter' => 'private_no_expire:'
]);

if (isset($_SESSION['authenticated']) && ($_SESSION['authenticated'] == 'true')) {
	Header('Cache-Control "no-cache, no-store, must-revalidate"');
	Header('Pragma "no-cache"');
	Header('Expires 0');
	header('Location: ' . $_SERVER['BASE_PATH']);
}



