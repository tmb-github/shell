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

include $absolute_root . 'includes/forms/admin/shared_routines/page_name_variations.inc.php';

/////////////////
// GET POST DATA:
/////////////////

$slug = $_POST['slug'];
$title = $_POST['title'];

// To catch apostrophes, etc.:
$title = escape_ascii_string($title);

$status_ok = true;
$reasons = '';

// qwer
$title_prefix = '';
$slug_prefix = '';
if (isset($_POST['admin']) && !empty($_POST['admin'])) {
	$title_prefix = 'Admin: ';
	$slug_prefix = 'admin/';
}

$full_slug = $slug_prefix . $slug;
$full_title = $title_prefix . $title;

///////////////////////////////////
// Does this title already exist? //
///////////////////////////////////

if (in_array($full_slug, $camel_case_array)) {
	$status_ok = false;
	$reasons .= '<br>Page slug (' . $full_slug . ') already exists among current pages.';
}

if ($status_ok) {
	if (in_array($full_title, $title_case_array)) {
		$status_ok = false;
		$reasons .= '<br>Page title (' . $full_title . ') already exists among current pages.';
	}
}

// If not, go ahead and make a folder for it:

$directory_path = $absolute_root . 'pages/' . $full_slug . '/';

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
	$return = generate_css_array_inc_php($directory_path . 'css_array.inc.php', $full_slug);
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
	$return = write_index_main_vars_php($directory_path . 'index-main-vars.php', $slug);
	if (!$return) {
		$reasons .= $return;
		$status_ok = false;
	}
}

///////////////////
// Make main.php //
///////////////////

if ($status_ok) {
	$return = write_main_php($directory_path . 'main.php', $title);
	if (!$return) {
		$reasons .= $return;
		$status_ok = false;
	}
}


////////////////////////////////////////
// Make meta-schema-overrides.inc.php //
////////////////////////////////////////

if ($status_ok) {
	$return = write_meta_schema_overrides_inc_php($directory_path . 'meta-schema-overrides.inc.php', $title);
	if (!$return) {
		$reasons .= $return;
		$status_ok = false;
	}
}

///////////////////
// Make CSS file //
///////////////////

$css_path = $absolute_root . 'assets/css/pages/' . $full_slug . '.css';

if ($status_ok) {
	$return = write_css_file($css_path, $slug);
	if (!$return) {
		$reasons .= $return;
		$status_ok = false;
	}
}

///////////////////
// Make mjs file //
///////////////////

$camel_cased_name = kebab_to_camel_case($slug);

$mjs_path = $absolute_root . 'assets/javascript/modules/pages/' . $slug_prefix . $camel_cased_name . '.mjs';

if ($status_ok) {
	$return = write_mjs_file($mjs_path, $camel_cased_name, $slug, $title);
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
	$return = modify_site_data_mjs($site_data_mjs_path, $slug, $slug_prefix . $camel_cased_name);
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
