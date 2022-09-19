<?php
echo PHP_EOL;
echo 'Stripping comments and blank lines in .htaccess-TEMPLATE and writing to .htaccess...' . PHP_EOL;

if ($_SERVER['HTTP_HOST'] == 'localhost') {
	$localhost = true;
} else {
	$localhost = false;
}

$absolute_root = $_SERVER['ABSOLUTE_ROOT'];

$htaccess_template = $absolute_root . '.htaccess-TEMPLATE';

$fp = fopen($htaccess_template, 'r');

$htaccess = '';
while (!feof($fp)) {
	$buffer = fgets($fp);
	$trimmed_buffer = trim($buffer);
	if ($trimmed_buffer != '') {
		if (substr($trimmed_buffer, 0, 1) != '#') {
			$htaccess .= $buffer;
		} else {
//
// On Inmotion Hosting livehost, we need this line in .htaccess to use PECL Imagick:
//
// suPHP_ConfigPath /home/a07af35/public_html
//
// It causes localhost to fail with a 500 server error, so it must not be retained on localhost.
// It's saved this way in the .htaccess-TEMPLATE:
//
// #	suPHP_ConfigPath /home/a07af35/public_html
//
// So, trim off the initial # from the line (all lines at this point in the IF structure
// will begin with at least one #:
			$trim_off_initial_hash = substr($trimmed_buffer, 1);
// Eliminate the white space surrounding it:
			$trim_what_remains = trim($trim_off_initial_hash);
// See if what remains is the text in question:
			if ($trim_what_remains == 'suPHP_ConfigPath /home/a07af35/public_html') {
// Retain it if we're not on localhost:
				if ($localhost == false) {
					$htaccess .= $trim_what_remains . PHP_EOL;
				}
			}
		}
	}
}
fclose($fp);

file_put_contents($absolute_root . '.htaccess', $htaccess);
