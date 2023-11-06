/**
* sample.mjs
* Copyright (c) 2023 Thomas M. Brodhead <https://bmt-systems.com>
* Released under the MIT license
* Date: 2023-11-01
*/

var sample;

sample = function () {

	var o;
// 'this' is the outer 'o' via .bind(o), so the outer 'o' === inner 'o':
	o = this;

};

export default Object.freeze({
	sample
});

