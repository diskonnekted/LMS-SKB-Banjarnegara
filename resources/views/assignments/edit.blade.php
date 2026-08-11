<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1">
            <h2 class="text-xl font-semibold text-text-dark leading-tight">
                {{ __('Edit Tugas') }}
            </h2>
            <div class="text-sm text-gray-600">
                {{ __('Untuk pelajaran: ') . $lesson->title }}
            </div>
        </div>
    </x-slot>

    <div class="relative py-10 bg-background">
        <div class="pointer-events-none absolute inset-0 overflow-hidden">
            <div class="absolute -top-28 -right-28 h-80 w-80 rounded-full bg-hover-tertiary blur-3xl opacity-70"></div>
            <div class="absolute -bottom-28 -left-28 h-80 w-80 rounded-full bg-hover-secondary blur-3xl opacity-70"></div>
        </div>
        <div class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-surface border border-gray-200 shadow-sm rounded-2xl overflow-hidden">
                <div class="h-1.5 bg-gradient-to-r from-tertiary via-secondary to-primary"></div>
                <div class="p-6 sm:p-8">
                    <form method="POST" action="{{ route('modules.lessons.assignments.update', [$module, $lesson, $assignment]) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-sm font-semibold text-text-dark">{{ __('Judul Tugas') }}</label>
                            <input type="text" name="title" value="{{ old('title', $assignment->title) }}" class="mt-2 block w-full rounded-xl border-gray-200 bg-white shadow-sm focus:border-tertiary focus:ring-tertiary" required>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-text-dark">{{ __('Instruksi') }}</label>
                            <textarea name="description" rows="4" class="mt-2 block w-full rounded-xl border-gray-200 bg-white shadow-sm focus:border-tertiary focus:ring-tertiary">{{ old('description', $assignment->description) }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="block text-sm font-semibold text-text-dark">{{ __('Tenggat Waktu') }}</label>
                                <input type="datetime-local" name="due_date" value="{{ old('due_date', $assignment->due_date?->format('Y-m-d\TH:i')) }}" class="mt-2 block w-full rounded-xl border-gray-200 bg-white shadow-sm focus:border-tertiary focus:ring-tertiary">
                                <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ada batas waktu</p>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-text-dark">{{ __('Maks. Ukuran File (MB)') }}</label>
                                <input type="number" name="file_size_limit" min="1" max="50" value="{{ old('file_size_limit', $assignment->file_size_limit) }}" class="mt-2 block w-full rounded-xl border-gray-200 bg-white shadow-sm focus:border-tertiary focus:ring-tertiary">
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $assignment->is_active) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-tertiary focus:ring-tertiary">
                            <label class="text-sm text-gray-700">{{ __('Tugas Aktif') }}</label>
                        </div>

                        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <a href="{{ route('modules.lessons.assignments.index', [$module, $lesson]) }}" class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50">
                                {{ __('Batal') }}
                            </a>
                            <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-tertiary to-secondary px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:opacity-95 focus:outline-none focus:ring-2 focus:ring-tertiary focus:ring-offset-2">
                                {{ __('Perbarui Tugas') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
