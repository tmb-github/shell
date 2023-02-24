<?php 

echo 'Minifying loader.js . . .' . PHP_EOL;

$absolute_root = $_SERVER['ABSOLUTE_ROOT'];
$source = $absolute_root . 'javascript/loader.js';
$destination = $absolute_root . 'javascript/loader.min.js';

if (file_exists($source)) {
	minify_with_closure_compiler($source, $destination);
	if ((filesize($source) == filesize($destination)) && (md5_file($source) == md5_file($destination))) {
		echo PHP_EOL;
		echo 'ERROR: Closure Compiler minification failed: ' . $source . PHP_EOL;
		echo PHP_EOL;
	} else {
		echo 'SUCCESS: Closure Compiler minified: ' . $source . PHP_EOL;
	}
} else {
	echo $source . ' does not exist...procedure aborted.' . PHP_EOL . PHP_EOL;
}

///////

echo 'Minifying loader.js . . .' . PHP_EOL;

$absolute_root = $_SERVER['ABSOLUTE_ROOT'];

if (!file_exists($absolute_root . 'javascript/minified-scripts')) {
// NB: 0777 is security permission: make executable
	mkdir($absolute_root . 'javascript/minified-scripts', 0777);
} else {
	$files = glob('javascript/minified-scripts/*'); // get all file names
	foreach($files as $file){ // iterate files
	if(is_file($file))
		unlink($file); // delete file
	}
}

$source = $absolute_root . 'javascript/scripts/loader.js';
$destination = $absolute_root . 'javascript/minified-scripts/loader.min.js';

if (file_exists($source)) {

// Get contents of current file:
	$contents = file_get_contents($source);

// Get attribute comment at beginning of file:
	$comment_start = strpos($contents, '/*');
	$comment_end = strpos($contents, '*/');
	$comment = substr($contents, $comment_start, (($comment_end + 2) - $comment_start));
// replace crlf with space character:
	$comment = str_replace(PHP_EOL, " ", $comment);
// consolidate consecutive white space characters into single white space character:
	$comment = preg_replace("/\s+/", ' ', $comment);

// Safeguard against the closure compiler barking at us!

// replace tabs with 4 spaces:
	$find = array("\t");
	$replace = array('    ');
	$contents = str_replace($find, $replace, $contents);

// strip out all JavaScript comments:
	$pattern = '/(?:(?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:(?<!\:|\\\|\'|\")\/\/.*))/';
	$contents = preg_replace($pattern, '', $contents);

// Safeguard against accidental double .mins (DO IT FIRST HERE):
	$contents = str_replace(".min.min.", ".min.", $contents);

// Get an autoversioning value corresponding to the current time:
	$url = 'dummy.txt';
	file_put_contents($url, 'abcdefghijklmnopqrstuvwxyz');
	if (file_exists($url)) {
		$date = date("YmdHis", filemtime($url));
	} else {
		$date = '19990221125549';
	}
	unlink($url);

// The mjs files we're processing reference other modules in their unminified
// state, so we need to splice in .min before .mjs in each place they occur.
// Also, we should splice in the $date number to autoversion the modules:

// Replace .mjs with .min.{{$date}}.mjs in all of the import() functions:
	$contents = str_replace(".mjs')", ".min." . $date . ".mjs')", $contents);
	$contents = str_replace('.mjs")', '.min.' . $date . '.mjs")', $contents);

// Now do the same for the non-module JS resources:
// Replace .min.js with .min.{{$date}}.js in enqueue(), promiseLoader(), and elsewhere:
	$contents = str_replace(".min.js'", ".min." . $date . ".js'", $contents);
	$contents = str_replace('.min.js"', '.min.' . $date . '.js"', $contents);

// Safeguard against accidental double .mins (DO IT AGAIN HERE):
	$contents = str_replace(".min.min.", ".min.", $contents);

// Finally, replace each instance of "import('./" with "import('./minified-modules/":
// NB: Within the JavaScript itself, minified-modules is a child folder. At the 
// top of this routine, we have to spell out javascript/minified-modules for the files
// to be written to the right place:
		$contents = str_replace("import('./", "import('./minified-modules/", $contents);
		$contents = str_replace('import("./', 'import("./minified-modules/', $contents);

// 2022-08-20:
// Do the same for enqueued scripts:
// THIS APPARENTLY HAS NO EFFECT...REASON UNKNOWN.
// ACTUAL SOLUTION IN minify_scripts_js.php...relative path to the loader file
// was failing; absolute path was needed.
		$contents = str_replace("({src: 'javascript/scripts/", "({src: 'javascript/minified-scripts/", $contents);
		$contents = str_replace('({src: "javascript/scripts/', '({src: "javascript/minified-scripts/', $contents);


// Safeguard against double minified-modules:
		$contents = str_replace("minified-modules/minified-modules/", "minified-modules/", $contents);

// Temporarily rename all dynamic "import()" functions as "dyanamicImport()"
// If we don't, the closure compiler fails to compile the code:
	$contents = str_replace("import(", "dynamicImport(", $contents);

// Consolidate consecutive crlfs into a single crlf:
	$contents = preg_replace("/[\r\n]+/", "\r\n", $contents);

// Name a file where our revised contents may be written:
	$temp_file = 'closureCompilerTemp.js';
	if (file_exists($temp_file)) {
		unlink($temp_file);
	}
	file_put_contents($temp_file, $contents);

// Now, finally, we can compile the revised contents with the closure compiler:
// The function now (2020-03-28) uses the locally hosted version of the closure compiler
// via shell_exec() when it detects that we're on localhost:
	minify_with_closure_compiler($temp_file, $destination);

// Get the code from the result of the compilation:
	$destination_text = file_get_contents($destination);

// Finally, replace each instance of "dynamicImport(" with "import(":
	$destination_text_replaced = str_replace("dynamicImport(", "import(", $destination_text);

// Restore the comment:
	$destination_text_replaced = $comment . PHP_EOL . $destination_text_replaced;

// Then write the revised text to the destination file:
	file_put_contents($destination, $destination_text_replaced);

	if ((filesize($temp_file) == filesize($destination)) && (md5_file($temp_file) == md5_file($destination))) {
		echo PHP_EOL;
		echo 'ERROR: Closure Compiler minification failed: ' . $source . PHP_EOL;
		echo PHP_EOL;
	} else {
		echo 'SUCCESS: Closure Compiler minified: ' . $source . PHP_EOL;
	}

	unlink($temp_file);

} else {
	echo $source . ' does not exist...procedure aborted.' . PHP_EOL . PHP_EOL;
}
