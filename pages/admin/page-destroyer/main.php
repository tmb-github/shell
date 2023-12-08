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

////////////////////////////////////////
// Retrieve existing page information //
////////////////////////////////////////

include $absolute_root . 'includes/forms/admin/page-destroyer/page_destroyer_functions.inc.php';

?>
	<h1 id=main-content tabindex=0>Page Destroyer</h1>
	<form id=page-destroyer-form class=page-destroyer-form method=post>
		<div class="submit-button info-text">
			<button type="submit">Submit</button>
		</div>
		<div class="destruction-status padding-bottom-1em"></div>
<?php
echo '		<fieldset class=destroy-options>' . PHP_EOL;
echo '			<legend><strong>Be Certain</strong></legend>' . PHP_EOL;
echo '			<ul class=current-pages>' . PHP_EOL;

for ($x = 0; $x < count($title_case_array); $x++) {

	$value = $camel_case_array[$x] . '|' . $snake_case_array[$x] . '|' . $kabob_case_array[$x] . '|' . $title_case_array[$x];
	$hashed_value = hash('sha256', $value);

	echo '				<li>' . PHP_EOL;
	echo '					<input id="' . $kabob_case_array[$x] . '" type=checkbox name="page_info[]" title="' . $title_case_array[$x] . '" value="' . $hashed_value . '">'. PHP_EOL;
	echo '					<label for="' . $kabob_case_array[$x] . '">' . $title_case_array[$x] . '</label>' . PHP_EOL;
	echo '				</li>' . PHP_EOL;
}

echo '			</ul>' . PHP_EOL;
echo '		</fieldset>' . PHP_EOL;
?>
	</form>
<?php
include $absolute_root . 'includes/components/breadcrumb_schema.php';
?>
</main>
<?php
include $absolute_root . 'includes/components/etc/main.ending.inc.php';
