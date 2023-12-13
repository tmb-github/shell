<?php

include './compile_reqs.inc.php';

if ($do_not_compile_static_html == true) {

	$php_text = '<?php' . PHP_EOL . '$compile_static_html = false;';

	$return_value = file_put_contents($absolute_root . 'includes/common/utilities/compile_static_html.inc.php', $php_text);

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
