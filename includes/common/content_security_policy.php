<?php

/*********************************
** CSP: CONTENT SECURITY POLICY **
*********************************/

// reCAPTCHA and HONEYPOT code in this file; rem/unrem each appropriately

function generateRandomString($length = 10) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}

// return ' nonce-sha256-############################################', etc., unless $hash is empty string:
function hash_format($hash) {
	$formatted = $hash ? " '" . $hash . "'" : "";
	return $formatted;
}

// The nonce remains constant by virtue of storing it in a session variable:
// This is necessary for getNonce.mjs to return the same nonce that was
// sent originally.
if ((isset($_SESSION['nonce'])) && (!empty($_SESSION['nonce']))) {
	$nonce = $_SESSION['nonce'];
} else {
	$random_string = generateRandomString();
	$hash = hash('sha256', $random_string, true);
	$nonce = base64_encode($hash);
	$_SESSION['nonce'] = $nonce;
}

// We need to remove the final '=' from nonce, but for it to be a valid base64 number,
// the number of characters should be in multiples of 4, so delete the last 4 characters, not just the final "=":
$nonce = substr($nonce, 0, -4);
$nonce_src = hash_format('nonce-' . $nonce);
$inline_nonce_property = 'nonce="' . $nonce . '"';
$inline_data_nonce_property = 'data-nonce="' . $nonce . '"';

// $beautified_script_sri = hash_format($beautified_index_script_sri);
// $minified_script_sri = hash_format($minified_index_script_sri);
// $minified_style_sri = hash_format($minified_index_style_sri);
// $script_src = "script-src 'self' http: https:" . $nonce_src . $beautified_script_sri . $minified_script_sri . " 'strict-dynamic' ; ";

// 2021-02-11:
// Use info here to determine whether strict-dynamic and style-src may be sent;
// this will require identifying browser by UA string:
// https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy#browser_compatibility

// Chrome >= 52
// Edge >= 79
// Firefox >= 52
// Opera >= 39
// Webview Android >= 52
// Chrome Android >= 52
// Opera Android >= 41
// Samsung Internet >= 6.0

$browser = new Browser();

$_SESSION['browser'] = $browser->getBrowser();
$_SESSION['browser_version'] = $browser->getVersion();
$_SESSION['browser_mobile'] = $browser->isMobile();

/*
echo '<pre>';
echo print_r($_SESSION['browser'], true) . PHP_EOL;
echo print_r($_SESSION['browser_mobile'], true) . PHP_EOL;
echo print_r($_SESSION['browser_version'], true) . PHP_EOL;
exit;
*/

/************************************************
** DETERMINE STRICT DYNAMIC SUPPORT BY BROWSER **
************************************************/

$strict_dynamic_support = false;
$firefox_browser = false;

if ($browser->isMobile() == true) {
	if (($browser->getBrowser() == Browser::BROWSER_ANDROID) && ($browser->getVersion() >= 52)) {
		$strict_dynamic_support = true;
	}
	if (($browser->getBrowser() == Browser::BROWSER_CHROME) && ($browser->getVersion() >= 52)) {
		$strict_dynamic_support = true;
	}
	if (($browser->getBrowser() == Browser::BROWSER_OPERA) && ($browser->getVersion() >= 41)) {
		$strict_dynamic_support = true;
	}
	if (($browser->getBrowser() == Browser::BROWSER_SAMSUNG) && ($browser->getVersion() >= 6.0)) {
		$strict_dynamic_support = true;
	}
} else {
	if (($browser->getBrowser() == Browser::BROWSER_CHROME) && ($browser->getVersion() >= 52)) {
		$strict_dynamic_support = true;
	}
	if (($browser->getBrowser() == Browser::BROWSER_EDGE) && ($browser->getVersion() >= 79)) {
		$strict_dynamic_support = true;
	}
	if (($browser->getBrowser() == Browser::BROWSER_FIREFOX) && ($browser->getVersion() >= 52)) {
		$strict_dynamic_support = true;
		$firefox_browser = true;
	}
	if (($browser->getBrowser() == Browser::BROWSER_OPERA) && ($browser->getVersion() >= 39)) {
		$strict_dynamic_support = true;
	}
}

