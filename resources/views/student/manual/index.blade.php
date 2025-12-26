<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manual Siswa
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="prose max-w-none">
                        <h1>Panduan Belajar untuk Siswa</h1>
                        
                        <p>Selamat datang! Panduan ini akan membantu kamu menggunakan aplikasi LMS SKB Banjarnegara untuk kegiatan belajar sehari-hari.</p>

                        <div class="mt-8 space-y-12">
                            
                            <!-- 1. Dasbor -->
                            <section>
                                <h3 class="text-xl font-bold text-indigo-700 mb-4 border-b pb-2">1. Halaman Utama (Dasbor)</h3>
                                <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                                    <p class="mb-4">Saat pertama kali masuk, kamu akan melihat halaman Dasbor yang berisi:</p>
                                    <ul class="list-disc list-inside space-y-2 text-gray-700">
                                        <li><strong>Statistik Belajar:</strong> Jumlah pelajaran yang sedang diikuti dan yang sudah selesai.</li>
                                        <li><strong>Kelas Saya:</strong> Daftar pelajaran yang sedang kamu pelajari. Klik tombol <strong>"Lanjut Belajar"</strong> untuk masuk ke materi.</li>
                                        <li><strong>Jelajahi Pelajaran:</strong> Jika belum ada pelajaran, kamu bisa mencari pelajaran baru di menu ini.</li>
                                    </ul>
                                </div>
                            </section>

                            <!-- 2. Memulai Belajar -->
                            <section>
                                <h3 class="text-xl font-bold text-indigo-700 mb-4 border-b pb-2">2. Cara Mengikuti Pelajaran</h3>
                                <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                                    <h4 class="font-bold text-md text-gray-800 mb-2">A. Mendaftar Pelajaran Baru</h4>
                                    <ol class="list-decimal list-inside space-y-2 text-gray-700 mb-6">
                                        <li>Klik menu <strong>Pelajaran</strong> di bagian atas.</li>
                                        <li>Pilih pelajaran yang kamu minati.</li>
                                        <li>Klik tombol <strong>"Daftar Sekarang"</strong> atau <strong>"Mulai Belajar"</strong>.</li>
                                    </ol>

                                    <h4 class="font-bold text-md text-gray-800 mb-2">B. Membuka Materi</h4>
                                    <ol class="list-decimal list-inside space-y-2 text-gray-700">
                                        <li>Di halaman detail pelajaran, kamu akan melihat daftar <strong>Modul</strong> (Bab) dan <strong>Materi</strong>.</li>
                                        <li>Klik judul materi untuk membukanya.</li>
                                        <li>Materi bisa berupa <strong>Video</strong>, <strong>Teks bacaan</strong>, atau dokumen <strong>PDF/PPT</strong>.</li>
                                        <li>Setelah selesai mempelajari materi, klik tombol <strong>"Tandai Selesai"</strong> (jika ada) atau lanjut ke materi berikutnya.</li>
                                    </ol>
                                </div>
                            </section>

                            <!-- 3. Mengerjakan Kuis -->
                            <section>
                                <h3 class="text-xl font-bold text-indigo-700 mb-4 border-b pb-2">3. Mengerjakan Kuis</h3>
                                <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                                    <p class="mb-4">Beberapa materi memiliki kuis untuk menguji pemahamanmu.</p>
                                    <ul class="list-disc list-inside space-y-2 text-gray-700">
                                        <li>Klik materi yang bertanda ikon Kuis.</li>
                                        <li>Baca instruksi dan perhatikan <strong>Nilai Minimal (KKM)</strong>.</li>
                                        <li>Jawab semua pertanyaan pilihan ganda.</li>
                                        <li>Klik <strong>Submit</strong> untuk melihat nilaimu.</li>
                                        <li>Jika nilaimu belum mencapai KKM, kamu harus mengulang kuis tersebut agar bisa melanjutkan.</li>
                                    </ul>
                                </div>
                            </section>

                            <!-- 4. Sertifikat -->
                            <section>
                                <h3 class="text-xl font-bold text-indigo-700 mb-4 border-b pb-2">4. Mendapatkan Sertifikat</h3>
                                <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                                    <p class="mb-4">Setelah kamu menyelesaikan <strong>semua materi</strong> dan lulus <strong>semua kuis</strong> dalam satu pelajaran:</p>
                                    <ul class="list-disc list-inside space-y-2 text-gray-700">
                                        <li>Status pelajaran akan berubah menjadi <strong>Selesai</strong>.</li>
                                        <li>Kamu bisa mengunduh sertifikat digital melalui menu <strong>Sertifikat</strong> atau langsung dari halaman pelajaran tersebut.</li>
                                    </ul>
                                </div>
                            </section>

                            <!-- 5. Profil -->
                            <section>
                                <h3 class="text-xl font-bold text-indigo-700 mb-4 border-b pb-2">5. Mengatur Profil</h3>
                                <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                                    <p class="mb-2">Kamu bisa mengubah data diri di menu <strong>Profil</strong>:</p>
                                    <ul class="list-disc list-inside space-y-1 text-gray-700">
                                        <li>Mengganti Foto Profil.</li>
                                        <li>Mengubah Nama dan Email.</li>
                                        <li>Mengganti Kata Sandi (Password).</li>
                                    </ul>
                                </div>
                            </section>

                        </div>

                        <div class="mt-12 p-4 bg-blue-50 border border-blue-200 rounded text-sm text-blue-800">
                            <strong>Tips Belajar:</strong> Jangan lupa untuk selalu mencatat poin-poin penting dari setiap video atau materi yang kamu pelajari. Selamat belajar!
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
