<?php

// Part of attempt to make JS/MJS of newly created pages run
// upon first navigation to the page:

$absolute_root = $_SERVER['ABSOLUTE_ROOT'];

// Delete cache_control_on
$cache_control_on = $absolute_root . 'cache_control_on';
if (file_exists($cache_control_on)) {
	unlink($cache_control_on);
}

// Create cache_control_off
$cache_control_off = $absolute_root . 'cache_control_off';
if (!file_exists($cache_control_off)) {
	file_put_contents($cache_control_off, '');
}

header('Location: ' . $_SERVER['BASE_PATH']);
