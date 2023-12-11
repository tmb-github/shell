<?php

$absolute_root = $_SERVER['ABSOLUTE_ROOT'];

$maintenance_on_file = $absolute_root . 'maintenance_on';
$maintenance_off_file = $absolute_root . 'maintenance_off';

$message = '';

if (file_exists($maintenance_on_file)) {
	$message = 'on';
} elseif (file_exists($maintenance_off_file)) {
	$message = 'off';
}

// Output JSON using json_encode
echo json_encode(["status" => "ok", "message" => $message]);

exit;
