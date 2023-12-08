<?php

session_start([
	'name' => '__Secure-PHPSESSID',
	'cache_limiter' => 'private_no_expire:'
]);

$absolute_root = $_SERVER['ABSOLUTE_ROOT'];

include_once $absolute_root . 'includes/common/functions.php';
include_once './page_destroyer_functions.inc.php';

$message = '';

function destroyModule($module) {
	$absolute_root = $_SERVER['ABSOLUTE_ROOT'];
	$file_path = $absolute_root . 'assets/javascript/modules/pages/' . $module . '.mjs';
// Attempt to delete the file
	$result = unlink($file_path);
// Check for success or failure
	if ($result) {
		return $file_path; // Success
	} else {
		return false; // Failure
	}
}

function destroyCss($css) {
	$absolute_root = $_SERVER['ABSOLUTE_ROOT'];
	$file_path = $absolute_root . 'assets/css/pages/' . $css . '.css';
// Attempt to delete the file
	$result = unlink($file_path);
// Check for success or failure
	if ($result) {
		return $file_path; // Success
	} else {
		return false; // Failure
	}
}

function destroyFolder($dir) {
	$files = array_diff(scandir($dir), array('.', '..'));
	foreach ($files as $file) {
		$path = "$dir/$file";
		if (is_dir($path)) {
// Recursive call for subdirectory
			destroyFolder($path);
		} else {
// Unlink (delete) file
			unlink($path);
		}
	}
// Remove the directory itself
	return rmdir($dir);
}

function reviseSiteData($kabob, $camel) {

	$absolute_root = $_SERVER['ABSOLUTE_ROOT'];
	$filepath = $absolute_root . 'assets/javascript/modules/siteData.mjs';

	// Read the file into an array of lines
	$lines = file($filepath);


	// Define the pattern for three consecutive lines with XXX and YYY
	$pattern1 = '/^\s*\'' . preg_quote($kabob, '/') . '\'\s*:\s*\{\s*$/';
	$pattern2 = '/^\s*mjs:\s*\'\.' . preg_quote($camel, '/') . '\.mjs\'\s*$/';
	$pattern3 = '/^\s*\},\s*$/';
	$combinedPattern = $pattern1 . '|' . $pattern2 . '|' . $pattern3;

	// Iterate through the lines to find and remove the specified pattern
	for ($i = 0; $i < count($lines) - 2; $i++) {
		$line1 = trim($lines[$i]);
		$line2 = trim($lines[$i + 1]);
		$line3 = trim($lines[$i + 2]);

		// Check if the pattern matches all three lines
		if (preg_match($combinedPattern, $line1) && preg_match($combinedPattern, $line2) && preg_match($combinedPattern, $line3)) {
			// Remove the three consecutive lines
			unset($lines[$i], $lines[$i + 1], $lines[$i + 2]);
		}
	}

// Write the modified lines back to the file
	$result = file_put_contents($filepath, implode('', $lines));

// Check for success or failure
	if ($result !== false) {
		return $filepath; // Success
	} else {
		return false; // Failure
	}

}

//file_put_contents('x.txt', print_r($_POST['page_info'], true));

$message = '';
if (isset($_POST['page_info']) && is_array($_POST['page_info'])) {
	for ($x = 0; $x < count($_POST['page_info']); $x++) {
		$hash256 = $_POST['page_info'][$x];
		$value = $hash_value_array[$hash256];
		
		$destruction[$x] = explode('|', $value);
//file_put_contents('x.txt', print_r($destruction[$x], true), FILE_APPEND);
		$camel = $destruction[$x][0];
		$snake = $destruction[$x][1];
		$kabob = $destruction[$x][2];
		$page_name = $destruction[$x][3];

		$message .= $camel . ' ' . $snake . ' ' . $kabob . ' ' . $page_name . PHP_EOL;
//file_put_contents('x.txt', $message, FILE_APPEND);

		$absolute_root = $_SERVER['ABSOLUTE_ROOT'];
		$dir = $absolute_root . 'pages/' . $kabob;
//file_put_contents('x.txt', $dir, FILE_APPEND);


		$status_ok = destroyModule($camel);
		if ($status_ok) {
			$message .= 'Deleted Module: ' . $status_ok . '<br>';
		} else {
			$message .= 'Failed to delete Module: ' . $camel . '.mjs<br>';
		}

		$status_ok = destroyCss($kabob);
		if ($status_ok) {
			$message .= 'Deleted CSS: ' . $status_ok . '<br>';
		} else {
			$message .= 'Failed to delete CSS: ' . $kabob . '.css<br>';
		}

		$absolute_root = $_SERVER['ABSOLUTE_ROOT'];
		$dir = $absolute_root . 'pages/' . $kabob;
		if (destroyFolder($dir)) {
			$message .= 'Deleted folder: ' . $dir . '<br>';
		} else {
			$message .= 'Failed to delete folder: ' . $kabob . '<br>';
		}

		$status_ok = reviseSiteData($kabob, $camel);
		if ($status_ok) {
			$message .= 'Revised siteData.mjs<br>';
		} else {
			$message .= 'Failed to revise siteData.mjs<br>';
		}

	}
}

//file_put_contents('x.txt', $message);

$status_ok = true;
if ($status_ok) {
//	$message = 'Everything ok.';
	echo '{"status": "ok", "message": "' . $message . '"}';
} else {
// send a 422 Unprocessable Entity header, echo the JSON, and exit:
	http_response_code(422);
	$message = 'Process could not be completed';
	echo json_encode(array("status" => "error", "message" => "$message"));
	exit;
}

