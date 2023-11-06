<?php

// ENSURE THE FIRST LINE OF THE INPUT FILE IS A BLANK LINE!!!

$lines = file('input.txt');
$count = 0;
$text = '';

foreach($lines as $line) {
	$count += 1;
	if (($count % 4) === 0) {
		$text = $line . ' ';
		file_put_contents('output.txt', $text, FILE_APPEND);
		echo $line;
	}
}