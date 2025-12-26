<x-app-layout>
    <div class="flex h-screen bg-gray-100" style="height: calc(100vh - 65px);">
        <!-- Sidebar (Same as show.blade.php for consistency, simplified) -->
        <div class="w-1/4 bg-white border-r overflow-y-auto hidden md:block">
            <div class="p-4">
                 <h3 class="font-bold text-lg mb-4">{{ $course->title }}</h3>
                 <p class="text-gray-500">Sedang Mengerjakan Kuis...</p>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-y-auto p-8">
            <div class="max-w-3xl mx-auto bg-white shadow-sm rounded-lg p-8">
                <h1 class="text-2xl font-bold mb-2">Kuis: {{ $quiz->title }}</h1>
                <p class="text-gray-500 mb-6">Nilai Kelulusan: {{ $quiz->passing_score }}%</p>
                
                <form action="{{ route('learning.quiz.submit', [$course, $module, $quiz]) }}" method="POST">
                    @csrf
                    
                    @foreach($quiz->questions as $index => $q)
                        <div class="mb-6 p-4 border rounded">
                            <p class="font-semibold mb-3">{{ $index + 1 }}. {{ $q->question }}</p>
                            @php
                                $optsRaw = $q->options;
                                if (is_string($optsRaw)) {
                                    $decoded = json_decode($optsRaw, true);
                                    $opts = is_array($decoded) ? $decoded : [$optsRaw];
                                } else {
                                    $opts = $optsRaw;
                                }
                                $hasAlphaKeys = is_array($opts) && (isset($opts['a']) || isset($opts['b']) || isset($opts['c']) || isset($opts['d']));
                            @endphp
                            <div class="space-y-2">
                                @if($hasAlphaKeys)
                                    @foreach(['a','b','c','d'] as $key)
                                        @if(isset($opts[$key]))
                                            <label class="flex items-center space-x-3 p-2 rounded hover:bg-gray-50 cursor-pointer">
                                                <input type="radio" name="q_{{ $q->id }}" value="{{ $key }}" class="form-radio text-blue-600" {{ $key === 'a' ? 'required' : '' }}>
                                                <span>{{ $opts[$key] }}</span>
                                            </label>
                                        @endif
                                    @endforeach
                                @else
                                    @foreach($opts as $i => $text)
                                        @php
                                            $keyLabel = is_string($i) ? $i : chr(97 + $loop->index); // a,b,c...
                                        @endphp
                                        <label class="flex items-center space-x-3 p-2 rounded hover:bg-gray-50 cursor-pointer">
                                            <input type="radio" name="q_{{ $q->id }}" value="{{ $text }}" class="form-radio text-blue-600" {{ $loop->first ? 'required' : '' }}>
                                            <span>{{ $text }}</span>
                                        </label>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @endforeach

                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow">
                            Kirim Jawaban
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
