/**
* siteWideEdits.js - v2.4.5
* Copyright (c) 2019-2023 Thomas M. Brodhead <https://bmt-systems.com>
* Released under the MIT license
* Date: 2023-11-01
*/

(function () {

// Google Universal Analytics (real-time reporting):
// https://analytics.google.com/analytics/web/#/report-home/a###################

// getEventListeners(window); --> will log event listeners to console

	'use strict';

	var importAppendToCssClosure;
	var importSiteWideEditsClosure;

// Delete query string:
	if (window.location.href.includes('?')) {

// 2023-11-10:
// KEEP!!!
// Restore this if query strings need to be removed. BUT WHY WOULD THEY?
//		window.history.replaceState({}, document.title, window.location.href.split('?')[0]);

		if (window.sessionStorage) {
			window.sessionStorage.clear();
		}
	}

// siteWideEditsClosure will contain the routine with nonce stored in it because
// the nonce is part of the closure:

	importAppendToCssClosure = function (nonce) {
		import('../modules/appendToCssClosure.mjs').then(function ({default: object}) {

			var appendToCSS;

// Remove the 'error' listener of errorListener.js, which exists only to catch
// syntax errors that would be thrown while parsing JavaScript in browsers that
// do not implement dynamic import() or other similar, modern features.
//
// NB: If we do NOT remove it here, then ANY error that is thrown subsequently
// in the JavaScript will cause the site to be redirected to the
// incompatible-browser page, which would making debugging the code impossible!
// So, if more modern JavaScript than dynamic import() is added to the site, it
// really should be tested for in this JS file, perhaps before the import(), so
// that the listener can be removed upon its detection.

			window.removeEventListener('error', window.incompatibleBrowser);

			appendToCSS = object.main();
			window.appendToCSS = appendToCSS(nonce);

// This must follow adding appendToCSS to the window object:
			importSiteWideEditsClosure(nonce);

		}).catch(function (error) {
			console.log(error);
		});
	};


// NB: We'll call this inside the importAppendToCssClosure() function.
// siteWideEdits() expects appendToCSS() being present on the window object.
	importSiteWideEditsClosure = function (nonce) {
		import('../modules/siteWideEditsClosure.mjs').then(function ({default: object}) {
			var siteWideEdits;

			siteWideEdits = object.main();
			siteWideEdits(nonce);

		}).catch(function (error) {
			console.log(error);
		});
	};

// 2022-04-19
// This logic has been moved to loader.js, and shouldn't be required at this point
// any longer:
/*
// First try to get the nonce directly from the currentScript:
	if (document.currentScript && document.currentScript.nonce) {
		importAppendToCssClosure(document.currentScript.nonce);
	} else {
// If that fails, use the getNonce.mjs, which will first search for it on the
// web_author META tag, and then, if that fails, will perform XHR to get it
// via headers:
		import('../modules/getNonce.mjs').then(function ({default: object}) {
			object.main(importAppendToCssClosure);
		}).catch(function (error) {
			console.log(error);
		});
	}
*/

// The nonce should have been added to this script (siteWideEdits.js) by the
// loader (loader.js):
	if (document.currentScript && document.currentScript.nonce) {
		importAppendToCssClosure(document.currentScript.nonce);
	} else {
		console.log('ERROR: No nonce');
	}

}());
