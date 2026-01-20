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
                        <div class="mb-6 p-4 border rounded bg-gray-50">
                            <!-- Question Header & Text -->
                            <div class="mb-4">
                                <p class="font-bold text-lg mb-2">{{ $index + 1 }}. {{ $q->question }}</p>
                                
                                <!-- Media Display -->
                                @if($q->media_url)
                                    <div class="mb-4">
                                        @php 
                                            $isRemote = \Illuminate\Support\Str::startsWith($q->media_url, ['http://', 'https://', '/']);
                                            $url = $isRemote ? $q->media_url : \Illuminate\Support\Facades\Storage::disk('public')->url($q->media_url);
                                            $ext = pathinfo($url, PATHINFO_EXTENSION);
                                            $isImage = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                            $isAudio = in_array(strtolower($ext), ['mp3', 'wav', 'ogg']);
                                            $isVideo = in_array(strtolower($ext), ['mp4', 'webm']);
                                        @endphp
                                        
                                        @if($isImage)
                                            <img src="{{ $url }}" alt="Question Media" class="max-w-md h-auto rounded border shadow-sm">
                                        @elseif($isAudio)
                                            <audio controls class="w-full max-w-md">
                                                <source src="{{ $url }}">
                                                Your browser does not support the audio element.
                                            </audio>
                                        @elseif($isVideo)
                                            <video controls class="w-full max-w-md">
                                                <source src="{{ $url }}">
                                                Your browser does not support the video element.
                                            </video>
                                        @else
                                            <a href="{{ $url }}" target="_blank" class="text-blue-600 hover:underline flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                                View Attached Media
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <!-- Answer Inputs -->
                            <div class="space-y-3">
                                @php
                                    $options = is_array($q->options) ? $q->options : (json_decode($q->options, true) ?? []);
                                @endphp

                                @if($q->type === 'multiple_choice' || !$q->type)
                                    @foreach(['a','b','c','d','e'] as $key)
                                        @if(isset($options[$key]))
                                            <label class="flex items-center space-x-3 p-3 rounded-lg border border-gray-200 hover:bg-white hover:shadow-sm cursor-pointer transition">
                                                <input type="radio" name="q_{{ $q->id }}" value="{{ $key }}" class="form-radio h-5 w-5 text-blue-600">
                                                <span class="text-gray-800">{{ $options[$key] }}</span>
                                            </label>
                                        @endif
                                    @endforeach

                                @elseif($q->type === 'multiple_response')
                                    @foreach(['a','b','c','d','e'] as $key)
                                        @if(isset($options[$key]))
                                            <label class="flex items-center space-x-3 p-3 rounded-lg border border-gray-200 hover:bg-white hover:shadow-sm cursor-pointer transition">
                                                <input type="checkbox" name="q_{{ $q->id }}[]" value="{{ $key }}" class="form-checkbox h-5 w-5 text-blue-600 rounded">
                                                <span class="text-gray-800">{{ $options[$key] }}</span>
                                            </label>
                                        @endif
                                    @endforeach

                                @elseif($q->type === 'true_false')
                                    <div class="flex gap-4">
                                        <label class="flex items-center space-x-2 p-3 rounded-lg border border-gray-200 bg-white hover:shadow-sm cursor-pointer w-full">
                                            <input type="radio" name="q_{{ $q->id }}" value="true" class="form-radio h-5 w-5 text-green-600">
                                            <span class="font-bold">True</span>
                                        </label>
                                        <label class="flex items-center space-x-2 p-3 rounded-lg border border-gray-200 bg-white hover:shadow-sm cursor-pointer w-full">
                                            <input type="radio" name="q_{{ $q->id }}" value="false" class="form-radio h-5 w-5 text-red-600">
                                            <span class="font-bold">False</span>
                                        </label>
                                    </div>

                                @elseif($q->type === 'short_answer')
                                    <input type="text" name="q_{{ $q->id }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Type your answer here...">

                                @elseif($q->type === 'numeric')
                                    <input type="number" step="any" name="q_{{ $q->id }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Enter a number...">

                                @elseif($q->type === 'essay')
                                    <textarea name="q_{{ $q->id }}" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Type your essay here..."></textarea>

                                @elseif(in_array($q->type, ['matching', 'drag_drop']))
                                    <div class="grid grid-cols-1 gap-4">
                                        @foreach($options as $idx => $pair)
                                            <div class="flex flex-col md:flex-row md:items-center gap-4 p-3 border rounded bg-white">
                                                <div class="flex-1 font-medium">{{ $pair['left'] }}</div>
                                                <div class="hidden md:block text-gray-400">→</div>
                                                <div class="flex-1">
                                                    <select name="q_{{ $q->id }}[{{ $idx }}]" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                                        <option value="">Select match...</option>
                                                        @foreach($options as $p)
                                                            <option value="{{ $p['right'] }}">{{ $p['right'] }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                @elseif($q->type === 'sequencing')
                                    <div class="space-y-2">
                                        <p class="text-sm text-gray-600 mb-2">Assign the correct order number to each item:</p>
                                        @php 
                                            // Shuffle options for display if they are not already shuffled? 
                                            // Actually $options contains the correct order from DB. We should shuffle them for display.
                                            // But standard collection shuffle might not be persistent across reloads if not stored.
                                            // For simplicity, we just display them. The user sees the list and assigns numbers.
                                            // Ideally, we should shuffle this list.
                                            $shuffledOptions = collect($options)->shuffle();
                                        @endphp
                                        @foreach($shuffledOptions as $item)
                                            <div class="flex items-center gap-3 p-3 border rounded bg-white">
                                                <input type="number" name="q_{{ $q->id }}[{{ $item }}]" min="1" max="{{ count($options) }}" class="w-16 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                                <span class="flex-1">{{ $item }}</span>
                                            </div>
                                        @endforeach
                                    </div>
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css">
    <script src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/contrib/auto-render.min.js"></script>
    <script>
        (function(){
            const container = document.querySelector('.mx-auto');
            if (container) {
                try {
                    renderMathInElement(container, {
                        delimiters: [
                            {left: "$$", right: "$$", display: true},
                            {left: "\\[", right: "\\]", display: true},
                            {left: "$", right: "$", display: false},
                            {left: "\\(", right: "\\)", display: false}
                        ],
                        throwOnError: false
                    });
                } catch(e){}
            }
        })();
    </script>
</x-app-layout>
