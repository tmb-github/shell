/**
* emptyRecycleBin.mjs
* Copyright (c) 2019-2023 Thomas M. Brodhead <https://bmt-systems.com>
* Released under the MIT license
* Date: 2023-12-09
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

	o = this;

// If there are any utility modules needed on this page, run this function,
// passing as an argument a string with name of this module (camelCase, not kabob-case):
//	o.loadUtilityModules('emptyRecycleBin');

// Always revise the meta data:
	metaData = returnMetaData(o);
	o.reviseMetaData(metaData);

	o.emptyRecycleBin.formLogic();
// always include this in every page.mjs, and execute it last in main():
	o.appendToCSS(':root', '{ --main-opacity: 1; }');

};

formLogic = function () {

	var formOnSubmit;
	var o;

// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
	o = this;

	formOnSubmit = function (event) {
		var ajaxURL;
		var ajaxResponse;
		var contactFormMutate;
		var submit;

		event.preventDefault();

		contactFormMutate = function () {
			submit.disabled = true;
			ajaxResponse = function (userData) {
				console.log(userData);
				var userDataObj = JSON.parse(userData);
				document.querySelector('.message').innerHTML = userDataObj.message;
				submit.disabled = false;
			};
			o.ajax.post(ajaxURL, {}, ajaxResponse, true);
		};

		submit = document.querySelector('.empty-recycle-bin-form button');
		ajaxURL = o.metaDataRootDir + 'includes/forms/admin/empty-recycle-bin/empty_recycle_bin_process.php';

		contactFormMutate();

	};

	(function (form) {
		if (form) {
			if (!form.classList.contains('empty-recycle-bin-form-on-submit-listener')) {
				form.classList.add('empty-recycle-bin-form-on-submit-listener');
				form.addEventListener('submit', formOnSubmit);
			}
		}
	}(document.querySelector('.empty-recycle-bin-form')));

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
	_description = 'Empty Recycle Bin of ' + o.siteData.metaDescription;
// NB: kabob-case:
	_page = 'empty-recycle-bin';
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