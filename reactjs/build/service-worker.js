"use strict";var precacheConfig=[["/wp-content/plugins/anycomment/index.html","ebe28087b9de4d689ae553b1a09ed5b6"],["/wp-content/plugins/anycomment/static/css/main.32ada99b.css","1ff0b8be705b7c84650ad600e72c6d13"],["/wp-content/plugins/anycomment/static/js/main.8c454a32.js","11be756ec48156f6dc532fc12a33c0fa"],["/wp-content/plugins/anycomment/static/media/comment-logo.9a8bc438.svg","9a8bc43875e2f5d94bea07bd9cac16e4"],["/wp-content/plugins/anycomment/static/media/dropzone.2cd703f4.svg","2cd703f47eadb4cc1394f1522d91d6fd"],["/wp-content/plugins/anycomment/static/media/like-off.312322cf.svg","312322cfbb80a8cca2de944f6ff0ab3f"],["/wp-content/plugins/anycomment/static/media/like-on.34643ac2.svg","34643ac2677c72e322c4eb3ee70b2057"],["/wp-content/plugins/anycomment/static/media/mini-logo.68334f3e.svg","68334f3e95ee58431cfb6e8631a5d86e"],["/wp-content/plugins/anycomment/static/media/select-file.b894fc4b.svg","b894fc4b486ba163a34c22fc8710c729"],["/wp-content/plugins/anycomment/static/media/social-500px.137a8ebf.svg","137a8ebf16e5f2fe001453e3b844c3b6"],["/wp-content/plugins/anycomment/static/media/social-500px.bcf02551.svg","bcf025514422d07fe0cb92c2df74e8c2"],["/wp-content/plugins/anycomment/static/media/social-dribbble.0c731451.svg","0c731451a212951f9ba8ff5ef445c01b"],["/wp-content/plugins/anycomment/static/media/social-dribbble.7c554482.svg","7c5544825bc5efae147168bafc259611"],["/wp-content/plugins/anycomment/static/media/social-facebook.1bac9fc4.svg","1bac9fc42b8a56f74c2437df754b80cd"],["/wp-content/plugins/anycomment/static/media/social-facebook.c60312cc.svg","c60312cc65c8fbbff4521f5bd713ab00"],["/wp-content/plugins/anycomment/static/media/social-github.5d29099f.svg","5d29099f6900b3716f6082c9f652f30c"],["/wp-content/plugins/anycomment/static/media/social-github.d1099e27.svg","d1099e27a32dc99eb134e9c22830f942"],["/wp-content/plugins/anycomment/static/media/social-google.24e11f98.svg","24e11f98c1900f7816126fd5cd65a514"],["/wp-content/plugins/anycomment/static/media/social-google.32120882.svg","3212088255de50b60821a43b795dec1e"],["/wp-content/plugins/anycomment/static/media/social-instagram.25202c26.svg","25202c26aea504747c9a3dfc157e7038"],["/wp-content/plugins/anycomment/static/media/social-instagram.a57cb906.svg","a57cb90696258afe9efc4d29f1b80b37"],["/wp-content/plugins/anycomment/static/media/social-odnoklassniki.a403c9fd.svg","a403c9fd56069372e34e064b446ad876"],["/wp-content/plugins/anycomment/static/media/social-odnoklassniki.bd77e74c.svg","bd77e74c9505dd000ce0f02a97478cd8"],["/wp-content/plugins/anycomment/static/media/social-reddit.71edd392.svg","71edd39233198d5dd61618690923ca6c"],["/wp-content/plugins/anycomment/static/media/social-rediit.b1bd23b7.svg","b1bd23b7940301230b17359951e8470a"],["/wp-content/plugins/anycomment/static/media/social-stackoverflow.05011c37.svg","05011c37bf8e2bf2d48a4d98c10c43b6"],["/wp-content/plugins/anycomment/static/media/social-stackoverflow.97eaffae.svg","97eaffaee35b6af448a6a95aec8327f5"],["/wp-content/plugins/anycomment/static/media/social-steam.1f7ac638.svg","1f7ac6389cc43ca782131472a4865537"],["/wp-content/plugins/anycomment/static/media/social-steam.b75c1c4a.svg","b75c1c4a50528dfdc8257495d9793555"],["/wp-content/plugins/anycomment/static/media/social-twitch.05e187c9.svg","05e187c99525d08c59bd8c4d447fe7c8"],["/wp-content/plugins/anycomment/static/media/social-twitch.631a8ce2.svg","631a8ce2ca60521b4d462a456f4e637e"],["/wp-content/plugins/anycomment/static/media/social-twitter.942c6d2a.svg","942c6d2aa701346d855d42d63dda9336"],["/wp-content/plugins/anycomment/static/media/social-twitter.d8eabfb5.svg","d8eabfb53b5d2ff26974617b5d465bfe"],["/wp-content/plugins/anycomment/static/media/social-vkontakte.3898b358.svg","3898b35866828a1a94d50115caacc8cd"],["/wp-content/plugins/anycomment/static/media/social-vkontakte.ad2bf25c.svg","ad2bf25c9384744946d1dcd650a7b19d"],["/wp-content/plugins/anycomment/static/media/social-windows.128b065e.svg","128b065ecc719a5252c3586bfbad6025"],["/wp-content/plugins/anycomment/static/media/social-windows.ae7a26a2.svg","ae7a26a2068a2068d83a804e8101b666"],["/wp-content/plugins/anycomment/static/media/social-yahoo.07fb9b39.svg","07fb9b39497b9d997717e28b9e67f994"],["/wp-content/plugins/anycomment/static/media/social-yahoo.d82bf83f.svg","d82bf83f9d5728121f7108e82be9f24a"]],cacheName="sw-precache-v3-sw-precache-webpack-plugin-"+(self.registration?self.registration.scope:""),ignoreUrlParametersMatching=[/^utm_/],addDirectoryIndex=function(e,t){var n=new URL(e);return"/"===n.pathname.slice(-1)&&(n.pathname+=t),n.toString()},cleanResponse=function(t){return t.redirected?("body"in t?Promise.resolve(t.body):t.blob()).then(function(e){return new Response(e,{headers:t.headers,status:t.status,statusText:t.statusText})}):Promise.resolve(t)},createCacheKey=function(e,t,n,a){var c=new URL(e);return a&&c.pathname.match(a)||(c.search+=(c.search?"&":"")+encodeURIComponent(t)+"="+encodeURIComponent(n)),c.toString()},isPathWhitelisted=function(e,t){if(0===e.length)return!0;var n=new URL(t).pathname;return e.some(function(e){return n.match(e)})},stripIgnoredUrlParameters=function(e,n){var t=new URL(e);return t.hash="",t.search=t.search.slice(1).split("&").map(function(e){return e.split("=")}).filter(function(t){return n.every(function(e){return!e.test(t[0])})}).map(function(e){return e.join("=")}).join("&"),t.toString()},hashParamName="_sw-precache",urlsToCacheKeys=new Map(precacheConfig.map(function(e){var t=e[0],n=e[1],a=new URL(t,self.location),c=createCacheKey(a,hashParamName,n,/\.\w{8}\./);return[a.toString(),c]}));function setOfCachedUrls(e){return e.keys().then(function(e){return e.map(function(e){return e.url})}).then(function(e){return new Set(e)})}self.addEventListener("install",function(e){e.waitUntil(caches.open(cacheName).then(function(a){return setOfCachedUrls(a).then(function(n){return Promise.all(Array.from(urlsToCacheKeys.values()).map(function(t){if(!n.has(t)){var e=new Request(t,{credentials:"same-origin"});return fetch(e).then(function(e){if(!e.ok)throw new Error("Request for "+t+" returned a response with status "+e.status);return cleanResponse(e).then(function(e){return a.put(t,e)})})}}))})}).then(function(){return self.skipWaiting()}))}),self.addEventListener("activate",function(e){var n=new Set(urlsToCacheKeys.values());e.waitUntil(caches.open(cacheName).then(function(t){return t.keys().then(function(e){return Promise.all(e.map(function(e){if(!n.has(e.url))return t.delete(e)}))})}).then(function(){return self.clients.claim()}))}),self.addEventListener("fetch",function(t){if("GET"===t.request.method){var e,n=stripIgnoredUrlParameters(t.request.url,ignoreUrlParametersMatching),a="index.html";(e=urlsToCacheKeys.has(n))||(n=addDirectoryIndex(n,a),e=urlsToCacheKeys.has(n));var c="/wp-content/plugins/anycomment/index.html";!e&&"navigate"===t.request.mode&&isPathWhitelisted(["^(?!\\/__).*"],t.request.url)&&(n=new URL(c,self.location).toString(),e=urlsToCacheKeys.has(n)),e&&t.respondWith(caches.open(cacheName).then(function(e){return e.match(urlsToCacheKeys.get(n)).then(function(e){if(e)return e;throw Error("The cached response that was expected is missing.")})}).catch(function(e){return console.warn('Couldn\'t serve response for "%s" from cache: %O',t.request.url,e),fetch(t.request)}))}});