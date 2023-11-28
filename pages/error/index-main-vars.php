<?php

// This file is included in both index.php and main.php

// $slug & $page = trailing URL slug and class name for CSS
// $title = <title> text, 60 characters max

include $absolute_root . 'includes/common/domain_info.php';
include_once $absolute_root . 'includes/common/functions.php';

$slug = 'error';
$page = $slug;

$title = generate_title_from_request_uri($site_title);

// Check if the variable is defined, not null, and not empty
if (!isset($redirect) || empty($redirect)) {
	$redirect = 400;
}

// Replace everything from the start to the first "|"
if (strpos($title, "|") !== false) {
	$title = preg_replace('/^.*?\|/', 'Error ' . $redirect . ' |', $title);
}

