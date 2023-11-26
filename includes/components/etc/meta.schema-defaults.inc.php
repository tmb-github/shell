<?php

/////////////////////////////
// META tag default values //
/////////////////////////////

// NB: Any content attribute in META tags that specifies a resource requires the full, qualified path

$author = 'Thomas M. Brodhead';
$google_site_verification = 'Emklps7uK_STmuirmgr5Z8y6jWumhC-Pch11agfb4pg';
$application_config = $base_href . $browserconfig_xml;
$apple_mobile_web_app_title = $site_title_short_form_uc;

// For $description, return everything from the title up to
// ' | John Q. Public', which will be at the end. So, one space before the
// last pipe in the title marks marks the slicing point:

$last_pipe = strrpos($title, ' |');
if ($last_pipe !== false) {
//	$description = substr($title, 0, $last_pipe) . ' in the online location of ' . $site_title_short_form_uc;
	$description = 'Shell application for website development';
} else {
//	$description = $title . ' in the online location of ' . $site_title_short_form_uc;
	$description = 'Shell application for website development';
}

$default_og_image = $base_href . autoversion('images/head/' . $site_title_short_form_lc . '-1200x630.jpg', $use_webp = false);

$og_description = $description;
// The og:image must be 1200x630 - Test with https://realfavicongenerator.net/social/checker
// NB: We need to use the complete URL for og:image, not a relative site path:
$og_image = $default_og_image;
$og_image_alt = $site_title;
$og_image_height = '630';
$og_image_width = '1200';
$og_image_secure_url = $default_og_image;
$og_image_type = 'image/jpeg';
$og_site_name = $site_title;
$og_title = $title;
$og_type = 'website';
// use canonical URL for og:url, see: https://neilpatel.com/blog/open-graph-meta-tags/
$og_url = $canonical;

$twitter_description = $description;
$twitter_image = $default_og_image;
$twitter_image_alt = $og_image_alt;
$twitter_title = $og_title;
$twitter_url = $canonical;
$twitter_creator = '@' . $site_title;
$twitter_site = '@' . $site_title;

//$og_work_year = date('Y');
