<?php

session_start([
	'name' => '__Secure-PHPSESSID',
	'cache_limiter' => 'private_no_expire:'
]);

$absolute_root = $_SERVER['ABSOLUTE_ROOT'];

include_once $absolute_root . 'includes/common/functions.php';

////////////////////////////////////////
// Retrieve existing page information //
////////////////////////////////////////

$subfolders = get_all_subfolders($absolute_root . 'pages/');

$existing_slug_array = array();
foreach ($subfolders as $path) {
	if (strpos($path, "/pages/") !== false) {
		$parts = explode('/', rtrim($path, '/'));
		$existing_slug_array[] = end($parts);
	}
}
sort($existing_slug_array);


$existing_camelcase_array = array();
foreach ($subfolders as $path) {
	if (strpos($path, "/pages/") !== false) {
		$parts = explode('/', rtrim($path, '/'));
		$lastPart = end($parts);
// Omit hyphens and uppercase the letter that follows the hyphen
		$formattedString = preg_replace_callback('/-(.)/', function($matches) {
			return strtoupper($matches[1]);
		}, $lastPart);
		$existing_camelcase_array[] = $formattedString;
	}
}
sort($existing_camelcase_array);

$existing_page_name_array = array();
foreach ($subfolders as $path) {
	if (strpos($path, "/pages/") !== false) {
		$parts = explode('/', rtrim($path, '/'));
		$lastPart = end($parts);
// Replace hyphens with spaces and capitalize all resulting words
		$formattedString = ucwords(str_replace('-', ' ', $lastPart));
		$existing_page_name_array[] = $formattedString;
	}
}
sort($existing_page_name_array);


///////////////////////////////////////////////////////////////////
// UTILITY FUNCTION: remove last occurrence of specified substring:
///////////////////////////////////////////////////////////////////

function str_lreplace($search, $replace, $subject) {
	$pos = strrpos($subject, $search);
	if ($pos !== false) {
		$subject = substr_replace($subject, $replace, $pos, strlen($search));
	}
	return $subject;
}

///////////////////////////////////////////////////
// UTILITY FUNCTION: Delete directory and contents:
///////////////////////////////////////////////////

function delete_folder_and_contents($target) {
	if (is_dir($target)) {
		$files = glob($target . '/*', GLOB_MARK); //GLOB_MARK adds a slash to directories returned
// Call itself recursively:
		foreach ($files as $file) {
			delete_folder_and_contents($file);
		}
		if (file_exists($target)) {
			rmdir($target);
		}
	} elseif (is_file($target)) {
		unlink($target);
	}
}

///////////////////////////////////////////////
// UTILITY FUNCTION: Escape special characters:
///////////////////////////////////////////////

function escape_ascii_string($inputString) {
	return preg_replace_callback('/[\\\'"\x00-\x1F\x7F-\x9F]/', function($match) {
// Replace each special character with its escaped form
		switch ($match[0]) {
			case '\\':
				return '\\\\';
			case '\'':
				return '\\\'';
			case '\"':
				return '\\\"';
			case "\b":
				return '\\b';
			case "\f":
				return '\\f';
			case "\n":
				return '\\n';
			case "\r":
				return '\\r';
			case "\t":
				return '\\t';
			default:
// For non-printable ASCII characters, replace with unicode escape
				return '\\u' . sprintf('%04x', ord($match[0]));
		}
	}, $inputString);
}


/////////////////
// GET POST DATA:
/////////////////

$page_slug = $_POST['page_slug'];
$page_name = $_POST['page_name'];

// To catch apostrophes, etc.:
$page_name = escape_ascii_string($page_name);

$status_ok = true;
$reasons = '';

if (in_array($page_slug, $existing_slug_array)) {
	$status_ok = false;
	$reasons .= '<br>Page slug (' . $page_slug . ') already exists among current pages.';
}

if ($status_ok) {
	if (in_array($page_slug, $existing_slug_array)) {
		$status_ok = false;
		$reasons .= '<br>Page name (' . $page_name . ') already exists among current pages.';
	}
}

//file_put_contents('x.txt', $page_slug . ' ' . $page_name . ' ' . $reasons);


$directory_path = $absolute_root . 'pages/' . $page_slug . '/';

if ($status_ok) {
	if (!file_exists($directory_path)) {
		mkdir($directory_path, 0777, true); // The third parameter 'true' creates nested directories if they don't exist
	} else {
		$status_ok = false;
		$reasons .= 'Directory already exists';
	}
}


function generate_css_array_inc_php($filename, $slug) {
	$content = "<?php\n\$css_array = ['css/pages/$slug.css'];\n";
// Attempt to write content to the file
	$result = file_put_contents($filename, $content);
	if ($result === false) {
		return "Error creating PHP file '$filename'. Check permissions or disk space.";
	}
	return true;
}

