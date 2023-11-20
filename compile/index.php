<?php
session_start();

// NB: LEAVE THESE ON TRUE!
$do_not_compile_static_html = true;
$do_not_use_static_html = true;
//
$compile_scripts = true;
$compile_modules = true;
//
$update_site_webmanifest = true;
$update_browserconfig_xml = true;
$update_fontface_css = true;
$compile_css = true;
$update_individual_imports_css = true;
$update_service_worker = true;
$minify_sw_js = true;
$update_date_modified = true;
$update_htaccess = true;


$absolute_root = $_SERVER['ABSOLUTE_ROOT'];

// The google-closure-compiler libraries are needed in functions.php, so include them HERE, not in functions.php:
include $absolute_root . 'includes/google-closure-compiler/src/Compiler.php';
include $absolute_root . 'includes/google-closure-compiler/src/Response.php';
include $absolute_root . 'includes/google-closure-compiler/src/exceptions.php';

include $absolute_root . 'includes/common/domain_info.php';
include $absolute_root . 'includes/common/routines.php';

// 2023-11-01
// Ensure nothing served is minified 
$_SESSION['minify'] = false;

if ($_SERVER['HTTP_HOST'] === 'localhost') {
	$localhost = true;
} else {
	$localhost = false;
}

echo '<pre>';

/*
echo '=====================' . PHP_EOL;
echo '===== IMPORTANT =====' . PHP_EOL;
echo '=====================' . PHP_EOL;
echo PHP_EOL;
echo 'You MUST compile the individual static HTML pages using the headless Chrome ' . PHP_EOL;
echo 'COMPILE-HTML option after running this routine or else if you enter ' . PHP_EOL;
echo 'use-static-html mode, then the SCRIPT elements will not execute because ' . PHP_EOL;
echo 'the integrity attributes will be wrong, etc.' . PHP_EOL;
echo PHP_EOL;
echo '=====================' . PHP_EOL;
*/

if ($do_not_compile_static_html == true) {
	include 'do_not_compile_static_html.php';
	echo PHP_EOL;
	echo 'Done.' . PHP_EOL;
}

if ($do_not_use_static_html == true) {
	include 'do_not_use_static_html.php';
	echo PHP_EOL;
	echo 'Done.' . PHP_EOL;
}

// Compile index.php pages for /404/localhost/ and /404/livehost/ directories from /error/
// Needed in error/index.php and includes/html/body.php:
$_SESSION['compile'] = 'true';

if ($localhost === true) {
// CHECKED
	if ($compile_scripts == true) {
		echo 'Compiling scripts...' . PHP_EOL;
		include 'minify_scripts_js.php';
		echo 'DONE.' . PHP_EOL;
		echo PHP_EOL;
	}

// CHECKED
	if ($compile_modules == true) {
		echo 'Compiling modules...' . PHP_EOL;
		include 'minify_modules_mjs.php';
		echo 'DONE.' . PHP_EOL;
		echo PHP_EOL;
	}

// 2020-04-01: We apparently are better off using the loader for everything.
// CHECKED
//	echo 'Creating master JS file...' . PHP_EOL;
//	include 'compile_master_min_js_list.php';
//	echo PHP_EOL;


} else {
	echo PHP_EOL;
	echo '=================================================================' . PHP_EOL;
	echo 'Copy compiled .js and .mjs files to server BEFORE running COMPILE' . PHP_EOL;
	echo '=================================================================' . PHP_EOL;
	echo PHP_EOL;
}


// CHECKED
echo 'NOT Compiling custom 404 pages...' . PHP_EOL;
// UNREM THE FOLLOWING TO COMPILE:
//include $absolute_root . 'error/index.php';
//echo 'DONE.' . PHP_EOL;
//echo PHP_EOL;

// NOT-JS-RELATED:
if ($update_site_webmanifest == true) {
	include 'update_site_webmanifest.php';
}
if ($update_browserconfig_xml == true) {
	include 'update_browserconfig_xml.php';
}
// Attend to CSS:
// NB: run update_fontface_css.php BEFORE compile_css.php
if ($update_fontface_css  == true) {
	include 'update_fontface_css.php';
}
if ($compile_css == true) {
	include 'compile_css.php';
}
if ($update_individual_imports_css == true) {
	include 'update_individual_imports_css.php';
}
echo PHP_EOL;

// The service worker must be compiled AFTER all of the JS files have been
// compiled/edited, as well as the site.webmanifest:
if ($update_service_worker == true) {
	include 'update_service_worker.php';
}
if ($minify_sw_js == true) {
	include 'minify_sw_js.php';
}
echo PHP_EOL;

if ($update_date_modified == true) {
	echo 'Updating dateModified.txt . . .' . PHP_EOL;
	file_put_contents('dateModified.txt', gmdate(DATE_ATOM));
	echo PHP_EOL;
}

if ($update_htaccess == true) {
	echo 'Updating .htaccess . . .' . PHP_EOL;
	include 'compile_htaccess.php';
	echo PHP_EOL;
}

echo 'Done.' . PHP_EOL;
echo '</pre>';

session_destroy();

//include 'print_message_to_window.php';