<?php 
/*

NB: minify_with_closure_compiler requires these libraries.
NB include them NOT HERE but in whichever directory this functions.php file itself is being included:

include_once $absolute_root . 'google-closure-compiler/src/Compiler.php';
include_once $absolute_root . 'google-closure-compiler/src/Response.php';
include_once $absolute_root . 'google-closure-compiler/src/exceptions.php';
*/

//->setCompilationLevel('WHITESPACE_ONLY')

/*
//'abc/def/ghi' -> 'ghi'
function last_slug($url) {
	$pos = strrpos($url, '/');
	return $pos === false ? $url : substr($url, $pos + 1);
}
*/


/*
2022-03-05
TODO: See if we're using the old error page at all...should the new 404 page be the error page?
*/

function javascript_integrity_sha256($complete_file_path) {
	if (file_exists($complete_file_path)) {
		$hash = hash_file('sha256', $complete_file_path, true);
		$hash_base64 = base64_encode($hash);
	} else {
		$hash_base64 = '0123456789';
	}
	return "sha256-$hash_base64";
}

function javascript_integrity_sha384($complete_file_path) {
	if (file_exists($complete_file_path)) {
		$hash = hash_file('sha384', $complete_file_path, true);
		$hash_base64 = base64_encode($hash);
	} else {
		$hash_base64 = '0123456789';
	}
	return "sha384-$hash_base64";
}

// 2020-09-04:
// Used in HEAD
function render_initial_page_style_element($page, $css_array) {

// 2021-10-17
// Prepend common-admin-stylings.css to $css_array if logged in as admin
	if (isset($_SESSION['authenticated']) && ($_SESSION['authenticated'] == 'true')) {
		array_unshift($css_array, 'css/common-admin-stylings.css'); 
	}

	$absolute_root = $_SERVER['ABSOLUTE_ROOT'];
	$minified_css = '';

	foreach ($css_array as $css) {
		if (file_exists($absolute_root . $css)) {
			$minified_css .= tovic_minify_css(file_get_contents($absolute_root . $css));
		}
	}

	if ($minified_css != '') {
		echo '	<style class=custom-style data-page=' . $page . ' ' . $GLOBALS['inline_nonce_property'] . '>' . $minified_css . '</style>' . PHP_EOL;
	}

}

// OLD: function render_custom_style_elements($includes_folder) {
function render_custom_style_elements($folder) {

// css_array.inc.php used here and in includes/html/head.php:

	$absolute_root = $_SERVER['ABSOLUTE_ROOT'];
	$minified_css = '';

// OLD:	$css_array_inc = $absolute_root . 'includes/' . $includes_folder . '/css_array.inc.php';
	$css_array_inc = $absolute_root . $folder . '/css_array.inc.php';

//echo $css_array_inc = $absolute_root . $folder . '/css_array.inc.php';


	if (file_exists($css_array_inc)) {

		include $css_array_inc;

		foreach ($css_array as $css) {
			if (file_exists($absolute_root . $css)) {
				$minified_css .= tovic_minify_css(file_get_contents($absolute_root . $css));
			}
			$minified_css .= ' ';
		}

		$minified_css = trim($minified_css);

	}

	echo '	<custom-style class=display-none>' . $minified_css . '</custom-style>' . PHP_EOL;

}


function render_custom_style_elements_body_only_no_tags($css_array) {
	$absolute_root = return_absolute_root();
	$minified_css = '';
	foreach ($css_array as $css) {
		if (file_exists($absolute_root . $css)) {
			$minified_css .= tovic_minify_css(file_get_contents($absolute_root . $css));
		}
	}
	if ($minified_css != '') {
		echo $minified_css;
	}
}


function render_style_elements($css_array, $nonce) {
	$absolute_root = return_absolute_root();
	$minified_css = '';
	foreach ($css_array as $css) {
		if (file_exists($absolute_root . $css)) {
			$minified_css .= tovic_minify_css(file_get_contents($absolute_root . $css));
		}
	}
	if ($minified_css != '') {
		echo '	<style nonce="' . $nonce . '">' . $minified_css . '</style>' . PHP_EOL;
	}
}

// Determines if the $needle is at the end of the $haystack:
function endsWith($haystack, $needle) {
	$length = strlen($needle);
	if ($length == 0) {
		return true;
	}
	return (substr($haystack, -$length) === $needle);
}

// NO: 'ADVANCED_OPTIMIZATIONS'
function minify_with_closure_compiler($source_file, $destination_file, $compilation_level = 'SIMPLE_OPTIMIZATIONS') {

// For kicks:
//$compilation_level = 'ADVANCED_OPTIMIZATIONS';

	if ($_SERVER['HTTP_HOST'] === 'localhost') {
		$localhost = true;
	} else {
		$localhost = false;
	}

	$source_js_code = file_get_contents($source_file);
	$response_good = false;

// On live host:
	if ($localhost === false) {
		$compiler = new GoogleClosureCompiler\Compiler;
		$response = $compiler->setCompilationLevel($compilation_level)->setJsCode($source_js_code)->compile();
		$response_good = $response && $response->isWithoutErrors();
		if ($response_good) {
// To delete all line breaks:
//$cc_minified = preg_replace("/\r|\n/", "", $response->getCompiledCode());
			$cc_minified = $response->getCompiledCode();
		}
// On localhost:
	} else {

// new:
		$jar_folder_on_windows = '/xampp/htdocs/closure-compiler';
		$jar_folder_on_mac = '/Applications/XAMPP/htdocs/closure-compiler';
		$current_jar_file = 'closure-compiler-v20210505.jar';

		if (is_dir($jar_folder_on_windows)) {
			$jar_folder = $jar_folder_on_windows;
		} else if (is_dir($jar_folder_on_mac)) {
			$jar_folder = $jar_folder_on_mac;
		} else {
			echo 'No JAR file folder found';
			exit;
		}

		$jar_file = $jar_folder . '/' . $current_jar_file;

		if (!file_exists($jar_file)) {
			echo 'No JAR file found:   ' . $jar_file . PHP_EOL;
		}

// delete old destination file if it exists:
		if (file_exists($destination_file)) {
			unlink($destination_file);
		}

		$cc_cmd = "java -jar " . $jar_file . " --js=" . $source_file . " --js_output_file=" . $destination_file . " --compilation_level=SIMPLE --rewrite_polyfills=false";
//echo $cc_cmd . PHP_EOL;
		shell_exec($cc_cmd);
		$cc_minified = file_get_contents($destination_file);
//echo $cc_minified . PHP_EOL;
		$response_good = true;
	}

	if ($response_good) {
		file_put_contents($destination_file, $cc_minified);
	} else {
		file_put_contents($destination_file, $source_js_code);
	}

}

