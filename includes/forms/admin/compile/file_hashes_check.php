<?php

include './compile_reqs.inc.php';

$file_hashes = 'file_hashes.json';
$status_ok = true;

if (!file_exists($file_hashes)) {
	$status_ok = file_put_contents('file_hashes.json', '{}');
}

if ($status_ok) {
	$response_code = 200;
	$status = 'ok';
	$message = 'Done.';
} else {
	$response_code = 422;
	$status = 'error';
	$message = 'Failed.';
}

header('Content-Type: application/json');
http_response_code($response_code);
echo json_encode(array("status" => "$status", "message" => "$message"));
exit;
