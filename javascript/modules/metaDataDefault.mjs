var metaDataDefault;

// all are for content attribute of META tags

metaDataDefault = {
	name: {
		"description": "SHELL application described in 70 characters or less",
		"twitter:card": "summary_large_image",
		"twitter:creator": "@Shell",
		"twitter:description": "SHELL application described in 70 characters or less",
		"twitter:site": "@Shell",
		"twitter:title": "${TITLE}",
		"twitter:url": "${CANONICAL}",
		"twitter:image": "${BASE}images/head/shell-1200x630.20220913065837.jpg",
		"twitter:image:alt": "Shell"
	},
	property: {
		"og:description": "SHELL application described in 70 characters or less",
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