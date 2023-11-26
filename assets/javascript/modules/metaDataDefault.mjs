/**
* metaDataDefault.mjs
* Copyright (c) 2019-2023 Thomas M. Brodhead <https://bmt-systems.com>
* Released under the MIT license
* Date: 2023-11-01
*/

var metaDataDefault;

// all are for content attribute of META tags

metaDataDefault = {
	name: {
		"description": "SHELL application description [150-160 characters are best].",
		"twitter:card": "summary_large_image",
		"twitter:creator": "@Shell",
		"twitter:description": "SHELL application description [150-160 characters are best].",
		"twitter:site": "@Shell",
		"twitter:title": "${TITLE}",
		"twitter:url": "${CANONICAL}",
		"twitter:image": "${BASE}images/head/shell-1200x630.20220913065837.jpg",
		"twitter:image:alt": "Shell"
	},
	property: {
		"og:description": "SHELL application description [150-160 characters are best].",
		"og:image": "${BASE}images/head/shell-1200x630.20220913065837.jpg",
		"og:image:alt": "Shell",
		"og:image:height": "630",
		"og:image:width": "1200",
		"og:image:secure_url": "${BASE}images/head/shell-1200x630.20220913065837.jpg",
		"og:image:type": "image/jpeg",
		"og:site_name": "Shell",
		"og:title": "${TITLE}",
		"og:type": "website",
		"og:url": "${CANONICAL}"
	}
};

export default Object.freeze({
	metaDataDefault
});