<?php

// NB: Perform this BEFORE running compile_css.php

include './compile_reqs.inc.php';

if ($update_fontface_css == true) {

	$html = '';

	$root_folder = $_SERVER['BASE_PATH'];

	$font_face_template_css = $absolute_root . $assets_folder . 'css/font-face.TEMPLATE.css';

	if (file_exists($font_face_template_css)) {
		$fontface_css_content = file_get_contents($font_face_template_css);
		$fontface_css_content = str_replace('/###ROOT-FOLDER###/', $root_folder, $fontface_css_content);
		file_put_contents($absolute_root . $assets_folder . 'css/font-face.css', $fontface_css_content);
	} else {
		$html .= $font_face_template_css . ' does not exist . . . procedure aborted.<br>';
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
