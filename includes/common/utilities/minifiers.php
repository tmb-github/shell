<?php
function tovic_minify_html($input) {

	if (trim($input) == "") {
		return $input;
	}

// 2022-03-04:
	$input_save = $input;

// 2023-12-03
//
// It looks as though we no longer need to protect these math symbols from
// minification, but KEEP just in case there's a case we overlooked (see 
// corresponding routine at the end of this function):
//
//	$input = str_replace(' + ', 'INGOLFDAHLPLUSINGOLFDAHL', $input);
//	$input = str_replace(' - ', 'INGOLFDAHLMINUSINGOLFDAHL', $input);
//	$input = str_replace(' * ', 'INGOLFDAHLTMULTIPLYINGOLFDAHL', $input);
//	$input = str_replace(' / ', 'INGOLFDAHLDIVIDEINGOLFDAHL', $input);

// Remove extra white-space(s) between HTML attribute(s)
	$input = preg_replace_callback('#<([^\/\s<>!]+)(?:\s+([^<>]*?)\s*|\s*)(\/?)>#s', function($matches) {
		return '<' . $matches[1] . preg_replace('#([^\s=]+)(\=([\'"]?)(.*?)\3)?(\s+|$)#s', ' $1$2', $matches[2]) . $matches[3] . '>';
	}, str_replace("\r", "", $input));

// Minify inline CSS declaration(s)
	if(strpos($input, ' style=') !== false) {
		$input = preg_replace_callback('#<([^<]+?)\s+style=([\'"])(.*?)\2(?=[\/\s>])#s', function($matches) {
			return '<' . $matches[1] . ' style=' . $matches[2] . tovic_minify_css($matches[3]) . $matches[2];
		}, $input);
	}

	$return_string = preg_replace(
		array(
			// t = text
			// o = tag open
			// c = tag close
			// Keep important white-space(s) after self-closing HTML tag(s)
			'#<(img|input)(>| .*?>)#s',
			/* 
			TMB: see https://stackoverflow.com/questions/36974505/seeking-regex-for-html-attributes-meeting-specific-criteria
			Within tags *only*, remove quotes around element attribute strings that do not contain (1) spaces or (2) an equal sign;
--> or a forward slash!
			attributes with hyphens (-) and dots (.) are accounted for.
			To retain quotes around href and src elements, replace xyz in regex with them, as in commented version below;
			likewise, to retain quotes around any desired attribute text, replace xyz with that attribute or a series of attributes separated by | (bars).
			NB: Notice that in the original, ~ is used as a delimiter at the beginning and end; this has been replaced by # to conform with
			the syntax of the enclosing function. (u ensures unicode is treated corrected, and i ensures case insensitivity.)
			NB2: It turns out that the default Firefox tester of GTMetrix provides a higher score when src and href elements consistently have quotes around them,
			so it's apparently best to leave them in; other tags could be added, also, but none that I can think of right now.
			
			OLD: #(?:<[a-z][\w:.-]*|(?!^)\G)(?:\s+(?:(?:src|href)=(?:"[^"]*"|\'[^\']*\')|[a-z][\w:.-]*="(?:[^"=]*\s[^"=]*|[^"\s]*=[^"\s]*)"))*\s+(?!(?:src|href)=)[a-z][\w:.-]*=\K(?|"([^\s"=]*)"|\'([^\s\'=]*)\')#ui
			
			// 6 January 2017: NEW, add additional characters to [^\s"=] to complete listing of characters that require surrounding quotes:
			\s ---> any white space(s)
			" in '' quotes; \' in "" quotes
			
			These do not require an initial escape character:
			`
			=
			
			These require an initial escape character (\):
			\[
			\]
			\^
			
			These characters are OKAY in values and do not need to be checked for
			&
			,
			;
			?
			\#
			\$
			\(
			\)
			\/
			\+
			
			This would seem to be the complete list, prefixed with \s\' or \s" (depending on whether its in double quotes or single quotes, respectively):
			`=\[\]\^
			
			*/
			'#(?:<[a-z][\w:.-]*|(?!^)\G)(?:\s+(?:(?:src|href)=(?:"[^"]*"|\'[^\']*\')|[a-z][\w:.-]*="(?:[^"=]*\s[^"=]*|[^"\s]*=[^"\s]*)"))*\s+(?!(?:src|href)=)[a-z][\w:.-]*=\K(?|"([^\s"`=\[\]\^]*)"|\'([^\s\'`=\[\]\^]*)\')#ui',
			// Remove a line break and two or more white-space(s) between tag(s)
			'#(<!--.*?-->)|(>)(?:\n*|\s{2,})(<)|^\s*|\s*$#s',
			'#(<!--.*?-->)|(?<!\>)\s+(<\/.*?>)|(<[^\/]*?>)\s+(?!\<)#s', // t+c || o+t
			'#(<!--.*?-->)|(<[^\/]*?>)\s+(<[^\/]*?>)|(<\/.*?>)\s+(<\/.*?>)#s', // o+o || c+c
			'#(<!--.*?-->)|(<\/.*?>)\s+(\s)(?!\<)|(?<!\>)\s+(\s)(<[^\/]*?\/?>)|(<[^\/]*?\/?>)\s+(\s)(?!\<)#s', // c+t || t+o || o+t -- separated by long white-space(s)
			'#(<!--.*?-->)|(<[^\/]*?>)\s+(<\/.*?>)#s', // empty tag
			'#<(img|input)(>| .*?>)<\/\1>#s', // reset previous fix
			'#(&nbsp;)&nbsp;(?![<\s])#', // clean up ...
			'#(?<=\>)(&nbsp;)(?=\<)#', // --ibid
			// Remove HTML comment(s) except IE comment(s)
			'#\s*<!--(?!\[if\s).*?-->\s*|(?<!\>)\n+(?=\<[^!])#s'
		),
		array(
			'<$1$2</$1>',
			//TMB
			'$1',
			'$1$2$3',
			'$1$2$3',
			'$1$2$3$4$5',
			'$1$2$3$4$5$6$7',
			'$1$2$3',
			'<$1$2',
			'$1 ',
			'$1',
			""
		),
		$input);

// 2023-12-03
//
// See corresponding replacement block at top of this function for explanation.
// KEEP this all for now until we're certain it's no longer necessary.
//
//	if (!empty($return_string)) {
//
// This line was added to reverse a corresponding replacement in
// gallery-editor/main.php of the art site. Along with the rest of this routine,
// it no longer seems necessary:
//
//		$return_string = str_replace(' INGOLFDAHLDIVIDEINGOLFDAHL ', ' / ', $return_string);
//
//		$return_string = str_replace('INGOLFDAHLPLUSINGOLFDAHL', ' + ', $return_string);
//		$return_string = str_replace('INGOLFDAHLMINUSINGOLFDAHL', ' - ', $return_string);
//		$return_string = str_replace('INGOLFDAHLTMULTIPLYINGOLFDAHL', ' * ', $return_string);
//		$return_string = str_replace('INGOLFDAHLDIVIDEINGOLFDAHL', ' / ', $return_string);
//	} else {
//// ensure it's not null:
//		$return_string = '';
//	}

// 2023-12-03
// empty() returns TRUE when argument is NULL, which is a necessary
// check in cases of routine failure:
	if (empty($return_string) || ($return_string == '')) {
		$return_string = $input_save;
	}

	return $return_string;
}

