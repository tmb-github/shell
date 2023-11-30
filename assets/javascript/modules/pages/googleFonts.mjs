/**
* googleFonts.mjs
* Copyright (c) 2019-2023 Thomas M. Brodhead <https://bmt-systems.com>
* Released under the MIT license
* Date: 2023-11-02
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

	var o;
	var metaData;

	o = this;

	o.googleFonts.formLogic();
// always include this in every page.mjs, and execute it last in main():

// Always revise the meta data:
	metaData = returnMetaData(o);
	o.reviseMetaData(metaData);

	o.appendToCSS(':root', '{ --main-opacity: 1; }');

};

formLogic = function () {

	var clearOutputButton;
	var formOnSubmit;
	var o;

// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
	o = this;

	clearOutputButton = function () {
		(function (button) {
			if (button) {
				if (!button.classList.contains('click-listener')) {
					button.classList.add('click-listener');
					button.addEventListener('click', function (e) {
						(function (section) {
							if (section) {
								section.innerHTML = '';
							}
						}(document.querySelector('.output')));
						e.target.classList.remove('display');
					});
				}
			}
		}(document.querySelector('.clear-output')));
	};

	formOnSubmit = function (event) {
		var ajaxURL;
		var bot;
		var contactFormMutate;
		var elementBot;
		var googleFontUrlInput;
		var url;
		var submit;

		event.preventDefault();

		contactFormMutate = function () {

			var ajaxResponse;

			if (url === '') {
				alert("URL unspecified.\n\nPlease add URL and re-submit.\n");
			} else {

				submit.disabled = true;

				(function (p) {
					if (p) {
						p.classList.remove('display-none');
					}
				}(document.querySelector('.info-text .sending')));

				(function (p) {
					if (p) {
						p.classList.add('display-none');
					}
				}(document.querySelector('.info-text .sent')));

				(function (header) {
					if (header) {
						header.classList.add('draw');
					}
				}(document.querySelector('HEADER')));


// response:
				ajaxResponse = function (userData) {
					var html;
					var googleFontObject;
					var fontface;
					var parsedJson;

					html = '';
					submit.disabled = false;

					(function (p) {
						if (p) {
							p.classList.add('display-none');
						}
					}(document.querySelector('.info-text .sending')));

					(function (header) {
						if (header) {
							header.classList.remove('draw');
						}
					}(document.querySelector('HEADER')));


					(function (button) {
						if (button) {
							button.classList.add('display');
						}
					}(document.querySelector('.clear-output')));

// The userData will be whatever comes from print_r() in contact_process.php:

					if ((typeof userData !== 'string') || (userData !== 'success')) {

						parsedJson = JSON.parse(userData);

						googleFontObject = parsedJson[0];
						fontface = parsedJson[1];

						fontface = fontface.replaceAll(/\}@font-face/g, '} \n\n @font-face');

						Object.entries(googleFontObject).forEach(function (fontArray) {
							var fontFormat = fontArray[0];
							html += `
<table>
	<thead>
		<tr>
			<th scope="col" class="float-left">
				<strong>` + fontFormat + `</strong>
			</th>
		</tr>
	</thead>
	<tbody>`;
							if (fontArray[1].length !== 0) {
								Object.entries(fontArray[1]).forEach(function (entry) {
									var fontName = entry[0];
									var fontUrl = entry[1];
									fontUrl = fontUrl.replace("http://", "https://");
									html += `
		<tr>
			<td>
				<a href="` + fontUrl + `">
					` + fontName + `
				</a>
			</td>
		</tr>`;
								});
							} else {
									html += `
		<tr>
			<td>
				Empty
			</td>
		</tr>`;
							}
							html += `
	</tbody>
</table>`;
						});

						if (fontface.length !== 0) {
							html += `<pre>` + fontface + `</pre>`;
						} else {
							html += `<pre>fontface missing</pre>`;
						}

						(function (section) {
							if (section) {
								section.innerHTML = html;
							}
						}(document.querySelector('.output')));

					} else {

						(function (p) {
							if (p) {
								p.classList.remove('display-none');
							}
						}(document.querySelector('.info-text .sent')));

// Clear out the fields in case the user returns to the form again:
						googleFontUrlInput.value = '';
						elementBot.value = '';

					}
				};
				o.ajax.post(ajaxURL, {'url': url, 'bot': bot}, ajaxResponse, true);
			}
		};

		googleFontUrlInput = document.getElementById('google-font-url-input');
		url = googleFontUrlInput.value.trim();

// For honeypot bot-trap:
		elementBot = document.getElementById('input-bot');
		bot = elementBot.value.trim();

		submit = document.querySelector('.google-fonts-form input');
		ajaxURL = o.metaDataRootDir + 'includes/forms/google-fonts/google_font_process.php';

		(function (button) {
			if (button) {
				button.classList.remove('display');
			}
		}(document.querySelector('.clear-output')));


// qwer: For honeypot method:
		if (bot === '') {
			contactFormMutate();
		} else {
			alert('Hi Robot!');
		}

	};

	(function (form) {
		if (form) {
			if (!form.classList.contains('google-fonts-form-on-submit-listener')) {
				form.classList.add('google-fonts-form-on-submit-listener');
				form.addEventListener('submit', formOnSubmit);
			}
		}
	}(document.querySelector('.google-fonts-form')));

	clearOutputButton();

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
	_description = 'Google Fonts download page for ' + o.siteData.metaDescription;
	_page = 'google-fonts';
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
