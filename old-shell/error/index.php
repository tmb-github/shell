<?php

// this could conditionally branch off to catch different kinds of errors in the future:

header("HTTP/1.1 404 Not Found", true, 404);

$absolute_root = $_SERVER['ABSOLUTE_ROOT'];
include $absolute_root . "domain_info/domain_info.inc.php";

// Needed by routine that loads the correct <MAIN> element in includes/html/body.php:

if (isset($_SERVER['SERVER_NAME']) && !empty($_SERVER['SERVER_NAME']) && ($_SERVER['SERVER_NAME'] == 'localhost')) {
	$root = '/' . $htdocs_folder . '/';
} else {
	$root = '/';
}
$_SERVER['REQUEST_URI'] = $root . 'error-404/';

$absolute_root = $_SERVER['ABSOLUTE_ROOT'];
include $absolute_root . 'error-404/index.php';

exit;

?>
