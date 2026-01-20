<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1">
            <h2 class="text-xl font-semibold text-text-dark leading-tight">
                {{ __('Tambah Pelajaran') }}
            </h2>
            <div class="text-sm text-gray-600">
                {{ __('Untuk modul: ') . $module->title }}
            </div>
        </div>
    </x-slot>

    <div class="relative py-10 bg-background">
        <div class="pointer-events-none absolute inset-0 overflow-hidden">
            <div class="absolute -top-28 -right-28 h-80 w-80 rounded-full bg-hover-tertiary blur-3xl opacity-70"></div>
            <div class="absolute -bottom-28 -left-28 h-80 w-80 rounded-full bg-hover-secondary blur-3xl opacity-70"></div>
        </div>
        <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-surface border border-gray-200 shadow-sm rounded-2xl overflow-hidden">
                <div class="h-1.5 bg-gradient-to-r from-tertiary via-secondary to-primary"></div>
                <div class="p-6 sm:p-8">
                    <div class="mb-8">
                        <div class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-tertiary to-secondary px-3 py-1 text-xs font-semibold text-white shadow-sm">
                            {{ __('Editor WYSIWYG') }}
                        </div>
                        <h3 class="mt-3 text-2xl font-bold text-text-dark tracking-tight">
                            {{ __('Buat materi pelajaran yang mudah dipahami') }}
                        </h3>
                        <p class="mt-2 text-sm text-gray-600 leading-relaxed">
                            {{ __('Gunakan toolbar untuk menambahkan gambar, embed YouTube, atau embed dokumen (URL publik).') }}
                        </p>
                    </div>

                    <form method="POST" action="{{ route('modules.lessons.store', $module) }}" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div>
                                <label class="block text-sm font-semibold text-text-dark">{{ __('Judul Pelajaran') }}</label>
                                <input type="text" name="title" placeholder="Contoh: Pengenalan HTML" class="mt-2 block w-full rounded-xl border-gray-200 bg-white shadow-sm focus:border-tertiary focus:ring-tertiary" required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-text-dark">{{ __('Tipe') }}</label>
                                <select name="type" id="type" class="mt-2 block w-full rounded-xl border-gray-200 bg-white shadow-sm focus:border-tertiary focus:ring-tertiary">
                                    <option value="video">{{ __('Video') }}</option>
                                    <option value="text">{{ __('Teks / Artikel') }}</option>
                                    <option value="pdf">{{ __('Dokumen PDF') }}</option>
                                    <option value="doc">{{ __('Dokumen Word') }}</option>
                                    <option value="xls">{{ __('Dokumen Excel') }}</option>
                                    <option value="ppt">{{ __('PowerPoint / Presentasi') }}</option>
                                </select>
                            </div>
                        </div>

                        <div id="content-field">
                            <div class="flex items-center justify-between gap-3">
                                <label class="block text-sm font-semibold text-text-dark">{{ __('Konten') }}</label>
                                <div class="hidden sm:flex items-center gap-2 text-xs text-gray-600">
                                    <span class="inline-flex items-center rounded-full bg-hover-secondary px-2.5 py-1">{{ __('Image') }}</span>
                                    <span class="inline-flex items-center rounded-full bg-hover-secondary px-2.5 py-1">{{ __('Video') }}</span>
                                    <span class="inline-flex items-center rounded-full bg-hover-secondary px-2.5 py-1">{{ __('Embed') }}</span>
                                </div>
                            </div>
                            <div class="mt-3 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                            <div id="content-editor-toolbar" class="ql-toolbar ql-snow border-0 border-b border-gray-200 bg-white">
                                <span class="ql-formats">
                                    <button class="ql-bold"></button>
                                    <button class="ql-italic"></button>
                                    <button class="ql-underline"></button>
                                </span>
                                <span class="ql-formats">
                                    <select class="ql-header">
                                        <option value="1"></option>
                                        <option value="2"></option>
                                        <option value="3"></option>
                                        <option selected></option>
                                    </select>
                                </span>
                                <span class="ql-formats">
                                    <select class="ql-align"></select>
                                </span>
                                <span class="ql-formats">
                                    <button class="ql-list" value="ordered"></button>
                                    <button class="ql-list" value="bullet"></button>
                                </span>
                                <span class="ql-formats">
                                    <button class="ql-link"></button>
                                    <button class="ql-image"></button>
                                    <button class="ql-video"></button>
                                    <button class="ql-embed"></button>
                                </span>
                                <span class="ql-formats">
                                    <button class="ql-clean"></button>
                                </span>
                            </div>
                            <div id="content-editor-quill" class="block w-full"></div>
                            </div>
                            <input type="hidden" name="content" id="content-hidden" />
                            <p class="mt-3 text-xs text-gray-600 leading-relaxed">
                                {{ __('Tips: tombol Video mendukung URL YouTube; tombol Embed mendukung URL YouTube/PDF/PPTX/DOC/XLS publik atau kode iframe.') }}
                            </p>
                        </div>

                        <div id="file-field" class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-text-dark">{{ __('Unggah File Lampiran (Opsional)') }}</label>
                                <div class="mt-2 rounded-2xl border border-dashed border-gray-300 bg-gradient-to-r from-white via-hover-secondary to-white p-4">
                                    <div class="flex flex-col gap-1">
                                        <div class="text-sm text-gray-700 font-medium">
                                            {{ __('PDF / Word / Excel / PPT / Video') }}
                                        </div>
                                        <div class="text-xs text-gray-600">
                                            {{ __('Ukuran maksimal 10MB. Untuk pratinjau PPT/DOC lewat embed, gunakan URL publik.') }}
                                        </div>
                                    </div>
                                    <input type="file" name="file" class="mt-3 block w-full text-sm text-gray-700 file:mr-4 file:rounded-xl file:border-0 file:bg-hover-tertiary file:px-4 file:py-2.5 file:text-sm file:font-semibold file:text-tertiary hover:file:bg-hover-secondary">
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <a href="{{ route('courses.modules.index', $module->course) }}" class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50">
                                {{ __('Batal') }}
                            </a>
                            <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-tertiary to-secondary px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:opacity-95 focus:outline-none focus:ring-2 focus:ring-tertiary focus:ring-offset-2">
                                {{ __('Simpan Pelajaran') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="paste-modal" class="fixed inset-0 z-50 hidden" aria-hidden="true">
        <div id="paste-modal-backdrop" class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
        <div class="relative mx-auto flex min-h-full max-w-2xl items-center justify-center p-4">
            <div class="w-full rounded-2xl bg-white shadow-xl ring-1 ring-black/5">
                <div class="px-6 py-5">
                    <div class="text-lg font-bold text-text-dark" id="paste-modal-title"></div>
                    <div class="mt-1 text-sm text-gray-600" id="paste-modal-desc"></div>
                    <textarea id="paste-modal-input" rows="4" class="mt-4 w-full rounded-xl border border-gray-200 bg-white p-3 text-sm text-gray-800 shadow-sm focus:border-tertiary focus:ring-tertiary"></textarea>
                    <div class="mt-4 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                        <button type="button" id="paste-modal-cancel" class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50">{{ __('Batal') }}</button>
                        <button type="button" id="paste-modal-submit" class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-tertiary to-secondary px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:opacity-95 focus:outline-none focus:ring-2 focus:ring-tertiary focus:ring-offset-2">{{ __('Sisipkan') }}</button>
                    </div>
                    <div class="mt-2 text-xs text-gray-500">{{ __('Tip: Ctrl+Enter untuk menyisipkan.') }}</div>
                </div>
            </div>
        </div>
    </div>

    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
    <style>
        #content-editor-toolbar.ql-toolbar.ql-snow {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 10px;
        }

        #content-editor-toolbar .ql-formats {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-right: 0;
        }

        #content-editor-toolbar.ql-toolbar.ql-snow button,
        #content-editor-toolbar.ql-toolbar.ql-snow .ql-picker {
            margin: 0;
        }

        #content-editor-quill .ql-editor {
            min-height: 280px;
            font-size: 16px;
            line-height: 1.75;
            color: #2D3436;
            padding: 16px;
        }

        #content-editor-quill .ql-editor.ql-blank::before {
            color: #6B7280;
            font-style: normal;
        }

        #content-editor-toolbar.ql-toolbar.ql-snow {
            padding: 10px 12px;
        }

        #content-editor-toolbar .ql-embed {
            height: 26px;
            border-radius: 10px;
            width: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            font-weight: 600;
            color: #6C5CE7;
            line-height: 1;
            white-space: nowrap;
        }

        #content-editor-toolbar .ql-embed::before {
            content: '</>';
            font-size: 12px;
            letter-spacing: 0.5px;
        }

        #content-editor-toolbar .ql-embed:hover {
            background: rgba(108, 92, 231, 0.12);
        }
    </style>
    <script src="https://cdn.quilljs.com/1.3.7/quill.js"></script>
    <script>
        (function () {
            const toolbarEl = document.getElementById('content-editor-toolbar');
            const quillContainer = document.getElementById('content-editor-quill');
            const hiddenInput = document.getElementById('content-hidden');
            const form = quillContainer.closest('form');
            const uploadUrl = @json(route('modules.lessons.editor-images', $module));
            const csrfToken = @json(csrf_token());

            const modalEl = document.getElementById('paste-modal');
            const modalTitleEl = document.getElementById('paste-modal-title');
            const modalDescEl = document.getElementById('paste-modal-desc');
            const modalInputEl = document.getElementById('paste-modal-input');
            const modalCancelBtn = document.getElementById('paste-modal-cancel');
            const modalSubmitBtn = document.getElementById('paste-modal-submit');
            let resolveModal;

            const closePasteModal = (value) => {
                modalEl.classList.add('hidden');
                modalEl.setAttribute('aria-hidden', 'true');
                const resolver = resolveModal;
                resolveModal = null;
                if (resolver) {
                    resolver(value);
                }
            };

            const openPasteModal = ({ title, description, placeholder }) => {
                modalTitleEl.textContent = title || '';
                modalDescEl.textContent = description || '';
                modalInputEl.value = '';
                modalInputEl.placeholder = placeholder || '';
                modalEl.classList.remove('hidden');
                modalEl.setAttribute('aria-hidden', 'false');
                setTimeout(() => modalInputEl.focus(), 0);
                return new Promise((resolve) => {
                    resolveModal = resolve;
                });
            };

            modalCancelBtn.addEventListener('click', () => closePasteModal(null));
            modalSubmitBtn.addEventListener('click', () => closePasteModal((modalInputEl.value || '').trim() || null));
            modalEl.addEventListener('click', (e) => {
                if (e.target === modalEl || e.target.id === 'paste-modal-backdrop') {
                    closePasteModal(null);
                }
            });
            document.addEventListener('keydown', (e) => {
                if (modalEl.classList.contains('hidden')) {
                    return;
                }
                if (e.key === 'Escape') {
                    e.preventDefault();
                    closePasteModal(null);
                    return;
                }
                if (e.key === 'Enter' && (e.ctrlKey || e.metaKey)) {
                    e.preventDefault();
                    closePasteModal((modalInputEl.value || '').trim() || null);
                }
            });
            let quill;

            const youtubeEmbedUrl = (value) => {
                const input = String(value || '').trim();
                if (!input) {
                    return null;
                }

                if (input.includes('<iframe')) {
                    const m = input.match(/src=["']([^"']+)["']/i);
                    if (m && m[1]) {
                        return m[1];
                    }
                }

                let videoId = null;
                if (input.startsWith('https://youtu.be/') || input.startsWith('http://youtu.be/')) {
                    videoId = input.split('youtu.be/')[1] || null;
                } else if (input.includes('youtube.com/watch')) {
                    try {
                        const u = new URL(input);
                        videoId = u.searchParams.get('v');
                    } catch (e) {
                        videoId = null;
                    }
                } else if (input.includes('youtube.com/embed/')) {
                    videoId = input.split('embed/')[1] || null;
                }

                if (!videoId) {
                    return null;
                }

                videoId = videoId.split(/[?&\s"'>]/)[0];
                if (!videoId) {
                    return null;
                }

                return 'https://www.youtube.com/embed/' + videoId;
            };

            const embedHtmlFromUrl = (rawUrl) => {
                const url = String(rawUrl || '').trim();
                if (!url) {
                    return null;
                }

                const lower = url.toLowerCase();

                if (lower.endsWith('.pdf')) {
                    return '<div class="w-full my-4"><iframe src="' + url + '" class="w-full" style="height:600px" frameborder="0"></iframe></div>';
                }

                if (lower.endsWith('.ppt') || lower.endsWith('.pptx') || lower.endsWith('.doc') || lower.endsWith('.docx') || lower.endsWith('.xls') || lower.endsWith('.xlsx')) {
                    const viewer = 'https://view.officeapps.live.com/op/embed.aspx?src=' + encodeURIComponent(url);
                    return '<div class="w-full my-4"><iframe src="' + viewer + '" class="w-full" style="height:600px" frameborder="0"></iframe></div>';
                }

                return null;
            };

            const initQuill = () => {
                if (!quill) {
                    quill = new Quill('#content-editor-quill', {
                        theme: 'snow',
                        modules: {
                            toolbar: {
                                container: '#content-editor-toolbar',
                                handlers: {
                                    image: function () {
                                        const input = document.createElement('input');
                                        input.setAttribute('type', 'file');
                                        input.setAttribute('accept', 'image/*');
                                        input.click();

                                        input.addEventListener('change', async () => {
                                            const file = input.files && input.files[0] ? input.files[0] : null;
                                            if (!file) {
                                                return;
                                            }

                                            const formData = new FormData();
                                            formData.append('image', file);

                                            let response;
                                            try {
                                                response = await fetch(uploadUrl, {
                                                    method: 'POST',
                                                    headers: {
                                                        'X-CSRF-TOKEN': csrfToken,
                                                        'Accept': 'application/json'
                                                    },
                                                    body: formData
                                                });
                                            } catch (e) {
                                                alert('Gagal mengunggah gambar.');
                                                return;
                                            }

                                            if (!response.ok) {
                                                alert('Gagal mengunggah gambar.');
                                                return;
                                            }

                                            const data = await response.json();
                                            if (!data || !data.url) {
                                                alert('Gagal mengunggah gambar.');
                                                return;
                                            }

                                            const range = quill.getSelection(true) || { index: quill.getLength(), length: 0 };
                                            quill.insertEmbed(range.index, 'image', data.url, 'user');
                                            quill.setSelection(range.index + 1, 0, 'silent');
                                        });
                                    },
                                    video: async function () {
                                        const input = await openPasteModal({
                                            title: 'Tambahkan Video',
                                            description: 'Tempel URL YouTube (youtu.be / watch?v=...) atau URL embed.',
                                            placeholder: 'https://youtu.be/... atau https://www.youtube.com/watch?v=...'
                                        });
                                        if (!input) {
                                            return;
                                        }
                                        const embed = youtubeEmbedUrl(input) || input;
                                        const range = quill.getSelection(true) || { index: quill.getLength(), length: 0 };
                                        quill.insertEmbed(range.index, 'video', embed, 'user');
                                        quill.setSelection(range.index + 1, 0, 'silent');
                                    },
                                    embed: async function () {
                                        const input = await openPasteModal({
                                            title: 'Tambahkan Embed',
                                            description: 'Tempel URL YouTube/PDF/PPTX/DOC (harus URL publik) atau kode iframe.',
                                            placeholder: 'https://... atau <iframe ...></iframe>'
                                        });
                                        if (!input) {
                                            return;
                                        }

                                        const range = quill.getSelection(true) || { index: quill.getLength(), length: 0 };

                                        if (String(input).includes('<iframe')) {
                                            quill.clipboard.dangerouslyPasteHTML(range.index, String(input), 'user');
                                            return;
                                        }

                                        const yt = youtubeEmbedUrl(input);
                                        if (yt) {
                                            quill.insertEmbed(range.index, 'video', yt, 'user');
                                            quill.setSelection(range.index + 1, 0, 'silent');
                                            return;
                                        }

                                        const html = embedHtmlFromUrl(input);
                                        if (html) {
                                            quill.clipboard.dangerouslyPasteHTML(range.index, html, 'user');
                                            return;
                                        }

                                        alert('URL tidak dikenali. Gunakan URL YouTube, URL file PDF/PPTX/DOC publik, atau kode iframe.');
                                    }
                                }
                            }
                        }
                    });
                }
            };

            toolbarEl.style.display = '';
            quillContainer.style.display = '';
            initQuill();

            form.addEventListener('submit', function () {
                hiddenInput.value = quill ? quill.root.innerHTML : '';
            });
        })();
    </script>
</x-app-layout>
