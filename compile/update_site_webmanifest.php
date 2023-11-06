<?php

echo 'Updating site.webmanifest . . .' . PHP_EOL;

$absolute_root = $_SERVER['ABSOLUTE_ROOT'];

$site_webmanifest = '{' . PHP_EOL; 
$site_webmanifest .= '  "background_color": "' . $site_webmanifest_background_color . '",' . PHP_EOL; 
$site_webmanifest .= '  "description": "' . $site_webmanifest_description . '",' . PHP_EOL;
$site_webmanifest .= '  "display": "standalone",' . PHP_EOL;
$site_webmanifest .= '  "icons": [{' . PHP_EOL;
// remove initial 'assets/favicons/':
$site_webmanifest .= '      "src": "' . substr($favicon_ico, 16) . '",' . PHP_EOL; 
$site_webmanifest .= '      "type": "image/x-icon",' . PHP_EOL; 
$site_webmanifest .= '      "sizes": "48x48"' . PHP_EOL; 
$site_webmanifest .= '    }, {' . PHP_EOL; 
/*
// remove initial 'assets/favicons/':
$site_webmanifest .= '      "src": "' . substr($maskable_icon_64x64_png, 16) . '",' . PHP_EOL; 
$site_webmanifest .= '      "sizes": "64x64",' . PHP_EOL; 
$site_webmanifest .= '      "type": "image/png",' . PHP_EOL; 
$site_webmanifest .= '      "purpose": "any maskable"' . PHP_EOL;
$site_webmanifest .= '    }, {' . PHP_EOL; 

See: https://betterprogramming.pub/app-shortcuts-and-maskable-icons-play-it-like-twitter-c7da9b7e90fa

Generate maskable icons here: https://maskable.app/editor

*/
// remove initial 'assets/favicons/':
$site_webmanifest .= '      "src": "' . substr($maskable_icon_192x192_png, 16) . '",' . PHP_EOL; 
$site_webmanifest .= '      "sizes": "192x192",' . PHP_EOL; 
$site_webmanifest .= '      "type": "image/png",' . PHP_EOL; 
$site_webmanifest .= '      "purpose": "maskable"' . PHP_EOL;
$site_webmanifest .= '    }, {' . PHP_EOL; 
// remove initial 'assets/favicons/':
$site_webmanifest .= '      "src": "' . substr($maskable_icon_512x512_png, 16) . '",' . PHP_EOL; 
$site_webmanifest .= '      "sizes": "512x512",' . PHP_EOL; 
$site_webmanifest .= '      "type": "image/png",' . PHP_EOL; 
$site_webmanifest .= '      "purpose": "maskable"' . PHP_EOL;
$site_webmanifest .= '    }, {' . PHP_EOL; 


// remove initial 'assets/favicons/':
$site_webmanifest .= '      "src": "' . substr($android_chrome_192x192_png, 16) . '",' . PHP_EOL; 
$site_webmanifest .= '      "sizes": "192x192",' . PHP_EOL; 
$site_webmanifest .= '      "type": "image/png"' . PHP_EOL; 
$site_webmanifest .= '    }, {' . PHP_EOL; 
// remove initial 'assets/favicons/':
/*
$site_webmanifest .= '      "src": "' . substr($android_chrome_256x256_png, 16) . '",' . PHP_EOL; 
$site_webmanifest .= '      "sizes": "256x256",' . PHP_EOL; 
$site_webmanifest .= '      "type": "image/png"' . PHP_EOL; 
$site_webmanifest .= '    }, {' . PHP_EOL; 
*/
// remove initial 'assets/favicons/':
$site_webmanifest .= '      "src": "' . substr($android_chrome_512x512_png, 16) . '",' . PHP_EOL; 
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
// Inmotion Hosting PHP had json_decode() and json_encode() disabled;
// I re-enabled them by adding:
//
// extension=json.so
//
// ...to the Inmotion Hosting PHP.INI file on 2021-04-21:
//
	$site_webmanifest = json_encode(json_decode($site_webmanifest));
}

file_put_contents($absolute_root . 'assets/favicons/site.webmanifest', $site_webmanifest);