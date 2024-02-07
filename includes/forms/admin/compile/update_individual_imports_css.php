<?php

include './compile_reqs.inc.php';
include_once $absolute_root . 'includes/common/functions.php';

if ($update_individual_imports_css == true) {

	$absolute_root = $_SERVER['ABSOLUTE_ROOT'];
	$assets_folder = $_SERVER['ASSETS_FOLDER'];
	$master_list_template_css = $absolute_root . $assets_folder. 'css/individual-imports.TEMPLATE.css';

	$jsonFilePath = 'file_hashes.json';
	$updated = updateHashInJson($master_list_template_css, $jsonFilePath);
	$message = 'Unchanged.';

	if ($updated == true) {
		$message = 'Done.';

// Make copy of individual-imports.TEMPLATE.css into individual-imports.css in
// which each imported CSS file is auto-versioned:

// Note that this function requests CSS files directly, and thus needs
// $assets_folder prefix on every such call:
		update_individual_imports_css();
	}

	$status_ok = true;

	if ($status_ok) {
		$response_code = 200;
		$status = 'ok';
//		$message = 'Done.';
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