if ($status_ok) {
	$return = generate_css_array_inc_php($directory_path . 'css_array.inc.php', $page_slug);
	if (!$return) {
		$reasons .= $return;
		$status_ok = false;
	}
}

function write_index_php($filename) {
	$content = "<?php\n\n";
	$content .= "\$absolute_root = \$_SERVER['ABSOLUTE_ROOT'];\n\n";
	$content .= "include 'index-main-vars.php';\n";
	$content .= "include \$absolute_root . 'includes/common/routines.php';\n";
	$content .= "include \$absolute_root . 'includes/components/html.php';\n";
// Attempt to write content to the file
	$result = file_put_contents($filename, $content);
	if ($result === false) {
		return "Error creating PHP file '$filename'. Check permissions or disk space.";
	}
	return true;
}


if ($status_ok) {
	$return = write_index_php($directory_path . 'index.php');
	if (!$return) {
		$reasons .= $return;
		$status_ok = false;
	}
}

function write_index_main_vars_php($filename, $slug) {
	$content = <<<PHP
<?php

// This file is included in both index.php and main.php

// \$slug & \$page = trailing URL slug and class name for CSS
// \$title = <title> text, 60 characters max

include \$absolute_root . 'includes/common/domain_info.php';
include_once \$absolute_root . 'includes/common/functions.php';

\$slug = '$slug';
\$page = \$slug;

\$title = generate_title_from_request_uri(\$site_title);
PHP;

	// Attempt to write content to the file
	$result = file_put_contents($filename, $content);

	if ($result === false) {
		return "Error creating PHP file '$filename'. Check permissions or disk space.";
	}

	return true;
}


if ($status_ok) {
	$return = write_index_main_vars_php($directory_path . 'index-main-vars.php', $page_slug);
	if (!$return) {
		$reasons .= $return;
		$status_ok = false;
	}
}

function write_main_php($filename) {
	$content = <<<PHP
<?php

\$absolute_root = \$_SERVER['ABSOLUTE_ROOT'];

include_once 'index-main-vars.php';
include_once \$absolute_root . 'includes/common/routines.php';

ob_start();

\$main_classes = \$page . " main custom-style-elements";

?>

<main class="<?php echo \$main_classes; ?>" data-page="<?php echo \$page; ?>" data-title="<?php echo \$title; ?>">
<?php

render_custom_style_elements();

?>
    <h1 id=main-content tabindex=0>Dummy Page</h1>
    <section class=font-samples>
        <p class="roman">The quick brown fox jumped over the lazy dogs.</p>
        <p class="italic">The quick brown fox jumped over the lazy dogs.</p>
        <p class="bold">The quick brown fox jumped over the lazy dogs.</p>
        <p class="bold-italic">The quick brown fox jumped over the lazy dogs.</p>
    </section>

<?php
include \$absolute_root . 'includes/components/breadcrumb_schema.php';
?>
</main>
<?php
include \$absolute_root . 'includes/components/etc/main.ending.inc.php';
PHP;

// Attempt to write content to the file
	$result = file_put_contents($filename, $content);

	if ($result === false) {
		return "Error creating PHP file '$filename'. Check permissions or disk space.";
	}

	return true;
}

if ($status_ok) {
	$return = write_main_php($directory_path . 'main.php', $page_slug);
	if (!$return) {
		$reasons .= $return;
		$status_ok = false;
	}
}

function write_meta_schema_overrides_inc_php($filename) {
	$content = <<<PHP
<?php

////////////////////
// META overrides //
////////////////////

// NB: Ensure corresponding [page].mjs file has the same text as \$description for its _description variable:

\$description = 'Dummy Page description for ' . \$site_title . ' [150-160 characters are best here].';

\$og_description = \$description;

\$twitter_description = \$description;

//////////////////////
// SCHEMA overrides //
//////////////////////

\$schema_webpage_description = \$description;
PHP;

	// Attempt to write content to the file
	$result = file_put_contents($filename, $content);

	if ($result === false) {
		return "Error creating PHP file '$filename'. Check permissions or disk space.";
	}

	return true;
}

if ($status_ok) {
	$return = write_meta_schema_overrides_inc_php($directory_path . 'meta-schema-overrides.inc.php');
	if (!$return) {
		$reasons .= $return;
		$status_ok = false;
	}
}


//////////
// FINISH:
//////////

if ($status_ok) {
	$message = 'Everything ok.';
	echo '{"status": "ok", "message": "' . $message . '"}';
} else {
// send a 422 Unprocessable Entity header, echo the JSON, and exit:
	http_response_code(422);
	$message = 'Page could not be created: ' . $reasons;
	echo json_encode(array("status" => "error", "message" => "$message"));
	exit;
}