/*
function minify_with_closure_compiler($source_file, $destination_file) {
	$source_js_code = file_get_contents($source_file);
	$source_js_code = str_replace("\t", "    ", $source_js_code);
	$compiler = new GoogleClosureCompiler\Compiler;
	$response = $compiler->setJsCode($source_js_code)->compile();
	if ($response && $response->isWithoutErrors()) {
		// To delete all line breaks:
		//$cc_minified = preg_replace("/\r|\n/", "", $response->getCompiledCode());
		$cc_minified = $response->getCompiledCode();
		file_put_contents($destination_file, $cc_minified);
	} else {
		file_put_contents($destination_file, $source_js_code);
	}
}
*/

function recursiveSubfolderSearch($folder, $regexPattern) {
	$dir = new RecursiveDirectoryIterator($folder);
	$ite = new RecursiveIteratorIterator($dir);
	$files = new RegexIterator($ite, $regexPattern, RegexIterator::GET_MATCH);
	$fileList = array();
	foreach($files as $file) {
		$file = str_replace('\\', '/', $file);
		$fileList = array_merge($fileList, $file);
	}
	return $fileList;
}

function return_absolute_root() {
	$absolute_root = $_SERVER['ABSOLUTE_ROOT'];
	return $absolute_root;
}

function return_relative_root() {

// e.g., /shell/ or /shell/about/
// OLD:	$number_of_slashes = substr_count($_SERVER['REQUEST_URI'], "/");
// TEST, 2021-11-03:
// NEW: $number_of_slashes = substr_count($_SERVER['SCRIPT_NAME'], "/");

	$number_of_slashes = substr_count($_SERVER['REQUEST_URI'], "/");

// FOR ERROR TESTING:
// $number_of_slashes += 1;

	//$number_of_slashes = substr_count($_SERVER['CONTEXT_DOCUMENT_ROOT'], "/");

	if ($_SERVER['HTTP_HOST'] === $domain_name) {
		$number_of_super_folders_to_root = $number_of_slashes - 1;
	} else {
		$number_of_super_folders_to_root = $number_of_slashes - 2;
	}
// NB: OKAY TO DELETE AFTER DOMAIN TRANSFER:
	if ($_SERVER['HTTP_HOST'] === 'ecbiz261.inmotionhosting.com') {
		$relative_root = $_SERVER['CONTEXT_DOCUMENT_ROOT'] . '/';
	}

	$relative_root = '';
	for ($i = 0; $i < $number_of_super_folders_to_root; $i++) {
		$relative_root .= '../';
	}
//$relative_root = '/Applications/XAMPP/htdocs/shell/';
	return $relative_root;

}

// NB: The return string from autoversion_lazyload() is:
//
// $base64 . '" data-src="' . $autoversioned_img . '" data-lazyload="true';
//
// Hence, the src value, the data-src and its value, and data-lazyload=true 
// will be returned as a string.

// NB: During development, set $use_webp to FALSE; change to TRUE once all 
// images are ready and webps have been generated. Do here and at beginning 
// of autoVersion():

function autoversion_lazyload($img, $use_webp = true) {

	$use_single_pixel_base64_img = true;

	if ($use_single_pixel_base64_img) {
		$base64 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=';
	} else {
// THIS IS EXPENSIVE; SINGLE PIXEL METHOD SEEMS BETTER
// make a scaled base64 that scales to size:
		$absolute_root = return_absolute_root();
// Write it to a text file that can be retrieved later, as make_base64_png() is an expensive procedure, time-wise:
		$base64_storage_file = $absolute_root . $img . '.base64.txt';
		if (file_exists($base64_storage_file)) {
			$base64 = file_get_contents($base64_storage_file);
		} else {
			$base64 = make_base64_png($img);
			file_put_contents($base64_storage_file, $base64);
		}
	}
	$autoversioned_img = autoVersion($img, $use_webp);

	return $base64 . '" data-src="' . $autoversioned_img . '" data-lazyload="true';
}

function make_base64_png($img) {
// exclude base64 images
	$absolute_root = return_absolute_root();
	$jpg = $absolute_root . $img;
// Get $width and $height of JPG
	list($width, $height, $type, $attr) = getimagesize($jpg);
// Create transparent PNG with same width and height as JPG:
	$image = imagecreatetruecolor($width, $height);
	$transparent = imagecolorallocate($image, 0, 0, 0);
	imagecolortransparent($image, $transparent);

// To avoid having to write the image to disk to collect its contents to perform a base64 encode, save it to a buffer stream:
	ob_start(); // Start buffering
	$imageData = imagepng($image);
	$imageData = ob_get_contents(); // store image data
	ob_end_clean(); // end and clear buffer

// Now convert it to base64 and prefix standard data info:
	$base64 = 'data:image/png;base64,' . base64_encode($imageData);
	imagedestroy($image);
	return $base64;
}

// NB: During development, set $use_webp to FALSE; change to TRUE once all 
// images are ready and webps have been generated. Do here and at beginning 
// of autoversion_lazyload():

// qwer
//function autoVersion($url, $use_webp = true) {
function autoVersion($url, $use_webp = true) {

// 2021-11-09:
// THIS DOESN'T WORK, REASON UNKNOWN:
//	$use_webp = true;
//	if ($use_webp == 'no_webp') {
//		$use_webp = false;
//	}

// Set $external_site = TRUE if the resource is not on the localhost or parent domain;
// write original code for that HERE.

	$external_site = FALSE;
	$absolute_root = return_absolute_root();

// 2021-04-26:
// Needed to accept new SSL certificate for PHP 8 on localhost
	$context = stream_context_create( [
		'ssl' => [
			'verify_peer' => false,
			'verify_peer_name' => false,
		],
	]);

// If we're retrieving static content from an external site:
	if ($external_site) {

// 2021-04-26:
//		$headers = get_headers($url, 1);
		$headers = get_headers($url, 1, $context);

		if ($headers && (strpos($headers[0], '200') !== FALSE)) {
			$time = strtotime($headers['Last-Modified']);
			$date = date("YmdHis", $time);
		} else {
			$date = '19990221125549';
		}
// Otherwise, we're retrieving it from the dynamic server or from the local server:
	} else {
// filemtime can't handle cross-origin requests, sigh...
		if ((substr($url, 0, 8) == "https://") || (substr($url, 0, 7) == "http://") || (substr($url, 0, 2) == "//")) {
			return $url;
		} else {
// 2019-04-07
// qwer

// 2020-08-07:
			if (($use_webp == true) && (isset($_SESSION['webp_support']) && !empty($_SESSION['webp_support']) && ($_SESSION['webp_support'] === true))) {

				//$ext = substr($url, -3);
				$ext = substr(strrchr($url, '.'), 1);

				if (($ext === 'jpg') || ($ext === 'jpeg') || ($ext === 'png') || ($ext === 'gif')) {
					if ($ext === 'jpg') {
						$webp_url = str_replace('.jpg', '.webp', $url);
					}
					if ($ext === 'jpeg') {
						$webp_url = str_replace('.jpeg', '.webp', $url);
					}
					if ($ext === 'png') {
						$webp_url = str_replace('.png', '.webp', $url);
					}
					if ($ext === 'gif') {
						$webp_url = str_replace('.gif', '.webp', $url);
					}
// This file check for the existence of WEBP files really slows it down.
// Consider using '$use_webp = false' in the function call during development;
// WEBPs should be generated only as the project is wrapping up.
					//if (file_exists($relative_root . $webp_url)) {
						$url = $webp_url;
					//}
				}
			}

/*
// 2022-03-05:
// OLD:
			$relative_url = $relative_root . $url;
			if (file_exists($relative_url)) {
				$date = date("YmdHis", filemtime($relative_url));
			} else {
				$date = '19990221125549';
			}
*/
			$absolute_root = return_absolute_root();
			$absolute_url = $absolute_root . $url;
			if (file_exists($absolute_url)) {
				$date = date("YmdHis", filemtime($absolute_url));
			} else {
				$date = '19990221125549';
			}

		}
	}
//file_put_contents('x.txt', $url . PHP_EOL, FILE_APPEND);
// Add date & time stamp to resource name: https://www.example.com/js/defer.min.20160221212802.js
	$name = explode('.', $url);
	$extension = array_pop($name);
	array_push($name, $date, $extension);
	$fullname = implode('.', $name);

	return $fullname;
}

