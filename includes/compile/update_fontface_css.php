<?php

echo 'Updating font-face.css . . .' . PHP_EOL;

$absolute_root = $_SERVER['ABSOLUTE_ROOT'];

// NB: Perform this BEFORE running compile_css.php

/*
When compile directory is off root of site, $_SERVER['REQUEST_URI'] will simply be:

/compile/

When not, it will be:

/mydomain484459.com/developer/compile/
/ron-sanford/compile/
etc.

So, remove /compile/ from string. If the result is NOT empty (''), then append a trailing slash to it.
*/

/*
$request_uri = $_SERVER['REQUEST_URI'];
$root_folder = str_replace('/compile/', '', $request_uri);
if (strlen($root_folder) !== '') {
	$root_folder .= '/';
}
*/

$root_folder = $_SERVER['BASE_PATH'];

$absolute_root = $_SERVER['ABSOLUTE_ROOT'];

//$loader_template = file_get_contents($absolute_root . 'assets/javascript/loader-template.js');
$font_face_template_css = $absolute_root . 'assets/css/font-face.TEMPLATE.css';

if (file_exists($font_face_template_css)) {
	$fontface_css_content = file_get_contents($font_face_template_css);

//$fontface_css_content = file_get_contents($absolute_root . 'assets/css/font-face.TEMPLATE.css');

	$fontface_css_content = str_replace('/###ROOT-FOLDER###/', $root_folder, $fontface_css_content);
	file_put_contents($absolute_root . 'assets/css/font-face.css', $fontface_css_content);
} else {
	echo $font_face_template_css . ' does not exist...procedure aborted.' . PHP_EOL . PHP_EOL;
}




