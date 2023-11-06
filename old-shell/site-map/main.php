<?php

$absolute_root = $_SERVER['ABSOLUTE_ROOT'];

include_once 'index-main-vars.php';
include_once $absolute_root . 'includes/common_routines.php';

ob_start();

// <main> element data-title should match $title in index.php
// NB: It *must* be hand-coded (i.e., no use of PHP variable), as the SPA mechanism has no way to retrieve it.

$main_classes = $page . " main custom-style-elements";

// After generating XML site map at:
//
// https://www.xml-sitemaps.com
//
// Get the HTML version, accessible as:
//
// https://www.xml-sitemaps.com/download/shell.com-833534e9/sitemap.html?view=1
//
// To edit:
// * Minify then beautify
// * Replace all "<a href=" with "<a class=internal-anchor href="
// * Replace all instances of "https://shell.com" with <?php echo $base_href; ? >
// * Add missing merchandise pages
// * Replace ">1 pages" with ">1 page" (the > is the end of the preceding tag).\
// * Replace "|" with "<br>" in site map footer (not the SHELL footer).
// * Edit HTML of div#top from:
//
//	<div id="top">
//		<nav>shell.com HTML Site Map</nav>
//		<h3>
//		<span>Last updated: 2021, November 29<br />
//		Total pages: 146</span>
//		<a class=internal-anchor href=<?php echo $base_href; ? >>shell.com Homepage</a>
//		</h3>
//	</div>
//
// to:
//
//	<div id=top>
//		<h1>Site Map</h1>
//		<h2>
//			<span>Last updated: 2021, November 29</span>
//			<span>Total pages: 146</span>
//			<span><a class=internal-anchor href=.>shell.com Homepage</a></span>
//		</h2>
//	</div>
//
// * Delete space character in this sequence: "? >"
// * Replace " data-title=" with " data-title="


/*

// Apparently unnecessary...?

if ($_SERVER['HTTP_HOST'] == 'localhost') {
	$base_href = 'https://localhost/' . $htdocs_folder . "'";
} else {
	$base_href = 'https://' . $base_href_name . "'";
}
*/

?>

<main class="<?php echo $main_classes; ?>" data-page="<?php echo $page; ?>" data-title="<?php echo $title; ?>">
<?php

render_custom_style_elements($page);

?>
	<div id=top>
		<h1>Site Map</h1>
		<h2>
			<span>Last updated: 8 August 2022</span>
			<span>Total pages: 7</span>
			<span><a class=internal-anchor href=.><?php echo $site_title; ?> Homepage</a></span>
		</h2>
	</div>
	<div id=cont>
		<ul class=level-0>
			<li class=lhead>/ <span class=lcount>1 page</span></li>
			<li class="lpage last-page"><a class=internal-anchor href=. data-title="Home | <?php echo $site_title; ?>">Home | <?php echo $site_title; ?></a></li>
			<li>
				<ul class=level-1>
					<li class=lhead>dummy-1/ <span class=lcount>1 page</span></li>
					<li class="lpage last-page"><a class=internal-anchor href=dummy-1/ data-title="Dummy 1 | <?php echo $site_title; ?>">Dummy 1 | <?php echo $site_title; ?></a></li>
				</ul>
				<ul class=level-1>
					<li class=lhead>dummy-2/ <span class=lcount>1 page</span></li>
					<li class="lpage last-page"><a class=internal-anchor href=dummy-2/ data-title="Dummy 2 | <?php echo $site_title; ?>">Dummy 2 | <?php echo $site_title; ?></a></li>
				</ul>
				<ul class=level-1>
					<li class=lhead>dummy-3/ <span class=lcount>1 page</span></li>
					<li class="lpage last-page"><a class=internal-anchor href=dummy-3/ data-title="Dummy 3 | <?php echo $site_title; ?>">Dummy 3 | <?php echo $site_title; ?></a></li>
				</ul>
				<ul class=level-1>
					<li class=lhead>dummy-4/ <span class=lcount>1 page</span></li>
					<li class="lpage last-page"><a class=internal-anchor href=dummy-4/ data-title="Dummy 4 | <?php echo $site_title; ?>">Dummy 4 | <?php echo $site_title; ?></a></li>
				</ul>
				<ul class=level-1>
					<li class=lhead>dummy-5/ <span class=lcount>1 page</span></li>
					<li class="lpage last-page"><a class=internal-anchor href=dummy-5/ data-title="Dummy 5 | <?php echo $site_title; ?>">Dummy 5 | <?php echo $site_title; ?></a></li>
				</ul>
				<ul class=level-1>
					<li class=lhead>contact/ <span class=lcount>1 page</span></li>
					<li class="lpage last-page"><a class=internal-anchor href=contact/ data-title="Contact | <?php echo $site_title; ?>">Contact | <?php echo $site_title; ?></a></li>
				</ul>
				<ul class=level-1>
					<li class=lhead>privacy-policy/ <span class=lcount>1 page</span></li>
					<li class="lpage last-page"><a class=internal-anchor href=privacy-policy/ data-title="Privacy Policy | <?php echo $site_title; ?>">Privacy Policy | <?php echo $site_title; ?></a></li>
				</ul>
			</li>
		</ul>
	</div>
	<div id=footer>Page created with <a target=_blank href=https://www.xml-sitemaps.com>Google XML sitemap and html sitemaps generator</a> <br> Copyright &copy; 2005-<?php echo date('Y'); ?> XML-Sitemaps.com</div>

<?php
include $absolute_root . 'includes/html/common/main-breadcrumb-schema.inc.php';
?>
</main>
<?php
include $absolute_root . 'includes/html/common/main-ending.inc.php';
?>