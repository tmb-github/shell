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


include $absolute_root . 'includes/forms/admin/shared_routines/page_name_variations.inc.php';

?>
	<h1 id=main-content tabindex=0>Page Maker</h1>
	<form id=upload-form class=upload-form method=post>
		<div class=upload-status></div>
		<x-label for=submit-input class=no-colon><input id=submit-input type=submit value="Submit" name=submit></x-label>
		<label class=admin for=admin-input>Admin: <input id=admin-input type=checkbox name=admin title="Admin" value=admin></label>
		<label for=title-input>Page Title: <input id=title-input type=text name=title title="Example: Merchandise" data-existing-titles="<?php echo implode(' | ', $title_case_array); ?>" autocomplete=off data-required=true required></label>
		<label for=slug-input>Page Slug: <input id=slug-input type=text name=slug autocomplete=off readonly data-existing-slugs="<?php echo implode(' | ', $kabob_case_array); ?>"></label>
<?php

echo '	<h2>Current Pages</h2>' . PHP_EOL;
echo '	<ul class=current-pages>' . PHP_EOL;

for ($x = 0; $x < count($title_case_array); $x++) {
	echo '		<li>' . $title_case_array[$x] . '</li>' . PHP_EOL;
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
