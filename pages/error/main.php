<?php

$absolute_root = $_SERVER['ABSOLUTE_ROOT'];

include_once 'index-main-vars.php';
include_once $absolute_root . 'includes/common/routines.php';

ob_start();

$main_classes = $page . " main custom-style-elements";

// Check if the variable is defined, not null, and not empty
if (!isset($redirect) || empty($redirect)) {
	$redirect = 400;
}

?>

<main class="<?php echo $main_classes; ?>" data-page="<?php echo $page; ?>" data-title="<?php echo $title; ?>" data-http-status=<?php echo $redirect; ?>>

<?php

render_custom_style_elements();

// default:
$error = "";
$message = "";

if ($redirect == 200) {
	$error = "Error Page";
	$message = "You've reached the error page without causing an error. Well done!";
}
if ($redirect == 400) {
	$error = "400: Bad Request";
	$message = "The server couldn't understand your request.";
}
if ($redirect == 403) {
	$error = "403: Forbidden";
	$message = "You don't have permission to access that resource.";
}
if ($redirect == 404) {
	$error = "404: Not Found";
	$message = "The page you've specified doesn't exist.";
}
if ($redirect == 500) {
	$error = "500: Internal Server Error";
	$message = "An internal error has flummoxed the server.";
}
?>
	<h1 id=main-content class=error-heading tabindex=0><?php echo $error; ?></h1>
	<p class="error-message text-align-center"><?php echo $message; ?></p>
<?php
include $absolute_root . 'includes/components/breadcrumb_schema.php';
?>
</main>
<?php
include $absolute_root . 'includes/components/etc/main.ending.inc.php';
