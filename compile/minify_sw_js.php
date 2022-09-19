<?php 

echo 'Minifying sw.js . . .' . PHP_EOL;

$absolute_root = $_SERVER['ABSOLUTE_ROOT'];
$source = $absolute_root . 'sw.js';
$destination = $absolute_root . 'sw.min.js';

if (file_exists($source)) {
	minify_with_closure_compiler($source, $destination);
	if ((filesize($source) == filesize($destination)) && (md5_file($source) == md5_file($destination))) {
		echo PHP_EOL;
		echo 'ERROR: Closure Compiler minification failed: ' . $source . PHP_EOL;
		echo PHP_EOL;
	} else {
		echo 'SUCCESS: Closure Compiler minified: ' . $source . PHP_EOL;
	}
} else {
	echo $source . ' does not exist...procedure aborted.' . PHP_EOL . PHP_EOL;
}