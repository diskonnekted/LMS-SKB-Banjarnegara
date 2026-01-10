<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Soal Ujian</h2>
                <div class="text-sm text-gray-500">{{ $question->exam->title }}</div>
            </div>
            <a href="{{ route('teacher.exams.edit', $question->exam) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50">Kembali</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900" x-data="{type: @js(old('type', $question->type))}">
                    @if ($errors->any())
                        <div class="mb-4 rounded-md bg-red-50 p-4 text-sm text-red-700">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('teacher.exam-questions.update', $question) }}" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tipe Soal</label>
                                <select x-model="type" name="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="multiple_choice">Multiple Choice</option>
                                    <option value="multiple_response">Multiple Response</option>
                                    <option value="true_false">True / False</option>
                                    <option value="short_answer">Short Answer</option>
                                    <option value="numeric">Numeric</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Poin</label>
                                <input type="number" name="points" value="{{ old('points', $question->points) }}" min="1" max="1000" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Urutan</label>
                                <input type="number" name="order" value="{{ old('order', $question->order) }}" min="0" max="100000" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Media URL (opsional)</label>
                            <input type="text" name="media_url" value="{{ old('media_url', $question->media_url) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="https://example.com/image.jpg">
                        </div>

                        <div>
                            <div class="flex items-center justify-between gap-3">
                                <label class="block text-sm font-medium text-gray-700">Teks Soal</label>
                                <a href="{{ route('teacher.latex-guide') }}" target="_blank" rel="noopener" class="text-sm text-indigo-600 hover:text-indigo-800 font-semibold">Contoh LaTeX</a>
                            </div>
                            <textarea name="question" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>{{ old('question', $question->question) }}</textarea>
                        </div>

                        <div x-show="type === 'multiple_choice' || type === 'multiple_response'" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Option A</label>
                                <input type="text" name="option_a" value="{{ old('option_a', $options['a'] ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" :required="type === 'multiple_choice' || type === 'multiple_response'">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Option B</label>
                                <input type="text" name="option_b" value="{{ old('option_b', $options['b'] ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" :required="type === 'multiple_choice' || type === 'multiple_response'">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Option C</label>
                                <input type="text" name="option_c" value="{{ old('option_c', $options['c'] ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Option D</label>
                                <input type="text" name="option_d" value="{{ old('option_d', $options['d'] ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Option E</label>
                                <input type="text" name="option_e" value="{{ old('option_e', $options['e'] ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div x-show="type === 'multiple_choice'">
                            <label class="block text-sm font-medium text-gray-700">Kunci Jawaban</label>
                            @php $picked = old('correct_answer_mc', old('correct_answer', $question->correct_answer)); @endphp
                            <select name="correct_answer_mc" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="a" @selected($picked === 'a')>Option A</option>
                                <option value="b" @selected($picked === 'b')>Option B</option>
                                <option value="c" @selected($picked === 'c')>Option C</option>
                                <option value="d" @selected($picked === 'd')>Option D</option>
                                <option value="e" @selected($picked === 'e')>Option E</option>
                            </select>
                        </div>

                        <div x-show="type === 'multiple_response'">
                            <label class="block text-sm font-medium text-gray-700">Kunci Jawaban (boleh lebih dari 1)</label>
                            @php $picked = old('correct_answers', $correctAnswers); @endphp
                            <div class="flex flex-wrap gap-4 mt-2">
                                @foreach(['a','b','c','d','e'] as $k)
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="correct_answers[]" value="{{ $k }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" @checked(is_array($picked) && in_array($k, $picked, true))>
                                        <span class="ml-2">Option {{ strtoupper($k) }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div x-show="type === 'true_false'">
                            <label class="block text-sm font-medium text-gray-700">Kunci Jawaban</label>
                            @php $picked = old('correct_answer_tf', old('correct_answer', $question->correct_answer)); @endphp
                            <select name="correct_answer_tf" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="true" @selected($picked === 'true')>True</option>
                                <option value="false" @selected($picked === 'false')>False</option>
                            </select>
                        </div>

                        <div x-show="type === 'short_answer' || type === 'numeric'">
                            <label class="block text-sm font-medium text-gray-700">Kunci Jawaban</label>
                            <input type="text" name="correct_answer_text" value="{{ old('correct_answer_text', in_array($question->type, ['short_answer', 'numeric'], true) ? $question->correct_answer : '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div class="flex justify-end gap-2">
                            <a href="{{ route('teacher.exams.edit', $question->exam) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Batal</a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
