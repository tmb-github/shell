<?php

session_start([
	'name' => '__Secure-PHPSESSID',
	'cache_limiter' => 'private_no_expire:'
]);

$absolute_root = $_SERVER['ABSOLUTE_ROOT'];

include_once $absolute_root . 'includes/common/functions.php';
include_once './page_maker_functions.inc.php';

////////////////////////////////////////
// Retrieve existing page information //
////////////////////////////////////////

$subfolders = get_all_subfolders($absolute_root . 'pages/');

////////////////////
// Get slug_array //
////////////////////

$existing_slug_array = array();
foreach ($subfolders as $path) {
	if (strpos($path, "/pages/") !== false) {
		$parts = explode('/', rtrim($path, '/'));
		$existing_slug_array[] = end($parts);
	}
}
sort($existing_slug_array);
// eliminate duplicate entries:
$existing_slug_array = array_unique($existing_slug_array);


/////////////////////////
// Get camelcase_array //
/////////////////////////

$existing_camelcase_array = array();
foreach ($subfolders as $path) {
	if (strpos($path, "/pages/") !== false) {
		$parts = explode('/', rtrim($path, '/'));
		$lastPart = end($parts);
// Omit hyphens and uppercase the letter that follows the hyphen
		$formattedString = preg_replace_callback('/-(.)/', function($matches) {
			return strtoupper($matches[1]);
		}, $lastPart);
		$existing_camelcase_array[] = $formattedString;
	}
}
sort($existing_camelcase_array);
// eliminate duplicate entries:
$existing_camelcase_array = array_unique($existing_camelcase_array);

////////////////////////
// Get pagename_array //
////////////////////////

$existing_page_name_array = array();
foreach ($subfolders as $path) {
	if (strpos($path, "/pages/") !== false) {
		$parts = explode('/', rtrim($path, '/'));
		$lastPart = end($parts);
// Replace hyphens with spaces and capitalize all resulting words
		$formattedString = ucwords(str_replace('-', ' ', $lastPart));
		$existing_page_name_array[] = $formattedString;
	}
}
sort($existing_page_name_array);
// eliminate duplicate entries:
$existing_page_name_array = array_unique($existing_page_name_array);

/////////////////
// GET POST DATA:
/////////////////

$page_slug = $_POST['page_slug'];
$page_name = $_POST['page_name'];

// To catch apostrophes, etc.:
$page_name = escape_ascii_string($page_name);

$status_ok = true;
$reasons = '';

// qwer
$admin = '';
$admin_slug = '';
if (isset($_POST['admin_page']) && !empty($_POST['admin_page'])) {
	$admin = 'admin';
	$admin_slug = 'admin/';
}

///////////////////////////////////
// Does this page already exist? //
///////////////////////////////////

if (in_array($page_slug, $existing_slug_array)) {
	$status_ok = false;
	$reasons .= '<br>Page slug (' . $page_slug . ') already exists among current pages.';
}

if ($status_ok) {
	if (in_array($page_name, $existing_page_name_array)) {
		$status_ok = false;
		$reasons .= '<br>Page name (' . $page_name . ') already exists among current pages.';
	}
}

// If not, go ahead and make a folder for it:

$directory_path = $absolute_root . 'pages/' . $admin_slug . $page_slug . '/';

if ($status_ok) {
	if (!file_exists($directory_path)) {
		mkdir($directory_path, 0777, true); // The third parameter 'true' creates nested directories if they don't exist
	} else {
		$status_ok = false;
		$reasons .= 'Directory already exists';
	}
}

////////////////////////////
// Make css_array.inc.php //
////////////////////////////

if ($status_ok) {
	$return = generate_css_array_inc_php($directory_path . 'css_array.inc.php', $admin_slug . $page_slug);
	if (!$return) {
		$reasons .= $return;
		$status_ok = false;
	}
}

////////////////////
// Make index.php //
////////////////////

if ($status_ok) {
	$return = write_index_php($directory_path . 'index.php');
	if (!$return) {
		$reasons .= $return;
		$status_ok = false;
	}
}

//////////////////////////////
// Make index-main-vars.php //
//////////////////////////////

if ($status_ok) {
	$return = write_index_main_vars_php($directory_path . 'index-main-vars.php', $page_slug);
	if (!$return) {
		$reasons .= $return;
		$status_ok = false;
	}
}

///////////////////
// Make main.php //
///////////////////

if ($status_ok) {
	$return = write_main_php($directory_path . 'main.php', $page_name);
	if (!$return) {
		$reasons .= $return;
		$status_ok = false;
	}
}


////////////////////////////////////////
// Make meta-schema-overrides.inc.php //
////////////////////////////////////////

if ($status_ok) {
	$return = write_meta_schema_overrides_inc_php($directory_path . 'meta-schema-overrides.inc.php', $page_name);
	if (!$return) {
		$reasons .= $return;
		$status_ok = false;
	}
}

///////////////////
// Make CSS file //
///////////////////

$css_path = $absolute_root . 'assets/css/pages/' . $admin_slug . $page_slug . '.css';

if ($status_ok) {
	$return = write_css_file($css_path, $page_slug);
	if (!$return) {
		$reasons .= $return;
		$status_ok = false;
	}
}

///////////////////
// Make mjs file //
///////////////////

$camel_cased_name = kebab_to_camel_case($page_slug);

$mjs_path = $absolute_root . 'assets/javascript/modules/pages/' . $admin_slug . $camel_cased_name . '.mjs';

if ($status_ok) {
	$return = write_mjs_file($mjs_path, $camel_cased_name, $page_slug, $page_name);
	if (!$return) {
		$reasons .= $return;
		$status_ok = false;
	}
}

///////////////////////
// Edit siteData.mjs //
///////////////////////

$site_data_mjs_path = $absolute_root . 'assets/javascript/modules/siteData.mjs';

if ($status_ok) {
	$return = modify_site_data_mjs($site_data_mjs_path, $page_slug, $admin_slug . $camel_cased_name);
	if (!$return) {
		$reasons .= $return;
		$status_ok = false;
	}
}

//////////
// FINISH:
//////////

if ($status_ok) {
	$message = 'Everything ok.';
	echo '{"status": "ok", "message": "' . $message . '"}';
} else {
// send a 422 Unprocessable Entity header, echo the JSON, and exit:
	http_response_code(422);
	$message = 'Page could not be created: ' . $reasons;
	echo json_encode(array("status" => "error", "message" => "$message"));
}

exit;
