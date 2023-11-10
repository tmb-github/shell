/**
* siteCommon.mjs
* Copyright (c) 2019-2023 Thomas M. Brodhead <https://bmt-systems.com>
* Released under the MIT license
* Date: 2023-11-01
*/

// There's more than one INPUT in the section:
var clearInput;
var clearOutput;
var corsProxy;
var dataUtility;
var endpointSelectors;
var fetchJson;
var fetchProgress;
var fetchStatus;
var fieldsetControlsAndInfo;
var formatTime;
var formClasses;
var loadUtilityModules;
var setListeners;
var setSearchInputAriaLabel;
var setSearchInputSize;
var utilities;
var writeInfo;


clearInput = function (e) {
	var o;
	o = this;

	o.utility = e.target.closest('section').dataset?.utility;
	document.querySelectorAll(o.dataUtility() + ' ' + o.formClasses(e.target) + ' input').forEach(function (element) {
		element.value = '';
	});
};

clearOutput = function (e) {

	var o;
	o = this;

	var fetchClasses;
	var fetchDivSelectors;

	o.utility = e.target.closest('section').dataset?.utility;

	fetchClasses = ['.fetch-status', '.fetch-progress', '.fetch-output'];

	fetchDivSelectors = fetchClasses.map(function (div) {
		return o.dataUtility() + ' ' + o.formClasses(e.target) + ' ' + div;
	}).join(', ');

	document.querySelectorAll(fetchDivSelectors).forEach(function (element) {
		element.innerHTML = '';
	});
};

corsProxy = 'https://corsproxy.io/?';

dataUtility = function () {
	var o;
	o = this;
	return '[data-utility="' + o.utility + '"]';
};

fetchJson = function (url, callback) {

	var o;
	o = this;

	fetch(url).then(
		function (response) {
			if (!response.ok) {
				throw new Error('Network error');
			}
			return response.json();
		}
	).then(callback).catch(function (error) {
		console.error('There was a problem with the fetch operation:', error);
		o.fetchProgress('<strong>Failure:</strong> Bad endpoint or server error.', false);
	});
};

fetchProgress = function (msg, proceed) {

	var o;
	o = this;

// Create P element inside DIV if it doesn't already exist:
	(function (p) {
		if (!p) {
			p = document.createElement('P');
			document.querySelector(o.dataUtility() + ' .fetch-progress')?.appendChild(p);
		}
	}(document.querySelector(o.dataUtility() + ' .fetch-progress p')));

// Update progress:
	(function (element) {
		var fragment;
		if (element) {
			fragment = document.createRange().createContextualFragment('<span>' + msg + '</span>');
			element.appendChild(fragment);
		}
	}(document.querySelector(o.dataUtility() + ' .fetch-progress p')));

	if (!proceed) {
// Stop printing dots to progress status
		if (o.intervalId) {
			window.clearInterval(o.intervalId);
		}
// Remove data-fetching attribute from section
		(function (section) {
			if (section) {
				section.removeAttribute('data-fetching');
			}
		}(document.querySelector('section[data-fetching]')));
	}

};

fetchStatus = function (msg) {
	var o;
	o = this;
	(function (element) {
		var fragment;
		if (element) {
			fragment = document.createRange().createContextualFragment('<p>' + msg + '</p>');
			element.appendChild(fragment);
		}
	}(document.querySelector(o.dataUtility() + ' .fetch-status')));
};

formatTime = function (milliseconds) {
	var minutes = Math.floor((milliseconds / 1000 / 60) % 60);
	var seconds = Math.floor((milliseconds / 1000) % 60);
	return [
		minutes.toString(),
		seconds.toString().padStart(2, '0')
	].join(':');
};

formClasses = function (element) {
	return element.closest('form').getAttribute('class').split(' ').map(function (className) {
		return '.' + className;
	}).join('');
};

