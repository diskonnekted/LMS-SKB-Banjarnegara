<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'LMS') }}</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
            }
            .glass-nav {
                background: #6C5CE7;
                backdrop-filter: blur(10px);
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }
            .blob {
                position: absolute;
                filter: blur(40px);
                z-index: -1;
                opacity: 0.5;
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-white text-gray-900 overflow-x-hidden pb-16 md:pb-0">
        
        <!-- Navbar -->
        <nav class="glass-nav fixed w-full z-50 transition-all duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20">
                    <div class="flex items-center">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center gap-2">
                            <x-application-logo theme="dark" class="w-32 h-auto object-contain" />
                        </div>
                    </div>
                    <div class="flex items-center space-x-6">
                        <div class="hidden md:flex space-x-8">
                            <a href="#home" class="text-sm font-medium text-white hover:text-gray-200 transition">Beranda</a>
                            <a href="#courses" class="text-sm font-medium text-white hover:text-gray-200 transition">Pelajaran</a>
                            <a href="{{ route('courses.catalog') }}" class="text-sm font-medium text-white hover:text-gray-200 transition">Semua Pelajaran</a>
                            <a href="#news" class="text-sm font-medium text-white hover:text-gray-200 transition">Berita</a>
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

        <!-- Hero Section -->
        <div id="home" class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden bg-gradient-to-b from-indigo-50/50 to-white">
            <!-- Background Elements -->
            <div class="blob bg-purple-200 w-96 h-96 rounded-full top-0 left-0 -translate-x-1/2 -translate-y-1/2 mix-blend-multiply"></div>
            <div class="blob bg-indigo-200 w-96 h-96 rounded-full bottom-0 right-0 translate-x-1/2 translate-y-1/2 mix-blend-multiply"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="lg:grid lg:grid-cols-12 lg:gap-16 items-center">
                    <div class="lg:col-span-6 text-center lg:text-left">
                        <div class="inline-flex items-center px-4 py-2 rounded-full bg-indigo-50 text-indigo-600 text-sm font-medium mb-6 border border-indigo-100">
                            <span class="flex h-2 w-2 rounded-full bg-indigo-600 mr-2 animate-pulse"></span>
                            Pelajaran Baru Tersedia
                        </div>
                        <h1 class="text-5xl lg:text-7xl font-extrabold tracking-tight text-gray-900 mb-6 leading-tight">
                            {{ $heroTitle }}
                        </h1>
                        <p class="text-lg text-gray-600 mb-8 leading-relaxed max-w-2xl mx-auto lg:mx-0">
                            {{ $heroDescription }}
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                            <a href="#courses" class="px-8 py-4 rounded-full bg-indigo-600 text-white font-semibold shadow-xl shadow-indigo-500/30 hover:bg-indigo-700 hover:-translate-y-1 transition-all duration-300 flex items-center justify-center">
                                Jelajahi Pelajaran
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </a>
                            <a href="{{ route('register') }}" class="px-8 py-4 rounded-full bg-white text-gray-900 border border-gray-200 font-semibold hover:bg-gray-50 hover:-translate-y-1 transition-all duration-300 flex items-center justify-center">
                                Gabung Gratis
                            </a>
                        </div>
                        
                        <!-- Stats -->
                        <div class="mt-12 grid grid-cols-3 gap-8 border-t border-gray-200 pt-8">
                            <div>
                                <p class="text-3xl font-bold text-gray-900">100+</p>
                                <p class="text-sm text-gray-500">Pelajaran Online</p>
                            </div>
                            <div>
                                <p class="text-3xl font-bold text-gray-900">50+</p>
                                <p class="text-sm text-gray-500">Pengajar Ahli</p>
                            </div>
                            <div>
                                <p class="text-3xl font-bold text-gray-900">1k+</p>
                                <p class="text-sm text-gray-500">Siswa Aktif</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="lg:col-span-6 mt-16 lg:mt-0 relative">
                        <div class="relative rounded-3xl overflow-hidden shadow-2xl shadow-indigo-500/20 transform hover:scale-[1.02] transition-all duration-500 group">
                            <div class="absolute inset-0 bg-gradient-to-t from-gray-900/40 to-transparent z-10"></div>
                            <img src="{{ asset('images/skb1.jpg') }}" alt="Siswa belajar" class="w-full h-[600px] object-cover object-center group-hover:scale-110 transition-transform duration-700">
                            
                            <!-- Floating Card -->
                            <div class="absolute bottom-8 left-8 right-8 z-20 bg-white/90 backdrop-blur-md p-6 rounded-2xl shadow-lg border border-white/20">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900">Pembelajaran Bersertifikat</p>
                                        <p class="text-sm text-gray-600">Dapatkan sertifikat resmi setelah selesai.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Decorative Elements -->
                        <div class="absolute -top-12 -right-12 w-24 h-24 bg-yellow-300 rounded-full blur-2xl opacity-60 animate-pulse"></div>
                        <div class="absolute -bottom-12 -left-12 w-32 h-32 bg-indigo-300 rounded-full blur-2xl opacity-60 animate-pulse delay-700"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-base font-semibold text-indigo-600 tracking-wide uppercase">Mengapa Memilih Kami</h2>
                    <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                        Pengalaman Belajar Lebih Baik
                    </p>
                    <p class="mt-4 max-w-2xl text-xl text-gray-500 mx-auto">
                        Kami menyediakan fitur terbaik untuk meningkatkan perjalanan belajar Anda.
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                    <div class="bg-gray-50 p-8 rounded-3xl hover:bg-indigo-50 transition-colors duration-300 group border border-gray-100">
                        <div class="w-14 h-14 bg-indigo-100 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-indigo-600 transition-colors duration-300">
                            <svg class="w-7 h-7 text-indigo-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Konten Ahli</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Akses materi berkualitas tinggi yang dibuat oleh profesional industri dan pendidik berpengalaman.
                        </p>
                    </div>
                    
                    <div class="bg-gray-50 p-8 rounded-3xl hover:bg-indigo-50 transition-colors duration-300 group border border-gray-100">
                        <div class="w-14 h-14 bg-indigo-100 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-indigo-600 transition-colors duration-300">
                            <svg class="w-7 h-7 text-indigo-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Kuis Interaktif</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Uji pengetahuan Anda dengan kuis menarik dan pantau kemajuan Anda secara real-time.
                        </p>
                    </div>
                    
                    <div class="bg-gray-50 p-8 rounded-3xl hover:bg-indigo-50 transition-colors duration-300 group border border-gray-100">
                        <div class="w-14 h-14 bg-indigo-100 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-indigo-600 transition-colors duration-300">
                            <svg class="w-7 h-7 text-indigo-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Keahlian Bersertifikat</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Dapatkan sertifikat setelah menyelesaikan pelajaran untuk menunjukkan pencapaian Anda.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Courses Section -->
        <section id="courses" class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-4">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900">Pelajaran Unggulan</h2>
                        <p class="mt-4 text-gray-600 max-w-2xl">
                            Jelajahi jalur pembelajaran terpopuler yang dirancang untuk Anda.
                        </p>
                    </div>
                    <a href="{{ route('courses.catalog') }}" class="text-indigo-600 font-semibold hover:text-indigo-700 flex items-center group">
                        Lihat Semua Pelajaran
                        <svg class="w-5 h-5 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>

                <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                    @forelse($courses as $course)
                        <div class="group bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-2xl hover:shadow-indigo-500/10 transition-all duration-300 border border-gray-100">
                            <div class="relative h-56 overflow-hidden">
                                @if($course->thumbnail)
                                    <img class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700" src="{{ Storage::url($course->thumbnail) }}" alt="{{ $course->title }}">
                                @else
                                    <img class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700" src="{{ asset('images/skb2.jpg') }}" alt="{{ $course->title }}">
                                @endif
                                <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-bold text-indigo-600 shadow-lg">
                                    PELAJARAN
                                </div>
                            </div>
                            
                            <div class="p-8">
                                <div class="flex items-center gap-2 mb-4">
                                    @if($course->teacher && $course->teacher->avatar)
                                        <img src="{{ Storage::url($course->teacher->avatar) }}" alt="{{ $course->teacher->name }}" class="w-8 h-8 rounded-full object-cover border border-gray-200">
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-600 border border-gray-200">
                                            {{ substr($course->teacher->name ?? 'T', 0, 1) }}
                                        </div>
                                    @endif
                                    <span class="text-sm font-medium text-gray-500">{{ $course->teacher->name ?? 'Instruktur' }}</span>
                                </div>
                                
                                <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-1 group-hover:text-indigo-600 transition-colors">
                                    {{ $course->title }}
                                </h3>
                                
                                <p class="text-gray-600 text-sm leading-relaxed line-clamp-2 mb-6">
                                    {{ Str::limit(strip_tags($course->description), 150) }}
                                </p>
                                
                                <div class="pt-6 border-t border-gray-100 flex items-center justify-between">
                                    <div class="flex items-center text-sm text-gray-500">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ $course->created_at->format('d M Y') }}
                                    </div>
                                    <a href="{{ route('courses.show', $course) }}" class="px-4 py-2 rounded-md bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition">
                                        Lihat Pelajaran
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-3 py-12 text-center bg-white rounded-3xl border border-dashed border-gray-300">
                            <p class="text-gray-500">Belum ada pelajaran yang tersedia saat ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- News Section -->
        <section id="news" class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-bold text-gray-900">Berita Terbaru</h2>
                    <p class="mt-4 text-gray-600">Tetap terinformasi dengan berita dan pengumuman terbaru kami.</p>
                </div>

                <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                    @forelse($news as $item)
                        <div x-data="{ open: false }" class="h-full">
                            <article class="flex flex-col h-full bg-gray-50 rounded-3xl overflow-hidden hover:bg-white hover:shadow-xl transition-all duration-300 border border-transparent hover:border-gray-100">
                                <div class="h-48 w-full overflow-hidden">
                                    @if($item->thumbnail && \Illuminate\Support\Facades\Storage::disk('public')->exists($item->thumbnail))
                                        <img class="w-full h-full object-cover hover:scale-105 transition-transform duration-500" src="{{ Storage::url($item->thumbnail) }}" alt="{{ $item->title }}">
                                    @else
                                        <img class="w-full h-full object-cover hover:scale-105 transition-transform duration-500" src="{{ asset('images/skb3.jpg') }}" alt="{{ $item->title }}">
                                    @endif
                                </div>
                                
                                <div class="p-8 flex flex-col flex-1">
                                    <div class="flex items-center gap-3 text-xs font-medium text-indigo-600 mb-4">
                                        <span class="px-2 py-1 rounded-md bg-indigo-50 border border-indigo-100">BERITA</span>
                                        <span class="text-gray-400">&bull;</span>
                                        <span class="text-gray-500">{{ $item->created_at->format('d M Y') }}</span>
                                    </div>
                                    
                                    <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-indigo-600 transition-colors line-clamp-2">
                                        <button @click="open = true" class="text-left hover:text-indigo-600 transition-colors">
                                            {{ $item->title }}
                                        </button>
                                    </h3>
                                    
                                    <p class="text-gray-600 leading-relaxed mb-6 line-clamp-3">
                                        {{ Str::limit(strip_tags($item->content), 100) }}
                                    </p>
                                    
                                    <div class="mt-auto pt-6 border-t border-gray-100">
                                        <button @click="open = true" class="inline-flex items-center text-indigo-600 font-semibold text-sm hover:underline group-hover:translate-x-1 transition-transform">
                                            Baca Selengkapnya
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                        </button>
                                    </div>
                                </div>
                            </article>

                            <!-- Modal -->
                            <template x-teleport="body">
                                <div x-show="open" class="relative z-[9999]" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                    <!-- Backdrop -->
                                    <div x-show="open" 
                                         x-transition:enter="ease-out duration-300" 
                                         x-transition:enter-start="opacity-0" 
                                         x-transition:enter-end="opacity-100" 
                                         x-transition:leave="ease-in duration-200" 
                                         x-transition:leave-start="opacity-100" 
                                         x-transition:leave-end="opacity-0" 
                                         class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity"></div>

                                    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                                        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                                            <!-- Modal Panel -->
                                            <div x-show="open" 
                                                 @click.away="open = false" 
                                                 x-transition:enter="ease-out duration-300" 
                                                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                                                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                                                 x-transition:leave="ease-in duration-200" 
                                                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                                                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                                                 class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-gray-100">
                                                
                                                <!-- Close Button -->
                                                <div class="absolute right-4 top-4 z-50">
                                                    <button type="button" @click="open = false" class="rounded-full bg-white/80 backdrop-blur p-2 text-gray-400 hover:text-gray-500 hover:bg-gray-100 transition-all focus:outline-none shadow-sm border border-gray-200">
                                                        <span class="sr-only">Close</span>
                                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </div>

                                                <!-- Modal Content -->
                                                <div class="bg-white">
                                                    @if($item->thumbnail)
                                                        <div class="aspect-video w-full overflow-hidden bg-gray-100">
                                                            <img src="{{ Storage::url($item->thumbnail) }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                                                        </div>
                                                    @endif

                                                    <div class="px-6 py-8 sm:p-10">
                                                        <div class="flex flex-wrap items-center gap-4 mb-6">
                                                            @if($item->category)
                                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-indigo-100 text-indigo-700 tracking-wide uppercase">
                                                                    {{ $item->category->name }}
                                                                </span>
                                                            @endif
                                                            <span class="flex items-center text-gray-500 text-sm font-medium">
                                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                                {{ $item->created_at->format('d F Y') }}
                                                            </span>
                                                            <span class="flex items-center text-gray-500 text-sm font-medium">
                                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                                                {{ $item->user->name ?? 'Admin' }}
                                                            </span>
                                                        </div>

                                                        <h3 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mb-6 leading-tight">
                                                            {{ $item->title }}
                                                        </h3>

                                                        <div class="prose prose-indigo max-w-none text-gray-600 space-y-6 leading-relaxed">
                                                            {!! nl2br(e($item->content)) !!}
                                                        </div>
                                                        
                                                        @if($item->course)
                                                            <div class="mt-10 p-6 bg-gradient-to-br from-indigo-50 to-white rounded-2xl border border-indigo-100 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-sm">
                                                                <div>
                                                                    <h4 class="text-lg font-bold text-indigo-900 mb-1">Tertarik mendalami topik ini?</h4>
                                                                    <p class="text-indigo-600">Pelajari selengkapnya di pelajaran: <span class="font-bold text-indigo-800">{{ $item->course->title }}</span></p>
                                                                </div>
                                                                <a href="{{ route('courses.show', $item->course) }}" class="shrink-0 inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-xl font-bold text-sm text-white uppercase tracking-wider hover:bg-indigo-700 hover:-translate-y-0.5 transition-all shadow-lg shadow-indigo-200">
                                                                    Lihat Pelajaran
                                                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    @empty
                        <div class="col-span-3 text-center py-12">
                            <p class="text-gray-500">Belum ada berita terbaru.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-20">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="relative bg-indigo-600 rounded-[2.5rem] p-12 overflow-hidden shadow-2xl shadow-indigo-600/30 text-center">
                    <!-- Background Shapes -->
                    <div class="absolute top-0 left-0 w-64 h-64 bg-white opacity-10 rounded-full -translate-x-1/2 -translate-y-1/2 blur-3xl"></div>
                    <div class="absolute bottom-0 right-0 w-64 h-64 bg-purple-500 opacity-20 rounded-full translate-x-1/2 translate-y-1/2 blur-3xl"></div>
                    
                    <div class="relative z-10">
                        <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">Siap untuk mulai belajar?</h2>
                        <p class="text-indigo-100 text-lg mb-8 max-w-2xl mx-auto">
                            Bergabunglah dengan ribuan siswa dan mulailah perjalanan Anda hari ini. Dapatkan akses tak terbatas ke semua pelajaran.
                        </p>
                        @auth
                            <a href="{{ route('dashboard') }}" class="inline-block px-8 py-4 bg-white text-indigo-600 font-bold rounded-full shadow-lg hover:bg-gray-50 hover:scale-105 transition-all duration-300">
                                Ke Dasbor
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="inline-block px-8 py-4 bg-white text-indigo-600 font-bold rounded-full shadow-lg hover:bg-gray-50 hover:scale-105 transition-all duration-300">
                                Mulai Gratis
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-white text-gray-600 pt-20 pb-10 border-t border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
                    <div class="col-span-1 md:col-span-2">
                        <a href="{{ route('home') }}" class="mb-6 inline-block" aria-label="{{ $organizerName }}">
                            <img src="{{ asset('images/black.png') }}" alt="{{ $organizerName }}" class="h-12 w-auto">
                        </a>
                        <p class="text-gray-500 max-w-sm leading-relaxed">
                            Memberdayakan pembelajar dengan pelajaran berkualitas tinggi dan bimbingan ahli. Bangun masa depan Anda bersama kami hari ini.
                        </p>
                    </div>
                    <div>
                        <h4 class="text-lg font-bold text-gray-900 mb-6">Tautan Cepat</h4>
                        <ul class="space-y-4">
                            <li><a href="#home" class="hover:text-indigo-600 transition-colors">Beranda</a></li>
                            <li><a href="#courses" class="hover:text-indigo-600 transition-colors">Pelajaran</a></li>
                            <li><a href="#news" class="hover:text-indigo-600 transition-colors">Berita</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-lg font-bold text-gray-900 mb-6">Kontak</h4>
                        <ul class="space-y-4">
                            <li class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                support@example.com
                            </li>
                            <li class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                Jakarta, Indonesia
                            </li>
                        </ul>
                    </div>
                </div>
                
                <div class="pt-8 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-sm">
                        &copy; {{ date('Y') }} {{ $organizerName }}. Hak Cipta Dilindungi.
                    </p>
                    <div class="flex items-center gap-6 text-sm">
                        <a href="#" class="hover:text-indigo-600 transition-colors">Kebijakan Privasi</a>
                        <a href="#" class="hover:text-indigo-600 transition-colors">Syarat & Ketentuan</a>
                    </div>
                </div>
            </div>
        </footer>

    </body>
</html>
