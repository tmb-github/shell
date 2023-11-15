/**
* common.mjs
* Copyright (c) 2019-2023 Thomas M. Brodhead <https://bmt-systems.com>
* Released under the MIT license
* Date: 2023-11-01
*/

// reCAPTCHA and HONEYPOT code in this file; rem/unrem each appropriately
var activateLoadingMask;

//var animationStart;
//var animationIteration;
//var animationEnd;

var ajaxMainContent;
var anchorHashFragmentIntercept;
var anchorIntercept;
var aspectRatioImgEdits;
var calledCommonVariables;
var camelCaseToKebobCase;
var checkTrustedTypesSupport;
var closeDrawerOnAnchorClick;
var closeDrawerOnMobileLogoClick;
var commonEventListeners;
var commonRoutines;
var commonRoutinesOnFirstLoadOnly;
var commonRoutinesOnFirstLoadOrAjax;
var commonVariables;
var conditionallyReplaceJpgExtensionWithWebpExtension;
var consoleLog;
var consoleLogMsgObj;
var customizePushAndReplaceState;

//var standardPushAndReplaceState;

var deactivateLoadingMask;
var deleteCachesUnregisterServiceWorkerAndClearSessionStorage;
var editExternalLinks;
var enqueueArray;
var escapeForwardSlashes;
var escapeSingleQuotes;
var fetchAppendToUploadStatusDiv;
var fetchReject;
var fetchResolve;
var fetchResolveWithOptions;
var fetchResponse;
var fireCustomEvent;
var fiveRandomAlphaNumerics;
var footerEdits;
var highlightMenuItem;
var inEu;
var initializeFooterHeightAndObserver;
var initializeHeaderHeightAndObserver;
var initializePopstateLocationChangeListeners;
var initializeWindowOnResizeRoutines;
var inner;
var innerFinish;
var isObject;
// SAVE: var iOS;
// SAVE: var iosCheckAndEdit;
var isSafari;
var kebobCaseToCamelCase;
var kebobCaseToUpperCamelCase;
var loadLocalResource;
var loadPageDependencies;
// 2020-08-15:
// RETAIN IN CASE WE WANT TO REVERT TO JAVASCRIPT LAZYLOADING:
//var makeWaypoints;
var makeTransparentMaskClickable;
var maxWidth759px;
//var moreInfoLessInfo;
var noTitle;
var popstateListener;
var promiseLoader;
var removeVisibilityHiddenFromTmbAlert;
//var resetHeaderHeightVariable;
var returnAjaxObject;
//var returnHeaderHeight;
var returnTimeStamp;
var revealHashedContent;
var reviseMetaData;
var reviseSchema;
var scrollDownByHeaderHeight;
var serviceWorkerRoutine;
var setCanonicalLink;
var setOrRemoveMobileClasses;
var setOrientation;
var setMaxWidth759px;
var siteWideLoader;
var stripEndQuotes;
var stripHtmlTags;
var tmbTT;
var updateFenestra;
var updateUriInfo;
// ARCHIVE THIS FUNCTION, JUST IN CASE WE NEED IT: var wait;
// ARCHIVE THIS FUNCTION, JUST IN CASE WE NEED IT: var waitAndSetHeaderHeight;
var windowEvents;

activateLoadingMask = function () {

//console.log('activateLoadingMask');

// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
//	var o = this;

	document.querySelectorAll('.loading-mask').forEach(function (mask) {
//console.log(mask);
//		mask.addEventListener('animationstart', o.animationStart);
//		mask.addEventListener('animationend', o.animationEnd);
//		mask.addEventListener('animationiteration', o.animationIteration);

		mask.classList.add('active');
	});
};