// If a page has a section with class=utility, then the data-utility attribute indicates
// a utility module that should be run on that page.
// This allows different modules to be loaded dynamically based on element attribute mark-up.
// E.g., <section class="utility" data-utility="magnum-ott"></section>
// The argument 'page' is string with name of the calling module (e.g. home.mjs = 'home', myLife.mjs = 'myLife')
loadUtilityModules = function (page) {

	var loadErrors;
	var metaData;
	var modules;
	var orphanedUtilities;
	var utilityModules;
	var o;
	var promiseArray;

// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
	o = this;

// For each section with a class of 'utility', save the kebob-case name of the
// utility, found in data-utility on the element, to o.utilities[]
// Write the camelCase version of the utility names to utilityModules[]
	o.utilities = [];
	utilityModules = [];
	document.querySelectorAll('.utility').forEach(function (section) {
		o.utilities.push(section.dataset.utility);
		(function (camel) {
			if (utilityModules.indexOf(camel) === -1) {
				utilityModules.push(camel);
			}
		}(o.kebobCaseToCamelCase(section.dataset.utility)));
	});

// Create an array of dynamic import promises in promiseArray[].
// Write camelCase names of modules to modules[]
	loadErrors = false;
	promiseArray = [];
	modules = [];
	orphanedUtilities = [];
	utilityModules.forEach(function (module) {
		promiseArray.push(
			import('./utilities/' + module + '.mjs').then(function ({default: object}) {
				modules.push(module);
				o.assignToModulePropertyOnCommonObject(o.pageNameCamelCase, object);
// qwer
// 2023-11-10
// Fire custom event that the module is now loaded (= assigned to the common object)
				o.fireCustomEvent(module + 'ModuleLoaded');
			}).catch(function () {
				loadErrors = true;
				orphanedUtilities.push(o.camelCaseToKebobCase(module));
			})
		);
	});

	Promise.all(promiseArray).then(function () {
		if (loadErrors) {
			console.error("Warning: One or more data-utility attributes lack corresponding modules:\r\n" + orphanedUtilities.join('\r\n'));
		}
		metaData = o[page].returnMetaData(o);
		o.reviseMetaData(metaData);

// run each of the modules
		modules.forEach(function (module) {
			o[page][module]();
// qwer
// 2023-11-10
// Fire custom event that the module has been executed.
// This allows code that's dependent on the modules only to execute
// when it's available, by wrapping it in a window.addEventListener()
// that listens for the custom event name, which will just be the 
// module name + 'ModuleExecuted':
			o.fireCustomEvent(module + 'ModuleExecuted');
		});

	});

};

// Listeners:
setListeners = function (callback) {

	var o;
	o = this;

// Set click listener on main button:
	(function (button) {
		if (button && !button.classList.contains('click-listener')) {
			button.classList.add('click-listener');
			button.addEventListener('click', function (e) {
				(function (section) {
					if (section) {
						section.setAttribute('data-fetching', true);
					}
				}(e.target.closest('section')));
				o.clearOutput(e);
				callback();
			});
		}
	}(document.querySelector(o.dataUtility() + ' button.display-output')));

// set listener on clear input button
	(function (button) {
		if (button && !button.classList.contains('click-listener')) {
			button.classList.add('click-listener');
			button.addEventListener('click', o.clearInput);
		}
	}(document.querySelector(o.dataUtility() + ' button.clear-input')));

// set listener on clear output button
	(function (button) {
		if (button && !button.classList.contains('click-listener')) {
			button.classList.add('click-listener');
			button.addEventListener('click', o.clearOutput);
		}
	}(document.querySelector(o.dataUtility() + ' button.clear-output')));

};

utilities = Array.from(document.querySelectorAll('section.utility')).map(function (section) {
	return section.dataset?.utility;
});

// print utilities to console:
//console.log(o.utilities.join('\r\n'));

