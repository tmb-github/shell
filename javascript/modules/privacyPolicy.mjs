// NB: All functions will have the mjs file name as their parent property,
// so o.contact.main(), o.contact.formLogic(), etc.
// ALSO:
// Every mjs should have a main() function that is called by common.mjs inner()

var main;
var returnMetaData;


main = function () {

	var metaData;
	var o;

// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
	o = this;

	metaData = returnMetaData();
	o.reviseMetaData(metaData);

};

returnMetaData = function () {

	var _canonical;
	var _default;
	var _description;
	var _image;
	var _imageAlt;
	var _page;
	var _title;
	var metaData;

	_canonical = '${CANONICAL}';
	_default = '${DEFAULT}';
	_title = '${TITLE}';
	_description = 'Privacy Policy page description for SHELL application [70 characters are best here].';
	_page = 'privacy-policy';
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
	main,
	returnMetaData
});
