<?php

// NB: Perform this BEFORE running compile_css.php

include './compile_reqs.inc.php';

if ($update_fontface_css == true) {

	$html = '';

	$root_folder = $_SERVER['BASE_PATH'];
	$font_face_template_css = $absolute_root . $assets_folder . 'css/font-face.TEMPLATE.css';
	$destination = $absolute_root . $assets_folder . 'css/font-face.css';

	$jsonFilePath = 'file_hashes.json';
	$updated = updateHashInJson($font_face_template_css, $jsonFilePath);
	$message = 'Unchanged.';

// If the file has been updated or the destination file doesn't exist, proceed:
	if (($updated == true) || (!file_exists($destination))) {
		if (file_exists($font_face_template_css)) {
			$fontface_css_content = file_get_contents($font_face_template_css);
			$fontface_css_content = str_replace('/###ROOT-FOLDER###/', $root_folder, $fontface_css_content);
			file_put_contents($destination, $fontface_css_content);
		} else {
			$html .= $font_face_template_css . ' does not exist . . . procedure aborted.<br>';
		}
		$message = $html . 'Done.';
	}

	$status_ok = true;

	if ($status_ok) {
		$response_code = 200;
		$status = 'ok';
//		$message = $html . 'Done.';
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
