<?php

include './compile_reqs.inc.php';

if ($update_date_modified == true) {

	$return_value = file_put_contents('dateModified.txt', gmdate(DATE_ATOM));

	$status_ok = ($return_value !== false);

	if ($status_ok) {
		$message = 'Done.';
		echo '{"status": "ok", "message": "' . $message . '"}';
	} else {
// send a 422 Unprocessable Entity header, echo the JSON, and exit:
		http_response_code(422);
		$message = 'Failed.';
		echo json_encode(array("status" => "error", "message" => "$message"));
		exit;
	}
} else {
	$message = 'Not Selected';
	echo '{"status": "ok", "message": "' . $message . '"}';
}
