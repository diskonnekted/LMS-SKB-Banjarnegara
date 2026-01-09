const CACHE_NAME = 'skb-cache-v3';

const getBasePath = () => {
  const scopeUrl = new URL(self.registration.scope);
  return scopeUrl.pathname.replace(/\/$/, '');
};

self.addEventListener('install', (event) => {
  const basePath = getBasePath();
  const offlineUrls = [basePath + '/', basePath + '/manifest.webmanifest'];

  event.waitUntil(
    Promise.all([
      caches.open(CACHE_NAME).then((cache) => cache.addAll(offlineUrls)),
      self.skipWaiting(),
    ])
  );
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    Promise.all([
      caches.keys().then((keys) =>
        Promise.all(keys.map((key) => (key !== CACHE_NAME ? caches.delete(key) : null)))
      ),
      self.clients.claim(),
    ])
  );
});

self.addEventListener('fetch', (event) => {
  const req = event.request;
  if (req.method !== 'GET') return;

  const basePath = getBasePath();
  const url = new URL(req.url);

  if (url.protocol !== 'http:' && url.protocol !== 'https:') return;
  if (url.origin !== self.location.origin) return;

  const accept = req.headers.get('accept') || '';
  const isHtml = accept.includes('text/html');

  const dynamicPaths = ['/login', '/register', '/forgot-password', '/reset-password', '/logout'];
  const bypassCache =
    isHtml ||
    dynamicPaths.some((p) => url.pathname.startsWith(basePath + p));

  if (bypassCache) {
    event.respondWith(
      fetch(req).catch(() => caches.match(basePath + '/'))
    );
    return;
  }

  event.respondWith(
    caches.match(req).then((cached) => {
      const fetchPromise = fetch(req)
        .then((response) => {
          const clone = response.clone();
          caches
            .open(CACHE_NAME)
            .then((cache) => cache.put(req, clone).catch(() => {}))
            .catch(() => {});
          return response;
        })
        .catch(() => cached || caches.match(basePath + '/'));
      return cached || fetchPromise;
    })
  );
});
