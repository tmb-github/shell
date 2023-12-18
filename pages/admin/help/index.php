<?php

session_start([
	'name' => '__Secure-PHPSESSID',
	'cache_limiter' => 'private_no_expire:'
]);

if (isset($_SESSION['authenticated']) && ($_SESSION['authenticated'] == 'true')) {
?>
<!doctype html>
<html lang=en-us>
<head>
<title>Help</title>
<meta charset=utf-8>
<meta name=viewport content="width=device-width, initial-scale=1.0">
</head>
<body>
<?php
echo '<pre>';
echo "admin/compile                    : Compile various resources (required if anything changes in JavaScript)" . PHP_EOL;
echo "?minify                     : minify the HTML (default on live site)" . PHP_EOL;
echo "?beautify                   : beautify the HTML (default on localhost)" . PHP_EOL;
echo "Ctrl+Alt+s                  : Stop service worker, empty caches, clear session storage" . PHP_EOL;
echo "Ctrl+Alt+l                  : Go to login screen" . PHP_EOL;
echo '</pre>';
?>
</body>
</html>
<?php

} else {

// BASE_PATH defined in .htaccess:
	header('Location: ' . $_SERVER['BASE_PATH'] . 'admin/login/');

}
