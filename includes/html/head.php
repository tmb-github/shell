<?php

ob_start();

/**********************
** get absolute_root **
***********************/

$absolute_root = $_SERVER['ABSOLUTE_ROOT'];


/***********************************
** To allow robot indexing or not **
************************************/

$robots_boolean = true;

// Do not index any of the admin pages,
// and never index the /admin or /login page:
if (isset($_SESSION['authenticated']) && ($_SESSION['authenticated'] == 'true')) {
	$robots_boolean = false;
} else if ((strpos($_SERVER['REQUEST_URI'], '/admin') !== false) || (strpos($_SERVER['REQUEST_URI'], '/login') !== false)) {
	$robots_boolean = false;
}

/******************************************************************
** Ensure definition for $page, which should be set in index.php **
*******************************************************************/

if (!isset($page) || empty($page)) {
	$page = '';
}

/****************
** META values **
*****************/

include $absolute_root . 'includes/html/common/meta-schema-defaults.inc.php';

if (file_exists($absolute_root . $page . '/meta-schema-overrides.inc.php')) {
	include $absolute_root . $page . '/meta-schema-overrides.inc.php';
};


/********************
** web_author data **
*********************/

include $absolute_root . 'includes/html/common/head-web-author-data.inc.php';

?>

<head prefix="og: http://ogp.me/ns#" typeof=http://ogp.me/ns#>
	<meta charset=utf-8>
	<title><?php echo $title; ?></title>
	<base href="<?php echo $base_href; ?>">
	<meta name=apple-mobile-web-app-capable          content="yes">
	<meta name=apple-mobile-web-app-status-bar-style content="black">
	<meta name=apple-mobile-web-app-title            content="<?php echo $apple_mobile_web_app_title; ?>">
	<meta name=author                                content="<?php echo $author; ?>">
	<meta name=description                           content="<?php echo $description; ?>">
	<meta name=google-site-verification              content="<?php echo $google_site_verification; ?>">
	<meta name=mobile-web-app-capable                content="yes">
	<meta name=msapplication-config                  content="<?php echo $application_config; ?>">
	<meta name=msapplication-TileColor               content="<?php echo $ms_application_tile_color; ?>">
	<meta name=theme-color                           content="<?php echo $meta_theme_color; ?>">
	<meta name=viewport                              content="width=device-width, initial-scale=1.0">
<?php

/***********
** robots **
************/

// The default is for bots to index and follow, so there's no need to
// include the tag otherwise:

if ($robots_boolean === false) {
?>
	<meta name=robots                                content="noindex, nofollow">
<?php
}

/***************
** web_author **
****************/

// Equivalent to writing out: echo $data_root_dir; echo $data_webp_support; echo $data_timestamp;  etc.
$web_author_data_attributes = '';

foreach($GLOBALS as $key => $value) {
	if (str_starts_with($key, 'data_')) { 
		$web_author_data_attributes .= $value;
	}
}

$web_author_data_attributes = trim($web_author_data_attributes);

?>
	<meta name=web_author                            content="<?php echo $web_author; ?>" <?php echo $web_author_data_attributes;?>>
<?php

/************
** Twitter **
*************/

?>
	<meta name=twitter:card        content="summary_large_image">
	<meta name=twitter:creator     content="<?php echo $twitter_creator; ?>">
	<meta name=twitter:description content="<?php echo $twitter_description; ?>">
	<meta name=twitter:site        content="<?php echo $twitter_site; ?>">
	<meta name=twitter:title       content="<?php echo $twitter_title; ?>">
	<meta name=twitter:url         content="<?php echo $twitter_url; ?>">
	<meta name=twitter:image       content="<?php echo $twitter_image; ?>">
	<meta name=twitter:image:alt   content="<?php echo $twitter_image_alt; ?>">
<?php

/*******
** OG **
********/

?>
	<meta property=og:description      content="<?php echo $og_description; ?>">
	<meta property=og:image            content="<?php echo $og_image; ?>">
	<meta property=og:image:alt        content="<?php echo $og_image_alt; ?>">
	<meta property=og:image:height     content="<?php echo $og_image_height; ?>">
	<meta property=og:image:width      content="<?php echo $og_image_width; ?>">
	<meta property=og:image:secure_url content="<?php echo $og_image_secure_url; ?>">
	<meta property=og:image:type       content="<?php echo $og_image_type; ?>">
	<meta property=og:site_name        content="<?php echo $og_site_name; ?>">
	<meta property=og:title            content="<?php echo $og_title; ?>">
	<meta property=og:type             content="<?php echo $og_type; ?>">
	<meta property=og:url              content="<?php echo $og_url; ?>">
<?php

// NB: $include_ecwid_product_properties is set to FALSE in VARIABLES.PHP
// because the GoogleBot apparently does not read this information from the <HEAD>,
// but rather from microdata on the tags themselves

if (($page == 'merchandise') && ($on_product_page == true) && ($include_ecwid_product_properties == true))  {
// see: https://developers.facebook.com/docs/marketing-api/catalog/guides/microdata-tags/
?>
	<meta property=product:url              content="<?php echo $product_url; ?>" class=merchandise-product-meta-tag>
	<meta property=product:brand            content="<?php echo $product_brand; ?>" class=merchandise-product-meta-tag>
	<meta property=product:availability     content="<?php echo $product_availability; ?>" class=merchandise-product-meta-tag>
	<meta property=product:condition        content="<?php echo $product_condition; ?>" class=merchandise-product-meta-tag>
	<meta property=product:price:amount     content="<?php echo $product_price_amount; ?>" class=merchandise-product-meta-tag>
	<meta property=product:price:currency   content="<?php echo $product_price_currency; ?>" class=merchandise-product-meta-tag>
	<meta property=product:retailer_item_id content="<?php echo $product_retailer_item_id; ?>" class=merchandise-product-meta-tag>
	<meta property=product:item_group_id    content="<?php echo $product_item_group_id; ?>" class=merchandise-product-meta-tag>
<?php
}

