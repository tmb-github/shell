<?php

echo 'Updating compiled.css . . .' . PHP_EOL;

/* Write to compiled.css the contents of all CSS files found in individual-imports.TEMPLATE.css */

// NB: Perform this AFTER running update_fontface_css.php

$absolute_root = $_SERVER['ABSOLUTE_ROOT'];
$individual_imports_template_css = $absolute_root . 'assets/css/individual-imports.TEMPLATE.css';

$compiled = $absolute_root . 'assets/css/compiled.css';
if (file_exists($compiled)) {
	unlink($compiled);
}

if (file_exists($individual_imports_template_css)) {
	$file = fopen($individual_imports_template_css, "r");
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
			$contents .= file_get_contents($absolute_root . 'assets/css/' . $css_filename[1]) . PHP_EOL;
		}
	}
	fclose($file);
	file_put_contents($compiled, $contents);
} else {
	echo $individual_imports_template_css . ' could not be found...procedure aborted.' . PHP_EOL . PHP_EOL;
}
