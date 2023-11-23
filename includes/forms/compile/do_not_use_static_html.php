<?php

include 'compile_reqs.inc.php';

file_put_contents('xyz.txt', 'wow');

if ($do_not_use_static_html == true) {


	$php_text = '<?php' . PHP_EOL . '$use_static_html = false;';
	$return_value = file_put_contents($absolute_root . 'includes/common/utilities/use_static_html.inc.php', $php_text);

	$status_ok = ($return_value !== false);

	if ($status_ok) {
		$message = 'Done.';
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

