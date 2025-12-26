<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manage Quiz: ') . $quiz->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <x-auth-session-status class="mb-4" :status="session('success')" />

            <!-- Edit Quiz Details -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-bold mb-4">Quiz Details</h3>
                    <form method="POST" action="{{ route('quizzes.update', $quiz) }}">
                        @csrf
                        @method('PUT')
                        <div class="flex gap-4 mb-4">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                                <input type="text" name="title" value="{{ $quiz->title }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            </div>
                            <div class="w-32">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Passing Score</label>
                                <input type="number" name="passing_score" value="{{ $quiz->passing_score }}" min="0" max="100" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            </div>
                        </div>
                        <div class="flex justify-between">
                            <form action="{{ route('quizzes.destroy', $quiz) }}" method="POST" onsubmit="return confirm('Delete entire quiz?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700">Delete Quiz</button>
                            </form>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update Details
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Questions List -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-bold mb-4">Questions ({{ $quiz->questions->count() }})</h3>
                    @if($quiz->questions->isEmpty())
                        <p class="text-gray-500">No questions yet.</p>
                    @else
                        <ul class="space-y-4">
                            @foreach($quiz->questions as $index => $question)
                                <li class="border dark:border-gray-700 p-4 rounded bg-gray-50 dark:bg-gray-700">
                                    <div class="flex justify-between">
                                        <p class="font-bold mb-2">{{ $index + 1 }}. {{ $question->question }}</p>
                                        <form action="{{ route('questions.destroy', $question) }}" method="POST" onsubmit="return confirm('Delete question?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 text-sm">Delete</button>
                                        </form>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2 text-sm">
                                        <div class="{{ $question->correct_answer == 'a' ? 'text-green-500 font-bold' : '' }}">A: {{ $question->options['a'] }}</div>
                                        <div class="{{ $question->correct_answer == 'b' ? 'text-green-500 font-bold' : '' }}">B: {{ $question->options['b'] }}</div>
                                        <div class="{{ $question->correct_answer == 'c' ? 'text-green-500 font-bold' : '' }}">C: {{ $question->options['c'] }}</div>
                                        <div class="{{ $question->correct_answer == 'd' ? 'text-green-500 font-bold' : '' }}">D: {{ $question->options['d'] }}</div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            <!-- Add Question Form -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-bold mb-4">Add New Question</h3>
                    <form method="POST" action="{{ route('quizzes.questions.store', $quiz) }}">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Question Text</label>
                            <textarea name="question" rows="2" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required></textarea>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Option A</label>
                                <input type="text" name="option_a" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Option B</label>
                                <input type="text" name="option_b" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Option C</label>
                                <input type="text" name="option_c" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Option D</label>
                                <input type="text" name="option_d" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Correct Answer</label>
                            <select name="correct_answer" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="a">Option A</option>
                                <option value="b">Option B</option>
                                <option value="c">Option C</option>
                                <option value="d">Option D</option>
                            </select>
                        </div>
                        <div class="flex justify-end">
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
</x-app-layout>
