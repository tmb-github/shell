<?php
ob_start();

// https://community.adobe.com/t5/photoshop-ecosystem-discussions/p-how-do-i-export-to-svg-from-photoshop/m-p/12208976

// JavaScript should search for #skip-to-main-content and change href to 
// location.pathname + '#main-content' when the page is loaded.

// CONSTANT settings for the two logo images:

$picture = $GLOBALS['use_picture_element'];
$variable_size = false;
// If $variable_size == true, provide $img_widths & sizes; leave multipliers empty
// If $variable_size == false, provide $img_widths & multipliers; leave sizes empty

// 2020-05-15:
// OLD: $img_widths = ['115x35', '230x70', '345x105', '460x140'];
$img_widths = ['115', '230', '345', '460'];

$multipliers = ['1', '2', '3', '4'];
$sizes = '';

// 2020-07-18
// Provide full URL, including extension (for revision of picture_or_img_element())
// 2020-05-15:
// OLD: $img_url = 'images/header/shell-logo.jpg';
$img_url = $site_logo_url; //'images/header/shell-logo.png';

// VARIABLE: $picture_attribute_array = array('class' => 'logo-for-mobile-menu');
// settings needed for $img_attribute_array[]:

$alt = $site_logo_alt; //'SHELL site logo';
$centered_image = false;

// 2020-08-15
if ($GLOBALS['use_browser_based_lazy_loading'] == true) {
	$waypoints = false;
} else {
	$waypoints = true;
}

$width = 115;
$height = 35;

$img_attribute_array = return_img_attribute_array($alt, $centered_image, $waypoints, $width, $height);
// For additional IMG attributes:
// $img_attribute_array['data-attribute-name'] = $data_attribute_variable;
// VARIABLE: $tabs = 1;
// $lazy_load_images is defined in includes/common-variables/variables.php:
$lazyload = $GLOBALS['lazy_load_images'];
// 2020-11-04
//$lazyload = false;
$social_share = false;

echo PHP_EOL;

$header_classes = "header";

// The load-* classes are an experiment (2020-07-11) in ensuring all site
// fonts load when the first page of the site is opened, regardless of
// which page the user opens:

// 2021-02-13:
// We're not using material icons; the hamburger is an SVG. So... 
// SAVE: <span class=load-material-icons>eco</span>
?>
<header class="<?php echo $header_classes; ?>">
	<h1 class=visually-hidden aria-hidden=true>Header</h1>
	<a href=#main-content id=skip-to-main-content class="hash-anchor skip-to-main-content">Skip to main content</a>
<?php
// If false, use font-display: swap; in font-face.css
$font_force_load = true;

if ($font_force_load == true) {
// 2022-03-13
// Unicode versions of &nbsp; (non-breaking space) character used in each SPAN below (previously A B C D):
?>
	<p class="visually-hidden speak-none" aria-hidden=true data-purpose="To force loading of site fonts"><span class=load-roman> </span><span class=load-italic> </span><span class=load-bold> </span><span class=load-bold-italic> </span></p>
<?php

}

?>
	<input id=hamburger type=checkbox title="hidden checkbox" name=ignore value=ignore>
<?php

/*
if ($_SESSION['IE_OR_EDGE_BROWSER'] === true) {
	$menu = '&#xe5d2;';
} else {
	$menu = 'menu';
}
*/

// SVG version of material menu icon generated directly at: 
// https://material.io/resources/icons/?icon=menu&style=baseline
//
// NB: If I make the <path> elements self-closing, like this: 
// <path [attributes] />, then Chrome converts then to 
// <path [attributes]></path>
// IE11 complains that <path> elements are not self-closing, and I can't find 
// documentation indicating that they are, so I'm going with what Chrome does 
// under the hood to speed up its process:

// See: https://www.sitepoint.com/tips-accessible-svg/

$menu = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" role=img aria-labelledby=svg-menu-icon-title aria-describedby=svg-menu-icon-desc><title id=svg-menu-icon-title>menu</title><desc id=svg-menu-icon-desc>three stacked horizontal bars</desc><path fill="none" d="M0 0h24v24H0z"></path><path fill="#565656" d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"></path></svg>';

// 2021-02-13:
// We're not using material icons; the hamburger is an SVG. So... 
// SAVE: <label for=hamburger tabindex=0><span class=material-icons><?php echo $menu; ?XXX></span></label>

