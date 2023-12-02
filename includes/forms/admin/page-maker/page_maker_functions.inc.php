<?php


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

function capitalize_words($input_string) {
	return ucwords($input_string);
}

function kebab_to_camel_case($kebab_case_string) {
	$words = explode('-', $kebab_case_string);
	foreach ($words as $key => $word) {
		if ($key !== 0) {
			$words[$key] = ucfirst($word);
		}
	}
	return implode('', $words);
}

function write_index_php($filename) {
	$content = <<<PHP
<?php

\$absolute_root = \$_SERVER['ABSOLUTE_ROOT'];

include 'index-main-vars.php';
include \$absolute_root . 'includes/common/routines.php';
include \$absolute_root . 'includes/components/html.php';
PHP;

// Attempt to write content to the file
	$result = file_put_contents($filename, $content);

	if ($result === false) {
		return "Error creating PHP file '$filename'. Check permissions or disk space.";
	}

	return true;
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


function write_main_php($filename, $page_name) {
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
	<h1 id=main-content tabindex=0>$page_name</h1>
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

function write_meta_schema_overrides_inc_php($filename, $capitalized_page_name) {
	$content = <<<PHP
<?php

////////////////////
// META overrides //
////////////////////

// NB: Ensure corresponding [page].mjs file has the same text as \$description for its _description variable:

\$description = '$capitalized_page_name description for ' . \$site_title . ' [150-160 characters are best here].';

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

function write_css_file($filename, $className) {
	$content = <<<CSS
/* 
NB: :root variables declared in page *.css files cannot be revised by JavaScript.
Any CSS variable that will be edited by JavaScript MUST be declared in common.css.
Only :root variables declared in common.css can be revised by JavaScript.
*/

.main.$className {
	margin: calc(1em + var(--header-height)) 1em 3em 1em;
}

.$className .font-samples {
	text-align: center;
}
CSS;

// Attempt to write content to the file
	$result = file_put_contents($filename, $content);

	if ($result === false) {
		return "Error creating CSS file '$filename'. Check permissions or disk space.";
	}
	return true;
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

function write_mjs_file($filename, $camel_case_name, $kebab_case_name, $page_title) {

	$currentYear = date("Y");
	$currentDate = date("Y-m-d");

	$content = <<<JS
/**
* $camel_case_name.mjs
* Copyright (c) 2019-$currentYear Thomas M. Brodhead <https://bmt-systems.com>
* Released under the MIT license
* Date: $currentDate
*/

// Every mjs file has a main() function that is called by common.mjs inner().
//
// Functions from common.mjs are accessed as methods of the 'o' object:
//
// o.commonMjsFunction();
//
// functions local to this module that need access to the 'o' object and its
// methods should receive the 'o' object as a parameter when called:
//
// localFunction(o);
//
// Local functions may also be called with o.[mjsFilePrefix].localFunction();
// but then, in their body, they must have an 'o' variable set equal to
// their 'this' keyword, as is done in main().

var main;
var returnMetaData;


main = function () {

	var metaData;
	var o;

	o = this;

// If there are any utility modules needed on this page, run this function,
// passing as an argument a string with name of this module (camelCase, not kabob-case):
//	o.loadUtilityModules('$camel_case_name');

// Always revise the meta data:
	metaData = returnMetaData(o);
	o.reviseMetaData(metaData);

// always include this in every page.mjs, and execute it last in main():
	o.appendToCSS(':root', '{ --main-opacity: 1; }');

};

returnMetaData = function (o) {

	var _canonical;
	var _default;
	var _description;
	var _image;
	var _imageAlt;
	var _page;
	var _title;
	var metaData;

// NB: Ensure that _description below matches \$description in the corresponding meta-schema-overrides.inc.php for this page.

	_canonical = '\${CANONICAL}';
	_default = '\${DEFAULT}';
	_title = '\${TITLE}';
	_description = '$page_title of ' + o.siteData.metaDescription;
	_page = '$kebab_case_name';
	_image = _default;
	_imageAlt = _default;

	metaData = {
		page: _page,
		name: {
			"description": _description,
			"twitter:card": _default,
			"twitter:creator": _default,
			"twitter:description": _description,
			"twitter:site": _default,
			"twitter:title": _title,
			"twitter:url": _canonical,
			"twitter:image": _image,
			"twitter:image:alt": _imageAlt
		},
		property: {
			"og:description": _description,
			"og:image": _image,
			"og:image:alt": _imageAlt,
			"og:image:height": _default,
			"og:image:width": _default,
			"og:image:secure_url": _image,
			"og:image:type": _default,
			"og:site_name": _default,
			"og:title": _title,
			"og:type": _default,
			"og:url": _canonical
		}
	};

	return metaData;

};

// qwer: for reCAPTCHA: restore recaptchaEdits to module export:
export default Object.freeze({
	main,
	returnMetaData
});
JS;

// Attempt to write content to the file
	$result = file_put_contents($filename, $content);

	if ($result === false) {
		return "Error creating JavaScript file '$filename'. Check permissions or disk space.";
	}

	return true;
}

function modify_site_data_mjs($filename, $page_slug, $camel_cased_name) {

	$backupExtension = 'BACKUP';

	$searchString = "pageDependencies: {";

	$insertLines = [
		"\t'$page_slug': {",
		"\t\tmjs: './$camel_cased_name.mjs'",
		"\t},"
	];

	// Check if the file exists
	if (!file_exists($filename)) {
		return "File '$filename' not found.";
	}

	// Create a backup copy
	$backupFilename = $filename . '.' . $backupExtension;
	copy($filename, $backupFilename);

	// Read the file content
	$fileContent = file_get_contents($filename);

	// Check if the search string is present
	if (strpos($fileContent, $searchString) === false) {
		return "Search string '$searchString' not found in '$filename'.";
	}

	// Prepare the lines to insert
	$insertLinesIndented = implode("\n", array_map(function ($line) {
		return "\t" . $line;
	}, $insertLines));

	// Modify the file content
	$modifiedContent = preg_replace(
		"/$searchString/s",
		"$searchString\n$insertLinesIndented",
		$fileContent
	);

	// Write the modified content back to the file
	$result = file_put_contents($filename, $modifiedContent);

	if ($result === false) {
		return "Error creating JavaScript file '$filename'. Check permissions or disk space.";
	}

	return true;

}
