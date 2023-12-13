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
		$response_code = 200;
		$status = 'ok';
		$message = $html . 'Done.';
	} else {
		$response_code = 422;
		$status = 'error';
		$message = $html . 'Failed.';
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
