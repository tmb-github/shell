<?php

// NB: Perform this AFTER running update_fontface_css.php

include './compile_reqs.inc.php';

if ($compile_css == true) {

// Write to compiled.css the contents of all CSS files found in individual-imports.TEMPLATE.css

	$html = '';
	$individual_imports_template_css = $absolute_root . $assets_folder . 'css/individual-imports.TEMPLATE.css';
	$compiled = $absolute_root . $assets_folder . 'css/compiled.css';
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
				$contents .= file_get_contents($absolute_root . $assets_folder . 'css/' . $css_filename[1]) . PHP_EOL;
			}
		}
		fclose($file);
		file_put_contents($compiled, $contents);
	} else {
		$html .= $individual_imports_template_css . ' could not be found . . . procedure aborted.<br>';
	}

	$status_ok = true;

	if ($status_ok) {
		$response_code = 200;
		$status = 'ok';
		$message = $html . 'Done.';
	} else {
		$response_code = 422;
		$status = 'error';
		$message = $html . 'Failed.';
	}
} else {
	$response_code = 200;
	$status = 'ok';
	$message = 'Not Selected';
}

header('Content-Type: application/json');
http_response_code($response_code);
echo json_encode(array("status" => "$status", "message" => "$message"));
exit;
