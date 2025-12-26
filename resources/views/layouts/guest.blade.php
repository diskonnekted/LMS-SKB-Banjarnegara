<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#6C5CE7">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-title" content="{{ config('app.name', 'Laravel') }}">
        <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
        <link rel="apple-touch-icon" href="{{ url('/icons/icon-192.png') }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased" style="background-color: #4ECDC4;">
        <div class="min-h-screen flex flex-col justify-center items-center p-6 relative">
            <div class="mb-8">
                <a href="/">
                    <x-application-logo :theme="$logoTheme" class="w-auto h-28" />
                </a>
            </div>

            <div class="w-full {{ $maxWidth ?? 'max-w-md' }} bg-white shadow-xl rounded-[2.5rem] p-8 space-y-6 border border-gray-100 relative z-10">
                {{ $slot }}
            </div>
        </div>
        @if(!empty($isMobile) && $isMobile && !request()->routeIs('login'))
            @include('components.bottom-nav')
        @endif
    </body>
</html>
