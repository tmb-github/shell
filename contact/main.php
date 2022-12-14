<?php

$absolute_root = $_SERVER['ABSOLUTE_ROOT'];

include_once 'index-main-vars.php';
include_once $absolute_root . 'includes/common_routines.php';

ob_start();

$main_classes = $page . " main custom-style-elements";

?>

<main class="<?php echo $main_classes; ?>" data-page="<?php echo $page; ?>" data-title="<?php echo $title; ?>">
<?php

render_custom_style_elements($page);

?>
	<h1 id=main-content tabindex=0>Send Smoke Signals!</h1>
	<h2 class="font-size-125rem">We’d love to hear from you. Please write to us using the form below.</h2>
	<form class=contact-form>
		<p class=padding-top-1em><em>Please complete the fields below, then press Send at the bottom.</em></p>
		<div class=input>

			<label for=input-sender>
				<strong>Your name *</strong>
			</label>
			<input id=input-sender type=text name=sender title="Please fill out this field" autocomplete=on required><br>

			<label for=input-email>
				<strong>Your email *</strong>
			</label>
			<input id=input-email type=email name=email title="Please fill out this field" autocomplete=on required><br>

<?php /* For honeypot method */ ?> 
			<label for=input-bot class=bot aria-hidden=true>
				<strong>How You Found Us *</strong>
			</label>
			<input id=input-bot class=bot type=text name=bot title="Please fill out this field" autocomplete=off tabindex=-1>

			<label for=input-subject>
				<strong>Subject *</strong>
			</label>
			<input id=input-subject type=text name=subject title="Please fill out this field" autocomplete=off required><br>

			<label for=input-message>
				<strong>Message *</strong>
			</label>
			<textarea id=input-message name=message title="Please fill out this field" autocomplete=off required></textarea>

			<p class=padding-top-1em>* Required fields.</p>
		</div>
<?php /*
<!-- For reCAPTCHA method, use this DIV: -->
		<div class="recaptcha padding-top-1em">
			<label for=g-recaptcha-response class=recaptcha-label>reCAPTCHA</label>
<!-- This textarea is needed so that the 'g-recaptcha-response' label before it has a referent. The *true* referent textarea is created by the reCAPTCHA routine's JavaScript once it loads, but without this textarea, the HTML is invalid. Because the reference is made by ID, this creates a conflict: we can't have two elements with the same ID. So the JavaScript for this site deletes 'textarea.screen-reader#g-recaptcha-response' upon loading -->
			<textarea class=screen-reader id=g-recaptcha-response></textarea>

			<div id=recaptcha class="g-recaptcha" data-sitekey="6LdqHFEUAAAAAHgdqbQOKU-sDC8UksKpKjWe2KWa" data-size="invisible"></div><br>

			<div class=info-text>
				<input type=submit value=Send>
				<p class="sending display-none"><em>Sending message...</em></p>
				<p class="sent display-none"><em>Your message is sent.</em></p>
			</div>

		</div>
*/ ?>
<?php /*
<!-- For honeypot method, use this DIV: -->
*/ ?>
		<div class="info-text padding-top-1em">
			<button type=submit id=junk>Send</button>
			<p class="sending display-none"><em>Sending message...</em></p>
			<p class="sent display-none"><em>Your message is sent.</em></p>
		</div>
	</form>
<?php
include $absolute_root . 'includes/html/common/main-breadcrumb-schema.inc.php';
?>
</main>
<?php
include $absolute_root . 'includes/html/common/main-ending.inc.php';
?>
