<?php

$absolute_root = $_SERVER['ABSOLUTE_ROOT'];

include_once 'index-main-vars.php';
include_once $absolute_root . 'includes/common_routines.php';

ob_start();

$oops = false;

$main_classes = $page . " main custom-style-elements";

?>

<main class="<?php echo $main_classes; ?>" data-page="<?php echo $page; ?>" data-title="<?php echo $title; ?>">
<?php
render_custom_style_elements('server-error-test');
?>

	<h1 id=main-content>Server Error Test</h1>
	<form class="server-error-test-form outer-container-to-center" method=post enctype=multipart/form-data>
		<div class="inner-container-to-center input-grid">
			<label for=random-word-input>Random Word:</label>
			<input id=random-word-input type=text name=random_word autocomplete=off required autofocus>
		</div>
		<div class="submit-button info-text padding-top-1em">
			<button type=submit>Submit</button>
		</div>
		<p class="random-word-unrecognized text-align-center padding-1em"><strong>Random word not recognized.</strong></p>
		<p class="random-word-recognized text-align-center padding-1em"><strong>Random word recognized.</strong></p>
	</form>

<?php
include $absolute_root . 'includes/html/common/main-breadcrumb-schema.inc.php';
?>
</main>
<?php
include $absolute_root . 'includes/html/common/main-ending.inc.php';
?>