// qwerty
// 	<label for=hamburger tabindex=0><span class=necessary-span-for-hover-styling><?php echo $menu; ? ></span></label>

?>
	<label for=hamburger tabindex=0><span class=necessary-span-for-hover-styling><?php echo $menu; ?></span></label>
<?php

// CONSTANTs defined at top of this file, as this symbol is defined twice in the 

// CONSTANT:	$picture = $GLOBALS['use_picture_element'];
// CONSTANT:	$variable_size = false;
// If $variable_size == true, provide $img_widths & sizes; leave multipliers empty
// If $variable_size == false, provide $img_widths & multipliers; leave sizes empty
// CONSTANT:	$img_widths = ['115x35', '230x70', '345x105', '460x140'];
// CONSTANT:	$multipliers = ['1', '2', '3', '4'];
// CONSTANT:	$sizes = '';
// CONSTANT:	$img_url = 'images/header/shell-logo';
	$picture_attribute_array = array(
		'class' => 'logo-for-mobile-menu'
	);
// settings needed for $img_attribute_array[]:
// CONSTANT:	$alt = 'John Q. Public site logo';
// CONSTANT:	$centered_image = false;
// CONSTANT:	$waypoints = false;
// CONSTANT:	$width = 115;
// CONSTANT:	$height = 35;
// CONSTANT:	$img_attribute_array = return_img_attribute_array($alt, $centered_image, $waypoints, $width, $height);
	$tabs = 1;
// CONSTANT:	$lazyload = $GLOBALS['lazy_load_images'];
// CONSTANT:	$social_share = false;

$lazyload = false;

$clickable_mobile_logo = true;

if ($clickable_mobile_logo == true) {

// 2021-06-03:
// role=presentation does not support aria-label="transparent mask"
// So sayeth: https://web.dev/aria-allowed-attr/?utm_source=lighthouse&utm_medium=devtools
// research valid aria-labels for role=presentation
// Also see: https://dockyard.com/blog/2020/03/02/accessible-loading-indicatorswith-no-extra-elements

// To make the mobile image logo a link to the home page (but needs vertical centering):
	if ($_SERVER['HTTP_HOST'] == 'localhost') {
		$home = './';
	} else {
		$home = '/';
	}
		echo '				<a href="' . $home . '" class="internal-anchor mobile-logo-icon" tabindex=-1>' . PHP_EOL;
		$img_attribute_array['title'] = 'Navigate to Home Page';
		$tabs = 6;
		echo picture_or_img_element($picture, $variable_size, $img_url, $img_widths, $multipliers, $sizes, $picture_attribute_array, $img_attribute_array, $tabs, $lazyload, $social_share);
		echo '					<span class=screen-reader>Home</span>' . PHP_EOL;
		echo '				</a>' . PHP_EOL;

} else {

// return the PICTURE or IMG:
	echo picture_or_img_element($picture, $variable_size, $img_url, $img_widths, $multipliers, $sizes, $picture_attribute_array, $img_attribute_array, $tabs, $lazyload, $social_share);

}
?>
	<div class=transparent-mask role=presentation></div>
<?php /*	<div class=drawer> */ ?>
<?php /* #header-nav ID is used by the JavaScript to add and remove .selected class from anchors */ ?>
	<nav class=nav id=header-nav aria-labelledby=navigation-heading>
		<h2 class=screen-reader id=navigation-heading>Site Navigation</h2>
		<ul class=primary-ul>
			<li data-page=home data-href=home class=menu-side-one id=nav-list-home-link>
<?php
if ($_SERVER['HTTP_HOST'] == 'localhost') {
	$home = './';
} else {
	$home = '/';
}
?>
				<a href="<?php echo $home; ?>" class="internal-anchor" tabindex=-1>
<?php
// $img_url will have "-" plus the pixel size from the img_widths[] array plus "px.jpg" appended to it.
// NB: $img_url omits the size and extension:
// Example full URL: 'images/header/shell-logo-115x35px.jpg'

// CONSTANT:	$picture = $GLOBALS['use_picture_element'];
// CONSTANT:	$variable_size = false;
// If $variable_size == true, provide $img_widths & sizes; leave multipliers empty
// If $variable_size == false, provide $img_widths & multipliers; leave sizes empty
// CONSTANT:	$img_widths = ['115', '230', '345', '460'];
// CONSTANT:	$multipliers = ['1', '2', '3', '4'];
// CONSTANT:	$sizes = '';
// CONSTANT:	$img_url = 'images/header/SHELL-logo.jpg';

	$picture_attribute_array = array();
