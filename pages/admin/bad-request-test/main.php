<?php

$absolute_root = $_SERVER['ABSOLUTE_ROOT'];

include_once 'index-main-vars.php';
include_once $absolute_root . 'includes/common/routines.php';

ob_start();

$oops = false;

$main_classes = $page . " main custom-style-elements";

?>

<main class="<?php echo $main_classes; ?>" data-page="<?php echo $page; ?>" data-title="<?php echo $title; ?>">
<?php

render_custom_style_elements();

?>

	<h1 id=main-content>Bad Request Test</h1>
	<form class="bad-request-test-form outer-container-to-center" method=post enctype=multipart/form-data>
		<div class="submit-button info-text padding-top-1em">
			<button type=submit>Submit</button>
		</div>
	</form>

<?php
include $absolute_root . 'includes/components/breadcrumb_schema.php';
?>
</main>
<?php
include $absolute_root . 'includes/components/etc/main.ending.inc.php';
