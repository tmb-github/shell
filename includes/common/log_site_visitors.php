<?php

function sanitize($string) {
	$string = trim($string); 
	$string = strip_tags($string);
	$string = htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
	$string = str_replace("\n", "", $string);
	$string = trim($string); 
	return $string;
}

$log_visitors = false;

if ($log_visitors == true) {

	$dir = 'visitors';

	if (!is_dir($dir)) {
// NB: 0777 is security permission: make executable
		mkdir($dir, 0777);
	}

	date_default_timezone_set('America/Chicago');

//$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$ipaddress = sanitize($_SERVER['REMOTE_ADDR']);
	$useragent = sanitize($_SERVER['HTTP_USER_AGENT']);
	$file = date('Y-m-d') . '.txt';
	$filename = $dir . '/' . $file;

// Class found in includes/common/browser_detection.php:
	$browser = new Browser();

// 71.199.196.72 = my IP address.
// Don't log me!
	if ($ipaddress != '71.199.196.72') {
		$info = date('d/m/Y H:i:s a') . ' ' . $ipaddress . ' ' . $browser->getBrowser() . ' ' . $browser->getVersion() . ' ' . PHP_EOL;
	}

	file_put_contents($filename, $info, FILE_APPEND);

}
?>