(function () {
  'use strict';
  var version = '20231106002544';
  var cacheNameHTML = version + '-html';
  var cacheNameCSS = version + '-css';
  var cacheNameJavaScript = version + '-javascript';
  var cacheNameImages = version + '-images';
  var cacheNameFonts = version + '-fonts';
  var cacheNameFavicons = version + '-favicons';
  var cacheNameCatchAll = version + '-catch-all';
  var maxCacheHTML = 200;
  var maxCacheCSS = 200;
  var maxCacheJavaScript = 200;
  var maxCacheImages = 200;
  var maxCacheFonts = 200;
  var maxCacheFavicons = 200;
  var maxCacheCatchAll = 200;
  var offlineHTML = [];
  var offlineCSS = [];
  var offlineJavaScript = [];
  var offlineImages = [];
  var offlineFonts = [];
  var offlineFavicons = [];
  var offlineCatchAll = [];
  function stashInCache(cacheName, request, response) {
    caches.open(cacheName).then(function (cache) {
      return cache.put(request, response);
    }).catch(function (error) {
      console.log(error);
      return false;
    });
  }
  function updateCaches() {
    function cachesOpen(cacheName, offline) {
      return caches.open(cacheName).then(function (cache) {
        return Promise.all(
          offline.map(function (url) {
            if (url.length !== 0) {
              return cache.add(url).catch(function (reason) {
                console.log(String(reason) + ' - ' + url);
                return false;
              });
            } else {
              return false;
            }
          })
        ).catch(function (error) {
          console.log(error);
          return false;
        });
      }).catch(function (error) {
        console.log(error);
        return false;
      });
    }
    cachesOpen(cacheNameHTML, offlineHTML);
    cachesOpen(cacheNameCSS, offlineCSS);
    cachesOpen(cacheNameJavaScript, offlineJavaScript);
    cachesOpen(cacheNameFonts, offlineFonts);
    cachesOpen(cacheNameFavicons, offlineFavicons);
    cachesOpen(cacheNameCatchAll, offlineCatchAll);
    return cachesOpen(cacheNameImages, offlineImages);
  }
  function trimCache(cacheName, maxItems) {
    caches.open(cacheName).then(function (cache) {
      cache.keys().then(function (keys) {
        if (keys.length > maxItems) {
          cache.delete(keys[0]).then(trimCache(cacheName, maxItems));
        }
      }).catch(function (error) {
        console.log(error);
      });
    }).catch(function (error) {
      console.log(error);
    });
  }
  function clearOldCaches() {
    return caches.keys().then(function (keys) {
      return Promise.all(keys.filter(function (key) {
        return key.indexOf(version) !== 0;
      }).map(function (key) {
        return caches.delete(key);
      })).catch(function (error) {
        console.log(error);
        return false;
      });
    }).catch(function (error) {
      console.log(error);
      return false;
    });
  }
  self.addEventListener('install', function (event) {
    event.waitUntil(updateCaches().then(function () {
      return self.skipWaiting();
    }).catch(function (error) {
      console.log(error);
      return false;
    }));
  });
  self.addEventListener('activate', function (event) {
    event.waitUntil(clearOldCaches().then(function () {
      return self.clients.claim();
    }).catch(function (error) {
      console.log(error);
      return false;
    }));
  });
  self.addEventListener('message', function (event) {
    if (event.data.command === 'trimCaches') {
      trimCache(cacheNameHTML, maxCacheHTML);
      trimCache(cacheNameCSS, maxCacheCSS);
      trimCache(cacheNameJavaScript, maxCacheJavaScript);
      trimCache(cacheNameFonts, maxCacheFonts);
      trimCache(cacheNameFavicons, maxCacheFavicons);
      trimCache(cacheNameImages, maxCacheImages);
      trimCache(cacheNameCatchAll, maxCacheCatchAll);
    }
  });
  self.addEventListener('fetch', function (event) {
    var request;
    var requestClone;
    var responseClone;
    var url;
    request = event.request;
    try {
      requestClone = request.clone();
    } catch (error) {
      console.log(error);
    }
    if (event.request.cache === 'only-if-cached' && event.request.mode !== 'same-origin') {
      return;
    }
    url = new URL(event.request.url);
    if (event.request.url.indexOf('google-analytics') !== -1) {
      return;
    }
    if (event.request.url.indexOf('sw.js') !== -1) {
      return;
    }
    if (event.request.url.indexOf('compile') !== -1) {
      return;
    }
    if (event.request.method !== 'GET') {
      return;
    }
    if (navigator.onLine) {
      if (event.request.headers.get('Accept') && (event.request.headers.get('Accept').indexOf('text/html') !== -1)) {
        event.respondWith(fetch(requestClone, {mode: 'no-cors'}).then(function (response) {
          try {
            responseClone = response.clone();
            if (offlineHTML.includes(url.pathname) || offlineHTML.includes(url.pathname + '/')) {
              stashInCache(cacheNameCatchAll, request, responseClone);
            } else {
              stashInCache(cacheNameHTML, request, responseClone);
            }
          } catch (error) {
            console.log(error);
          }
          return response;
        }).catch(function (error) {
          console.log('-----');
          console.log(error);
          console.log(requestClone.url);
          return caches.match(request, {ignoreVary: true}).then(function (response2) {
            return response2 || caches.match('./offline/');
          }).catch(function (error) {
            console.log('-----');
            console.log(error);
            console.log(request.url);
            return new Response("<br><br>Network error", {
              "status": 408,
              "headers": {
                "Content-Type": "text/plain"
              }
            });
          });
        }));
        return;
      }
    }
    if (event.request.url.indexOf('?') !== -1) {
      return;
    }
    event.respondWith(caches.match(request, {ignoreVary: true}).then(function (response) {
      var corsType = 'cors';
      if ((request.url.indexOf('https://www.google.com/') !== -1) || (request.url.indexOf('https://www.gstatic.com/') !== -1)) {
        corsType = 'no-cors';
      }
      return response || fetch(requestClone, {mode: corsType}).then(function (response2) {
        if (event.request.headers.get('Accept') && (event.request.headers.get('Accept').indexOf('image') !== -1)) {
          try {
            responseClone = response2.clone();
            stashInCache(cacheNameImages, request, responseClone);
          } catch (error) {
            console.log(error);
          }
        }
        if (event.request.headers.get('Accept') && (event.request.headers.get('Accept').indexOf('text/css') !== -1)) {
          try {
            responseClone = response2.clone();
            stashInCache(cacheNameImages, request, responseClone);
          } catch (error2) {
            console.log(error2);
          }
        }
        return response2;
      }).catch(function (error) {
        console.log('=====');
        console.log(error);
        console.log(request);
        if (event.request.headers.get('Accept') && (event.request.headers.get('Accept').indexOf('image') !== -1)) {
          return new Response('<svg role="img" aria-labelledby="offline-title" viewBox="0 0 400 300" xmlns="http://www.w3.org/2000/svg"><title id="offline-title">Offline</title><g fill="none" fill-rule="evenodd"><path fill="#D8D8D8" d="M0 0h400v300H0z"/><text fill="#9B9B9B" font-family="Helvetica Neue,Arial,Helvetica,sans-serif" font-size="72" font-weight="bold"><tspan x="93" y="172">offline</tspan></text></g></svg>', {headers: {'Content-Type': 'image/svg+xml'}});
        } else {
          return new Response("<br><br>Network error", {
            "status": 408,
            "headers": {
              "Content-Type": "text/plain"
            }
          });
        }
      });
    }).catch(function (error) {
      console.log(error);
      return new Response("<br><br>Network error", {
        "status": 408,
        "headers": {
          "Content-Type": "text/plain"
        }
      });
    }));
  });
}());