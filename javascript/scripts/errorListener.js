/**
* errorListener.js
* Copyright (c) 2019-2020 Thomas M. Brodhead <https://bmt-systems.com>
* Released under the MIT license
* Date: 2020-03-30
*/

// getEventListeners(window); --> will log event listeners to console

// This is used on site loading to redirect if the browser doesn't support
// dynamic import() and other current features, all of which will throw a
// syntax error that is impossible to catch otherwise.
//
// This routine may not be loaded with the loader (loader.js). The error
// listener added by this routine needs to be in place *before* the loader is
// executed so that if a parse error occurs while the loader is running, then
// it can catch it and redirect the visitor to the incompatible-browser page.
//
// This error listener MUST be removed after script parsing or else ANY
// subsequent error will trigger it. For the current site, it is removed
// in the callback of the dynamic import() in siteWideEdits.js.

window.incompatibleBrowser = function (e) {

	var origin;

// Remove this listener before redirecting. The listener is also removed in
// siteWideEdits.js within the import() callback, in case parsing is
// successful, in which case this listener is no longer needed.

	window.removeEventListener('error', window.incompatibleBrowser);

	origin = (
		(window.location.href === 'https://shell.com/')
		? 'https://shell.com/'
		: 'https://localhost/shell/'
	);

	window.location.href = origin + 'incompatible-browser?filename=' + encodeURI(e.filename) + '&lineno=' + encodeURI(e.lineno) + '&colno=' + encodeURI(e.colno) + '&message=' + encodeURI(e.message);

};

// A syntax error during parsing simply generates 'error':
window.addEventListener('error', window.incompatibleBrowser);

window.DOMContentLoaded
