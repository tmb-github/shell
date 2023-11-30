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
	<h1 id=main-content tabindex=0>Admin Listing</h1>
	<ul class=admin-ul>
<?php

function get_kebab_case($inputArray) {
	$resultArray = array();
	foreach ($inputArray as $path) {
		if (strpos($path, "/pages/admin/") !== false) {
			$parts = explode('/', rtrim($path, '/'));
			$lastPart = end($parts);
// Convert to kebab-case
			$formattedString = strtolower(str_replace(' ', '-', $lastPart));
			$resultArray[] = $formattedString;
		}
	}
	return $resultArray;
}

function get_capitalized_words($inputArray) {
	$resultArray = array();
	foreach ($inputArray as $path) {
		if (strpos($path, "/pages/admin/") !== false) {
			$parts = explode('/', rtrim($path, '/'));
			$lastPart = end($parts);
// Capitalize words
			$formattedString = ucwords(str_replace('-', ' ', $lastPart));
			$resultArray[] = $formattedString;
		}
	}
	return $resultArray;
}

$subfolders = get_all_subfolders($absolute_root . 'pages/');

$kebab_case = get_kebab_case($subfolders);
sort($kebab_case);

$capitalized_words = get_capitalized_words($subfolders);
sort($capitalized_words);

for ($x = 0; $x < count($capitalized_words); $x++) {
	echo '		<li><a href="admin/' . $kebab_case[$x] . '/">' . $capitalized_words[$x] . '</a></li>' . PHP_EOL;
}

?>
	</ul>
<?php
include $absolute_root . 'includes/components/breadcrumb_schema.php';
?>
</main>
<?php
include $absolute_root . 'includes/components/etc/main.ending.inc.php';
