<?php
ob_start();
echo PHP_EOL;

$social_icon_json = '[
{
	"media": "Facebook",
	"href": "' . $social_media_href['Facebook'] . '",
	"class": "facebook",
	"title": "(Open Facebook in new window/tab)",
	"screenReader": "(Opens Art by John Q. Public on Facebook in new window/tab)"
},
{
	"media": "Instagram",
	"href": "' . $social_media_href['Instagram'] . '",
	"class": "instagram",
	"title": "(Open Instagram in new window/tab)",
	"screenReader": "(Opens Art by John Q. Public on Instagram in new window/tab)"
},
{
	"media": "Pinterest",
	"href": "' . $social_media_href['Pinterest'] . '",
	"class": "pinterest",
	"title": "(Open Pinterest in new window/tab)",
	"screenReader": "(Opens Art by John Q. Public on Pinterest in new window/tab)"
},
{
	"media": "Twitter",
	"href": "' . $social_media_href['Twitter'] . '",
	"class": "twitter",
	"title": "(Open Twitter in new window/tab)",
	"screenReader": "(Opens Art by John Q. Public on Twitter in new window/tab)"
}
]';

$json_data = json_decode($social_icon_json, true);

?>

<footer class=footer>
	<h1 class=screen-reader>Footer</h1>
	<ul class=social-icons>
<?php
foreach($json_data as $attribute)  {
	echo '		<li><a class="social-icon-anchor no-open-in-new no-border-bottom ' . $attribute['class'] . '" href="' . $attribute['href'] . '" title="' . $attribute['title'] . '"><span class=screen-reader>' . $attribute['screenReader'] . '</span></a></li>' . PHP_EOL;
}
?>
	</ul>
	<div class="copyright display-block">
		<p class=internal-links><a class="internal-anchor privacy-policy" href="privacy-policy/">Privacy Policy</a> | <a class="internal-anchor site-map" href="site-map/">Site Map</a></p>
		<p class=copyright-notice>All images and text Copyright © 2009–<?php echo date('Y'); ?> by John Q. Public. <span class=avoid-wrap>All Rights Reserved.</span></p>
	</div>
</footer>

<?php
$html = ob_get_contents();
ob_end_clean();
$html = preg_replace("/[\r\n]+/", "\n\t", $html);
echo $html;
?>