// CSS Minifier => https://gist.github.com/tovic/d7b310dea3b33e4732c0
function tovic_minify_css($input) {
	$input = trim($input);

// 2022-03-04:
	$input_save = $input;

	//if(trim($input) === "") return $input;

	$input = str_replace(' + ', 'INGOLFDAHLPLUSINGOLFDAHL', $input);
	$input = str_replace(' - ', 'INGOLFDAHLMINUSINGOLFDAHL', $input);
	$input = str_replace(' * ', 'INGOLFDAHLTMULTIPLYINGOLFDAHL', $input);
	$input = str_replace(' / ', 'INGOLFDAHLDIVIDEINGOLFDAHL', $input);

	$return_string = "";

	if ($input === "") {
		$return_string = $input;
		//return $input;
	} else {
		//return preg_replace(
		$return_string = preg_replace(
		array(
			// Remove comment(s)
			'#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')|\/\*(?!\!)(?>.*?\*\/)|^\s*|\s*$#s',
			// Remove unused white-space(s)
			'#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/))|\s*+;\s*+(})\s*+|\s*+([*$~^|]?+=|[{};,>~+]|\s*+-(?![0-9\.])|!important\b)\s*+|([[(:])\s++|\s++([])])|\s++(:)\s*+(?!(?>[^{}"\']++|"(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')*+{)|^\s++|\s++\z|(\s)\s+#si',
			// Replace `0(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)` with `0`
			'#(?<=[\s:])(0)(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)#si',
			// Replace `:0 0 0 0` with `:0`
			'#:(0\s+0|0\s+0\s+0\s+0)(?=[;\}]|\!important)#i',
			// Replace `background-position:0` with `background-position:0 0`
			'#(background-position):0(?=[;\}])#si',
			// Replace `0.6` with `.6`, but only when preceded by `:`, `,`, `-` or a white-space
			'#(?<=[\s:,\-])0+\.(\d+)#s',
			// Minify string value
			'#(\/\*(?>.*?\*\/))|(?<!content\:)([\'"])([a-z_][a-z0-9\-_]*?)\2(?=[\s\{\}\];,])#si',
			'#(\/\*(?>.*?\*\/))|(\burl\()([\'"])([^\s]+?)\3(\))#si',
			// Minify HEX color code
			'#(?<=[\s:,\-]\#)([a-f0-6]+)\1([a-f0-6]+)\2([a-f0-6]+)\3#i',
			// Replace `(border|outline):none` with `(border|outline):0`
			'#(?<=[\{;])(border|outline):none(?=[;\}\!])#',
			// Replace `type=[submit i]` with `type=['submit i']`
			'#(?<=[\{;])type=[submit i](?=[;\}\!])#',
			// Replace `-webkit-font-feature-settings:liga` with `-webkit-font-feature-settings:'liga'`(THIS ROUTINE STRIPS OFF THE QUOTES!!!)
			'#(?<=[\{;])(-webkit-font-feature-settings):liga(?=[;\}\!])#',
			// Replace `font-feature-settings:liga on` with `font-feature-settings:'liga' on`(THIS ROUTINE STRIPS OFF THE QUOTES!!!)
			'#(?<=[\{;])(font-feature-settings):liga on(?=[;\}\!])#',
			// Remove empty selector(s)
			'#(\/\*(?>.*?\*\/))|(^|[\{\}])(?:[^\s\{\}]+)\{\}#s'
		),
		array(
			'$1',
			'$1$2$3$4$5$6$7',
			'$1',
			':0',
			'$1:0 0',
			'.$1',
			'$1$3',
			'$1$2$4$5',
			'$1$2$3',
			'$1:0',
			"type=['submit i']",
			"$1:'liga'",
			"$1:'liga' on",
			'$1$2'
		),
		$input);
	}

