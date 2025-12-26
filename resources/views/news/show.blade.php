<x-public-layout>
    <div class="pt-24 pb-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Breadcrumb / Back Link -->
            <div class="mb-8">
                <a href="{{ route('home') }}#news" class="inline-flex items-center text-indigo-600 hover:text-indigo-700 font-medium transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Berita
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                <!-- Main Content (Left Column) -->
                <div class="lg:col-span-8">
                    <article class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        
                        <!-- Featured Image -->
                        @if($news->thumbnail)
                            <div class="aspect-video w-full overflow-hidden">
                                <img src="{{ Storage::url($news->thumbnail) }}" alt="{{ $news->title }}" class="w-full h-full object-cover">
                            </div>
                        @endif

                        <div class="p-8 md:p-12">
                            <!-- Header -->
                            <header class="mb-8">
                                <div class="flex items-center gap-4 mb-6">
                                    @if($news->category)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                            {{ $news->category->name }}
                                        </span>
                                    @endif
                                    <span class="text-gray-500 text-sm flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        {{ $news->created_at->format('d F Y') }}
                                    </span>
                                </div>

                                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 leading-tight mb-6">
                                    {{ $news->title }}
                                </h1>

                                <div class="flex items-center border-b border-gray-100 pb-8">
                                    <div class="flex-shrink-0">
                                        <span class="inline-block h-10 w-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-lg">
                                            {{ substr($news->user->name ?? 'A', 0, 1) }}
                                        </span>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $news->user->name ?? 'Admin' }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            Penulis
                                        </p>
                                    </div>
                                </div>
                            </header>

                            <!-- Content Body -->
                            <div class="mt-8 text-gray-800 leading-relaxed text-lg space-y-4">
                                {!! nl2br(e($news->content)) !!}
                            </div>
                        </div>
                    </article>

                    <!-- Related Course (Mobile/Bottom) -->
                    @if($news->course)
                        <div class="mt-8 lg:hidden">
                            <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-6">
                                <h3 class="text-indigo-900 font-bold text-lg mb-2">Tertarik dengan topik ini?</h3>
                                <p class="text-indigo-700 mb-4 text-sm">Pelajari lebih lanjut di pelajaran: <span class="font-semibold">{{ $news->course->title }}</span></p>
                                <a href="{{ route('courses.show', $news->course) }}" class="block w-full text-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                                    Lihat Pelajaran
                                </a>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar (Right Column) -->
                <div class="lg:col-span-4 space-y-8">
                    
                    <!-- Related Course Widget -->
                    @if($news->course)
                        <div class="hidden lg:block bg-white rounded-xl shadow-sm border border-indigo-100 p-6 relative overflow-hidden">
                            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-indigo-50 rounded-full opacity-50"></div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2 relative z-10">Terkait Pelajaran</h3>
                            <p class="text-gray-600 text-sm mb-4 relative z-10">
                                Berita ini berkaitan dengan materi pelajaran:
                            </p>
                            <div class="font-semibold text-indigo-600 mb-6 relative z-10 text-lg">
                                {{ $news->course->title }}
                            </div>
                            <a href="{{ route('courses.show', $news->course) }}" class="block w-full text-center px-4 py-3 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200 relative z-10">
                                Pelajari Sekarang
                            </a>
                        </div>
                    @endif

                    <!-- Share Widget -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">Bagikan</h3>
                        <div class="flex space-x-4">
                            <button class="flex-1 py-2 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition flex items-center justify-center">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                            </button>
                            <button class="flex-1 py-2 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 transition flex items-center justify-center">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Recent News Widget -->
                    @if(isset($recentNews) && $recentNews->count() > 0)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">Berita Terbaru</h3>
                            <div class="space-y-4">
                                @foreach($recentNews as $item)
                                    <a href="{{ route('news.show', $item) }}" class="group flex gap-4">
                                        @if($item->thumbnail)
                                            <div class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden bg-gray-100">
                                                <img src="{{ Storage::url($item->thumbnail) }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                            </div>
                                        @endif
                                        <div>
                                            <h4 class="text-sm font-semibold text-gray-900 group-hover:text-indigo-600 transition line-clamp-2">
                                                {{ $item->title }}
                                            </h4>
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ $item->created_at->format('d M Y') }}
                                            </p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-public-layout>
