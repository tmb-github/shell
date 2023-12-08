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

function destroyFolder($folder) {
	$absolute_root = $_SERVER['ABSOLUTE_ROOT'];
	$dir_path = $absolute_root . 'pages/' . $folder;
// Check if the directory exists
	if (is_dir($dir_path)) {
// Open the directory
		$dir_handle = opendir($dir_path);
// Loop through the directory
		while (($file = readdir($dir_handle)) !== false) {
			if ($file != "." && $file != "..") {
				$file_path = $dir_path . '/' . $file;
// Recursively remove files and subdirectories
				if (is_dir($file_path)) {
// Check if the recursive operation failed
					if (!destroyFolder($folder . '/' . $file)) {
						closedir($dir_handle);
						return false; // Failure
					}
				} else {
// Attempt to unlink the file
					if (!unlink($file_path)) {
						closedir($dir_handle);
						return false; // Failure
					}
				}
			}
		}
// Close the directory handle
		closedir($dir_handle);
// Attempt to remove the directory itself
		if (!rmdir($dir_path)) {
			return false; // Failure
		}
// Return true if all operations were successful
		return $dir_path; // Success
	} else {
// Return false if the directory doesn't exist
		return false; // Failure
	}
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

if (isset($_POST['page_info']) && is_array($_POST['page_info'])) {
	for ($x = 0; $x < count($_POST['page_info']); $x++) {
		$hash256 = $_POST['page_info'][$x];
		$value = $hash_value_array[$hash256];
		
		$destruction[$x] = explode('|', $value);

		$camel = $destruction[0];
		$snake = $destruction[1];
		$kabob = $destruction[2];
		$page_name = $destruction[3];

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

		$status_ok = destroyFolder($kabob);
		if ($status_ok) {
			$message .= 'Deleted Module: ' . $status_ok . '<br>';
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

