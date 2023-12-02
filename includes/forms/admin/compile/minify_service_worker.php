<?php 

include './compile_reqs.inc.php';

if ($minify_service_worker == true) {

	$html = '';

	$source = $absolute_root . 'sw.js';
	$destination = $absolute_root . 'sw.min.js';

	if (file_exists($source)) {
		minify_with_closure_compiler($source, $destination);
		if ((filesize($source) == filesize($destination)) && (md5_file($source) == md5_file($destination))) {
			$html .= 'ERROR: Closure Compiler minification failed: ' . $source . '<br>';
		} else {
			$html .= 'SUCCESS: Closure Compiler minified: ' . $source . '<br>';
		}
	} else {
		$html .= $source . ' does not exist . . . procedure aborted.<br>';
	}

	$status_ok = true;

	if ($status_ok) {
		$message = $html . 'Done.';
		echo '{"status": "ok", "message": "' . $message . '"}';
	} else {
// send a 422 Unprocessable Entity header, echo the JSON, and exit:
		http_response_code(422);
		$message = $html . 'Failed.';
		echo json_encode(array("status" => "error", "message" => "$message"));
		exit;
	}
} else {
	$message = 'Not Selected';
	echo '{"status": "ok", "message": "' . $message . '"}';
}
