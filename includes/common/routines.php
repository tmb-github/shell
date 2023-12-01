<?php

$absolute_root = $_SERVER['ABSOLUTE_ROOT'];

/*
int session_status ( void )

session_status() is used to return the current session status.

This returns an integer when you print it, since it is what PHP converts it into.

0 ----> PHP_SESSION_DISABLED if sessions are disabled.
1 ----> PHP_SESSION_NONE if sessions are enabled, but none exists.
2 ----> PHP_SESSION_ACTIVE if sessions are enabled, and one exists.
*/

if (session_status() != 2) {

// **PREVENTING SESSION HIJACKING**
// Prevents javascript XSS attacks aimed to steal the session ID
	ini_set('session.cookie_httponly', 1);

// **PREVENTING SESSION FIXATION**
// Session ID cannot be passed through URLs
	ini_set('session.use_only_cookies', 1);

// Uses a secure connection (HTTPS) if possible
	ini_set('session.cookie_secure', 1);

// SameSite=None: Cookies will be sent in all contexts, i.e., in responses to
// both first-party and cross-origin requests.
//
// NB: If SameSite=None is set, the cookie Secure attribute must also be set
// (or the cookie will be blocked).
//
// SameSite=Strict: Cookies will only be sent in a first-party context and not
// be sent along with requests initiated by third party websites.

	ini_set('session.cookie_samesite', 'Strict');

// Remove X-Powered-By (which exposes the version of PHP running on the server)
	header_remove("X-Powered-By");

// Ensure session cookie is secure; see: https://www.sjoerdlangkemper.nl/2017/02/09/cookie-prefixes/
	session_name('__Secure-PHPSESSID');

// This allows us to override the default Cache-Control header sent by the server;
// without it, "no-cache, no-store, must-revalidate" is sent simply by calling session_start();
// 2021-03-21: This ensures that the cache-control is max-age: 0, immutable
// NB: The directive MUST end in a colon:
	session_cache_limiter('private_no_expire:');

//session_set_cookie_params(['SameSite' => 'None', 'Secure' => true]);

/*
// NB: Now that we've named the session, to start it in AJAXed PHP files subsequently, use:

session_start([
	'name' => '__Secure-PHPSESSID',
	'cache_limiter' => 'private_no_expire:'
]);

// In this PHP script, however, session_start() is all that's needed, as session_name() is called earlier in this script.
*/

	session_start();

}


// Include variables that do NOT rely on functions:
include_once $absolute_root . 'includes/common/variables.php';
include_once $absolute_root . 'includes/common/functions.php';
// Include variables that DO rely on functions:
include_once $absolute_root . 'includes/common/autoversioned_variables.php';


/*****************************************************************
** variables that depend on POST parameters sent during AJAXing **
*****************************************************************/

// In case we're ajaxing a MAIN.PHP using the JavaScript siteWideEdits.js routine,
// we need to indicate whether webp is supported in the calling browser:
if (isset($_POST) && !empty($_POST)) {
	if (isset($_POST['webp'])) {
		if ($_POST['webp'] === 'true') {
			$webp_support = true;
		} else {
			$webp_support = false;
		}
	}
}

// $webp_support is set in variables.php:
// Don't reset this session variable in case the site refreshes with a POST:
if (!isset($_SESSION['webp_support']) && empty($_SESSION['webp_support'])) {
	$_SESSION['webp_support'] = $webp_support;
}


/*****************************************************
** include all PHP files in common utilities folder **
*****************************************************/

// 2023-11-20
// Moved all of those PHP files to utilities subfolder
// Include all of the common PHP files *perhaps* except social-media-data.php file:
foreach (glob($absolute_root . "includes/common/utilities/*.php") as $filename) {
	if ($include_social_media_curl === true) {
		include $filename;
	} else {
// if it's not social-media-data, go ahead and include it:
		if (strpos($filename, 'social-media-data.php') === false) {
			include $filename;
		}
	}
}

/********
** CSP **
********/

// Relies on BROWSER class, which is in 'utility' folder and included in loop above:
include_once $absolute_root . 'includes/common/content_security_policy.php';

/*******************
** Detect AJAXing **
*******************/

// $title is only set in index.php!
//
// If the application gets the main.php file by POST, then $title will not be
// set, which is therefore the flag that we're ajaxing main.php:

if (!isset($title)) {
	$_SESSION['ajax'] = true;
}


/***************************************
** redirect when down for maintenance **
***************************************/

if ($down_for_maintenance == true) {

// BASE_PATH defined in .htaccess:
	header('Location: ' . $_SERVER['BASE_PATH'] . 'down-for-maintenance');

}

/*********************************************************
** Try sending cache control headers when authenticated **
*********************************************************/

if (isset($_SESSION['authenticated']) && ($_SESSION['authenticated'] == 'true')) {

	header("Cache-Control: no-cache, no-store, must-revalidate");
	header("Pragma: no-cache");
	header("Expires: 0");

}


/*************************************************
** Minify when testing mobile mode on localhost **
*************************************************/

$minify_when_testing_mobile_on_localhost = false;

if ($minify_when_testing_mobile_on_localhost == true) {
	// The following CANNOT be run until /common/*.php files are read in above:
	// Class found in includes/common/browser_detection.php:
	$browser = new Browser();

	$_SESSION['initial_device_is_mobile'] = $browser->isMobile();

	if ($_SESSION['initial_device_is_mobile'] == true) {
		$_SESSION['minify'] = true;
	}
}

/*****************************************
** record IP addresses of site visitors **
*****************************************/

$record_ip_addresses_of_site_visitors = false;

if ($record_ip_addresses_of_site_visitors == true) {
	if (isset($_SERVER) && (isset($_SERVER['REMOTE_ADDR'])) && (!empty($_SERVER['REMOTE_ADDR']))) {
		$client_ip = $_SERVER['REMOTE_ADDR'];
	} else {
		$client_ip = 0;
	}
	file_put_contents($absolute_root . 'client_ip.txt', $client_ip . PHP_EOL, FILE_APPEND);
}


