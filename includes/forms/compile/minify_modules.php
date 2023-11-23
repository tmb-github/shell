<?php

include 'compile_reqs.inc.php';

if ($minify_modules == true) {
	$html = '';

// TOGGLE ON AND OFF:
	$convert_template_strings = true;

	function escapeNonHTML($matches) {
		return $matches[1] . str_replace("'", "\'", $matches[2]) . $matches[3];
	}

	function revise($templateString) {
		$escapedString = preg_replace_callback('/(`.*?`)/s', function($matches) {
			$templateString = preg_replace('/\R\s*/', '', $matches[0]);
			$templateString = str_replace('`', '\'', $templateString);
			return preg_replace_callback('/(<[^>]*>)([^<>]*)(<\/[^>]*>)/', 'escapeNonHTML', $templateString);
		}, $templateString);
		$escapedString = trim($escapedString, "'");
		$escapedString = str_replace("\'", "ROALDDAHLP!ENGUIN", $escapedString);
		$escapedString = str_replace("'", "\'", $escapedString);
		$escapedString = str_replace("ROALDDAHLP!ENGUIN", "\'", $escapedString);
		return "'" . $escapedString . "'";
	}

///////////////////////////////
// ONLY COMPILE ON LOCALHOST //
///////////////////////////////

	if ($localhost === true) {

		$folderPath = $absolute_root . 'assets/javascript/modules';

// getAllSubfolders() is common function in functions.php
		$subfolders = getAllSubfolders($folderPath);

		foreach (($subfolders) as $subfolder) {
			$full_subfolder_path = $absolute_root . 'assets/javascript/minified-modules/' . $subfolder;
			if (!file_exists($full_subfolder_path)) {
// NB: 0777 is security permission: make executable
				mkdir($full_subfolder_path, 0777);
			} else {
				$files = glob('assets/javascript/minified-modules/' . $subfolder . '/*'); // get all file names
				foreach($files as $file){ // iterate files
				if(is_file($file))
					unlink($file); // delete file
				}
			}
		}

		$mjs = [];
		$min_mjs = [];

		foreach (($subfolders) as $subfolder) {
			$full_asset_path = $absolute_root . 'assets/javascript/modules/' . $subfolder . '/*.mjs';
			foreach (glob($full_asset_path) as $filename) {
				if (!endsWith($filename, '.min.mjs')) {
					array_push($mjs, $filename);
					$min = str_replace('.mjs', '.min.mjs', $filename);
					$min = str_replace('assets/javascript/modules/', 'assets/javascript/minified-modules/', $min);
					array_push($min_mjs, $min);
				}
			}
		}

// $mjs     is array of all of the .mjs module files 
// $min_mjs is array of all of the .mjs module files with the extension changed to .min.mjs
// ...and with the destination directory set to assets/javascript/minified-modules/'

		for ($i = 0; $i < count($mjs); $i++) {

			$source = $mjs[$i];
			$destination = $min_mjs[$i];

// 2023-11-01
// replace "/./" with "/" in source and destination:
//	$source = str_replace("/./", "/", $source);
//	$destination = str_replace("/./", "/", $source);

			if (file_exists($source)) {

// Get contents of current mjs file:
				$contents = file_get_contents($source);

// Safeguard against the closure compiler barking at us!

// replace tabs with 4 spaces:
				$find = array("\t");
				$replace = array('    ');
				$contents = str_replace($find, $replace, $contents);

// strip out all JavaScript comments:
				$pattern = '/(?:(?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:(?<!\:|\\\|\'|\")\/\/.*))/';
				$contents = preg_replace($pattern, '', $contents);

// Get an autoversioning value corresponding to the current time:
				$url = 'dummy.txt';
				file_put_contents($url, 'abcdefghijklmnopqrstuvwxyz');
				if (file_exists($url)) {
					$date = date("YmdHis", filemtime($url));
				} else {
					$date = '19990221125549';
				}
				unlink($url);

// Convert special cases to immunize them from the splicing to come:
				$contents = str_replace("https://www.google.com/recaptcha/api.js", "httpswwwgooglecomrecaptchaapijs", $contents);
				$contents = str_replace("https://www.google-analytics.com/analytics.js", "httpswwwgoogleanalyticscomanalyticsjs", $contents);

// The mjs files we're processing reference other modules in their unminified
// state, so we need to splice in .min before .mjs in each place they occur.
// Also, we should splice in the $date number to autoversion the modules:

// Replace .mjs with .min.{{$date}}.mjs where there's an ending quote:
				$contents = str_replace(".mjs'", ".min." . $date . ".mjs'", $contents);
				$contents = str_replace('.mjs"', '.min.' . $date . '.mjs"', $contents);

// Replace .js with .min.{{$date}}.js where there's an ending quote:
				$contents = str_replace(".js'", ".min." . $date . ".js'", $contents);
				$contents = str_replace('.js"', '.min.' . $date . '.js"', $contents);

// Replace .min.js with .min.{{$date}}.js elsewhere:
				$contents = str_replace(".min.js'", ".min." . $date . ".js'", $contents);
				$contents = str_replace('.min.js"', '.min.' . $date . '.js"', $contents);

// Safeguard against double .mins:
				$contents = str_replace(".min.min.", ".min.", $contents);

				$contents = str_replace("assets/javascript/scripts", "assets/javascript/minified-scripts", $contents);
				$contents = str_replace('assets/javascript/scripts', 'assets/javascript/minified-scripts', $contents);

// Revert special cases to former form:
				$contents = str_replace("httpswwwgooglecomrecaptchaapijs", "https://www.google.com/recaptcha/api.js", $contents);
				$contents = str_replace("httpswwwgoogleanalyticscomanalyticsjs", "https://www.google-analytics.com/analytics.js", $contents);

// Temporarily rename all dynamic "import()" functions as "dyanamicImport()"
// If we don't, the closure compiler fails to compile the code:
				$contents = str_replace("import(", "dynamicImport(", $contents);

// We have to strip out the export at the end of each module before compiling
// and then restore it at the end. It's the only way...

////////////////////
// STATIC IMPORTS //
////////////////////

				$found_static_imports = false;

/////////////////////////////////////////
// See if Object Freeze import is in use:
/////////////////////////////////////////

// Here's the start of my object freeze import signature:
				$start = 'import {';
				$end = ';';
				$start_len = strlen($start);
				$end_len = strlen($end);

				$search_start_pos = 0;
				$import_counts = substr_count($contents, $start);

				$replace_str = '';

				$import_var_arr = [];
				$import_stmt_arr = [];

				for ($x = 0; $x < $import_counts; $x++) {

// Determine the position of the $start and $end in the $contents:
					$str_pos_start = strpos($contents, $start, $search_start_pos);
					$str_pos_end = strpos($contents, $end, $str_pos_start + $search_start_pos) + $end_len;
// Only proceed if we've found both the start and end of the import object code:
					if (($str_pos_start !== false) && ($str_pos_end !== false)) {
						$found_static_imports = true;
					}

					if ($found_static_imports == true) {

// Save the complete import statement in a variable:
						$import = substr($contents, $str_pos_start, ($str_pos_end - $str_pos_start));
// Now, get just the list of variables in the import statement:
						$bracket_start = strpos($import, '{');
						$bracket_end = strpos($import, '}');

						$var_list = substr($import, ($bracket_start + 1), ($bracket_end - $bracket_start - 1));
						$import_end = substr($import, ($bracket_end + 1));

// Reconstruction of the complete import statement:
						$tmp = trim('import {' . trim($var_list) . '} ' . $import_end);
// Now, strip all duplicate white-space characters from the import :
						$original_import_edited = preg_replace('/\s+/S', " ", $tmp);

						$import_stmt_arr[$x] = $original_import_edited;

// From the variable list in the import, remove all white space characters, including tabs and end of line characters:
						$var_list = preg_replace('/\s+/', '', $var_list);
// Add a white space after every comma:
						$var_list = str_replace(',', ', ', $var_list);
// Convert the variable list into an array:
						$str_arr = explode(", ", $var_list);

						for ($y = 0; $y < count($str_arr); $y++) {
							$replace_str .= 'var ' . $str_arr[$y] . ';' . PHP_EOL;
							$import_var_arr[$x][$y] = $str_arr[$y];
						}
					}

					$contents = str_replace($import, $replace_str, $contents);
					$replace_str = '';

				}

////////////
// START: //
////////////

// NB: IF THESE CAN'T BE FOUND, SOMETHING IS WRONG WITH THE INPUT MJS FILE, AND IT MUST BE SKIPPED:

				$found_method = false;

/////////////////////////////////////////
// See if Object Freeze export is in use:
/////////////////////////////////////////

				if ($found_method == false) {
// Here's the start of my object freeze export signature:
					$start = 'export default Object.freeze({';
					$end = '});';

					$start_len = strlen($start);
					$end_len = strlen($end);

// Determine the position of the $start and $end in the $contents:
					$str_pos_start = strpos($contents, $start);
					$str_pos_end = strpos($contents, $end, $str_pos_start) + $end_len;

// Only proceed if we've found both the start and end of the export object code:
					if (($str_pos_start !== false) && ($str_pos_end !== false)) {
						$found_method = true;
					}
				}

////////////////////////////////////
// See if standard export is in use:
////////////////////////////////////

				if ($found_method == false) {
// Here's the start of my standard export signature:
					$start = 'export {';
					$end = '};';

					$start_len = strlen($start);
					$end_len = strlen($end);

// Determine the position of the $start and $end in the $contents:
					$str_pos_start = strpos($contents, $start);
					$str_pos_end = strpos($contents, $end, $str_pos_start) + $end_len;

// Only proceed if we've found both the start and end of the export object code:
					if (($str_pos_start !== false) && ($str_pos_end !== false)) {
						$found_method = true;
					}
				}

////////////////////////////////
// If either is in use, proceed:
////////////////////////////////
				if ($found_method == true) {
// Save the complete export statement in a variable:
					$export = substr($contents, $str_pos_start, ($str_pos_end - $str_pos_start));
// Now, get just the list of variables in the export statement:
					$var_list = substr($contents, ($str_pos_start + $start_len), (($str_pos_end - $end_len) - ($str_pos_start + $start_len)));
// Reconstruction of the complete export statement:
					$original_export = $start . trim($var_list) . $end . PHP_EOL;
// Now, strip all duplicate white-space characters from the export :
					$original_export_edited = preg_replace('/\s+/S', " ", $original_export);
// From the variable list in the export, remove all white space characters, including tabs and end of line characters:
					$var_list = preg_replace('/\s+/', '', $var_list);
// Add a white space after every comma:
					$var_list = str_replace(',', ', ', $var_list);
// Convert the variable list into an array:
					$str_arr = explode(", ", $var_list);


// Regarding how to preserve variable names in a compiled module, see:
// https://developers.google.com/closure/compiler/docs/api-tutorial3
//
// Now construct the listing of variables in the form:
//
// window['myVariableOne'] = myVariableOne;
// window['myVariableTwo'] = myVariableTwo;
// window['myVariableThree'] = myVariableThree;
//
// This is what we'll put in place of the original export statement so that the 
// closure compiler preserves all of our variables.
					$replacement = '';
					foreach($str_arr as &$value) {
						$replacement .= "window['" . $value . "'] = " . $value . ";" . PHP_EOL;
					}

// From the same data, we can predict in what form that very block will be compiled:
//
// window.myVariableOne=myVariableOne;
// window.myVariableTwo=myVariableTwo;
// window.myVariableThree=myVariableThree;
//
// But we only need the first and last of these to be able to find the complete block
// of them in the compiled code:

					$first_replace = 'window.' . $str_arr[0] . '=' . $str_arr[0] . ';';
					$last_index = count($str_arr) - 1;
					$last_replace = 'window.' . $str_arr[$last_index] . '=' . $str_arr[$last_index] . ';';

// Now, in the contents, replace the export block with the replacement block:
					$contents = str_replace($export, $replacement, $contents);

// Consolidate consecutive crlfs into a single crlf:
					$contents = preg_replace("/[\r\n]+/", "\r\n", $contents);

////////////////////////////////////////////////////
// Convert template strings to single quote strings:
////////////////////////////////////////////////////

// THIS NEEDS ${x} SEARCH AND REPLACE ADDED TO IT:

					if ($convert_template_strings == true) {

						$backtick_count = substr_count($contents, '`');

						if (($backtick_count % 2) == 0) {

							$offset = 0;
							$backtick_pos_array = [];

							for ($x = 0; $x < $backtick_count; $x++) {
								$offset = strpos($contents, '`', $offset);
								array_push($backtick_pos_array, $offset);
								$offset += 1;
							}

							$template_str = [];
							$normal_str = [];
							for ($x = 0; $x < $backtick_count; $x++) {
								$start = $x;
								$end = $start + 1;
								$length = ($backtick_pos_array[$end] - $backtick_pos_array[$start]) + 1;

								$template = substr($contents, $backtick_pos_array[$start], $length);
								$revise_template = revise($template);

								array_push($template_str, $template);
								array_push($normal_str, $revise_template);
								$x += 1;
							}

							for ($x = 0; $x < count($template_str); $x++) {
								$contents = str_replace($template_str[$x], $normal_str[$x], $contents);
							}

						} else {
							$html .= '<br>';
							$html .= 'Number of backticks is not even - could not convert template strings to standard strings.<br>';
							$html .= '<br>';
						}
					}
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

// ^ The above is the same as this on localhost:
// $cc_cmd = "java -jar /xampp/htdocs/closure-compiler/closure-compiler-v20200315.jar --js=" . $temp_file . " --js_output_file=" . $destination . " --compilation_level=SIMPLE --rewrite_polyfills=false";
// shell_exec($cc_cmd);

// Get the code from the result of the compilation:
					$destination_text = file_get_contents($destination);

// Now find the start and end of the window.myVariableOne=myVariableOne; etc. block:
					$end_block_start = strpos($destination_text, $first_replace);
					$end_block_end = strpos($destination_text, $last_replace, $end_block_start) + strlen($last_replace);

// Now we can find the end block:
					$end_block = substr($destination_text, $end_block_start, ($end_block_end - $end_block_start));

// replace the end block with the edited, original export block:
					$destination_text_replaced = str_replace($end_block, $original_export_edited, $destination_text);

// Finally, replace each instance of "dynamicImport(" with "import(":
					$destination_text_replaced = str_replace("dynamicImport(", "import(", $destination_text_replaced);

//////////////////////////////
// Restore standard imports //
//////////////////////////////

					if (!empty($import_stmt_arr)) {
						if (!empty($import_var_arr)) {
							for ($x = 0; $x < count($import_stmt_arr); $x++) {
							$var_decs_orig = substr($destination_text_replaced, 0, strpos($destination_text_replaced, ';') + 1);
							$var_decs = $var_decs_orig;
								for ($y = 0; $y < count($import_var_arr[$x]); $y++) {
									$var = $import_var_arr[$x][$y];
// followed by a comma:
									$var_decs = str_replace($var . ',', '', $var_decs);
// not followed by a comma:
									$var_decs = str_replace($var, '', $var_decs);
								}
								$var_decs .= $import_stmt_arr[$x];
								$destination_text_replaced = str_replace($var_decs_orig, $var_decs, $destination_text_replaced);
							}
						}
					}

// In case we have:
//
// var main,;
//
// ...at the beginning, replace ',;' with ';':

					$var_decs_orig = substr($destination_text_replaced, 0, strpos($destination_text_replaced, ';') + 1);
					$var_decs_new = str_replace(',;', ';',   $var_decs_orig);
					$destination_text_replaced = str_replace($var_decs_orig, $var_decs_new, $destination_text_replaced);

// Then write the revised text to the destination file:
					file_put_contents($destination, $destination_text_replaced);

					if ((filesize($temp_file) == filesize($destination)) && (md5_file($temp_file) == md5_file($destination))) {
						$html .= '<br>';
						$html .= 'ERROR: Closure Compiler minification failed: ' . $source . '<br>';
						$html .= '<br>';
					} else {
						$html .= 'SUCCESS: Closure Compiler minified: ' . $source . '<br>';
					}

					unlink($temp_file);

				} else {
					$html .= $source . ' does not contain "export default Object.freeze({" and closing "});" OR "export {" and closing "};" . . . procedure aborted.<br><br>';
				}
			} else {
				$html .= $source . ' does not exist . . . procedure aborted.<br><br>';
			}
		}
	} else {
		$html .= '<br>';
		$html .= '<strong>Modules are not minified on live site.</strong><br>';
		$html .= '<strong>Compile modules on localhost, then upload them to the live site.</strong><br>';
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