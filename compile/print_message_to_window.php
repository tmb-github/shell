<?php
echo '<html>' . PHP_EOL;
echo '<head lang=en-us>' . PHP_EOL;
echo '	<title>Compile files that change</title>' . PHP_EOL;
echo '	<meta charset=utf-8>' . PHP_EOL;
echo '</head>' . PHP_EOL;
echo '<body>' . PHP_EOL;
echo '<pre>';
echo "Compiled CSS indicated in @imports statements in MASTER.CSS into COMPILED.CSS" . PHP_EOL;
echo "Compiled LOADER-TEMPLATE.JS into LOADER.JS, auto-versioning individual JavaScript files that are indicated." . PHP_EOL;
echo "Compiled SITE.WEBMANIFEST, auto-versioning individual image files that are indicated." . PHP_EOL;
echo "Compiled BROWSERCONFIG.XML, auto-versioning individual image files that are indicated." . PHP_EOL;
echo "Compiled SW.JS, auto-versioning individual assets that are indicated." . PHP_EOL;
echo '</pre>';
echo '</body>' . PHP_EOL;
echo '</html>' . PHP_EOL;