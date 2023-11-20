<?php
ob_start();
?>
	<script type=application/ld+json id=breadcrumb-list>
		{
			"@context": "https://schema.org",
			"@type": "BreadcrumbList",
			"description": "Breadcrumbs list",
			"name": "Breadcrumbs",
			"itemListElement": [{
				"@type": "ListItem",
				"position": 1,
				"item": {
					"@type": "Thing",
					"@id": <?php echo '"' . $base_href . '"'; ?>,
					"name": "Home",
					"image": {
						"@type": "ImageObject",
						"url": <?php echo '"' . $base_href . $schema_breadcrumb_img_url . '"'; ?>,
						"width": "115",
						"height": "35"
					}
				}
<?php
if ($page != 'home') {
?>
			}, 
			{
				"@type": "ListItem",
				"position": 2,
				"item": {
					"@type": "Thing",
					"@id": <?php echo '"' . $base_href . $slug . '/' . '"'; ?>,
					"name": <?php echo '"' . $schema_name . '"'; ?>,
					"image": {
						"@type": "ImageObject",
						"url": <?php echo '"' . $base_href . $schema_breadcrumb_img_url . '"'; ?>,
						"width": "115",
						"height": "35"
					}
				}
			}]
<?php
} else {
?>
			}]
<?php
}
?>
		}
	</script>
<?php
echo PHP_EOL;
$html = ob_get_contents();
ob_end_clean();
// minify is set in variables.php, which is called by common/routines.php,
// which is called at the top of every main.php file:
if (isset($_SESSION['minify']) && ($_SESSION['minify'] == true)) {
	$html = tovic_minify_js($html);
}
echo $html;
