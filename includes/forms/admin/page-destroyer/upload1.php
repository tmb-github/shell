<?php

session_start([
	'name' => '__Secure-PHPSESSID',
	'cache_limiter' => 'private_no_expire:'
]);

$absolute_root = $_SERVER['ABSOLUTE_ROOT'];

include_once $absolute_root . 'includes/common/functions.php';
include $absolute_root . 'includes/forms/admin/shared_routines/page_name_variations.inc.php';

// Create recycle bin:
$recycle_bin_path = $absolute_root . 'includes/recycle-bin/';
if (!file_exists($recycle_bin_path)) {
	mkdir($recycle_bin_path, 0777, true); // The third parameter 'true' creates nested directories if they don't exist
}

// Create folder in recycle bin with current date/time stamp as name:
$date_time = date("YmdHis");
$backup_path = $recycle_bin_path . $date_time . '/';
if (!file_exists($backup_path)) {
	mkdir($backup_path, 0777, true); // The third parameter 'true' creates nested directories if they don't exist
}

$message = '';

function destroy_file($file) {
// Make a back-up copy of the file first:
	global $backup_path;
	copy($file, $backup_path . basename($file));
// Now proceed with the main work:
	return unlink($file);
}

function copy_folder($source, $destination) {
	if (!file_exists($destination)) {
		mkdir($destination, 0777, true);
	}
	$dir = opendir($source);
	while (false !== ($file = readdir($dir))) {
		if (($file != '.') && ($file != '..')) {
			$sourceFile = $source . '/' . $file;
			$destinationFile = $destination . '/' . $file;
			if (is_dir($sourceFile)) {
				copy_folder($sourceFile, $destinationFile);
			} else {
				copy($sourceFile, $destinationFile);
			}
		}
	}
	closedir($dir);
}


function destroy_folder($folder) {
// Make a back-up copy of the folder first:
	global $backup_path;
	copy_folder($folder, $backup_path . basename($folder));
// Now proceed with the main work:
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

function reviseSiteDataSAVE($file, $kabob, $camel) {

// Read the file into an array of lines
	$lines = file($file);

// in siteData, we just use the final slug in the URL
// as the key, not any of the folders that precede it,
// so 'compile', not 'admin/compile'

// Check if the string has any forward slashes
	if (strpos($kabob, '/') !== false) {
// Get the text that follows the last forward slash
		$kabob = substr($kabob, strrpos($kabob, '/') + 1);
	}

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

function reviseSiteData($file, $kabob, $camel) {
    // Read the entire file into a string
    $content = file_get_contents($file);

    // in siteData, we just use the final slug in the URL
    // as the key, not any of the folders that precede it,
    // so 'compile', not 'admin/compile'

    // Check if the string has any forward slashes
    if (strpos($kabob, '/') !== false) {
        // Get the text that follows the last forward slash
        $kabob = substr($kabob, strrpos($kabob, '/') + 1);
    }

    // Define the pattern for $kabob and $camel on a single line
//    $pattern = '/\s*\'' . preg_quote($kabob, '/') . '\'\s*:\s*{\s*mjs:\s*\'\.\/' . preg_quote($camel, '/') . '\.mjs\'\s*},?\s*/';
//    $pattern = '/\s*[\'"]' . preg_quote($kabob, '/') . '[\'"]\s*:\s*{\s*mjs:\s*[\'"]\.\/' . preg_quote($camel, '/') . '\.mjs[\'"]\s*},?\s*/';
    $pattern = '/[\'"]' . preg_quote($kabob, '/') . '[\'"]\s*:\s*{\s*mjs:\s*[\'"]\.\/' . preg_quote($camel, '/') . '\.mjs[\'"]\s*}(\s*,)?/';

    $matches_found = false;

    $replacement = PHP_EOL;
    // Apply the pattern to the entire file content
    $content = preg_replace($pattern, $replacement, $content, -1, $count);
    if ($count > 0) {
        $matches_found = true;
    }

    // Write the modified content back to the file
    $return_value = file_put_contents($file, $content);

    if ($return_value !== false && $matches_found) {
        // Remove blank lines
        $lines = file($file);
        $lines = array_filter($lines, function ($line) {
            // Remove lines that are empty or consist only of tabs
            return !preg_match('/^\s*$/', $line);
        });

        // Write the modified lines back to the file
        file_put_contents($file, implode('', $lines));
        
        return true;
    } else {
        return false;
    }
}



$message = '';
$hash_array = [];

// We must make a backup of the siteData file before running the loop,
// else we'll write over the siteData file with each iteration of the loop,
// thus not preserving its original state:
$site_data_mjs = $absolute_root . 'assets/javascript/modules/siteData.mjs';
copy($site_data_mjs, $backup_path . basename($site_data_mjs));

if (isset($_POST['page_info']) && is_array($_POST['page_info'])) {
	for ($x = 0; $x < count($_POST['page_info']); $x++) {

// The incoming POST value will be an array of hashes
// Each hash corresponds to one of the strings that contain the page's
// camel, kabob, snake, and name 
		$hash256 = $_POST['page_info'][$x];
		$value = $hash_value_array[$hash256];

		$destruction[$x] = explode('|', $value);

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


		$message .= '<br>';

	}
}

$message .= '<div><strong>Backup copies of all deleted assets written to:</strong> ' . $backup_path . '</div>';

$json_hash_array = json_encode($hash_array, JSON_FORCE_OBJECT);
$message .= $json_hash_array;

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

