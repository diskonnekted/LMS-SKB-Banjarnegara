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
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-background flex flex-col">
            @php
                $hideTopNav = request()->routeIs('learning.lesson') && auth()->check() && auth()->user()->hasRole('student');
            @endphp
            @unless($hideTopNav)
                @include('layouts.navigation')
            @endunless

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-surface shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main class="flex-grow">
                {{ $slot }}
            </main>

            @if(!empty($isMobile) && $isMobile)
                @include('components.bottom-nav')
            @endif

            <!-- Footer -->
            <footer style="background-color: #FF6B6B;" class="border-t border-red-300">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-center gap-3">
                        <span class="text-sm text-white">LMS</span>
                        <img src="{{ asset('images/black.png') }}" alt="{{ config('app.name') }}" class="h-12 w-auto">
                        <span class="text-sm text-white">
                            developed by Clasnet
                            <a href="https://www.clasnet.co.id" class="text-white font-bold hover:underline" target="_blank">www.clasnet.co.id</a>
                        </span>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
