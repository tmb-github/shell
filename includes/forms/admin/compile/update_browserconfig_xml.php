<?php

include './compile_reqs.inc.php';

if ($update_browserconfig_xml == true) {

	$browserconfig_xml = '<?xml version="1.0" encoding="utf-8"?>' . PHP_EOL;
	$browserconfig_xml .= '<browserconfig>' . PHP_EOL;
	$browserconfig_xml .= '  <msapplication>' . PHP_EOL;
	$browserconfig_xml .= '    <tile>' . PHP_EOL;

// Apparently need to use a full, qualified path:
	$browserconfig_xml .= '      <square150x150logo src="' . $root_dir . $mstile_150x150_png . '"/>' . PHP_EOL;
	$browserconfig_xml .= '      <TileColor>' . $browserconfig_tile_color . '</TileColor>' . PHP_EOL;
	$browserconfig_xml .= '    </tile>' . PHP_EOL;
	$browserconfig_xml .= '  </msapplication>' . PHP_EOL;
	$browserconfig_xml .= '</browserconfig>' . PHP_EOL;

	$return_value = file_put_contents($absolute_root . $assets_folder . 'favicons/browserconfig.xml', $browserconfig_xml);

	$status_ok = ($return_value !== false);

	if ($status_ok) {
		$response_code = 200;
		$status = 'ok';
		$message = 'Done.';
	} else {
		$response_code = 422;
		$status = 'error';
		$message = 'Failed.';
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
