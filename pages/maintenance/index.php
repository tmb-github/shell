<?php

session_start();

$absolute_root = $_SERVER['ABSOLUTE_ROOT'];

include $absolute_root . 'includes/common/domain_info.php';
include_once $absolute_root . 'includes/common/functions.php';
include $absolute_root . 'includes/common/routines.php';

if ($_SERVER['HTTP_HOST'] === 'localhost') {
	$http_host = "https://localhost/" . $htdocs_folder . "/";
} else {
	$http_host = "https://" . $domain_name . "/";
}

$site_name = $domain_name;

?>
<!DOCTYPE html>
<html lang="en-US">

<head prefix="og: http://ogp.me/ns# article: http://ogp.me/ns/article#" typeof="http://ogp.me/ns#">
	<title>Down for Maintenance | <?php echo $site_title; ?></title>
	<base href="<?php echo $http_host; ?>">
	<meta charset="utf-8">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="apple-mobile-web-app-title" content="<?php echo $site_title_short_form_uc; ?>">
	<meta name="author" content="<?php echo $site_title; ?>">
	<meta name="description" content="Down for Maintenance in the online portfolio of <?php echo $site_title; ?>">
	<meta name="google-site-verification" content="Emklps7uK_STmuirmgr5Z8y6jWumhC-Pch11agfb4pg">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="msapplication-config" content="<?php echo $http_host; ?>favicons/browserconfig.xml">
	<meta name="msapplication-TileColor" content="#eeb935">
	<meta name="robots" content="noindex, nofollow">
	<meta name="theme-color" content="#fafafa">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta property="og:description" content="Down for Maintenance in the online portfolio of <?php echo $site_title; ?>">
	<meta property="og:image:type" content="image/jpeg">
	<meta property="og:site_name" content="<?php echo $site_title; ?>">
	<meta property="og:title" content="Down for Maintenance | <?php echo $site_title; ?>">
	<meta property="og:type" content="website">
	<meta property="og:url" content="<?php echo $http_host; ?>down-for-maintenance/">
	<meta property="og:image" content="<?php echo $http_host; ?>images/head/<?php echo $site_title_short_form_lc; ?>-1200x630.jpg">
	<meta property="og:image:alt" content="<?php echo $site_title; ?>">
	<meta property="og:image:height" content="630">
	<meta property="og:image:width" content="1200">
	<meta property="og:image:secure_url" content="<?php echo $http_host; ?>images/head/<?php echo $site_title_short_form_lc; ?>-1200x630.jpg">
	<meta property="article:published_time" content="2020-01-01T06:00:00+00:00">
	<meta name="twitter:card" content="summary">
	<meta name="twitter:creator" content="@<?php echo $site_title; ?>">
	<meta name="twitter:site" content="@<?php echo $site_title; ?>">
	<meta name="twitter:url" content="<?php echo $http_host; ?>down-for-maintenance/">
	<meta name="twitter:image" content="<?php echo $http_host; ?>images/head/<?php echo $site_title_short_form_lc; ?>-1200x630.jpg">
	<meta name="twitter:image:alt" content="<?php echo $site_title; ?> Logo">
	<meta name="web_author" content="<?php echo $site_title; ?>">
	<link href="https://fonts.googleapis.com/css2?family=Noto+Serif:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link rel="apple-touch-icon" href="favicons/apple-touch-icon.png">
	<link rel="canonical" href="<?php echo $http_host; ?>down-for-maintenance/">
	<link rel="icon" href="favicons/favicon.ico" type="image/x-icon">
	<link rel="icon" href="favicons/favicon-32x32.png" sizes="32x32" type="image/png">
	<link rel="icon" href="favicons/favicon-16x16.png" sizes="16x16" type="image/png">
	<link rel="manifest" href="favicons/site.webmanifest" type="application/manifest+json">
	<link rel="mask-icon" href="favicons/safari-pinned-tab.svg" color="#eeb935">
	<style <?php echo $inline_nonce_property; ?>>
		main {
			display: block;
		}

		a {
			background-color: transparent;
		}

		img {
			border-style: none;
		}

		input {
			font-family: inherit;
			font-size: 100%;
			line-height: 1.15;
			margin: 0;
		}
		input {
			overflow: visible;
		}

		:focus {
			outline: #9ecaed auto 5px;
		}
		
		* {
			box-sizing: border-box;
			margin: 0;
			padding: 0;
		}

		.screen-reader {
			position: absolute;
			left: -10000px;
			top: auto;
			width: 1px;
			height: 1px;
			overflow: hidden;
		}

		html {
			line-height: 1.15;
			height: 100%;
			scroll-behavior: smooth;
			-webkit-text-size-adjust: 100%;
			-ms-text-size-adjust: 100%;
			    text-size-adjust: 100%;
			overflow-y: scroll;
			-webkit-writing-mode: horizontal-tb;
			-ms-writing-mode: lr-tb;
			writing-mode: horizontal-tb;
		}

		@media screen and (min-width:760px) {
			html {
				overflow-y: visible;
			}
		}

		body {
			margin: 0;
		}

		h1 {
			font: 700 2em/1.5 'Noto Sans', sans-serif;
			display: block;
			margin: .67em 0;
		}

		h2 {
			font: 700 1.75em/1.5 'Noto Sans', sans-serif;
			display: block;
		}

		img {
			font: 400 16px 'Noto Sans', sans-serif;
			color: #565656;
		}

		span.avoid-wrap {
			display: inline-block;
		}

		.body .h5-italic {
			font: italic 700 16px/1.5 'Noto Sans', sans-serif;
			font: italic 700 1rem/1.5 'Noto Sans', sans-serif;
			display: block;
		}

		.text-align-center {
			text-align: center;
		}

		.display-block {
			display: block;
		}

		.italic {
			font-style: italic;
		}

		.body {
			display: -webkit-box;
			display: -ms-flexbox;
			display: flex;
			-webkit-box-orient: vertical;
			-webkit-box-direction: normal;
			-ms-flex-direction: column;
			    flex-direction: column;
			height: 100%;
			font-size: 16px;
			background-color: #fafafa;
			font-family: 'Noto Sans', sans-serif;
			color: #565656;
		}

		.body p~p {
			margin-top: 1em;
		}

		.body a:link,
		.body a:visited,
		.body a:hover,
		.body a:focus,
		.body a:active {
			background-color: transparent;
		}

		.body a[href*="//"][target*=_blank]:hover:after {
			color: #565656;
		}

		.body .header-main-wrapper {
			-webkit-box-flex: 1;
			-ms-flex: 1 0 auto;
			    flex: 1 0 auto;
		}

		.main {
			font: 400 1em/1.5 'Noto Sans', sans-serif;
			margin: 32px 1em 3em 1em;
			position: relative;
		}

		@media screen and (min-width:760px) {
			.main {
				margin: 32px 22% 3em 22%;
			}
		}

		.main a {
			border-bottom: 2px solid #a9a9a9;
			color: black;
			font-weight: 700;
			text-decoration: none;
			-webkit-transition: all .5s;
			transition: all .5s;
		}

		.main a:hover, 
		.main a:not(.no-border-bottom):hover {
			background-color: #e4e4e4;
			border-bottom: 2px solid #696969;
		}

		.main a:active {
			box-shadow: 0 0 36px #696969, inset 0 0 72px #444;
			border-bottom: 2px solid #fff;
			border-radius: 10px 10px 0 0;
			color: #fff;
			outline: 0;
		}

		.main h1 {
			margin: 1em 0 .5em 0;
			line-height: 1.25;
		}

		.footer {
			background-color: #080808;
			background-image: -webkit-linear-gradient(#565656, #080808 50%);
			background-image: linear-gradient(#565656, #080808 50%);
			color: #fff;
			-ms-flex-negative: 0;
			flex-shrink: 0;
			padding: 1em;
			text-align: center;
		}

		.footer .copyright {
			padding: .5em;
		}

		.down-for-maintenance a:link {
			background: transparent;
		}

		.down-for-maintenance p {
			margin: 1em 0;
			text-align: center;
		}

		.down-for-maintenance h1 {
			text-shadow: 0 0 1px #fff, 0 0 0 #adadad, 1px 1px 0 #adadad, 1px 1px 1px #adadad;
			line-height: 1.5;
			letter-spacing: .1em;
			word-spacing: .1em;
		}

		.visually-hidden {
			border: 0;
			clip: rect(0,0,0,0);
			height: 1px;
			margin: -1px;
			overflow: hidden;
			padding: 0;
			position: absolute;
			width: 1px;
			white-space: nowrap;
		}

	</style>
</head>

<body class="body" tabindex="0">
	<div class="header-main-wrapper">
		<main class="main down-for-maintenance">
			<h1 class="italic text-align-center" tabindex="0">Down for Maintenance</h1>
			<p><strong>We’re down for maintenance temporarily. Please check back with us soon.</strong></p>
			<p><strong>Thank you!</strong></p>
		</main>
	</div>
	<footer class="footer">
		<h1 class=visually-hidden aria-hidden=true>Footer</h1>
		<div class="copyright display-block">
			<p>All images and text Copyright © 2009–<?php echo date("Y"); ?> by <?php echo $site_title; ?>. <span class="avoid-wrap">All Rights Reserved.</span></p>
		</div>
	</footer>
</body>

</html>