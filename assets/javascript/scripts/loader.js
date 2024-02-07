/**
* loader.js - v2.4
* Copyright (c) 2019-2023 Thomas M. Brodhead <https://bmt-systems.com>
* Released under the MIT license
* Date: 2023-11-01
*/

// This script MUST run before the DOMContentLoaded event.
// The nonce written to this script by PHP is retrieved during this routine
// and used on each script that's appended to the DOM by this script.
// Chromium browsers strip nonces from elements upon DOMContentLoaded, hence
// it's imperative not to load this script after DOMContentLoaded.
// Loading it with async or defer is the only viable solution.

(function () {

	'use strict';
	var loader;

	loader = function (nonceArgument) {

//////////////////
// DECLARATIONS //
//////////////////

		var camelCaseToKabobCase;
		var currentScript;
		var deleteDynamicScripts;
		var enqueue;
		var loadScriptsSynchronously;
		var metaNameWebAuthor;
		var onloadRoutines;
		var scriptObjects;
		var selfDestruct;
		var tmbTT;
		var useTrustedTypes;


/////////////////
// DEFINITIONS //
/////////////////

		camelCaseToKabobCase = function (str) {
			return str.replace(/([a-zA-Z])(?=[A-Z])/g, '$1-').toLowerCase();
		};

		currentScript = document.currentScript || document.scripts[document.scripts.length - 1];

		deleteDynamicScripts = function () {
			document.querySelectorAll('.dynamic-script').forEach(function (script) {
				script.parentNode.removeChild(script);
			});
		};

		enqueue = function (object) {
			scriptObjects.push(object);
		};

		loadScriptsSynchronously = function () {

			var attributeObject;
			var fragment;
			var script;
			var value;
			var scriptName;

			attributeObject = scriptObjects.shift();
			fragment = document.createDocumentFragment();
			script = document.createElement('script');
			fragment.appendChild(script);

			Object.keys(attributeObject).forEach(function (key) {

				value = attributeObject[key];

				if (tmbTT.active && (key === 'src')) {
					script[key] = tmbTT.policy.createScriptURL(value);
				} else {
					script[key] = value;
				}

				if (key.substring(0, 4) === 'data') {
					script.setAttribute(camelCaseToKabobCase(key), attributeObject[key]);
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

			script.async = false;
			script.classList.add('dynamic-script');
/*
// If it's a standard .js file, set mechanism to run this function again after
// the .js file is loaded:
			if ((scriptObjects.length > 0) && (script.type === 'text/javascript')) {
				script.onload = loadScriptsSynchronously;
				script.onerror = function (e) {
					console.log(script.src + ' could not load:');
					console.log(e);
					loadScriptsSynchronously();
				};
			}

// Append the script, regardless of type; standard .js scripts will call this
// function again after loading; others will not:
			document.body.appendChild(fragment);

// If it was NOT a standard .js file, repeat the function (needed because json
// files and similar emit no "onload" event:
			if ((scriptObjects.length > 0) && (script.type !== 'text/javascript')) {
				loadScriptsSynchronously();
			}
*/
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

			document.body.appendChild(fragment);
			if (scriptName) {
				console.log('loading ' + scriptName);
			}

			if (script.type !== 'text/javascript') {
				if (scriptObjects.length > 0) {
					loadScriptsSynchronously();
				} else {
					deleteDynamicScripts();
				}
			}

		};

		onloadRoutines = function () {
			deleteDynamicScripts();
		};

		scriptObjects = [];

		selfDestruct = function () {
			if (currentScript.parentNode) {
				currentScript.parentNode.removeChild(currentScript);
			}
		};

		tmbTT = {};

/////////////////
// THE ROUTINE //
/////////////////

// 2023-12-20
// OLD:
//		metaNameWebAuthor = document.querySelector('meta[name=web_author]');
//		useTrustedTypes = (metaNameWebAuthor && metaNameWebAuthor.dataset && metaNameWebAuthor.dataset.useTrustedTypes && (metaNameWebAuthor.dataset.useTrustedTypes === 'true'));

		(function (metaNameWebAuthor) {
			useTrustedTypes = Boolean(metaNameWebAuthor && metaNameWebAuthor.dataset.useTrustedTypes === 'true');
		}(document.querySelector('meta[name=web_author]')));

		if (window.hasOwnProperty('trustedTypes') && useTrustedTypes) {
			tmbTT.policy = window.trustedTypes.createPolicy('stop-gap', {
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
			tmbTT.active = true;
			tmbTT.trusted = '';
			tmbTT.untrusted = '';
		} else {
			tmbTT.active = false;
		}

/*
// KEEP FOR REFERENCE: to add integrity attributes:
// NB: Remove XXX after enqueue if reinstating this (the compiler looks for 'enqueue('
	enqueueXXX({
		src: 'https://code.jquery.com/jquery-3.2.1.slim.min.js',
		integrity: 'sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN',
		crossOrigin: 'anonymous'
	});

// NB: Before adding dynamic import() of modules, we included these polyfills:
//
// polyfill-css-var.js
// polyfill-css-scope.js
// polyfill-promise.js
//
*/

// Everything else:
		if (tmbTT.active) {
			enqueue({src: 'javascript/scripts/purify.js', dataScriptName: 'purify'});
		}

// 2020-08-15:
// RETAIN IN CASE WE WANT TO REVERT TO JAVASCRIPT LAZYLOADING:
//	enqueueXXX({src: 'javascript/scripts/noframework.waypoints.js'});
// 2024-01-12:
//		enqueueXXX({src: 'javascript/scripts/passiveSupport.js', dataScriptName: 'passiveSupport'});
//		enqueueXXX({src: 'javascript/scripts/tmbBug.js'});

// The nonce is set on the global object 'o' in siteWideEditsClosure.
// It must be set on siteWideEdits.js to be accessible to siteWideEditsClosure

// 2022-04-19:
// OLD:	enqueue({src: 'javascript/scripts/siteWideEdits.js', nonce: currentScript.nonce});
		enqueue({src: 'javascript/scripts/siteWideEdits.js', nonce: nonceArgument, dataScriptName: 'siteWideEdits'});

// SEE: https://philipwalton.com/articles/idle-until-urgent/
// Prevent long-running tasks by using setTimeout() to break tasks up:
		window.setTimeout(function () {
			window.onload = onloadRoutines;
			loadScriptsSynchronously();
			selfDestruct();
		}, 0);

	};

// currentScript.nonce is the nonce added to loader.js as delivered
// to the browser, whose value will be erased by chromium browsers upon
// DOMContentLoaded event. It should still be accessible at this point:

	if (document.currentScript && document.currentScript.nonce) {
		loader(document.currentScript.nonce);
	} else {
// If it's not accessible, use getNonce.mjs, which will first search for it on the
// web_author META tag, and then, if that fails, will perform XHR to get it
// via headers:
		import('../modules/getNonce.mjs').then(function ({default: object}) {
			object.main(loader);
		}).catch(function (error) {
			console.log(error);
		});
	}

}());
