<?php

include './compile_reqs.inc.php';

if ($update_service_worker == true) {
	$html = '';

	$preload_assets_into_cache = false;

	$timestamp = date("YmdHis", filemtime($absolute_root . $assets_folder . 'favicons/site.webmanifest'));

	$sw_offline_html = [];
	$sw_offline_css = [];
	$sw_offline_javascript = [];
	$sw_offline_fonts = [];
	$sw_offline_favicons = [];
	$sw_offline_images = [];
	$sw_offline_catch_all = [];

// Convert serviceWorkerTemplate.js
	$service_worker_template_js = $absolute_root . $assets_folder . 'javascript/serviceWorkerTemplate/serviceWorkerTemplate.js';
	if (file_exists($service_worker_template_js)) {

		$service_worker_content = file_get_contents($service_worker_template_js);
		$service_worker_content = str_replace('###DATA-TIMESTAMP###', $timestamp, $service_worker_content);

// strip out /* comments */ in service worker script
		$service_worker_content = preg_replace('!/\*.*?\*/!s', '', $service_worker_content);
		$service_worker_content = preg_replace('/\n\s*\n/', "\n", $service_worker_content);

//////////
// HTML //
//////////

		$sw_offline_html_root = ['./'];
		$sw_offline_html_subfolders = ['./about/', './abstractions/', './chromatic-geometry/', './comical/', './contact/', './message-sent/', './news/', './pareidolia/', './parody/', './privacy-policy/', './quotidian/', './technical-studies/', './offline/'];

		$sw_offline_html = array_merge($sw_offline_html_root, $sw_offline_html_subfolders);
		asort($sw_offline_html);

		$sw_offline_html = PHP_EOL . "\t\t'" . implode("'," . PHP_EOL . "\t\t'", $sw_offline_html) . "'" . PHP_EOL . "\t";

		if ($preload_assets_into_cache == false) {
			$sw_offline_html = '';
		}
		$service_worker_content = str_replace("'###HTML-TO-CACHE-STR###'", $sw_offline_html, $service_worker_content);

/////////
// CSS //
/////////

		$individual_imports_template_css = $absolute_root . $assets_folder . 'css/individual-imports.TEMPLATE.css';

		if (file_exists($individual_imports_template_css)) {
			$file = fopen($individual_imports_template_css, "r");
			while (!feof($file)) {
				$line = trim(fgets($file));
				if ($line !== '') {
					preg_match('~"(.*?)"~', $line, $css_filename);
					$css = autoversion($assets_folder . 'css/' . $css_filename[1]);
					array_push($sw_offline_css, $css);
				}
			}
			$css = autoversion($assets_folder . 'css/compiled.css');
			array_push($sw_offline_css, $css);
			$css = autoversion($assets_folder . 'css/individual-imports.css');
			array_push($sw_offline_css, $css);

			asort($sw_offline_css);
			$sw_offline_css = PHP_EOL . "\t\t'" . implode("'," . PHP_EOL . "\t\t'", $sw_offline_css) . "'" . PHP_EOL . "\t";

			if ($preload_assets_into_cache == false) {
				$sw_offline_css = '';
			}
			$service_worker_content = str_replace("'###CSS-TO-CACHE-STR###'", $sw_offline_css, $service_worker_content);

		} else {
			$html .= $individual_imports_template_css . ' could not be found . . . procedure aborted.<br>';
		}

////////////////
// JavaScript //
////////////////

// Get .js (and .min.js) files:
		$sw_offline_javascript_files = recursiveSubfolderSearch($absolute_root . $assets_folder . 'javascript', '/.+\.min.m?js/');

// eliminate '../' at beginning of each listing:
		foreach($sw_offline_javascript_files as &$javascript) {
			$javascript = autoversion(substr($javascript, 3));
		}

// Remove TEMPLATE and other unneeded js files and folders:
		foreach ($sw_offline_javascript_files as $key => $value) {
			if (strpos($value, '.TEMPLATE.')) {
				unset($sw_offline_javascript_files[$key]);
			}
			if (strpos($value, 'unneeded-for-finished-site')) {
				unset($sw_offline_javascript_files[$key]);
			}
			if (strpos($value, '000--archive')) {
				unset($sw_offline_javascript_files[$key]);
			}
			if (strpos($value, 'mjs-save')) {
				unset($sw_offline_javascript_files[$key]);
			}
// TINYMCE and SKINS folder contents are not needed to be cached, for the service worker is disengaged when we go into admin mode:
			if (strpos($value, 'tinymce')) {
				unset($sw_offline_javascript_files[$key]);
			}
			if (strpos($value, 'skins')) {
				unset($sw_offline_javascript_files[$key]);
			}
// These admin-related modules need not be cached, for the service worker is disengaged when we go into admin mode:
			if (strpos($value, 'artworkEditor')) {
				unset($sw_offline_javascript_files[$key]);
			}
			if (strpos($value, 'galleryEditor')) {
				unset($sw_offline_javascript_files[$key]);
			}
			if (strpos($value, 'galleryOrdering')) {
				unset($sw_offline_javascript_files[$key]);
			}
			if (strpos($value, 'newsItemEditor')) {
				unset($sw_offline_javascript_files[$key]);
			}
		}

// Get .map files:
		$sw_offline_javascript_map = recursiveSubfolderSearch($absolute_root . $assets_folder . 'javascript', '/.+\.map/');

// eliminate '../' at beginning of each listing:
		foreach($sw_offline_javascript_map as &$javascript_map) {
			$javascript_map = substr($javascript_map, 3);
		}

		foreach ($sw_offline_javascript_map as $key => $value) {
			if (strpos($value, '.TEMPLATE.')) {
				unset($sw_offline_javascript_map[$key]);
			}
			if (strpos($value, 'unneeded-for-finished-site')) {
				unset($sw_offline_javascript_map[$key]);
			}
			if (strpos($value, '000--archive')) {
				unset($sw_offline_javascript_map[$key]);
			}
// TINYMCE and SKINS folder contents are not needed to be cached, for the service worker is disengaged when we go into admin mode:
			if (strpos($value, 'skins')) {
				unset($sw_offline_javascript_map[$key]);
			}
			if (strpos($value, 'tinymce')) {
				unset($sw_offline_javascript_map[$key]);
			}
		}

// If there is both a non-minified and minified version of a file, 
// only store the minified version in the cache:
//
// $assets_folder . 'javascript/siteWideEdits.20191027205510.js',
// $assets_folder . 'javascript/siteWideEdits.min.20191027205512.js',
//
// So...look for files with '.min.' in the array; then see if there
// are any correlative, non-'.min.' files; remove the non-'.min.' files
// from the array:

		$key_delete = [];
		foreach ($sw_offline_javascript_files as $key => $value) {
// Is '.min.' in the file name?
			if (strpos($value, '.min.')) {
				$min_pos = strpos($value, '.min.');
// Get everything before the '.min.':
				$prefix = substr($value, 0, $min_pos);
// Find all files that begin with the same prefix but do not contain '.min.':
				foreach ($sw_offline_javascript_files as $key2 => $value2) {
					if ((substr($value2, 0, $min_pos) === $prefix) && (!strpos($value2, '.min.'))) {
// Save the key positions of all such files in $key_delete array:
						array_push($key_delete, $key2);
					}
				}
			}
		}
// Remove all of the targeted files from the array:
		foreach ($key_delete as $key => $value) {
			unset($sw_offline_javascript_files[$value]);
		}

// combine the arrays:
		$sw_offline_javascript = array_merge($sw_offline_javascript_files, $sw_offline_javascript_map);

		asort($sw_offline_javascript);
		$sw_offline_javascript = PHP_EOL . "\t\t'" . implode("'," . PHP_EOL . "\t\t'", $sw_offline_javascript) . "'" . PHP_EOL . "\t";

		if ($preload_assets_into_cache == false) {
			$sw_offline_javascript = '';
		}
		$service_worker_content = str_replace("'###JAVASCRIPT-TO-CACHE-STR###'", $sw_offline_javascript, $service_worker_content);

///////////
// FONTS //
///////////

		$fontface_template_css = $absolute_root . $assets_folder . 'css/font-face.TEMPLATE.css';

		if (file_exists($fontface_template_css)) {
			$file = fopen($fontface_template_css, "r");
			while (!feof($file)) {
				$line = trim(fgets($file));

				if ($line !== '') {
					preg_match('/url\((.[^\(]*)\)/', $line, $font_filename);
					if (!empty($font_filename)) {
						if (strlen($font_filename[1]) > 0) {
							$font = substr($font_filename[1], 19);
							array_push($sw_offline_fonts, $font);
						}
					}
				}
			}
			asort($sw_offline_fonts);
			$sw_offline_fonts = PHP_EOL . "\t\t'" . implode("'," . PHP_EOL . "\t\t'", $sw_offline_fonts) . "'" . PHP_EOL . "\t";

			if ($preload_assets_into_cache == false) {
				$sw_offline_fonts = '';
			}
			$service_worker_content = str_replace("'###FONTS-TO-CACHE-STR###'", $sw_offline_fonts, $service_worker_content);
		} else {
			$html .= $fontface_template_css . ' could not be found . . . procedure aborted.<br>';
		}

//////////////
// Favicons //
//////////////

		$favicons = [$favicon_ico, $android_chrome_512x512_png, $android_chrome_192x192_png];
		foreach ($favicons as $icon) {
			array_push($sw_offline_favicons, $icon);
		}

		$site_webmanifest_autoversioned = autoversion($assets_folder . 'favicons/site.webmanifest');
		array_push($sw_offline_favicons, $site_webmanifest_autoversioned);

		asort($sw_offline_favicons);
		$sw_offline_favicons = PHP_EOL . "\t\t'" . implode("'," . PHP_EOL . "\t\t'", $sw_offline_favicons) . "'" . PHP_EOL . "\t";

		if ($preload_assets_into_cache == false) {
			$sw_offline_favicons = '';
		}
		$service_worker_content = str_replace("'###FAVICONS-TO-CACHE-STR###'", $sw_offline_favicons, $service_worker_content);

////////////
// Images //
////////////

// !!! IMPORTANT !!! ///
// For the Art site, only cache the PNG files initially:

	$special_cacheing = true;
	if ($special_cacheing) {
// All pngs throughout site:
		$sw_offline_images_png = recursiveSubfolderSearch($absolute_root . $assets_folder . 'images', '/.+\.png/');
// JPGs and WEBPs on home page only:

//	$sw_offline_images_home_jpg = recursiveSubfolderSearch($absolute_root . $assets_folder . 'images/home', '/.+\.jpg/');
//	$sw_offline_images_home_webp = recursiveSubfolderSearch($absolute_root . $assets_folder . 'images/home', '/.+\.webp/');
// Only include 767px images. (There must be a better way to write the regex for this, but this works and will have to do for the moment:
		$sw_offline_images_home_jpg = recursiveSubfolderSearch($absolute_root . $assets_folder . 'images/home', '/.+-768p.+\.jpg/');
		$sw_offline_images_home_webp = recursiveSubfolderSearch($absolute_root . $assets_folder . 'images/home', '/.+-768p.+\.webp/');

// JPGs and WEBPs on header:
		$sw_offline_images_header_jpg = recursiveSubfolderSearch($absolute_root . $assets_folder . 'images/header', '/.+\.jpg/');
		$sw_offline_images_header_webp = recursiveSubfolderSearch($absolute_root . $assets_folder . 'images/header', '/.+\.webp/');
// Merge the arrays:
		$sw_offline_images = array_merge($sw_offline_images_png, $sw_offline_images_home_jpg, $sw_offline_images_home_webp, $sw_offline_images_header_jpg, $sw_offline_images_header_webp);
	} else {
		$sw_offline_images_webp = recursiveSubfolderSearch($absolute_root . $assets_folder . 'images', '/.+\.webp/');
		$sw_offline_images_jpg = recursiveSubfolderSearch($absolute_root . $assets_folder . 'images', '/.+\.jpg/');
		$sw_offline_images_png = recursiveSubfolderSearch($absolute_root . $assets_folder . 'images', '/.+\.png/');
// Merge the arrays:
		$sw_offline_images = array_merge($sw_offline_images_webp, $sw_offline_images_jpg, $sw_offline_images_png);
	}

// NB: Images references in CSS without autoversioning (as background-image, etc.):
// NB: WE MUST NOT AUTOVERSION THEM!
		$sw_offline_images_css = [];

// Strip extension from css images in array:
		foreach($sw_offline_images_css as &$image) {
			$image = preg_replace('/\\.[^.\\s]{3,4}$/', '', $image);
		}

// Autoversion all images EXCEPT those in the CSS list:
		foreach($sw_offline_images as &$image) {
// strip 3-4 character extension from end of file name:
			$image_without_extension = preg_replace('/\\.[^.\\s]{3,4}$/', '', $image);
			if (in_array($image_without_extension, $sw_offline_images_css)) { 
// substr($image, 3) => eliminate '../' at beginning of each file
				$image = substr($image, 3);
			} else {
// prevent autoversion from substituting .webp for the extension of JPG files:
				$image = autoversion(substr($image, 3), $use_webp = false);
			}
		}

// Clear out image array.
// Loading images into the caches during site loading is expensive.
// Leave the existing routines for collecting the images intact so that
// it may be modified for a future project.
// Here, clear out the array:
		$sw_offline_images = [];

		$sw_offline_images = PHP_EOL . "\t\t'" . implode("'," . PHP_EOL . "\t\t'", $sw_offline_images) . "'" . PHP_EOL . "\t";

		if ($preload_assets_into_cache == false) {
			$sw_offline_images = '';
		}
		$service_worker_content = str_replace("'###IMAGES-TO-CACHE-STR###'", $sw_offline_images, $service_worker_content);

///////////////
// Catch-all //
///////////////

		asort($sw_offline_catch_all);
		$sw_offline_catch_all = PHP_EOL . "\t\t'" . implode("'," . PHP_EOL . "\t\t'", $sw_offline_catch_all) . "'" . PHP_EOL . "\t";

		if ($preload_assets_into_cache == false) {
			$sw_offline_catch_all = '';
		}
		$service_worker_content = str_replace("'###CATCH-ALL-TO-CACHE-STR###'", $sw_offline_catch_all, $service_worker_content);

//////////////////////
// Final Operations //
//////////////////////

// replace tabs with double spaces:
		$service_worker_content = str_replace("\t", "  ", $service_worker_content);

// delete old service worker(s):
		foreach (GLOB($absolute_root . "sw*.js") as $filename) {
			unlink($filename);
		}

		file_put_contents($absolute_root . 'sw.js', $service_worker_content);

	} else {
		$html .= $service_worker_template_js . ' could not be found . . . procedure aborted.<br>';
	}

	$status_ok = true;

	if ($status_ok) {
		$message = $html . 'Done.';
		echo '{"status": "ok", "message": "' . $message . '"}';
	} else {
// send a 422 Unprocessable Entity header, echo the JSON, and exit:
		http_response_code(422);
		$message = $html . 'Failed.';
		echo json_encode(array("status" => "error", "message" => "$message"));
		exit;
	}
} else {
	$message = 'Not Selected';
	echo '{"status": "ok", "message": "' . $message . '"}';
}
