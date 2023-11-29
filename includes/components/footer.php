<?php
ob_start();
echo PHP_EOL;

$social_icon_json = '[
{
	"media": "Facebook",
	"href": "' . $social_media_href['Facebook'] . '",
	"class": "facebook",
	"title": "(Open Facebook in new window/tab)",
	"screenReader": "(Opens Shell on Facebook in new window/tab)"
},
{
	"media": "Instagram",
	"href": "' . $social_media_href['Instagram'] . '",
	"class": "instagram",
	"title": "(Open Instagram in new window/tab)",
	"screenReader": "(Opens Shell on Instagram in new window/tab)"
},
{
	"media": "Pinterest",
	"href": "' . $social_media_href['Pinterest'] . '",
	"class": "pinterest",
	"title": "(Open Pinterest in new window/tab)",
	"screenReader": "(Opens Shell on Pinterest in new window/tab)"
},
{
	"media": "Twitter",
	"href": "' . $social_media_href['Twitter'] . '",
	"class": "twitter",
	"title": "(Open Twitter in new window/tab)",
	"screenReader": "(Opens Shell on Twitter in new window/tab)"
}
]';

$json_data = json_decode($social_icon_json, true);

?>

<footer class=footer>
	<h1 class=visually-hidden aria-hidden=true>Footer</h1>
	<section class=social-media-links>
		<h2 class=screen-reader>Social Media Links</h2>
		<ul class=social-icons>
<?php
foreach($json_data as $attribute)  {
	echo '			<li><a class="social-icon-anchor no-open-in-new no-border-bottom ' . $attribute['class'] . '" href="' . $attribute['href'] . '" title="' . $attribute['title'] . '"><span class=screen-reader>' . $attribute['screenReader'] . '</span></a></li>' . PHP_EOL;
}
?>
		</ul>
	</section>

	<section class="site-links display-block">
		<h2 class=screen-reader>Site Navigation</h2>
		<ul class=internal-links>
			<li class=larger-screens-display-none><a class="internal-anchor home" href="<?php echo $home; ?>">Home</a></li>
			<li class=larger-screens-display-none><a class="internal-anchor google-fonts" href="google-fonts/">Google Fonts</a></li>
			<li><a class="internal-anchor site-map" href="site-map/">Site Map</a></li>
<?php
if (isset($_SESSION['authenticated']) && ($_SESSION['authenticated'] == 'true')) {
?>
			<li><a class="internal-anchor logout" href="admin/logout/" rel="nofollow">Logout</a></li>
<?php
} else {
	$display_login = false;
	if ($display_login) {
?>
			<li><a class="internal-anchor login" href="admin/login/" rel="nofollow">Login</a></li>
<?php
	}
}
?>
		</ul>
	</section>

	<section class=copyright>
		<h2 class=screen-reader>Copyright Notice</h2>
		<p class=copyright-notice>Site design and programming Copyright © 2009–<?php echo date('Y'); ?> by BMT Systems, Inc. <span class=avoid-wrap>All Rights Reserved.</span></p>
	</section>
</footer>

<?php
$html = ob_get_contents();
ob_end_clean();
$html = preg_replace("/[\r\n]+/", "\n\t", $html);
echo $html;
?>