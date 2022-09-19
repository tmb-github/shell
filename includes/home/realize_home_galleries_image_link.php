<?php
if ($main_content == true) {
	$main_content_id = 'id=main-content ';
} else {
	$main_content_id = '';
}
/*
// Use this for H2 element below to restore anchors to the galleries in the 
// text of the cards:
<h2 id=<?php echo $home_galleries_aria_labelledby; ?>>
<a class=internal-anchor href="<?php echo $home_galleries_link; ?>/"><em>
<?php echo $home_galleries_title; ?></em></a></h2>
*/

// NB: This is also used in includes/html/header.php
// Make sure to set identically in both locations.
$use_theme = true;
if ($use_theme == true) {
	$theme = 'theme/';
} else {
	$theme = '';
}

?>
		<section aria-labelledby=<?php echo $home_galleries_aria_labelledby; ?>>
			<div class=text-wrapper>
				<h3 id=<?php echo $home_galleries_aria_labelledby; ?> class="font-size-1rem font-style-italic"><a <?php echo $main_content_id; ?>href="<?php echo $theme . $home_galleries_link . '/'; ?>"><?php echo $home_galleries_title; ?></a></h3>
<?php
if ($home_galleries_card_text != '') {
?>
				<p><?php echo $home_galleries_card_text; ?></p>
<?php
}
?>
			</div>
			<figure class="gallery-image image-wrapper aspect-ratio ratio-1x1" data-href="<?php echo $theme . $home_galleries_link . '/'; ?>">
<?php

// Use Responsive mobile mode in Chrome to find breakpoints:
//
// up through screen width of 726px, the image is 85vw
// from screen width of 727px to 1130px, the image is 40vw
// from screen width 1131px and up, the image is 25vw
//
// Hence: (max-width: 726p.98x) 85vw, (max-width: 1130.98px) 40vw, 25vw
// NB: vw = viewport width = window.innerwidth

?>
<?php

// CONSTANTs defined in main.php:

// CONSTANT: $picture = $GLOBALS['use_picture_element'];
// CONSTANT: $variable_size = true;
// If $variable_size == true, provide $img_widths & sizes; leave multipliers empty
// If $variable_size == false, provide $img_widths & multipliers; leave sizes empty
// CONSTANT: $img_widths = [344, 560, 824, 918, 1084, 1254];
// CONSTANT: $multipliers = [];
// CONSTANT: $sizes = '(min-width: 1130px) calc((87vw - 9em) / 3), ((min-width: 726px) and (max-width: 1130px)) calc((87vw - 4.5em) / 2), (max-width: 726px) 87vw';

// 2020-07-18
// Provide full URL, including extension (for revision of picture_or_img_element())
$img_url = $home_galleries_image;

// CONSTANT: $picture_attribute_array = array();
// settings needed for $img_attribute_array[]:
$alt = 'Navigate to ' . $home_galleries_title . ' Gallery';
// CONSTANT: $centered_image = true;

// 2020-08-15
if ($GLOBALS['use_browser_based_lazy_loading'] == true) {
	$waypoints = false;
} else {
	$waypoints = true;
}

// Retain for now; this will be excised from the attributes in the
// picture_or_img_element() function, because the aspect ratio CSS
// fails if an explicit width or height is supplied in the IMG tag:

// 2020-10-04
// UPDATE: This now serves as a flag to use actual pixel width and 
// height on IMG element in picture_or_img_element(). The trick when
// using aspect ratio boxes is to set width: 100% and height: 100%
// on the image using CSS, which is now done in common.css.
$width = '100%';
$height = '100%';

$img_attribute_array = return_img_attribute_array($alt, $centered_image, $waypoints, $width, $height);
// For additional IMG attributes:
// $img_attribute_array['data-attribute-name'] = $data_attribute_variable;
$tabs = 4;
// CONSTANT: $lazyload = $GLOBALS['lazy_load_images'];
// CONSTANT: $social_share = false;

// 2021-12-3:
// Lighthouse complains during mobile testing that these images count toward
// LCP and thus should not be lazyloaded:
// $_SESSION['browser_mobile'] is set in content_security_policy.php:
	if ($_SESSION['browser_mobile'] == true) {
		$lazyload = false;
	}

// return the PICTURE or IMG:
	echo picture_or_img_element($picture, $variable_size, $img_url, $img_widths, $multipliers, $sizes, $picture_attribute_array, $img_attribute_array, $tabs, $lazyload, $social_share);

?>
			</figure>
		</section>
<?php
echo PHP_EOL;
?>