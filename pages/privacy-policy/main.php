<?php

$absolute_root = $_SERVER['ABSOLUTE_ROOT'];

include_once 'index-main-vars.php';
include_once $absolute_root . 'includes/common/routines.php';

ob_start();

$main_classes = $page . " main custom-style-elements";

?>

<main class="<?php echo $main_classes; ?>" data-page="<?php echo $page; ?>" data-title="<?php echo $title; ?>">
<?php

render_custom_style_elements($page);

/*
see corresponding ipapi lookup settings in:
common.css
privacy-policy.css
privacyPolicy.mjs
privacy-policy/main.php
*/

?>
	<h1 id=main-content tabindex=0>What’s Your Website Privacy Policy?</h1>

	<section class=ipapi-lookup-true>
		<h2>EU</h2>
		<p>If you are in the EU, we only use essential cookies required for site operation. No tracking cookies are used for analytics or other purposes.</p>

		<h2>Non-EU</h2>
		<p>If you are outside the EU, in addition to essential cookies required for site operation, we use <a href="https://analytics.google.com/analytics/web/">Google Analytics</a> to see how many visitors our site gets, what pages they visit, and how they found the site (e.g., from a Google search or from a link from another site). This helps us understand how well the site is performing and whether it needs sprucing up.</p>

		<p>But we go an extra step: we cloak your identity by <a href="https://support.google.com/analytics/answer/2763052">anonymizing the data</a>. That way, we only learn the fact that someone visited the site, not who it was.</p>

		<p>Google explains their <a href="https://policies.google.com/technologies/partner-sites">Privacy Policy & Terms</a> in detail, but you can simply <a href="https://tools.google.com/dlpage/gaoptout">opt out of Google Analytics</a> altogether if you wish.</p>

		<h2>Everywhere</h2>
		<p>Regardless of your location, if you write to us, we'll keep your email address on file to communicate with you. We won’t share it with third parties unless they’ve been hired to help complete our services to you.</p>

		<p>If you have additional questions, no problem. Just <a href="contact/">write or call us</a> and we’ll be happy to answer!</p>
	</section>

	<section class="ipapi-lookup-false margin-top-1em">
		<p>In addition to essential cookies required for site operation, we use <a href="https://analytics.google.com/analytics/web/">Google Analytics</a> to see how many visitors our site gets, what pages they visit, and how they found the site (e.g., from a Google search or from a link from another site). This helps us understand how well the site is performing and whether it needs sprucing up.</p>

		<p>But we go an extra step: we cloak your identity by <a href="https://support.google.com/analytics/answer/2763052">anonymizing the data</a>. That way, we only learn the fact that someone visited the site, not who it was.</p>

		<p>Google explains their <a href="https://policies.google.com/technologies/partner-sites">Privacy Policy & Terms</a> in detail, but you can simply <a href="https://tools.google.com/dlpage/gaoptout">opt out of Google Analytics</a> altogether if you wish.</p>

		<p>Regardless of your location, if you write to us, we'll keep your email address on file to communicate with you. We won’t share it with third parties unless they’ve been hired to help complete our services to you.</p>

		<p>If you have additional questions, no problem. Just <a href="contact/">write or call us</a> and we’ll be happy to answer!</p>
	</section>

<?php
include $absolute_root . 'includes/html/common/main-breadcrumb-schema.inc.php';
?>
</main>
<?php
include $absolute_root . 'includes/html/common/main-ending.inc.php';
?>