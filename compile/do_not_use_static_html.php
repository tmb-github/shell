<?php
echo PHP_EOL;
echo 'Setting $use_static_html = false;' . PHP_EOL;

if ($_SERVER['HTTP_HOST'] == 'localhost') {
	$localhost = true;
} else {
	$localhost = false;
}

$absolute_root = $_SERVER['ABSOLUTE_ROOT'];

$php_text = '<?php' . PHP_EOL . '$use_static_html = false;';

file_put_contents($absolute_root . 'includes/common/use_static_html.inc.php', $php_text);