/* Copy of master-list.css into individual-imports.css in which each imported CSS file is auto-versioned */

function update_individual_imports_css() {

	$absolute_root = return_absolute_root();
	$master_list_template_css = $absolute_root . 'css/master-list.TEMPLATE.css';
	$individual_imports_css = $absolute_root . 'css/individual-imports.css';

	if (file_exists($master_list_template_css)) {

		$file = fopen($master_list_template_css, "r");
		$array = [];

		while (!feof($file)) {
			$line = trim(fgets($file));
			if ($line !== '') {
				preg_match('~"(.*?)"~', $line, $filename);
				$auto_versioned_filename = autoVersion('css/' . $filename[1]);
// strip off the leading 'css/' regardless:
				$auto_versioned_filename = substr($auto_versioned_filename, 4);
				$line = str_replace($filename[1], $auto_versioned_filename, $line);
				array_push($array, $line);
			}
		}
		fclose($file);

		$fh = fopen($individual_imports_css, 'w') or die("can't open file");
		foreach ($array as $index => $string) {
			fwrite($fh, $string . PHP_EOL);
		}
		fclose($fh);

	} else {
		echo $master_list_template_css . ' could not be found...procedure aborted.' . PHP_EOL . PHP_EOL;
	}
}

function greatest_common_denominator($a, $b) {
	$a = abs($a);
	$b = abs($b);
	if ($a < $b) {
		list($b, $a) = Array($a, $b);
	}
	if ($b == 0) {
		return $a;
	}
	$r = $a % $b;
	while ($r > 0) {
		$a = $b;
		$b = $r;
		$r = $a % $b;
	}
	return $b;
}

function simplify($num, $den) {
	$g = greatest_common_denominator($num, $den);
	if (($g === 0) || ($g === null)) {
		$g = 1;
	}
	return Array($num / $g, $den / $g);
}

// qwer
function aspect_ratio_image_stats($image_url) {

	$arr = array();
	$image_width = 0;
	$image_height = 0;
	$aspect_width = 0;
	$aspect_height = 0;

	$absolute_root = return_absolute_root();
//echo $relative_root . $image_url;

	if (file_exists($absolute_root . $image_url)) {
		list($image_width, $image_height, $type, $attr) = getimagesize($absolute_root . $image_url);
		$ratio = simplify($image_width, $image_height);
		$aspect_width = $ratio[0];
		$aspect_height = $ratio[1];
	}

	$arr['image_width'] = $image_width;
	$arr['image_height'] = $image_height;
	$arr['aspect_width'] = $aspect_width;
	$arr['aspect_height'] = $aspect_height;

	return $arr;

}

/*
// SEE: https://medium.com/hceverything/applying-srcset-choosing-the-right-sizes-for-responsive-images-at-different-breakpoints-a0433450a4a3
// 768, 825, 875, 1024, 1366, 1600
// 1600 --> For lightbox display
*/

function aspect_ratio_img_tag($image_url, $alt) {

/*
// 2022-09-08:
// OLD...we're dropping $aspect_ratio_lazyload:
//
// NB: $GLOBALS['aspect_ratio_lazyload'] === $aspect_ratio_lazyload, 
// which is defined in includes/variables.php, along with $use_srcset

// 2020-08-07:
	if (isset($GLOBALS['aspect_ratio_lazyload']) && !empty($GLOBALS['aspect_ratio_lazyload']) && ($GLOBALS['aspect_ratio_lazyload'] === true)) {

// NB: The return string from autoversion_lazyload() is:
//
// $base64 . '" data-src="' . $autoversioned_img . '" data-lazyload="true';
//
// Hence, the src value, the data-src and its value, and data-lazyload=true 
// will be returned as a string.

// WARNING: It makes the site INCREDIBLY sluggish if scaled base64 
// transparencies are used--see autoversion_lazyload() function...
// UNLESS the site is interrogated by the service worker first. That 
// interrogation causes the TXT versions of the base64 images to be created.

		$image_src = autoversion_lazyload($image_url, $use_webp = true);
	} else {
		$image_src = autoVersion($image_url, $use_webp = true);
	}
*/

	$image_src = autoVersion($image_url, $use_webp = true);

	$i = aspect_ratio_image_stats($image_url);
	$image_width = $i['image_width'];
	$image_height = $i['image_height'];
	$aspect_width = $i['aspect_width'];
	$aspect_height = $i['aspect_height'];


// NB: See above. $image_src for lazyload will be: 
// $base64 . '" data-src="' . $autoversioned_img . '" data-lazyload="true';

	$img_element = '<img src="' . $image_src . '" alt="' . $alt . '" width=' . $image_width . ' height=' . $image_height . ' data-aspect-width=' . $aspect_width . ' data-aspect-height=' . $aspect_height . '>' . PHP_EOL;

//EXPERIMENT:	$img_element = '<img loading=lazy src="' . $image_src . '" alt="' . $alt . '" width=' . $image_width . ' height=' . $image_height . ' data-aspect-width=' . $aspect_width . ' data-aspect-height=' . $aspect_height . '>' . PHP_EOL;

	return $img_element;
}


