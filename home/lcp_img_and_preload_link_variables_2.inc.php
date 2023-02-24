<?php

// LCP = Largest Contentful Paint
// These are the variables used for the landing image on the home page (/main.php)
// and the corresponding preload LINK in the HEAD (/includes/html/head.php)

// NB: To generate CSS for the transitioning animation,
// edit the index.php in https://localhost/shell/make-animation-css/
// and then open that URL; copy the CSS written to the screen to page-home.css

$img_url_array = [
	'images/home/dummy-1/dummy-1-gallery.jpg'
];

// If $variable_size == true, provide $img_widths & sizes; leave multipliers empty
// If $variable_size == false, provide $img_widths & multipliers; leave sizes empty
$variable_size = true;

$img_widths = [344, 560, 824, 918, 1084, 1254];

$multipliers = [];

// NEW (2021-12-13): order inverted and numbers revised:
$sizes = '(max-width: 727px) 87vw, ((min-width: 727px) and (max-width: 1131px)) calc((87vw - 4.5em) / 2), (min-width: 1131px) calc((87vw - 9em) / 3)';
