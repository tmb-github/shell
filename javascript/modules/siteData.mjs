var siteData;

/*
// qwer
Object.entries(o.siteData.pageSpecificRoutines).forEach(function ([key, values]) {
// name of the function must be provided as a string literal:
	if (key === 'reviseOgAndTwitterMetaInfo') {
		values.forEach(function (value) {
			if ((o.pageName === 'gallery') && (value === o.pageName)) {
				o.reviseOgAndTwitterMetaInfo();
			} else {
				o.gallery.reviseOgAndTwitterMetaInfo();
			}
		});
	}
});
*/


siteData = {
/*
	pageSpecificRoutines: {
		siteWideLoader: ['gallery']
	},
*/
/*
	bodyListeners: {
		theme: [
			'theme-click-listener',
			'theme-popstate-listener'
		]
	},
*/

// Your own IP should be included here (instead of 00.00.000.000)
	excludedIps: ['::1', '00.00.000.00'],
	fontArray: [{
		family: 'Cabin',
		source: 'url(fonts/Cabin-Roman-400.woff2)',
		descriptors: {
			style: 'normal',
			weight: 400
		}
	}, {
		family: 'Cabin',
		source: 'url(fonts/Cabin-Italic-400.woff2)',
		descriptors: {
			style: 'italic',
			weight: 400
		}
	}, {
		family: 'Cabin',
		source: 'url(fonts/Cabin-Bold-700.woff2)',
		descriptors: {
			style: 'normal',
			weight: 700
		}
	}, {
		family: 'Cabin',
		source: 'url(fonts/Cabin-Bold-Italic-700.woff2)',
		descriptors: {
			style: 'italic',
			weight: 700
		}
	}],
	liveSiteUrl: 'https://shell.com',
	localhostUrl: 'https://localhost',
	metaData: {
		page: 'default',
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
	},
	noMouseoverPreload: [
// 2021-11-26: experiment:
//		'merchandise/',
		'home/',
		'theme/'
	],
	pageDependencies: {
/*
// EXAMPLE:
		'home': {
			mjs: './home.mjs',
			promiseLoader: [
				['tmbAlert', 'javascript/scripts/tmbAlert.js', {}]
			],
			enqueueLoader: [
				{src: 'javascript/scripts/tmbBug.js', dataScriptName: 'tmbBug'},
			]
		},
*/
		'home': {
			mjs: './home.mjs'
		},
		'contact': {
			mjs: './contact.mjs'
		},
		'dummy-1': {
			mjs: './dummy1.mjs'
		},
		'dummy-2': {
			mjs: './dummy2.mjs'
		},
		'dummy-3': {
			mjs: './dummy3.mjs'
		},
		'dummy-4': {
			mjs: './dummy4.mjs'
		},
		'dummy-5': {
			mjs: './dummy5.mjs'
		},
		'error-404': {
			mjs: './error404.mjs'
		},
		'login': {
			mjs: './login.mjs'
		},
		'message-sent': {
			mjs: './messageSent.mjs'
		},
		'number-up': {
			mjs: './numberUp.mjs',
			promiseLoader: [
				['tmbAlert', 'javascript/scripts/tmbAlert.js', {}]
			]
		},
		'offline': {
			mjs: './offline.mjs'
		},
		'privacy-policy': {
			mjs: './privacyPolicy.mjs'
		},
		'site-map': {
			mjs: './siteMap.mjs'
		}
// 2021-10-23
// retain as example of loading third-party library that needs attributes on the SCRIPT tag:
// We're using the enqueue method in common.mjs to load the ecwid libraries, as the promiseLoader
// has no mechanism for loading scripts synchronously (enqueue uses the load event of the script
// element to determine when to commence appending the next queued script).
/*
		'merchandise': {
			mjs: './merchandise.mjs',
			promiseLoader: [
				['Ecwid', 'https://app.ecwid.com/script.js?67178212&data_platform=code&data_date=2021-10-23', {'data-cfasync': 'false', 'async': 'false'}],
				['ecwidHelper', 'javascript/scripts/ecwidHelper.js', {}],
			]
		},
*/
	},
/*
see corresponding ipapi lookup settings in:
common.css
privacy-policy.css
privacyPolicy.mjs
privacy-policy/main.php
*/
	ipapiLookup: true,
	useGoogleAnalytics: false,
	uaString: 'UA-#########-1'

};

//export default pageDependencies;
export default Object.freeze({
	siteData
});