function splice_size($url, $splice) {
	$last_dot_pos = strrpos($url, '.');
	if ($last_dot_pos === false) {
		return false;
	}
	$prefix = substr($url, 0, $last_dot_pos);
	$suffix = substr($url, $last_dot_pos);
	return $prefix . $splice . $suffix;
}

// SEE: https://medium.com/@woutervanderzee/responsive-images-with-srcset-and-sizes-fc434845e948
//	$sizes = '(min-width: 761px) 50vw, 100vw';

// Translation: when the viewport is 761px or greater, the image will occupy
// only 50vw (~50% of the viewport) otherwise, it will fill the entire
// viewport (100vw).

// To determine the correct 'sizes' string, you must determine the break-points
// for each image on your site:
//
// * Open the page in question and target an image for analysis.
// * Use Chrome Developer Tools to open the 'responsive' mobile view.
// * Edit the 'width' of the responsive view to find the break point(s)

// For each break point, determine the CSS formula for the size of the image
// using a calc(), such as (max-width: 759px) calc(90vw - 4em), (min-width: 760px) calc(45vw - 4.5em)

// That's problem number 1. Next, with the correct 'sizes' attribute in place,
// determine which image sizes should be available to the site provided those
// break-point image sizes.

// To determine desired server image file size using PICTURE and srcset:
// 
// * Turn OFF service worker in variables.php and reopen incognito tab
// * Open Chrome Developer Tools and switch to mobile.
// * Select Nexus 5x, which is the mobile device Google uses for its audit.
// * Load site to page with image that is the subject of the analysis.
// * Right click the image and choose Inspect to see its display in the DOM
// * Hover over IMG tag within PICTURE tag.
// * If the PICTURE element is functioning correctly, a balloon with info in
// this format will appear:
//
// 350 x 350 pixels (Intrinsic: 875 x 875 pixels)
//
// This means the image fills 350x350 on screen, but the 875px image file
// was chosen for use. The Nexus 5x has a 2.5 pixel density, so a 350x350
// slot is equivalent to 875x875, so for that device, an 875px image is needed.
//
// NB: To get the pixel density for a given mobile device, again use the mobile
// view in Chrome and load this site:
//
// https://www.mydevice.io/#tab1
//
// The pixel density is the "CSS pixel-ratio" in the site's report.
//
// window.devicePixelRatio; in console returns exact device pixel ratio
//
// For each image on the page, Lighthouse compares the size of the rendered
// image against the size of the actual image. The rendered size also accounts
// for device pixel ratio. If the rendered size is at least 25KB smaller than
// the actual size, then the image fails the audit.
//
// https://web.dev/uses-responsive-images/
// https://web.dev/serve-images-with-correct-dimensions/
// https://web.dev/codelab-serve-images-correct-dimensions/
// https://web.dev/serve-responsive-images/
// https://amp.dev/documentation/guides-and-tutorials/develop/style_and_layout/art_direction/
//
// https://medium.com/hceverything/applying-srcset-choosing-the-right-sizes-for-responsive-images-at-different-breakpoints-a0433450a4a3
// https://cloudfour.com/thinks/responsive-images-101-definitions/

// ???? NB: If we swap out the srcset with a dummy, the picture element sources
// are ignored and the benefit is lost! So, don't use srcset dummies!

// For IMG elements whose width and height will be variable in the HTML, but 
// need to be at different sizes for DPR selection purposes by the browser.
// For pictures whose width and height will be fixed in the HTML, but which 
// need to be at different sizes for DPR selection purposes by the browser.


// 2020-08-15:
//
// Aspect ratio boxes (see aspect-ratio.css) cannot have an explicit pixel 
// width and height provided, or the technique will fail.
//
// However, the Lighthouse Audit demands explicit height and width.
//
// Using 100% works with the aspect ratio box technique and with the
// Lighthouse audit.
//
// So, whenever calling:
// $img_attribute_array = return_img_attribute_array($alt, $centered_image, $waypoints, $width, $height);
// ...use 100% for $width and $height when NOT using explict pixel sizes 
// (e.g., news item images, site logo, etc.)

// compose and return the img_attribute_array needed by picture_or_img_element():
function return_img_attribute_array($alt, $centered_image, $waypoints, $width, $height) {

// 2020-08-15:
//	$waypoints = false;

	$img_attribute_array = array();

	if ($alt) {
		$img_attribute_array['alt'] = $alt;
	}

// 2021-12-06:
// We're not using 'centered_image' in the CSS or JavaScript anywhere...
//	if ($centered_image) {
//		$img_attribute_array['class'] = 'centered_image';
//	}

	if ($waypoints) {
		if ($GLOBALS['use_browser_based_lazy_loading'] == false) {
			$img_attribute_array['data-lazyload-mobile-offset-percent'] = '100%';
		} else {
			$img_attribute_array['loading'] = 'lazy';
		}
	}

	if ($width) {
		$img_attribute_array['width'] = $width;
	}
	if ($height) {
		$img_attribute_array['height'] = $height;
	}

	return $img_attribute_array;

};

// NEW METHOD:

function picture_or_img_element($picture, $variable_size, $img_url, $img_widths, $multipliers, $sizes, $picture_attribute_array, $img_attribute_array, $tabs, $lazyload, $social_share) {

// $use_browser_based_lazy_loading is defined in includes/common_variables/variables.php
	$use_browser_based_lazy_loading = $GLOBALS['use_browser_based_lazy_loading'];

//file_put_contents('x.txt', $img_url . PHP_EOL, FILE_APPEND);

// 2020-07-18: $img_url must include full extension:
//
// Everything up to the extension:

	$img_url_without_extension = substr($img_url, 0, strrpos($img_url, '.'));
// The extension:
	$extension = strrchr($img_url, '.');

	if (($extension === '.jpg') || ($extension === '.jpeg')) {
		$mime_type = 'image/jpeg';
	}
	if ($extension === '.png') {
		$mime_type = 'image/png';
	}
	if ($extension === '.gif') {
		$mime_type = 'image/gif';
	}

	$src_array = [];
	$src_array_jpg = [];
	$src_array_webp = [];

	$img_widths_count = count($img_widths);

	for ($x = 0; $x < $img_widths_count; $x++) {
		$url = $img_url_without_extension . '-' . $img_widths[$x] . 'px' . $extension;
		array_push($src_array, autoVersion($url, $use_webp = true));
		array_push($src_array_jpg, autoVersion($url, $use_webp = false));
		array_push($src_array_webp, autoVersion($url, $use_webp = true));
	}

// NB: We use the largest image as the 'src' in the <img> tag, because
// that is the image that will be used for the lightbox:
	$last_src_array_index = count($src_array) - 1;
//	$last_src_array_index = 0;

// For variable size images to maintain aspect ratio:
	$i = aspect_ratio_image_stats($img_url_without_extension . '-' . $img_widths[$img_widths_count - 1] . 'px' . $extension);
	$image_width = $i['image_width'];
	$image_height = $i['image_height'];
	$aspect_width = $i['aspect_width'];
	$aspect_height = $i['aspect_height'];

// 2020-08-015:
//
// Aspect ratio boxes (see aspect-ratio.css) cannot have an explicit pixel 
// width and height provided, or the technique will fail.
//
// However, the Lighthouse Audit demands explicit height and width.
//
// Using 100% works with the aspect ratio box technique and with the
// Lighthouse audit.
//
// So, whenever calling:
// $img_attribute_array = return_img_attribute_array($alt, $centered_image, $waypoints, $width, $height);
// ...use 100% for $width and $height when NOT using explict pixel sizes 
// (e.g., news item images, site logo, etc.)

// We also need to determine how $use_browser_based_lazy_loading = true; is
// being used throughout the code of the site.
// 

// 2020-10-04
// UPDATE: 100% now serves as a flag to use actual pixel width and 
// height on IMG element in picture_or_img_element(). The trick when
// using aspect ration boxes is to set width: 100% and height: 100%
// on the image using CSS, which is now done in common.css.


// 1x1 transparent PNG:
	$src_dummy = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=';

	$srcset = '';
	$srcset_jpg = '';
	$srcset_webp = '';
	$srcset_dummy = '';

	if ($social_share == true) {
// Derive social share image name from first srcset JPG name:
		$url = $img_url_without_extension . '-' . $img_widths[0] . 'px' . $extension;

// Splice in '/social-share/':
		$before_last_slash = substr($url, 0, strrpos($url, '/'));
		$pos = strrpos($url, '/');
		$after_last_slash = $pos === false ? $url : substr($url, $pos + 1);
		$url = $before_last_slash . '/social-share/' . $after_last_slash;

// Replace everything following final hyphen with '-1200x630px.jpg':
		$before_last_hyphen = substr($url, 0, strrpos($url, '-'));
		$pos = strrpos($url, '-');
		$after_last_hyphen = $pos === false ? $url : substr($url, $pos + 1);
		$url = $before_last_hyphen . '-1200x630px' . $extension;

// Unless we learn otherwise, the social share image should never be a webp,
// so use JPG always:
		$data_social_share = autoVersion($url, $use_webp = false);
	} else {
		$data_social_share = 'default';
	}

	if ($variable_size == true) {
		$srcset_unit = 'w';
	} else {
		$srcset_unit = 'x';
	}

	for ($x = 0; $x < count($img_widths); $x++) {

		if ($srcset !== '') {
			$srcset       .= ', ';
			$srcset_jpg   .= ', ';
			$srcset_webp  .= ', ';
			$srcset_dummy .= ', ';
		}

		if ($variable_size == true) {
			$srcset .=       $src_array[$x] .      ' ' . $img_widths[$x] . $srcset_unit;
			$srcset_jpg .=   $src_array_jpg[$x] .  ' ' . $img_widths[$x] . $srcset_unit;
			$srcset_webp .=  $src_array_webp[$x] . ' ' . $img_widths[$x] . $srcset_unit;
			$srcset_dummy .= $src_dummy .          ' ' . $img_widths[$x] . $srcset_unit;
		} else {
			$srcset .=       $src_array[$x] .      ' ' . $multipliers[$x] . $srcset_unit;
			$srcset_jpg .=   $src_array_jpg[$x] .  ' ' . $multipliers[$x] . $srcset_unit;
			$srcset_webp .=  $src_array_webp[$x] . ' ' . $multipliers[$x] . $srcset_unit;
			$srcset_dummy .= $src_dummy .          ' ' . $multipliers[$x] . $srcset_unit;
		}

	}

// We cannot have width or height values in the IMG element when we are using
// the aspect ratio CSS styling. The routines that call this function (the one
// we're in) that are using aspect ratio CSS have indicated '100%' for width
// and height in the IMG attributes. Other routines may be passing in actual
// values that are necessary. So, look in the $img_attribute_array for 'width'
// and 'height' keys. If found, see if their values are '100%'. If so, remove
// them from the array:

// 2020-10-04:
// UPDATE: The above is FALSE. We can specify the pixel-unit width and height 
// in an aspect ratio image provided that there is a corresponding CSS rule
// for the element with width: 100% and height: 100%!
// The flag for using this is now specifying '100%' for width and height values
// being passed into this function:
	if (array_key_exists('width', $img_attribute_array)) {
		if ($img_attribute_array['width'] == '100%') {
// 2020-10-04:
// OLD:			unset($img_attribute_array['width']);
			$img_attribute_array['width'] = $image_width;
		}
	}

	if (array_key_exists('height', $img_attribute_array)) {
		if ($img_attribute_array['height'] == '100%') {
// 2020-10-04:
// OLD:			unset($img_attribute_array['height']);
			$img_attribute_array['height'] = $image_height;
		}
	}

// Used in both <PICTURE> and <IMG> elements:
	$img_attributes = '';
	foreach($img_attribute_array as $key => $value) { 
		$img_attributes .= ' ' . $key . '="' . $value . '"';  
	}

// PICTURE ELEMENT:
	if ($picture == true) {

		$picture_attributes = '';
		foreach($picture_attribute_array as $key => $value) { 
			$picture_attributes .= ' ' . $key . '="' . $value . '"';  
		} 

		$p1 = '<picture' . $picture_attributes . '>';

		if ($variable_size == true) {
			$source_attributes = '' . 
				' sizes="' . $sizes . '"' . 
				'';
			$img_attributes .= '' .
				' data-aspect-height=' . $aspect_height . 
				' data-aspect-width=' . $aspect_width . 
				'';
		} else {
			$source_attributes = '';
		}

// PICTURE: LAZYLOAD:
		if ($lazyload == true) {

// PICTURE: LAZYLOAD: BROWSER:
			if ($use_browser_based_lazy_loading == true) {
				$p2 = '<source' .
							' srcset="' . $srcset_webp . '"' . 
							' type="image/webp"' . 
								$source_attributes . 
							'>';
				$p3 = '<source' .
							' srcset="' . $srcset_jpg . '"' .
							' type="' . $mime_type . '"' .
								$source_attributes . 
							'>';
				$p4 = '<img' . 
							' src="' . $src_array_jpg[$last_src_array_index] . '"' . 
							' loading=lazy' . 
							' data-social-share="' . $data_social_share . '"' . 
								$img_attributes . 
							'>';
// PICTURE: LAZYLOAD: JAVASCRIPT:
			} else {
				$p2 = '<source' .
							' srcset="' . $srcset_dummy . '"' . 
							' data-srcset="' . $srcset_webp . '"' .
							' type="image/webp"' .
								$source_attributes . 
							'>';
				$p3 = '<source' . 
							' srcset="' . $srcset_dummy . '"' .
							' data-srcset="' . $srcset_jpg . '"' . 
							' type="' . $mime_type . '"' . 
								$source_attributes . 
							'>';
				$p4 = '<img' .
							' src="' . $src_dummy . '"' . 
							' data-src="' . $src_array_jpg[$last_src_array_index] . '"' . 
							' data-lazyload=true' . 
							' data-social-share="' . $data_social_share . '"' .
								$img_attributes . 
							'>';
			}

// PICTURE: NO LAZYLOAD:
		} else {
			$p2 = '<source' .
						' srcset="' . $srcset_webp . '"' . 
						' type="image/webp"' . 
							$source_attributes . 
						'>';
			$p3 = '<source' .
						' srcset="' . $srcset_jpg . '"' .
						' type="' . $mime_type . '"' .
							$source_attributes . 
						'>';
			$p4 = '<img' . 
						' src="' . $src_array_jpg[$last_src_array_index] . '"' . 
						' data-social-share="' . $data_social_share . '"' .
							$img_attributes . 
						'>';
		}

		$p5 = '</picture>';

		$p1 = str_repeat('	', $tabs) . $p1 . PHP_EOL;
		$p2 = str_repeat('	', $tabs + 1) . $p2 . PHP_EOL;
		$p3 = str_repeat('	', $tabs + 1) . $p3 . PHP_EOL; 
		$p4 = str_repeat('	', $tabs + 1) . $p4 . PHP_EOL;
		$p5 = str_repeat('	', $tabs) . $p5 . PHP_EOL;

		$picture_element = $p1 . $p2 . $p3 . $p4 . $p5;

		$element = $picture_element;

// IMG ELEMENT:
	} else {

// IMG: LAZYLOAD
		if ($lazyload == true) {

// IMG: LAZYLOAD: BROWSER:
			if ($use_browser_based_lazy_loading == true) {

// IMG: LAZYLOAD: BROWSER: WEBP
				if ($_SESSION['webp_support'] == true) {
					$img_element = 	'<img' .
													' src="' . $src_array_webp[$last_src_array_index] . '"' . 
													' srcset="' . $srcset_webp . '"' . 
													' loading=lazy' . 
													' data-social-share="' . $data_social_share . '"' . 
														$img_attributes . 
													'>';

// IMG: LAZYLOAD: BROWSER: JPG
				} else {
					$img_element = 	'<img' .
													' src="' . $src_array_jpg[$last_src_array_index] . '"' . 
													' srcset="' . $srcset_jpg . '"' . 
													' loading=lazy' . 
													' data-social-share="' . $data_social_share . '"' . 
														$img_attributes . 
													'>';
				}

// IMG: LAZYLOAD: JAVASCRIPT:
			} else {

// IMG: LAZYLOAD: JAVASCRIPT: WEBP
				if ($_SESSION['webp_support'] == true) {
					$img_element = 	'<img' .
													' src="' . $src_dummy . '"' . 
													' data-src="' . $src_array_webp[$last_src_array_index] . '"' . 
													' srcset="' . $srcset_dummy . '"' . 
													' data-srcset="' . $srcset_webp . '"' . 
													' data-social-share="' . $data_social_share . '"' . 
													$img_attributes . 
													' data-lazyload=true' . 
													'>';

// IMG: LAZYLOAD: JAVASCRIPT: JPG
				} else {
					$img_element = 	'<img' .
													' src="' . $src_dummy . '"' . 
													' data-src="' . $src_array_jpg[$last_src_array_index] . '"' . 
													' srcset="' . $srcset_dummy . '"' . 
													' data-srcset="' . $srcset_jpg . '"' . 
													' data-social-share="' . $data_social_share . '"' . 
													$img_attributes . 
													' data-lazyload=true' . 
													'>';
				}

			}

// IMG: NO LAZYLOAD
		} else {
// IMG: WEBP
			if ($_SESSION['webp_support'] == true) {
				$img_element = 	'<img' .
												' src="' . $src_array_webp[$last_src_array_index] . '"' . 
												' srcset="' . $srcset_webp . '"' . 
												' data-social-share="' . $data_social_share . '"' . 
													$img_attributes . 
												'>';
// IMG: JPG
			} else {
				$img_element = 	'<img' .
												' src="' . $src_array_jpg[$last_src_array_index] . '"' . 
												' srcset="' . $srcset_jpg . '"' . 
												' data-social-share="' . $data_social_share . '"' . 
													$img_attributes . 
												'>';
			}
		}

		$img_element = str_repeat('	', $tabs) . $img_element . PHP_EOL;

		$element = $img_element;

	}

	return $element;

}

