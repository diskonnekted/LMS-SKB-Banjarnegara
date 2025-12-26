<x-mobile-layout>
    <div class="pb-20">
        <div class="px-4 pt-6">
            <h2 class="text-xl font-semibold">Halaman Siswa (Mobile)</h2>
            <p class="text-gray-600 mt-1">Ringkas untuk penggunaan ponsel.</p>
        </div>
        <div class="px-4 mt-4">
            <h3 class="text-lg font-medium mb-3">Pelajaran Saya</h3>
            @php($courses = auth()->user()->enrolledCourses()->with('teacher')->get())
            @if($courses->count() > 0)
                <div class="space-y-4">
                    @foreach($courses as $course)
                        <div class="bg-white shadow rounded-lg overflow-hidden">
                            @if($course->thumbnail)
                                <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-32 object-cover">
                            @endif
                            <div class="p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="font-semibold">{{ $course->title }}</div>
                                        <div class="text-xs text-gray-500">Instruktur: {{ $course->teacher->name }}</div>
                                    </div>
                                    @if($course->pivot->completed_at)
                                        <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-700">Selesai</span>
                                    @endif
                                </div>
                                <div class="mt-3">
                                    <a href="{{ route('learning.course', $course) }}" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded">
                                        Buka
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white shadow rounded-lg p-6 text-center">
                    <p class="text-gray-600">Belum ada pelajaran diikuti.</p>
                    <a href="{{ route('courses.index') }}" class="text-blue-600 mt-2 inline-block">Jelajahi Pelajaran</a>
                </div>
            @endif
        </div>
    </div>
</x-mobile-layout>