writeInfo = function (data, func) {

	var fragment;
	var o;

	o = this;

	o.html = '';

// sets specific UL html for the data in question:
	func(data);

	o.fetchProgress('<strong><em>Success!</em></strong>', false);
	fragment = document.createRange().createContextualFragment(o.html);

	(function (element) {
		if (element) {
			element.innerHTML = '';
			element.appendChild(fragment);
		}
	}(document.querySelector(o.dataUtility() + ' .fetch-output')));

// This must be run every time content is replaced in .fetch-output DIV,
// so that there will be listeners on the newly added inputs.
//
// Ensure the scroll is at the left side of the horizontal list, regardless of
// whether it's in normal order or reversed
// Also, set 'checked' attribute to display the internal checked state on input checkboxes:
	document.querySelectorAll(o.dataUtility() + ' .fetch-output input').forEach(function (input) {
		if (!input.classList.contains('click-listener')) {
			input.classList.add('click-listener');
			input.addEventListener('click', function (e) {
				if (e.target.checked) {
					e.target.setAttribute('checked', "");
				} else {
					e.target.removeAttribute('checked');
				}
// There may be multiple ULs in the output:
				e.target.closest('.fetch-output')?.querySelectorAll('ul').forEach(function (ul) {
					ul.scrollBy((ul.scrollWidth * -1), 0);
				});
			});
		}
	});

};

endpointSelectors = function () {

// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
	var o;
	o = this;

	o.utilities.forEach(function (utility) {
		var dataUtility = '[data-utility="' + utility + '"]';
		document.querySelectorAll(dataUtility + ' ' + 'input[type="search"]').forEach(function (input) {
			if (!input.classList.contains('selector-listener')) {
				input.classList.add('selector-listener');
				input.addEventListener('change', function () {
// the 'list' on the input corresponds to the class of the same name on the span in the anchor
// <input list=xyz> -> <a><code><span class=xyz></span></code></a>
					document.querySelectorAll(dataUtility + ' .' + input.getAttribute('list')).forEach(function (span) {
						span.innerText = input.value;
					});
					document.querySelectorAll(dataUtility + ' a').forEach(function (anchor) {
						anchor.href = anchor.innerText.trim();
					});
				});
			}
		});
	});

/*
document.querySelectorAll('.datalists').forEach(

document.querySelectorAll('input[type="search"]').forEach(function (input) {
	var list = input.getAttribute('list');
	document.querySelector('#' + list).children;
	console.log
});
*/
};

fieldsetControlsAndInfo = function (info) {

	var o;
	o = this;

	var reverseRandomId;
	var verticalRandomId;

	reverseRandomId = o.fiveRandomAlphaNumerics();
	verticalRandomId = o.fiveRandomAlphaNumerics();

	if (!info) {
		info = '';
	}

	return '<fieldset><legend>Controls & Info</legend><label for="' + verticalRandomId + '">Vertical list: <input type="checkbox" id="' + verticalRandomId + '" name="vertical-list"></label><label for="' + reverseRandomId + '">Reverse list: <input type="checkbox" id="' + reverseRandomId + '" name="reverse-list"></label>' + info + '</fieldset>';

};

// Run once per page!
setSearchInputSize = function () {

// Set length of longest option in datalists to data-length:
	(function (section) {
		if (section) {
			section.querySelectorAll('datalist').forEach(function (datalist) {
				var length = 0;
				Array.from(datalist.children).forEach(function (option) {
					if (option.value.length > length) {
						length = option.value.length;
					}
				});
				datalist.setAttribute('data-length', length);
			});
		}
	}(document.querySelector('.datalists')));


// set the size of the corresponding search inputs to the length of longest
// option associated with them + 5 for padding (for the X and the down-caret):
	document.querySelectorAll('.utility input[type="search"]').forEach(function (input) {
		(function (datalist) {
			if (datalist) {
				input.setAttribute('size', parseInt(datalist.dataset?.length, 10) + 5);
			}
		}(document.querySelector('#' + input.getAttribute('list'))));
	});


};


setSearchInputAriaLabel = function () {
// set the size of the corresponding search inputs to the length of longest
// option associated with them + 5 for padding (for the X and the down-caret):
	document.querySelectorAll('.utility input[type="search"]').forEach(function (input) {
		input.setAttribute('aria-label', 'Search ' + input.getAttribute('list'));
	});
};

export default Object.freeze({
	clearInput,
	clearOutput,
	corsProxy,
	dataUtility,
	endpointSelectors,
	fetchJson,
	fetchProgress,
	fetchStatus,
	fieldsetControlsAndInfo,
	formatTime,
	formClasses,
	loadUtilityModules,
	setListeners,
	setSearchInputAriaLabel,
	setSearchInputSize,
	utilities,
	writeInfo
});