// 2021-03-31:
function link_preload_img_srcset($img_url, $variable_size, $img_widths, $multipliers, $sizes, $tabs) {

/*

SEE: https://web.dev/preload-responsive-images/

<link 
rel=preload 
as=image 
href=images/home/landing-image-set/a01/a01.20210330223318.webp imagesrcset="images/home/landing-image-set/a01/a01-683px.20210330223318.webp 683w, images/home/landing-image-set/a01/a01-768px.20210330223318.webp 768w, images/home/landing-image-set/a01/a01-804px.20210330223318.webp 804w, images/home/landing-image-set/a01/a01-918px.20210330223318.webp 918w, images/home/landing-image-set/a01/a01-1024px.20210330223317.webp 1024w, images/home/landing-image-set/a01/a01-1225px.20210330223317.webp 1225w" 
imagesizes="(max-width: 580px) 87vw, (min-width: 580px) 50vw">

*/

// 2020-07-18: $img_url must include full extension:
//
// Everything up to the extension:
	$img_url_without_extension = substr($img_url, 0, strrpos($img_url, '.'));
// The extension:
	$extension = strrchr($img_url, '.');

	if (($extension === '.jpg') || ($extension === '.jpeg')) {
		$mime_type = 'image/jpeg';
	}
	if ($extension === '.png') {
		$mime_type = 'image/png';
	}
	if ($extension === '.gif') {
		$mime_type = 'image/gif';
	}

	$src_array = [];
	$src_array_jpg = [];
	$src_array_webp = [];

	$img_widths_count = count($img_widths);

	for ($x = 0; $x < $img_widths_count; $x++) {
		$url = $img_url_without_extension . '-' . $img_widths[$x] . 'px' . $extension;
		array_push($src_array, autoVersion($url, $use_webp = true));
		array_push($src_array_jpg, autoVersion($url, $use_webp = false));
		array_push($src_array_webp, autoVersion($url, $use_webp = true));
	}

// NB: We use the largest image as the 'src' in the <img> tag, because
// that is the image that will be used for the lightbox:
	$last_src_array_index = count($src_array) - 1;
//	$last_src_array_index = 0;

// For variable size images to maintain aspect ratio:
	$i = aspect_ratio_image_stats($img_url_without_extension . '-' . $img_widths[$img_widths_count - 1] . 'px' . $extension);
	$image_width = $i['image_width'];
	$image_height = $i['image_height'];
	$aspect_width = $i['aspect_width'];
	$aspect_height = $i['aspect_height'];

	$srcset = '';
	$srcset_jpg = '';
	$srcset_webp = '';


	if ($variable_size == true) {
		$srcset_unit = 'w';
	} else {
		$srcset_unit = 'x';
	}

	for ($x = 0; $x < count($img_widths); $x++) {

		if ($srcset !== '') {
			$srcset       .= ', ';
			$srcset_jpg   .= ', ';
			$srcset_webp  .= ', ';
		}

		if ($variable_size == true) {
			$srcset .=       $src_array[$x] .      ' ' . $img_widths[$x] . $srcset_unit;
			$srcset_jpg .=   $src_array_jpg[$x] .  ' ' . $img_widths[$x] . $srcset_unit;
			$srcset_webp .=  $src_array_webp[$x] . ' ' . $img_widths[$x] . $srcset_unit;
		} else {
			$srcset .=       $src_array[$x] .      ' ' . $multipliers[$x] . $srcset_unit;
			$srcset_jpg .=   $src_array_jpg[$x] .  ' ' . $multipliers[$x] . $srcset_unit;
			$srcset_webp .=  $src_array_webp[$x] . ' ' . $multipliers[$x] . $srcset_unit;
		}

	}

// NB: $_SESSION['browser'] is recorded in includes/common/content_security_policy.inc.php

// 2021-09-28:
// Caught by HTML validator when adding merchandise image preloads:
	if ($sizes == '') {
		$imagesizes_attribute = '';
	} else {
		$imagesizes_attribute = ' imagesizes="' . $sizes . '"';
	}

// IMG: WEBP
	if ($_SESSION['webp_support'] == true) {
		$link_element = 	'<link' .
										' rel=preload' . 
										' as=image' .  
										' type=image/webp' . 
// 2021-10-09
// https://html.spec.whatwg.org/multipage/semantics.html#attr-link-imagesrcset
//
// "Note how we omit the href attribute, as it would only be relevant for 
// browsers that do not support imagesrcset, and in those cases it would likely
// cause the incorrect image to be preloaded."
//
//										' href="' . $img_url_without_extension . '.webp"' . 
										' imagesrcset="' . $srcset_webp . '"' . 
										$imagesizes_attribute .
										'>';
// IMG: JPG
	} else {
		$link_element = 	'<link' .
										' rel=preload' . 
										' as=image' . 
										' type=image/jpeg' . 
// 2021-10-09
// https://html.spec.whatwg.org/multipage/semantics.html#attr-link-imagesrcset
//
// "Note how we omit the href attribute, as it would only be relevant for 
// browsers that do not support imagesrcset, and in those cases it would likely
// cause the incorrect image to be preloaded."
//
//										' href="' . $img_url_without_extension . $extension . '"' . 
										' imagesrcset="' . $srcset_jpg . '"' . 
										$imagesizes_attribute .
										'>';
	}

	$link_element = str_repeat('	', $tabs) . $link_element . PHP_EOL;

	$element = $link_element;

	return $element;

}