/*************
** Favicons **
**************/

/* 
NB: No need to specify sizes on apple-touch-icon. See:
https://webhint.io/docs/user-guide/hints/hint-apple-touch-icons/#why-is-this-important

NBB: Most of the variables below are defined in variables.php:
*/ 

?>
	<link rel=apple-touch-icon href="<?php echo $apple_touch_icon_png; ?>">
	<link rel=canonical        href="<?php echo $canonical; ?>">
	<link rel=icon             href="<?php echo $favicon_ico;?>" type="image/x-icon">
	<link rel=icon             href="<?php echo $favicon_32x32_png; ?>" sizes=32x32 type=image/png>
	<link rel=icon             href="<?php echo $favicon_16x16_png; ?>" sizes=16x16 type=image/png>
	<link rel=manifest         href="<?php echo $site_webmanifest; ?>" type="application/manifest+json">
	<link rel=mask-icon        href="<?php echo $safari_pinned_tab_svg; ?>" color="<?php echo $mask_icon_color; ?>">
<?php 

/********************************
** Preconnect Google Analytics **
*********************************/

$use_preconnect = true;

// No google analytics in admin mode!
if (isset($_SESSION['authenticated']) && ($_SESSION['authenticated'] == 'true')) {
	$use_preconnect = false;
}

// Don't preconnect during a Lighthouse Audit; the flag will be the absense of the
// data-chrome-lighthouse attribute:

if ((($_SERVER['SERVER_NAME']) != 'localhost') && ($use_preconnect == true)) {
	if ($data_chrome_lighthouse != '') {
?>
	<link rel=preconnect href="https://www.google-analytics.com/analytics.js">
<?php
	}
}

/******************
** Preload fonts **
*******************/

// Preloading the fonts seems to reduce the request chain problem on the main thread:
$use_preload_font = false;

if ((!isset($_GET['no-preload-font'])) && ($use_preload_font == true)) {
?>
	<link rel=preload    href="fonts/Cabin-Bold-700.woff2" as=font type=font/woff2 crossorigin>
	<link rel=preload    href="fonts/Cabin-Bold-Italic-700.woff2" as=font type=font/woff2 crossorigin>
	<link rel=preload    href="fonts/Cabin-Italic-400.woff2" as=font type=font/woff2 crossorigin>
	<link rel=preload    href="fonts/Cabin-Roman-400.woff2" as=font type=font/woff2 crossorigin>
<?php
}

/**************************************************
** Preload site-wide CSS when not in minify mode **
***************************************************/

if ($_SESSION['minify'] != true) {
	if (($_SERVER['SERVER_NAME']) === 'localhost') {
		echo '	<link rel=preload    href="' . autoversion('css/individual-imports.css') . '" as=style type=text/css>' . PHP_EOL;
	} else {
		echo '	<link rel=preload    href="' . autoversion('css/compiled.css') . '" as=style type=text/css>' . PHP_EOL;
	}
}

/*************************************************
** Preload LCP (Largest Contentful Paint) Image **
**************************************************/

// NB:
// Safari doesn't support 'imagesrcset' attribute on preload LINK. It only preloads the source
// indicated in the 'href', which is required for the LINK to be valid HTML, but it's not what's
// needed by the browser...the 'srcset' on the SOURCE element is reproduced on the 'imagesrcset'
// on the LINK so the correct image is preloaded, and as of Safari 14 (on 31 March 2021), this
// is not implemented.
//
// So, don't bother preloading on Safari -- all other major browsers support the feature;
// see: https://caniuse.com/?search=imagesrcset

if ($_SESSION['browser'] != 'Safari') {
	if (file_exists($absolute_root . 'includes/html/common/head-lcp-preload-' . $page . '.inc.php')) {
		include $absolute_root . 'includes/html/common/head-lcp-preload-' . $page . '.inc.php';
	};
}

/*******************
** Site-wide CSS: **
********************/

if ($_SESSION['minify'] == true) {
	$minified_css = tovic_minify_css(file_get_contents($absolute_root . 'css/compiled.css'));
	echo '<style ' . $inline_nonce_property . '>' . $minified_css . '</style>';
} else {
	if (($_SERVER['SERVER_NAME']) === 'localhost') {
		echo '	<link rel=stylesheet href="' . autoversion('css/individual-imports.css') . '" type=text/css>' . PHP_EOL;
	} else {
		echo '	<link rel=stylesheet href="' . autoversion('css/compiled.css') . '" type=text/css>' . PHP_EOL;
	}
}

/***********************
** Page-specific CSS: **
************************/

// Add the page-specific CSS, using the $page variable to identify the CSS file:
$css_array_inc = $absolute_root . 'includes/' . $page . '/css_array.inc.php';
if (file_exists($css_array_inc)) {
	include $css_array_inc;
	render_initial_page_style_element($page, $css_array);
}


?>
</head><?php echo PHP_EOL; ?>
<?php
$html = ob_get_contents();
ob_end_clean();
echo $html;
?>
