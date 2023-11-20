<?php

// See: https://www.askapache.com/php/php-errordocument/

// From:
// ErrorDocument in .htaccess

$absolute_root = $_SERVER['ABSOLUTE_ROOT'];

// To test headers on localhost, use Powershell command: 
// (invoke-webrequest http://localhost/).headers

$redirect = $_SERVER['REDIRECT_STATUS'];
if (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) {
	$redirect = $_SERVER['QUERY_STRING'];
}

if ($redirect == 400) {
	header("HTTP/1.1 400 Bad Request", true, 400);
} else if ($redirect == 403) {
	header("HTTP/1.1 403 Forbidden", true, 403);
} else if ($redirect == 500) {
	header("HTTP/1.1 403 Internal Server Error", true, 500);
// treat everything else as a 404:
} else {
	header("HTTP/1.1 404 Not Found", true, 404);
}

// we need this here for the $htdocs_folder:
include $absolute_root . "includes/common/domain_info.php";

// Needed by routine that loads the correct <MAIN> element in includes/html/body.php:
if (isset($_SERVER['SERVER_NAME']) && !empty($_SERVER['SERVER_NAME']) && ($_SERVER['SERVER_NAME'] == 'localhost')) {
	$root = '/' . $htdocs_folder . '/';
} else {
	$root = '/';
}
$_SERVER['REQUEST_URI'] = $root . 'error';


include 'index-main-vars.php';
include $absolute_root . 'includes/common/routines.php';
include $absolute_root . 'includes/html/html.php';

?>