$safari_browser_without_strict_dynamic_support = false;
if (($browser->getBrowser() == Browser::BROWSER_SAFARI) && ($browser->getVersion() >= 1)) {
	$safari_browser_without_strict_dynamic_support = true;
}
$safari_browser_without_worker_src_support = false;
if (($browser->getBrowser() == Browser::BROWSER_SAFARI) && ($browser->getVersion() >= 1)) {
	$safari_browser_without_worker_src_support = true;
}

// ecwid
if ((isset($_SESSION['ecwid'])) && ($_SESSION['ecwid'] == 'true')) {
	$ecwid = true;
} else {
	$ecwid = false;
}


// See https://developer.okta.com/blog/2021/10/18/security-headers-best-practices


/***************
** script-src **
***************/

if ($ecwid == true) {
	$ecwid_sha_1 = "'sha256-47DEQpj8HBSa+/TImW+5JCeuQeRkm5NMpJWZG3hSuFU='";
	$ecwid_sha_2 = "'sha256-ymumcoYI7TRO8OCEk3dBZ7jMATXhsF91Thbhlih2Zj8='";
	$unsafe_eval = "'unsafe-eval'";
	$ecwid_script_src = ' ' . $ecwid_sha_1 . ' ' . $ecwid_sha_2 . ' ' . $unsafe_eval;
} else {
	$ecwid_script_src = '';
}

// We may be able to use 'strict-dynamic' but with one exception: 'unsafe-eval'
$special_ecwid_test = true;

// If we weren't having to accommodate eval(), we could use:
// 	$ecwid_script_src = '';
if ($special_ecwid_test == true) {
	$ecwid_script_src = " 'unsafe-eval'";
}

// For displaying the error message re: ru.cdev.xnext.legacyfrontendstub.LegacyFrontendStub-0.js
// $ecwid_script_src = "";

if ($strict_dynamic_support == true) {

// Google's CSP evaluator wants fallbacks when using strict-dynamic: https://csp-evaluator.withgoogle.com/:
// Security Headers (https://securityheaders.com) is okay with the fallbacks, too.
// However, Firefox complains if the fallbacks are included!
// So, let's use the fallbacks for all browsers that support strict-dynamic EXCEPT for Firefox:

	if ($firefox_browser == true) {
		$script_src = "script-src" . $nonce_src . $ecwid_script_src . " 'strict-dynamic' ; ";
	} else {
// All other browsers that support strict-dynamic:
		$script_src = "script-src 'unsafe-inline' 'self' http: https:" . $nonce_src . $ecwid_script_src . " 'strict-dynamic' ; ";
	}

} else {

// Let's include strict-dynamic with fallbacks for other browsers that we haven't filtered, in case some do 
// (or eventually do, after this code is written):
	$script_src = "script-src 'unsafe-inline' 'self' http: https:" . $nonce_src . $ecwid_script_src . " 'strict-dynamic' ; ";

// Safari complains loudly about not recognizing strict-dynamic, so appease it for now:
//
// 2021-04-12: BUT...we need the nonce ANYWAY...the revision of the nonce
// routine requires a nonce to be present on script-src, or else it will fail
// on Safari:
	if ($safari_browser_without_strict_dynamic_support) {
		$script_src = "script-src 'unsafe-inline' 'self' http: https:" . $nonce_src . $ecwid_script_src . " ; ";
	}

}

$script_src_save = $script_src;

// This can be eliminated if the nonce is added to the Ecwid script in merchandise.mjs:
// (But what about the unsafe-inlines and other things?)
if ($ecwid == true) {
	$script_src = "script-src 'self' 'unsafe-eval' 'unsafe-inline' https://*.ecwid.com https://*.cloudfront.net https://ecomm.events https://*.hcaptcha.com https://hcaptcha.com https://*.squareup.com https://www.googletagmanager.com https://www.google-analytics.com ; ";
}

