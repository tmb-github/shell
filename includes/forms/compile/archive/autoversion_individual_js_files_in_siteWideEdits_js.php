<?php 

// Perform this routine BEFORE running autoversioned_individual_js_files_in_loader_js

echo 'Autoversioning individual js files in siteWideEdits.js . . .' . PHP_EOL;

$absolute_root = $_SERVER['ABSOLUTE_ROOT'];
$site_wide_edits_template_js = $absolute_root . 'assets/javascript/siteWideEdits.TEMPLATE.js';

if (file_exists($site_wide_edits_template_js)) {

	$sitewide_template = file_get_contents($site_wide_edits_template_js);

// Auto-version enqueued javascript files:
// E.g., enqueue({src: 'assets/javascript/jQuery-3.4.1.min.js'});
	$matches = '';
	preg_match_all("/(enqueue\({src: ')(.+)('}\);)/i", $sitewide_template, $matches);

	for ($i = 0; $i < count($matches[0]); $i++) {
		$resource = $matches[2][$i];
		if (substr($resource, 0, 10) === 'javascript') {
			$autoversioned = autoVersion($resource);
			$sitewide_template = str_replace($resource, $autoversioned, $sitewide_template);
		}
	}

// Auto-version javascript files in promise structures:
// E.g., loadLocalResource.js('assets/javascript/pureJsCarousel.min.js')
	$matches = '';
	preg_match_all("/(loadLocalResource.js\(')(.+)('\))/i", $sitewide_template, $matches);

	for ($i = 0; $i < count($matches[0]); $i++) {
		$resource = $matches[2][$i];
		if (substr($resource, 0, 10) === 'javascript') {
			$autoversioned = autoVersion($resource);
			$sitewide_template = str_replace($resource, $autoversioned, $sitewide_template);
		}
	}


// Auto-version the service worker:
	$matches = '';

	preg_match_all("/(navigator\.serviceWorker\.register\(')(sw.min.js)(', {scope:)/i", $sitewide_template, $matches);

	for ($i = 0; $i < count($matches[0]); $i++) {
		$resource = $matches[2][$i];
		if (substr($resource, 0, 9) === 'sw.min.js') {
			$autoversioned = autoVersion($resource);
			$sitewide_template = str_replace($resource, $autoversioned, $sitewide_template);
		}
	}

	file_put_contents($absolute_root . 'assets/javascript/siteWideEdits.js', $sitewide_template);

} else {
	echo $site_wide_edits_template_js . ' could not be found . . . procedure aborted.' . PHP_EOL . PHP_EOL;
}