/**
* pageDestroyer.mjs
* Copyright (c) 2019-2023 Thomas M. Brodhead <https://bmt-systems.com>
* Released under the MIT license
* Date: 2023-11-01
*/

// Every mjs file has a main() function that is called by common.mjs inner().
//
// Functions from common.mjs are accessed as methods of the 'o' object:
//
// o.commonMjsFunction();
//
// functions local to this module that need access to the 'o' object and its
// methods should receive the 'o' object as a parameter when called:
//
// localFunction(o);
//
// Local functions may also be called with o.[mjsFilePrefix].localFunction();
// but then, in their body, they must have an 'o' variable set equal to
// their 'this' keyword, as is done in main().

var formLogic;
var main;
var returnMetaData;


main = function () {

	var metaData;
	var o;

// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
	o = this;

// Always revise the meta data:
	metaData = returnMetaData(o);
	o.reviseMetaData(metaData);

	o.pageDestroyer.formLogic();
// always include this in every page.mjs, and execute it last in main():
	o.appendToCSS(':root', '{ --main-opacity: 1; }');

};

formLogic = function () {

	var checkboxListener;
	var formOnSubmit;
	var o;

// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
	o = this;

	checkboxListener = function () {
		document.querySelectorAll('.destroy-options input').forEach(function (input) {
			if (!input.classList.contains('click-listener')) {
				input.classList.add('click-listener');
				input.addEventListener('click', function (e) {
					if (e.target.checked) {
						e.target.setAttribute('checked', "");
					} else {
						e.target.removeAttribute('checked');
					}
				});
			}
		});
	};

	formOnSubmit = function (event) {

		var atLeastOneCheckboxIsChecked;
		var destructionFinished;
		var extractSubstringBeforeBrace;
		var extractSubstringFromBrace;
		var pageDestroyerPostFolder;
		var fetchRoutine;
		var formData;
		var inputArray;
		var options;
		var pageNames;
		var submit;
		var url;

		event.preventDefault();

//////////////////////
// DEFINE FUNCTIONS //
//////////////////////

		atLeastOneCheckboxIsChecked = function () {
			var checkboxes = Array.from(document.querySelectorAll('.destroy-options input'));
			return checkboxes.reduce(function (accumulator, currentValue) {
				return (accumulator || currentValue.checked);
			}, false);
		};

		destructionFinished = function () {
			(function (fetchProgressLine) {
				if (fetchProgressLine) {
					fetchProgressLine.classList.remove('active');
				}
			}(document.querySelector('.progress-line')));
			submit.disabled = false;
		};

		extractSubstringBeforeBrace = function (inputString) {
			var openingBraceIndex;
			var substringBeforeBrace;

// Find the position of the opening brace
			openingBraceIndex = inputString.indexOf('{');

// Check if the opening brace is found
			if (openingBraceIndex !== -1) {
// Use substring to get the substring before the opening brace
				substringBeforeBrace = inputString.substring(0, openingBraceIndex);
// Alternatively, you can use slice: const substringBeforeBrace = inputString.slice(0, openingBraceIndex);
				return substringBeforeBrace;
			} else {
// If '{' is not found, return the entire string
				return inputString;
			}
		};

		extractSubstringFromBrace = function (inputString) {
			var openingBraceIndex;
			var substringFromBrace;

// Find the position of the opening brace
			openingBraceIndex = inputString.indexOf('{');

// Check if the opening brace is found
			if (openingBraceIndex !== -1) {
// Use substring or slice to get the substring starting from the opening brace
// Variation with slice: const substringFromBrace = inputString.slice(openingBraceIndex);
				substringFromBrace = inputString.substring(openingBraceIndex);
				return substringFromBrace;
			} else {
// Return an empty string or handle the case where '{' is not found
				return '';
			}
		};


		fetchRoutine = function () {
			url = pageDestroyerPostFolder + 'upload1.php';
			fetch(url, options).then(o.fetchResponse).then(
				function (response) {

// An object of hashes of the succesfully deleted pages will be at end of response.message
// It may be an empty object.
					(function (hashObject) {
						if (hashObject) {
							Object.values(hashObject).forEach(function (hash) {
								document.querySelector('.destroy-options ul li:has(input[value="' + hash + '"])')?.remove();
							});
						}
					}(JSON.parse(extractSubstringFromBrace(response.message))));

// Remove the hash object before sending it on to o.fetchAppendToUploadStatusDiv():
					response.message = extractSubstringBeforeBrace(response.message);

					o.fetchAppendToUploadStatusDiv(response.message);
					return o.fetchResolve(response, '', 'Finished.');
				},
				o.fetchReject
			).then(destructionFinished);
		};

		(function (endpoint) {
			pageDestroyerPostFolder = (
				(window.location.host === 'localhost')
				? o.siteData.localhostUrl + endpoint
				: o.siteData.liveSiteUrl + endpoint
			);
		}('/includes/forms/admin/page-destroyer/'));

//////////////////////
// DEFINE VARIABLES //
//////////////////////

		o.fetchUploadStatusDiv = document.querySelector('.destruction-status');

		submit = document.querySelector('.submit-button button');

// event.target will be the FORM element itself:
		formData = new FormData(event.target);

		options = {
			method: 'POST',
			body: formData
		};

/////////////////////////
// KICK OFF THE ACTION //
/////////////////////////

// DO NOT PROCEDE IF NOTHING HAS BEEN CHECKED!

		if (atLeastOneCheckboxIsChecked()) {
// get array of all checked input boxes:
			inputArray = Array.prototype.slice.call(document.querySelectorAll('.destroy-options input[checked]'));
// get array of their titles:
			pageNames = inputArray.map(function (input) {
				return input.getAttribute('title');
			});

// Don't proceed without confirming:
			if (window.confirm('Proceed to delete: ' + pageNames.join(', ') + '?') === true) {

// Deactivate submit button while routine is working:
				submit.disabled = true;

// Clear the DIV where the fetch results are written:
				if (o.tmbTT.active) {
					o.fetchUploadStatusDiv.innerHTML = o.DOMPurify.sanitize('');
				} else {
					o.fetchUploadStatusDiv.innerHTML = '';
				}

// activate progress line:
				(function (fetchProgressLine) {
					if (fetchProgressLine) {
						fetchProgressLine.classList.add('active');
					}
				}(document.querySelector('.progress-line')));

// POST the data and let the compilation begin:
				fetchRoutine();

			}

		} else {
			window.alert('Select at least one page to delete.');
		}

	};

	checkboxListener();

	(function (form) {
		if (form) {
			if (!form.classList.contains('page-destroyer-form-on-submit-listener')) {
				form.classList.add('page-destroyer-form-on-submit-listener');
				form.addEventListener('submit', formOnSubmit);
			}
		}
	}(document.querySelector('.page-destroyer-form')));

};

