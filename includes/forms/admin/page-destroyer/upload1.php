<?php

session_start([
	'name' => '__Secure-PHPSESSID',
	'cache_limiter' => 'private_no_expire:'
]);

$absolute_root = $_SERVER['ABSOLUTE_ROOT'];

include_once $absolute_root . 'includes/common/functions.php';
include_once './page_destroyer_functions.inc.php';

$message = '';

function destroy_file($file) {
	return unlink($file);
}

function destroy_folder($folder) {
	$files = array_diff(scandir($folder), array('.', '..'));
	foreach ($files as $file) {
		$path = "$folder/$file";
		if (is_dir($path)) {
// Recursive call for subdirectory
			destroy_folder($path);
		} else {
// Unlink (delete) file
			unlink($path);
		}
	}
// Remove the directory itself
	return rmdir($folder);
}

function reviseSiteData($file, $kabob, $camel) {

// Read the file into an array of lines
	$lines = file($file);

// Define the pattern for three consecutive lines with $kabob and $camel:
	$pattern1 = '/^\s*\'' . preg_quote($kabob, '/') . '\'\s*:\s*\{\s*$/';
	$pattern2 = '/^\s*mjs:\s*\'\.\/' . preg_quote($camel, '/') . '\.mjs\'\s*$/';
	$pattern3 = '/^\s*\},\s*$/';

	$matches_found = false;

// Iterate through the lines to find and remove the specified pattern
	for ($i = 0; $i < count($lines) - 2; $i++) {
		$line1 = trim($lines[$i]);
		$line2 = trim($lines[$i + 1]);
		$line3 = trim($lines[$i + 2]);
		if ((preg_match($pattern1, $line1)) && (preg_match($pattern2, $line2)) && (preg_match($pattern3, $line3))) {
			$matches_found = true;
			unset($lines[$i], $lines[$i + 1], $lines[$i + 2]);
		}
	}

// Write the modified lines back to the file
	$return_value = file_put_contents($file, implode('', $lines));

	if (($return_value !== false) && ($matches_found == true)) {
		return true;
	} else {
		return false;
	}

}

//file_put_contents('x.txt', print_r($_POST['page_info'], true));

$message = '';
$hash_array = [];

if (isset($_POST['page_info']) && is_array($_POST['page_info'])) {
	for ($x = 0; $x < count($_POST['page_info']); $x++) {

// The incoming POST value will be an array of hashes
// Each hash corresponds to one of the strings that contain the page's
// camel, kabob, snake, and name 
		$hash256 = $_POST['page_info'][$x];
		$value = $hash_value_array[$hash256];

		$destruction[$x] = explode('|', $value);
//file_put_contents('x.txt', print_r($destruction[$x], true), FILE_APPEND);

		$camel = trim($destruction[$x][0]);
		$snake = trim($destruction[$x][1]);
		$kabob = trim($destruction[$x][2]);
		$title = trim($destruction[$x][3]);

		$success_count = 0;

/////////////////
// Destroy CSS //
/////////////////

		$file = $absolute_root . 'assets/css/pages/' . $kabob . '.css';
// assume failure & revise when successful:
		$result = '<div><strong>Failed to delete:</strong> ' . $file . '</div>';
// guard against empty entries:
		if ($file !== $absolute_root . 'assets/css/pages/.css') {
			if (destroy_file($file)) {
				$result = '<div><strong>Deleted:</strong> ' . $file . '</div>';
				$success_count += 1;
			}
		}
		$message .= $result;

/////////////////
// Destroy MJS //
/////////////////

		$file = $absolute_root . 'assets/javascript/modules/pages/' . $camel . '.mjs';
// assume failure & revise when successful:
		$result = '<div><strong>Failed to delete:</strong> ' . $file . '</div>';
// guard against empty entries:
		if ($file !== $absolute_root . 'assets/css/pages/.css') {
			if (destroy_file($file)) {
				$result = '<div><strong>Deleted:</strong> ' . $file . '</div>';
				$success_count += 1;
			}
		}
		$message .= $result;

////////////////////
// Destroy Folder //
////////////////////

		$folder = $absolute_root . 'pages/' . $kabob;
// assume failure; revise otherwise:
		$result = '<div><strong>Failed to delete:</strong> ' . $folder . '</div>';
		if ($folder !== $absolute_root . 'pages/') {
			if (destroy_folder($folder)) {
				$result = '<div><strong>Deleted:</strong> ' . $folder . '</div>';
				$success_count += 1;
			}
		}
		$message .= $result;

/////////////////////
// Revise siteData //
/////////////////////

		$file = $absolute_root . 'assets/javascript/modules/siteData.mjs';
// assume failure; revise otherwise:
		$result = '<div><strong>Failed to revise:</strong> ' . $file . '</div>';
		if ($file !== $absolute_root . 'assets/javascript/modules/') {
			if (reviseSiteData($file, $kabob, $camel)) {
				$result = '<div><strong>Revised:</strong> ' . $file . '</div>';
				$success_count += 1;
			}
		}
		$message .= $result;

		if ($success_count == 4) {
			$hash_array[] = $hash256;
		}

		if ($x != (count($_POST['page_info']) - 1)) {
			$message .= '<br>';
		}

	}
}

$json_hash_array = json_encode($hash_array, JSON_FORCE_OBJECT);
$message .= $json_hash_array;

//file_put_contents('x.txt', $message);

$message = addslashes($message);

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