ajaxMainContent = function (hrefText, target, backbutton, eventType) {

	var ajaxResponse;
	var ajaxURL;
//	var editedHrefText;
//	var folder;
//	var folderMatch;
//	var mouseoverPreload;
	var o;
	var queries;
	var sessionValue;
	var URL;

// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
	o = this;

// For testing:
// qwer:htaccess
//console.log('---------------');
//console.log('o.ajaxMainContent');
//console.log('hrefText = ' + hrefText);
//console.log('target = ' + target);
//console.log('backbutton = ' + backbutton);
//console.log('eventType = ' + eventType);

// Define URL without query string
	if (target === undefined) {
		URL = o.baseHref;
	} else {
		URL = target.replace(/\/$/, '');
		if (!URL.includes('?')) {
			URL = URL + '/';
		}
	}

// Define URL without hash string
	if (URL.indexOf('#') !== -1) {
		URL = URL.substring(0, URL.indexOf('#'));
	}

// qwer:htaccess
// 2023-10-28

	o.URL = URL;

// No need to revise this...the .htaccess rewrite rules will kick in and do
// everything:

// qwer:htaccess
// 2023-10-29
// strip out 'pages' on '/pages/error/' URLs:
	URL = URL.replace(/\/pages\/error\//g, '/error/');
// The routine above will add '/' to the end of 'main.php' on error pages, so strip it off if found:
	if ((URL.endsWith('main.php/')) || (URL.endsWith('main.php'))) {
		if (URL.endsWith('/')) {
			URL = URL.slice(0, -1);
		}
		ajaxURL = URL;
	} else {
		ajaxURL = URL + 'main.php';
	}

//console.log('ajaxURL = ' + ajaxURL);
//console.log(window.sessionStorage);

// Go to home directory if none indicated
	if (hrefText === null) {
		hrefText = '.';
	}

	ajaxResponse = function (data) {

//console.log('ajaxResponse');
//console.log('backbutton = ' + backbutton);
//console.log('MINERVA 1');

// qwer:htaccess
// MINERVA 1:
// 2023-10-29:
// This solves the problem with the long-press backbutton not recording the initial landing page!
// (This works with MINERVA 2 below...both are needed for the routine to work)
// We must pushState() the target HREF here, them replaceState() it later, inside the window.setTimeout():
		if (backbutton === false) {
			window.history.pushState({}, '', window.location.href);
		}

		window.setTimeout(function () {

			var customStyle;
			var customStyleCloseTag;
			var customStyleEnd;
			var customStyleOpenTag;
			var customStyleStart;
			var currentCustomStyle;
			var dataPage;
			var dataPageEnd;
			var dataPageStart;
			var fragment;
			var hashedElement;
			var headElement;
			var main;
			var mainElement;
			var newData;
			var page;
			var stateObject;
			var styleElement;
			var switcheroo;

			function setInnerHTML(element, html) {
				if (element && element.parentNode) {
					element.parentNode.removeChild(element);
// 2022-09-05:
// Eliminate the reference to the element so that it and its event
// listeners may be garbage-collected.
// https://stackoverflow.com/questions/12528049/if-a-dom-element-is-removed-are-its-listeners-also-removed-from-memory
					element = null;
					if (tmbTT.active) {
						html = o.DOMPurify.sanitize(html);
					}
					fragment = document.createRange().createContextualFragment(html);
					document.querySelectorAll('HEADER').forEach(function (header) {
						header.after(fragment);
					});
				}
			}

// 2021-04-18:
			if (window.sessionStorage) {
				window.sessionStorage.setItem(ajaxURL, data);
			}

// 2021-08-14: the following swaps the <MAIN> content with the contents
// specified in the HREF, so don't perform during 'mouseover':
			if (eventType !== 'mouseover') {

				main = document.querySelector('main');

// qwer:htaccess:
// Needed now that we're adding this when the user hits the error page;
// remove it before navigating again (no harm to try to remove if it's not there):

//		(function (main) {
//			if (main) {
//				main.removeAttribute('data-http-status');
//			}
//		}(document.querySelector('MAIN')))


// OLD (2022-03-17):
//			window.setTimeout(function () {

// This sets the new content as we move from page to page using the SPA:
//
// What would be ideal would be to extract the CUSTOM-STYLE from it,
// determine if it's not what'a already in the HEAD, then, if not, add
// it to the HEAD, then set the rest of the MAIN content.
//
// That way, all the styles for the page would be in place when the content
// comes in.

				customStyleOpenTag = '<custom-style class=display-none>';
				customStyleCloseTag = '</custom-style>';

				customStyleStart = data.indexOf(customStyleOpenTag);
				customStyleEnd = data.indexOf(customStyleCloseTag);


// NB: customStyle is just text!
				customStyle = data.slice(customStyleStart + customStyleOpenTag.length, customStyleEnd);

// 2022-03-07
// OLD: data.slice(0, (customStyleStart - 1)), which was wrong!
// .slice(x, y) is from x to y *exclusive of y*!
				newData = data.slice(0, customStyleStart) + data.slice(customStyleEnd + customStyleCloseTag.length);
				dataPage = 'data-page=';
				dataPageStart = newData.indexOf(dataPage);
				dataPageEnd = newData.indexOf(' ', dataPageStart);
// 2023-02-22
				page = newData.slice(dataPageStart + dataPage.length, dataPageEnd);
				page = stripEndQuotes(page);

				if (o.nonce) {

// Do NOT perform the switcheroo (convert <CUSTOM-STYLE> to a true <STYLE>
// element) if the existing .custom-style <STYLE> has a data-page value
// that matches the incoming <MAIN> element's data-page value.

// Assume we will convert the <CUSTOM-STYLE> element:
					switcheroo = true;
					currentCustomStyle = document.querySelector('.custom-style');
					if (currentCustomStyle) {
// Ensure it has the data-page property we're seeking:
						if (currentCustomStyle.dataset && currentCustomStyle.dataset.page) {
// Now see if the page type (e.g., 'home', 'about', 'gallery') matches the
// customStyle:
							if (page && (page === currentCustomStyle.dataset.page)) {
// If their data-page properties match, do NOT convert the <CUSTOM-STYLE>
// element:
								switcheroo = false;
							}
						}
					}

					if (switcheroo) {

// Custom STYLE elements are added to the HEAD, so remove the OLD ones before
// replacing the MAIN element, which will trigger the creation of NEW ones in
// the HEAD:
						document.querySelectorAll('.custom-style').forEach(function (element) {
							if (element.parentNode) {
								element.parentNode.removeChild(element);
// Remove reference to element so it can be garbage-collected:
// (Set each element in the loop to null, as not all elements will
// have parents, so not all of them will deleted, so we can't simply
// test for the last element returned by the forEach loop)
								element = null;
							}
						});

						headElement = document.querySelector('HEAD');

// Create fragment:
						fragment = document.createDocumentFragment();
// Create STYLE element to be inserted into the DOM:
						styleElement = document.createElement('STYLE');
// Append it to the fragment:
						fragment.appendChild(styleElement);
// Modify the properties of the element now that it's attached to the fragment:
						styleElement.setAttribute('nonce', o.nonce);
// Mark it as a custom style:
						styleElement.setAttribute('class', 'custom-style');
						styleElement.setAttribute('data-page', page);

// NB: customStyle is just text, so don't use .innerHTML on it!
//				styleElement.innerHTML = customStyle;
						if (tmbTT.active) {
							styleElement.innerHTML = o.DOMPurify.sanitize(customStyle);
						} else {
							styleElement.innerHTML = customStyle;
						}

// Append the fragment to the DOM at the desired location; the fragment itself
// disappears and its children are what is actually appended:
						headElement.appendChild(fragment);
// Delete the nonce so an attacker can't steal it:
						styleElement.removeAttribute('nonce');
					}
				}

				setInnerHTML(main, newData);

// --main-opacity is set to 0 in anchorIntercept() when an anchor is clicked
// to navigate about the site using the SPA. Doing that prevents a flash of
// unstyled content (mainly a flash of images on the left side of the screen).
// This restores the opacity:
//
// asdf
// 2023-11-15:
// Put in main() of each page mjs file so that any style edits performed by
// those pages are performed before the opacity is returned to 1:
//				o.appendToCSS(':root', '{ --main-opacity: 1; }');

				stateObject = {};
// we've swapped out the MAIN element, so select it again (?)
				mainElement = document.querySelector('main');
				if (mainElement && mainElement.dataset && mainElement.dataset.title) {
					stateObject.title = mainElement.dataset.title;
				} else {
					stateObject.title = 'Untitled';
				}
				stateObject.url = URL;
				document.title = stateObject.title;
// Revise canonical link in HEAD:
				setCanonicalLink(URL);

// qwer:htaccess
// add 404/403 etc. status to data-http-status
				if (ajaxURL.endsWith("/error/main.php")) {
					mainElement.setAttribute('data-http-status', o.httpStatus);
				} else {
					mainElement.removeAttribute('data-http-status');
				}

				document.querySelectorAll('#header-nav ul a').forEach(function (anchor) {
					if (anchor.getAttribute('href') !== hrefText) {
						if (anchor.classList.contains('selected')) {
							anchor.classList.remove('selected');
							anchor.closest('.secondary-ul')?.classList.remove('selected');
						}
					}
				});

				document.activeElement.blur();

// qwer:htaccess
// MINERVA 2:
// 2023-10-29
// This works with MINERVA 1 above to solve the long-standing long-press backbutton problem!

//console.log('MINERVA 2');
//console.log('backbutton = ' + backbutton);

				if (backbutton === false) {
					window.history.replaceState(stateObject, stateObject.title, stateObject.url);
				}

// aha!
// 2022-07-31
// If there's no hash in the URL, then scroll to the top:
				if (window.location.hash === '') {
					window.scroll(0, 0);
				}

				if ((backbutton === true) && (hrefText && (hrefText.charAt(0) === '#'))) {
					hashedElement = document.querySelector(hrefText);
					if (hashedElement !== null) {
						hashedElement.scrollIntoView();
// 2022-07-31:
						if (window.location.hash === '') {
							o.scrollDownByHeaderHeight();
						}

					}
				}

// NEW: (2022-03-17):
// Start new process:
//				window.setTimeout(function () {
				o.commonRoutinesOnFirstLoadOrAjax();

// This is necessary after AJAXing in content:

// Deactivate the loading mask regardless:
				o.deactivateLoadingMask();

// NEW (2022-03-17):
//				}, 0);

			}
		}, 0);
	};

	queries = (
		(o.metaDataWebpSupport === true)
		? {'webp': 'true'}
		: {'webp': 'false'}
	);

// 2021-08-04: post if 'mouseover', otherwise use the standard routine:
	if (eventType === 'mouseover') {
// 2021-11-10:
// Initialized in siteWideEditsClosure.mjs:
		if (o.mouseoverLoadedURLs) {
			if (!o.mouseoverLoadedURLs.includes(ajaxURL)) {
// fetch it if it's not already in session storage:
				if (window.sessionStorage) {
					if (window.sessionStorage.getItem(ajaxURL) === null) {
						o.ajax.post(ajaxURL, queries, ajaxResponse, true);
						o.mouseoverLoadedURLs.push(ajaxURL);
					}
				}
			}
		}
	} else {
// 2021-04-18:
// qwer:htaccess
		if (window.sessionStorage) {
			sessionValue = window.sessionStorage.getItem(ajaxURL);
		}
		if (!sessionValue) {
			o.ajax.post(ajaxURL, queries, ajaxResponse, true);
		} else {
			ajaxResponse(sessionValue);
		}
	}

};

anchorHashFragmentIntercept = function () {

	var o;
	var reassignHref;

// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
	o = this;

// To override the BASE href for hashed anchors on a page, set the URI equal
// to the HREF of the hashed anchor:
	reassignHref = function (selector) {
		function reassignHrefOnAnchorClick(e) {
			var anchor;
			e.preventDefault();
// It's possible that the clicked element is an element inside the anchor, not
// the anchor itself,so find the first element (or the clicked element) that
// is an anchor:
			anchor = e.target.closest('a');
			if (anchor) {
				o.fenestra.location.hash = anchor.getAttribute('href');
			}
		}
// When using a variable, escape any forward slashes that might be in the text:
		document.querySelectorAll(o.escapeForwardSlashes(selector)).forEach(function (element) {
			if (!element.classList.contains('reassign-href-on-anchor-click-listener')) {
				element.classList.add('reassign-href-on-anchor-click-listener');
				element.addEventListener('click', reassignHrefOnAnchorClick, false);
			}
		});
	};

// 2023-10-16:
// Needed for Shell program: section:not(.utility)
// OLD:	reassignHref('a[href*="#"]:not([href*="//"]):not(.email):not(.phone)');
	reassignHref('a[href*="#"]:not([href*="//"]):not(.email):not(.phone):not(.preserve-href)');
	reassignHref('a.hash-anchor');

// Create tab index on main-content element, which varies from page to page.
//
// NB: This is NOT the #skip-to-main-content element in the <header>,
// it's the #main-content element in the <main> element, which is different
// from page to page:
	document.querySelectorAll('#main-content').forEach(function (element) {
		element.setAttribute('tabIndex', '0');
	});

};

anchorIntercept = function () {

	var mouseoverPreload;
	var o;
	var onAnchorClick;

// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
	o = this;

	onAnchorClick = function (e) {
		var backbutton;
		var closestAnchor;
		var hrefText;
		var target;

// --main-opacity is restored to 1 in ajaxMainContent() after the new MAIN
// element has been placed in the DOM. Turning the opacity of the MAIN element
// off when the anchor is clicked ensures that there is not a flash of
// unstyled content (mainly a flash of images on the left side of the screen).
// This sets the opacity to 0 to prevent that flash:

// 2021-08-04: Only do the following when NOT mousing-over; otherwise, it will
// hide the <MAIN> content and activate the loading mask, something only needed
// when actually navigating to the HREF, not simply preloading it:
//
// For preloading the HTML of the MAIN into session storage:
		if (e.type !== 'mouseover') {

			o.appendToCSS(':root', '{ --main-opacity: 0; }');
// 2022-09-22
// Apparently unnecessary:
//			o.appendToCSS(':root', '{ --footer-opacity: 0; }');

			o.activateLoadingMask();
/*
			document.querySelectorAll('.loading-mask').forEach(function (mask) {
				mask.classList.add('active');
			});
*/
			o.updateUriInfo();
		}

		if (e.target.tagName === 'A') {
			hrefText = e.target.getAttribute('href');
			target = e.target.href;
		} else {
// ...else it's likely an IMG within an Anchor, hence an image-anchor.
// So, look up the family tree for the first anchor & collect info if found:
			closestAnchor = e.target.closest('A');
			if (closestAnchor) {
				if (closestAnchor.classList.contains('image-anchor')) {
					hrefText = closestAnchor.getAttribute('href');
					target = closestAnchor.href;
//console.log(target);
				}
			}
		}

		backbutton = false;
// qwer:htaccess
		o.backbutton = backbutton;

// When navigating to the home page, hrefText will be null and target will be
// null, so revise to prevent a reloading of the site:

		if ((hrefText === null) || (hrefText === undefined)) {
			hrefText = '/';
		}

		if ((target === null) || (target === undefined)) {
			document.querySelectorAll('BASE').forEach(function (base) {
				if (base.hasAttribute('href')) {
					target = base.getAttribute('href');
				}
			});
		}

		if (navigator.onLine) {
			if (hrefText && (hrefText.charAt(0) !== '#')) {
				e.preventDefault();
// Have we already ajaxed the main.php?
				if (e.type === 'mouseover') {
// If the target + main.php is a key in sessionStorage, then do NO perform ajax:
					if (window.sessionStorage.hasOwnProperty(target + 'main.php')) {
// Get the hell out of here...
						return true;
					}
				}
				o.ajaxMainContent(hrefText, target, backbutton, e.type);
// Odd... The sliding drawer does, in fact, close when a link on it is clicked.
// This must be code taken from Ives and BMT sites. Keep in case we can use it
// on either of those sites.
//
// Execute this ONLY if you want the sliding drawer to close automatically when
// an anchor in the drawer is clicked:
//
//				if (o.closeDrawerOnAnchorClick) {
//					if (window.matchMedia('(max-width: 759px)').matches) {
//						document.querySelector('#hamburger').click();
//					}
//				}
			} else {
				return true;
			}
		} else {
			return true;
		}
	};

// Only target anchors to pages within the site, i.e., exclude external links:
// Escape forward slashes in the text:
	document.querySelectorAll(o.escapeForwardSlashes('a:not([href*="//"]):not([href*="#"]):not(.hash-anchor):not(.gwt-Anchor)')).forEach(function (element) {
		if (!element.classList.contains('anchor-intercept-click-listener')) {
			element.classList.add('anchor-intercept-click-listener');
			element.addEventListener('click', onAnchorClick);
		}
// 2021-08-14: Don't preload the HTML of the <MAIN> element unless session
// storage is present:

		if (window.sessionStorage) {

// 2021-11-29
// Exclude anchors on site map page:
			if (!element.matches('.site-map a')) {

// Ensure ajaxURL isn't excluded from mouseover preload.
// Blacklist stored in noMouseoverPreload[] in siteData.mjs.
// Do not perform ajax if found in array.

// 2022-03-21:
// mouseover causes more problems than it solves:
				mouseoverPreload = false;

				if (o.siteData.noMouseoverPreload) {
// The URLS in noMouseoverPreload will be in the form:
// 'home/', 'various/', etc.
					o.siteData.noMouseoverPreload.forEach(function (url) {
						if (element.href.endsWith(url)) {
							mouseoverPreload = false;
						}
					});
				}
				if (mouseoverPreload) {
					if (!element.classList.contains('anchor-mouseover-listener')) {
						element.classList.add('anchor-mouseover-listener');
						element.addEventListener('mouseover', onAnchorClick);
					}
				}
			}
		}
	});

};

aspectRatioImgEdits = function (document, selector) {

// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
	var o = this;

// When using a variable, escape any forward slashes that might be in the text:
	document.querySelectorAll(o.escapeForwardSlashes(selector)).forEach(function (element) {
		var aspectWidth;
		var aspectHeight;
		var enclosingDiv;
		var ratioClass;

		if (element.dataset.aspectWidth) {
			aspectWidth = element.dataset.aspectWidth;
		} else {
			aspectWidth = 1;
			console.log('ERROR: aspectWidth not found in dataset of element.', element);
		}

		if (element.dataset.aspectHeight) {
			aspectHeight = element.dataset.aspectHeight;
		} else {
			aspectHeight = 1;
			console.log('ERROR: aspectHeight not found in dataset of element.', element);
		}

		enclosingDiv = element.closest('.aspect-ratio');
		if (enclosingDiv) {
			enclosingDiv.classList.add('ratio-' + aspectWidth + 'x' + aspectHeight);
		}

		ratioClass = '.ratio-' + aspectWidth + 'x' + aspectHeight;

		o.appendToCSS(ratioClass, '{ --aspect-ratio-w: ' + aspectWidth + '; --aspect-ratio-h: ' + aspectHeight + '; }');

	});
};

calledCommonVariables = false;

camelCaseToKebobCase = function (str) {
	return str.replace(/([a-zA-Z])(?=[A-Z])/g, '$1-').toLowerCase();
};

checkTrustedTypesSupport = function () {

	var o;
	var metaNameWebAuthor;
	var useTrustedTypes;

	o = this;

	metaNameWebAuthor = document.querySelector('meta[name=web_author]');
	useTrustedTypes = (metaNameWebAuthor && metaNameWebAuthor.dataset && metaNameWebAuthor.dataset.useTrustedTypes && (metaNameWebAuthor.dataset.useTrustedTypes === 'true'));

// Because we need the tmbTT object in the loadLocalResource(), we must have this
// object available in this module as one of its own properties:
	if (window.hasOwnProperty('trustedTypes') && useTrustedTypes) {
		o.tmbTT.policy = window.trustedTypes.createPolicy('stop-gap', {
			createHTML: function (html) {
				return html;
			},
			createScript: function (script) {
				return script;
			},
			createScriptURL: function (url) {
				return url;
			}
		});
		o.tmbTT.active = true;
		o.tmbTT.trusted = '';
		o.tmbTT.untrusted = '';
	} else {
		o.tmbTT.active = false;
	}

};



// Close the drawer when an anchor is clicked (but not the links that merely
// toggle the sides):
closeDrawerOnAnchorClick = function () {

// SAVE:
// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
//	var o = this;

	function internalAnchorClick() {
		var mediaQueryString;
		var mqList;
		mediaQueryString = '(max-width: 759px)';
		mqList = window.matchMedia(mediaQueryString);
		if (mqList.matches === true) {
			window.setTimeout(function () {
				document.querySelectorAll('#hamburger').forEach(function (hamburger) {
					hamburger.click();
				});
			}, 500);
		}
	}

// The selector MUST be a.internal-anchor because the home button is duplicated
// as a DIV as well as an A, so only listen on the anchors!

	document.querySelectorAll('.nav a.internal-anchor').forEach(function (element) {
		if (!element.classList.contains('drawer-internal-anchor-click-listener')) {
			element.classList.add('drawer-internal-anchor-click-listener');
			element.addEventListener('click', internalAnchorClick);
		}
	});

};

closeDrawerOnMobileLogoClick = function () {

// SAVE:
// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
//	var o = this;

	function mobileLogoAnchorClick() {
		var mediaQueryString;
		var mqList;
		mediaQueryString = '(max-width: 759px)';
		mqList = window.matchMedia(mediaQueryString);
		if (mqList.matches === true) {
			window.setTimeout(function () {
				document.querySelectorAll('#hamburger').forEach(function (hamburger) {
					if (hamburger.checked) {
						hamburger.click();
					}
				});
			}, 500);
		}
	}

	document.querySelectorAll('.header .logo-for-mobile-menu').forEach(function (element) {
		if (!element.classList.contains('mobile-logo-anchor-click-listener')) {
			element.classList.add('mobile-logo-anchor-click-listener');
			element.addEventListener('click', mobileLogoAnchorClick);
		}
	});

};

inEu = false;

commonRoutines = function () {

	var o;
	var remainingRoutines;

// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
	o = this;

// The 'remaining routines' are the functions that may only be called after
// the inner() function inside o.commonRoutinesOnFirstLoadOrAjax() is finished,
// which is complicated by the use of promises within its inner() function.
// Only after the promises have been fulfilled should the 'remaining routines'
// be run, so the custom event 'innerFunctionFinished' is issued at the end of
// inner(). This will in turn cause the remainingRoutines() function to run:

	remainingRoutines = function () {

// See:
// https://philipwalton.com/articles/idle-until-urgent/
// Prevent long-running tasks by using setTimeout() to break tasks up:
		window.setTimeout(function () {

			var adminMode;
			var appendGoogleScript;
			var clientIp;
			var gtagConfig;
			var initializeAnalytics;
			var lighthouseAudit;
			var manageErrors;
			var onLiveSite;
			var proceed;
			var request;

			window.dataLayer = window.dataLayer || [];

			o.commonRoutinesOnFirstLoadOnly();

			window.removeEventListener('innerFunctionFinished', remainingRoutines);

			window.requestAnimationFrame(function () {
				document.body.focus();
			});

// If a service worker is NOT controlling the site, then initialize it:
			if (navigator.serviceWorker !== undefined) {
// If not under control of service worker:
				if (!navigator.serviceWorker.controller) {
					if (o.useServiceWorker) {
						o.serviceWorkerRoutine();
					}
				} else {
					console.log('Service Worker active.' + '\n' + '(Hard refresh for new Service Worker.)');
				}
			}

			appendGoogleScript = function (srcAttribute) {

				var fragment;
				var bodyElement;
				var scriptElement;

				bodyElement = document.querySelector('BODY');
// Create fragment:
				fragment = document.createDocumentFragment();
// Create SCRIPT element to be inserted into the DOM:
				scriptElement = document.createElement('SCRIPT');
// Append it to the fragment:
				fragment.appendChild(scriptElement);
// Modify the properties of the element now that it's attached to the fragment:
// Do NOT use crossOrigin: 'anonymous'
				scriptElement.setAttribute('src', srcAttribute);
				scriptElement.setAttribute('defer', '');
// Append the fragment to the DOM at the desired location; the fragment itself
// disappears and its children are what is actually appended:
				bodyElement.appendChild(fragment);

			};

			o.gtag = function () {
				window.dataLayer.push(arguments);
			};

			initializeAnalytics = function () {

// 1: Google Tag Manager:
// NB: This must come before the gtag routine
				tmbTT.untrusted = 'https://www.googletagmanager.com/gtag/js?id=' + o.siteData.uaString;
				if (tmbTT.active) {
					tmbTT.trusted = tmbTT.policy.createScriptURL(tmbTT.untrusted);
					appendGoogleScript(tmbTT.trusted);
				} else {
					appendGoogleScript(tmbTT.untrusted);
				}
// 2: Google Universal Analytics:
// To see tracking in real time for shell.com:
// https://analytics.google.com/analytics/web/#/realtime/rt-overview/###################/
				o.gtag('js', new Date());
// Firefox Developer Edition will throw error:
//
// Some cookies are misusing the recommended “SameSite“ attribute 3
// Cookie “_ga” will be soon rejected...
// Cookie “_gid” will be soon rejected...
// Cookie “_gat_gtag_UA_#########_1” will be soon rejected...
// ...because it has the “SameSite” attribute set to “None” or an invalid
// value, without the “secure” attribute. To know more about the “SameSite“
// attribute, read:
// https://developer.mozilla.org/docs/Web/HTTP/Headers/Set-Cookie/SameSite
//
// https://stackoverflow.com/questions/62569419/how-to-set-secure-attribute-of-the-cookies-used-by-google-analytics-global-sit
				gtagConfig = {
					'anonymize_ip': true,
					'cookie_flags': 'SameSite=None; Secure',
					'page_path': window.location.pathname
				};
				o.gtag('config', o.siteData.uaString, gtagConfig);
// Initialize Google analytics ONCE ONLY
				o.googleAnalyticsInitialized = true;
			};


			adminMode = document.body.hasAttribute('data-admin');

			lighthouseAudit = (o.metaNameWebAuthor && o.metaNameWebAuthor.dataset && o.metaNameWebAuthor.dataset.chromeLighthouse);

			onLiveSite = ((o.siteData && o.siteData.liveSiteUrl) && (window.location.origin === o.siteData.liveSiteUrl));

/*
//
// Keep for testing!
//
adminMode = false;
lighthouseAudit = false;
onLiveSite = true;
// Sample EU IP:
o.metaNameWebAuthor.dataset.clientIp = '40.119.158.195';
// My IP:
o.metaNameWebAuthor.dataset.clientIp = '##.##.###.##';
*/

			manageErrors = function (response) {
				var responseError;
				if (!response.ok) {
					responseError = {
						url: response.url,
						statusText: response.statusText,
						status: response.status
					};
					throw (responseError);
				}
				return response;
			};

// 2023-02-14
			if (o.siteData.useGoogleAnalytics) {

// Run only if NOT during lighthouse audit:
				if (!adminMode && !lighthouseAudit && onLiveSite && navigator.onLine && !o.googleAnalyticsInitialized) {
// Run the following only outside the EU:
					if (o.metaNameWebAuthor && o.metaNameWebAuthor.dataset && o.metaNameWebAuthor.dataset.clientIp) {
						clientIp = o.metaNameWebAuthor.dataset.clientIp;

// proceed with ipapi lookup and analytics unless IP is in excluded array:
						proceed = true;
						if (o.hasOwnProperty('siteData')) {
							if (o.siteData.hasOwnProperty('excludedIps')) {
								if (o.siteData.excludedIps.includes(clientIp)) {
									proceed = false;
								}
							}
						}

// https://ipapi.co/40.119.158.195/json

						if (proceed) {
							if ((o.siteData.hasOwnProperty('ipapiLookup')) && (o.siteData.ipapiLookup === true)) {
// The call to ipapi.co (e.g., https://ipapi.co/00.00.000.00/json) returns
// an Expect-CT header, which produces a Deprecated Feature Used warning in DevTools:
								request = 'https://ipapi.co/' + clientIp + '/json';

// Test using this url, using different response codes for endpoints:
// https://httpstat.us is allowed in our connect-src CSP:
//request = 'https://httpstat.us/503';

// Assume failure...that the client *will* be in the EU:
								o.inEu = true;

// See:
// https://towardsdev.com/how-to-handle-404-500-and-more-using-fetch-api-in-javascript-f4e301925a51

								fetch(request).then(manageErrors).then(
									function (response) {
										return response.json();
									}
								).then(function (data) {
									if (data.error) {
										console.log(data);
									} else {
										o.inEu = data.in_eu;
									}
								}).catch(function (error) {
									o.inEu = error;
								}).finally(function () {
	// if we're in the EU, do NOT initialize analytics:
									if (o.inEu === true) {
										console.log('EU IP detected: no analytics performed');
									} else {
										console.log('Non-EU IP: initialize analytics');
										initializeAnalytics();
									}

								});
							} else {
								console.log('Initialize analytics');
								initializeAnalytics();
							}
						}
					}
				}
			}

			o.deactivateLoadingMask();

		}, 0);

	};

// 'innerFunctionFinished' is emitted in commonRoutinesOnFirstLoadOrAjax();
	window.addEventListener('innerFunctionFinished', remainingRoutines);

//window.addEventListener('ajaxResponse', ajaxEvent);

// 2022-03-20:
	o.checkTrustedTypesSupport();

// Now proceed with the actual common routines:
	o.commonRoutinesOnFirstLoadOrAjax();

};

// PAGES: GALLERY
// This is run AFTER commonRoutinesOnFirstLoadOrAjax, and on the first load
// only:
commonRoutinesOnFirstLoadOnly = function () {

	var callSetOrientation;
	var o;


// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
	o = this;

// qwer:htaccess
	o.URL = window.location.href;

// Without useSPA, the site defaults to standard, non-ajax navigation:

// o.anchorIntercept() Moved to commonEventListeners(), which is called from
// commonRoutinesOnFirstLoadOrAjax():
//
// We need to add click listeners on the gallery image links on the home page
// every time we return to it, as they are not present when the MAIN content is
// loaded:

	o.makeTransparentMaskClickable();
	o.closeDrawerOnAnchorClick();
	o.closeDrawerOnMobileLogoClick();
	o.noTitle('.footer .no-title');
	o.initializeWindowOnResizeRoutines();
	o.popstateListener();

// If the initial URI has a hash, then adjust for header:
// https://localhost/shell/about/#hashed-section-on-page

	if (o.fenestra.location.hash !== '') {
// When the SPA is not in force, a history.length of 2 is the only indicator I
// could find to determine if the user had come directly to a hashed URL,
// which requires headerHeight adjustment, oddly.
		if (o.useSpa || (!o.useSpa && (window.history.length === 2))) {

// 2022-07-31:
			o.scrollDownByHeaderHeight();

		}
	}

// Keep this routine in case it proves useful in the future:
	callSetOrientation = false;
	if (callSetOrientation) {
		o.setOrientation();
	}

// 2021-11-04:
// Initialized to true in siteWideEditsClosure() in siteWideEditsClosure.mjs.

	o.firstLoad = false;

// asdf
// 2023-11-15
// Put in main() of each page mjs file so that any style edits performed by
// those pages are performed before the opacity is returned to 1:
//	o.appendToCSS(':root', '{ --main-opacity: 1; }');

// This works on first load without an issue; there is no flash of the footer in the viewport:
	o.appendToCSS(':root', '{ --footer-opacity: 1; }');

// This allows the 404 page (i.e., the artwork/not-found content)
// to display when backbuttoning to the errant URL:
	window.addEventListener('http404', function () {
		var backbutton;
		var eventType;
		var hrefText;
// qwer:htaccess2
		var stateObject;
		var target;

//console.log('--- start http404 callback');
		hrefText = 'error/';

// qwer:htaccess2
		stateObject = {};
		stateObject.title = 'Error';
		stateObject.url = o.URL;
		window.history.pushState(stateObject, stateObject.title, stateObject.url);

// qwer:htaccess2
// 2023-10-29:
// Needed for new directory structure, with site pages in /pages/ folder:
//		target = o.baseHref + 'error/main.php';
		target = o.baseHref + 'pages/error/main.php';

// IMPORTANT: by setting backbutton to true, we don't get a history.pushState()
// action in ajaxMainContent. This seems to be the only way to prevent it (other
// methods have failed, reason unknown)
		backbutton = true;
		eventType = 'click';
		o.ajaxMainContent(hrefText, target, backbutton, eventType);

// qwer:htaccess
// Needed to be able to backbutton through 404 and 403 pages
// This clears double entries in the history, made apparently
// when the ajaxing resulted in 404/403 etc.
// There MUST be a better way to prevent this, but I could not find one:
		if (o.backbutton === true) {
			window.history.back();
		}
//console.log('--- end http404 callback');

	});

/* To delay the switch from position: fixed to position: absolute in the secondary-ul CSS: */
// To listen for checked state:
	document.querySelectorAll('#hamburger').forEach(function (hamburger) {
		if (!hamburger.classList.contains('hamburger-change-listener')) {
			hamburger.classList.add('hamburger-change-listener');
			hamburger.addEventListener('change', function () {
				if (hamburger.checked) {
// Hamburger open:
// In the corresponding CSS, apply a transition-delay of 100ms to NAV to give
// time for this rule to be appended and rendered:
					o.appendToCSS(':root', '{ --secondary-ul-position: fixed; }');
// deadlandwoods:
					o.appendToCSS(':root', '{ --header-shadow-filter-value: none; }');
					o.appendToCSS(':root', '{ --header-background-color: unset; }');
				} else {
// Hamburger close:
// The time delay must equal the duration of the translateX transform on the
// NAV element in the corresponding CSS (280ms):
					window.setTimeout(function () {
						o.appendToCSS(':root', '{ --secondary-ul-position: absolute; }');
// deadlandwoods:
						o.appendToCSS(':root', '{ --header-shadow-filter-value: drop-shadow(0 4mm 6mm #680000); }');
						o.appendToCSS(':root', '{ --header-background-color: #7c0000; }');
					}, 280);
				}
			});
		}
	});

	o.initializeHeaderHeightAndObserver();
	o.footerEdits();
	o.initializeFooterHeightAndObserver();

};

// Run these before commonRoutinesOnFirstLoadOnly()
commonRoutinesOnFirstLoadOrAjax = function () {

	var o;

// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
	o = this;
	window.setTimeout(function () {

// 2021-04-04
// inner function used to be here.
// loadPageDependencies function used to be here.
// promiseLoader function used to be here.

// Here is where we need to load the page-specific modules.
//
// This function (o.commonRoutinesOnFirstLoadOrAjax) is called by the
// ajaxResponse() function in the o.ajaxMainContent() function, but
// no page-specific functions are called within it.
//
// Determine the site page by looking at the class on the MAIN
// element, then load the corresponding page-module.
		document.querySelectorAll('main').forEach(function (element) {

			o.previousPageName = o.pageName;
			o.pageName = element.getAttribute('data-page');
			if (o.pageName) {
				o.pageNameCamelCase = kebobCaseToCamelCase(o.pageName);
			} else {
				console.log('ERROR: MAIN element needs page name');
				o.pageName = 'dummy';
				o.pageNameCamelCase = 'dummy';
			}

			if (o.loadedDependencies[o.pageName] === undefined) {
				o.loadedDependencies[o.pageName] = false;
			}

		});
// 2021-04-06
		if (o.hasOwnProperty('siteData')) {
			if (o.siteData.hasOwnProperty('pageDependencies')) {
				o.loadPageDependencies();
			}
		}

	}, 0);

};

commonEventListeners = function () {

	var hamburgerLabelClick;
	var mainElement;
	var o;
	var onKeydown;
	var mainContentID;
	var skipToMainContentKeyupListener;

// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
	o = this;

	mainContentID = '#main-content';
	skipToMainContentKeyupListener = 'skip-to-main-content-keyup-listener';

// For testing and debugging tabbing/entering drawer items:
// document.body.addEventListener('keyup', function (e) {
//	 console.log('active element: ', document.activeElement);
// });


// toggle useServiceWorker:
	onKeydown = function (zEvent) {
		if (zEvent.ctrlKey && zEvent.altKey && zEvent.key === 's') {
			console.log('Ctrl+Alt+s: Delete caches, unregister service worker, clear session storage');
			o.deleteCachesUnregisterServiceWorkerAndClearSessionStorage();
		}
		if (zEvent.ctrlKey && zEvent.altKey && zEvent.key === 'l') {
			console.log('Ctrl+Alt+l: Go to login');
			location.replace(o.baseHref + 'login');
		}
	};

// GALLERY
	hamburgerLabelClick = function () {
		var match;
		var pageSlug;

// To see if we're on a secondary UL page, check for .secondary-ul.selected:

		document.querySelectorAll('.secondary-ul.selected').forEach(function (ul) {
// The selected anchor within the UL will have .selected
			ul.querySelectorAll('a.selected').forEach(function (a) {
// Its data-page will be the page-slug, without a trailing slash:
				pageSlug = a.parentElement.dataset.page;
			});
		});

// The MAIN element will have the page slug as one of its classes.
// If there's a match between them, we need to display the corresponding
// secondary menu when opening it with the hamburger:
		match = false;
		mainElement = document.querySelector('MAIN');
		if (pageSlug && mainElement.classList.contains(pageSlug)) {
			match = true;
		}

// Select all of the side-switchers:
		document.querySelectorAll('[id^=side-switcher]').forEach(function (sideSwitcher) {
// assume that none of them should be checked (thus clearing out old check, if present):
			sideSwitcher.checked = false;
			if (match) {
// But if we have a match, determined above, check the sideSwitcher that is a sibling
// to the selected secondary UL:
				if (sideSwitcher.parentElement.querySelector('#' + sideSwitcher.id + ' ~ .secondary-ul.selected')) {
					sideSwitcher.checked = true;
				}
			}
		});


	};

// To show the blue focus ring around tabbable anchors ONLY when tabbing:
	document.addEventListener('keydown', function (e) {
		if (e.code === 'Tab') {
			document.body.classList.add('show-focus-outlines');
		}
	});
// To revert back to standard behavior:
	document.addEventListener('click', function (ignore) {
		document.body.classList.remove('show-focus-outlines');
	});


	if (o.windowEvents.keydown === false) {
		window.addEventListener('keydown', onKeydown);
		o.windowEvents.keydown = true;
	}

// Ensure the correct side of the drawer is displayed based on which page is in view:
	document.querySelectorAll('label[for=hamburger]').forEach(function (element) {
		if (!element.classList.contains('label-for-hamburger-click-listener')) {
			element.classList.add('label-for-hamburger-click-listener');
			element.addEventListener('click', hamburgerLabelClick);
		}
	});

// When hamburger is first clicked, the tabindex on the items active side
// should be set to 0 (initially, they're all set to -1, i.e., inactive)
//
// .menu-side-one should show when it is being tabbed
// .menu-side-two should show when it is being tabbed
//
// Each side should be accessible to tabbing only after the LABEL list item is
// ENTERED
// This may require toggling the tabindex of each side on and off
// The mechanism should continue to work if the user switches between tabbing
// and clicking with the mouse.

// When .header-mobile is on the HEADER element, place the initial focus on
// either the hamburger or the element before the "Skip to main content" link.
// That should be done in the corner of the code handling what to do when the
// page first loads (via SPA mechanism or not); it doesn't belong in this
// routine:

// Also, use same strategy as found here to ensure duplicate event listeners
// are not added to the same element, elsewhere in the code (perhaps in
// JSCarousel, too).

//////////////////////////
// SKIP TO MAIN CONTENT //
//////////////////////////

	document.querySelectorAll('#skip-to-main-content').forEach(function (element) {
		var windowLocationHref = window.location.href;
// Add '#main-content' to URL unless it's already there:
		if ((windowLocationHref.slice(windowLocationHref.length - mainContentID.length)) !== mainContentID) {
			element.setAttribute('href', window.location.href + mainContentID);
		}
		if (!element.classList.contains(skipToMainContentKeyupListener)) {
			element.classList.add(skipToMainContentKeyupListener);
			element.addEventListener('keyup', function (e) {
				e.preventDefault();
				if (e.code === 'Enter') {
					document.querySelectorAll(mainContentID).forEach(function (mainContent) {
						mainContent.focus();
// close the drawer if skipping to the main content:
						document.getElementById('hamburger').checked = false;
					});
				}
			});
		}
	});

///////////////
// HAMBURGER //
///////////////

	document.querySelectorAll('label[for=hamburger]:not(.keyup-listener)').forEach(function (element) {

		element.classList.add('keyup-listener');
		element.addEventListener('keyup', function (e) {
//console.log('hamburger: keyup -----');
// Enter key:
			if (e.code === 'Enter') {
//console.log('Enter');
				e.preventDefault();
				if (o.maxWidth759px) {
//console.log('menu-side-one ALL LI: tabIndex = 0');
					document.querySelectorAll('.header .menu-side-one').forEach(function (element) {
						element.tabIndex = 0;
					});
//console.log('menu-side-two ALL LI: tabIndex = -1');
					document.querySelectorAll('.header .menu-side-two').forEach(function (element) {
						element.tabIndex = -1;
					});
				}
//console.log('click');
				document.querySelectorAll('#hamburger').forEach(function (hamburger) {
					hamburger.click();
				});
			}
//console.log('hamburger: keyup ^^^^^');
		});
	});

///////////////////
// MENU SIDE ONE //
///////////////////

	document.querySelectorAll('.menu-side-one').forEach(function (li) {
		if (!li.classList.contains('keyup-listener')) {
			li.classList.add('keyup-listener');

// keyup events on the internal UL LI should not be registered on the "See the Art" LI that contains them!

			li.addEventListener('keyup', function (e) {
				if (!e.target.classList.contains('menu-side-two')) {
//console.log('menu-side-one li: keyup -----');
					e.preventDefault();
// Tab key:
					if (e.code === 'Tab') {
//console.log('Tab');
						if (o.maxWidth759px) {
							document.querySelectorAll('#hamburger').forEach(function (hamburger) {
								hamburger.checked = true;
//console.log('#hamburger unchecked');
							});
							document.querySelectorAll('[id^=side-switcher]').forEach(function (sideSwitcher) {
//console.log('#side-switcher unchecked');
								sideSwitcher.checked = false;
							});
						}
					}
// Escape key:
					if (e.code === 'Escape') {
//console.log('Escape');
						document.querySelectorAll('#hamburger').forEach(function (hamburger) {
//console.log('#hamburger click');
							hamburger.click();
						});
// NB: The BODY element must have 'tabfocus=0' for this to work:
//console.log('body focus');
						document.querySelector('body').focus();
					}
// Enter key:
					if (e.code === 'Enter') {
//console.log('Enter');
// If the first child is an anchor:
						if (li.firstElementChild.tagName === 'A') {
//console.log('a click');
							li.firstElementChild.click();
// If the first child is input#side-switcher:
//						} else if (li.firstElementChild.id === 'side-switcher') {
						} else if (li.firstElementChild.id.startsWith('side-switcher')) {
//console.log('#side-switcher: REVERSE CHECKED STATE');
							li.firstElementChild.checked = !li.firstElementChild.checked;
// The data-switcher-target attribute will have the name of the switcher target ('Main Menu' or 'See the Art');
							document.querySelectorAll('.header .nav li[data-span="' + li.firstElementChild.dataset.switcherTarget + '"]').forEach(function (target) {
//console.log(li.firstElementChild.dataset.switcherTarget + ': click, focus; #side-switcher: blur');
// #side-swicher:
								e.target.blur();
// "Main Menu" DIV or "See the Art" DIV:
								target.click();
								target.focus();
							});
// reverse all tab indexes:
//console.log('REVERSE ALL TABINDEXES ON BOTH SIDES');
							document.querySelectorAll('.header .nav ul li[tabindex]').forEach(function (item) {
								if (item.tabIndex === 0) {
									item.tabIndex = -1;
								} else {
									item.tabIndex = 0;
								}
							});
						}
					}
//console.log('menu-side-one li: keyup ^^^^^');
				}
			});
		}
	});

///////////////////
// MENU SIDE TWO //
///////////////////

	document.querySelectorAll('.menu-side-two').forEach(function (li) {
		if (!li.classList.contains('keyup-listener')) {
			li.classList.add('keyup-listener');
			li.addEventListener('keyup', function (e) {
				if (!e.target.classList.contains('menu-side-one')) {
//console.log('menu-side-two li: keyup -----');
					e.preventDefault();
// Tab key:
					if (e.code === 'Tab') {
//console.log('Tab');
						if (o.maxWidth759px) {
							document.querySelectorAll('#hamburger').forEach(function (hamburger) {
								hamburger.checked = true;
							});
							document.querySelectorAll('[id^=side-switcher]').forEach(function (sideSwitcher) {
//console.log('#side-switcher checked');
								sideSwitcher.checked = true;
							});
						}
					}
// Escape key:
					if (e.code === 'Escape') {
//console.log('Escape');
						document.querySelectorAll('#hamburger').forEach(function (hamburger) {
//console.log('#hamburger click');
							hamburger.click();
						});
// NB: The BODY element must have 'tabfocus=0' for this to work:
//console.log('body focus');
						document.querySelector('body').focus();
					}
// Enter key:
					if (e.code === 'Enter') {
//console.log('Enter');
// If the first child is an anchor:
						if (li.firstElementChild.tagName === 'A') {
//console.log('a click');
							li.firstElementChild.click();
// If the first child is input#side-switcher:
						} else if (li.firstElementChild.matches('div[data-for^=side-switcher]')) {
//console.log('#side-switcher: REVERSE CHECKED STATE');
							document.querySelectorAll('[id^=side-switcher]').forEach(function (sideSwitcher) {
								sideSwitcher.checked = !sideSwitcher.checked;
							});
// The data-switcher-target attribute will have the name of the switcher target ('Main Menu' or 'See the Art');
							document.querySelectorAll('.header .nav li[data-span="' + li.firstElementChild.dataset.switcherTarget + '"]').forEach(function (target) {
//console.log(li.firstElementChild.dataset.switcherTarget + ': click, focus; #side-switcher: blur');
// #side-swicher:
								e.target.blur();
// "Main Menu" DIV or "See the Art" DIV:
								target.click();
								target.focus();
							});
// reverse all tab indexes:
//console.log('REVERSE ALL TABINDEXES ON BOTH SIDES');
							document.querySelectorAll('.header .nav ul li[tabindex]').forEach(function (item) {
								if (item.tabIndex === 0) {
									item.tabIndex = -1;
								} else {
									item.tabIndex = 0;
								}
							});
						}
					}
//console.log('menu-side-two li: keyup ^^^^^');
				}
			});
		}
	});

// Specific to side-switcher in header:
//
// To satisfy accessibility requirements:
// * I can't have two LABEL elements that reference the same INPUT element.
// * Using 'aria-labelledby' is meant to reference non-LABEL elements.
// * I can't use a custom element, because the Wiersch Validator can't
// associate the 'aria-labelledby' values on the INPUT with the IDs on the
// custom element.
// * Lastly, 'for' can only be used on LABELs.
//
// So, I'm using a DIV with a custom attribute of 'data-for=side-switcher'
// to reference the #side-switcher ID of the INPUT element.
//
// NB: different kinds of listeners are being tested for:
// mouse-clicks: .side-switcher-click-listener
// tabbing: side-switcher-keyup-listener

////////////////////
// SIDE SWITCHERS //
////////////////////

	document.querySelectorAll('div[data-for^=side-switcher]').forEach(function (element) {
// For mouse-clicks
		if (!element.classList.contains(element.dataset.for + '-click-listener')) {
			element.classList.add(element.dataset.for + '-click-listener');
			element.addEventListener('click', function (ignore) {
//console.log('div[data-for=side-switcher]: click -----');
// Find the element whose ID matches the 'data-for' attribute of the side-switcher DIV:
				document.querySelectorAll('#' + element.dataset.for).forEach(function (sideSwitcher) {
					sideSwitcher.click();
				});
//console.log('div[data-for=side-switcher]: click ^^^^^');
			});
		}
// For entering:
/*
		if (!element.classList.contains('side-switcher-keyup-listener')) {
			element.classList.add('side-switcher-keyup-listener');
*/
		if (!element.classList.contains(element.dataset.for + '-keyup-listener')) {
			element.classList.add(element.dataset.for + '-keyup-listener');

			element.addEventListener('keyup', function (e) {
//console.log('div[data-for=side-switcher]: keyup -----');
// Enter key:
				if (e.code === 'Enter') {
//console.log('Enter');
					if (o.maxWidth759px) {
						document.querySelectorAll('.header .menu-side-one').forEach(function (element) {
							if (element.tabIndex === 0) {
//console.log('menu-side-one tabIndex = -1');
								element.tabIndex = -1;
							} else {
//console.log('menu-side-one tabIndex = 0');
								element.tabIndex = 0;
							}
						});
						document.querySelectorAll('.header .menu-side-two').forEach(function (element) {
							if (element.tabIndex === 0) {
//console.log('menu-side-two tabIndex = -1');
								element.tabIndex = -1;
							} else {
//console.log('menu-side-one tabIndex = 0');
								element.tabIndex = 0;
							}
						});
					}
					document.querySelectorAll('[id^=side-switcher]').forEach(function (sideSwitcher) {
						sideSwitcher.click();
					});
				}
//console.log('div[data-for=side-switcher]: keyup ^^^^^');
			});
		}
	});

// We need to add click listeners on the gallery image links
// on the home page every time we return to it, as they are not present
// when the MAIN content is loaded:

	if (navigator.onLine && o.useSpa) {
		o.anchorIntercept();
	}

};

commonVariables = function () {

// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
	var o = this;

	o.calledCommonVariables = true;

	o.ajax = o.returnAjaxObject();

// update global baseHref:
// 2023-10-17
// Need fallback in case base href is not found...put in siteData?
// MAKE INTO UTILITY FUNCTION
	document.querySelectorAll('base').forEach(function (element) {
		if (element.hasAttribute('href')) {
			o.baseHref = element.getAttribute('href');
		} else {
			console.log('ERROR: Could not find BASE href');
		}
	});

	o.customEvent = [];

// SAVE:	o.isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);

	o.lazyloadDefaultOffsetPercent = '80%';

// NB: metaNameWebAuthor is defined in siteWideEditsClosure.mjs main()

	if (o.metaNameWebAuthor && o.metaNameWebAuthor.dataset) {
		if (o.metaNameWebAuthor.dataset.rootDir) {
			o.metaDataRootDir = o.metaNameWebAuthor.dataset.rootDir;
		} else {
			o.metaDataRootDir = '/';
		}
		if (o.metaNameWebAuthor.dataset.webpSupport) {
			o.metaDataWebpSupport = (o.metaNameWebAuthor.dataset.webpSupport === 'true');
		} else {
			o.metaDataWebpSupport = false;
		}
		if (o.metaNameWebAuthor.dataset.useSpa) {
			o.metaDataUseSpa = (o.metaNameWebAuthor.dataset.useSpa === 'true');
		} else {
// default to using SPA
			o.metaDataUseSpa = true;
		}
		if (o.metaNameWebAuthor.dataset.useServiceWorker) {
			o.metaDataUseServiceWorker = (o.metaNameWebAuthor.dataset.useServiceWorker === 'true');
		} else {
// Toggle during development:
// default to using Service Worker
			o.metaDataUseServiceWorker = true;
//			o.metaDataUseServiceWorker = false;
		}

	}

// turn off o.useSpa during INITIAL development:
	o.useSpa = o.metaDataUseSpa;
	o.safari = o.isSafari();


// 2021-03-14: the meta tag data-use-service-worker is controlled by
// the $use_service_worker variable in includes/common_variables/variables.php
// It's been toggled off because of the looming Lighthouse audit (scheduled
// for Chrome 93, August 2021) that will reject Service Worker sites that are
// not installable by the test. So, for now, o.metaDataUseServiceWorker will
// evaluate to false to prevent the service worker from installing. The code
// that installs it is fine and should remain until the installation issue can
// be resolved:

	if ((o.safari === true) || (o.metaDataUseServiceWorker === false)) {
		o.useServiceWorker = false;
		if (o.safari === true) {
			console.log('Safari detected.');
		}
		o.deleteCachesUnregisterServiceWorkerAndClearSessionStorage();
	} else {
// 2020-05-20: turn off service worker for testing only:
		o.useServiceWorker = true;
	}

};

consoleLog = function (msg) {

// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
	var o = this;

	if (o.logToConsole) {
		console.log(msg);
	}

};


// This is meant for image links in the ekphrases, not for other kinds of images.
// It will affect image displayed on mobile devices, in which we're not using
// the iframe previewer.
//
// NB: This is risky. It assumes that every JPG in the assets/images/various directory
// has a corresponding WEBP. What to do if the resource isn't there?

conditionallyReplaceJpgExtensionWithWebpExtension = function (document, selector) {

// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
	var o = this;

	if (o.metaDataWebpSupport) {
// When using a variable, escape any forward slashes that might be in the text:
		document.querySelectorAll(o.escapeForwardSlashes(selector)).forEach(function (anchor) {
			if (anchor.href.endsWith('.jpg')) {
				anchor.href = anchor.href.replace('.jpg', '.webp');
			}
		});
	}

};

consoleLogMsgObj = function (msg, obj) {

// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
	var o = this;

	if (o.logToConsole) {
		console.log(msg, obj);
	}

};

// SEE: https://stackoverflow.com/questions/6390341/how-to-detect-url-change-in-javascript
//
// This is called by initializePopstateLocationChangeListeners(), which is called by
// siteWideEditsClosure.mjs.
//
// Create a custom 'locationchange' event for every pushstate and replacestate event:
customizePushAndReplaceState = function () {

//console.log('customizePushAndReplaceState');

// SAVE:
// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
//	var o = this;

	window.history.pushState = (function (f) {
		return function pushState() {
			var ret = f.apply(this, arguments);
			window.dispatchEvent(new Event('pushState'));
			window.dispatchEvent(new Event('locationchange'));
			return ret;
		};
	}(window.history.pushState));

	window.history.replaceState = (function (f) {
		return function replaceState() {
			var ret = f.apply(this, arguments);
			window.dispatchEvent(new Event('replaceState'));
			window.dispatchEvent(new Event('locationchange'));
			return ret;
		};
	}(window.history.replaceState));

// qwer:htaccess
/*
window.addEventListener('pushState', function () {
	console.log('EVENT: pushState');
});
window.addEventListener('replaceState', function () {
	console.log('EVENT: replaceState');
});
window.addEventListener('locationchange', function () {
	console.log('EVENT: locationchange');
});
*/

};

deactivateLoadingMask = function () {

	var config;
	var o;
	var observer;

// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
	o = this;

//console.log('deactivateLoadingMask');

// 2021-11-06:
// Added .active to the querySelector, since we don't need to do what's
// in the function unless the mask is active:
	document.querySelectorAll('.loading-mask.active').forEach(function (mask) {

// Removing the .active class on the loading-mask DIV will:
// 1) Hide the loading mask
// 2) Trigger the mutation observer to run the callback function, which sets
// the opacity to 1 on the main.PAGENAME on all public-facing pages:

		observer = new MutationObserver(function (ignore) {
			o.appendToCSS(':root', '{ --custom-style-elements-opacity: 1; }');
			observer.disconnect();
		});

		config = {attributes: true};
		observer.observe(mask, config);

		mask.classList.remove('active');

//		mask.removeEventListener('animationstart', o.animationStart);
//		mask.removeEventListener('animationend', o.animationEnd);
//		mask.removeEventListener('animationiteration', o.animationIteration);


	});



};

deleteCachesUnregisterServiceWorkerAndClearSessionStorage = function () {

// SAVE:
// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
//	var o = this;

	console.log('Deleting caches, unregistering Service Worker, clearing session storage');

// NB: Safari has no window.caches
// delete caches:
	if (window.caches) {

// NB: window.caches.keys() will throw an error in Firefox:
// DOMException: The operation is insecure.
// Try at the Firefox console: console.log(window.caches.keys());
// There seems to be no way to suppress this.

		window.caches.keys().then(function (names) {
			Object.keys(names || {}).forEach(function (key) {
				window.caches.delete(names[parseInt(key, 10)]);
			});
		}).catch(function (error) {
			console.log('Caches deletion warning: ', error);
		});
	}

// unregister service worker:
	if (navigator.serviceWorker !== undefined) {
		navigator.serviceWorker.getRegistrations().then(function (registrations) {
			Object.keys(registrations || {}).forEach(function (key) {
				registrations[parseInt(key, 10)].unregister();
			});
		}).catch(function (error) {
			console.log('Service Worker de-registration failed: ', error);
		});
	}

// 2021-04-18:
// Clearing session storage:
	if (window.sessionStorage) {
		window.sessionStorage.clear();
	}
//
//	} else {
//		console.log('On live site');
//	}

};

// CLOSURE COMPILER EXPECTS ALL ARGUMENTS TO BE PROVIDED WHEN FUNCTION
// IS CALLED, UNLESS DEFAULT VALUES ARE PROVIDED IN FUNCTION DEFINITION,
// SIGH...

editExternalLinks = function (document, selector, setTitle, title) {

// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
	var o = this;

	if (title === true) {
		title = '(opens in new window/tab)';
	}

// When using a variable, escape any forward slashes that might be in the text:
	document.querySelectorAll(o.escapeForwardSlashes(selector)).forEach(function (element) {
		element.setAttribute('target', '_blank');
		element.setAttribute('rel', 'noopener noreferrer');
		if (setTitle) {
			element.setAttribute('title', title);
		}
	});

};

enqueueArray = [];

// NOTICE: When a page with the TmbAlert code is first loaded, there
// will be a brief flash of the alert component UNLESS it is hidden
// with {visibility: hidden} using CSS.
//
// This is distinct from the .tmb-alert-hidden CSS which is used
// to diplay and hide the alert during application use.

footerEdits = function () {

	var o;
	var removeSeparatorAtEndOfLine;

	o = this;

	removeSeparatorAtEndOfLine = function () {

		function routine() {
			var lastElement = false;
			document.querySelectorAll('.footer .site-links .internal-links a').forEach(function (element) {
				if (lastElement) {
					if (lastElement.offsetTop !== element.offsetTop) {
						lastElement.parentElement.classList.add('last-in-line');
					} else {
						lastElement.parentElement.classList.remove('last-in-line');
					}
				}
				lastElement = element;
			});
			if (lastElement) {
				lastElement.parentElement.classList.add('last-in-line');
			}
		}

		document.querySelectorAll('.footer .site-links .internal-links li').forEach(function (element) {
			element.classList.remove('last-in-line');
		});

		routine();

	};

	removeSeparatorAtEndOfLine();

	window.addEventListener('resize', o.debounce(removeSeparatorAtEndOfLine, 250, false));

};


removeVisibilityHiddenFromTmbAlert = function () {

// SAVE:
// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
//	var o = this;

	document.querySelectorAll('.tmb-alert.visibility-hidden').forEach(function (element) {
		element.classList.remove('visibility-hidden');
	});
};

escapeSingleQuotes = function (text) {
	return text.replace(/'/g, "\\'");
};

escapeForwardSlashes = function (text) {

// SAVE:
// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
//	var o = this;

	return text.replace(/\//g, '\\/');

};

fetchAppendToUploadStatusDiv = function (msg) {

	var o;
	var fragment;
	var pElement;

// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
	o = this;

	fragment = document.createDocumentFragment();
// Create P element to be inserted into the DOM:
	pElement = document.createElement('P');
// Append it to the fragment:
	fragment.appendChild(pElement);
// Modify the properties of the element now that it's attached to the fragment:

	if (tmbTT.active) {
		pElement.innerHTML = o.DOMPurify.sanitize(msg);
	} else {
		pElement.innerHTML = msg;
	}

	o.fetchUploadStatusDiv.appendChild(fragment);

};

fetchReject = function (reject) {

// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
	var o = this;

	o.fetchNoProblem = false;

	if (o.fetchProgressLine) {
		o.fetchProgressLine.classList.remove('active');
	}

	return (
// It may not be a promise...
		(reject.then)
		? reject.then(function (json) {
// Set the innerHTML of an error div to the error text returned:
			o.consoleLog(json.message);
			o.fetchAppendToUploadStatusDiv(json.message);
		})
		: null
	);

};

fetchResolve = function (resolve, url, msg) {

// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
	var o = this;

	o.consoleLogMsgObj('Success: ', resolve);
	o.consoleLog('');
// Set the innerHTML of a div to the msg text returned:
	o.consoleLog(msg);
	o.fetchAppendToUploadStatusDiv(msg);
	return (
		(url !== '')
		? fetch(url)
		: null
	);

};

fetchResolveWithOptions = function (resolve, url, options, msg) {

// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
	var o = this;

	o.consoleLogMsgObj('Success: ', resolve);
	o.consoleLog('');
// Set the innerHTML of a div to the msg text returned:
	o.consoleLog(msg);
	o.fetchAppendToUploadStatusDiv(msg);
	return (
		(url !== '')
		? fetch(url, options)
		: null
	);

};

fetchResponse = function (response) {

// SAVE:
// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
//	var o = this;

	return (
		(response.status !== 200)
// If an error occurs, return the json response in a Promise rejection:
		? Promise.reject(response.json())
// ...but if it resolves, return the json response:
		: response.json()
	);

};


fireCustomEvent = function (eventName) {

// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
	var o = this;

	o.customEvent[eventName] = new Event(eventName);

	window.dispatchEvent(o.customEvent[eventName]);

};

// Ensures first characters is not number:
// revised 2023-11-09
fiveRandomAlphaNumerics = function () {

// If a number may be in the first position, this is all you need:
//
// Math.random().toString(36).substring(2, 7)
//
// but IDs and other identifiers may not start with numbers, hence
// the following routine:

	var getRandomLetter;
	var randomLetter;
	var randomString;

	getRandomLetter = function () {
		var alphabet;
		var randomIndex;
		alphabet = 'abcdefghijklmnopqrstuvwxyz';
		randomIndex = Math.floor(Math.random() * alphabet.length);
		return alphabet[randomIndex];
	};

	randomString = Math.random().toString(36).substring(2, 6);
	randomLetter = getRandomLetter();

	return randomLetter + randomString;

};

// 2021-04-07:
// hashAnchorClickListener() was originally in this location.
// It's only called on gallery pages, so moved to gallery.mjs

// PAGES: GALLERY --> The edit here would apply to any secondary menu, not just to the gallery;
// so, perhaps we need a new class for pages that have drop-down secondary menus?
// We need term for pages of a multiple-class distinction (i.e., there are many galleries...)
// sub-menu-page?
highlightMenuItem = function () {

	var href;
	var o;
	var url;

// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
	o = this;

	url = o.fenestra.location.origin + o.fenestra.location.pathname;

// 2020-03-10:
// ensure the url has a trailing slash:
// 2022-07-31:
// ...unless it ends with a hash!
	if ((url.slice(-1) !== '/') && (url.slice(-1) !== '#')) {
		url += '/';
	}

// Clear-out .selected on all .secondary-ul ULs:
	document.querySelectorAll('.secondary-ul').forEach(function (element) {
		element.classList.remove('selected');
	});

	document.querySelectorAll('header ul li a').forEach(function (element) {
		href = element.href;
// ensure the href has a trailing slash:
		if (href.slice(-1) !== '/') {
			href += '/';
		}
		if (href === url) {
			element.classList.add('selected');
// Add .selected to any .secondary-ul UL ancestor:
			element.closest('.secondary-ul')?.classList.add('selected');
		}
	});

/* asdf */
	if (!document.querySelector('.secondary-ul.selected')) {
		document.querySelector('.primary-ul').classList.add('selected');
	} else {
		document.querySelector('.primary-ul').classList.remove('selected');
	}

	document.querySelectorAll('div[data-for^=side-switcher] span').forEach(function (element) {
		element.classList.remove('selected');
	});

};

initializeHeaderHeightAndObserver = function () {

	var o;
	var resizeObserverForHeaderHeight;

	o = this;
// Default to 0; revise immediately in resizeObserver:
	o.headerHeight = 0;

	resizeObserverForHeaderHeight = new ResizeObserver(function (entries) {

		var rect;
		var height;

	// We're only observing a single element, so access the first element in
	// the entries array:
		rect = entries[0].contentRect;

		height = rect.height;

		if (o.headerHeight !== height) {
			o.headerHeight = height;
//console.log('Current Height : ' + height);
			o.appendToCSS(':root', '{ --header-height: ' + o.headerHeight + 'px; }');
		}

	});

// start observing for resize
	resizeObserverForHeaderHeight.observe(document.querySelector('.header'));

};

initializeFooterHeightAndObserver = function () {

	var o;
	var resizeObserverForFooterHeight;

	o = this;
// Default to 0; revise immediately in resizeObserver:
	o.footerHeight = 0;

	resizeObserverForFooterHeight = new ResizeObserver(function (entries) {

		var rect;
		var height;

	// We're only observing a single element, so access the first element in
	// the entries array:
		rect = entries[0].contentRect;

// rect.top is the top padding.
// There's no way to get the bottom padding, so we're simply doubling it here.
		height = rect.height + rect.top + rect.top;

		if (o.footerHeight !== height) {
			o.footerHeight = height;
//console.log('Current Height : ' + height);
			o.appendToCSS(':root', '{ --footer-height: ' + o.footerHeight + 'px; }');
		}

	});

// start observing for resize
	resizeObserverForFooterHeight.observe(document.querySelector('.footer'));

};


// SHELL: It seems we need parts of this routine!

// OLD NAME:
// initialHashlessPaintingNavigationRoutines = function () {
initializePopstateLocationChangeListeners = function () {

//	var hiddenGalleries;
	var o;
	var windowPopstate;
	var windowLocationChange;

// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
	o = this;

// Issue a custom 'locationchange' event whenever pushstate and replacestate
// are triggered:
	o.customizePushAndReplaceState();

	windowPopstate = function () {
		window.dispatchEvent(new Event('locationchange'));
		window.dispatchEvent(new Event('hashchange'));
	};

	if (o.windowEvents.popstate === false) {
		window.addEventListener('popstate', windowPopstate, false);
		o.windowEvents.popstate = true;
	}

// Fenestra is a shallow copy of the essential properties of Window needed for
// navigation. Initialize it upon starting the site:
	o.updateFenestra();

	windowLocationChange = function () {
		o.updateFenestra();
	};

	window.addEventListener('locationchange', windowLocationChange);

};

initializeWindowOnResizeRoutines = function () {

	var o;
	var currentDeviceIsDesktop;
	var previousDeviceWasMobile;
	var windowResize;

// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
	o = this;

	windowResize = o.debounce(function () {

// uncheck hamburger when in desktop mode:
		if (window.matchMedia('(min-width: 760px)').matches) {
// 2021-12-5:
// OLD:			document.getElementById('hamburger').checked = false;
			document.querySelectorAll('#hamburger').forEach(function (hamburger) {
				hamburger.checked = false;
			});
		}

// reset variable and soft-reload the page if switching the device (in
// DevTools, etc.) This solves the problem with the carousel not resizing
// when switching between devices.

		previousDeviceWasMobile = o.isMobile;

		o.isMobile = (
			navigator.userAgentData
			? navigator.userAgentData.mobile
			: /iPhone|iPad|iPod|Android/i.test(navigator.userAgent)
		);

/*
// OLD VERSION:
		if (navigator.userAgentData) {
			o.isMobile = navigator.userAgentData.mobile;
		} else {
			o.isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
		}
*/

		currentDeviceIsDesktop = !o.isMobile;

// reload if going from mobile to desktop mode:
		if (previousDeviceWasMobile && currentDeviceIsDesktop) {
			console.log('Switching from mobile view to desktop view');
			window.location.reload();
		}

	}, 250, false);

	if (o.windowEvents.resize2 === false) {
		window.addEventListener('resize', windowResize, true);
		o.windowEvents.resize2 = true;
	}

};

// NB: The inner() function is called by the promiseLoader.

// We may need to emit an event at the end of inner.
// The event will trigger these routines:
//			commonRoutinesOnFirstLoadOnly();
//			o.deactivateLoadingMask();
// ...to run in commonRoutines().
// If we don't do that, commonRoutinesOnFirstLoadOnly() will
// run before inner is finished or even run because of the
// promises that call inner.
// Ensure the event listener is activated and deactivated in commonRoutines,
// otherwise we could have those functions called any time inner is run,
// which would happen a lot during AJAXing.

// PAGE: ALL OF THEM
// called by loadPageDependencies()
// called by promiseLoader()
inner = function () {

//console.log('inner');

//	var content;
//	var lastPipe;
//	var metaDescription;
//	var metaElements;
//	var metaOgDescription;
//	var metaTwitterDescription;
	var o;

// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
	o = this;

//window.removeEventListener('replacedMainContent', o.inner);

	o.setMaxWidth759px();
	o.setOrRemoveMobileClasses();
// innerFinish() is called by this routine:
	o.siteWideLoader();

};

innerFinish = function () {

//console.log('innerFinish');

	var o = this;

// remove script preload LINK so attacker can't access name of preloaded scripts
	document.querySelectorAll('link[rel=preload][as=script]').forEach(function (link, index, array) {
		link.parentNode.removeChild(link);
// if last index, delete element reference so element may be garbage-collected:
		if (index === (array.length - 1)) {
			link = null;
		}
	});

	if (o.calledCommonVariables === false) {
		o.commonVariables();
	}

	o.highlightMenuItem();
// 2021-11-29
// exclude explicitly marked internal anchors (needed on site map page):
	o.editExternalLinks(document, '.main a:not(.social-icon-anchor):not(.saatchi-link):not(.internal-anchor)[href*="//"]', true, true);
	o.editExternalLinks(document, '.main a[class*="social-icon-anchor"][href*="//"]', true, 'Share on social media (opens in new window/tab)');
	o.editExternalLinks(document, '.footer a[href*="//"]', false, true);

// reset meta data to defaults before running page-specific module, which may
// have its own metadata that is assumed to overwrite the default...so,
// set (or restore, depending on the case) the defaults first:
	o.reviseMetaData(o.siteData.metaData);

	if (o.hasOwnProperty(o.pageNameCamelCase)) {
		o[o.pageNameCamelCase].main();
	}

// 2020-08-15:
// RETAIN IN CASE WE WANT TO REVERT TO JAVASCRIPT LAZYLOADING:
// for lazyloading images throughout the site:
//		o.makeWaypoints();

	o.updateUriInfo();
// SAVE:		o.iosCheckAndEdit();

// ALL WE NEED IS THIS HERE:
//	o.resetHeaderHeightVariable();
// This is all that's called by o.resetHeaderHeightVariable() that we need:
	o.anchorHashFragmentIntercept();

	o.commonEventListeners();

	document.querySelectorAll('#skip-to-main-content').forEach(function (element) {
		element.setAttribute('href', window.location.origin + window.location.pathname + '#main-content');
	});


// See:
// https://philipwalton.com/articles/idle-until-urgent/
// Prevent long-running tasks by using setTimeout() to break tasks up:
	window.setTimeout(function () {


// 2022-07-12
// Used as trigger in numberUp.mjs
// Perhaps use it as trigger in artwork social share revision rather than the method used now?

//		window.dispatchEvent(new Event('commonMetaDescriptionsSet'));

// 2019-12-29:
// This solves the brief flashing of the anchor bottom borders on the menu
// as well as the brief flashing of the drop-down gallery menu when the
// page first loads. Having the rule in place on the .header-* selector
// in each case causes the problem. Adding after the page has been loaded
// eliminates the problem.

		o.appendToCSS(':root', '{ --background-color-500ms-ease--border-bottom-color-500ms-ease: background-color 500ms ease, border-bottom-color 500ms ease; }');
		o.appendToCSS(':root', '{ --background-color-500ms-ease--border-bottom-color-500ms-ease--color-500ms-ease: background-color 500ms ease, border-bottom-color 500ms ease, color 500ms ease; }');
		o.appendToCSS(':root', '{ --header-li-not-first-child-a-transition: border-color 0.5s ease, background 0.5s ease; }');
		o.appendToCSS(':root', '{ --opacity-500ms-ease: opacity 500ms ease; }');
		o.appendToCSS(':root', '{ --opacity-500ms-ease-in-out: opacity 500ms ease-in-out; }');
		o.appendToCSS(':root', '{ --transform-500ms-ease: transform 500ms ease; }');

// Moved to commonRoutines() in callback:
//			document.body.focus();
		window.dispatchEvent(new Event('innerFunctionFinished'));

	}, 0);

};

isObject = function (variable) {
	return ((typeof variable === 'object') && (!Array.isArray(variable)) && (variable !== null));
};

isSafari = function () {

// SAVE:
// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
//	var o = this;

/*
// SEE: https://web.dev/migrate-to-ua-ch/
// brands array may have info in any index
function isCompatible(item) {
// In real life you most likely have more complex rules here
	return ['Chromium', 'Google Chrome', 'NewBrowser'].includes(item.brand);
}
if (navigator.userAgentData.brands.filter(isCompatible).length > 0) {
	// browser reports as compatible
}
*/

	var brandTest;
	var brandArray = ['Safari'];

	brandTest = function (item) {
		return brandArray.includes(item.brand);
	};

	return (
		navigator.userAgentData
		? (navigator.userAgentData.brands.filter(brandTest).length > 0)
		: /^((?!chrome|android).)*safari/i.test(navigator.userAgent)
	);

};

kebobCaseToCamelCase = function (str) {
	return str[0].toLowerCase() + str.replace(/-(.)/g, function (ignore, b) {
		return b.toUpperCase();
	}).slice(1);
};

kebobCaseToUpperCamelCase = function (str) {
	return str[0].toUpperCase() + str.replace(/-(.)/g, function (ignore, b) {
		return b.toUpperCase();
	}).slice(1);
};

// NB: o.loadLocalResource becomes an object with three methods that accept a URL:
//
// o.loadLocalResource.css(url)
// o.loadLocalResource.js(url)
// o.loadLocalResource.img(url)
//
// Each method name corresponds to an instance of the _load() function called
// with the type of resource indicated by the method name. That _load()
// function returns the anonymous target function, which has access to the
// resource type (the 'tag' argument) by means of the closure the function is
// within. This ensures that only the appropriate routine is run:

loadLocalResource = (function () {

// NB:
// THIS TECHNIQUE WILL NOT WORK IN THIS FUNCTION!
// Because loadLocalResource is a function that returns a function,
// the 'o' object binding will NOT work.
//
// So, NO, NO, NO:
// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
//	var o = this;

	function _load(tag) {

// The anonymous target function (see explanation above):
		return function (url, attributeObj) {

// This promise will be used by Promise.all to determine success or failure
			return new Promise(function (resolve, reject) {
				var attr;
				var element;
				var fragment;
				var parent;

				attr = 'src';
				parent = 'body';

				fragment = document.createDocumentFragment();
				element = document.createElement(tag);
				fragment.appendChild(element);

// Important success and error for the promise
				element.onload = function () {
					resolve(url);
				};
				element.onerror = function () {
					reject(url);
				};

// Need to set different attributes depending on tag type
				switch (tag) {
				case 'script':
					element.async = true;
					element.class = 'dynamic-script';
					break;
				case 'link':
					element.type = 'text/css';
					element.rel = 'stylesheet';
					attr = 'href';
					parent = 'head';
					break;
				case 'img':
					break;
				}

// 2021-10-23:
// Set attributes (often required on the <script> tag of some third-party libraries):
				Object.entries(attributeObj).forEach(function ([key, value]) {
//	console.log(key, value);
// 2021-10-24:
// Obtain actual Boolean for 'async':
					if ((key === 'async') && ((value === 'true') || (value === 'false'))) {
						value = JSON.parse(value);
					}
					element.setAttribute(key, value);
				});

// Inject into document to kick off loading
//				element[attr] = url;
// NB: Because we cannot access the 'o' object here, we must
// declare the tmbTT object in this module (common.mjs) so that
// it is accessible here.
				if (tmbTT.active) {
					element[attr] = tmbTT.policy.createScriptURL(url);
				} else {
					element[attr] = url;
				}

				document[parent].appendChild(fragment);

			});
		};
	}

	return {
		css: _load('link'),
		img: _load('img'),
		js: _load('script')
	};

}());

// Called by commonRoutinesOnFirstLoadOrAjax()
// This loads external dependencies (e.g., pureJsCarousel), not site modules.
loadPageDependencies = function () {

//console.log('loadPageDependencies');

	var assignFn;
	var calledInner;
	var o;
	var pageObj;


	calledInner = false;
// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
	o = this;

	if (o.siteData.hasOwnProperty('pageDependencies')) {
		if (o.siteData.pageDependencies.hasOwnProperty(o.pageName)) {
// Shorthand:
			pageObj = o.siteData.pageDependencies[o.pageName];
// Ensure that it is an object...
			if ((typeof pageObj === 'object') && (pageObj !== null)) {
// ...that is not empty:
				if (Object.keys(pageObj).length !== 0) {
// If the dependencies for the page haven't already been loaded...
					if (o.loadedDependencies[o.pageName] === false) {
// ...and if the page object has a 'mjs' property and it's a string:
						if ((pageObj.hasOwnProperty('mjs')) && (typeof pageObj.mjs === 'string')) {
// One way or another, the routines that follow will call inner():
							calledInner = true;
// Import the page module...
							import('./pages/' + pageObj.mjs).then(function ({default: object}) {
// 2021-10-24:
// (nameArr, urlArr, attributeArr, assignFnArr)
								var nameArr = [];
								var urlArr = [];
								var attributesArr = [];
								var assignFnArr = [];
// Assign its methods/properties to common object 'o':
// old:
//								o.assignToCommonObject(object);
								o.assignToModulePropertyOnCommonObject(o.pageNameCamelCase, object);

//console.log(pageObj);
								o.enqueue = [];
								if ((pageObj.enqueueLoader) && (Array.isArray(pageObj.enqueueLoader)) && (pageObj.enqueueLoader.length !== 0)) {
//console.log(pageObj.enqueueLoader);
									pageObj.enqueueLoader.forEach(function (obj) {
										o.enqueue.push(obj);
									});
								}

// If there's a promiseLoader property, and it's an array, and it's not empty...
								if ((pageObj.promiseLoader) && (Array.isArray(pageObj.promiseLoader)) && (pageObj.promiseLoader.length !== 0)) {
// Cycle through each entry (array) within the array:
									pageObj.promiseLoader.forEach(function (arr) {
										var fnName = arr[0];
										var fnUrl = arr[1];
// 2021-10-23:
// To support attributes on the element/tag:
										var fnAttributes = arr[2];
// assign the function to the global object 'o':
										assignFn = function () {
// If we wanted to make an external dependency specific to a page, we could use
// this routine. But different pages might make use of the same dependency
// (e.g., jQuery):
//
// if (!o.hasOwnProperty(o.pageNameCamelCase)) {
//	o[o.pageNameCamelCase] = {};
// }
// o[o.pageNameCamelCase][fnName] = window[fnName];
//
// But perhaps better to put them in a dedicated 'external' property:
//
// 2021-10-24: This works for pureJsCarousel...does it not work for Sortable and other libraries?
// or is it just more to keep track of when we put 3rd-party scripts in an o.external object?
											if (!o.hasOwnProperty('external')) {
												o.external = {};
											}
											o.external[fnName] = window[fnName];
// Needed to reference o.pureJsCarousel(), etc:
											o[fnName] = window[fnName];
										};
// The promiseLoader calls inner() at its end:

// 2021-10-23:
// To support attributes on the element/tag:
// OLD:										o.promiseLoader([fnName], [fnUrl], assignFn);
//										o.promiseLoader([fnName], [fnUrl], [fnAttributes], assignFn);

// 2021-10-24:
										nameArr.push(fnName);
										urlArr.push(fnUrl);
										attributesArr.push(fnAttributes);
										assignFnArr.push(assignFn);


									});

// 2021-10-24:
									o.promiseLoader(nameArr, urlArr, attributesArr, assignFnArr);


								} else {
// If we're not using the promiseLoader, then we need to call o.inner() here
// (because the promiseLoader, which we're not using, calls o.inner):
									o.inner();
								}
								o.loadedDependencies[o.pageName] = true;
							}).catch(function (error) {
								console.log(error);
							});
						}
					}
				}
			}
		}
	}
	if (calledInner === false) {
		o.inner();
	}
};


makeTransparentMaskClickable = function () {

	var headerTransparentMask;
	var transparentMaskClick;

	headerTransparentMask = document.querySelector('.header .transparent-mask');

	transparentMaskClick = function () {
		document.querySelectorAll('#hamburger').forEach(function (hamburger) {
			hamburger.click();
		});
	};

	if (headerTransparentMask) {
		if (!headerTransparentMask.classList.contains('transparent-mask-click-listener')) {
			headerTransparentMask.classList.add('transparent-mask-click-listener');
			headerTransparentMask.addEventListener('click', transparentMaskClick, false);
		}
	}

};

maxWidth759px = true;


// To prevent display of title in @shell link in footer
noTitle = function (selector) {

	var noTitleMouseover;
	var noTitleMouseout;
	var o;

// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
	o = this;

	noTitleMouseover = function (e) {
		if (e.target.hasAttribute('title')) {
			if (e.target.dataset && !e.target.dataset.title) {
				e.target.dataset.title = e.target.getAttribute('title');
			}
			e.target.setAttribute('title', '');
		}
	};

	noTitleMouseout = function (e) {
		if (e.target.dataset && e.target.dataset.title) {
			e.target.setAttribute('title', e.target.dataset.title);
		}
	};

// When using a variable, escape any forward slashes that might be in the text:
	document.querySelectorAll(o.escapeForwardSlashes(selector)).forEach(function (element) {
		if (!element.classList.contains('mouseover-listener')) {
			element.classList.add('mouseover-listener');
			element.addEventListener('mouseover', noTitleMouseover);
		}
		if (!element.classList.contains('mouseout-listener')) {
			element.classList.add('mouseout-listener');
			element.addEventListener('mouseout', noTitleMouseout);
		}
	});

};

// on backbutton, right?
popstateListener = function () {

	var o;
	var windowPopstate;

// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
	o = this;

// NB: At this point, o.pageName will hold where we WERE, not where we ARE.
// E.g., if we're backbuttoning from 'about' to 'news', then o.pageName will
// hold 'about', not 'news'.

	windowPopstate = function () {
		o.revealHashedContent('');
	};

	window.addEventListener('popstate', windowPopstate, false);

};

// The promiseLoader() is for scripts that are a single routine that is defined
// with a single variable name. If the library has:
//
// function xyz() {}
//
// ...as an enclosing function, and we may therefore call xyz() in our code,
// then it's probably right to be loaded with the promiseLoader.
//
// This is in contrast to the enqueue method, which is for libraries that are
// not contained within a single enclosing function, but may be any number of
// top-level routines and variables (e.g., like so many 3rd-party libraries).

// This version of promiseLoader() loads any number of specified
// libraries:

// 2021-10-23:
// To support attributes on the element/tag:
// OLD: promiseLoader = function (nameArr, urlArr, assignFn) {
promiseLoader = function (nameArr, urlArr, attributeArr, assignFnArr) {

//console.log('promiseLoader');

/*
console.log('------');
console.log(nameArr);
console.log(urlArr);
console.log(attributeArr);
console.log(assignFnArr);
console.log('------');
*/

	var filteredNameArr;
	var filteredUrlLoaderArr;
	var o;
	var windowHasOwnPropery;

// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
	o = this;

	filteredNameArr = [];
	filteredUrlLoaderArr = [];

	windowHasOwnPropery = function (string) {
		return window.hasOwnProperty(string);
	};

// If the named routine is NOT already on the window object, then it needs to
// be loaded:
	nameArr.forEach(function (str, index) {
		if (!window.hasOwnProperty(str)) {
			filteredNameArr.push(str);
// 2021-10-23:
// To support attributes on the element/tag:
// Push the load routine and its target into the array:
// OLD:			filteredUrlLoaderArr.push(o.loadLocalResource.js(urlArr[index]));
			filteredUrlLoaderArr.push(o.loadLocalResource.js(urlArr[index], attributeArr[index]));
		}
	});

	if (filteredUrlLoaderArr.length !== 0) {

//console.log(filteredUrlLoaderArr);

		Promise.all(filteredUrlLoaderArr).then(function () {

// If every targeted name is now on the window object, then proceed:
			if (filteredNameArr.every(windowHasOwnPropery)) {

//console.log(filteredNameArr);
//console.log(assignFnArr);

// 2021-10-24:
// OLD:				assignFn();
// NB: Do NOT rename parameter to assignFn!
				assignFnArr.forEach(function (assignFn) {

//console.log(assignFn);

					assignFn();
				});
//console.log('going to o.inner()');
				o.inner();

				filteredNameArr.forEach(function (nameStr) {
					console.log('Success: ' + nameStr + ' loaded');
				});
			}

		}).catch(function () {
			filteredNameArr.forEach(function (nameStr) {
				console.log('Failure: ' + nameStr + ' not loaded');
			});
		});


	} else {
		o.inner();
	}
};
// ^end of promiseLoader() function

// The --header-height variable is used in calculations affecting the placement
// of MAIN material relative to height of header. It is not used to set the
// header height; it is used to record dynamic changes to it. The function
// creates a second --header-height rule later in a new style sheet that will
// overrule the one set initially in common.css.
/*
// PAGES: GALLERY (Maybe...read the code...)
resetHeaderHeightVariable = function () {

//	var headerHeight;
	var o;

// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
	o = this;

//	headerHeight = o.returnHeaderHeight();
//	o.appendToCSS(':root', '{ --header-height: ' + headerHeight + 'px; }');

// NB!!!
// If you resize the window, the carousel must be rebuilt, and all click
// listeners on its anchors are lost and must be reinitialized:

	o.anchorHashFragmentIntercept();

};
*/

returnAjaxObject = function () {

// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
	var o = this;

	o.ajax = {};

	o.ajax.post = function (url, data, callback, asynchronous) {
		var query = [];
		Object.keys(data).forEach(function (key) {
			query.push(encodeURIComponent(key) + '=' + encodeURIComponent(data[key]));
		});
		o.ajax.send(url, callback, 'POST', query.join('&'), asynchronous);
// NEED TO CATCH POST ERROR: TypeError: Cannot read property 'requestContent' of undefined
	};

// CLOSURE COMPILER EXPECTS ALL ARGUMENTS TO BE PROVIDED WHEN FUNCTION
// IS CALLED, UNLESS DEFAULT VALUES ARE PROVIDED IN FUNCTION DEFINITION,
// SIGH...

	o.ajax.send = function (url, callback, method, data, asynchronous = true) {

		var http;

		http = new XMLHttpRequest();

		http.open(method, url, asynchronous);
		http.onreadystatechange = function () {
			if (http.readyState === 4) {

//o.httpStatus = http.status;
// qwer:htaccess

//o.httpStatus = http.status;
//console.log('- new status: ' + http.status);
				if (http.status === 200) {
					callback(http.responseText);
				} else {
// qwer:htaccess
// We must do this here, because a 404 triggers a call to pages/error/main.php, which will be 200
					o.httpStatus = http.status;
					window.dispatchEvent(new Event('http404'));
				}
			}
		};
		if (method === 'POST') {
			http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
//			http.withCredentials = true;
		}
		http.send(data);
	};

	return o.ajax;

};

/*
returnHeaderHeight = function () {
	var o = this;

	var header;
	var headerHeight;

// SAVE:
// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
//	var o = this;

// This is now consistent in non-admin mode:
	headerHeight = 60;

	if (document.body.hasAttribute('data-admin')) {
		header = document.querySelector('header');
		if (header) {
			headerHeight = header.offsetHeight;
		}
	}
	return headerHeight;
};
*/

returnTimeStamp = function () {

	var today;
	var year;
	var month;
	var date;
	var hours;
	var minutes;
	var seconds;
	var timeStamp;

	today = new Date();
	year = today.getFullYear();
	month = today.getMonth() + 1;
	if (month <= 9) {
		month = '0' + month;
	}
	date = today.getDate();
	hours = today.getHours();
	minutes = today.getMinutes();
	seconds = today.getSeconds();
	timeStamp = year + month + date + hours + minutes + seconds;

	return timeStamp;

};

// 2021-04-15:
// This had been moved to gallery.mjs, but now it's moved back to common.mjs.
// This is needed when backbuttoning through gallery and non-gallery pages.
// It must be put in common.mjs
// 2021-04-16:
// We default to revising the window.history when doing this.
// It should NOT be done when backbuttoning to the main URL of the gallery,
// which will have no slug (e.g., 'kingdom-come')

// OLD: revealHashedContent = function (target, reviseHistory = true) {
revealHashedContent = function (target) {

// CLOSURE COMPILER EXPECTS ALL ARGUMENTS TO BE PROVIDED WHEN FUNCTION
// IS CALLED, UNLESS DEFAULT VALUES ARE PROVIDED IN FUNCTION DEFINITION,
// SIGH...
//
// Default for 'target', in case no argument is provided when function is called:
//				target = (
//					(target === undefined)
//					? ''
//					: target
//				);

// If the previousURL and the target's URL are the same,
// DO NOT AJAX material...just go to the hash (if present):

	var backbutton;
	var hashedElement;
	var hrefText;
	var o;

// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
	o = this;

///////////////////////
//// COMMON TO ALL ////
///////////////////////

// The following code is common to backbuttoning through all pages,

	hrefText = o.fenestra.location.href.substring(o.baseHref.length);
	if (hrefText.slice(-1) === '/') {
		hrefText = hrefText.slice(0, -1);
	}
	target = o.fenestra.location.href;
	backbutton = true;

// qwer:htaccess
// Necessary to distinguish true backbuttoning through 404 pages.
// A quirk of that routine is that the 'backbutton' variable passed
// to it must be set to 'true' even if not backbuttoning...described
// at the routine in a comment:
	o.backbutton = backbutton;

	o.targetURL = o.fenestra.location.origin + o.fenestra.location.pathname;
	if (o.previousURL !== o.targetURL) {
// 2021-08-14: hard-code an event type of 'click', since this content could not
// be loaded otherwise (and we can't check the event.type from this function).
		o.ajaxMainContent(hrefText, target, backbutton, 'click');
	}

	window.requestAnimationFrame(function () {

		window.scroll(0, 0);

		if ((backbutton === true) && (hrefText && (hrefText.charAt(0) === '#'))) {
// When using a variable, escape any forward slashes that might be in the text:

// 2021-11-02
// Don't do this if there's only a hash followed by no text!
			if (hrefText !== '#') {
				hashedElement = document.querySelector(o.escapeForwardSlashes(hrefText));
				if (hashedElement !== null) {
					hashedElement.scrollIntoView();

					o.scrollDownByHeaderHeight();

				}
			}
		}
	});
};


// must be performed AFTER meta information is revised in the HEAD:
reviseSchema = function () {

	document.querySelectorAll('#schema-web-page').forEach(function (script) {
		var schemaJSON;
		schemaJSON = JSON.parse(script.innerText);
		document.querySelectorAll('meta[name="description"]').forEach(function (element) {
			schemaJSON.description = element.getAttribute('content');
		});
		schemaJSON.url = window.location.href;
		script.innerHTML = JSON.stringify(schemaJSON);
	});

	document.querySelectorAll('#schema-person').forEach(function (script) {
		var schemaJSON;
		schemaJSON = JSON.parse(script.innerText);
		schemaJSON.url = window.location.href;
		script.innerHTML = JSON.stringify(schemaJSON);
	});

};

// STANDARD VERSION; special version for gallery pages in gallery.mjs
reviseMetaData = function (metaDataObject) {

	var base;
	var canonical;
	var o;
	var page;

// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
	o = this;

	document.querySelectorAll('BASE').forEach(function (element) {
		if (element.hasAttribute('href')) {
			base = element.href;
		}
	});

	document.querySelectorAll('LINK').forEach(function (link) {
		if (link.hasAttribute('rel')) {
			if (link.rel === 'canonical') {
				if (link.hasAttribute('href')) {
					canonical = link.href;
				}
			}
		}
	});


	Object.entries(metaDataObject).forEach(function ([attribute, values]) {
		if (o.isObject(values)) {
			Object.entries(values).forEach(function ([attributeValue, contentValue]) {

//		console.log(attribute + '=' + attributeValue + ' content=' + contentValue);

				document.querySelectorAll('meta[' + attribute + '="' + attributeValue + '"]').forEach(function (element) {

					if (metaDataObject.page) {
						page = metaDataObject.page;

						if ((!element.dataset.page) || (element.dataset.page !== page)) {

							contentValue = contentValue.replace('${BASE}', base);
							contentValue = contentValue.replace('${CANONICAL}', canonical);
							contentValue = contentValue.replace('${TITLE}', document.title);

// if '${DEFAULT}' just use default value; this will allow the user to retain the full metaData listing as a paradigm.

//console.log(attribute + '=' + attributeValue + ' content=' + contentValue);

							if (contentValue !== '${DEFAULT}') {
								element.setAttribute('content', contentValue);
								element.dataset.page = page;
							}
						}

					}
				});
			});
		}
	});

	o.reviseSchema();

};

scrollDownByHeaderHeight = function () {

//	var headerHeight;
	var o;

// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
	o = this;

	window.setTimeout(function () {
//		headerHeight = o.returnHeaderHeight();
		window.scrollBy(0, (o.headerHeight * -1));
	}, 500);

};

serviceWorkerRoutine = function () {

	var o;
	var metaDataTimestamp;
	var serviceWorkerFileName;

// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
	o = this;

	metaDataTimestamp = '19990221125549';

	if (o.metaNameWebAuthor && o.metaNameWebAuthor.dataset && o.metaNameWebAuthor.dataset.timestamp) {
		metaDataTimestamp = o.metaNameWebAuthor.dataset.timestamp;
	}

// Initialize and employ service worker:
	if (navigator.serviceWorker === undefined) {
		console.log('Service Worker not supported');
	} else {
// Safari has no window.caches:
		if (window.caches) {
			window.caches.keys().then(function (keyList) {
				return Promise.all(keyList.map(function (key) {
					var keyNumber = parseInt(key.substring(0, 14), 10);
					var jsonNumber = parseInt(metaDataTimestamp.substring(0, 14), 10);
					if (keyNumber < jsonNumber) {
						window.caches.delete(key).then(function () {
							console.log('cache ' + key + ' is deleted');
						}).catch(function (error) {
							console.log(error);
							return false;
						});
					}
				})).catch(function (error) {
					console.log(error);
					return false;
				});
			}).catch(function (error) {
				console.log(error);
				return false;
			});
			if (navigator.onLine) {
				console.log('Registering new Service Worker...');
				if (tmbTT.active) {
					serviceWorkerFileName = tmbTT.policy.createScriptURL('sw.js');
				} else {
					serviceWorkerFileName = 'sw.js';
				}
				navigator.serviceWorker.register(serviceWorkerFileName, {scope: './', updateViaCache: 'none'}).then(function (registration) {
					registration.update();
					console.log('New Service Worker registration successful with scope: ', registration.scope);
					console.log('Hard refresh at any time for new Service Worker.');
				}).catch(function (error) {
					console.log('New Service Worker registration failed:', error);
				});
			}
		}
	}

};


setCanonicalLink = function (URL) {

	var canonicalLink;
	var firstLink;
	var fragment;
	var newLink;
	var nodes;

// console.log('setting canonical: ' + URL);

	canonicalLink = document.querySelector('link[rel="canonical"]');
	if (Boolean(canonicalLink)) {
		canonicalLink.setAttribute('href', URL);
	} else {
		nodes = document.head.querySelectorAll('LINK');
		firstLink = nodes[0];
		fragment = document.createDocumentFragment();
		newLink = document.createElement('LINK');
		fragment.appendChild(newLink);
		newLink.setAttribute('rel', 'canonical');
		newLink.setAttribute('href', URL);
		firstLink.parentNode.insertBefore(fragment, firstLink.nextSibling);
	}

};


// See: https://developer.mozilla.org/en-US/docs/Web/CSS/Media_Queries/Testing_media_queries
setMaxWidth759px = function () {

	var handleOrientationChange;
	var mediaQueryList;
	var o;

// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
	o = this;

// Create the query list.
	mediaQueryList = window.matchMedia("(max-width: 759px)");

// Define a callback function for the event listener.
	handleOrientationChange = function (e) {
		if (e.matches) {
			o.maxWidth759px = true;
		} else {
			o.maxWidth759px = false;
		}
	};

// Set default value:
	o.maxWidth759px = mediaQueryList.matches;

// Safari 13.x still requires the deprecated .addListener() interface.
// SEE: https://stackoverflow.com/questions/62028516/window-matchmedia-does-not-work-in-safari

// Check to see if .addEventListener() is on prototype; use it if it is,
// otherwise fall back to deprecated .addListener()
	if (Object.getPrototypeOf(mediaQueryList).addEventListener) {
		mediaQueryList.addEventListener('change', handleOrientationChange);
	} else {
		mediaQueryList.addListener(handleOrientationChange);
	}

};

// NB: There are references to GALLERY in this function, but the work done
// should be good for any secondary menu:
setOrRemoveMobileClasses = function () {

// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
	var o = this;

	if (o.maxWidth759px) {

// .header-mobile currently only used in:
// header.css
// BUT is used extensively in the JavaScript that edits the sides of the two-sided menu.

/*
// KEEP -- IT'S USED BY THE JAVASCRIPT:
		document.querySelectorAll('.header').forEach(function (ignore) {
			o.resetHeaderHeightVariable();
		});
*/
// This is all that's called by o.resetHeaderHeightVariable() that we need:
		o.anchorHashFragmentIntercept();

//console.log('NON-2nd-SIDE: .menu-side-two: make all LIs tabbable');
		document.querySelectorAll('.menu-side-one').forEach(function (element) {
			element.tabIndex = 0;
		});
//console.log('NON-2nd-SIDE: .menu-side-two: make all LIs untabbable');
		document.querySelectorAll('.menu-side-two').forEach(function (element) {
			element.tabIndex = -1;
		});


//console.log('-----');

// If min-width: 760px (desktop):
	} else {

/*
		document.querySelectorAll('.header').forEach(function (ignore) {
			o.resetHeaderHeightVariable();
		});
*/
// This is all that's called by o.resetHeaderHeightVariable() that we need:
		o.anchorHashFragmentIntercept();

		document.querySelectorAll('.menu-side-one').forEach(function (element) {
			element.tabIndex = 0;
		});
		document.querySelectorAll('.menu-side-two').forEach(function (element) {
			element.tabIndex = 0;
		});

// The side switcher spans shouldn't be tabbable in Desktop mode
//		o.makeSideSwitchersUntabbable();

	}

};

// This may come in handy at some future point. The BODY element has
// data-orientation=initial on it in the HTML/PHP
setOrientation = function () {

// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
	var o = this;

	switch (window.orientation) {
	case 0:
		document.body.dataset.orientation = 'portrait';
		o.onResizeEdits();
		break;
	case 90:
		document.body.dataset.orientation = 'landscape-left';
		o.onResizeEdits();
		break;
	case -90:
		document.body.dataset.orientation = 'landscape-right';
		o.onResizeEdits();
		break;
	}

};

// This is ALMOST identical to the basic loader.js script.

// NB: This is called every time the user navigates to a different destination in the site:
siteWideLoader = function () {

	var o;
	var scriptObjects;

// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
	o = this;

// This is moved here from commonVariables to accommodate lightboxDependenciesAdded routine, below:

	o.isMobile = (
		navigator.userAgentData
		? navigator.userAgentData.mobile
		: /iPhone|iPad|iPod|Android/i.test(navigator.userAgent)
	);

	scriptObjects = [];

	function deleteDynamicScripts() {
		document.querySelectorAll('.dynamic-script').forEach(function (script, index, array) {
			script.parentNode.removeChild(script);
// if last index, delete element reference so element may be garbage-collected:
			if (index === (array.length - 1)) {
				script = null;
			}
		});
// If there are any scripts to enqueue, this is where innerFinish() is called:
		o.innerFinish();
	}

// The enqueue method loader is for libraries that are
// not contained within a single enclosing function, but may be any number of
// top-level routines and variables (e.g., like so many 3rd-party libraries).

	function enqueue(object) {
		scriptObjects.push(object);
	}

	function loadScriptsSynchronously() {

		var attributeObject = scriptObjects.shift();
		var fragment = document.createDocumentFragment();
		var script = document.createElement('script');
		var scriptName;
		var value;

		fragment.appendChild(script);

		Object.keys(attributeObject).forEach(function (key) {
//			script[key] = attributeObject[key];
			value = attributeObject[key];
			if (tmbTT.active && (key === 'src')) {
				script[key] = tmbTT.policy.createScriptURL(value);
			} else {
				script[key] = value;
			}

			if (key.substring(0, 4) === 'data') {
				script.setAttribute(o.camelCaseToKebobCase(key), attributeObject[key]);
// If dataScriptName is set as an attribute on the enqueued script,
// then it will be written to the console when it's loaded:
				if (key === 'dataScriptName') {
					scriptName = attributeObject[key];
				}
			}
		});

		if ((script.type === undefined) || (script.type === '')) {
			script.type = 'text/javascript';
		}

// nonce
		if (o.nonce) {
			script.setAttribute('nonce', o.nonce);
		}

		script.async = false;
		script.classList.add('dynamic-script');

		if (script.type === 'text/javascript') {
			if (scriptObjects.length > 0) {
				script.onload = loadScriptsSynchronously;
				script.onerror = function (e) {
					console.log(script.src + ' could not load:');
					console.log(e);
					loadScriptsSynchronously();
				};
			} else {
				script.onload = deleteDynamicScripts;
			}
		}

		if (!o.enqueueArray.includes(script.src)) {
			o.enqueueArray.push(script.src);
			document.body.appendChild(fragment);
			if (scriptName) {
				console.log('loading ' + scriptName);
			}
		} else {
// This ensures either:
// (1) loadScriptsSynchronously() is called next, or
// (2) deleteDynamicScript() is called next,
// depending on whether there are more scripts to enqueue:
			script.type = 'previouslyLoaded';
		}

		if (script.type !== 'text/javascript') {
			if (scriptObjects.length > 0) {
				loadScriptsSynchronously();
			} else {
				deleteDynamicScripts();
			}
		}

	}

// NB: This assumes everything that follows the FOOTER element is specific to
// the page in question EXCEPT application/ld+json blocks. Any element that
// follows the FOOTER element (i.e., is a sibling of the FOOTER) is removed
// when a new page is loaded EXCEPT application/ld+json blocks, to prevent the
// DOM from having extraneous elements:
//
// SCRIPTs with src="https://www.google-analytics.com/analytics.js" should be retained as well:
	function removeLeftoverFooterSiblings() {
		var footer;
		function getNextSiblings(element) {
			var siblings = [];
			while (element.nextElementSibling !== null) {
				siblings.push(element.nextElementSibling);
				element = element.nextElementSibling;
			}
			return siblings;
		}
		function ldJsonFilter(el) {
// NB: We're not filtering for 'application/json', as none should follow the
// footer:
			return ((el.nodeName === 'SCRIPT') && (el.hasAttribute('type')) && (el.getAttribute('type') === 'application/ld+json'));
		}
		function filterNextSiblings(element, filter) {
			var nextSiblings = getNextSiblings(element);
			Object.keys(nextSiblings).forEach(function (key) {
				var sibling = nextSiblings[key];
				if (!filter(sibling)) {
// defer
					sibling.parentNode.removeChild(sibling);
// remove reference to element so it can be garbage-collected:
					sibling = null;
				}
			});
		}
		footer = document.querySelector('footer');
		if (footer !== null) {
			filterNextSiblings(footer, ldJsonFilter);
		}
	}

	if ((o.enqueue) && (o.enqueue.length)) {
		o.enqueue.forEach(function (obj) {
			enqueue(obj);
		});
	}

	removeLeftoverFooterSiblings();

	if (scriptObjects.length !== 0) {
		loadScriptsSynchronously();
	} else {
// If there are no scripts to enqueue, then this is where innerFinish() is called:
		o.innerFinish();
	}

};

stripEndQuotes = function (str) {
	return str.replace(/^"?([^"]*)"?$/, '$1');
};

stripHtmlTags = function (html) {
	var doc = new DOMParser().parseFromString(html, 'text/html');
	return doc.body.textContent || '';
};

// Set trusted types object before everything else:
tmbTT = {};

// PAGES: GALLERY (but called and needed by all routines)
updateFenestra = function () {

	var adminMode;
	var gtagConfig;
	var lighthouseAudit;
	var o;
	var onLiveSite;

// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
	o = this;

	adminMode = document.body.hasAttribute('data-admin');

	lighthouseAudit = (o.metaNameWebAuthor && o.metaNameWebAuthor.dataset && o.metaNameWebAuthor.dataset.chromeLighthouse);

	onLiveSite = ((o.siteData && o.siteData.liveSiteUrl) && (window.location.origin === o.siteData.liveSiteUrl));

// NB: Here we check to ensure Google Analytics *has* already been initialized.
	if (!adminMode && !lighthouseAudit && onLiveSite && navigator.onLine && o.googleAnalyticsInitialized && o.gtag) {

// if we're in the EU, do NOT run analytics:
		if (o.inEu !== true) {

			gtagConfig = {
				'anonymize_ip': true,
				'cookie_flags': 'SameSite=None; Secure',
				'page_path': window.location.pathname,
				'send': 'pageview'
			};

			o.gtag('config', o.siteData.uaString, gtagConfig);

		}
	}


	o.fenestra = {};

	o.fenestra.location = {};
	o.fenestra.location.hash = window.location.hash;
	o.fenestra.location.href = window.location.href;

// eliminate query string in href history:
	if (o.fenestra.location.href.indexOf('?') > -1) {
		o.fenestra.location.href = o.fenestra.location.href.split('?')[0];
	}

	o.fenestra.location.origin = window.location.origin;
	o.fenestra.location.pathname = window.location.pathname;

};

updateUriInfo = function () {

// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
	var o = this;

	o.previousURL = o.fenestra.location.origin + o.fenestra.location.pathname;

};

// To ensure we only register a window event listener once, maintain this
// object with booleans that indicate what has been set and what has not been
// set:
//
// NB: To see all event listeners in Chrome dev tools, use:
// getEventListeners(window);
//
windowEvents = {
	hashchange: false,
	keydown: false,
	load: false,
	popstate: false,
// onResizeEdits in siteWideEditsClosure.mjs:
	resize1: false,
// initializeWindowOnResizeRoutines in common.mjs:
	resize2: false
};


export default Object.freeze({
	activateLoadingMask,

//	animationStart,
//	animationIteration,
//	animationEnd,
//	ajaxEvent,
	ajaxMainContent,
	anchorHashFragmentIntercept,
	anchorIntercept,
	aspectRatioImgEdits,
	calledCommonVariables,
	camelCaseToKebobCase,
	checkTrustedTypesSupport,
	closeDrawerOnAnchorClick,
	closeDrawerOnMobileLogoClick,
	commonEventListeners,
	commonRoutines,
	commonRoutinesOnFirstLoadOnly,
	commonRoutinesOnFirstLoadOrAjax,
	commonVariables,
	conditionallyReplaceJpgExtensionWithWebpExtension,
	consoleLog,
	consoleLogMsgObj,
	customizePushAndReplaceState,
	deactivateLoadingMask,
	deleteCachesUnregisterServiceWorkerAndClearSessionStorage,
	editExternalLinks,
	enqueueArray,
	escapeForwardSlashes,
	escapeSingleQuotes,
	fetchAppendToUploadStatusDiv,
	fetchReject,
	fetchResolve,
	fetchResolveWithOptions,
	fetchResponse,
	fireCustomEvent,
	fiveRandomAlphaNumerics,
	footerEdits,
	highlightMenuItem,
	inEu,
	initializeFooterHeightAndObserver,
	initializeHeaderHeightAndObserver,
	initializePopstateLocationChangeListeners,
	initializeWindowOnResizeRoutines,
	inner,
	innerFinish,
// SAVE:	iOS,
// SAVE:	iosCheckAndEdit,
	isObject,
	isSafari,
	kebobCaseToCamelCase,
	kebobCaseToUpperCamelCase,
	loadLocalResource,
	loadPageDependencies,
	makeTransparentMaskClickable,
	maxWidth759px,
// RETAIN IN CASE WE WANT TO REVERT TO JAVASCRIPT LAZYLOADING:
//	makeWaypoints,
//	moreInfoLessInfo,
	noTitle,
	popstateListener,
	promiseLoader,
	removeVisibilityHiddenFromTmbAlert,
//	resetHeaderHeightVariable,
	returnAjaxObject,
//	returnHeaderHeight,
	returnTimeStamp,
	revealHashedContent,
	reviseMetaData,
	reviseSchema,
	scrollDownByHeaderHeight,
	serviceWorkerRoutine,
	setMaxWidth759px,
	setCanonicalLink,
	setOrRemoveMobileClasses,
	setOrientation,
	siteWideLoader,
	stripEndQuotes,
	stripHtmlTags,
//standardPushAndReplaceState,

	tmbTT,
	updateFenestra,
	updateUriInfo,
//	wait,
//	waitAndSetHeaderHeight,
	windowEvents
});