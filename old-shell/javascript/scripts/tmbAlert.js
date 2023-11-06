/**
* tmbAlert.js
* Copyright (c) 2019-2022 Thomas M. Brodhead <https://bmt-systems.com>
* Released under the MIT license
* Date: 2022-08-07
*/

function tmbAlert(msg) {

	var addAlertHidden;
	var removeAlertHidden;

	addAlertHidden = function (e) {

		var buttonPressed;

		document.querySelectorAll('.tmb-alert').forEach(function (element) {
			if (!element.classList.contains('tmb-alert-hidden')) {
				element.classList.add('tmb-alert-hidden');
			}
		});

		document.querySelectorAll('.tmb-alert .tmb-alert-button').forEach(function (element) {
			if (element.classList.contains('tmb-alert-button-listener')) {
				element.classList.remove('tmb-alert-button-listener');
				element.removeEventListener('click', addAlertHidden);
			}
		});

		document.querySelectorAll('.tmb-alert .svg-close-icon').forEach(function (element) {
			if (element.classList.contains('svg-close-icon-listener')) {
				element.classList.remove('svg-close-icon-listener');
				element.removeEventListener('click', addAlertHidden);
			}
		});

// Needed for Saatchi links...see editSaatchiLink() in common.mjs.
		if (e.target.classList.contains('tmb-cancel-button')) {
			buttonPressed = new Event('tmb-cancel-button-pressed');
		}
		if (e.target.classList.contains('tmb-ok-button')) {
			buttonPressed = new Event('tmb-ok-button-pressed');
		}

		if (buttonPressed) {
			window.dispatchEvent(buttonPressed);
		}

	};

	removeAlertHidden = function () {
		document.querySelectorAll('.tmb-alert').forEach(function (element) {
			element.classList.remove('tmb-alert-hidden');
		});
	};

// set the text of the alert
	document.querySelectorAll('.tmb-alert .tmb-alert-content').forEach(function (element) {
		element.innerHTML = msg;
	});


// add event listener on OK button:
	document.querySelectorAll('.tmb-alert .tmb-alert-button').forEach(function (element) {
		if (!element.classList.contains('tmb-alert-button-listener')) {
			element.classList.add('tmb-alert-button-listener');
			element.addEventListener('click', addAlertHidden);
		}
	});

	document.querySelectorAll('.tmb-alert .svg-close-icon').forEach(function (element) {
		if (!element.classList.contains('tmb-alert-close-icon-listener')) {
			element.classList.add('tmb-alert-close-icon-listener');
			element.addEventListener('click', addAlertHidden);
		}
	});

// display the modal:
	removeAlertHidden();

}