/**
* contactAnchorEventListeners.mjs
* Copyright (c) 2019-2023 Thomas M. Brodhead <https://bmt-systems.com>
* Released under the MIT license
* Date: 2023-11-01
*/

var contactAnchorEventListeners;

contactAnchorEventListeners = function () {

/*

See: https://www.nelsonpires.com/development/protect-your-email-and-phone-number-from-spam-bots

The email and phone have been presented in reverse, spread across an internal 
SPAN, with the first part of the text in and ::after element, the middle part 
of the text in the SPAN itself, and the first part of the text in a ::before 
element. The A-anchor element has href=#.

This CSS styling reverses the text string so it appears normally:

	unicode-bidi: bidi-override; 
	direction: rtl

This routine swaps the href=# with the intended text during mouse and pointer
actions.

*/
	var mailTo = 'mailto:joe@example.com';
	var tel = 'tel:+10123456789';

	document.querySelectorAll('A.email').forEach(function (anchor) {
		if (!anchor.classList.contains('email-mouseover-mouseout-click-listener')) {
			anchor.addEventListener('mouseover', function () {
				anchor.setAttribute('href', mailTo);
			});
			anchor.addEventListener('mouseout', function () {
				anchor.setAttribute('href', '#');
			});
			anchor.addEventListener('click', function () {
				anchor.setAttribute('href', mailTo);
			});
			anchor.classList.add('email-mouseover-mouseout-click-listener');
		}
	});

	document.querySelectorAll('A.phone').forEach(function (anchor) {
		if (!anchor.classList.contains('phone-mouseover-mouseout-click-listener')) {
			anchor.addEventListener('mouseover', function () {
				anchor.setAttribute('href', tel);
			});
			anchor.addEventListener('mouseout', function () {
				anchor.setAttribute('href', '#');
			});
			anchor.addEventListener('click', function () {
				anchor.setAttribute('href', tel);
			});
			anchor.classList.add('phone-mouseover-mouseout-click-listener');
		}
	});

};

export {contactAnchorEventListeners};