if ($special_ecwid_test == true) {
	$script_src = $script_src_save;
}


/**************
** style-src **
**************/

// NB: SET TO FALSE TO ENFORCE style-src CSP!
$style_src_unsafe_inline = false;

// FOR TESTING:
//$style_src_unsafe_inline = true;

if ($ecwid == true) {
	$ecwid_style_src = " https://*.cloudfront.net";
} else {
	$ecwid_style_src = '';
}

// There are inline styles set in the TINYMCE editor that I can't get Chrome to accept, even with the SHA hash it recommends :-(
/*
// NB: The TinyMCE hashes that SHOULD but DON'T work: The first of these is not accepted by Chrome, even though it is the 
// hash that Chrome suggests for the MCE inline style source
	$tiny_mce_1 = "'sha256-Ut79aLjs3fC5UtVv26l2r+kyv/4DhifGEM6YG3xXOyo='";
	$tiny_mce_2 = "'sha256-47DEQpj8HBSa+/TImW+5JCeuQeRkm5NMpJWZG3hSuFU='";
	$tiny_mce_3 = "'sha256-yTCJFvBsJ3q3wf4Dk4paMpnG1N2ABmaPEXkImUtBFdM='";
	$tiny_mce_sha = ' ' . $tiny_mce_1 . ' ' . $tiny_mce_2 . ' ' . $tiny_mce_3;
	$style_src = "style-src 'self'" . $nonce_src . $tiny_mce_sha . " https://fonts.googleapis.com ; ";
*/

if (isset($_SESSION['authenticated']) && ($_SESSION['authenticated'] == 'true')) {
	$style_src_unsafe_inline = true;
}

if ($ecwid == true) {
	$style_src = "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com" . $ecwid_style_src . " ; ";
} else {

	if ($style_src_unsafe_inline === true) {
		$style_src = "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com ; ";
	} else {

		$lighthouse_sha = " 'sha256-TyNUDnhSZIj6eZZqS6qqchxBN4+zTRUU+TkPeIxxT1I='";

// unsafe-inline is ignored if a nonce is present
		$style_src = "style-src 'self'" . $nonce_src . $lighthouse_sha . " https://fonts.googleapis.com ; ";
	}
}

/************
** img-src **
************/

//Refused to load the image 'https://scontent-atl3-1.cdninstagram.com/vp/d7afec20dbf847d3176dbefd4d377bdd/5D16865D/t51.2885-15/e15/c108.0.864.864a/s150x150/51683048_293620117993957_3533445156148326284_n.jpg?_nc_ht=scontent-atl3-1.cdninstagram.com' because it violates the following Content Security Policy directive: "img-src 'self' data: https://localhost www.google-analytics.com".

//https://cdn.000webhost.com/000webhost/logo/footer-powered-by-000webhost-white2.png

if ($ecwid == true) {
	$ecwid_img_src = ' https://*.cloudfront.net';
} else {
	$ecwid_img_src = '';
}

//$img_src = "img-src 'self' data: https://localhost www.google-analytics.com *.cdninstagram.com *.fbcdn.net *.pinimg.com *.000webhost.com ; ";
$img_src = "img-src 'self' data: www.google-analytics.com www.googletagmanager.com *.cdninstagram.com *.fbcdn.net *.pinimg.com *.ytimg.com" . $ecwid_img_src . " ; ";

/***************
** worker-src **
***************/

$worker_src = "worker-src 'self' www.google.com www.youtube.com blob: ; ";
if ($safari_browser_without_worker_src_support) {
	$worker_src = '';
}

/**************
** frame-src **
**************/

//$frame_src = "frame-src 'self' www.google.com www.youtube.com ; ";
$frame_src = 'frame-src * ; ';

/****************
** connect-src **
****************/

if ($ecwid == true) {
	$ecwid_connect_src = ' https://*.ecwid.com https://ecomm.events/register';
} else {
	$ecwid_connect_src = '';
}

