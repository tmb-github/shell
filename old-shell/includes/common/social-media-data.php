<?php

// OLD: session_start();

//////////////////////////////////////////////////
// DETECT REFRESH/HARD-REFRESH
//////////////////////////////////////////////////

// Determine first if we're refreshing or not. If we are, clear out instagram data:

// NB: The request_signature is a refresh signature!
//The second parameter on print_r returns the result to a variable rather than displaying it
$request_signature = md5($_SERVER['REQUEST_URI'] . $_SERVER['QUERY_STRING'] . print_r($_POST, true));

if ((!isset($_SESSION['request_signature'])) || (empty($_SESSION['request_signature']))) {
	$_SESSION['request_signature'] = $request_signature;
} else {
	if ($_SESSION['request_signature'] === $request_signature) {
// This is a refresh, so dump any existing instagram data:
		unset($_SESSION['instagram_url']);
		unset($_SESSION['instagram_handle']);
		unset($_SESSION['instagram_data']);
		unset($_SESSION['pinterest_gallery_data']);
	} else {
		$_SESSION['request_signature'] = $request_signature;
	}
}

////////////////////////////////////////////////
// FUNCTIONS
////////////////////////////////////////////////

function curl_load($url){
	$ch = curl_init(); // initializing
	curl_setopt( $ch, CURLOPT_URL, $url ); // API URL to connect
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 ); // Return the result, do not print
	curl_setopt( $ch, CURLOPT_TIMEOUT, 20 );
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	$response = curl_exec($ch);
	curl_close($ch);
	return $response;
}


function getInstagramData($url) {

	$html = curl_load($url);
	$instagram_data = [];

	preg_match_all('/("thumbnail_resources":\[)([^\]]*)(\])/mi', $html, $matches);
	for ($i = 0; $i < count($matches[2]); $i++) {
		$thumbnail_resources = $matches[2][$i];

		preg_match_all('/("src":")([^"]*)(")/i', $thumbnail_resources, $thumbnail_matches);
		for ($j = 0; $j < count($thumbnail_matches[2]); $j++) {
			$src = $thumbnail_matches[2][$j];
			if ($j === 0) {
				$key = '150x150';
			}
			if ($j === 1) {
				$key = '240x240';
			}
			if ($j === 2) {
				$key = '320x320';
			}
			if ($j === 3) {
				$key = '480x480';
			}
			if ($j === 4) {
				$key = '640x640';
			}
			$instagram_data[$i][$key] = $src;
		}
	}

	preg_match_all('/("shortcode":")([^"]*)(")/i', $html, $matches);
	for ($i = 0; $i < count($matches[2]); $i++) {
		$shortcode = $matches[2][$i];
		$instagram_data[$i]['shortcode'] = $shortcode;
	}


// This should work for returning text with escaped quotes within the text, but it doesn't:
// ("text":")([^"\\]*(?:\\.[^"\\]*)*)(")
//
// SOLUTION: ("text":")([^"\\\\]*(?:\\\\.[^"\\\\]*)*)(")
// SEE MY POSTING ABOUT IT: https://stackoverflow.com/questions/55284237/php-regex-syntax-problem-slashes-in-non-capturing-classes
//

	preg_match_all('/("text":")([^"\\\\]*(?:\\\\.[^"\\\\]*)*)(")/i', $html, $matches);
	for ($i = 0; $i < count($matches[2]); $i++) {
		$text = $matches[2][$i];
		$instagram_data[$i]['text'] = $text;
	}

	return $instagram_data;

}

function getPinterestObject($api_url) {

	$json = curl_load($api_url);
	$pinterest_object = json_decode($json);
	return $pinterest_object;
}


// SEE: https://stackoverflow.com/questions/20612652/pinterest-api-image-size

function getPinterestData($username, $board) {
// e.g., https://api.pinterest.com/v3/pidgets/boards/ronsanfordprod/ceiling-treatments-ron-sanford-productions/pins/
	$api_url = 'https://api.pinterest.com/v3/pidgets/boards/' . $username . '/' . $board . '/pins/';

	$pinterest_object = getPinterestObject($api_url);
	$pinterest_data_pins = $pinterest_object->data->pins;
	$pinterest_data = [];
	for ($i = 0; $i < sizeof($pinterest_data_pins); $i++ ) {

		$pinterest_data[$i]['description'] = $pinterest_data_pins[$i]->description;

		$images = get_object_vars($pinterest_data_pins[$i]->images);

		$pinterest_data[$i]['images']['564x']['url'] = $images['564x']->url;
		$pinterest_data[$i]['images']['564x']['width'] = $images['564x']->width;
		$pinterest_data[$i]['images']['564x']['height'] = $images['564x']->height;

		$pinterest_data[$i]['images']['237x']['url'] = $images['237x']->url;
		$pinterest_data[$i]['images']['237x']['width'] = $images['237x']->width;
		$pinterest_data[$i]['images']['237x']['height'] = $images['237x']->height;
	}
	return $pinterest_data;
}

function getPinterestGalleryData($username, $board_names, $boards) {
	$pinterest_gallery_data = [];
	for ($i = 0; $i < sizeof($boards); $i++) {
		$pinterest_gallery_data[$i][0] = $board_names[$i];
		$pinterest_gallery_data[$i][1] = getPinterestData($username, $boards[$i]);
	}
	return $pinterest_gallery_data;
}