// settings needed for $img_attribute_array[]:
// CONSTANT:	$alt = 'John Q. Public site logo';
// CONSTANT:	$centered_image = false;
// CONSTANT:	$waypoints = false;
// CONSTANT:	$width = 115;
// CONSTANT:	$height = 35;
// CONSTANT:	$img_attribute_array = return_img_attribute_array($alt, $centered_image, $waypoints, $width, $height);
	$img_attribute_array['title'] = 'Navigate to Home Page';

	$tabs = 6;

// CONSTANT:	$lazyload = $GLOBALS['lazy_load_images'];
// CONSTANT:	$social_share = false;

?>
					<div class="home-image-link picture-image-anchor">
<?php
// return the PICTURE or IMG:
	echo picture_or_img_element($picture, $variable_size, $img_url, $img_widths, $multipliers, $sizes, $picture_attribute_array, $img_attribute_array, $tabs, $lazyload, $social_share);
?>
					</div>
					<div class="home-text-link internal-anchor">Home</div>
				</a>
			</li>
			<li data-page=dummy-1 data-href=dummy-1 class=menu-side-one><a class=internal-anchor href="dummy-1/" tabindex=-1>Dummy 1</a></li>
			<li data-page=dummy-2 data-href=dummy-2 class=menu-side-one><a class=internal-anchor href="dummy-2/" tabindex=-1>Dummy 2</a></li>
<?php

	$switcher_name = 'Additional Pages';
	$switcher_id = 'additional-pages';
// This must be 'side-switcher-' followed by a unique character:
	$side_switcher_id = 'side-switcher-1';
	$main_menu_id = 'main-menu-1';
// $page_name => $page_slug:
	$page_array = [
		'Dummy 3' => 'dummy-3',
		'Dummy 4' => 'dummy-4', 
		'Dummy 5' => 'dummy-5',
		'Dummy 13' => 'dummy-13',
		'Dummy 14' => 'dummy-14', 
		'Dummy 15' => 'dummy-15',
		'Dummy 23' => 'dummy-23',
		'Dummy 24' => 'dummy-24', 
		'Dummy 25' => 'dummy-25'
	];
	include $absolute_root . 'includes/html/header-secondary-ul.inc.php';
/*
	$switcher_name = 'Additional Pages 2';
	$switcher_id = 'additional-pages-2';
// This must be 'side-switcher-' followed by a unique character:
	$side_switcher_id = 'side-switcher-2';
	$main_menu_id = 'main-menu-2';
// $page_name => $page_slug:
	$page_array = [
		'Dummy 13' => 'dummy-13',
		'Dummy 14' => 'dummy-14', 
		'Dummy 15' => 'dummy-15'
	];
	include $absolute_root . 'includes/html/header-secondary-ul.inc.php';

	$switcher_name = 'Additional Pages 3';
	$switcher_id = 'additional-pages-3';
// This must be 'side-switcher-' followed by a unique character:
	$side_switcher_id = 'side-switcher-3';
	$main_menu_id = 'main-menu-3';
// $page_name => $page_slug:
	$page_array = [
		'Dummy 23' => 'dummy-23',
		'Dummy 24' => 'dummy-24', 
		'Dummy 25' => 'dummy-25'
	];
	include $absolute_root . 'includes/html/header-secondary-ul.inc.php';
*/
?>
			<li data-page=does-not-exist data-href=does-not-exist class=menu-side-one><a class=internal-anchor href="does-not-exist/" tabindex=-1>Does Not Exist</a></li>
			<li data-page=contact data-href=contact class="menu-side-one orange ripple"><a class="bold-on-selected internal-anchor no-border-bottom" href="contact/" tabindex=-1>Contact</a></li>
<?php
if (isset($_SESSION['authenticated']) && ($_SESSION['authenticated'] == 'true')) {
?>
			<li data-page=logout data-href=logout class=menu-side-one><a class=internal-anchor href="logout/" tabindex=-1>Logout</a></li>
<?php
}
?>
		</ul>
		<div class=progress-line></div>
	</nav>
<?php /*	</div> */ ?>

</header>

<?php
$html = ob_get_contents();
ob_end_clean();
$html = preg_replace("/[\r\n]+/", "\n\t", $html);
echo $html;
?>