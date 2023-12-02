/**
* login.mjs
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

	o.login.formLogic();
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
		var hideUsernamePasswordUnrecognized;
		var loginFormMutate;
		var password;
		var passwordInput;
		var username;
		var usernameInput;
		var showUsernamePasswordUnrecognized;
		var submit;

		event.preventDefault();

		loginFormMutate = function () {

			var ajaxResponse;

			if ((username === '') && (password === '')) {
				alert("Nothing specified in either field.\n\nPlease add appropriate information and re-submit.\n");
			} else {
				if (username === '') {
					alert("Username required.\n\nPlease add username and re-submit.\n");
				} else {
					if (password === '') {
						alert("Password required.\n\nPlease add password and re-submit.\n");
					} else {
console.log('xxx');
						submit.disabled = true;
// response:
						ajaxResponse = function (userData) {
							usernameInput.value = '';
							passwordInput.value = '';
							submit.disabled = false;
							if (userData === 'true') {
								window.location.href = (
									(window.location.host === 'localhost')
									? o.siteData.localhostUrl
									: o.siteData.liveSiteUrl
								);
// It may be advisable to add:
// window.location.reload();
// See: https://stackoverflow.com/questions/41020403/reload-a-page-with-location-href-or-window-location-reloadtrue
							} else {
								showUsernamePasswordUnrecognized();
							}
						};

						o.ajax.post(ajaxURL, {'username': username, 'password': password}, ajaxResponse, true);

					}
				}
			}
		};

		hideUsernamePasswordUnrecognized = function () {
			(function (message) {
				if (message) {
					message.classList.remove('show');
				}
			}(document.querySelector('.username-password-unrecognized')));
		};

		showUsernamePasswordUnrecognized = function () {
			(function (message) {
				if (message) {
					message.classList.add('show');
				}
			}(document.querySelector('.username-password-unrecognized')));
		};

		usernameInput = document.getElementById('username-input');
		username = usernameInput.value.trim();
		usernameInput.addEventListener('focus', hideUsernamePasswordUnrecognized);

		passwordInput = document.getElementById('password-input');
		password = passwordInput.value.trim();
		passwordInput.addEventListener('focus', hideUsernamePasswordUnrecognized);

		submit = document.querySelector('.login-form input');
		ajaxURL = o.metaDataRootDir + 'includes/forms/admin/login/login_process.php';

		loginFormMutate();

	};

	(function (form) {
		if (form) {
			if (!form.classList.contains('login-form-on-submit-listener')) {
				form.classList.add('login-form-on-submit-listener');
				form.addEventListener('submit', formOnSubmit);
			}
		}
	}(document.querySelector('.login-form')));

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
	_description = 'Login page description for ' + o.siteData.metaDescription;
	_page = 'login';
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
