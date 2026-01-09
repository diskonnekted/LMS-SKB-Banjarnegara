<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="app-url" content="{{ rtrim(url('/'), '/') }}">
        <meta name="theme-color" content="#6C5CE7">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-title" content="{{ config('app.name', 'Laravel') }}">
        <link rel="manifest" href="{{ rtrim(url('/'), '/') }}/manifest.webmanifest">
        <link rel="apple-touch-icon" href="{{ rtrim(url('/'), '/') }}/icons/icon-192.png">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased {{ request()->routeIs('login') ? 'text-gray-900 bg-gradient-to-br from-white via-indigo-50 to-slate-50' : 'text-gray-900' }}" @if(!request()->routeIs('login')) style="background-color: #4ECDC4;" @endif>
        <div class="min-h-screen flex flex-col justify-center items-center p-6 relative overflow-hidden">
            @if(request()->routeIs('login'))
                <div class="absolute -top-24 -left-24 h-80 w-80 rounded-full bg-tertiary/15 blur-3xl"></div>
                <div class="absolute -bottom-24 -right-24 h-80 w-80 rounded-full bg-secondary/15 blur-3xl"></div>
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_1px_1px,rgba(99,102,241,0.12)_1px,transparent_0)] bg-[length:22px_22px] opacity-60"></div>
            @endif
            <div class="mb-8">
                <a href="/">
                    <x-application-logo :theme="$logoTheme" class="w-auto h-28" />
                </a>
            </div>

            <div class="w-full {{ $maxWidth ?? 'max-w-md' }} rounded-[2.5rem] p-8 space-y-6 relative z-10 {{ request()->routeIs('login') ? 'bg-white/90 backdrop-blur-xl shadow-2xl shadow-indigo-500/10 border border-gray-200 ring-1 ring-indigo-100' : 'bg-white shadow-xl border border-gray-100' }}">
                {{ $slot }}
            </div>
        </div>
        @unless(request()->routeIs('login'))
            @include('components.bottom-nav')
        @endunless
    </body>
</html>
