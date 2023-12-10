<?php

session_start([
	'name' => '__Secure-PHPSESSID',
	'cache_limiter' => 'private_no_expire:'
]);

$absolute_root = $_SERVER['ABSOLUTE_ROOT'];

$recycle_bin = $absolute_root . 'includes/recycle-bin/';

function delete_folder_contents($folder) {
	try {
		// Now proceed with the main work:
		$files = array_diff(scandir($folder), array('.', '..'));
		foreach ($files as $file) {
			$path = "$folder/$file";
			if ($file !== '.' && $file !== '..') {
				if (is_dir($path)) {
					// Recursive call for subdirectory
					delete_folder_contents($path);
					// Remove the subdirectory itself
					rmdir($path);
				} else {
					// Unlink (delete) file
					unlink($path);
				}
			}
		}

		// If the loop completes without any issues, return true
		return true;
	} catch (Exception $e) {
		// If an exception is caught, return an array with error information
		$lastError = error_get_last();
		return [
			'error' => $e->getMessage(),
			'file' => $lastError['file'],
			'line' => $lastError['line'],
		];
	}
}

$result = delete_folder_contents($recycle_bin);


// Check the result
if ($result === true) {
	$message = 'Deleted contents of <strong>includes/recycle-bin</strong>';
	echo json_encode(array("status" => "ok", "message" => "$message"));
} else {
	// send a 422 Unprocessable Entity header, echo the JSON, and exit:
	http_response_code(422);
	$message = 'Process could not be completed';
	echo json_encode(array("status" => "error", "message" => "$message", "error_details" => $result));
}

// Make sure to exit after sending the response
exit;
