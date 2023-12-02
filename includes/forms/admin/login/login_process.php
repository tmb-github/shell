<?php

// To capture the existing session
session_start([
	'name' => '__Secure-PHPSESSID',
	'cache_limiter' => 'private_no_expire:'
]);

if (isset($_POST['username']) && isset($_POST['password'])) {
/* Define username and associated password array */
	$logins = array('admin' => 'abc123');

/* Check and assign submitted Username and Password to new variable */
	$username = isset($_POST['username']) ? $_POST['username'] : '';
	$password = isset($_POST['password']) ? $_POST['password'] : '';

/* Check Username and Password existence in defined array */
	if (isset($logins[$username]) && $logins[$username] == $password) {
/* Success: Set session variables and redirect to home page by sending true as response */
		$_SESSION['authenticated'] = 'true';
		print_r('true');
	} else {
		print_r('false');
	}
}



