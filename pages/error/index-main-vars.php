<?php

// This file is included in both index.php and main.php

// $slug & $page = trailing URL slug and class name for CSS
// $schema_name = formal page name
// $title = <title> text, 60 characters max

include $absolute_root . 'includes/common/domain_info.php';

$slug = 'error';
$page = $slug;
$schema_name = ucwords(str_replace('-', ' ', $slug));
$title = $schema_name . ' | ' . $site_title;

// Check if the variable is defined, not null, and not empty
if (!isset($redirect) || empty($redirect)) {
	$redirect = 400;
}

// Replace everything from the start to the first "|"
if (strpos($title, "|") !== false) {
	$title = preg_replace('/^.*?\|/', 'Error ' . $redirect . ' |', $title);
}

