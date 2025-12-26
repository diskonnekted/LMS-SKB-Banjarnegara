<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#6C5CE7">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-title" content="{{ config('app.name', 'LMS') }}">
        <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
        <link rel="apple-touch-icon" href="{{ url('/icons/icon-192.png') }}">
        <title>{{ config('app.name', 'LMS') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            .glass-nav { background: #6C5CE7; backdrop-filter: blur(10px); border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
        </style>
    </head>
    <body class="font-sans antialiased bg-gray-50 text-gray-900 overflow-x-hidden flex flex-col min-h-screen">
        <nav class="glass-nav fixed w-full z-50 transition-all duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20">
                    <div class="flex items-center">
                        <div class="shrink-0 flex items-center gap-2">
                            <a href="{{ url('/') }}">
                                <x-application-logo theme="dark" class="w-32 h-auto object-contain" />
                            </a>
                        </div>
                    </div>
                    <div class="flex items-center space-x-6">
                        <div class="hidden md:flex space-x-8">
                            <a href="{{ url('/#home') }}" class="text-sm font-medium text-white hover:text-gray-200 transition">Beranda</a>
                            <a href="{{ url('/#courses') }}" class="text-sm font-medium text-white hover:text-gray-200 transition">Pelajaran</a>
                            <a href="{{ url('/#news') }}" class="text-sm font-medium text-white hover:text-gray-200 transition">Berita</a>
                        </div>
                        @if (Route::has('login'))
                            <div class="flex items-center gap-3">
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 rounded-full text-white text-sm font-semibold hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300" style="background-color: #FF6B6B;">
                                        Dasbor
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="text-sm font-semibold text-white hover:text-gray-200">Masuk</a>
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-full text-white text-sm font-semibold shadow-lg hover:-translate-y-0.5 transition-all duration-300" style="background-color: #FF6B6B !important;">
                                            Daftar
                                        </a>
                                    @endif
                                @endauth
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </nav>
        <main class="flex-grow">
            {{ $slot }}
        </main>
        @if(!empty($isMobile) && $isMobile)
            @include('components.bottom-nav')
        @endif
        <footer style="background-color: #FF6B6B;" class="border-t border-red-300 mt-auto">
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
    </body>
</html>

