<?php

// ===========================================
// COMMON GLOBAL VARIABLES & SESSION VARIABLES
// ===========================================

// NB: THIS FILE IS CALLED BY common/routines.php

/******************************************************************************
*******************************************************************************
** START: VARIABLES THAT ARE USED IN THE DEFINITION OF SUBSEQUENT VARIABLES: **
*******************************************************************************
******************************************************************************/

// To keep admin mode on, unrem the following (helpful for development of admin
// tools):
$_SESSION['authenticated'] = 'true';

/****************
** request_uri **
****************/

// This is used in subsequent variable definitions!
// It's also used in multiple common files!

// Remove any trailing query string:
$_SERVER['REQUEST_URI'] = strtok($_SERVER['REQUEST_URI'], '?');


/**************
** http_host **
**************/

// These correspond to $_SERVER['HTTP_HOST']:
$inmotion_http_host = 'ecbiz261.inmotionhosting.com';
$shell_http_host = $domain_name;


/**************
** localhost **
**************/

$local_host_root = $htdocs_folder;

// localhost or not:
$localhost_boolean = ($_SERVER['HTTP_HOST'] === 'localhost');


/****************************
** protocol: http or https **
****************************/

if ((isset($_SERVER['HTTPS'])) && ($_SERVER['HTTPS'] === 'on')) {
	$protocol = "https://";
} else {
	$protocol = "http://";
}


/*************
** root dir **
*************/

// On localhost, this is '/shell/'
// On livehost, this is '/'

if ($_SERVER['SERVER_NAME'] === 'localhost') {
	$root_dir = '/' . $local_host_root . '/';
} else {
	$root_dir = '/';
	if ($_SERVER['HTTP_HOST'] === $inmotion_http_host) {
		$root_dir = '/~ncb2a75/';
	}
}


/***************************
** SPA: to use or not use **
***************************/

$use_spa = true;
// Don't use in admin mode:
if (isset($_SESSION['authenticated']) && ($_SESSION['authenticated'] == 'true')) {
	$use_spa = false;
}


/****************************************************************************
*****************************************************************************
** END: VARIABLES THAT ARE USED IN THE DEFINITION OF SUBSEQUENT VARIABLES: **
*****************************************************************************
****************************************************************************/


/***************
** admin mode **
***************/

// TO FORCE ADMIN MODE, UNREM THESE 2 LINES:
//$_SESSION['authenticated'] = 'true';
//$use_spa = false;


/**************************
** aspect_ratio_lazyload **
**************************/
//
// 2022-09-08
// Apparently no longer needed...edited out of functions.php
//
// $aspect_ratio_lazyload = false;


/****************
** <BASE> href **
****************/

$base_href = $protocol . $_SERVER['HTTP_HOST'] . $root_dir;


/**************
** canonical **
**************/

$canonical = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

// OLD COMPILE VERSION!
// Compiling the 404 page will result in 'compile' showing as the URL unless we change it:
//$canonical = str_replace('/compile/', '/error/', $canonical);


/******************
** document root **
******************/

// E.G.: 
// /home/ni348jd/shell.com
// C:/xampp/htdocs

if ($_SERVER['HTTP_HOST'] === $inmotion_http_host) {
	$document_root = $_SERVER['CONTEXT_DOCUMENT_ROOT'];
} else {
	$document_root = $_SERVER['DOCUMENT_ROOT'];
}
$document_root = $document_root;


/*************************
** down for maintenance **
*************************/

$down_for_maintenance = false;


/**********
** Ecwid **
**********/

// NB: GoogleBot does not read any of these in the HEAD.
// It reads what's found in the element markup.
$include_ecwid_product_properties = false;

// Hard-wire ecwid (set to false to disable):
$_SESSION['ecwid'] = 'false';

// Turn on ecwid with ?ecwid
if (isset($_GET['ecwid'])) {
	$_SESSION['ecwid'] = 'true';
}

// Turn off ecwid with ?square
if (isset($_GET['square'])) {
	$_SESSION['ecwid'] = 'false';
}

// Display test messages in Ecwid (set on web_author tag...see includes/head.php)
$_SESSION['ecwid-testing'] = 'false';


/*********************************
** hash navigation in galleries **
** NB: Shouldn't we omit this?  **
*********************************/

$use_hash_navigation = false;


/******************
** html_inc_path **
******************/

$component_path = $absolute_root . '/includes/components/';


/**********************************
** Iframe previewer in galleries **
**********************************/

// enable/disable the gallery anchor link iframe previews:
$_SESSION['gallery_link_iframe_preview'] = false;
if (isset($_GET['gallery_link_iframe_preview']) && !empty($_GET['gallery_link_iframe_preview'])) {
	$_SESSION['gallery_link_iframe_preview'] = ($_GET['gallery_link_iframe_preview'] === 'true') ? true : false;
}


/******************************
** JSON storage in galleries **
******************************/

// Default to true:
if (isset($_GET['json_storage_on'])) {
	$_SESSION['use_json_storage'] = 'true';
}
if (isset($_GET['json_storage_off'])) {
	$_SESSION['use_json_storage'] = 'false';
}
// force it off:
//$_SESSION['use_json_storage'] = 'false';

// Store session variable in global variable:
$use_json_storage = true;
if (isset($_SESSION['use_json_storage']) && !empty($_SESSION['use_json_storage'])) {
	$use_json_storage = ($_SESSION['use_json_storage'] === 'true') ? true : false;
}

/*****************
** lazy loading **
*****************/

$lazy_load_images = true;
$use_browser_based_lazy_loading = true;

/*********
** LOGO **
*********/

