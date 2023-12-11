/**
* siteData.mjs
* Copyright (c) 2019-2023 Thomas M. Brodhead <https://bmt-systems.com>
* Released under the MIT license
* Date: 2023-11-01
*/
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
		family: 'Noto-Sans',
		source: 'url(fonts/Noto-Sans-Roman-400.woff2)',
		descriptors: {
			style: 'normal',
			weight: 400
		}
	}, {
		family: 'Noto-Sans',
		source: 'url(fonts/Noto-Sans-Italic-400.woff2)',
		descriptors: {
			style: 'italic',
			weight: 400
		}
	}, {
		family: 'Noto-Sans',
		source: 'url(fonts/Noto-Sans-Bold-700.woff2)',
		descriptors: {
			style: 'normal',
			weight: 700
		}
	}, {
		family: 'Noto-Sans',
		source: 'url(fonts/Noto-Sans-Bold-Italic-700.woff2)',
		descriptors: {
			style: 'italic',
			weight: 700
		}
	}, {
		family: 'Consolas',
		source: 'url(fonts/Consolas-Normal.woff2)',
		descriptors: {
			style: 'normal',
			weight: 400
		}
	}],
	liveSiteUrl: 'https://shell.com',
	localhostUrl: 'https://localhost/shell',
	metaData: {
		page: 'default',
		name: {
			"description": "Shell application for website development",
			"twitter:card": "summary_large_image",
			"twitter:creator": "@verylocaltv",
			"twitter:description": "Shell application for website development",
			"twitter:site": "@verylocaltv",
			"twitter:title": "${TITLE}",
			"twitter:url": "${CANONICAL}",
			"twitter:image": "${BASE}images/head/shell-1200x630.20220913065837.jpg",
			"twitter:image:alt": "Shell"
		},
		property: {
			"og:description": "Shell application for website development",
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
		'admin': {
			mjs: './admin.mjs'
		},
		'bad-request-test': {
			mjs: './admin/badRequestTest.mjs'
		},
		'compile': {
			mjs: './admin/compile.mjs'
		},
		'contact': {
			mjs: './contact.mjs'
		},
		'dummy': {
			mjs: './dummy.mjs'
		},
		'empty-recycle-bin': {
			mjs: './admin/emptyRecycleBin.mjs'
		},
		'error': {
			mjs: './error.mjs'
		},
		'google-fonts': {
			mjs: './googleFonts.mjs'
		},
		'home': {
			mjs: './home.mjs'
		},
		'login': {
			mjs: './admin/login.mjs'
		},
		'message-sent': {
			mjs: './messageSent.mjs'
		},
		'offline': {
			mjs: './offline.mjs'
		},
		'maintenance-mode': {
			mjs: './admin/maintenanceMode.mjs'
		},
		'page-destroyer': {
			mjs: './admin/pageDestroyer.mjs'
		},
		'page-maker': {
			mjs: './admin/pageMaker.mjs'
		},
		'privacy-policy': {
			mjs: './privacyPolicy.mjs'
		},
		'server': {
			mjs: './admin/server.mjs'
		},
		'server-error-test': {
			mjs: './admin/serverErrorTest.mjs'
		},
		'site-map': {
			mjs: './siteMap.mjs'
		},
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
	metaDescription: 'Shell application.',
	useGoogleAnalytics: false,
	uaString: 'UA-#########-1'
};
//export default pageDependencies;
export default Object.freeze({
	siteData
});