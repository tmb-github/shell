<?php
ob_start();

// In admin mode, add data-admin to <BODY> element:
if (isset($_SESSION['authenticated']) && ($_SESSION['authenticated'] == 'true')) {
	$body_data_attributes = 'data-orientation=initial data-admin';
} else {
	$body_data_attributes = 'data-orientation=initial';
}

/* <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAA+gAAABkCAQAAAC/MNJRAAABUElEQVR42u3VAREAAAQEMB9HKv1byMFtJZaeAgCOi9ABQOgAgNABAKEDAEIHAKEDAEIHAIQOAAgdAIQOAAgdABA6ACB0ABA6ACB0AEDoAIDQAUDoAIDQAQChAwBCBwChAwBCBwCEDgAIHQCEDgAIHQAQOgAgdAAQOgAgdABA6ACA0AFA6ACA0AEAoQMAQgcAoQMAQgcAhA4ACB0AhA4ACB0AEDoAIHQAQOgAIHQAQOgAgNABAKEDgNABAKEDAEIHAIQOAEIHAIQOAAgdABA6AAgdABA6ACB0AEDoACB0AEDoAIDQAQChA4DQAQChAwBCBwCEDgBCBwCEDgAIHQAQOgAIHQAQOgAgdABA6AAgdABA6ACA0AEAoQOA0AEAoQMAQgcAhA4AQhc6AAgdABA6ACB0AEDoACB0AEDoAIDQAQChA4DQAQChAwBCBwCEDgAfLa43NYUTZ16KAAAAAElFTkSuQmCC"> */
?>
<body class="body" <?php echo $body_data_attributes; ?>>
	<div class="loading-mask active"></div>
<?php

include $component_path . 'header.php';

?>

<?php
// If we're going directly to a painting in a gallery, e.g., https://localhost/shell/comical/audition,
// We need to chop off the painting name (/audition) and just reference the gallery name (comical):
// So...
// Check to see if 'main.php' exists off the incoming URL; if it's comical/audition, it won't exist:

///////////////////////////

// 2023-10-28:
// qwer:htaccess
// htaccess revision

// if /shell/, then /shell/pages/home/
// if /shell/anything-else, then /shell/pages/anything-else

if ($_SERVER['HTTP_HOST'] == 'localhost') {
	$base = '/' . $htdocs_folder . '/';
} else {
	$base = '/';
}
$base_length = strlen($base);

$root = substr($_SERVER['REQUEST_URI'], 0, $base_length);
$root_asset = substr($_SERVER['REQUEST_URI'], $base_length);

if ($root_asset == '') {
	$root_asset = 'pages/home';
} else {
	$root_asset = 'pages/' . $root_asset;
}
// qwer:htaccess
// 2023-10-30
// trim off any trailing forward slash:
$root_asset = rtrim($root_asset, '/');

$full_request = $document_root . $root . $root_asset . '/main.php';

$not_found_request = $document_root . $root . 'pages/error/main.php';

// qwer:htaccess
// 2023-10-30
// unfortunate consequnce of the way the URL is constructed:
// remove duplicate /pages
$full_request = str_replace("/pages/pages/", "/pages/", $full_request);
$not_found_request = str_replace("/pages/pages/", "/pages/", $not_found_request);

if (file_exists($full_request)) {
	include $full_request;
} else {
	include $not_found_request;
}
///////////////////////

//if (file_exists($document_root . $_SERVER['REQUEST_URI'] . 'main.php')) {
//	include $document_root . $_SERVER['REQUEST_URI'] . 'main.php';
//}

// 2020-01-14
//
// Needed for compilation of /error/ page into /404/localhost/index.php and
// /404/livehost/index.php:
//
// $_SESSION['compile'] is set in /compile/index.php
//


// main.php no longer exists in /error:
/*
if ((session_status() == PHP_SESSION_ACTIVE) && (isset($_SESSION['compile'])) && ($_SESSION['compile'] == 'true')) {
	if ($_SERVER['SERVER_NAME'] === 'localhost') {
		include $document_root . '/shell/error/main.php';
	} else {
		include $document_root . '/error/main.php';
	}
}
*/

//include $document_root . $_SERVER['REQUEST_URI'] . 'main.php';
//echo PHP_EOL;
include $component_path . 'footer.php';
include $component_path . 'script.php';
echo PHP_EOL;
?>
</body>

<?php
$html = ob_get_contents();
ob_end_clean();
echo $html;
