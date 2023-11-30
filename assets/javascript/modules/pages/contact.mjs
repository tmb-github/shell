/**
* contact.mjs
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

// reCAPTCHA and HONEYPOT code in this file; rem/unrem each appropriately

var formLogic;
var main;
var returnMetaData;

import {contactAnchorEventListeners} from "../shared/contactAnchorEventListeners.mjs";

// SAVE for reCAPTCHA
// var recaptchaEdits;

main = function () {

	var metaData;
	var o;

// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
	o = this;

	o.contact.formLogic();

// Save in case we reinstate reCAPTCHA:
//	o.contact.recaptchaEdits();

// Always revise the meta data:
	metaData = returnMetaData(o);
	o.reviseMetaData(metaData);

	o.contact.contactAnchorEventListeners();
// always include this in every page.mjs, and execute it last in main():
	o.appendToCSS(':root', '{ --main-opacity: 1; }');

};

formLogic = function () {

	var formOnSubmit;
	var o;

// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
	o = this;

	formOnSubmit = function (event) {
		var ajaxResponse;
		var ajaxURL;
		var bot;
		var contactFormMutate;
		var elementBot;
		var elementEmail;
		var elementMessage;
		var elementSender;
		var elementSubject;
		var email;
/*
// qwer: for reCAPTCHA
		var gRecaptchaResponse;
*/
		var message;
		var sender;
		var subject;
		var submit;

		event.preventDefault();

		contactFormMutate = function () {
			if ((sender === '') && (email === '') && (subject === '') && (message === '')) {
				alert("Nothing specified in any of the email composition fields.\n\nPlease add appropriate information and re-submit.\n");
			} else {
				if (sender === '') {
					alert("Name required in 'Your name' field.\n\nPlease add your name and re-submit.\n");
				} else {
					if (email === '') {
						alert("Email address required in 'Your email' field.\n\nPlease add your email and re-submit.\n");
					} else {
						if (subject === '') {
							alert("Subject required in 'Subject' field.\n\nPlease add subject and re-submit.\n");
						} else {
							if (message === '') {
								alert("Message required in 'Message' field.\n\nPlease add message and re-submit.\n");
							} else {

								submit.disabled = true;
								document.querySelectorAll('.info-text .sending').forEach(function (element) {
									element.classList.remove('display-none');
								});
								document.querySelectorAll('.info-text .sent').forEach(function (element) {
									element.classList.add('display-none');
								});
								document.querySelectorAll('header').forEach(function (element) {
									element.classList.add('draw');
								});
								ajaxResponse = function (userData) {
									document.querySelectorAll('.info-text .sending').forEach(function (element) {
										element.classList.add('display-none');
									});
									submit.disabled = false;
									document.querySelectorAll('header').forEach(function (element) {
										element.classList.remove('draw');
									});
									if ((typeof userData !== 'string') || (userData !== 'success')) {
// The userData will be whatever comes from print_r() in contact_process.php:
										alert(userData);
									} else {
										document.querySelectorAll('.info-text .sent').forEach(function (element) {
											element.classList.remove('display-none');
										});
// Clear out the fields in case the user returns to the form again:
										elementSender.value = '';
										elementEmail.value = '';

// qwer: For honeypot method:
										elementBot.value = '';

										elementSubject.value = '';
										elementMessage.value = '';
										window.location.href = o.metaDataRootDir + 'message-sent/';
									}
								};
/*
// qwer: for reCAPTCHA
								o.ajax.post(ajaxURL, {'sender': sender, 'email': email, 'subject': subject, 'message': message, 'g-recaptcha-response': gRecaptchaResponse}, ajaxResponse, true);
*/

// qwer: for non-reCAPTCHA/HONEYPOT:
								o.ajax.post(ajaxURL, {'sender': sender, 'email': email, 'subject': subject, 'message': message, 'bot': bot}, ajaxResponse, true);

							}
						}
					}
				}
			}
		};

		elementSender = document.getElementById('input-sender');
		elementEmail = document.getElementById('input-email');

// qwer: For honeypot method:
		elementBot = document.getElementById('input-bot');

		elementSubject = document.getElementById('input-subject');
		elementMessage = document.getElementById('input-message');

		sender = elementSender.value.trim();
		email = elementEmail.value.trim();

// qwer: For honeypot method:
		bot = elementBot.value.trim();

		subject = elementSubject.value.trim();
		message = elementMessage.value.trim();

/*
// qwer: for reCAPTCHA
		gRecaptchaResponse = document.getElementById('g-recaptcha-response').value;
		submit = document.querySelector('.recaptcha input');
*/

		submit = document.querySelector('.contact-form input');
		ajaxURL = o.metaDataRootDir + 'includes/forms/contact/contact_process.php';

/*
// qwer: for reCAPTCHA
		contactFormMutate();
*/


// qwer: For honeypot method:
		if (bot === '') {
			contactFormMutate();
		} else {
			alert('Hi Robot!');
		}

	};

	document.querySelectorAll('.contact-form').forEach(function (element) {
		if (!element.classList.contains('contact-form-on-submit-listener')) {
			element.classList.add('contact-form-on-submit-listener');
			element.addEventListener('submit', formOnSubmit);
		}
	});

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
	_description = 'Contact form of ' + o.siteData.metaDescription;
	_page = 'home';
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


/*
// qwer: for reCAPTCHA
recaptchaEdits = function () {

	var o;
	var recaptchaObserver;
	var windowRecaptchaEditsPerformed;

// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
	o = this;

	document.querySelectorAll('textarea.screen-reader#g-recaptcha-response').forEach(function (element) {
		element.parentNode.removeChild(element);
	});

	function recaptchaLoadedCallback() {
// When the 2nd iframe has been created by the recaptcha script, the iframes
// are both available and ready to edit:
		var recaptchaIframe2 = document.querySelector('iframe[title*="recaptcha challenge"]');
		if (recaptchaIframe2) {
			document.querySelectorAll('iframe').forEach(function (element) {
				element.removeAttribute('frameborder');
				element.removeAttribute('scrolling');
				element.setAttribute('title', 'reCAPTCHA');
			});

			document.querySelectorAll('#recaptcha textarea').forEach(function (element) {
				element.setAttribute('title', 'reCAPTCHA response');
			});
			o.fireCustomEvent('recaptchaEditsPerformed');
		}
	}
	recaptchaObserver = new MutationObserver(recaptchaLoadedCallback);
// OLD: recaptchaObserver.observe(document.body, {attributes: true, childList: true, subtree: true});
// We shouldn't observe changes to document.body, because the addition of a
// local javascript library (such as pureJsCarousel.js) will trigger the
// routine to run again:
	recaptchaObserver.observe(document.querySelector('.main'), {attributes: true, childList: true, subtree: true});

	windowRecaptchaEditsPerformed = function () {
		recaptchaObserver.disconnect();
		window.removeEventListener('recaptchaEditsPerformed', windowRecaptchaEditsPerformed, false);
	};

	if (o.windowEvents.recaptchaEditsPerformed === false) {
		window.addEventListener('recaptchaEditsPerformed', windowRecaptchaEditsPerformed);
		o.windowEvents.recaptchaEditsPerformed = true;
	}

};
*/

// qwer: for reCAPTCHA: restore recaptchaEdits to module export:
export default Object.freeze({
	formLogic,
	main,
	contactAnchorEventListeners,
	returnMetaData
});