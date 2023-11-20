<?php

// LCP = Largest Contentful Paint
// These are the variables used for the landing image on the home page (/main.php)
// and the corresponding preload LINK in the HEAD (/includes/components/head.php)

// NB: To generate CSS for the transitioning animation,
// edit the index.php in https://localhost/shell/make-animation-css/
// and then open that URL; copy the CSS written to the screen to assets/css/pages/home.css

// TRANSITIONING IMAGE (list in opposite order of desired appearance, so last listed will be shown first, etc.):
$img_url_array = [
	'assets/images/home/landing-image-set/a02/a02.jpg',
	'assets/images/home/landing-image-set/a07/a07.jpg',
	'assets/images/home/landing-image-set/a06/a06.jpg',
	'assets/images/home/landing-image-set/a05/a05.jpg',
	'assets/images/home/landing-image-set/a04/a04.jpg',
	'assets/images/home/landing-image-set/a03/a03.jpg',
	'assets/images/home/landing-image-set/a09/a09.jpg',
	'assets/images/home/landing-image-set/a01/a01.jpg',
	'assets/images/home/landing-image-set/a08/a08.jpg'
];

// If $variable_size == true, provide $img_widths & sizes; leave multipliers empty
// If $variable_size == false, provide $img_widths & multipliers; leave sizes empty
$variable_size = true;

$img_widths = [683, 768, 804, 918, 1024, 1225];

$multipliers = [];

$sizes = '(max-width: 580px) 87vw, (min-width: 580px) 50vw';