//////////////////////////////////////////////////////

// ALMOST retired 2021-03-31
// This should likely be replaced by:
//
// link_preload_img_srcset()
//
// SEE: https://www.chromestatus.com/feature/5164259990306816
// ALSO SEE: https://web.dev/preload-responsive-images
function link_preload_srcset_tag2($image_url, $alt, $srcset_sizes) {

	$image_src_array = [];
	$srcset_sizes_count = count($srcset_sizes);

/*
// 2022-09-08:
// OLD...we're dropping $aspect_ratio_lazyload:
//
// NB: $GLOBALS['aspect_ratio_lazyload'] === $aspect_ratio_lazyload, 
// which is defined in includes/variables.php, along with $use_srcset
//

	if ($GLOBALS['aspect_ratio_lazyload'] === true) {

// When the ServiceWorker first interrogates the site, the simple act of 
// visiting the gallery (behind the scenes, asynchronously)
// will cause the TXT versions of the base64 placeholders to be created. 
// Thereafter, they'll be available for retrieving by this same function,
// as autoversion_lazyload does not make a TXT file of the base64 image 
// if it already exists in the folder.
//
// So, make base64 version of MAIN image only; those in the srcset will be 
// "hidden" because their tags will be data-srcset until they pass into view,
// at which point the JavaScript will rename 'data-srcset' to 'srcset', just 
// as it will switch 'data-src' to 'src':
//
// NB: The return string from autoversion_lazyload() is:
//
// $base64 . '" data-src="' . $autoversioned_img . '" data-lazyload="true';
//
// Hence, the src value, the data-src and its value, and data-lazyload=true 
// will be returned as a string.

		$image_src = autoversion_lazyload($image_url, $use_webp = true);

		for ($x = 0; $x < $srcset_sizes_count; $x++) {
			$url = splice_size($image_url, '-' . $srcset_sizes[$x] . 'px');

			array_push($image_src_array, autoVersion($url, $use_webp = true));

		}
	} else {

		$image_src = autoVersion($image_url, $use_webp = true);

		for ($x = 0; $x < $srcset_sizes_count; $x++) {
			$url = splice_size($image_url, '-' . $srcset_sizes[$x] . 'px');

			array_push($image_src_array, autoVersion($url, $use_webp = true));

		}
	}
*/

		$image_src = autoVersion($image_url, $use_webp = true);
		for ($x = 0; $x < $srcset_sizes_count; $x++) {
			$url = splice_size($image_url, '-' . $srcset_sizes[$x] . 'px');
			array_push($image_src_array, autoVersion($url, $use_webp = true));
		}

	$i = aspect_ratio_image_stats($image_url);
	$image_width = $i['image_width'];
	$image_height = $i['image_height'];
	$aspect_width = $i['aspect_width'];
	$aspect_height = $i['aspect_height'];

// Translation: when the viewport is 761px or greater, the image will occupy 
// only 50vw (~50% of the viewport) otherwise, it will fill the entire 
// viewport (100vw).
//
// SEE: https://medium.com/@woutervanderzee/responsive-images-with-srcset-and-sizes-fc434845e948
	$sizes = '(min-width: 761px) 50vw, 100vw';

	$srcset = '';
	for ($x = 0; $x < count($srcset_sizes); $x++) {
		if ($srcset !== '') {
			$srcset .= ', ';
		}
		$srcset .= $image_src_array[$x] . ' ' . $srcset_sizes[$x] . 'w';
	}

// 2019-09-30: To default to 1600px image:
	$image_src = autoVersion(splice_size($image_url, '-1600px'));

	$link_tag = '<link rel=preload as=image href="' . $image_src . '" imagesrcset="' . $srcset . '" imagesizes="' . $sizes . '">' . PHP_EOL;

	return $link_tag;
}

