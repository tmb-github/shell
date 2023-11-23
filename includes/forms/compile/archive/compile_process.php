<?php

// To capture the existing session
session_start([
	'name' => '__Secure-PHPSESSID',
	'cache_limiter' => 'private_no_expire:'
]);

		print_r('true');
//		print_r('false');

?>