<?php

$absolute_root = $_SERVER['ABSOLUTE_ROOT'];

include_once 'index-main-vars.php';
include_once $absolute_root . 'includes/common/routines.php';

ob_start();

$main_classes = $page . " shell main custom-style-elements";

?>

<main class="<?php echo $main_classes; ?>" data-page="<?php echo $page; ?>" data-title="<?php echo $title; ?>">
<?php

render_custom_style_elements();

$subfolders = get_all_subfolders($absolute_root . 'pages/');
//echo print_r($subfolders, true);

$slug_array = array();
foreach ($subfolders as $path) {
	if (strpos($path, "/pages/") !== false) {
		$parts = explode('/', rtrim($path, '/'));
		$slug_array[] = end($parts);
	}
}
sort($slug_array);

// Output the new array
//print_r($slug_array);

$camelcase_array = array();
foreach ($subfolders as $path) {
	if (strpos($path, "/pages/") !== false) {
		$parts = explode('/', rtrim($path, '/'));
		$lastPart = end($parts);
// Omit hyphens and uppercase the letter that follows the hyphen
		$formattedString = preg_replace_callback('/-(.)/', function($matches) {
			return strtoupper($matches[1]);
		}, $lastPart);
		$camelcase_array[] = $formattedString;
	}
}
sort($camelcase_array);

$page_name_array = array();
foreach ($subfolders as $path) {
	if (strpos($path, "/pages/") !== false) {
		$parts = explode('/', rtrim($path, '/'));
		$lastPart = end($parts);
// Replace hyphens with spaces and capitalize all resulting words
		$formattedString = ucwords(str_replace('-', ' ', $lastPart));
		$page_name_array[] = $formattedString;
	}
}
sort($page_name_array);


?>
	<h1 id=main-content tabindex=0>Page Maker</h1>
	<form id=page-upload-form class=page-upload-form method=post enctype=multipart/form-data>
		<div class=upload-status></div>
		<x-label for=submit-button class=no-colon><input id=submit-button type=submit value="Submit" name=submit-image></x-label>
		<label for=page-name>Page Name: <input id=page-name class=page-name type=text name=page_name title="Example: Discography" data-existing-page-names="<?php echo implode(' | ', $page_name_array); ?>" autocomplete=off data-required=true required></label>
		<label for=page-slug>Page Slug: <input id=page-slug class=page-slug type=text name=page_slug autocomplete=off readonly data-existing-page-slugs="<?php echo implode(' | ', $slug_array); ?>"></label>
<?php

echo '	<h2>Current Pages</h2>' . PHP_EOL;
echo '	<ul class=current-pages>' . PHP_EOL;
foreach ($page_name_array as $page_name) {
	echo '		<li>' . $page_name . '</li>' . PHP_EOL;
}
echo '	</ul>' . PHP_EOL;

?>
	</form>
<?php
include $absolute_root . 'includes/components/breadcrumb_schema.php';
?>
</main>
<?php
include $absolute_root . 'includes/components/etc/main.ending.inc.php';
