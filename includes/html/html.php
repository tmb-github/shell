<?php
ob_start();
?>
<!DOCTYPE html>
<?php
$minify = $_SESSION['minify'] ? ' data-minify=true' : ' data-minify=false';
?>
<html lang=en-US<?php echo $minify; ?>>
<?php
include $html_inc_path . 'head.php';
include $html_inc_path . 'body.php';
?>
</html>
<?php

$html = ob_get_contents();

if ($_SESSION['minify'] == true) {
// 2022-03-04:
// Modified all minify functions to save the original, unminified versions of the
// input and return them if the minifier would return nothing:
	$html = tovic_minify_html($html);
}

ob_end_clean();
echo $html;

?>