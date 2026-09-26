'use strict';

self.addEventListener(
	'install',
	function () {
		self.skipWaiting();
	}
);

self.addEventListener(
	'activate',
	function (event) {
		event.waitUntil( self.clients.claim() );
	}
);

self.addEventListener(
	'fetch',
	function (event) {
		if (event.request.method !== 'GET' || event.request.mode !== 'navigate') {
			return;
		}
		event.respondWith(
			fetch( event.request ).catch(
				function () {
					return new Response( '', { status: 503, statusText: 'Service Unavailable', headers: { 'Content-Type': 'text/plain; charset=UTF-8' } } );
				}
			)
		);
	}
);
