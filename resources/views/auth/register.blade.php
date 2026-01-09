<x-guest-layout maxWidth="max-w-6xl" logoTheme="light">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
        
        <!-- Left Side: Registration Manual -->
        <div class="prose max-w-none text-gray-700 border-r border-gray-100 pr-0 md:pr-12">
            <h3 class="text-xl font-bold text-indigo-700 mb-4">Panduan Pendaftaran</h3>
            
            <div class="space-y-4 text-sm">
                <p>Selamat datang di sistem LMS SKB Banjarnegara. Silakan ikuti langkah berikut untuk mendaftar:</p>

                <div>
                    <strong class="text-gray-900 block mb-1">1. Isi Data Diri</strong>
                    <p>Masukkan <strong>Nama Lengkap</strong> sesuai ijazah atau identitas resmi, serta <strong>Email</strong> yang aktif.</p>
                </div>

                <div>
                    <strong class="text-gray-900 block mb-1">2. Buat Kata Sandi</strong>
                    <p>Gunakan kata sandi yang mudah diingat namun aman (minimal 8 karakter).</p>
                </div>

                <div>
                    <strong class="text-gray-900 block mb-1">3. Pilih Peran (Role)</strong>
                    <ul class="list-disc list-inside ml-2">
                        <li><strong>Siswa:</strong> Pilih ini jika Anda adalah peserta didik yang ingin mengikuti pembelajaran.</li>
                        <li><strong>Guru:</strong> Pilih ini jika Anda adalah pengajar atau tutor.</li>
                    </ul>
                </div>

                <div>
                    <strong class="text-gray-900 block mb-1">4. Pilih Pelajaran (Opsional)</strong>
                    <p>
                        Jika Anda siswa, pilih pelajaran yang ingin diikuti (bisa lebih dari satu).<br>
                        Jika Anda guru, pilih pelajaran yang Anda ampu.
                    </p>
                </div>

                <div class="bg-blue-50 p-3 rounded text-blue-800 text-xs mt-4">
                    <strong>Catatan:</strong> Jika Anda sudah memiliki akun, silakan klik tombol "Sudah terdaftar?" di bawah untuk Login.
                </div>
            </div>
        </div>

        <!-- Right Side: Registration Form -->
        <div>
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div>
                    <x-input-label style="color: black !important;" for="name" :value="__('Nama')" />
                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email Address -->
                <div class="mt-4">
                    <x-input-label style="color: black !important;" for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label style="color: black !important;" for="password" :value="__('Kata Sandi')" />

                    <x-text-input id="password" class="block mt-1 w-full"
                                    type="password"
                                    name="password"
                                    required autocomplete="new-password" />

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <x-input-label style="color: black !important;" for="password_confirmation" :value="__('Konfirmasi Kata Sandi')" />

                    <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                    type="password"
                                    name="password_confirmation" required autocomplete="new-password" />

                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <!-- Role -->
                <div class="mt-4">
                    <x-input-label style="color: black !important;" for="role" :value="__('Daftar sebagai')" />
                    <select id="role" name="role" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500" required>
                        <option value="">-- Pilih Peran --</option>
                        <option value="student" @selected(old('role')=='student')>Siswa</option>
                        <option value="teacher" @selected(old('role')=='teacher')>Guru</option>
                    </select>
                    <x-input-error :messages="$errors->get('role')" class="mt-2" />
                </div>

                @if(isset($courses) && $courses->count() > 0)
                <!-- Student: Enroll Courses -->
                <div class="mt-4">
                    <x-input-label style="color: black !important;" for="enroll_courses" :value="__('Pilih Pelajaran untuk Diikuti (Siswa)')" />
                    <select id="enroll_courses" name="enroll_courses[]" multiple class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}">
                                {{ $course->title }} {{ $course->grade_level ? '— '.$course->grade_level : '' }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Opsional. Abaikan jika daftar sebagai guru.</p>
                    <x-input-error :messages="$errors->get('enroll_courses')" class="mt-2" />
                </div>

                <!-- Teacher: Teach Courses -->
                <div class="mt-4">
                    <x-input-label style="color: black !important;" for="teach_courses" :value="__('Pilih Pelajaran yang Diampu (Guru)')" />
                    <select id="teach_courses" name="teach_courses[]" multiple class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}">
                                {{ $course->title }} {{ $course->grade_level ? '— '.$course->grade_level : '' }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Opsional. Abaikan jika daftar sebagai siswa.</p>
                    <x-input-error :messages="$errors->get('teach_courses')" class="mt-2" />
                </div>
                @endif

                <div class="flex items-center justify-end mt-4">
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500" href="{{ route('login') }}">
                        {{ __('Sudah terdaftar?') }}
                    </a>

                    <x-primary-button class="ms-4">
                        {{ __('Daftar') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
