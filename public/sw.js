self.addEventListener('install', event => {
    self.skipWaiting();
});

self.addEventListener('activate', event => {
    event.waitUntil(clients.claim());
});

self.addEventListener('fetch', event => {
    event.respondWith(fetch(event.request).catch(() => {
        return new Response("<h1>Anda sedang Offline</h1><p>Silakan periksa koneksi internet Anda.</p>", {
            headers: { 'Content-Type': 'text/html' }
        });
    }));
});
