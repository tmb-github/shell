<?php 

/***************************************************************
** autoversioned variables that are needed in one or more of: **
** 1. <HEAD>                                                  **
** 2. browserconfig.xml                                       **
** 3. site.webmanifest                                        **
***************************************************************/

$android_chrome_192x192_png = autoVersion('assets/favicons/android-chrome-192x192.png', $use_webp = false);
$android_chrome_256x256_png = autoVersion('assets/favicons/android-chrome-256x256.png', $use_webp = false);
$android_chrome_512x512_png = autoVersion('assets/favicons/android-chrome-512x512.png', $use_webp = false);
$apple_touch_icon_png = autoVersion('assets/favicons/apple-touch-icon.png', $use_webp = false);
$browserconfig_xml = autoVersion('assets/favicons/browserconfig.xml');
$favicon_16x16_png = autoVersion('assets/favicons/favicon-16x16.png', $use_webp = false);
$favicon_32x32_png = autoVersion('assets/favicons/favicon-32x32.png', $use_webp = false);
$favicon_ico = autoVersion('assets/favicons/favicon.ico');
$maskable_icon_64x64_png =  autoVersion('assets/favicons/maskable-icon-64x64.png', $use_webp = false);
$maskable_icon_192x192_png =  autoVersion('assets/favicons/maskable-icon-192x192.png', $use_webp = false);
$maskable_icon_512x512_png =  autoVersion('assets/favicons/maskable-icon-512x512.png', $use_webp = false);
$mstile_150x150_png = autoVersion('assets/favicons/mstile-150x150.png', $use_webp = false);
$safari_pinned_tab_svg = autoVersion('assets/favicons/safari-pinned-tab.svg');
$site_webmanifest = autoVersion('assets/favicons/site.webmanifest');

/******************
** Schema images **
******************/
$schema_breadcrumb_img_url = autoVersion('assets/images/head/' . $site_title_short_form_lc . '-115x35.jpg', $use_webp = false);
$schema_person_image = $base_href . autoVersion('assets/images/head/' . $site_title_short_form_lc . '-1071px.jpg', $use_webp = false);