function link_preload_srcset_tag($image_url, $alt, $srcset_sizes, $isolate_one_px = false) {

	$image_src_array = [];
	$srcset_sizes_count = count($srcset_sizes);

/*
// 2022-09-08:
// OLD...we're dropping $aspect_ratio_lazyload:
//
// NB: $GLOBALS['aspect_ratio_lazyload'] === $aspect_ratio_lazyload, 
// which is defined in includes/variables.php, along with $use_srcset
//
	if ($GLOBALS['aspect_ratio_lazyload'] === true) {
		for ($x = 0; $x < $srcset_sizes_count; $x++) {
			$url = splice_size($image_url, '-' . $srcset_sizes[$x] . 'px');
			array_push($image_src_array, autoVersion($url, $use_webp = true));
		}
	} else {
		for ($x = 0; $x < $srcset_sizes_count; $x++) {
			$url = splice_size($image_url, '-' . $srcset_sizes[$x] . 'px');
			array_push($image_src_array, autoVersion($url, $use_webp = true));
		}
	}
*/

	for ($x = 0; $x < $srcset_sizes_count; $x++) {
		$url = splice_size($image_url, '-' . $srcset_sizes[$x] . 'px');
		array_push($image_src_array, autoVersion($url, $use_webp = true));
	}

	$link_preload = '';
	for ($x = 0; $x < count($srcset_sizes); $x++) {
		$type = "image/jpeg";
		if (strpos($image_src_array[$x], '.webp') !== false) {
			$type = "image/webp";
		}
// NB: We're using MIN-RESOLUTION here to specify all greater sizes:

// This really should match what's in the srcset array, i.e., we should dispense with hard-coded values:
// [448, 536, 780, 928, 1072, 1600]

		if (strpos($image_src_array[$x], '1600px') !== false) {
			$media = '(min-resolution: 1600dpi)';
		}
		if (strpos($image_src_array[$x], '1072px') !== false) {
			$media = '(max-resolution: 1072dpi)';
		}
		if (strpos($image_src_array[$x], '928px') !== false) {
			$media = '(max-resolution: 928dpi)';
		}
		if (strpos($image_src_array[$x], '780px') !== false) {
			$media = '(max-resolution: 780dpi)';
		}
		if (strpos($image_src_array[$x], '536px') !== false) {
			$media = '(max-resolution: 536dpi)';
		}
		if (strpos($image_src_array[$x], '448px') !== false) {
			$media = '(max-resolution: 448dpi)';
		}
// 2020-01-28: What size is the best for the Lighthouse Audit?
// 1600px is the only size that will defintely be used (in the lightbox), so use $isolate_one_px to isolate it for a single preload link:
		if ($isolate_one_px !== false) {
			if (strpos($image_src_array[$x], $isolate_one_px) > 0) {
				$link_preload .= "\t\t" . '<link rel=preload href="' . $image_src_array[$x] . '" as=image type="' . $type . '" media="' . $media . '">' . PHP_EOL;
			}
		} else {
			$link_preload .= "\t\t" . '<link rel=preload href="' . $image_src_array[$x] . '" as=image type="' . $type . '" media="' . $media . '">' . PHP_EOL;
		}
	}
	return $link_preload;
}

// PREFETCH!
function link_prefetch_srcset_tag($image_url, $alt, $srcset_sizes, $isolate_one_px = false) {


	$image_src_array = [];
	$srcset_sizes_count = count($srcset_sizes);
/*
// 2022-09-08:
// OLD...we're dropping $aspect_ratio_lazyload:
//
// NB: $GLOBALS['aspect_ratio_lazyload'] === $aspect_ratio_lazyload, 
// which is defined in includes/variables.php, along with $use_srcset
//
	if ($GLOBALS['aspect_ratio_lazyload'] === true) {
		for ($x = 0; $x < $srcset_sizes_count; $x++) {
			$url = splice_size($image_url, '-' . $srcset_sizes[$x] . 'px');
			array_push($image_src_array, autoVersion($url, $use_webp = true));
		}
	} else {
		for ($x = 0; $x < $srcset_sizes_count; $x++) {
			$url = splice_size($image_url, '-' . $srcset_sizes[$x] . 'px');
			array_push($image_src_array, autoVersion($url, $use_webp = true));
		}
	}
*/
	for ($x = 0; $x < $srcset_sizes_count; $x++) {
		$url = splice_size($image_url, '-' . $srcset_sizes[$x] . 'px');
		array_push($image_src_array, autoVersion($url, $use_webp = true));
	}

	$link_prefetch = '';
	for ($x = 0; $x < count($srcset_sizes); $x++) {
		$type = "image/jpeg";
		if (strpos($image_src_array[$x], '.webp') !== false) {
			$type = "image/webp";
		}

// This really should match what's in the srcset array, i.e., we should dispense with hard-coded values:
// [448, 536, 780, 928, 1072, 1600]
// 2020-08-17: Unsure how to edit this section:

		if (strpos($image_src_array[$x], '1600px') !== false) {
// NB: We're using MIN-RESOLUTION here to specify all greater sizes:
			$media = '(min-resolution: 1025dpi)';
		}
		if (strpos($image_src_array[$x], '1072px') !== false) {
			$media = '(max-resolution: 1024dpi)';
		}
		if (strpos($image_src_array[$x], '780px') !== false) {
			$media = '(max-resolution: 768dpi)';
		}
// 768px is really the only size that will be used, so use $isolate_one_px to isolate it for a single prefetch link:
// NB: According to the HTML validator, "as=image" should ONLY be used on "rel=preload", not "rel=prefetch":
		if ($isolate_one_px !== false) {
			if (strpos($image_src_array[$x], $isolate_one_px) > 0) {
				$link_prefetch .= "\t\t" . '<link rel=prefetch href="' . $image_src_array[$x] . '" type="' . $type . '" media="' . $media . '">' . PHP_EOL;
			}
		} else {
			$link_prefetch .= "\t\t" . '<link rel=prefetch href="' . $image_src_array[$x] . '" type="' . $type . '" media="' . $media . '">' . PHP_EOL;
		}
	}
	return $link_prefetch;
}


function seoUrl($string) {
	$string = trim($string);
//Lower case everything
	$string = strtolower($string);
//Make alphanumeric (removes all other characters)
	$string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
//Clean up multiple dashes or whitespaces
	$string = preg_replace("/[\s-]+/", " ", $string);
//Convert whitespaces and underscore to dash
	$string = preg_replace("/[\s_]/", "-", $string);
	return $string;
}

