<?php

include 'compile_reqs.inc.php';

if ($update_site_webmanifest == true) {

	$favicons_location = 'favicons/';
	$favicons_location_length = strlen($favicons_location);

	$site_webmanifest = '{' . PHP_EOL; 
	$site_webmanifest .= '  "background_color": "' . $site_webmanifest_background_color . '",' . PHP_EOL; 
	$site_webmanifest .= '  "description": "' . $site_webmanifest_description . '",' . PHP_EOL;
	$site_webmanifest .= '  "display": "standalone",' . PHP_EOL;
	$site_webmanifest .= '  "icons": [{' . PHP_EOL;

// remove initial 'favicons/':
	$site_webmanifest .= '      "src": "' . substr($favicon_ico, $favicons_location_length) . '",' . PHP_EOL; 
	$site_webmanifest .= '      "type": "image/x-icon",' . PHP_EOL; 
	$site_webmanifest .= '      "sizes": "48x48"' . PHP_EOL; 
	$site_webmanifest .= '    }, {' . PHP_EOL; 

// remove initial 'favicons/':
	$site_webmanifest .= '      "src": "' . substr($maskable_icon_192x192_png, $favicons_location_length) . '",' . PHP_EOL; 
	$site_webmanifest .= '      "sizes": "192x192",' . PHP_EOL; 
	$site_webmanifest .= '      "type": "image/png",' . PHP_EOL; 
	$site_webmanifest .= '      "purpose": "maskable"' . PHP_EOL;
	$site_webmanifest .= '    }, {' . PHP_EOL; 

// remove initial 'favicons/':
	$site_webmanifest .= '      "src": "' . substr($maskable_icon_512x512_png, $favicons_location_length) . '",' . PHP_EOL; 
	$site_webmanifest .= '      "sizes": "512x512",' . PHP_EOL; 
	$site_webmanifest .= '      "type": "image/png",' . PHP_EOL; 
	$site_webmanifest .= '      "purpose": "maskable"' . PHP_EOL;
	$site_webmanifest .= '    }, {' . PHP_EOL; 

// remove initial 'favicons/':
	$site_webmanifest .= '      "src": "' . substr($android_chrome_192x192_png, $favicons_location_length) . '",' . PHP_EOL; 
	$site_webmanifest .= '      "sizes": "192x192",' . PHP_EOL; 
	$site_webmanifest .= '      "type": "image/png"' . PHP_EOL; 
	$site_webmanifest .= '    }, {' . PHP_EOL; 

// remove initial 'favicons/':
	$site_webmanifest .= '      "src": "' . substr($android_chrome_512x512_png, $favicons_location_length) . '",' . PHP_EOL; 
	$site_webmanifest .= '      "sizes": "512x512",' . PHP_EOL; 
	$site_webmanifest .= '      "type": "image/png"' . PHP_EOL; 
	$site_webmanifest .= '    }],' . PHP_EOL; 
	$site_webmanifest .= '  "name": "' . $site_webmanifest_name . '",' . PHP_EOL;
	$site_webmanifest .= '  "orientation": "portrait-primary",' . PHP_EOL;
	$site_webmanifest .= '  "short_name": "' . $site_webmanifest_short_name . '",' . PHP_EOL;
	$site_webmanifest .= '  "start_url": "' . $root_dir . '",' . PHP_EOL; 
	$site_webmanifest .= '  "theme_color": "' . $site_webmanifest_theme_color . '"' . PHP_EOL;
	$site_webmanifest .= '}' . PHP_EOL;

	$minify_json = true;
	if ($minify_json == true) {
//
// IMPORTANT:
//
// Inmotion Hosting PHP had json_decode() and json_encode() disabled;
// I re-enabled them by adding:
//
// extension=json.so
//
// ...to the Inmotion Hosting PHP.INI file on 2021-04-21:
//
		$site_webmanifest = json_encode(json_decode($site_webmanifest));
	}

	$return_value = file_put_contents($absolute_root . $assets_folder . 'favicons/site.webmanifest', $site_webmanifest);

	$status_ok = ($return_value !== false);

	if ($status_ok) {
		$message = 'Done.';
		echo '{"status": "ok", "message": "' . $message . '"}';
	} else {
// send a 422 Unprocessable Entity header, echo the JSON, and exit:
		http_response_code(422);
		$message = 'Failed.';
		echo json_encode(array("status" => "error", "message" => "$message"));
		exit;
	}
} else {
	$message = 'Not Selected';
	echo '{"status": "ok", "message": "' . $message . '"}';
}