// 2022-05-01
// If it's not null or empty
	if (!empty($return_string)) {
		$return_string = str_replace('INGOLFDAHLPLUSINGOLFDAHL', ' + ', $return_string);
		$return_string = str_replace('INGOLFDAHLMINUSINGOLFDAHL', ' - ', $return_string);
		$return_string = str_replace('INGOLFDAHLTMULTIPLYINGOLFDAHL', ' * ', $return_string);
		$return_string = str_replace('INGOLFDAHLDIVIDEINGOLFDAHL', ' / ', $return_string);
	} else {
// ensure it's not null:
		$return_string = '';
	}

// 2022-03-04:
	if ($return_string == '') {
		$return_string = $input_save;
	}

	return $return_string;

}

// We should RETAIN the existing JavaScript minifier, as we don't know what the Kangax JS minifier will do with pre-minified code:
// JavaScript Minifier => https://gist.github.com/tovic/d7b310dea3b33e4732c0
function tovic_minify_js($input) {

	if (trim($input) === "") {
		return $input;
	}

// 2022-03-04:
	$input_save = $input;

	$return_string = preg_replace(
	array(
		// Remove comment(s)
		'#\s*("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')\s*|\s*\/\*(?!\!|@cc_on)(?>[\s\S]*?\*\/)\s*|\s*(?<![\:\=])\/\/.*(?=[\n\r]|$)|^\s*|\s*$#',
		// Remove white-space(s) outside the string and regex
		'#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/)|\/(?!\/)[^\n\r]*?\/(?=[\s.,;]|[gimuy]|$))|\s*([!%&*\(\)\-=+\[\]\{\}|;:,.<>?\/])\s*#s',
		// Remove the last semicolon
		'#;+\}#',
		// Minify object attribute(s) except JSON attribute(s). From `{'foo':'bar'}` to `{foo:'bar'}`
		'#([\{,])([\'])(\d+|[a-z_][a-z0-9_]*)\2(?=\:)#i',
		// --ibid. From `foo['bar']` to `foo.bar`
		'#([a-z0-9_\)\]])\[([\'"])([a-z_][a-z0-9_]*)\2\]#i'
	),
	array(
		'$1',
		'$1$2',
		'}',
		'$1$3',
		'$1.$3'
	),
	$input);

// 2022-05-01:
// 2022-03-04:
//	if ($return_string == '') {
	if (empty($return_string)) {
		$return_string = $input_save;
	}

	return $return_string;

}
