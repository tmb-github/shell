<?php

include 'compile_reqs.inc.php';

if ($minify_scripts == true) {

	$html = '';

// NB: jQuery.fancybox.min.js and jQuery-3.4.1.min.js are ALREADY MINIFIED
// and are in the 'minified-scripts' folder. There is no unminified version
// of either file in the 'scripts' folder, so no unnecessary minification
// will take place.
// NB: If you don't want to re-minify a script that needs no additional 
// work, then remove its unminified form from the 'scripts' folder and
// put it in the 'archive' folder.

//$absolute_root = $_SERVER['ABSOLUTE_ROOT'];

///////////////////////////////
// ONLY COMPILE ON LOCALHOST //
///////////////////////////////

	if ($localhost === true) {

		$folderPath = $absolute_root . $assets_folder . 'javascript/scripts';
// getAllSubfolders() is common function in functions.php
		$subfolders = getAllSubfolders($folderPath);

		foreach (($subfolders) as $subfolder) {
			$full_subfolder_path = $absolute_root . $assets_folder . 'javascript/minified-scripts/' . $subfolder;
			if (!file_exists($full_subfolder_path)) {
// NB: 0777 is security permission: make executable
				mkdir($full_subfolder_path, 0777);
			} else {
				$files = glob($assets_folder . 'javascript/minified-scripts/' . $subfolder . '/*'); // get all file names
				foreach($files as $file){ // iterate files
				if(is_file($file))
					unlink($file); // delete file
				}
			}
		}

		$js = [];
		$min_js = [];

		foreach (($subfolders) as $subfolder) {
			$full_asset_path = $absolute_root . $assets_folder . 'javascript/scripts/' . $subfolder . '/*.js';
			foreach (glob($full_asset_path) as $filename) {
				if (!endsWith($filename, '.min.js')) {
					array_push($js, $filename);
					$min = str_replace('.js', '.min.js', $filename);
					$min = str_replace('javascript/scripts/', 'javascript/minified-scripts/', $min);
					array_push($min_js, $min);
				}
			}
		}

// $js     is array of all of the .js files 
// $min_js is array of all of the .js files with the extension changed to .min.js
// ...and with the destination directory set to $assets_folder . 'javascript/minified-scripts/'

// The getAllSubfolders() routine results in perfectly serviceable addresses
// but at the same time unsightly /./ sequences in them. So, before going ahead:
//
// Convert: C:/xampp/htdocs/shell/assets/javascript/minified-scripts/./appendToCssClosure.min.js',
// To:      C:/xampp/htdocs/shell/assets/javascript/minified-scripts/appendToCssClosure.min.js',

		foreach ($js as &$javascript) {
			$javascript = str_replace('/./', '/', $javascript);
		}

		foreach ($min_js as &$min_javascript) {
			$min_javascript = str_replace('/./', '/', $min_javascript);
		}


		for ($i = 0; $i < count($js); $i++) {

			$source = $js[$i];
			$destination = $min_js[$i];

// 2021-03-08:
// This caused the routine on localhost on MAC to complain, reason unknown:
			if (file_exists($destination)) {
				unlink($destination);
			}

			if (file_exists($source)) {

// Get contents of current mjs file:
				$contents = file_get_contents($source);

// Get attribute comment at beginning of file:
				$comment_start = strpos($contents, "/*");
				$comment_end = strpos($contents, "*/");
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

// Get an autoversioning value corresponding to the current time:
				$date = date('YmdHis', time());

// If the js files we're processing reference modules in their unminified
// state, we need to splice in .min before .mjs in each place they occur.
// Also, we should splice in the $date number to autoversion the modules:

// ACHTUNG: https://www.google.com/recaptcha/api.js
// also: https://www.google-analytics.com/analytics.js

// Convert special cases to immunize them from the splicing to come:
				$contents = str_replace("https://www.google.com/recaptcha/api.js", "httpswwwgooglecomrecaptchaapijs", $contents);
				$contents = str_replace("https://www.google-analytics.com/analytics.js", "httpswwwgoogleanalyticscomanalyticsjs", $contents);

// Replace .mjs with .min.{{$date}}.mjs in all of the import() functions:
				$contents = str_replace(".mjs')", ".min." . $date . ".mjs')", $contents);
				$contents = str_replace('.mjs")', '.min.' . $date . '.mjs")', $contents);

// Replace .js with .min.{{$date}}.js where there's an ending quote:
				$contents = str_replace(".js'", ".min." . $date . ".js'", $contents);
				$contents = str_replace('.js"', '.min.' . $date . '.js"', $contents);

// Replace .min.js with .min.{{$date}}.js elsewhere:
				$contents = str_replace(".min.js'", ".min." . $date . ".js'", $contents);
				$contents = str_replace('.min.js"', '.min.' . $date . '.js"', $contents);

// Replace /scripts/ with /minified-scripts/ in the loader:
				if ($source == $absolute_root . $assets_folder . 'javascript/scripts/loader.js') {
					$contents = str_replace("javascript/scripts", "javascript/minified-scripts", $contents);
				}
// Safeguard against double .mins:
				$contents = str_replace(".min.min.", ".min.", $contents);

				$contents = str_replace("['javascript/scripts", "['javascript/minified-scripts", $contents);
				$contents = str_replace('["javascript/scripts', '["javascript/minified-scripts', $contents);

// Finally, replace each instance of "import('./" with "import('./minified-modules/":
// NB: Within the JavaScript itself, minified-modules is a child folder. At the 
// top of this routine, we have to spell out $assets_folder . javascript/minified-modules for the files
// to be written to the right place:
				$contents = str_replace("import('../modules/", "import('../minified-modules/", $contents);
				$contents = str_replace('import("../modules/', 'import("../minified-modules/', $contents);

// Safeguard against double minified-modules:
				$contents = str_replace("minified-modules/minified-modules/", "minified-modules/", $contents);

// Revert special cases to former form:
				$contents = str_replace("httpswwwgooglecomrecaptchaapijs", "https://www.google.com/recaptcha/api.js", $contents);
				$contents = str_replace("httpswwwgoogleanalyticscomanalyticsjs", "https://www.google-analytics.com/analytics.js", $contents);

// Temporarily rename all dynamic "import()" functions as "dyanamicImport()"
// If we don't, the closure compiler fails to compile the code:
				$contents = str_replace("import(", "dynamicImport(", $contents);

// Consolidate consecutive crlfs into a single crlf:
				$contents = preg_replace("/[\r\n]+/", "\r\n", $contents);

// Name a file where our revised contents may be written:
				$temp_file = 'closureCompilerTemp.js';
				file_put_contents($temp_file, $contents);

// Now, finally, we can compile the revised contents with the closure compiler:
// The function now (2020-03-28) uses the locally hosted version of the closure compiler
// via shell_exec() when it detects that we're on localhost:

				minify_with_closure_compiler($temp_file, $destination);

// ^ The above is the same as this on localhost:
// $cc_cmd = "java -jar /xampp/htdocs/closure-compiler/closure-compiler-v20200315.jar --js=" . $temp_file . " --js_output_file=" . $destination . " --compilation_level=SIMPLE --rewrite_polyfills=false";
// shell_exec($cc_cmd);

// Get the code from the result of the compilation:
				$destination_text = file_get_contents($destination);

// Finally, replace each instance of "dynamicImport(" with "import(":
				$destination_text = str_replace("dynamicImport(", "import(", $destination_text);

// Restore the comment:
				$destination_text = $comment . PHP_EOL . $destination_text;

// Then write the revised text to the destination file:
				file_put_contents($destination, $destination_text);

				if ((filesize($temp_file) == filesize($destination)) && (md5_file($temp_file) == md5_file($destination))) {
					$html .= '<br>';
					$html .= 'ERROR: Closure Compiler minification failed: ' . $source . '<br>';
					$html .= '<br>';
				} else {
					$html .= 'SUCCESS: Closure Compiler minified: ' . $source . '<br>';
				}
				unlink($temp_file);
			} else {
				$html .= $source . ' does not exist . . . procedure aborted.' . '<br><br>';
			}
		}
	} else {
		$html .= '<br>';
		$html .= '<strong>Scripts are not minified on live site.</strong><br>';
		$html .= '<strong>Compile scripts on localhost, then upload them to the live site.</strong><br>';
		$html .= '<strong>Then and only then compile the live site.</strong><br>';
		$html .= '<br>';
	}

	$status_ok = true;
	if ($status_ok) {
		$message = $html . 'Done.';
		echo '{"status": "ok", "message": "' . $message . '"}';
	} else {
// send a 422 Unprocessable Entity header, echo the JSON, and exit:
		http_response_code(422);
		$message = $html . 'Failed.';
		echo json_encode(array("status" => "error", "message" => "$message"));
		exit;
	}
} else {
	$message = 'Not Selected';
	echo '{"status": "ok", "message": "' . $message . '"}';
}