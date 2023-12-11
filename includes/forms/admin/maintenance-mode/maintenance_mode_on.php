<?php

$absolute_root = $_SERVER['ABSOLUTE_ROOT'];

$maintenance_on_file = $absolute_root . 'maintenance_on';
$maintenance_off_file = $absolute_root . 'maintenance_off';

$status = 'ok';
$message = '';

// delete maintenance_off
if (file_exists($maintenance_off_file)) {
	if (!unlink($maintenance_off_file)) {
		$status = 'error';
		$message .= 'maintenance_off file could not be destroyed.<br>';
	} else {
		$message .= 'maintenance_off file destroyed.<br>';
	}
}

// create maintenance_on
if (!file_exists($maintenance_on_file)) {
	if (file_put_contents($maintenance_on_file, '') === false) {
		$status = 'error';
		$message .= 'maintenance_on file could not be created.<br>';
	} else {
		$message .= 'maintenance_on file created.<br>';
	}
}

// Output JSON using json_encode
echo json_encode(["status" => $status, "message" => $message]);

exit;
