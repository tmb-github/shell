<?php

// NB:
// So long as we are using the errorListner.js routine, we no longer need this.
// Keep just in case, though.

/*
$browser = new Browser();

$_SESSION['IE_OR_EDGE_BROWSER'] = false;

if ((($browser->getBrowser() == Browser::BROWSER_IE) && ($browser->getVersion() <= 11)) || ($browser->getBrowser() == Browser::BROWSER_EDGE)) {
	$_SESSION['IE_OR_EDGE_BROWSER'] = true;
}

if (($browser->getBrowser() == Browser::BROWSER_IE) && ($browser->getVersion() <= 11)) {
	echo "<!doctype html><html lang=en-us><head><title>Don't use IE | John Q. Public</title><meta charset=utf-8><style>body { margin: 2em 22%; }</style></head><body><main><h1>Oops! You’re using Internet Explorer (IE), an <a href=https://www.engadget.com/2019/04/14/internet-explorer-file-stealing-exploit/ target=_blank rel='noopener noreferrer' title='(Opens in new window/tab)'>insecure</a>, dead browser that has not been updated since 2015.<br><br>Please open this site using <a href=https://www.google.com/chrome/ target=_blank rel=noopener title='(Opens in new window/tab)'>Chrome</a>, <a href=https://www.mozilla.org target=_blank rel=noopener title='(Opens in new window/tab)'>Firefox</a>, <a href=https://www.opera.com/ target=_blank rel=noopener title='(Opens in new window/tab)'>Opera</a>, or any other modern browser.<br><br>Thank you!</h1></main></body></html>";
	exit;
}
*/