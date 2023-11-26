<?php

echo 'Compiling master.min.js list . . .' . PHP_EOL;

$absolute_root = $_SERVER['ABSOLUTE_ROOT'];
$assets_folder = $_SERVER['ASSETS_FOLDER'];

// Use the contents of the loader to determine what to put in the master JS file:
$loader_template_js = $absolute_root . $assets_folder . 'javascript/scripts/loader.js';

if (file_exists($loader_template_js)) {

	$loader_js_content = file_get_contents($loader_template_js);

// strip out /* comments */ in service worker script
// This is necessary so that we don't accidentally capture a JS file in a comment
	$loader_js_content = preg_replace('!/\*.*?\*/!s', '', $loader_js_content);
	$loader_js_content = preg_replace('/\n\s*\n/', "\n", $loader_js_content);

	preg_match_all("/(enqueue\({src: ')(.+)('}\);)/i", $loader_js_content, $matches);

	$js = '';
	$master = '';
	for ($i = 0; $i < count($matches[0]); $i++) {
		$resource = $matches[2][$i];
		if (substr($resource, 0, 10) === 'javascript') {

			$resource = str_replace($assets_folder . 'javascript/scripts', $assets_folder . 'javascript/minified-scripts', $resource);

			$resource = str_replace('.js', '.min.js', $resource);
			$resource = str_replace('.min.min', '.min', $resource);
/*
			if ($resource === $assets_folder . 'javascript/scripts/siteWideEditsRedux.js') {
				$resource = $assets_folder . 'javascript/minified-scripts/siteWideEditsRedux.min.js';
			}
*/
			if (file_exists($absolute_root . $resource)) {
				$js = file_get_contents($absolute_root . $resource);
				$master .= $js . PHP_EOL;
			} else {
				echo PHP_EOL . $absolute_root . $resource . ' could not be found . . . procedure compromised.' . PHP_EOL . PHP_EOL;
			}
		}
	}

	file_put_contents($absolute_root . $assets_folder . 'javascript/minified-scripts/master.min.js', $master);

} else {
	echo $loader_template_js . ' could not be found . . . procedure aborted.' . PHP_EOL . PHP_EOL;
}