// For use in header navigation bar:

$site_logo_url = 'images/header/' . $site_title_short_form_lc . '-logo.png';
$site_logo_alt = $site_title_short_form_uc . ' site logo';


/**********************
** Minify | beautify **
**********************/

if (!isset($_SESSION['minify'])) {
	$_SESSION['minify'] = $localhost_boolean ? false : true;
}
if (isset($_GET['minify'])) {
	$_SESSION['minify'] = true;
}
if (isset($_SESSION['authenticated']) && ($_SESSION['authenticated'] == 'true')) {
	$_SESSION['minify'] = false;
}
if (isset($_GET['beautify'])) {
	$_SESSION['minify'] = false;
}

// TO FORCE MINIFY ON LOCALHOST, UNREM THIS LINE:
//$_SESSION['minify'] = true;

// TO FORCE BEAUTIFY ON LIVE HOST, UNREM THIS LINE:
//$_SESSION['minify'] = false;


/**********************
** <PICTURE> element **
**********************/

// NB: If you DON'T use picture elements, then, as of 2020-01-21, the first
// image above the fold will not display, reason unknown. So, DO use picture 
// elements!

$use_picture_element = true;


/******************
** robots or not **
******************/

$robots_boolean = (($_SERVER['HTTP_HOST'] === $shell_http_host) || ($localhost_boolean === true));


/*******************
** Service worker **
*******************/

// NOT WORTH IT (for now, at least):
$use_service_worker = false;

if (isset($_GET['no-service-worker'])) {
	$use_service_worker = false;
}
if (isset($_SESSION['authenticated']) && ($_SESSION['authenticated'] == 'true')) {
	$use_service_worker = false;
}


/***************************************
** Social media images CURL retrieval **
***************************************/

$include_social_media_curl = false;
$display_instagram_image_links = false;
$get_instagram_data_from_json = false;
$instagram_data_json_file = 'social-media-json/instagram_data.json';
// In case you're on localhost and need to bring in the social media images, just append ?social as a query string:
if (isset($_GET['social'])) {
	$include_social_media_curl = true;
	unset($_GET['social']);
}


/***********
** srcset **
***********/

$use_srcset = true;


/******************
** trusted types **
******************/

$use_trusted_types = false;

if (isset($_GET['trusted-types-off'])) {
	$_SESSION['trusted-types-off'] = 'true';
} else {
	$_SESSION['trusted-types-off'] = 'false';
}


/*****************
** webp support **
*****************/

// On Chrome, Opera, and some other browsers:
// $_SERVER['HTTP_ACCEPT'] => text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8
//...which means it supports webp (because of 'image/webp')
// So, search for 'image/webp' in HTTP_ACCEPT server variable:

$webp_support = false;
if (isset($_SERVER['HTTP_ACCEPT'])) {
	if (strpos($_SERVER['HTTP_ACCEPT'], 'image/webp') > 0) {
		$webp_support = true;
	}
}

/*************************************************
** variables that are needed in one or more of: **
** 1. <HEAD>                                    **
** 2. browserconfig.xml                         **
** 3. site.webmanifest                          **
*************************************************/

/*
$android_chrome_192x192_png = autoversion('favicons/android-chrome-192x192.png', $use_webp = false);
$android_chrome_256x256_png = autoversion('favicons/android-chrome-256x256.png', $use_webp = false);
$android_chrome_512x512_png = autoversion('favicons/android-chrome-512x512.png', $use_webp = false);
$apple_touch_icon_png = autoversion('favicons/apple-touch-icon.png', $use_webp = false);
$browserconfig_xml = autoversion('favicons/browserconfig.xml');
$favicon_16x16_png = autoversion('favicons/favicon-16x16.png', $use_webp = false);
$favicon_32x32_png = autoversion('favicons/favicon-32x32.png', $use_webp = false);
$favicon_ico = autoversion('favicons/favicon.ico');
$maskable_icon_64x64_png =  autoversion('favicons/maskable-icon-64x64.png', $use_webp = false);
$maskable_icon_192x192_png =  autoversion('favicons/maskable-icon-192x192.png', $use_webp = false);
$maskable_icon_512x512_png =  autoversion('favicons/maskable-icon-512x512.png', $use_webp = false);
$mstile_150x150_png = autoversion('favicons/mstile-150x150.png', $use_webp = false);
$safari_pinned_tab_svg = autoversion('favicons/safari-pinned-tab.svg');
$site_webmanifest = autoversion('favicons/site.webmanifest');
*/

$site_webmanifest_name = $site_title_short_form_uc;
$site_webmanifest_short_name = $site_title_short_form_uc;
$mask_icon_color = '#eeb935'; // --cta-dark-orange
$browserconfig_tile_color = $mask_icon_color;
$ms_application_tile_color = $mask_icon_color;
$meta_theme_color = '#fafafa'; // --body-text-color
$site_webmanifest_theme_color = $meta_theme_color;
$site_webmanifest_background_color = $meta_theme_color;
$site_webmanifest_description = $site_title_short_form_uc . ": Description of site";

/***********
** SCHEMA **
***********/

$schema_webpage_description = $site_title_short_form_uc . ": Needs webpage description.";
$schema_webpage_name = $site_title;
$schema_webpage_url = $canonical;

$schema_person_name = 'Thomas M. Brodhead';
$schema_person_url = $canonical;

/*****************
** Social Media **
*****************/

$social_media_href = array(
	"Facebook" => "https://www.facebook.com/",
	"Instagram" => "https://www.instagram.com/",
	"Pinterest" => "https://www.pinterest.com/",
	"Twitter" => "https://twitter.com/"
);
