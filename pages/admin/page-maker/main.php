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

?>
	<h1 id=main-content tabindex=0>Page Maker</h1>
<?php

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

echo '	<h2>Current Pages</h2>' . PHP_EOL;
echo '	<ul class=current-pages>' . PHP_EOL;
foreach ($page_name_array as $page_name) {
	echo '		<li>' . $page_name . '</li>' . PHP_EOL;
}
echo '	</ul>' . PHP_EOL;

?>

<?php
include $absolute_root . 'includes/components/breadcrumb_schema.php';
?>
</main>
<?php
include $absolute_root . 'includes/components/etc/main.ending.inc.php';
