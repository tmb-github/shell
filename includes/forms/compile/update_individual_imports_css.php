<?php

include 'compile_reqs.inc.php';

if ($update_individual_imports_css == true) {

// Make copy of individual-imports.TEMPLATE.css into individual-imports.css in
// which each imported CSS file is auto-versioned:

	update_individual_imports_css();

	$status_ok = true;

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
