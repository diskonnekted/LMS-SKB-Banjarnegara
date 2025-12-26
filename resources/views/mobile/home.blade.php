<x-mobile-layout>
    <div class="px-4 pt-6">
        <h2 class="text-xl font-semibold">Beranda</h2>
        <p class="text-gray-600 mt-1">Selamat datang di LMS Mobile.</p>
    </div>
    <div class="px-4 mt-4">
        <h3 class="text-lg font-medium mb-3">Pelajaran Unggulan</h3>
        @if(isset($courses) && $courses->count() > 0)
            <div class="space-y-4">
                @foreach($courses as $course)
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        @if($course->thumbnail)
                            <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-32 object-cover">
                        @endif
                        <div class="p-4">
                            <div class="font-semibold">{{ $course->title }}</div>
                            <p class="text-sm text-gray-600 line-clamp-2">{{ $course->description }}</p>
                            <div class="mt-3">
                                <a href="{{ route('courses.show', $course) }}" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded">Lihat</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white shadow rounded-lg p-6 text-center">
                <p class="text-gray-600">Belum ada pelajaran ditampilkan.</p>
            </div>
        @endif
    </div>
    <div class="px-4 mt-6 mb-6">
        <h3 class="text-lg font-medium mb-3">Berita Terbaru</h3>
        @if(isset($news) && $news->count() > 0)
            <div class="space-y-4">
                @foreach($news as $item)
                    <a href="{{ route('news.show', $item) }}" class="block bg-white shadow rounded-lg p-4">
                        <div class="font-semibold">{{ $item->title }}</div>
                        <p class="text-sm text-gray-600 line-clamp-2">{{ $item->excerpt ?? Str::limit($item->content, 120) }}</p>
                    </a>
                @endforeach
            </div>
        @else
            <div class="bg-white shadow rounded-lg p-6 text-center">
                <p class="text-gray-600">Belum ada berita.</p>
            </div>
        @endif
    </div>
</x-mobile-layout>
