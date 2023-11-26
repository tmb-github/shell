/**
* error.mjs
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

var main;
var returnMetaData;
var editPageText;

main = function () {

	var metaData;
	var o;

// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
	o = this;

	metaData = returnMetaData(o);
	o.reviseMetaData(metaData);
	o.error.editPageText();

// To stop the line that's drawn repeatedly at the bottom of the header.
// This can happen if we get a 400 error during email contact form
// submission, which will have the line carried over from the start of the
// sending process.
	(function (header) {
		if (header) {
			header.classList.remove('draw');
		}
	}(document.querySelector('header')));

// always include this in every page.mjs, and execute it last in main():
	o.appendToCSS(':root', '{ --main-opacity: 1; }');

};

editPageText = function () {

	var o;
	var returnErrorHeading;
	var returnErrorMessage;

	o = this;

	returnErrorHeading = function (httpStatus) {
// ensure argument is number:
		httpStatus = Number(httpStatus);
		return (
			(httpStatus === 200)
			? "Error Page"
			: (httpStatus === 400)
			? "400: Bad Request"
			: (httpStatus === 403)
			? "403: Forbidden"
			: (httpStatus === 404)
			? "404: Not Found"
			: (httpStatus === 500)
			? "500: Internal Server Error"
			: "XXX: Unknown Error"
		);
	};

	returnErrorMessage = function (httpStatus) {
// ensure argument is number:
		httpStatus = Number(httpStatus);
		return (
			(httpStatus === 200)
			? "You've reached the error page without causing an error. Well done!"
			: (httpStatus === 400)
			? "The server couldn't understand your request."
			: (httpStatus === 403)
			? "You don't have permission to access that resource."
			: (httpStatus === 404)
			? "The page you've specified doesn't exist."
			: (httpStatus === 500)
			? "An internal error has flummoxed the server."
			: "No information can be provided."
		);
	};

	(function (main) {
		if (main?.dataset?.httpStatus) {
			(function (h1) {
				if (h1) {
					h1.innerHTML = returnErrorHeading(main.dataset.httpStatus);
				}
			}(document.querySelector('.main h1.error-heading')));
			(function (p) {
				if (p) {
					p.innerHTML = returnErrorMessage(main.dataset.httpStatus);
				}
			}(document.querySelector('.main p.error-message')));
			(function (title) {
				if (title && title.innerHTML.includes('|')) {
// Replace everything from the start to the first "|"
					title.innerHTML = title.innerHTML.replace(/^.*?\|/, 'Error ' + main.dataset.httpStatus + ' |');

// 2023-11-26
// Bandaid solution to 500 error test, perhaps other errors. The 500 error test
// initially is registered as a 400 error, with 3 meta values not receiving the
// correct status codes. This routine corrects for that.
					(function (dataTitleContent) {
						var pipeIndex;
						var revisedTitle;
						if (dataTitleContent) {
							pipeIndex = dataTitleContent.indexOf('|');
							if (pipeIndex !== -1) {
								revisedTitle = 'Error ' + o.httpStatus + ' ' + dataTitleContent.substring(pipeIndex);
								document.querySelector('MAIN')?.setAttribute('data-title', revisedTitle);
								document.querySelector('META[name="twitter:title"]')?.setAttribute('content', revisedTitle);
								document.querySelector('META[property="og:title"]')?.setAttribute('content', revisedTitle);
							}
						};
					}(document.querySelector('MAIN')?.getAttribute('data-title')));

				}
			}(document.querySelector('TITLE')));
		}
		main.classList.add('opacity-1');
	}(document.querySelector('MAIN')));


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

//	_description = 'Error 404 page description for ' + o.siteData.metaDescription;
//	_page = 'error';
	_image = _default;
	_imageAlt = _default;

	(function (main) {
		if (main?.dataset?.httpStatus) {
			_description = 'Error ' + main.dataset.httpStatus + ' page description for ' + o.siteData.metaDescription;
			_page = 'error-' + main.dataset.httpStatus;
		}
	}(document.querySelector('MAIN')));

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
	main,
	editPageText,
	returnMetaData
});
