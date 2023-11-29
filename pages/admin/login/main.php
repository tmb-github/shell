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

	<h1 id=main-content>Login</h1>
	<form class="login-form outer-container-to-center" method=post enctype=multipart/form-data>
		<div class="inner-container-to-center input-grid">
			<label for=username-input>Username:</label>
			<input id=username-input type=text name=username autocomplete=off required autofocus>

			<label for=password-input>Password:</label>
			<input id=password-input type=password name=password autocomplete=off required>
		</div>
		<div class="submit-button info-text padding-top-1em">
			<button type=submit>Submit</button>
		</div>
		<p class="username-password-unrecognized text-align-center padding-1em"><strong>Username & Password combination not recognized.</strong></p>
	</form>

<?php
include $absolute_root . 'includes/components/breadcrumb_schema.php';
?>
</main>
<?php
include $absolute_root . 'includes/components/etc/main.ending.inc.php';
?>