$connect_src = "connect-src 'self' https://www.google-analytics.com https://www.googletagmanager.com https://ipapi.co https://httpstat.us " . $ecwid_connect_src . " ; ";

/*************
** font-src **
*************/

//$font_src = "font-src 'self' data: https://localhost https://fonts.gstatic.com https://cdnjs.cloudflare.com ; ";
if ($ecwid == true) {
	$ecwid_font_src = ' https://*.cloudfront.net https://*.shopsettings.com';
} else {
	$ecwid_font_src = '';
}

$font_src = "font-src 'self' data: https://fonts.gstatic.com https://cdnjs.cloudflare.com" . $ecwid_font_src . " ; ";

/****************
** form-action **
****************/

if ($ecwid == true) {
// Needed so buyers can print a receipt of their purchase
	$form_action = "";
} else {
	$form_action = "form-action 'self' ; ";
}


/***************
** object-src **
***************/

$object_src = "object-src 'none' ; ";

/*************
** base-uri **
*************/

$base_uri = "base-uri 'self' ; ";

/********************
** frame-ancestors **
********************/

// 2020-08-13: Had been 'none':
$frame_ancestors = "frame-ancestors 'self' ; ";

/*****************
** manifest-src **
*****************/

$manifest_src = "manifest-src 'self' ; ";

/*******************************
** prefetch-src | default-src **
*******************************/

// prefetch-src isn't recognized by Chrome yet, so use default-src instead:

$use_prefetch_src = false;

if ($use_prefetch_src == true) {
	$prefetch_src = "prefetch-src 'self' data: https://www.googletagmanager.com https://www.google-analytics.com ; ";
	$default_src = "default-src 'none' ; ";
} else {
	$prefetch_src = '';
// 2021-11-26
// We're allowing googletagmanager and google-analytics in the script-src CSP
// header, so we can use a more secure default_src header here:
// OLD: $default_src = "default-src 'self' https://www.googletagmanager.com https://www.google-analytics.com ; ";
//	$default_src = "default-src 'none' ; ";
	$default_src = "default-src 'self' ; ";
}


/***************
** report-uri **
***************/

// https://report-uri.com

// For LIVE SERVER only

$report_uri = 'report-uri https://shell.report-uri.com/r/d/csp/enforce ;';

/*******************************
** NEL (network error logging **
*******************************/

$network_error_logging = 'NEL: {"report_to":"default","max_age":31536000,"include_subdomains":true}; ';

/**************
** Report-To **
**************/

$report_to = 'Report-To: {"group":"default","max_age":31536000,"endpoints":[{"url":"https://shell.report-uri.com/a/d/g"}],"include_subdomains":true} ;';

/**************
** expect-ct **
**************/

// SEE: https://scotthelme.co.uk/a-new-security-header-expect-ct/
// Check Report-uri and gradually increase the max-age from 30 seconds to a week, etc.
// (max-age is expressed in seconds)
$expect_ct = "Expect-CT: enforce, max-age=3600, report-uri='https://shell.report-uri.com/r/d/ct/enforce' ; ";

// localhost vs. live server:
if (($_SERVER['SERVER_NAME']) === 'localhost') {
	$csp = "Content-Security-Policy: ";
	$report_uri = '';
} else {
// TESTING ONLY: $csp = "Content-Security-Policy-Report-Only: ";
	$csp = "Content-Security-Policy: ";
// 2022-07-26: The Expect-CT header is deprecated and will be removed.
// Chrome requires Certificate Transparency for all publicly trusted
// certificates issued after April 30, 2018.
// No need to indicate report_uri, since we're dropping the Expect-CT header:
	$report_uri = '';
}

// This is the email address you should configure in your DMARC policy to send reports to. 
// shell-d@dmarc.report-uri.com
//
// Your current subdomain is: shell.report-uri.com
// 2FA: ###############

/******************************
** require-trusted-types-for **
*******************************/

// Support stats from: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/require-trusted-types-for
$require_trusted_types_for_support = false;

