<?php

$absolute_root = $_SERVER['ABSOLUTE_ROOT'];

include_once 'index-main-vars.php';
include_once $absolute_root . 'includes/common/routines.php';

ob_start();

$main_classes = $page . " shell main custom-style-elements";

$fontTypes = array('woff2', 'woff', 'ttf', 'svg', 'eot');

?>

<main class="<?php echo $main_classes; ?>" data-page="<?php echo $page; ?>" data-title="<?php echo $title; ?>">
<?php

render_custom_style_elements();

?>

	<h1 id=main-content tabindex=0>Download Google Fonts</h1>
	<form class=google-fonts-form>
		<p>Input a Google Fonts URL for downloadable <?php echo implode(', ', $fontTypes); ?> fonts, as well as the corresponding @fontface style rules.</p>
		<p><span class=italic>Try:</span> https://fonts.googleapis.com/icon?family=Material+Icons</p>
		<p><span class=italic>Try:</span> https://fonts.googleapis.com/css?family=Cabin:400,700,400italic,700italic</p>
		<div class=input>
			<label for=google-font-url-input>
				<strong>Google Fonts URL *</strong>
			</label>
			<input id=google-font-url-input type=text name=url title="Type URL of Google Font, e.g., https://fonts.googleapis.com/icon?family=Material+Icons" value="<?php if (isset($_POST['url'])) { echo $_POST['url']; } ?>"  autocomplete=off required>
<!--			<input type="submit" value="Get fonts!"> -->
<?php /* For honeypot method */ ?> 
			<label for=input-bot class=bot aria-hidden=true>
				<strong>How You Found Us *</strong>
			</label>
			<input id=input-bot class=bot type=text name=bot title="Please fill out this field" autocomplete=off tabindex=-1>

			<p class=padding-top-1em>* Required field</p>

		</div>
		<div class="info-text padding-top-1em">
			<button type=submit>Submit</button>
			<button type=button class=clear-output>Clear Output</button>
			<p class="sending display-none"><em>Sending request...</em></p>
			<p class="sent display-none"><em>Your request is sent.</em></p>
		</div>
	</form>
	<section class=output></section>

<?php
include $absolute_root . 'includes/components/breadcrumb_schema.php';
?>
</main>
<?php
include $absolute_root . 'includes/components/etc/main.ending.inc.php';
