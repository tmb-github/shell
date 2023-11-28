<?php
ob_start();
?>
	<script type=application/ld+json id=breadcrumb-list>
	{
		"@context": "https://schema.org",
		"@type": "BreadcrumbList",
		"description": "Breadcrumbs list",
		"name": "Breadcrumbs",
		"itemListElement": [
<?php

	$folder_array = explode("/", $_SERVER['REQUEST_URI']);

	$base_folder = str_replace("/", "", $_SERVER['BASE_PATH']);
	$folder_name = [];
	$href_array = [];

	array_push($folder_name, $site_title);
	array_push($href_array, $base_href);

	foreach ($folder_array as $folder) {
// exclude empty entries and localhost base_path folder name:
		if (($folder !== '') && ($folder !== $base_folder) && ($folder !== 'main.php')) {
			$dehyphenated = str_replace("-", " ", $folder);
			$uppercased = ucwords($dehyphenated);
			array_push($folder_name, $uppercased);
			array_push($href_array, $href_array[count($href_array) - 1] . $folder . '/');
		}
	}

$b = '';

for ($x = 0; $x < count($href_array); $x++) {
	$b .= '			{' . PHP_EOL;
	$b .= '				"@type": "ListItem",' . PHP_EOL;
	$b .= '				"position": ' . ($x + 1) . ',' . PHP_EOL;
	$b .= '				"item": {' . PHP_EOL;
	$b .= '					"@type": "Thing",' . PHP_EOL;
	$b .= '					"@id": "' . $href_array[$x] . '",' . PHP_EOL;
	$b .= '					"name": "' . $folder_name[$x] . '",' . PHP_EOL;
	$b .= '					"image": {' . PHP_EOL;
	$b .= '						"@type": "ImageObject",' . PHP_EOL;
	$b .= '						"url": "' . $base_href . 'images/header/thomas-brodhead-logo-115x35.jpg",' . PHP_EOL;
	$b .= '						"width": "115",' . PHP_EOL;
	$b .= '						"height": "35"' . PHP_EOL;
	$b .= '					}' . PHP_EOL;
	$b .= '				}' . PHP_EOL;
	$b .= '			}';
	if ($x != (count($href_array) - 1)) {
		$b .= ',' . PHP_EOL;
	} else {
		$b .= PHP_EOL;
	}
}

echo $b;
?>
		]
	}
	</script>
<?php
echo PHP_EOL;
$html = ob_get_contents();
ob_end_clean();
// minify is set in variables.php, which is called by common/routines.php,
// which is called at the top of every main.php file:
if (isset($_SESSION['minify']) && ($_SESSION['minify'] == true)) {
	$html = tovic_minify_js($html);
}
echo $html;