returnMetaData = function (o) {

	var _canonical;
	var _default;
	var _description;
	var _image;
	var _imageAlt;
	var _page;
	var _title;
	var metaData;

// NB: Ensure that _description below matches $description in the corresponding meta-schema-overrides.inc.php for this page.

	_canonical = '${CANONICAL}';
	_default = '${DEFAULT}';
	_title = '${TITLE}';
	_description = 'Page Destroyer description for ' + o.siteData.metaDescription;
// NB: kabob-case:
	_page = 'page-destroyer';
	_image = _default;
	_imageAlt = _default;

	metaData = {
		page: _page,
		name: {
			"description": _description,
			"twitter:card": _default,
			"twitter:creator": _default,
			"twitter:description": _description,
			"twitter:site": _default,
			"twitter:title": _title,
			"twitter:url": _canonical,
			"twitter:image": _image,
			"twitter:image:alt": _imageAlt
		},
		property: {
			"og:description": _description,
			"og:image": _image,
			"og:image:alt": _imageAlt,
			"og:image:height": _default,
			"og:image:width": _default,
			"og:image:secure_url": _image,
			"og:image:type": _default,
			"og:site_name": _default,
			"og:title": _title,
			"og:type": _default,
			"og:url": _canonical
		}
	};

	return metaData;

};


// qwer: for reCAPTCHA: restore recaptchaEdits to module export:
export default Object.freeze({
	formLogic,
	main,
	returnMetaData
});
