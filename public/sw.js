const CACHE_NAME = 'skb-cache-v2';
const OFFLINE_URLS = ['/', '/manifest.webmanifest'];

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => cache.addAll(OFFLINE_URLS))
  );
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keys) =>
      Promise.all(keys.map((key) => (key !== CACHE_NAME ? caches.delete(key) : null)))
    )
  );
});

self.addEventListener('fetch', (event) => {
  const req = event.request;
  if (req.method !== 'GET') return;

  const url = new URL(req.url);
  const accept = req.headers.get('accept') || '';
  const isHtml = accept.includes('text/html');

  const dynamicPaths = ['/login', '/register', '/forgot-password', '/reset-password', '/logout'];
  const bypassCache =
    isHtml ||
    dynamicPaths.some((p) => url.pathname.startsWith(p));

  if (bypassCache) {
    event.respondWith(
      fetch(req).catch(() => caches.match('/'))
    );
    return;
  }

  event.respondWith(
    caches.match(req).then((cached) => {
      const fetchPromise = fetch(req)
        .then((response) => {
          const clone = response.clone();
          caches.open(CACHE_NAME).then((cache) => cache.put(req, clone));
          return response;
        })
        .catch(() => cached || caches.match('/'));
      return cached || fetchPromise;
    })
  );
});
