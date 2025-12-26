<nav x-data="{ open: false }" style="background-color: #6C5CE7; border-bottom: 1px solid #5a4ad1;" class="border-b border-indigo-500">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo theme="dark" class="block h-9 w-auto" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link theme="dark" :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        @hasrole('student')
                            Pelajaran
                        @else
                            Dasbor
                        @endhasrole
                    </x-nav-link>

                    <x-nav-link theme="dark" :href="route('courses.index')" :active="request()->routeIs('courses.*')">
                        Pelajaran
                    </x-nav-link>

                    @role('student')
                    <x-nav-link theme="dark" :href="route('profiles.public', Auth::user())" :active="request()->routeIs('profiles.public')">
                        Sertifikat
                    </x-nav-link>
                    @endrole

                    <x-nav-link theme="dark" :href="route('profile.show')" :active="request()->routeIs('profile.show')">
                        Profil
                    </x-nav-link>

                    @role('admin')
                    <x-nav-link theme="dark" :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                        Manajemen Pengguna
                    </x-nav-link>
                    <x-nav-link theme="dark" :href="route('categories.index')" :active="request()->routeIs('categories.*')">
                        Kategori
                    </x-nav-link>

                    <x-nav-link theme="dark" :href="route('settings.index')" :active="request()->routeIs('settings.*')">
                        Pengaturan
                    </x-nav-link>
                    @endrole
                    
                    @hasrole('admin|teacher')
                    <x-nav-link theme="dark" :href="route('news.index')" :active="request()->routeIs('news.*')">
                        Berita
                    </x-nav-link>
                    @endhasrole
                </div>
            </div>

            <!-- Settings / Auth -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-indigo-100 hover:text-white focus:outline-none transition ease-in-out duration-150" style="background-color: #6C5CE7;">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.show')">
                                Profil
                            </x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                    Keluar
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="flex items-center gap-3">
                        <a href="{{ route('login') }}" class="text-sm font-medium text-indigo-100 hover:text-white">Masuk</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-3 py-1.5 rounded-md bg-white text-indigo-700 text-sm font-semibold hover:bg-indigo-50">Daftar</a>
                        @endif
                    </div>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-indigo-200 hover:text-white hover:bg-indigo-600 focus:outline-none focus:bg-indigo-600 focus:text-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link theme="dark" :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                @hasrole('student')
                    Pelajaran
                @else
                    Dasbor
                @endhasrole
            </x-responsive-nav-link>
            
            <x-responsive-nav-link theme="dark" :href="route('courses.index')" :active="request()->routeIs('courses.*')">
                Pelajaran
            </x-responsive-nav-link>

            @role('student')
            <x-responsive-nav-link theme="dark" :href="route('profiles.public', Auth::user())" :active="request()->routeIs('profiles.public')">
                Sertifikat
            </x-responsive-nav-link>
            @endrole

            @auth
                <x-responsive-nav-link theme="dark" :href="route('profile.show')" :active="request()->routeIs('profile.show')">
                    Profil
                </x-responsive-nav-link>
            @endauth

            @role('admin')
            <x-responsive-nav-link theme="dark" :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                Manajemen Pengguna
            </x-responsive-nav-link>
            <x-responsive-nav-link theme="dark" :href="route('settings.index')" :active="request()->routeIs('settings.*')">
                Pengaturan
            </x-responsive-nav-link>
            @endrole
            
            @hasrole('admin|teacher')
            <x-responsive-nav-link theme="dark" :href="route('news.index')" :active="request()->routeIs('news.*')">
                Berita
            </x-responsive-nav-link>
            @endhasrole
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-indigo-500">
            @auth
                <div class="px-4">
                    <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-indigo-200">{{ Auth::user()->email }}</div>
                </div>
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link theme="dark" :href="route('profile.show')">
                        Profil
                    </x-responsive-nav-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link theme="dark" :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                            Keluar
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="px-4">
                    <div class="font-medium text-base text-white">Tamu</div>
                    <div class="font-medium text-sm text-indigo-200">Silakan masuk untuk akses lengkap</div>
                </div>
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link theme="dark" :href="route('login')">
                        Masuk
                    </x-responsive-nav-link>
                    @if (Route::has('register'))
                        <x-responsive-nav-link theme="dark" :href="route('register')">
                            Daftar
                        </x-responsive-nav-link>
                    @endif
                </div>
            @endauth
        </div>
    </div>
</nav>
