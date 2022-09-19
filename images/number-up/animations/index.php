<?php

echo '<pre>';
echo 'Writing new numberUpData.mjs' . PHP_EOL;

$absolute_root = $_SERVER['ABSOLUTE_ROOT'];

$allowed  = ['jpg', 'jpeg', 'png', 'gif', 'webm', 'ogv', 'mp4'];

// Used when run as index.php in current folder:
//$iterator = new DirectoryIterator(dirname(__FILE__));
//$file_count = count(glob("./*.{jpg,jpeg,png,gif,webm,ogv,mp4}", GLOB_BRACE));

// Used when included in another file:
$iterator = new DirectoryIterator($absolute_root . 'images/number-up/animations');
$file_count = count(glob($absolute_root . "images/number-up/animations/*.{jpg,jpeg,png,gif,webm,ogv,mp4}", GLOB_BRACE));

$count = 0;

$data = 'var numberUpData;' . PHP_EOL;
$data .= PHP_EOL;
$data .= 'numberUpData = {' . PHP_EOL;
$data .= '	videoHash: {' . PHP_EOL; 

foreach ($iterator as $fileinfo) {
	if (!$fileinfo->isDot()) {
		$file = $fileinfo->getFilename();
		$pathinfo = pathinfo($file);  /* split path into its components */
		$extension = strtolower($pathinfo['extension']);  /* extract the extension and normalize to lowercase */
		if (in_array($extension, $allowed)) {
			$count++;
			if ($count != $file_count) {
				$data .= "	'" . $file . "': '" . md5_file($absolute_root . "images/number-up/animations/" . $file) . "'," . PHP_EOL;
			} else {
				$data .= "	'" . $file . "': '" . md5_file($absolute_root . "images/number-up/animations/" . $file) . "'" . PHP_EOL;
			}
		}
	}
}
$data .= '	}' . PHP_EOL;
$data .= '};' . PHP_EOL;
$data .= PHP_EOL;
$data .= 'export default Object.freeze({' . PHP_EOL;
$data .= '	numberUpData' . PHP_EOL;
$data .= '});' . PHP_EOL;

// Print to screen:
//echo $file_count . PHP_EOL;
//echo $absolute_root . "images/number-up/animations/*.{jpg,jpeg,png,gif,webm,ogv,mp4}" . PHP_EOL;
//echo $data . PHP_EOL;

$output_filename = $absolute_root . 'javascript/modules/numberUpData.mjs';

file_put_contents($output_filename, $data);

echo 'DONE.';

?>