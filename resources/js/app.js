import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        const appUrlMeta = document.querySelector('meta[name="app-url"]');
        const appUrl = appUrlMeta?.getAttribute('content')?.replace(/\/$/, '');

        const swUrl = appUrl ? `${appUrl}/sw.js` : '/sw.js';

        navigator.serviceWorker.register(swUrl);
    });
}
