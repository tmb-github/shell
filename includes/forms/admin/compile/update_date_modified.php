<?php

include './compile_reqs.inc.php';

if ($update_date_modified == true) {

	$return_value = file_put_contents('dateModified.txt', gmdate(DATE_ATOM));

	$status_ok = ($return_value !== false);

	if ($status_ok) {
		$response_code = 200;
		$status = 'ok';
		$message = 'Done.';
	} else {
		$response_code = 422;
		$status = 'error';
		$message = 'Failed.';
	}
} else {
	$response_code = 200;
	$status = 'ok';
	$message = 'Not Selected';
}

header('Content-Type: application/json');
http_response_code($response_code);
echo json_encode(array("status" => "$status", "message" => "$message"));
exit;
