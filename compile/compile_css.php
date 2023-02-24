<?php

echo 'Updating compiled.css . . .' . PHP_EOL;

/* Write to compiled.css the contents of all CSS files found in master-list.css */

// NB: Perform this AFTER running update_fontface_css.php

$absolute_root = $_SERVER['ABSOLUTE_ROOT'];
$master_list_template_css = $absolute_root . 'css/master-list.TEMPLATE.css';

$compiled = $absolute_root . 'css/compiled.css';
if (file_exists($compiled)) {
	unlink($compiled);
}

if (file_exists($master_list_template_css)) {
	$file = fopen($master_list_template_css, "r");
	$contents = '';
	while (!feof($file)) {
		$line = trim(fgets($file));
		if ($line !== '') {
			preg_match('~"(.*?)"~', $line, $css_filename);
			$contents .= "/******************" . PHP_EOL;
			$contents .= PHP_EOL;
			$contents .= "BEGIN: " . $css_filename[1] . PHP_EOL;
			$contents .= PHP_EOL;
			$contents .= "******************/" . PHP_EOL;
			$contents .= PHP_EOL;
			$contents .= file_get_contents($absolute_root . 'css/' . $css_filename[1]) . PHP_EOL;
		}
	}
	fclose($file);
	file_put_contents($compiled, $contents);
} else {
	echo $master_list_template_css . ' could not be found...procedure aborted.' . PHP_EOL . PHP_EOL;
}
