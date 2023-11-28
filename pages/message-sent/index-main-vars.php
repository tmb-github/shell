<?php

// This file is included in both index.php and main.php

// $slug & $page = trailing URL slug and class name for CSS
// $schema_name = formal page name
// $title = <title> text, 60 characters max

include $absolute_root . 'includes/common/domain_info.php';
include_once $absolute_root . 'includes/common/functions.php';

$slug = 'message-sent';
$page = $slug;
$schema_name = ucwords(str_replace('-', ' ', $slug));
$title = generate_title_from_request_uri($site_title);
