<?php

$absolute_root = $_SERVER['ABSOLUTE_ROOT'];

include_once 'index-main-vars.php';
include_once $absolute_root . 'includes/common/routines.php';

ob_start();

$main_classes = $page . " main custom-style-elements";

?>

<main class="<?php echo $main_classes; ?>" data-page="<?php echo $page; ?>" data-title="<?php echo $title; ?>">
<?php

render_custom_style_elements();

?>
	<h1 id=main-content tabindex=0>Maintenance Mode</h1>
	<p class="text-align-center toggle-text">Off&nbsp;
		<label class="switch" for=maintenance-mode-input>
			<span class=screen-reader>Toggle Widget</span>
			<input type="checkbox" id=maintenance-mode-input>
			<span class="slider round"></span>
		</label>
	&nbsp;On</p>
	<div class=process-result></div>


<?php
include $absolute_root . 'includes/components/breadcrumb_schema.php';
?>
</main>
<?php
include $absolute_root . 'includes/components/etc/main.ending.inc.php';