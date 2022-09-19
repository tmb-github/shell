/**
* passiveSupport.js
* Date: 2019-05-18
*/
(function () {

	function passiveEventSupport() {
		var noop;
		var options;
		var support;

		if (window && window.addEventListener) {
			support = false;
			options = Object.defineProperty({}, 'passive', {
				get: function () {
					support = true;
				}
			});
// note: have to set and remove a no-op listener instead of null
// (which was used previously), because Edge v15 throws an error
// when providing a null callback.
// https://github.com/rafrex/detect-passive-events/pull/3
			noop = function () {
				return null;
			};
			window.addEventListener('testPassiveEventSupport', noop, options);
			window.removeEventListener('testPassiveEventSupport', noop, options);
			return support;
		}
	}

	if (passiveEventSupport() === true) {
		window.passiveSupport = {capture: false, passive: true};
	} else {
		window.passiveSupport = false;
	}

}());