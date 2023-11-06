<?php
$html = ob_get_contents();
ob_end_clean();
$html = preg_replace("/[\r\n]+/", "\n\t", $html);

// minify is set in variables.php, which is called by common_routines.php,
// which is called at the top of every main.php file:
if (isset($_SESSION['minify']) && ($_SESSION['minify'] == true)) {
	$html = tovic_minify_html($html);
}

echo $html;
