/**
* siteWideEditsClosure.mjs - v3.0.0
* Copyright (c) 2019-2023 Thomas M. Brodhead <https://bmt-systems.com>
* Released under the MIT license
* Date: 2023-11-01
*/

var main;

// We need the nonce that has been sent to the browser in order to realize
// custom STYLE elements as the SPA moves from page to page in the application.
//
// Why?
//
// With 'strict-dynamic' style source CSP, the realized STYLE elements need
// to be nonced when they are appended to the DOM, else they won't be
// recognized by the browser.
//
// To save the nonce, we create the siteWideEdits code as a closure that
// saves the nonce within its own code, with no further need to query the DOM
// to retrieve the nonce.

main = function () {

	var siteWideEditsClosure;

// The nonce is accessible to the siteWideEditsClosure routine, and is called from within it:

	siteWideEditsClosure = function (nonce) {

		var o;
		var windowLoad;
		var satisfyJsLint;

//var siteWideOnLocationChange;

// All of the existing functions will become methods of o:
// NB: o must be initialized at the top of this function!

		o = {};

		satisfyJsLint = false;

// 2021-04-13:
// THIS MUST BE DEFINED HERE!
// THE GALLERY WORKS WILL NOT UPDATE IN THE URL LATER UNLESS WE DEFINE IT NOW!
		o.metaNameWebAuthor = document.querySelector('meta[name=web_author]');

// set o.appendToCSS:
// Move external libraries off window to object to dedicated variables:
// NB: This is necessary to satisfy advanced mode of Closure Compiler:
		if (window.appendToCSS) {
			o.appendToCSS = window.appendToCSS;
		} else {
			console.log('ERROR: Window object needs appendToCSS in siteWideEditsClosure.mjs');
			return false;
		}

		o.assignToCommonObject = function (object) {
			Object.keys(object).forEach(function (key) {
				if (typeof object[key] === 'function') {
					o[key] = object[key].bind(o);
				} else {
					o[key] = object[key];
				}
			});
		};

// E.g., create 'gallery' property of 'o' object, and put each property/method
// of gallery.mjs as property/method of o.gallery:
		o.assignToModulePropertyOnCommonObject = function (pageName, object) {
			if (!o.hasOwnProperty(pageName)) {
				o[pageName] = {};
			}
			Object.keys(object).forEach(function (key) {
				if (typeof object[key] === 'function') {
					o[pageName][key] = object[key].bind(o);
				} else {
					o[pageName][key] = object[key];
				}
			});

		};

// SEE: https://davidwalsh.name/javascript-debounce-function
// Retain in siteWideEditsClosure():
		o.debounce = function (func, wait, immediate) {

			var timeout;

			return function () {

				var args;
				var callNow;
				var context;
				var later;

				args = arguments;
				callNow = immediate && !timeout;
				context = this;
				later = function () {
					timeout = null;
					if (!immediate) {
						func.apply(context, args);
					}
				};

				window.clearTimeout(timeout);
				timeout = window.setTimeout(later, wait);
				if (callNow) {
					func.apply(context, args);
				}
			};

		};

// set o.DOMPurify:
		if (window.hasOwnProperty('trustedTypes')) {
			if (window.DOMPurify) {

				o.DOMPurify = window.DOMPurify;

				o.DOMPurify.addHook('beforeSanitizeElements', function (currentNode, data, config) {
					if (satisfyJsLint) {
						console.log(data, config);
					}
					var validScriptTypes = ['application/json', 'application/ld+json'];
					if (currentNode.nodeName === 'SCRIPT') {
						if (currentNode.hasAttribute('type')) {
							if (!validScriptTypes.includes(currentNode.getAttribute('type'))) {
								currentNode.remove();
							}
						}
					}
				});

				o.DOMPurify.setConfig({RETURN_TRUSTED_TYPE: true, ADD_TAGS: ['style', 'SCRIPT', 'x-script', 'custom-style'], ADD_ATTR: ['target']});

// 2021-11-08
// From: https://stackoverflow.com/questions/62081028/this-document-requires-trustedscripturl-assignment
// Without this code, we cannot revise the 'application/ld+json' breadcrumbs
// in the reviseBreadCrumbs() function in gallery.mjs:
				if (window.trustedTypes && window.trustedTypes.createPolicy) { // Feature testing
					window.trustedTypes.createPolicy('default', {
						createHTML: (string) => o.DOMPurify.sanitize(string, {RETURN_TRUSTED_TYPE: true}),
						createScriptURL: (string) => string, // warning: this is unsafe!
						createScript: (string) => string // warning: this is unsafe!
					});
				}



			}
		}

		o.fenestra = {};
// 2021-11-01:
		o.history = [];
		o.history.push(window.location.href);

		o.googleAnalyticsInitialized = false;

		o.hardLoadFonts = function () {

			var callCommonRoutines;
			var fontCount;
//			var fontStyleSheetLink;
			var hardLoadFont;
//			var switchFontStyleSheetLinkMedia;
			var unloadedFontArray;

			callCommonRoutines = function () {

// This changes the value for --body-opacity-one, which is heretofore at 0
// when called at the end of the <custom-style> block at the end of each
// <main> element. This is part of the strategy to prevent layout shift.
				o.appendToCSS(':root', '{ --body-opacity-one: 1; }');
				o.commonRoutines();

			};

// See: https://csswizardry.com/2020/05/the-fastest-google-fonts/
// Specifically: https://www.filamentgroup.com/lab/load-css-simpler/

/*
			switchFontStyleSheetLinkMedia = function () {
				window.setTimeout(function () {
					document.querySelectorAll('#font-style-sheet-link').forEach(function (link) {
						if (link.getAttribute('media') !== 'all') {
							link.setAttribute('media', 'all');
							callCommonRoutines();
						}
					});
				}, 0);
			};
			fontStyleSheetLink = document.querySelector('#font-style-sheet-link');
			if (!fontStyleSheetLink) {
*/

// call the common routines if not forcing font load:

			unloadedFontArray = [];

// If the fonts are preloaded, then none of the following will happen:
			if (Boolean(window.FontFace) && Boolean(document.fonts)) {

				o.siteData.fontArray.forEach(function (fontObject) {

					var fontDescriptor;

					if (fontObject.descriptors.style === 'normal') {
						if (fontObject.descriptors.weight === 400) {
							fontDescriptor = 'normal 1em ';
						} else if (fontObject.descriptors.weight === 700) {
							fontDescriptor = 'bold 1em ';
						}
					} else if (fontObject.descriptors.style === 'italic') {
						if (fontObject.descriptors.weight === 400) {
							fontDescriptor = 'italic 1em ';
						} else if (fontObject.descriptors.weight === 700) {
							fontDescriptor = 'bold italic 1em ';
						}
					}

					fontDescriptor += fontObject.family;

					if (!document.fonts.check(fontDescriptor)) {
						unloadedFontArray.push(fontObject);
					}

				});
			}

			if (unloadedFontArray.length === 0) {
				callCommonRoutines();
			} else {
				fontCount = unloadedFontArray.length - 1;
				unloadedFontArray.forEach(function (fontObject, index) {
					hardLoadFont = new FontFace(fontObject.family, fontObject.source, fontObject.descriptors);
					hardLoadFont.load().then(function (loadedFont) {
						document.fonts.add(loadedFont);
						if (index === fontCount) {
							callCommonRoutines();
						}
					}).catch(function (error) {
						console.log(error);
						console.log('Error: ' + fontObject.family + ' ' + fontObject.descriptors.style + ' could not be loaded');
						callCommonRoutines();
					});
				});
			}
/*
			} else {
				switchFontStyleSheetLinkMedia();
			}
*/
		};

// NB: This is called in the .then() of the dynamic import of the
// common.mjs file, so we need it in this module:
		o.initializationRoutines = function () {

// 2021-04-11
// Moved to commonVariables() in common.mjs
//			o.metaNameWebAuthor = metaNameWebAuthor;

// Needed regardless of method of navigation, and must be run first:
// OLD NAME:
//			o.initialHashlessPaintingNavigationRoutines();
			o.initializePopstateLocationChangeListeners();

			window.addEventListener('orientationchange', o.setOrientation);

			if (o.windowEvents.resize1 === false) {
				window.addEventListener('resize', o.onResizeEdits);
				o.windowEvents.resize1 = true;
			}

			windowLoad = function () {

// This corrects the problem with site fonts not being displayed for roman text
// until after the site is refreshed. But it does more than that:
//
// We're calling o.commonRoutines() in the callback of the o.hardLoadFonts()
// promise. That way, the fonts are loaded immediately, and the CSS in
// prevent-layout-shift-end.css, which is included in the <custon-style>
// element at the top of each page's <main> element, will be triggered earlier
// in the load process (previously, o.hardLoadFonts was called during the
// commonRoutines() process:

				o.hardLoadFonts();

// 2021-04-11
// OLD:
// Delete data-nonce:
//				if (o.metaNameWebAuthor && Boolean(o.metaNameWebAuthor.dataset) && Boolean(o.metaNameWebAuthor.dataset.nonce)) {
//					delete o.metaNameWebAuthor.dataset.nonce;
//				}

			};

			if (o.windowEvents.load === false) {
				window.addEventListener('load-redux', windowLoad);
				o.windowEvents.load = true;
// We must fire the window.load event here because it was already fired.
// This module (siteWideEditsClosure.mjs) is loaded AFTER the actual
// window.load event, so we need to issue a custom event to jump start
// our processes:
				window.dispatchEvent(new Event('load-redux'));
			}
		};

// Array of page names stored in the data-page attribute of the main element;
// the values will be booleans indicating whether modules specific to those
// pages have been loaded into the site resources yet:
		o.loadedDependencies = [];

// 2021-11-10:
		o.mouseoverLoadedURLs = [];

// We must set the nonce on the o object here:
		o.nonce = nonce;

// We must wait until the carousel is updated in the DOM before resetting the
// various event listeners:
		o.onResizeEdits = o.debounce(function () {

//			o.resetHeaderHeightVariable();
// This is all that's called by o.resetHeaderHeightVariable() that we need:
			o.anchorHashFragmentIntercept();

			o.setOrRemoveMobileClasses();

		}, 250, false);

// 2021-11-04
// Set to false (canceled) in commonRoutinesOnFirstLoadOnly() in common.mjs.
// Needed in merchandise page routines; perhaps useful elsewhere.
		o.firstLoad = true;

// 2020-08-15:
// RETAIN IN CASE WE WANT TO REVERT TO JAVASCRIPT LAZYLOADING:
//
//	if (window.Waypoint) {
//		o.Waypoint = window.Waypoint;
//	} else {
//		console.log('ERROR: Window object needs Waypoint in siteWideEditsClosure.mjs');
//		return false;
//	}

// ------------------------------
// BEGIN INITIALIZATION ROUTINES:
// ------------------------------

		import('./siteData.mjs').then(function ({default: object}) {
// Assign its methods/properties to common object 'o':
			o.assignToCommonObject(object);

// Methods common to framework:
			import('./common.mjs').then(function ({default: object}) {
				o.assignToCommonObject(object);

// Methods common to site:
				import('./siteCommon.mjs').then(function ({default: object}) {
					o.assignToCommonObject(object);
					o.initializationRoutines();
				}).catch(function (error) {
					console.log(error);
				});

			}).catch(function (error) {
				console.log(error);
			});

		}).catch(function (error) {
			console.log(error);
		});

	};


	return siteWideEditsClosure;

};

// DATASET PRIMER
//
// element.hasAttribute('data-my-custom-attribute') === Boolean(lazyLoadElement.dataset.myCustomAttribute)
// element.getAttribute('data-my-custom-attribute') === lazyLoadElement.dataset.myCustomAttribute
// element.removeAttribute('data-my-custom-attribute') === delete element.dataset.myCustomAttribute
//
// img.hasAttribute('data-src') === img.dataset.src


// CLOSURE COMPILER EXPECTS ALL ARGUMENTS TO BE PROVIDED WHEN FUNCTION
// IS CALLED, UNLESS DEFAULT VALUES ARE PROVIDED IN FUNCTION DEFINITION,
// SIGH...
//
// Default for 'immediate', in case no argument is provided when function is called:
//	immediate = (
//		(immediate === undefined)
//		? false
//		: immediate
//	);

export default Object.freeze({
	main
});