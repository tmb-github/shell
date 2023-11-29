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
	<h1 id=main-content>Your message has been sent!</h1>
	<p class="font-size-125rem font-style-italic">Thanks for writing to us. You’ll hear back from us soon. In the meantime, please return to the <a href=".">Home Page</a> of the site and continue browsing!</p>
<?php
include $absolute_root . 'includes/components/breadcrumb_schema.php';
?>
</main>
<?php
include $absolute_root . 'includes/components/etc/main.ending.inc.php';
?>