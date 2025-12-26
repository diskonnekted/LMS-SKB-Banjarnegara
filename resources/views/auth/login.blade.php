<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">{{ __('Masuk ke Akun Anda') }}</h1>
            <p class="text-gray-500 text-sm mt-1">{{ __('Silakan masukkan email dan kata sandi Anda') }}</p>
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="block text-sm font-medium mb-1 text-gray-700" />
            <x-text-input id="email" class="w-full px-4 py-3 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="contoh@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex justify-between items-center mb-1">
                <x-input-label for="password" :value="__('Kata Sandi')" class="block text-sm font-medium text-gray-700" />
                @if (Route::has('password.request'))
                    <a class="text-sm text-orange-600 hover:text-orange-700 font-medium transition" href="{{ route('password.request') }}">
                        {{ __('Lupa kata sandi?') }}
                    </a>
                @endif
            </div>

            <x-text-input id="password" class="w-full px-4 py-3 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
                            type="password"
                            name="password"
                            required autocomplete="current-password"
                            placeholder="Masukkan kata sandi" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-orange-600 shadow-sm focus:ring-orange-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Ingat saya') }}</span>
            </label>
        </div>

        <div>
            <x-primary-button class="w-full bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 px-4 rounded-full transition shadow-lg shadow-orange-500/30 justify-center">
                {{ __('Masuk') }}
            </x-primary-button>
        </div>

        <!-- Divider -->
        <div class="relative flex items-center my-4">
            <div class="flex-grow border-t border-gray-300"></div>
            <span class="mx-4 text-sm text-gray-400">atau</span>
            <div class="flex-grow border-t border-gray-300"></div>
        </div>

        <!-- Sign Up Link -->
        <p class="text-center text-sm text-gray-600">
            {{ __('Belum punya akun?') }}
            <a href="{{ route('register') }}" class="text-orange-600 font-bold hover:underline">{{ __('Daftar sekarang') }}</a>
        </p>
        
        <div class="mt-4">
            <a href="{{ route('home') }}" class="block w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3 rounded-full transition">
                Kembali ke Beranda
            </a>
        </div>
    </form>
</x-guest-layout>
