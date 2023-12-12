<?php

session_start([
	'name' => '__Secure-PHPSESSID',
	'cache_limiter' => 'private_no_expire:'
]);

if (isset($_POST['username']) && isset($_POST['password'])) {

// Define username and associated hashed password array
//
// password was hashed with:
// password_hash($password, PASSWORD_DEFAULT);

	$logins = array('admin' => '$2y$10$DbVT0ZV/JADbie7a1J/76OmpE9O8c/1zmU5EsJ5FmkkJraWH9qM4u'); 

// Check and assign submitted Username and Password to new variables
	$username = isset($_POST['username']) ? $_POST['username'] : '';
	$password = isset($_POST['password']) ? $_POST['password'] : '';

// Check Username and Password existence in the defined array
	if (isset($logins[$username]) && password_verify($password, $logins[$username])) {
// Success: Set session variables and redirect to the home page by sending 'true' as a response
		$_SESSION['authenticated'] = true;

// To allow for maintenance mode
		setcookie('authenticated', 'true', time() + 3600, '/', '', true, false); // Change the expiration time as needed

		echo json_encode(['status' => 'true']);
	} else {
// Failure: Print 'false' without revealing specific details
		echo json_encode(['status' => 'false']);
	}
}

// Make sure to exit after sending the response
exit;