<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manual Administrator
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="prose max-w-none">
                        <h1>Panduan Penggunaan Sistem untuk Administrator</h1>
                        
                        <p>Selamat datang di panel admin. Halaman ini berisi panduan singkat untuk mengelola sistem LMS ini.</p>

                        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- 1. Dasbor -->
                            <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                                <h3 class="text-lg font-bold text-indigo-700 mb-2">1. Dasbor Utama</h3>
                                <p class="text-sm text-gray-600 mb-2">
                                    Halaman utama setelah login menampilkan ringkasan sistem:
                                </p>
                                <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                                    <li>Total Siswa, Guru, dan Pelajaran.</li>
                                    <li>Daftar pengguna terbaru yang mendaftar.</li>
                                    <li>Daftar pelajaran yang baru saja ditambahkan.</li>
                                </ul>
                            </div>

                            <!-- 2. Manajemen Pengguna -->
                            <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                                <h3 class="text-lg font-bold text-indigo-700 mb-2">2. Manajemen Pengguna</h3>
                                <p class="text-sm text-gray-600 mb-2">
                                    Menu <strong>Manajemen Pengguna</strong> digunakan untuk mengelola akun:
                                </p>
                                <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                                    <li><strong>Tambah Pengguna:</strong> Klik tombol "Buat Pengguna Baru". Pilih peran (Siswa/Guru).</li>
                                    <li><strong>Edit Pengguna:</strong> Ubah nama, email, password, atau peran pengguna.</li>
                                    <li><strong>Hapus Pengguna:</strong> Menghapus akun secara permanen.</li>
                                </ul>
                            </div>

                            <!-- 3. Kategori Pelajaran -->
                            <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                                <h3 class="text-lg font-bold text-indigo-700 mb-2">3. Kategori Pelajaran</h3>
                                <p class="text-sm text-gray-600 mb-2">
                                    Menu <strong>Kategori</strong> digunakan untuk mengelompokkan pelajaran.
                                </p>
                                <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                                    <li>Buat kategori baru (contoh: Paket A, Paket B, Umum).</li>
                                    <li>Edit atau hapus kategori yang sudah ada.</li>
                                </ul>
                            </div>

                            <!-- 4. Manajemen Pelajaran -->
                            <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                                <h3 class="text-lg font-bold text-indigo-700 mb-2">4. Manajemen Pelajaran</h3>
                                <p class="text-sm text-gray-600 mb-2">
                                    Menu <strong>Pelajaran</strong> adalah inti dari LMS ini.
                                </p>
                                <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                                    <li><strong>Buat Pelajaran:</strong> Masukkan judul, deskripsi, gambar sampul, dan tentukan Guru pengampu.</li>
                                    <li><strong>Modul & Materi:</strong> Setelah pelajaran dibuat, tambahkan Modul (Bab) dan Materi (Video/Teks/PDF).</li>
                                    <li><strong>Kuis:</strong> Tambahkan kuis di setiap materi untuk evaluasi siswa.</li>
                                </ul>
                            </div>

                            <!-- 5. Berita & Pengumuman -->
                            <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                                <h3 class="text-lg font-bold text-indigo-700 mb-2">5. Berita</h3>
                                <p class="text-sm text-gray-600 mb-2">
                                    Menu <strong>Berita</strong> untuk mempublikasikan informasi terbaru.
                                </p>
                                <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                                    <li>Buat berita baru dengan judul, konten, dan gambar thumbnail.</li>
                                    <li>Berita akan muncul di halaman depan (Landing Page) dan aplikasi mobile.</li>
                                </ul>
                            </div>

                            <!-- 6. Pengaturan -->
                            <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                                <h3 class="text-lg font-bold text-indigo-700 mb-2">6. Pengaturan</h3>
                                <p class="text-sm text-gray-600 mb-2">
                                    Menu <strong>Pengaturan</strong> berisi konfigurasi umum sistem.
                                </p>
                                <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                                    <li>Ubah nama aplikasi, kontak, atau informasi footer.</li>
                                    <li>Pengaturan lain yang bersifat global.</li>
                                </ul>
                            </div>
                        </div>

                        <div class="mt-8 bg-blue-50 border-l-4 border-blue-500 p-4">
                            <p class="text-sm text-blue-700">
                                <strong>Catatan Penting:</strong> <br>
                                Selalu pastikan untuk memeriksa data sebelum menghapus. Data yang dihapus (seperti Pengguna atau Pelajaran) juga akan menghapus data terkait (seperti Nilai, Progres, dll).
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
