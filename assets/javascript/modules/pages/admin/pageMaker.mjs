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


//o.onResizeEdits();

};

formWork = function () {

	var o;
	var uploadForm;
	var titleInput;
	var slugInput;
	var pageMakerPostFolder;

	o = this;

	uploadForm = document.querySelector('#upload-form');
	titleInput = document.querySelector('#title-input');
	slugInput = document.querySelector('#slug-input');
	o.fetchUploadStatusDiv = document.querySelector('.upload-status');


	// WHEN FIRST CREATING THE WORK ONLY:
	if ((slugInput) && (slugInput.value === '')) {

// Automatically copy an edited version of the work name into the
// page_url input:
		if (titleInput) {
			if (!titleInput.classList.contains('name-input-keyup-listener')) {
				titleInput.classList.add('name-input-keyup-listener');
				titleInput.addEventListener('keyup', function (e) {
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
					slugInput.value = e.target.value.normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/[^a-zA-Z0-9\-_\s]/g, '').toLowerCase().replace(/\s\s+/g, ' ').trim().replace(/\s/g, '-');

// If name begins with a numeral, prefix the URL with the letter "n",
// otherwise, it can't be selected from the page carousel (because
// hashed sections may not begin with a numeral).
					if (slugInput.value.match(/^\d/)) {
						slugInput.value = 'n' + slugInput.value;
					}

				});
			}
		}
	}

/////////////////////////////////////////////

	if (!uploadForm.classList.contains('upload-form-submit-listener')) {

		uploadForm.classList.add('upload-form-submit-listener');

		uploadForm.addEventListener('submit', function (e) {

			var admin;
			var formData;
			var pagePreview;
			var msg;
			var newLine;
			var options;
			var url;
			var title;
			var titles;
			var slug;
			var slugs;
			var titlePrefix;
			var slugPrefix;
			var fullTitle;
			var fullSlug;

			e.preventDefault();

			formData = new FormData(uploadForm);
			title = formData.get('title');
			slug = formData.get('slug');
// if 'admin' input is checked, this will return TRUE
			admin = formData.has('admin');

			slugPrefix = (
				(admin)
				? 'admin/'
				: ''
			);

			titlePrefix = (
				(admin)
				? 'Admin: '
				: ''
			);

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

// To go directly to the newly created page:
//				window.location.href = o.baseHref + slug + '/';

			};

			if (o.tmbTT.active) {
				o.fetchUploadStatusDiv.innerHTML = o.DOMPurify.sanitize('');
			} else {
				o.fetchUploadStatusDiv.innerHTML = '';
			}

// We have to check if the variables are populated.
// We have to ensure we're not overwriting an existing ID or Name and prompt
// user to change if desired.
// This can be done client-side with the custom data on the input elements.

			o.fetchNoProblem = true;

			if (titleInput && titleInput.dataset.existingTitles) {
				titles = titleInput.dataset.existingTitles.split(' | ').map(function (item) {
					return item;
				});
			} else {
				titles = [];
			}

			if (slugInput && slugInput.dataset.existingSlugs) {
				slugs = slugInput.dataset.existingSlugs.split(' | ').map(function (item) {
					return item;
				});
			} else {
				slugs = [];
			}

			fullTitle = titlePrefix + title;
			fullSlug = slugPrefix + slug;

			if ((titles.includes(fullTitle)) && (slugs.includes(fullSlug))) {
				msg = '';
				msg += 'A page with this title (' + fullTitle + ') already exists.' + newLine;
				msg += 'A page with this slug (' + fullSlug + ') already exists.' + newLine;
				msg += newLine;
				msg += 'Try another page title.' + newLine;
				window.alert(msg);
				return false;
			}

			if (titles.includes(fullTitle)) {
				msg = '';
				msg += 'A page with this title (' + fullTitle + ') already exists.' + newLine;
				msg += newLine;
				msg += 'Existing title: ' + fullTitle + newLine;
				msg += 'Existing slug: ' + slugs[titles.indexOf(fullTitle)] + newLine;
				msg += newLine;
				msg += 'Try another page title.';
				window.alert(msg);
				return false;
			}

			if (slugs.includes(fullSlug)) {
				msg = '';
				msg += 'A page with this slug (' + fullSlug + ') already exists.' + newLine;
				msg += newLine;
				msg += 'Existing title: ' + titles[slugs.indexOf(fullSlug)] + newLine;
				msg += 'Existing slug: ' + fullSlug + newLine;
				msg += newLine;
				msg += 'Try another page title.';
				window.alert(msg);
				return false;
			}

			o.fetchProgressLine = document.querySelector('.progress-line');

			if (o.fetchProgressLine) {
				o.fetchProgressLine.classList.add('active');
			}

//			formData.append('slug', slug);
//			formData.append('title', title);

			url = pageMakerPostFolder + 'upload1.php';

			msg = "Creating page and related files . . .";

			o.fetchAppendToUploadStatusDiv(msg);
			o.consoleLog(msg);

			fetch(url, options
// UPLOAD1: window.location.assign(o.baseHref + slug + '/');
			).then(o.fetchResponse).then(
				function (response) {
					return o.fetchResolve(response, '', 'Finished. Open : <a class=internal-anchor href="' + o.baseHref + slugPrefix + slug + '/' + '">' + titlePrefix + title + '</a>');
				},
				o.fetchReject
			).then(pagePreview);
		});
	}

	(function (endpoint) {
		pageMakerPostFolder = (
			(window.location.host === 'localhost')
			? o.siteData.localhostUrl + endpoint
			: o.siteData.liveSiteUrl + endpoint
		);
	}('/includes/forms/admin/page-maker/'));


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
// NB: kabob-case:
	_page = 'page-maker';
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