// Default the value to nothing:
$require_trusted_types_for = '';

if ($browser->isMobile() == true) {
	if (($browser->getBrowser() == Browser::BROWSER_ANDROID) && ($browser->getVersion() >= 83)) {
		$require_trusted_types_for_support = true;
	}
	if (($browser->getBrowser() == Browser::BROWSER_CHROME) && ($browser->getVersion() >= 83)) {
		$require_trusted_types_for_support = true;
	}
} else {
// Truly, support for this on Chrome begins at version 83, but to pass the Google CSP, which uses
// Chrome 80, and Scott Helme's site, which uses Chrome 75, I'm letting all versions of non-mobile
// Chrome receive the header:
	if ($browser->getBrowser() == Browser::BROWSER_CHROME) {
		$require_trusted_types_for_support = true;
	}
	if (($browser->getBrowser() == Browser::BROWSER_EDGE) && ($browser->getVersion() >= 83)) {
		$require_trusted_types_for_support = true;
	}
	if (($browser->getBrowser() == Browser::BROWSER_OPERA) && ($browser->getVersion() >= 69)) {
		$require_trusted_types_for_support = true;
	}
}

// set in variables.php:
if ($use_trusted_types == false) {
	$require_trusted_types_for_support = false;
}

// To Toggle off:
//$require_trusted_types_for_support = false;

// 2021-03-14: Apparently, Google Analytics & reCAPTCHA are both ready for this.
if (($require_trusted_types_for_support == true) && ($ecwid == false)) {
	$require_trusted_types_for = "require-trusted-types-for 'script' ; ";
}

// BUT: Tiny MCE editor, used in Admin mode, is not!
if (isset($_SESSION['authenticated']) && ($_SESSION['authenticated'] == 'true')) {
	$require_trusted_types_for = '';
}

// 2021-06-03:
if (isset($_SESSION['trusted-types-off']) && ($_SESSION['trusted-types-off'] == 'true')) {
	$require_trusted_types_for = '';
}

// ecwid
if ($ecwid == true) {
	$require_trusted_types_for = '';
}

// NEEDED FOR WAVE a11y AUDIT:
// UNREM to turn it off unilaterally:
//$require_trusted_types_for = '';


//file_put_contents('x.txt', $browser->getBrowser() . ' ' . $browser->getVersion());

// For testing trusted types only (send header below):
//$cspro = "Content-Security-Policy-Report-Only: require-trusted-types-for 'script'; report-uri //shell.report-uri.com/r/d/ct/enforce ";

// NB: KEEP THE FOLLOWING!
// It's helpful for printing the CSP to screen in readable format (in view-source mode):

// qwer
// Set to true to view,
// Go to view-source in browser and enable line-wrap:
$view_csp = false;

if ($view_csp == true) {
	if ($script_src !== '') {
		$script_src = $script_src . PHP_EOL;
	}
	if ($style_src !== '') {
		$style_src = $style_src . PHP_EOL;
	}
	if ($img_src !== '') {
		$img_src = $img_src . PHP_EOL;
	}
	if ($worker_src !== '') {
		$worker_src = $worker_src . PHP_EOL;
	}
	if ($frame_src !== '') {
		$frame_src = $frame_src . PHP_EOL;
	}
	if ($connect_src !== '') {
		$connect_src = $connect_src . PHP_EOL;
	}
	if ($font_src !== '') {
		$font_src = $font_src . PHP_EOL;
	}
	if ($form_action !== '') {
		$form_action = $form_action . PHP_EOL;
	}
	if ($object_src !== '') {
		$object_src = $object_src . PHP_EOL;
	}
	if ($base_uri !== '') {
		$base_uri = $base_uri . PHP_EOL;
	}
	if ($frame_ancestors !== '') {
		$frame_ancestors = $frame_ancestors . PHP_EOL;
	}
	if ($manifest_src !== '') {
		$manifest_src = $manifest_src . PHP_EOL;
	}
	if ($prefetch_src !== '') {
		$prefetch_src = $prefetch_src . PHP_EOL;
	}
	if ($default_src !== '') {
		$default_src = $default_src . PHP_EOL;
	}
	if ($require_trusted_types_for !== '') {
		$require_trusted_types_for = $require_trusted_types_for . PHP_EOL;
	}
	if ($report_uri !== '') {
		$report_uri = $report_uri . PHP_EOL;
	}
}

