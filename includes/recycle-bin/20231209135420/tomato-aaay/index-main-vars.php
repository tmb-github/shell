<?php

// This file is included in both index.php and main.php

// $slug & $page = trailing URL slug and class name for CSS
// $title = <title> text, 60 characters max

include $absolute_root . 'includes/common/domain_info.php';
include_once $absolute_root . 'includes/common/functions.php';

$slug = 'tomato-aaay';
$page = $slug;

$title = generate_title_from_request_uri($site_title);