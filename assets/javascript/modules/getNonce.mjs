/**
* getNonce.mjs
* Copyright (c) 2019-2023 Thomas M. Brodhead <https://bmt-systems.com>
* Released under the MIT license
* Date: 2023-11-01
*/

var main;

// We pass into the main() function the closure() function
// defined in siteWideEdits.js. closure takes 'nonce'
// as its argument (i.e., closure(nonce)); the nonce
// retrieved via promises in this routine is passed to
// closure, which kicks off all of the app's procedures.

main = function (closure) {

	var getNonceViaXhr;
	var getScriptNonce;
	var makeRequest;
	var metaNameWebAuthor;
	var nonce;
	var scriptNonce;


// Toggle between xhr method and web_author meta tag data-nonce method:
	getNonceViaXhr = true;

	nonce = '';
	scriptNonce = '';

// NB: If we write the nonce to the META tag, DO NOT BOTHER with xhr method:
// NB: We just record a single nonce to the HTML; currently (2021-04-11), both
// script-src and style-src get the same nonce:

	metaNameWebAuthor = document.querySelector('meta[name=web_author]');
	if (metaNameWebAuthor && Boolean(metaNameWebAuthor.dataset) && Boolean(metaNameWebAuthor.dataset.nonce)) {
		nonce = metaNameWebAuthor.dataset.nonce;
		getNonceViaXhr = false;
	}

	getScriptNonce = function (request) {

		var csp;
		var headerMap;
		var headers;
		var headersArray;
		var scriptSrc;
		var scriptSrcNonce;

		scriptSrcNonce = '';
		headers = request.getAllResponseHeaders();
// Convert the header string into an array of individual headers
		headersArray = headers.trim().split(/[\r\n]+/);
// Create a map of header names to values
		headerMap = {};
		headersArray.forEach(function (line) {
			var parts;
			var header;
			var value;
			parts = line.split(': ');
			header = parts.shift();
			value = parts.join(': ');
			headerMap[header] = value;
		});
		csp = headerMap['content-security-policy'].split(';').map(function (item) {
			return item.trim();
		}).filter(function (el) {
			return el !== '';
		});
		csp.forEach(function (policy) {
			if (policy.substring(0, 10) === 'script-src') {
				scriptSrc = policy.split(' ').map(function (item) {
					return item.trim();
				});
				scriptSrc.forEach(function (directive) {
					if (directive.substring(1, 7) === 'nonce-') {
						scriptSrcNonce = directive.substring(7).slice(0, -1);
					}
				});
			}
		});
		return scriptSrcNonce;
	};

	makeRequest = function (url, method) {
		var request = new XMLHttpRequest();
		return new Promise(function (resolve, reject) {
			request.onreadystatechange = function () {
				if (request.readyState !== 4) {
					return false;
				}
				if (request.status >= 200 && request.status < 300) {
					resolve(request);
				} else {
					reject({
						status: request.status,
						statusText: request.statusText
					});
				}
			};
// Setup our HTTP request
			request.open(method || 'GET', url, true);
// Send the request
			request.send();
		});
	};

	if (getNonceViaXhr) {
		makeRequest(document.URL, 'HEAD').then(
			function (request) {
				scriptNonce = getScriptNonce(request);
				if (!scriptNonce) {
					scriptNonce = 'DUMMY';
				}
				nonce = scriptNonce;
				closure(nonce);
			}
		).catch(function (error) {
			console.log(error);
		});
	} else {
		if (!nonce) {
			nonce = 'DUMMY';
		}
		closure(nonce);
	}

};


export default Object.freeze({
	main
});
