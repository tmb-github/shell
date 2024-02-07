/**
* tmbAlert.js
* Copyright (c) 2019-2022 Thomas M. Brodhead <https://bmt-systems.com>
* Released under the MIT license
* Date: 2022-08-07; rev. 2024-01-08
*/

function tmbAlert(msg) {

	var addAlertHidden;
	var addAlertHiddenWhenClickingOutsideModal;
	var removeAlertHidden;

/////////////////
/// FUNCTIONS ///
/////////////////

	addAlertHidden = function (e) {

		var buttonPressed;

// hide the alert
		(function (element) {
			if (element && !element.classList.contains('tmb-alert-hidden')) {
				element.classList.add('tmb-alert-hidden');
			}
		}(document.querySelector('.tmb-alert')));


// Eliminate tabbing on dialog and its buttons:
		(function (div) {
			if (div) {
				div.removeAttribute('tabindex');
			}
		}(document.querySelector('.tmb-alert-dialog')));

		document.querySelectorAll('.tmb-alert-buttons button').forEach(function (button) {
			button.setAttribute('tabindex', '-1');
		});


// remove OK and CLOSE button listeners
		document.querySelectorAll('.tmb-alert .tmb-alert-button').forEach(function (button) {
			if (!button.classList.contains('tmb-alert-button-listener')) {
				button.classList.remove('tmb-alert-button-listener');
				button.removeEventListener('click', addAlertHidden);
			}
		});

// remove SVG button listener
		(function (element) {
			if (element && element.classList.contains('tmb-alert-svg-close-button-listener')) {
				element.classList.remove('tmb-alert-svg-close-button-listener');
				element.removeEventListener('click', addAlertHidden);
			}
		}(document.querySelector('.tmb-alert .svg-close-button')));

// remove modal background listener
		(function (element) {
			if (element && element.classList.contains('tmb-alert-modal-listener')) {
				element.classList.remove('tmb-alert-modal-listener');
				element.removeEventListener('click', addAlertHiddenWhenClickingOutsideModal);
			}
		}(document.querySelector('.tmb-alert .tmb-alert-modal')));

// Needed for Saatchi links...see editSaatchiLink() in common.mjs.
		if (e.target.classList.contains('tmb-cancel-button')) {
			buttonPressed = new Event('tmb-cancel-button-pressed');
		}
		if (e.target.classList.contains('tmb-ok-button')) {
			buttonPressed = new Event('tmb-ok-button-pressed');
		}

// In case How To Play alert is being closed in Number Up!:
		if (e.target.classList.contains('close-number-up-how-to-play')) {
			buttonPressed = new Event('close-number-up-how-to-play-pressed');
		}

// Added to the alert that is displayed after the first time
// the game settings are displayed:
		if (e.target.classList.contains('make-new-puzzle-alert')) {
			buttonPressed = new Event('close-make-new-puzzle-alert-pressed');
		}

		if (buttonPressed) {
			window.dispatchEvent(buttonPressed);
		}

// Remove .close-number-up-how-to-play class on OK button *ONLY*
// It's added dynamically when the How To Play alert is displayed.
// Leave .close-number-up-how-to-play class on SVG button at top of alert.
// It's hard-coded into the SVG HTML (which is added by the JavaScript).
		(function (element) {
			if (element) {
				element.classList.remove('close-number-up-how-to-play');
				element.classList.remove('make-new-puzzle-alert');
			}
		}(document.querySelector('.number-up .tmb-alert-button')));

	};

	removeAlertHidden = function () {
		(function (element) {
			if (element) {
				element.classList.remove('tmb-alert-hidden');
			}
		}(document.querySelector('.tmb-alert')));

// Activate tabbing on dialog and its buttons:
		(function (div) {
			if (div) {
				div.setAttribute('tabindex', '0');
// Set focus:
				div.focus();
			}
		}(document.querySelector('.tmb-alert-dialog')));

		document.querySelectorAll('.tmb-alert-buttons button').forEach(function (button) {
			button.setAttribute('tabindex', '0');
		});

	};

	addAlertHiddenWhenClickingOutsideModal = function (event) {
		if (event.target.classList.contains('tmb-alert-modal')) {
			addAlertHidden(event);
		}
	};

///////////////
/// ACTIONS ///
///////////////


	msg = msg.replaceAll('[', '<strong>');
	msg = msg.replaceAll(']', '</strong>');

// set the text of the alert
	(function (element) {
		if (element) {
			element.innerHTML = msg;
		}
	}(document.querySelector('.tmb-alert .tmb-alert-content')));

// add event listener on OK button:
	document.querySelectorAll('.tmb-alert .tmb-alert-button').forEach(function (button) {
		if (!button.classList.contains('tmb-alert-button-listener')) {
			button.classList.add('tmb-alert-button-listener');
			button.addEventListener('click', addAlertHidden);
		}
	});

	(function (element) {
		if (element && !element.classList.contains('tmb-alert-modal-listener')) {
			element.classList.add('tmb-alert-modal-listener');
			element.addEventListener('click', addAlertHiddenWhenClickingOutsideModal);
		}
	}(document.querySelector('.tmb-alert .tmb-alert-modal')));

// add event listener on SVG close button at top of alert:
	(function (element) {
		if (element && !element.classList.contains('tmb-alert-svg-close-button-listener')) {
			element.classList.add('tmb-alert-svg-close-button-listener');
			element.addEventListener('click', addAlertHidden);
		}
	}(document.querySelector('.tmb-alert .svg-close-button')));

// display the modal:
	removeAlertHidden();

}