<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg text-center p-10">
                @if($passed)
                    <div class="text-green-500 mb-4">
                        <svg class="w-20 h-20 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Selamat!</h1>
                    <p class="text-xl text-gray-600 mb-6">Anda lulus kuis ini.</p>
                @else
                    <div class="text-red-500 mb-4">
                        <svg class="w-20 h-20 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Coba Lagi!</h1>
                    <p class="text-xl text-gray-600 mb-6">Anda belum lulus kali ini.</p>
                @endif

                <div class="text-4xl font-bold mb-8 {{ $passed ? 'text-green-600' : 'text-red-600' }}">
                    {{ round($percentage) }}%
                </div>

                <div class="space-x-4">
                    <a href="{{ route('learning.course', $course) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Kembali ke Pelajaran
                    </a>
                    @if(!$passed)
                        <a href="{{ route('learning.quiz', [$course, $module, $quiz]) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Ulangi Kuis
                        </a>
                    @else
                         <!-- Logic to go to next lesson/module -->
                         <a href="{{ route('learning.course', $course) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Lanjut Belajar
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
