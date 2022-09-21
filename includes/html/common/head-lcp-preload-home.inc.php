<?php

// Home page LCP (Largest Contentful Paint) image preloading:

$skip_landing_image = true;

if ($skip_landing_image != true) {

// This is set in the main.php file as well--make sure that they match:
	$transitioning_landing_image_set = true;

	if ($transitioning_landing_image_set == true) {
		include $absolute_root . 'home/lcp_img_and_preload_link_variables.inc.php';
		$tabs = 1;
		for ($x = 0; $x < count($img_url_array); $x++) {
			echo link_preload_img_srcset($img_url_array[$x], $variable_size, $img_widths, $multipliers, $sizes, $tabs);
		}
	}

// EXPERIMENT: Lighthouse claims the Chromatic Geometry Gallery Link Image is the LCP:
	include $absolute_root . 'home/lcp_img_and_preload_link_variables_2.inc.php';
	$tabs = 1;
	for ($x = 0; $x < count($img_url_array); $x++) {
		echo link_preload_img_srcset($img_url_array[$x], $variable_size, $img_widths, $multipliers, $sizes, $tabs);
	}
}
