// Sample:
var fiveRandomAlphaNumerics;

// Ensures first characters is not number:
fiveRandomAlphaNumerics = function () {
	var alfas;
	var first;
	var getRandomChar;
	var nums;
	var rest;

	getRandomChar = function (...params) {
		var symbols = params.join('');
		return symbols[Math.floor(Math.random() * symbols.length)];
	};

	alfas = 'abcdefghijklmnopqrstuvwxyz';
	nums = '0123456789';

	first = getRandomChar(alfas);
	rest = [...new Array(4)].map(() => getRandomChar(alfas, nums));

	return [first, ...rest].join('');

};


export default Object.freeze({
	fiveRandomAlphaNumerics,
});
