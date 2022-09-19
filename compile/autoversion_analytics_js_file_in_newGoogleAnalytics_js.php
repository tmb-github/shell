<?php 

echo 'Autoversioning analytics js file in newGoogleAnalytics.js . . .' . PHP_EOL;

$absolute_root = $_SERVER['ABSOLUTE_ROOT'];

function javascript_integrity_sha384_analytics($complete_file_path) {
	$hash = hash_file('sha384', $complete_file_path, true);
	$hash_base64 = base64_encode($hash);
	return "sha384-$hash_base64";
}

// To replace the last occurrence of the string:
function str_replace_last($search, $replace, $str) {
	if(($pos = strrpos($str, $search)) !== false) {
		$search_length = strlen($search);
		$str = substr_replace($str, $replace, $pos, $search_length);
	}
	return $str;
}

// To add to each enqueue argumnent list, e.g.: 
// integrity: "sha384-t4rw+57mjxt9FqnlmJJ4gdz8gJWg2X11Oyn2DBGbdoc="
// etc.
$include_subresource_integrity = true;

$new_google_analytics_template_js = $absolute_root . 'javascript/newGoogleAnalytics.TEMPLATE.js';
$new_google_analytics_js = $absolute_root . 'javascript/newGoogleAnalytics.js';

$javascript_analytics_js = 'javascript/analytics.js';

if (file_exists($new_google_analytics_template_js)) {

	$template = file_get_contents($new_google_analytics_template_js);

	if (file_exists($absolute_root . $javascript_analytics_js)) {
		$autoversioned = autoVersion($javascript_analytics_js);
		$template = str_replace($javascript_analytics_js, $autoversioned, $template);

// optionally append integrity attribute:
		if ($include_subresource_integrity == true) {
			$integrity = javascript_integrity_sha384_analytics($absolute_root . $javascript_analytics_js);
			$search = "a.crossOrigin='anonymous';";
			$replace = $search . "a.integrity='" . $integrity . "';";
			$template = str_replace_last($search, $replace, $template);
		}

		file_put_contents($new_google_analytics_js, $template);
	}

} else {
	echo $new_google_analytics_template_js . ' could not be found...procedure aborted.' . PHP_EOL . PHP_EOL;
}