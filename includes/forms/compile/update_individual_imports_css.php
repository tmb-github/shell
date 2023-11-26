<?php

include 'compile_reqs.inc.php';
include_once $absolute_root . 'includes/common/functions.php';

if ($update_individual_imports_css == true) {

// Make copy of individual-imports.TEMPLATE.css into individual-imports.css in
// which each imported CSS file is auto-versioned:

// Note that this function requests CSS files directly, and thus needs
// $assets_folder prefix on every such call:
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
