<?php

/******************************************************
*** CUSTOM DATA ATTRIBUTES FOR web_author META TAG ****
*** For inclusion in head.php *************************
******************************************************/

$web_author = 'John Q. Public';

/*********************
** chromeLighthouse **
*********************/

$data_chrome_lighthouse = '';

if (isset($_SERVER['HTTP_USER_AGENT']) && !empty(isset($_SERVER['HTTP_USER_AGENT']))) {
	$ua = $_SERVER['HTTP_USER_AGENT'];
// (compatible; Google-AMPHTML)
	if ((strpos($ua, 'Chrome-Lighthouse') !== false) || (strpos($ua, 'Google-AMPHTML') !== false)) {
		$data_chrome_lighthouse = ' data-chrome-lighthouse=true';
	}
}

/*************
** clientIP **
*************/

// To determine is visitor is in EU, write client IP to web_author:
if (isset($_SERVER) && (isset($_SERVER['REMOTE_ADDR'])) && (!empty($_SERVER['REMOTE_ADDR']))) {
	$client_ip = $_SERVER['REMOTE_ADDR'];
} else {
	$client_ip = 0;
}

// FOR TESTING:
// Berlin IP:
//$client_ip = '85.214.132.117';
// London IP:
//$client_ip = '193.62.157.66';
// My IP:
//$client_ip = '##.##.###.##';


if ($client_ip != 0) {
	$data_client_ip = ' data-client-ip="' . $client_ip . '"';
} else {
	$data_client_ip = '';
}


/**********
** nonce **
**********/

$use_data_nonce = false;

if ($use_data_nonce == true) {
	$data_nonce = ' data-nonce="' . $nonce . '"';
} else {
	$data_nonce = '';
}

/***********************
** ogDefaultLogoImage **
***********************/

// We need to retain the autoversioned full URL of the image,
// so we'll put in the web_author META element and let the SPA
// grab it from there when the visitor migrates to the pages that
// need it:

// 2022-09-16
// No longer needed:
// $data_default_og_image = ' data-default-og-image="' . $default_og_image . '"';

/****************
** queryString **
****************/

if (isset($_SERVER['QUERY_STRING']) && (!empty($_SERVER['QUERY_STRING']))) {
	$data_query_string = ' data-query-string="' . $_SERVER['QUERY_STRING'] . '"';
} else {
	$data_query_string = '';
}

/************
** rootDir **
************/

$data_root_dir = ' data-root-dir="' . $root_dir . '"';

/**************
** timestamp **
**************/

$timestamp = '19990221125549';
if (file_exists($absolute_root . 'favicons/site.webmanifest')) {
	$file_m_time = filemtime($absolute_root . 'favicons/site.webmanifest');
	$timestamp = date("YmdHis", $file_m_time);
}
$data_timestamp = ' data-timestamp=' . $timestamp;

/**********
** title **
**********/

// $title is set in the index.php of the page being opened:

$data_title = ' data-title="' . $title . '"';


/*********************
** useServiceWorker **
*********************/

// $use_service_worker set in variables.php

if ($use_service_worker === true) {
	$data_use_service_worker = ' data-use-service-worker=true';
} else {
	$data_use_service_worker = ' data-use-service-worker=false';
}

/***********
** useSpa **
***********/

// $use_spa is set in variables.php

if ($use_spa === true) {
	$data_use_spa = ' data-use-spa=true';
} else {
	$data_use_spa = ' data-use-spa=false';
}

/**************
** useSrcset **
**************/

// $use_srcset is set in variables.php

if ($use_srcset === true) {
	$data_use_srcset = ' data-use-srcset=true';
} else {
	$data_use_srcset = ' data-use-srcset=false';
}

/********************
** useTrustedTypes **
********************/

// $use_trusted_types is set in variables.php

if ($use_trusted_types == true) {
	$data_use_trusted_types = ' data-use-trusted-types=true';
} else {
	$data_use_trusted_types = '';
}

/****************
** webpSupport **
****************/

// $_SESSION['webp_support'] is set in variables.php

if ($_SESSION['webp_support'] == true) {
	$data_webp_support = ' data-webp-support=true';
} else {
	$data_webp_support = ' data-webp-support=false';
}
