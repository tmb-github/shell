<?php

// To capture the existing session
session_start([
	'name' => '__Secure-PHPSESSID',
	'cache_limiter' => 'private_no_expire:'
]);

if (isset($_POST['random_word'])) {
/* Define random and associated password array */
	$word_list = array('random', 'apple', 'cat', 'dog', 'hypotenuse');

/* Check and assign submitted random and Password to new variable */
	$random_word = isset($_POST['random_word']) ? $_POST['random_word'] : '';

/* Check random_word against words in word_list array */
	if (in_array($random_word, $word_list)) {

/**************************************************/
/* To prevent the 500 error, un-rem the following */
/**************************************************/
/*
		print_r('true');
	} else {
		print_r('false');
	}
}
*/

?>