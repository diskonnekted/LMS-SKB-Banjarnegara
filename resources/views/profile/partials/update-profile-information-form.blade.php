<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Informasi Profil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Perbarui informasi profil dan alamat email akun Anda.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="avatar" :value="__('Foto Profil')" />
            <div class="mt-2 flex items-center">
                @if($user->avatar)
                    <img src="{{ Storage::url($user->avatar) }}" alt="Avatar" class="h-12 w-12 rounded-full object-cover mr-4">
                @else
                    <div class="h-12 w-12 rounded-full bg-gray-200 mr-4 flex items-center justify-center">
                                <span class="text-gray-500 text-xs">{{ __('Tidak Ada Gambar') }}</span>
                            </div>
                @endif
                <input id="avatar" name="avatar" type="file" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" />
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
        </div>

        <div>
            <x-input-label for="name" :value="__('Nama')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="bio" :value="__('Bio')" />
            <textarea id="bio" name="bio" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3">{{ old('bio', $user->bio) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('bio')" />
        </div>

        @hasrole('admin|teacher')
        <div>
            <x-input-label for="nip" :value="__('NIP')" />
            <x-text-input id="nip" name="nip" type="text" class="mt-1 block w-full" :value="old('nip', $user->nip)" autocomplete="off" />
            <x-input-error class="mt-2" :messages="$errors->get('nip')" />
        </div>

        <div>
            <x-input-label for="classes_taught" :value="__('Kelas yang Diampu')" />
            <x-text-input id="classes_taught" name="classes_taught" type="text" class="mt-1 block w-full" :value="old('classes_taught', $user->classes_taught)" autocomplete="off" />
            <x-input-error class="mt-2" :messages="$errors->get('classes_taught')" />
        </div>
        @endhasrole

        @role('student')
        <div>
            <x-input-label for="date_of_birth" :value="__('Tanggal Lahir')" />
            <x-text-input id="date_of_birth" name="date_of_birth" type="date" class="mt-1 block w-full" :value="old('date_of_birth', optional($user->date_of_birth)->format('Y-m-d'))" />
            <x-input-error class="mt-2" :messages="$errors->get('date_of_birth')" />
        </div>

        <div>
            <x-input-label for="place_of_birth" :value="__('Tempat Lahir')" />
            <x-text-input id="place_of_birth" name="place_of_birth" type="text" class="mt-1 block w-full" :value="old('place_of_birth', $user->place_of_birth)" autocomplete="off" />
            <x-input-error class="mt-2" :messages="$errors->get('place_of_birth')" />
        </div>

        <div>
            <x-input-label for="gender" :value="__('Jenis Kelamin')" />
            <select id="gender" name="gender" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <option value="">-</option>
                <option value="L" @selected(old('gender', $user->gender) === 'L')>Laki-laki</option>
                <option value="P" @selected(old('gender', $user->gender) === 'P')>Perempuan</option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('gender')" />
        </div>

        <div>
            <x-input-label for="grade_level" :value="__('Kelas')" />
            <x-text-input id="grade_level" name="grade_level" type="text" class="mt-1 block w-full" :value="old('grade_level', $user->grade_level)" autocomplete="off" />
            <x-input-error class="mt-2" :messages="$errors->get('grade_level')" />
        </div>

        <div>
            <x-input-label for="whatsapp_number" :value="__('No WhatsApp')" />
            <x-text-input id="whatsapp_number" name="whatsapp_number" type="text" class="mt-1 block w-full" :value="old('whatsapp_number', $user->whatsapp_number)" autocomplete="tel" />
            <x-input-error class="mt-2" :messages="$errors->get('whatsapp_number')" />
        </div>

        <div>
            <x-input-label for="school_name" :value="__('Sekolah')" />
            <x-text-input id="school_name" name="school_name" type="text" class="mt-1 block w-full" :value="old('school_name', $user->school_name)" autocomplete="off" />
            <x-input-error class="mt-2" :messages="$errors->get('school_name')" />
        </div>

        <div>
            <x-input-label for="nisn" :value="__('NISN')" />
            <x-text-input id="nisn" name="nisn" type="text" class="mt-1 block w-full" :value="old('nisn', $user->nisn)" autocomplete="off" />
            <x-input-error class="mt-2" :messages="$errors->get('nisn')" />
        </div>
        @endrole

        <div>
            <x-input-label for="nik" :value="__('NIK')" />
            <x-text-input id="nik" name="nik" type="text" class="mt-1 block w-full" :value="old('nik', $user->nik)" autocomplete="off" />
            <x-input-error class="mt-2" :messages="$errors->get('nik')" />
        </div>

        <div>
            <x-input-label for="address" :value="__('Alamat')" />
            <textarea id="address" name="address" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3">{{ old('address', $user->address) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('address')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Alamat email Anda belum diverifikasi.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('Tautan verifikasi baru telah dikirim ke alamat email Anda.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Simpan') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Tersimpan.') }}</p>
            @endif
        </div>
    </form>
</section>
