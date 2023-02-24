<?php

$absolute_root = $_SERVER['ABSOLUTE_ROOT'];

include_once 'index-main-vars.php';
include_once $absolute_root . 'includes/common_routines.php';

ob_start();

$oops = false;

if (isset($_POST['submit'])) {
/* Define username and associated password array */
	$logins = array('admin' => 'abc123');

/* Check and assign submitted Username and Password to new variable */
	$username = isset($_POST['username']) ? $_POST['username'] : '';
	$password = isset($_POST['password']) ? $_POST['password'] : '';

/* Check Username and Password existence in defined array */
	if (isset($logins[$username]) && $logins[$username] == $password) {
/* Success: Set session variables and redirect to Protected page  */
		$_SESSION['authenticated'] = 'true';
/*
// OLD:
// Update works_list.inc.php in all-works directory:
		$target_directory = $absolute_root . 'works/';
		$files = glob($target_directory . '*.inc.php'); // get all file names
		$file_contents = '<?php' . PHP_EOL;
		foreach ($files as $file) { // iterate files
			if (is_file($file)) {
				$file_contents .= "include '" . $file . "';" . PHP_EOL;
			}
		}
*/
// Update works_list.inc.php in all-works directory to include the newly uploaded work:
		$target_directory = $absolute_root . 'works/';
		$files = glob($target_directory . '*.inc.php'); // get all file names
		$file_contents = '<?php' . PHP_EOL;
		$file_contents .= PHP_EOL;
		$file_contents .= '$absolute_root = $_SERVER[\'DOCUMENT_ROOT\'] . (($_SERVER[\'SERVER_NAME\'] == \'localhost\') ? \'/' . $site_title_short_form_lc . '/\' : \'/\');' . PHP_EOL;
		$file_contents .= PHP_EOL;

		$absolute_root_len = strlen($absolute_root);

		foreach ($files as $file) { // iterate files
			if (is_file($file)) {
// strip $absolute_root text from beginning of $file:
				$file_contents .= 'include $absolute_root . \'' . substr($file, $absolute_root_len) . '\';' . PHP_EOL;
			}
		}

// Update gallery version of all-works:
		file_put_contents($absolute_root . 'all-works/works_list.inc.php', $file_contents);

// Update theme version of all-works:
		file_put_contents($absolute_root . 'theme/all-works/works_list.inc.php', $file_contents);


		header("Location: ../");
		exit;
	} else {
/*Unsuccessful attempt: Set error message */
		// OOPS!
		$oops = true;
	}
}

$main_classes = $page . " main custom-style-elements";

?>

<main class="<?php echo $main_classes; ?>" data-page="<?php echo $page; ?>" data-title="<?php echo $title; ?>">
<?php
render_custom_style_elements('login');
?>

	<h1 id=main-content>Login</h1>
	<form id=artwork-upload-form class=outer-container-to-center method=post enctype=multipart/form-data>
		<div class="inner-container-to-center input-grid">

			<label for=username-input>Username:</label>
			<input id=username-input type=text name=username autocomplete=off required autofocus>

			<label for=password-input>Password:</label>
			<input id=password-input type=password name=password autocomplete=off required>
<?php /* 
No LABEL is needed for an INPUT of type=submit. However, to maintain the
styling/indentation of the page elements, a dummy element is needed here,
hence the X-LABEL element:
*/ ?>
			<x-label for=submit-button></x-label>
			<input id=submit-button type=submit name=submit value="Login">
		</div>
<?php
if ($oops) {
?>
		<h3 class=text-align-center>Oops! That username/password combination didn't work. Try again.</h3>
<?php
}
?>	</form>

<?php
include $absolute_root . 'includes/html/common/main-breadcrumb-schema.inc.php';
?>
</main>
<?php
include $absolute_root . 'includes/html/common/main-ending.inc.php';
?>