$content_security_policy = $csp . $script_src . $style_src . $img_src . $worker_src . $frame_src . $connect_src . $font_src . $form_action . $object_src . $base_uri . $frame_ancestors . $manifest_src . $prefetch_src . $default_src . $require_trusted_types_for . $report_uri;

/**************************************************
** Permissions-Policy (replaces: Feature-Policy) **
**************************************************/

// OLD FORMAT:
// $feature_policy = "Feature-Policy: accelerometer 'none'; camera 'none'; geolocation 'none'; gyroscope 'none'; magnetometer 'none'; microphone 'none'; payment 'none'; usb 'none' ; ";

// NEW FORMAT:
// NB: No final semicolon!
// re: FLoC and interest-cohort=() header: https://seirdy.one/2021/04/16/permissions-policy-floc-misinfo.html
$permissions_policy = "Permissions-Policy: accelerometer=(), camera=(), geolocation=(), gyroscope=(), magnetometer=(), microphone=(), payment=(), usb=() ";

/*************************************
** Access-Control-Allow-Credentials **
*************************************/

$access_control_allow_credentials = "Access-Control-Allow-Credentials: true ; ";

if ($view_csp == true) {
	echo $content_security_policy;
	exit;
}

header($content_security_policy);

/*
// experiment for ecwid:
<meta http-equiv="Content-Security-Policy" content="script-src 'self' 'unsafe-eval' 'unsafe-inline' http: https: https://*.ecwid.com https://*.cloudfront.net https://ecomm.events ;style-src 'self' 'unsafe-inline' https://fonts.googleapis.com 'unsafe-inline' https://*.cloudfront.net; img-src 'self' data: www.google-analytics.com www.googletagmanager.com *.cdninstagram.com *.fbcdn.net *.pinimg.com *.ytimg.com https://*.cloudfront.net; worker-src 'self' www.google.com www.youtube.com blob: ; frame-src * ; connect-src 'self' https://www.google-analytics.com https://www.googletagmanager.com https://*.ecwid.com https://ecomm.events/register; font-src 'self' data: https://fonts.gstatic.com https://cdnjs.cloudflare.com https://*.cloudfront.net https://*.shopsettings.com; form-action 'self'; object-src 'none'; base-uri 'self'; frame-ancestors 'self'; manifest-src 'self'; default-src 'self' https://www.googletagmanager.com https://www.google-analytics.com;">
*/

//header($cspro);

/*
// new in Chrome 88 re: iframes...utility not fully appreciated yet

// For testing:
$corp = 'Cross-Origin-Resource-Policy: same-site';
$coep = 'Cross-Origin-Embedder-Policy-Report-Only: require-corp; report-to="default"';
$coop = 'Cross-Origin-Opener-Policy-Report-Only: same-origin; report-to="default"';

// More full deployment:
$corp = "Cross-Origin-Resource-Policy: same-site ; ";
$coep = "Cross-Origin-Embedder-Policy: require-corp ; ";
$coop = "Cross-Origin-Opener-Policy: same-origin ; ";

header($corp);
header($coep);
header($coop);
*/

// 2022-07-26: The Expect-CT header is deprecated and will be removed. Chrome requires Certificate Transparency for all publicly trusted certificates issued after April 30, 2018.
/*
// NOT on localhost:
if (($_SERVER['SERVER_NAME']) != 'localhost') {
	header($network_error_logging);
	header($report_to);
	header($expect_ct);
}
*/

// OLD (replaced by Permissions-Policy): header($feature_policy);
header($permissions_policy);
header($access_control_allow_credentials);
