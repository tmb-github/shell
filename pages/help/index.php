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
echo "/compile                    : Compile various resources (required if anything changes in JavaScript)" . PHP_EOL;
echo "?minify                     : minify the HTML (default on live site)" . PHP_EOL;
echo "?beautify                   : beautify the HTML (default on localhost)" . PHP_EOL;
echo "Ctrl+Alt+s                  : Stop service worker, empty caches, clear session storage" . PHP_EOL;
echo "Ctrl+Alt+l                  : Go to login screen" . PHP_EOL;
echo PHP_EOL;
echo "If using service worker, to switch between minify and beautify mode takes additional steps:" . PHP_EOL;
echo PHP_EOL;
echo "1. Go to home page" . PHP_EOL;
echo "2. Perform hard-refresh" . PHP_EOL;
echo "3. Turn off service worker by clicking screen and depressing Ctrl+Alt+s" . PHP_EOL;
echo "4. Append either ?minify or ?beautify to URL and reload. A new service worker will start, but the desired mode will be in effect." . PHP_EOL;
echo '</pre>';
?>
</body>
</html>