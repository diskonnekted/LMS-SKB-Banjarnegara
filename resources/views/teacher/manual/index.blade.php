<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manual Guru
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="prose max-w-none">
                        <h1>Panduan Penggunaan Sistem untuk Guru</h1>
                        
                        <p>Halaman ini berisi panduan lengkap untuk mengelola pembelajaran, mulai dari membuat pelajaran hingga memantau perkembangan siswa.</p>

                        <div class="mt-8 space-y-12">
                            
                            <!-- 1. Dasbor Guru -->
                            <section>
                                <h3 class="text-xl font-bold text-indigo-700 mb-4 border-b pb-2">1. Memahami Dasbor Guru</h3>
                                <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                                    <p class="mb-4">Dasbor adalah pusat kontrol Anda. Di sini Anda dapat melihat:</p>
                                    <ul class="list-disc list-inside space-y-2 text-gray-700">
                                        <li><strong>Statistik Ringkas:</strong> Jumlah pelajaran yang Anda ampu dan total siswa yang terdaftar di kelas Anda.</li>
                                        <li><strong>Daftar Pelajaran Saya:</strong> Akses cepat ke kursus-kursus yang Anda kelola.</li>
                                        <li><strong>Daftar Siswa & Nilai:</strong> Tabel detail yang menampilkan progres belajar siswa dan rata-rata nilai kuis mereka.</li>
                                    </ul>
                                </div>
                            </section>

                            <!-- 2. Membuat & Mengelola Pelajaran -->
                            <section>
                                <h3 class="text-xl font-bold text-indigo-700 mb-4 border-b pb-2">2. Manajemen Pelajaran (Course)</h3>
                                <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                                    <p class="mb-4">Untuk membuat materi baru, masuk ke menu <strong>Pelajaran</strong> di navigasi atas.</p>
                                    
                                    <h4 class="font-bold text-md text-gray-800 mt-4 mb-2">Langkah Membuat Pelajaran:</h4>
                                    <ol class="list-decimal list-inside space-y-2 text-gray-700">
                                        <li>Klik tombol <strong>"Buat Pelajaran Baru"</strong>.</li>
                                        <li>Isi <strong>Judul Pelajaran</strong>, <strong>Deskripsi</strong>, dan upload <strong>Gambar Sampul</strong> (Thumbnail).</li>
                                        <li>Pilih <strong>Kategori</strong> dan <strong>Tingkat Kelas</strong> (misal: Kelas 10, Paket A).</li>
                                        <li>Isi Kompetensi Dasar (KD) dan Tujuan Pembelajaran jika diperlukan.</li>
                                        <li>Klik <strong>Simpan</strong>.</li>
                                    </ol>
                                    
                                    <div class="mt-4 bg-blue-50 p-4 rounded text-sm text-blue-800">
                                        <strong>Tips:</strong> Pastikan status pelajaran diubah menjadi <strong>"Published"</strong> agar bisa dilihat oleh siswa.
                                    </div>
                                </div>
                            </section>

                            <!-- 3. Struktur Modul & Materi -->
                            <section>
                                <h3 class="text-xl font-bold text-indigo-700 mb-4 border-b pb-2">3. Mengisi Materi (Modul & Lesson)</h3>
                                <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                                    <p class="mb-4">Struktur pembelajaran terdiri dari: <strong>Pelajaran > Modul (Bab) > Materi (Lesson)</strong>.</p>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <h4 class="font-bold text-md text-gray-800 mb-2">A. Membuat Modul (Bab)</h4>
                                            <p class="text-sm text-gray-600 mb-2">Modul adalah pengelompokan materi, misalnya "BAB 1: Pendahuluan".</p>
                                            <ul class="list-disc list-inside text-sm text-gray-600">
                                                <li>Buka detail pelajaran.</li>
                                                <li>Di bagian bawah, klik <strong>"Tambah Modul"</strong>.</li>
                                                <li>Beri nama modul dan urutannya.</li>
                                            </ul>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-md text-gray-800 mb-2">B. Menambah Materi (Lesson)</h4>
                                            <p class="text-sm text-gray-600 mb-2">Materi adalah konten yang dipelajari siswa.</p>
                                            <ul class="list-disc list-inside text-sm text-gray-600">
                                                <li>Di dalam modul, klik <strong>"Tambah Materi"</strong>.</li>
                                                <li>Pilih tipe materi: <strong>Video, Teks, PDF, atau PPT</strong>.</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <!-- 4. Cara Embed Video & Dokumen -->
                            <section>
                                <h3 class="text-xl font-bold text-indigo-700 mb-4 border-b pb-2">4. Cara Embed Video & Dokumen</h3>
                                <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 space-y-6">
                                    
                                    <!-- Youtube -->
                                    <div>
                                        <h4 class="font-bold text-lg text-red-600 mb-2">🎥 Embed Video YouTube</h4>
                                        <p class="text-sm text-gray-700 mb-2">
                                            Gunakan fitur embed agar video bisa diputar langsung di dalam aplikasi tanpa membuka YouTube.
                                        </p>
                                        <ol class="list-decimal list-inside text-sm text-gray-700 bg-white p-4 rounded border">
                                            <li>Buka video di YouTube.</li>
                                            <li>Klik tombol <strong>Share (Bagikan)</strong>.</li>
                                            <li>Pilih <strong>Embed (Sematkan)</strong>.</li>
                                            <li>Salin kode HTML yang muncul (biasanya diawali dengan <code>&lt;iframe...</code>).</li>
                                            <li>Di formulir materi, pilih tipe <strong>Video</strong> dan tempel kode tersebut di kolom konten.</li>
                                        </ol>
                                    </div>

                                    <!-- Google Drive -->
                                    <div>
                                        <h4 class="font-bold text-lg text-blue-600 mb-2">📄 Embed PDF/PPT dari Google Drive</h4>
                                        <p class="text-sm text-gray-700 mb-2">
                                            Agar siswa bisa membaca dokumen tanpa mendownload:
                                        </p>
                                        <ol class="list-decimal list-inside text-sm text-gray-700 bg-white p-4 rounded border">
                                            <li>Upload file PDF/PPT ke Google Drive Anda.</li>
                                            <li>Klik kanan file > <strong>Share (Bagikan)</strong> > Ubah akses menjadi <strong>"Anyone with the link" (Siapa saja yang memiliki link)</strong>.</li>
                                            <li>Buka file tersebut (double click) di browser.</li>
                                            <li>Klik ikon titik tiga di pojok kanan atas > pilih <strong>"Open in new window" (Buka di jendela baru)</strong>.</li>
                                            <li>Di jendela baru, klik titik tiga lagi > pilih <strong>"Embed item" (Sematkan item)</strong>.</li>
                                            <li>Salin kode HTML iframe tersebut.</li>
                                            <li>Tempelkan di editor materi LMS pada mode <strong>Source Code</strong> atau kolom embed yang tersedia.</li>
                                        </ol>
                                    </div>

                                </div>
                            </section>

                            <!-- 5. Membuat Kuis -->
                            <section>
                                <h3 class="text-xl font-bold text-indigo-700 mb-4 border-b pb-2">5. Membuat Kuis & Evaluasi</h3>
                                <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                                    <p class="mb-4">Setiap materi bisa memiliki kuis untuk menguji pemahaman siswa.</p>
                                    <ul class="list-disc list-inside text-gray-700 space-y-2">
                                        <li>Pada daftar materi, klik tombol <strong>"Kuis"</strong>.</li>
                                        <li>Buat judul kuis dan tentukan <strong>KKM (Passing Score)</strong>.</li>
                                        <li>Tambahkan pertanyaan pilihan ganda.</li>
                                        <li>Tentukan kunci jawaban yang benar.</li>
                                    </ul>
                                </div>
                            </section>

                        </div>

                        <div class="mt-12 p-4 bg-yellow-50 border border-yellow-200 rounded text-sm text-yellow-800">
                            <strong>Butuh Bantuan?</strong> Hubungi Administrator jika Anda mengalami kendala teknis atau membutuhkan akses tambahan.
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
