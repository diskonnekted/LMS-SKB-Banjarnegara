<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Quiz: ') . $quiz->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <x-auth-session-status class="mb-4" :status="session('success')" />

            <!-- Edit Quiz Details -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between gap-4 mb-4">
                        <h3 class="text-lg font-bold">Quiz Details</h3>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('teacher.quizzes.attempts.index', $quiz) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50">
                                Lihat Jawaban Siswa
                            </a>
                            <form action="{{ route('quizzes.destroy', $quiz) }}" method="POST" onsubmit="return confirm('Delete entire quiz?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-white border border-red-300 rounded-md font-semibold text-xs text-red-700 uppercase tracking-widest shadow-sm hover:bg-red-50">
                                    Delete Quiz
                                </button>
                            </form>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('quizzes.update', $quiz) }}">
                        @csrf
                        @method('PUT')
                        <div class="flex gap-4 mb-4">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700">Title</label>
                                <input type="text" name="title" value="{{ $quiz->title }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            </div>
                            <div class="w-32">
                                <label class="block text-sm font-medium text-gray-700">Passing Score</label>
                                <input type="number" name="passing_score" value="{{ $quiz->passing_score }}" min="0" max="100" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update Details
                            </button>
                        </div>
                    </form>


                </div>
            </div>

            <!-- Questions List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 quiz-questions-container" id="questions-list">
                    <h3 class="text-lg font-bold mb-4">Questions ({{ $quiz->questions->count() }})</h3>
                    @if($quiz->questions->isEmpty())
                        <p class="text-gray-500">No questions yet.</p>
                    @else
                        <ul class="space-y-4">
                            @foreach($quiz->questions as $index => $question)
                                <li class="border p-4 rounded bg-gray-50">
                                    <div class="flex justify-between gap-2">
                                        <p class="font-bold mb-2">
                                            {{ $index + 1 }}. {{ $question->question }}
                                            <span class="text-xs font-normal text-gray-500 ml-2">({{ ucfirst(str_replace('_', ' ', $question->type ?? 'multiple_choice')) }})</span>
                                        </p>
                                        <div class="flex items-center gap-3">
                                            <a href="{{ route('questions.edit', $question) }}" class="text-blue-600 hover:text-blue-800 text-sm font-semibold">Edit</a>
                                            <form action="{{ route('questions.destroy', $question) }}" method="POST" onsubmit="return confirm('Delete question?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 text-sm">Delete</button>
                                            </form>
                                        </div>
                                    </div>

                                    @if($question->type === 'multiple_choice' || $question->type === 'multiple_choice') 
                                    {{-- Handle legacy or default --}}
                                        <div class="grid grid-cols-2 gap-2 text-sm">
                                            @foreach(['a', 'b', 'c', 'd', 'e'] as $opt)
                                                @if(isset($question->options[$opt]))
                                                    <div class="{{ $question->correct_answer == $opt ? 'text-green-600 font-bold' : '' }}">
                                                        {{ strtoupper($opt) }}: {{ $question->options[$opt] }}
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>

                                    @elseif($question->type === 'multiple_response')
                                         <div class="grid grid-cols-2 gap-2 text-sm">
                                            @php($corrects = json_decode($question->correct_answer, true) ?? [])
                                            @foreach(['a', 'b', 'c', 'd', 'e'] as $opt)
                                                @if(isset($question->options[$opt]))
                                                    <div class="{{ in_array($opt, $corrects) ? 'text-green-600 font-bold' : '' }}">
                                                        <span class="inline-block w-4 h-4 border border-gray-400 rounded-sm mr-1 {{ in_array($opt, $corrects) ? 'bg-green-500 border-green-500' : '' }}"></span>
                                                        {{ strtoupper($opt) }}: {{ $question->options[$opt] }}
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>

                                    @elseif($question->type === 'true_false')
                                        <div class="text-sm">
                                            Answer: <span class="font-bold text-green-600">{{ ucfirst($question->correct_answer) }}</span>
                                        </div>

                                    @elseif(in_array($question->type, ['short_answer', 'numeric']))
                                        <div class="text-sm">
                                            Answer: <span class="font-bold text-green-600">{{ $question->correct_answer }}</span>
                                        </div>

                                    @elseif($question->type === 'essay')
                                         <div class="text-sm text-gray-500 italic">
                                            (Essay question - manual grading)
                                        </div>

                                    @elseif(in_array($question->type, ['matching', 'drag_drop']))
                                        <div class="text-sm">
                                            <p class="font-semibold mb-1">Pairs:</p>
                                            @php($pairs = is_array($question->options) ? $question->options : (json_decode((string) $question->options, true) ?? []))
                                            <ul class="list-disc list-inside">
                                                @foreach($pairs as $pair)
                                                    <li>{{ $pair['left'] }} → {{ $pair['right'] }}</li>
                                                @endforeach
                                            </ul>
                                        </div>

                                    @elseif($question->type === 'sequencing')
                                        <div class="text-sm">
                                            <p class="font-semibold mb-1">Correct Order:</p>
                                            @php($sequence = json_decode($question->correct_answer, true) ?? [])
                                            <ol class="list-decimal list-inside">
                                                @foreach($sequence as $item)
                                                    <li>{{ $item }}</li>
                                                @endforeach
                                            </ol>
                                        </div>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            <!-- Add Question Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900" x-data="{ 
                    type: 'multiple_choice',
                    pairs: [{left: '', right: ''}, {left: '', right: ''}],
                    sequence_items: ['', '', '', ''],
                    addPair() { this.pairs.push({left: '', right: ''}); },
                    removePair(index) { this.pairs.splice(index, 1); },
                    addSequenceItem() { this.sequence_items.push(''); },
                    removeSequenceItem(index) { this.sequence_items.splice(index, 1); }
                }">
                    <h3 class="text-lg font-bold mb-4">Add New Question</h3>
                    <form method="POST" action="{{ route('quizzes.questions.store', $quiz) }}" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Question Type</label>
                            <select x-model="type" name="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="multiple_choice">Multiple Choice</option>
                                <option value="multiple_response">Multiple Response</option>
                                <option value="true_false">True / False</option>
                                <option value="short_answer">Short Answer</option>
                                <option value="numeric">Numeric</option>
                                <option value="essay">Essay</option>
                                <option value="matching">Matching</option>
                                <option value="sequencing">Sequencing</option>
                                <option value="drag_drop">Drag & Drop</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Lampiran Gambar (Opsional)</label>
                            <input type="file" name="media_file" class="mt-1 block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" accept="image/*,application/pdf">
                            <p class="text-xs text-gray-500 mt-1">Maks 10MB.</p>
                        </div>

                        <div class="mb-4">
                            <div class="flex items-center justify-between gap-3">
                                <label class="block text-sm font-medium text-gray-700">Question Text / Instruction</label>
                                <a href="{{ route('teacher.latex-guide') }}" target="_blank" rel="noopener" class="text-sm text-indigo-600 hover:text-indigo-800 font-semibold">Contoh LaTeX</a>
                            </div>
                            <textarea name="question" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required placeholder="Gunakan $...$ atau \\[...\\] untuk rumus LaTeX"></textarea>
                            <div class="mt-2 p-3 rounded border bg-gray-50">
                                <div class="text-xs text-gray-500 mb-2">Preview</div>
                                <div id="question-preview" class="prose"></div>
                            </div>
                        </div>

                        <!-- Options for MC and MR -->
                        <div x-show="type === 'multiple_choice' || type === 'multiple_response'" class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Option A</label>
                                <input type="text" name="option_a" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" :required="type === 'multiple_choice' || type === 'multiple_response'">
                                <div class="mt-2 p-2 rounded border bg-gray-50">
                                    <div class="text-xs text-gray-500 mb-1">Preview</div>
                                    <div class="option-preview" data-option="a"></div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Option B</label>
                                <input type="text" name="option_b" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" :required="type === 'multiple_choice' || type === 'multiple_response'">
                                <div class="mt-2 p-2 rounded border bg-gray-50">
                                    <div class="text-xs text-gray-500 mb-1">Preview</div>
                                    <div class="option-preview" data-option="b"></div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Option C</label>
                                <input type="text" name="option_c" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <div class="mt-2 p-2 rounded border bg-gray-50">
                                    <div class="text-xs text-gray-500 mb-1">Preview</div>
                                    <div class="option-preview" data-option="c"></div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Option D</label>
                                <input type="text" name="option_d" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <div class="mt-2 p-2 rounded border bg-gray-50">
                                    <div class="text-xs text-gray-500 mb-1">Preview</div>
                                    <div class="option-preview" data-option="d"></div>
                                </div>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Option E</label>
                                <input type="text" name="option_e" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <div class="mt-2 p-2 rounded border bg-gray-50">
                                    <div class="text-xs text-gray-500 mb-1">Preview</div>
                                    <div class="option-preview" data-option="e"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Correct Answer for MC -->
                        <div class="mb-4" x-show="type === 'multiple_choice'">
                            <label class="block text-sm font-medium text-gray-700">Correct Answer</label>
                            <select name="correct_answer" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" :disabled="type !== 'multiple_choice'">
                                <option value="a">Option A</option>
                                <option value="b">Option B</option>
                                <option value="c">Option C</option>
                                <option value="d">Option D</option>
                                <option value="e">Option E</option>
                            </select>
                        </div>

                        <!-- Correct Answer for MR -->
                        <div class="mb-4" x-show="type === 'multiple_response'">
                            <label class="block text-sm font-medium text-gray-700">Correct Answers</label>
                            <div class="flex gap-4 mt-2">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="correct_answers[]" value="a" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <span class="ml-2">Option A</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="correct_answers[]" value="b" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <span class="ml-2">Option B</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="correct_answers[]" value="c" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <span class="ml-2">Option C</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="correct_answers[]" value="d" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <span class="ml-2">Option D</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="correct_answers[]" value="e" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <span class="ml-2">Option E</span>
                                </label>
                            </div>
                        </div>

                        <!-- Correct Answer for True/False -->
                        <div class="mb-4" x-show="type === 'true_false'">
                            <label class="block text-sm font-medium text-gray-700">Correct Answer</label>
                            <select name="correct_answer" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" :disabled="type !== 'true_false'">
                                <option value="true">True</option>
                                <option value="false">False</option>
                            </select>
                        </div>

                        <!-- Correct Answer for Short Answer / Numeric -->
                        <div class="mb-4" x-show="type === 'short_answer' || type === 'numeric'">
                            <label class="block text-sm font-medium text-gray-700">Correct Answer</label>
                            <input type="text" name="correct_answer_text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" :required="type === 'short_answer' || type === 'numeric'">
                            <p class="text-xs text-gray-500 mt-1" x-show="type === 'numeric'">For numeric answers, enter the exact number.</p>
                        </div>

                        <!-- Matching Fields -->
                        <div class="mb-4" x-show="type === 'matching' || type === 'drag_drop'">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pairs (Item - Match)</label>
                            <template x-for="(pair, index) in pairs" :key="index">
                                <div class="flex gap-2 mb-2">
                                    <input type="text" :name="'pairs['+index+'][left]'" x-model="pair.left" placeholder="Item (e.g., Cat)" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <span class="self-center">→</span>
                                    <input type="text" :name="'pairs['+index+'][right]'" x-model="pair.right" placeholder="Match (e.g., Animal)" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <button type="button" @click="removePair(index)" class="text-red-500 hover:text-red-700" x-show="pairs.length > 2">×</button>
                                </div>
                            </template>
                            <button type="button" @click="addPair()" class="text-sm text-blue-600 hover:text-blue-800">+ Add Pair</button>
                            <p class="text-xs text-gray-500 mt-1">Students will match the left item to the right item. For Drag & Drop, this defines the target zones and draggable items.</p>
                        </div>

                        <!-- Sequencing Fields -->
                        <div class="mb-4" x-show="type === 'sequencing'">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sequence Order (Correct Order)</label>
                            <template x-for="(item, index) in sequence_items" :key="index">
                                <div class="flex gap-2 mb-2">
                                    <span class="self-center font-bold w-6" x-text="index + 1 + '.'"></span>
                                    <input type="text" :name="'sequence['+index+']'" x-model="sequence_items[index]" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <button type="button" @click="removeSequenceItem(index)" class="text-red-500 hover:text-red-700" x-show="sequence_items.length > 2">×</button>
                                </div>
                            </template>
                            <button type="button" @click="addSequenceItem()" class="text-sm text-blue-600 hover:text-blue-800">+ Add Step</button>
                            <p class="text-xs text-gray-500 mt-1">Enter the items in the correct order. They will be shuffled for the student.</p>
                        </div>
                        
                        <div class="flex items-center gap-4">
                            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Add Question
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mt-6 flex justify-between">
                <a href="{{ route('courses.modules.index', $quiz->lesson->module->course) }}" class="text-gray-500 hover:text-gray-700">&larr; Back to Course Modules</a>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css">
    <script src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/contrib/auto-render.min.js"></script>
    <script>
        (function () {
            const linkInput = document.getElementById('quiz-student-link');
            const copyBtn = document.getElementById('copy-quiz-link');
            if (copyBtn && linkInput) {
                copyBtn.addEventListener('click', async function () {
                    try {
                        if (navigator.clipboard && navigator.clipboard.writeText) {
                            await navigator.clipboard.writeText(linkInput.value);
                        } else {
                            linkInput.focus();
                            linkInput.select();
                            document.execCommand('copy');
                            linkInput.setSelectionRange(0, 0);
                            linkInput.blur();
                        }
                        copyBtn.textContent = 'Tersalin';
                        setTimeout(function () {
                            copyBtn.textContent = 'Salin Link';
                        }, 1200);
                    } catch (e) {
                    }
                });
            }
        })();
    </script>
    <script>
        (function(){
            const ta = document.querySelector('textarea[name="question"]');
            const pv = document.getElementById('question-preview');
            const update = () => {
                if (!pv || !ta) return;
                pv.textContent = ta.value || '';
                try {
                    renderMathInElement(pv, {
                        delimiters: [
                            {left: "$$", right: "$$", display: true},
                            {left: "\\[", right: "\\]", display: true},
                            {left: "$", right: "$", display: false},
                            {left: "\\(", right: "\\)", display: false}
                        ],
                        throwOnError: false
                    });
                } catch(e){}
            };
            if (ta) {
                ta.addEventListener('input', update);
                update();
            }
            const optionInputs = {
                a: document.querySelector('input[name="option_a"]'),
                b: document.querySelector('input[name="option_b"]'),
                c: document.querySelector('input[name="option_c"]'),
                d: document.querySelector('input[name="option_d"]'),
                e: document.querySelector('input[name="option_e"]'),
            };
            const optionPreviews = {
                a: document.querySelector('.option-preview[data-option="a"]'),
                b: document.querySelector('.option-preview[data-option="b"]'),
                c: document.querySelector('.option-preview[data-option="c"]'),
                d: document.querySelector('.option-preview[data-option="d"]'),
                e: document.querySelector('.option-preview[data-option="e"]'),
            };
            const renderOption = (key) => {
                const inp = optionInputs[key], out = optionPreviews[key];
                if (!inp || !out) return;
                out.textContent = inp.value || '';
                try {
                    renderMathInElement(out, {
                        delimiters: [
                            {left: "$$", right: "$$", display: true},
                            {left: "\\[", right: "\\]", display: true},
                            {left: "$", right: "$", display: false},
                            {left: "\\(", right: "\\)", display: false}
                        ],
                        throwOnError: false
                    });
                } catch(e){}
            };
            ['a','b','c','d','e'].forEach(k => {
                if (optionInputs[k]) {
                    optionInputs[k].addEventListener('input', () => renderOption(k));
                    renderOption(k);
                }
            });
            const list = document.getElementById('questions-list');
            if (list) {
                try {
                    renderMathInElement(list, {
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
