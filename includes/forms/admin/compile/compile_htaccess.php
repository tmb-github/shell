<?php

include './compile_reqs.inc.php';

if ($compile_htaccess == true) {

	$htaccess_template = $absolute_root . '.htaccess_template';

	$fp = fopen($htaccess_template, 'r');

	$htaccess = '';
	while (!feof($fp)) {
		$buffer = fgets($fp);
		$trimmed_buffer = trim($buffer);
		if ($trimmed_buffer != '') {
			if (substr($trimmed_buffer, 0, 1) != '#') {
				$htaccess .= $buffer;
			} else {
// On Inmotion Hosting livehost, we need this line in .htaccess to use PECL Imagick:
//
// suPHP_ConfigPath /home/a07af35/public_html
//
// It causes localhost to fail with a 500 server error, so it must not be retained on localhost.
// It's saved this way in the .htaccess_template:
//
// #	suPHP_ConfigPath /home/a07af35/public_html
//
// So, trim off the initial # from the line (all lines at this point in the IF structure
// will begin with at least one #:
				$trim_off_initial_hash = substr($trimmed_buffer, 1);
// Eliminate the white space surrounding it:
				$trim_what_remains = trim($trim_off_initial_hash);
// See if what remains is the text in question:
				if ($trim_what_remains == 'suPHP_ConfigPath /home/a07af35/public_html') {
// Retain it if we're not on localhost:
					if ($localhost == false) {
						$htaccess .= $trim_what_remains . PHP_EOL;
					}
				}
			}
		}
	}
	fclose($fp);

	file_put_contents($absolute_root . '.htaccess', $htaccess);

	$status_ok = true;

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