/////////////////////////////////
// INSTAGRAM ROUTINE //
////////////////////////////////

// NB: trailing slash is necessary!
//$instagram_handle = 'ronsanfordproductions';
$instagram_handle = 'example';
$instagram_url = "https://www.instagram.com/" . $instagram_handle . "/";


if ((!isset($_SESSION['instagram_url'])) || (empty($_SESSION['instagram_url']))) {
	$_SESSION['instagram_url'] = $instagram_url;
}
if ((!isset($_SESSION['instagram_handle'])) || (empty($_SESSION['instagram_handle']))) {
	$_SESSION['instagram_handle'] = $instagram_handle;
}

// NB: All Instagram variables (except $instagram_url, set here) 
// are set in common_routines.php, not in variables.php:
//
// $display_instagram_image_links
// $instagram_data_json_file
// $get_instagram_data_from_json
//
// Do we want to display Instagram image links in the footer?
if ($display_instagram_image_links == true) {
// No need to grab the data if it's already stored in session variables...
	if ((!isset($_SESSION['instagram_data'])) || (empty($_SESSION['instagram_data']))) {
// How do we want to retrieve it?
		if ($get_instagram_data_from_json == true) {
// Does the JSON file exist?
			if ((file_exists($instagram_data_json_file)) && (filesize($instagram_data_json_file))) {
// If so, read it from the file and put it into the session variable:
// NB: The file will need to be refreshed at the end of processing...
				$instagram_data = file_get_contents($instagram_data_json_file);
				$_SESSION['instagram_data'] = json_decode($instagram_data, true);
			} else {
// If not, read it the "standard" way by retrieving and parsing the HTML from Instagram: 
				$_SESSION['instagram_data'] = getInstagramData($instagram_url);
			}
		} else {
// Retrieve it the standard way:
			$_SESSION['instagram_data'] = getInstagramData($instagram_url);
		}
	}
}

/*
echo '<pre>';
print_r($_SESSION['instagram_data']);
echo '</pre>';
exit;
*/


////////////////////
// PINTEREST //////
//////////////////


$username = $site_title;
$board_names = ['Weddings', 'Ceiling Treatments', 'Cake and Sweetheart Tables'];
$boards = ['weddings-shell', 'ceiling-treatments-shell', 'cake-and-sweetheart-tables-shell'];

// NB: UN-REM THE FOLLOWING TO GET PINTEREST DATA:
/*
if ((!isset($_SESSION['pinterest_gallery_data'])) || (empty($_SESSION['pinterest_gallery_data']))) {
	$_SESSION['pinterest_gallery_data'] = getPinterestGalleryData($username, $board_names, $boards);
}
*/


//https://www.pinterest.com/ronsanfordprod/ceiling-treatments-ron-sanford-productions/

/*
// $number_of_galleries = sizeof($_SESSION['pinterest_gallery_data']);

$_SESSION['pinterest_gallery_data'][x] = gallery / pin-board
$_SESSION['pinterest_gallery_data'][x][0] = gallery name (plaintext)
$_SESSION['pinterest_gallery_data'][x][1] = array of gallery info:

$_SESSION['pinterest_gallery_data'][x][1][y] = array of entries
$_SESSION['pinterest_gallery_data'][x][1][y]['description'] = image description
$_SESSION['pinterest_gallery_data'][x][1][y]['images'] = array of image info
$_SESSION['pinterest_gallery_data'][x][1][y]['images']['564x'] = array of width=564px images
$_SESSION['pinterest_gallery_data'][x][1][y]['images']['564x']['url'] = url
$_SESSION['pinterest_gallery_data'][x][1][y]['images']['564x']['width'] = width in pixels
$_SESSION['pinterest_gallery_data'][x][1][y]['images']['564x']['height'] = height in pixels
$_SESSION['pinterest_gallery_data'][x][1][y]['images']['237x'] = array of width=237px images
$_SESSION['pinterest_gallery_data'][x][1][y]['images']['237x']['url'] = url
$_SESSION['pinterest_gallery_data'][x][1][y]['images']['237x']['width'] = width in pixels
$_SESSION['pinterest_gallery_data'][x][1][y]['images']['237x']['height'] = height in pixels

*/

/*
echo '<pre>';
print_r(sizeof($_SESSION['pinterest_gallery_data']) . PHP_EOL);
print_r(sizeof($_SESSION['pinterest_gallery_data'][0][1]) . PHP_EOL);
print_r(sizeof($_SESSION['pinterest_gallery_data'][1][1]) . PHP_EOL);
print_r(sizeof($_SESSION['pinterest_gallery_data'][2][1]) . PHP_EOL);

for ($x = 0; $x < sizeof($_SESSION['pinterest_gallery_data']); $x++) {
	$gallery_name = $_SESSION['pinterest_gallery_data'][$x][0];
	print_r($gallery_name . PHP_EOL);
	for ($y = 0; $y < sizeof($_SESSION['pinterest_gallery_data'][$x][1]); $y++) {
		print_r($_SESSION['pinterest_gallery_data'][$x][1][$y]['images']['564x']['url'] . PHP_EOL);
		print_r($_SESSION['pinterest_gallery_data'][$x][1][$y]['images']['564x']['width'] . PHP_EOL);
		print_r($_SESSION['pinterest_gallery_data'][$x][1][$y]['images']['564x']['height'] . PHP_EOL);
	}
}
exit;
*/