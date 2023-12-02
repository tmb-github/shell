<?php

session_start([
	'name' => '__Secure-PHPSESSID',
	'cache_limiter' => 'private_no_expire:'
]);

$absolute_root = $_SERVER['ABSOLUTE_ROOT'];
$assets_folder = $_SERVER['ASSETS_FOLDER'];

// STRATEGY:
// The first fetched script will have POST, but not any of the subsequent ones
// in the fetch() chain. So, check for POST, then set the variables to session
// values. Immediately set the named variables in the PHP scripts
// ($minify_scripts, etc.) equal to the equivalent SESSION values, *if* the
// SESSION values are set and true, otherwise set the variables to false. This
// way, for subsequent scripts along the fetch() chain, in which the original
// POST is lost, the variables will still be in SESSION, and those values can
// be assigned from the SESSION values.


if (!isset($skip_compile_reqs) || ($skip_compile_reqs == false)) {

// Explicit check for POST:
	if ($_SERVER["REQUEST_METHOD"] == "POST") {

	// If a checkbox is checked, it will be in the POST, otherwise it will not exist in POST at all:

		$_SESSION['do_not_compile_static_html'] = isset($_POST['do_not_compile_static_html']) ? true : false;
		$_SESSION['do_not_use_static_html'] = isset($_POST['do_not_use_static_html']) ? true : false;
		$_SESSION['minify_scripts'] = isset($_POST['minify_scripts']) ? true : false;
		$_SESSION['minify_modules'] = isset($_POST['minify_modules']) ? true : false;
		$_SESSION['update_site_webmanifest'] = isset($_POST['update_site_webmanifest']) ? true : false;
		$_SESSION['update_browserconfig_xml'] = isset($_POST['update_browserconfig_xml']) ? true : false;
		$_SESSION['update_fontface_css'] = isset($_POST['update_fontface_css']) ? true : false;
		$_SESSION['compile_css'] = isset($_POST['compile_css']) ? true : false;
		$_SESSION['update_individual_imports_css'] = isset($_POST['update_individual_imports_css']) ? true : false;
		$_SESSION['update_service_worker'] = isset($_POST['update_service_worker']) ? true : false;
		$_SESSION['minify_service_worker'] = isset($_POST['minify_service_worker']) ? true : false;
		$_SESSION['compile_htaccess'] = isset($_POST['compile_htaccess']) ? true : false;
		$_SESSION['update_date_modified'] = isset($_POST['update_date_modified']) ? true : false;
	}

	// Next, set the named variables each to their SESSION values
	// If the S
	// That way, when we arrive here via GET on the second JavaScript fetch and
	// beyond, the values will still be set appropriately:

	$do_not_compile_static_html = isset($_SESSION['do_not_compile_static_html']) && $_SESSION['do_not_compile_static_html'] === true ? true : false;
	$do_not_use_static_html = isset($_SESSION['do_not_use_static_html']) && $_SESSION['do_not_use_static_html'] === true ? true : false;
	$minify_scripts = isset($_SESSION['minify_scripts']) && $_SESSION['minify_scripts'] === true ? true : false;
	$minify_modules = isset($_SESSION['minify_modules']) && $_SESSION['minify_modules'] === true ? true : false;
	$update_site_webmanifest = isset($_SESSION['update_site_webmanifest']) && $_SESSION['update_site_webmanifest'] === true ? true : false;
	$update_browserconfig_xml = isset($_SESSION['update_browserconfig_xml']) && $_SESSION['update_browserconfig_xml'] === true ? true : false;
	$update_fontface_css = isset($_SESSION['update_fontface_css']) && $_SESSION['update_fontface_css'] === true ? true : false;
	$compile_css = isset($_SESSION['compile_css']) && $_SESSION['compile_css'] === true ? true : false;
	$update_individual_imports_css = isset($_SESSION['update_individual_imports_css']) && $_SESSION['update_individual_imports_css'] === true ? true : false;
	$update_service_worker = isset($_SESSION['update_service_worker']) && $_SESSION['update_service_worker'] === true ? true : false;
	$minify_service_worker = isset($_SESSION['minify_service_worker']) && $_SESSION['minify_service_worker'] === true ? true : false;
	$compile_htaccess = isset($_SESSION['compile_htaccess']) && $_SESSION['compile_htaccess'] === true ? true : false;
	$update_date_modified = isset($_SESSION['update_date_modified']) && $_SESSION['update_date_modified'] === true ? true : false;
}

// The google-closure-compiler libraries are needed in functions.php, so include them HERE, not in functions.php:
include_once $absolute_root . 'includes/google-closure-compiler/src/Compiler.php';
include_once $absolute_root . 'includes/google-closure-compiler/src/Response.php';
include_once $absolute_root . 'includes/google-closure-compiler/src/exceptions.php';

include_once $absolute_root . 'includes/common/domain_info.php';
include_once $absolute_root . 'includes/common/routines.php';

// Ensure nothing served is minified 
$_SESSION['minify'] = false;

if ($_SERVER['HTTP_HOST'] === 'localhost') {
	$localhost = true;
} else {
	$localhost = false;
}

// To test how it would perform on livehost:
//$localhost = false;
//
// WARNING: Do NOT compile the .htaccess as though on livehost when on
// localhost. There's a remmed-out line in the .htaccess-template that's
// unremmed on livehost that will cause a 500 error when the site is attempted
// to launch on localhost:
//
// suPHP_ConfigPath /home/a07af35/public_html
//
// If you accidentally compile in livehost mode while on localhost and the site
// crashes, this is likely the reason. Delete the line and the site should then
// run.