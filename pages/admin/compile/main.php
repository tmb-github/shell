<?php

$absolute_root = $_SERVER['ABSOLUTE_ROOT'];

include_once 'index-main-vars.php';
include_once $absolute_root . 'includes/common/routines.php';

ob_start();

$oops = false;

$main_classes = $page . " main custom-style-elements";

?>

<main class="<?php echo $main_classes; ?>" data-page="<?php echo $page; ?>" data-title="<?php echo $title; ?>">
<?php

render_custom_style_elements();

?>

	<h1 id=main-content>Compile</h1>
	<form class="compile-form outer-container-to-center" method=post enctype=multipart/form-data>
		<fieldset class=compile-options>
			<legend>Options</legend>
			<ul>
				<li>
					<input type="checkbox" name="do_not_compile_static_html" id="do_not_compile_static_html" checked>
					<label for="do_not_compile_static_html">Do Not Compile Static HTML</label>
				</li>
				<li>
					<input type="checkbox" name="do_not_use_static_html" id="do_not_use_static_html" checked>
					<label for="do_not_use_static_html">Do Not Use Static HTML</label>
				</li>
				<li>
					<input type="checkbox" name="minify_scripts" id="minify_scripts" checked>
					<label for="minify_scripts">Minify Scripts</label>
				</li>
				<li>
					<input type="checkbox" name="minify_modules" id="minify_modules" checked>
					<label for="minify_modules">Minify Modules</label>
				</li>
				<li>
					<input type="checkbox" name="update_site_webmanifest" id="update_site_webmanifest" checked>
					<label for="update_site_webmanifest">Update <code>site.webmanifest</code></label>
				</li>
				<li>
					<input type="checkbox" name="update_browserconfig_xml" id="update_browserconfig_xml" checked>
					<label for="update_browserconfig_xml">Update <code>browserconfig.xml</code></label>
				</li>
				<li>
					<input type="checkbox" name="update_fontface_css" id="update_fontface_css" checked>
					<label for="update_fontface_css">Update <code>fontface.css</code></label>
				</li>
				<li>
					<input type="checkbox" name="compile_css" id="compile_css" checked>
					<label for="compile_css">Compile CSS</label>
				</li>
				<li>
					<input type="checkbox" name="update_individual_imports_css" id="update_individual_imports_css" checked>
					<label for="update_individual_imports_css">Update <code>individual-imports.css</code></label>
				</li>
				<li>
					<input type="checkbox" name="update_service_worker" id="update_service_worker" checked>
					<label for="update_service_worker">Update Service Worker</label>
				</li>
				<li>
					<input type="checkbox" name="minify_service_worker" id="minify_service_worker" checked>
					<label for="minify_service_worker">Minify <code>sw.js</code></label>
				</li>
				<li>
					<input type="checkbox" name="compile_htaccess" id="compile_htaccess" checked>
					<label for="compile_htaccess">Update <code>.htaccess</code></label>
				</li>
				<li>
					<input type="checkbox" name="update_date_modified" id="update_date_modified" checked>
					<label for="update_date_modified">Update <code>dateModified.txt</code></label>
				</li>
			</ul>
		</fieldset>
		<div class="submit-button info-text padding-top-1em">
			<button type=submit>Submit</button>
		</div>
		<div class=upload-status></div>
	</form>

<?php
include $absolute_root . 'includes/components/breadcrumb_schema.php';
?>
</main>
<?php
include $absolute_root . 'includes/components/etc/main.ending.inc.php';
?>