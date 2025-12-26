<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Pelajaran ke: ') . $module->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('modules.lessons.store', $module) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Judul Pelajaran') }}</label>
                            <input type="text" name="title" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Tipe') }}</label>
                            <select name="type" id="type" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="video">{{ __('Video') }}</option>
                                <option value="text">{{ __('Teks / Artikel') }}</option>
                                <option value="pdf">{{ __('Dokumen PDF') }}</option>
                                <option value="doc">{{ __('Dokumen Word') }}</option>
                                <option value="xls">{{ __('Dokumen Excel') }}</option>
                                <option value="ppt">{{ __('PowerPoint / Presentasi') }}</option>
                            </select>
                        </div>

                        <div class="mb-4" id="content-field">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Konten / URL / Kode Embed (Untuk Video/Teks)') }}</label>
                            <div id="content-editor-quill" class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-700"></div>
                            <textarea id="content-editor-plain" rows="6" class="mt-3 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                            <input type="hidden" name="content" id="content-hidden" />
                            <p class="text-xs text-gray-500 mt-1">{{ __('Tempel URL YouTube (atau kode iframe) untuk video, atau tulis artikel teks di sini.') }}</p>
                        </div>

                        

                        <div class="mb-4" id="file-field">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Unggah File (PDF/Word/Excel/PPT/Video)') }}</label>
                            <input type="file" name="file" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-500 file:text-white hover:file:bg-indigo-600">
                        </div>

                        <div class="flex justify-end">
                            <a href="{{ route('courses.modules.index', $module->course) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">{{ __('Batal') }}</a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Buat Pelajaran') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.7/quill.js"></script>
    <script>
        (function () {
            const typeSelect = document.getElementById('type');
            const quillContainer = document.getElementById('content-editor-quill');
            const plainTextarea = document.getElementById('content-editor-plain');
            const hiddenInput = document.getElementById('content-hidden');
            const form = quillContainer.closest('form');
            let quill;
            const initQuill = () => {
                if (!quill) {
                    quill = new Quill('#content-editor-quill', {
                        theme: 'snow',
                        modules: {
                            toolbar: [
                                ['bold', 'italic', 'underline'],
                                [{ 'header': [1, 2, 3, false] }],
                                [{ 'align': [] }],
                                [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                                ['link'],
                                ['clean']
                            ]
                        }
                    });
                }
            };
            const updateVisibility = () => {
                if (typeSelect.value === 'text') {
                    quillContainer.style.display = '';
                    plainTextarea.style.display = 'none';
                    initQuill();
                } else {
                    quillContainer.style.display = 'none';
                    plainTextarea.style.display = '';
                }
            };
            typeSelect.addEventListener('change', updateVisibility);
            form.addEventListener('submit', function () {
                if (typeSelect.value === 'text' && quill) {
                    hiddenInput.value = quill.root.innerHTML;
                } else {
                    hiddenInput.value = plainTextarea.value;
                }
            });
            updateVisibility();
        })();
    </script>
</x-app-layout>
