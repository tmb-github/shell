/**
* pageMaker.mjs
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

var formWork;
var main;
var returnMetaData;

main = function () {

	var metaData;
	var o;

	o = this;

// If there are any utility modules needed on this page, run this function,
// passing as an argument a string with name of this module (camelCase, not kabob-case):
//	o.loadUtilityModules('pageMaker');

// Always revise the meta data:
	metaData = returnMetaData(o);
	o.reviseMetaData(metaData);

// always include this in every page.mjs, and execute it last in main():
	o.appendToCSS(':root', '{ --main-opacity: 1; }');

	o.pageMaker.formWork();

};

formWork = function () {

	var o;
	var pageUploadForm;
	var pageNameInput;
	var pageSlugInput;
	var pageMakePostFolder;

	o = this;

	pageUploadForm = document.querySelector('#page-upload-form');
	pageNameInput = document.querySelector('#page-name');
	pageSlugInput = document.querySelector('#page-slug');
	o.fetchUploadStatusDiv = document.querySelector('.upload-status');


	// WHEN FIRST CREATING THE WORK ONLY:
	if ((pageSlugInput) && (pageSlugInput.value === '')) {

// Automatically copy an edited version of the work name into the
// page_url input:
		if (pageNameInput) {
			if (!pageNameInput.classList.contains('name-input-keyup-listener')) {
				pageNameInput.classList.add('name-input-keyup-listener');
				pageNameInput.addEventListener('keyup', function (e) {
//
// Eliminate diacriticals:
// * remove any characters that is not standard alpha-numeric or space
// * convert to lower case
// * replaces sequences of white space characters with a single white space
// character
// * trim any leading or trailing white space characters
// * replace resulting single white spaces with a hyphen
//
// TEST CASE:
//
// Hello # , ' " / ? Gang Kinderdämmerung: Zoöpsia M. C. Escher’s Ménage à Trois
//
					pageSlugInput.value = e.target.value.normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/[^a-zA-Z0-9\-_\s]/g, '').toLowerCase().replace(/\s\s+/g, ' ').trim().replace(/\s/g, '-');

// If name begins with a numeral, prefix the URL with the letter "n",
// otherwise, it can't be selected from the page carousel (because
// hashed sections may not begin with a numeral).
					if (pageSlugInput.value.match(/^\d/)) {
						pageSlugInput.value = 'n' + pageSlugInput.value;
					}

				});
			}
		}
	}

/////////////////////////////////////////////

	if (!pageUploadForm.classList.contains('page-upload-form-submit-listener')) {

		pageUploadForm.classList.add('page-upload-form-submit-listener');

/*

// For deleting pages...SAVE:

// If we've cleared out any required field, then we can't proceed with a delete procedure.
// So, listen for the delete box being checked.
// If it is, eliminate all of the required attributes on INPUT elements.
// If it's unchecked, restore all of the required attributes on INPUT elements.
// So that we know which elements are required and which are not, a custom
// data-required="true" attribute is now (2020-05-21) added to those input elements.

		document.querySelectorAll('#delete-page').forEach(function (element) {
// Ensure we only add the listener once:
			if (!element.classList.contains('delete-page-checkbox-listener')) {
				element.classList.add('delete-page-checkbox-listener');
// For visual checking, we're adding data-checked to the delete checkbox.
// When it's false, the box should be unchecked, and vice-versa:
				element.dataset.checked = 'false';
// When the box is altered:
				element.addEventListener('change', function (event) {
// ...see if the box is checked or not:
					if (event.target.checked) {
						element.dataset.checked = 'true';
						document.querySelectorAll('input[data-required="true"]').forEach(function (inputElement) {
							inputElement.removeAttribute('required');
						});
					} else {
						element.dataset.checked = 'false';
						document.querySelectorAll('input[data-required="true"]').forEach(function (inputElement) {
// The only way to set the required attribute is *without* a value; setting it to true fails.
							inputElement.setAttribute('required', '');
						});
					}
				});
			}
		});
*/

		pageUploadForm.addEventListener('submit', function (e) {

// For deleting pages...SAVE:
//			var deletePage;
			var formData;
			var pageName;
			var pageNames;
			var pagePreview;
			var pageSlug;
			var pageSlugs;
			var msg;
			var newLine;
			var options;
			var url;
			var message;

			e.preventDefault();

			formData = new FormData(pageUploadForm);
			pageName = formData.get('page_name');
			pageSlug = formData.get('page_slug');
			newLine = '\r\n';
			options = {
				method: 'POST',
				body: formData
			};

// Set to true to debug:
			o.logToConsole = true;

// Go to resulting page:
			pagePreview = function () {
				if (o.fetchProgressLine) {
					o.fetchProgressLine.classList.remove('active');
				}
				if (o.fetchNoProblem) {
/*
// For deleting pages...SAVE:
					if (deletePage) {
						location.replace(o.baseHref + 'admin/page-editor/');
					} else {
						location.replace(o.baseHref + pageSlug + '/');
					}
*/

// Does not work: location.replace(o.baseHref + pageSlug + '/');
// o.siteData.pageDependencies[pageSlug] = {mjs: "'" + o.kabobCaseToCamelCase(pageSlug) + "'"};

				}
			};

			if (o.tmbTT.active) {
				o.fetchUploadStatusDiv.innerHTML = o.DOMPurify.sanitize('');
			} else {
				o.fetchUploadStatusDiv.innerHTML = '';
			}


// Page Name need to be in the form somewhere:

// NB:
// We have to check if the variables are populated.
// We have to ensure we're not overwriting an existing ID or Name and prompt
// user to change if desired.
// This can be done client-side with the custom data on the input elements.

			o.fetchNoProblem = true;
/*

// For deleting pages...SAVE:

			if (formData.get('delete_page') === 'on') {
				deletePage = true;
// Must not delete all-works:
				if (pageSlug === 'all-works') {
					window.alert('This is a reserved directory, and cannot be deleted.');
					return false;
				}
				if (!window.confirm('This entire page will be deleted. Is this what you want?')) {
					return false;
				}
			} else {
				deletePage = false;
				if (pageSlug === 'all-works') {
					window.alert('This is a reserved directory, and cannot be altered.');
					return false;
				}
			}
*/

// For deleting pages...SAVE:
//			if (deletePage === false) {

			pageNameInput = document.querySelector('#page-name');
			if (pageNameInput && pageNameInput.dataset.existingPageNames) {
				pageNames = pageNameInput.dataset.existingPageNames.split(' | ').map(function (item) {
					return item;
				});
			} else {
				pageNames = [];
			}

			pageSlugInput = document.querySelector('#page-slug');
			if (pageSlugInput && pageSlugInput.dataset.existingPageSlugs) {
				pageSlugs = pageSlugInput.dataset.existingPageSlugs.split(' | ').map(function (item) {
					return item;
				});
			} else {
				pageSlugs = [];
			}

			if ((pageNames.includes(pageName)) && (pageSlugs.includes(pageSlug))) {
				msg = '';
				msg += 'A page with this name (' + pageName + ') already exists.' + newLine;
				msg += 'A page with this URL (' + pageSlug + ') already exists.' + newLine;
				msg += newLine;
				msg += 'Try another page name.' + newLine;
				window.alert(msg);
				return false;
			} else if (pageNames.includes(pageName)) {
				msg = '';
				msg += 'A page with this name (' + pageName + ') already exists.' + newLine;
				msg += newLine;
				msg += 'Existing Name: ' + pageName + newLine;
				msg += 'Existing URL: ' + pageSlugs[pageNames.indexOf(pageName)] + newLine;
				msg += newLine;
				msg += 'Try another page name.';
				window.alert(msg);
				return false;
			} else if (pageSlugs.includes(pageSlug)) {
				msg = '';
				msg += 'A page with this URL (' + pageSlug + ') already exists.' + newLine;
				msg += newLine;
				msg += 'Existing Name: ' + pageNames[pageSlugs.indexOf(pageSlug)] + newLine;
				msg += 'Existing URL: ' + pageSlug + newLine;
				msg += newLine;
				msg += 'Try another page name.';
				window.alert(msg);
				return false;
			}

// For deleting pages...SAVE:
//			}

			o.fetchProgressLine = document.querySelector('.progress-line');

			if (o.fetchProgressLine) {
				o.fetchProgressLine.classList.add('active');
			}

			formData.append('page_slug', pageSlug);
			formData.append('page_name', pageName);

			url = pageMakePostFolder + 'upload1.php';

/*

// For deleting pages...SAVE:

			if (deletePage) {
				msg = 'Deleting page . . .';
			} else {
				msg = 'Creating page and related files . . .';
			}
*/
			msg = "Creating page and related files . . .";

			o.fetchAppendToUploadStatusDiv(msg);
			o.consoleLog(msg);

			fetch(url, options
// UPLOAD1: window.location.assign(o.baseHref + pageSlug + '/');
			).then(o.fetchResponse).then(
				function (resolve) {
// Without first opening DevTools, the JavaScript for the page will not run.
// This is an icky stop-gap, but it works:
					return o.fetchResolve(resolve, '', 'Finished. Open <strong>DevTools</strong> and navigate to: <a class=internal-anchor href="' + o.baseHref + pageSlug + '/' + '">' + pageName + '</a>');
				},
				o.fetchReject
			).then(pagePreview);
		});
	}

	pageMakePostFolder = (
		(window.location.host === 'localhost')
		? o.siteData.localhostUrl + '/includes/forms/page-maker/'
		: o.siteData.liveSiteUrl + '/includes/forms/page-maker/'
	);


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
	_description = 'Page Maker of ' + o.siteData.metaDescription;
	_page = 'dummy';
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
	formWork,
	main,
	returnMetaData
});
