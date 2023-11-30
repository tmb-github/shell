/**
* compile.mjs
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

	o.compile.formLogic();
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
		document.querySelectorAll('.compile-options input').forEach(function (input) {
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

		var compilationFinished;
		var compilePostFolder;
		var fetchRoutine;
		var formData;
		var options;
		var submit;
		var url;

		event.preventDefault();

//////////////////////
// DEFINE FUNCTIONS //
//////////////////////

		compilationFinished = function () {
			(function (fetchProgressLine) {
				if (fetchProgressLine) {
					fetchProgressLine.classList.remove('active');
				}
			}(document.querySelector('.progress-line')));
			submit.disabled = false;
		};

		fetchRoutine = function () {
			url = compilePostFolder + 'do_not_compile_static_html.php';
			o.fetchAppendToUploadStatusDiv('Setting $compile_static_html = false . . .');
			fetch(url, options
// FETCH 1:
			).then(o.fetchResponse).then(
				function (resolve) {
					o.fetchAppendToUploadStatusDiv(resolve.message);
					return o.fetchResolve(resolve, compilePostFolder + 'do_not_use_static_html.php', 'Setting $use_static_html = false . . .');
				},
				o.fetchReject
// FETCH:
			).then(o.fetchResponse).then(
				function (resolve) {
					o.fetchAppendToUploadStatusDiv(resolve.message);
					return o.fetchResolve(resolve, compilePostFolder + 'minify_scripts.php', 'Minifying javascript/scripts/*.js . . .');
				},
				o.fetchReject
// FETCH:
			).then(o.fetchResponse).then(
				function (resolve) {
					o.fetchAppendToUploadStatusDiv(resolve.message);
					return o.fetchResolve(resolve, compilePostFolder + 'minify_modules.php', 'Minifying javascript/modules/*.mjs . . .');
				},
				o.fetchReject
// FETCH:
			).then(o.fetchResponse).then(
				function (resolve) {
					o.fetchAppendToUploadStatusDiv(resolve.message);
					return o.fetchResolve(resolve, compilePostFolder + 'update_site_webmanifest.php', 'Updating site.webmanifest . . .');
				},
				o.fetchReject
// FETCH:
			).then(o.fetchResponse).then(
				function (resolve) {
					o.fetchAppendToUploadStatusDiv(resolve.message);
					return o.fetchResolve(resolve, compilePostFolder + 'update_browserconfig_xml.php', 'Updating browserconfig.xml . . .');
				},
				o.fetchReject
// FETCH:
			).then(o.fetchResponse).then(
				function (resolve) {
					o.fetchAppendToUploadStatusDiv(resolve.message);
					return o.fetchResolve(resolve, compilePostFolder + 'update_fontface_css.php', 'Updating font-face.css . . .');
				},
				o.fetchReject
// FETCH:
			).then(o.fetchResponse).then(
				function (resolve) {
					o.fetchAppendToUploadStatusDiv(resolve.message);
					return o.fetchResolve(resolve, compilePostFolder + 'compile_css.php', 'Updating compiled.css . . .');
				},
				o.fetchReject
// FETCH:
			).then(o.fetchResponse).then(
				function (resolve) {
					o.fetchAppendToUploadStatusDiv(resolve.message);
					return o.fetchResolve(resolve, compilePostFolder + 'update_individual_imports_css.php', 'Updating individual-imports.css . . .');
				},
				o.fetchReject
// FETCH:
			).then(o.fetchResponse).then(
				function (resolve) {
					o.fetchAppendToUploadStatusDiv(resolve.message);
					return o.fetchResolve(resolve, compilePostFolder + 'update_service_worker.php', 'Updating Service Worker . . .');
				},
				o.fetchReject
// FETCH:
			).then(o.fetchResponse).then(
				function (resolve) {
					o.fetchAppendToUploadStatusDiv(resolve.message);
					return o.fetchResolve(resolve, compilePostFolder + 'minify_service_worker.php', 'Minifying sw.js . . .');
				},
				o.fetchReject
// FETCH:
			).then(o.fetchResponse).then(
				function (resolve) {
					o.fetchAppendToUploadStatusDiv(resolve.message);
					return o.fetchResolve(resolve, compilePostFolder + 'compile_htaccess.php', 'Updating .htaccess . . .');
				},
				o.fetchReject
// FETCH:
			).then(o.fetchResponse).then(
				function (resolve) {
					o.fetchAppendToUploadStatusDiv(resolve.message);
					return o.fetchResolve(resolve, compilePostFolder + 'update_date_modified.php', 'Updating dateModified.txt . . .');
				},
				o.fetchReject
// FETCH:
			).then(o.fetchResponse).then(
				function (resolve) {
					o.fetchAppendToUploadStatusDiv(resolve.message);
					return o.fetchResolve(resolve, '', 'Finished.');
				},
				o.fetchReject
			).then(compilationFinished);
		};

		compilePostFolder = (
			(window.location.host === 'localhost')
			? o.siteData.localhostUrl + '/includes/forms/compile/'
			: o.siteData.liveSiteUrl + '/includes/forms/compile/'
		);

//////////////////////
// DEFINE VARIABLES //
//////////////////////

		o.fetchUploadStatusDiv = document.querySelector('.upload-status');

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

	};


	checkboxListener();

	(function (form) {
		if (form) {
			if (!form.classList.contains('compile-form-on-submit-listener')) {
				form.classList.add('compile-form-on-submit-listener');
				form.addEventListener('submit', formOnSubmit);
			}
		}
	}(document.querySelector('.compile-form')));

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
	_description = 'Compile page description for ' + o.siteData.metaDescription;
	_page = 'compile';
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
