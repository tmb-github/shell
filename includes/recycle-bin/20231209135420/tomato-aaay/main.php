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
	<h1 id=main-content tabindex=0>tomato aaay</h1>
	<section class=font-samples>
		<p class="roman">The quick brown fox jumped over the lazy dogs.</p>
		<p class="italic">The quick brown fox jumped over the lazy dogs.</p>
		<p class="bold">The quick brown fox jumped over the lazy dogs.</p>
		<p class="bold-italic">The quick brown fox jumped over the lazy dogs.</p>
	</section>

<?php
include $absolute_root . 'includes/components/breadcrumb_schema.php';
?>
</main>
<?php
include $absolute_root . 'includes/components/etc/main.ending.inc.php';