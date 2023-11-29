<?php
ob_start();
echo PHP_EOL;

$dateModified_file = $absolute_root . 'includes/forms/compile/dateModified.txt';

if (file_exists($dateModified_file)) {
	$dateModified = file_get_contents($dateModified_file);
} else {
	$dateModified = '1999-01-01T00:00:00+00:00';
}
?>

<script type=application/ld+json id=schema-web-page>
	{
		"@context": "https://schema.org",
		"@type": "WebPage",
		"description": <?php echo '"' . $schema_webpage_description . '"'; ?>,
		"name": "<?php echo $schema_webpage_name; ?>",
		"speakable":
		{
			"@type": "SpeakableSpecification",
			"xPath": [
				"/html/head/title",
				"/html/head/meta[@name='description']/@content"
			]
		},
		"url": "<?php echo $schema_webpage_url; ?>",
		"dateModified": <?php echo '"' . $dateModified . '"' . PHP_EOL; ?>
	}
</script>

<script type=application/ld+json id=schema-person>
	{
		"@context": "https://schema.org",
		"@type": "Person",
		"name": "<?php echo $schema_person_name; ?>",
		"image": "<?php echo $schema_person_image; ?>",
		"url": "<?php echo $schema_person_url; ?>",
		"sameAs": [
<?php
$i = 0;
foreach($social_media_href as $media => $href)  {
	$i++;
	if ($i != count($social_media_href)) {
		echo '			"' . $href . '",' . PHP_EOL;
	} else {
		echo '			"' . $href . '"' . PHP_EOL;
	}
}
?>
		]
	}
</script>

<?php

// RE: 'defer' attribute from MDN:
// This Boolean attribute is set to indicate to a browser that the script is
// meant to be executed after the document has been parsed, but before firing
// DOMContentLoaded.
// Scripts with the defer attribute will prevent the DOMContentLoaded event
// from firing until the script has loaded and finished evaluating.
//
// 2020-01-31: I tried moving the script with the 'defer' attribute to the HEAD
// to see if there was any performance improvement, but there wasn't, and it
// actually made performance lag, according to the Lighthouse audit. So, it's
// better to put the SCRIPT at the bottom of the BODY element.
//
// BUT!
//
// Still leave the 'defer' attribute on the SCRIPT even when it's moved to the
// bottom of the BODY. The performance is better.
//

// 2020-04-01: Apparently, we're fastest now (on localhost, at least) when we
// load the error listener separately and THEN the loader script:
if ($_SESSION['minify'] == true) {
	$error_listener_script = 'javascript/minified-scripts/errorListener.min.js';
	$defer_script = 'javascript/minified-scripts/loader.min.js';
	$initialize_script = 'javascript/minified-scripts/initialize.min.js';
} else {
	$error_listener_script = 'javascript/scripts/errorListener.js';
	$defer_script = 'javascript/scripts/loader.js';
	$initialize_script = 'javascript/scripts/initialize.js';
}

// FOR TESTING, un-rem and change variables here:
//$error_listener_script = 'javascript/minified-scripts/errorListener.min.js';
//$defer_script = 'javascript/minified-scripts/loader.min.js';

// NB: To force minify in localhost, unrem the minify session variable in variables.php.

// We don't need the site script for the error page at all.
if (strpos($canonical, '/error/') === false) {
// 2020-03:
//
// We load the error listener first and by itself. It cannot be added with the
// loader. The error listener catches parsing errors in the JavaScript added by
// the loader and subsequent routines. Those errors will indicate that an 
// older, incompatible browser is in use. The site will then redirect to the 
// incompatible browser page if a parse error occurs thereafter during the
// loading of any subsequent part of the routines that follow.
//
// The loader loads everything else.

// initialize script contains errorListener.js code + routine to append loader.js
	$use_initialize_script = false;

	if ($use_initialize_script == true) {
?>
<script src=<?php echo autoversion($initialize_script); ?> <?php echo $inline_nonce_property; ?> defer integrity="<?php echo javascript_integrity_sha384($initialize_script); ?>"></script>
<?php
	} else {
?>
<script src=<?php echo autoversion($error_listener_script); ?> <?php echo $inline_nonce_property; ?> defer integrity="<?php echo javascript_integrity_sha384($error_listener_script); ?>"></script>
<script src=<?php echo autoversion($defer_script); ?> <?php echo $inline_nonce_property; ?> defer integrity="<?php echo javascript_integrity_sha384($defer_script); ?>"></script>
<?php
	}

}

$html = ob_get_contents();
if ($_SESSION['minify'] == true) {
	$html = tovic_minify_js($html);
// 2020-02-18: the tovic minifier deletes the space before 'defer';
// this restores it. (It actually seems like it's not putting a space
// after the nonce attribute, but so long as everything is in the order
// provided above, this next line of code will fix it).
	$html = str_ireplace('defer', ' defer', $html);
}
ob_end_clean();
$html = preg_replace("/[\r\n]+/", "\n\t", $html);

// NO! It is not valid HTML to add an 'integrity' attribute if there is not a 'src' attribute on the script element as well!
// So, do NOT do this:
/*
////////////////////////////////////////////////
// ADD SRI INTEGRITY TO SCRIPT ELEMENT ITSELF //
////////////////////////////////////////////////

$script_open_tag = '<script ' . $inline_nonce_property . '>';
$script_close_tag = '</script>';

$x = strpos($html, $script_open_tag);
$script_begin = $x + strlen($script_open_tag);

$y = strpos($html, $script_close_tag, $x);
$script_end = ($y - $script_begin);

$script = substr($html, $script_begin, $script_end);

$hash = hash('sha256', $script, true);
$hash_base64 = base64_encode($hash);
$sha256 = "sha256-$hash_base64";

$html = str_ireplace($script_open_tag, '<script ' . $inline_nonce_property . ' integrity="' . $sha256 . '">', $html);
*/

echo $html;
