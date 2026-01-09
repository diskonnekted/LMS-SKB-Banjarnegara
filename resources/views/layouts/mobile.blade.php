<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta name="app-url" content="{{ rtrim(url('/'), '/') }}">
        <meta name="theme-color" content="#6C5CE7">
        <meta name="mobile-web-app-capable" content="yes">
        <link rel="manifest" href="{{ rtrim(url('/'), '/') }}/manifest.webmanifest">
        <link rel="apple-touch-icon" href="{{ rtrim(url('/'), '/') }}/icons/icon-192.png">
        <title>{{ config('app.name', 'LMS') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            .mobile-header {
                background-color: #6C5CE7;
                color: #fff;
            }
            .mobile-safe {
                padding-bottom: env(safe-area-inset-bottom);
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-gray-50 text-gray-900 overflow-x-hidden flex flex-col min-h-screen">
        <header class="mobile-header py-3 px-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <x-application-logo theme="dark" class="h-6 w-auto" />
                <span class="font-semibold">{{ config('app.name', 'LMS') }}</span>
            </div>
            @auth
            <a href="{{ route('profile.edit') }}" class="text-white text-sm">Profil</a>
            @endauth
            <button id="install-btn" class="hidden ml-3 px-3 py-1 rounded bg-white text-tertiary text-xs font-semibold shadow">Install</button>
        </header>
        <main class="flex-grow pb-20">
            {{ $slot }}
        </main>
        @include('components.bottom-nav')
        <script>
            (function(){
                var btn = document.getElementById('install-btn');
                var inStandalone = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;
                if (inStandalone && btn) { btn.classList.add('hidden'); }
                var deferredPrompt;
                window.addEventListener('beforeinstallprompt', function(e){
                    e.preventDefault();
                    deferredPrompt = e;
                    if (btn) btn.classList.remove('hidden');
                });
                if (btn) {
                    btn.addEventListener('click', function(){
                        if (!deferredPrompt) return;
                        deferredPrompt.prompt();
                        deferredPrompt.userChoice.then(function(){
                            deferredPrompt = null;
                            btn.classList.add('hidden');
                        });
                    });
                }
            })();
        </script>
    </body>
</html>
