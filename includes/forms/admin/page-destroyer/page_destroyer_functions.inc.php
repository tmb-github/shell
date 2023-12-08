<?php

$subfolders = get_all_subfolders($absolute_root . 'pages/');

$kabob_case_array = array();
$camel_case_array = array();
$snake_case_array = array();
$title_case_array = array();
$value_array = array();
$hashed_array = array();
$hashed_value_array = array();

foreach ($subfolders as $path) {
	if ((strpos($path, "/pages/") !== false) && (substr($path, -6) !== "/admin") && (substr($path, -21) !== "/admin/page-destroyer")) {
		$parts = explode('/', rtrim($path, '/'));
		$last_part = end($parts);
// check if it's an admin page & prefix if so:
		if (prev($parts) == 'admin') {
			$last_part = 'admin/' . $last_part;
		}

// build slug array:
		$kabob_case_array[] = $last_part;

// build camelcase array:
// Omit hyphens and uppercase the letter that follows the hyphen
		$formattedString = preg_replace_callback('/-(.)/', function($matches) {
			return strtoupper($matches[1]);
		}, $last_part);
		$camel_case_array[] = $formattedString;

// build page name array:
// Replace hyphens with spaces and capitalize all resulting words
		$formattedString = ucwords(str_replace('-', ' ', $last_part));

		if (strpos($formattedString, '/') !== false) {
// Split the string by "/"
			$formatted_parts = explode('/', $formattedString);
// Capitalize the first letter of the second part
			$formatted_parts[1] = ucfirst($formatted_parts[1]);
// Join the parts with ": "
			$formattedString = implode(': ', $formatted_parts);
		}
		$title_case_array[] = $formattedString;

// build snake_case array:
// Replace hyphens with underscores:
		$formattedString = str_replace('-', '_', $last_part);
		$snake_case_array[] = $formattedString;

	}
}

array_multisort($snake_case_array, $camel_case_array, $kabob_case_array, $title_case_array);

function moveElementsToEnd($originalArray, $prefix) {
	$matchingElements = array_filter($originalArray, function ($item) use ($prefix) {
		return strpos($item, $prefix) === 0;
	});
	$nonMatchingElements = array_diff($originalArray, $matchingElements);
	return array_merge($nonMatchingElements, $matchingElements);
}

$camel_case_array = moveElementsToEnd($camel_case_array, 'admin/');
$snake_case_array = moveElementsToEnd($snake_case_array, 'admin/');
$kabob_case_array = moveElementsToEnd($kabob_case_array, 'admin/');
$title_case_array = moveElementsToEnd($title_case_array, 'Admin: ');

for ($x = 0; $x < count($title_case_array); $x++) {

// concatenate all the possible forms needed:
	$value = $camel_case_array[$x] . '|' . $snake_case_array[$x] . '|' . $kabob_case_array[$x] . '|' . $title_case_array[$x];
// get their hash:
	$hashed_value = hash('sha256', $value);

// put the value into an array whose index is the hash:
	$value_array[] = $value;
	$hash_array[] = $hashed_value;
	$hash_value_array[$hashed_value] = $value;

// On the client, the hash will be the value sent to the server.
// On the server, the hash will serve as a look-up to the value.
// That way, if the value is altered before/during posting, it won't
// correspond to any of the pages, which should safeguard unintended
// page destruction.

}
