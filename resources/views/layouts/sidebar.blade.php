<div class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    <div class="hidden md:flex flex-col w-64 bg-white border-r border-gray-200">
        <div class="flex items-center justify-center h-16 border-b border-gray-200 px-4">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                <x-application-logo class="w-8 h-8 text-orange-600 fill-current" />
                <span class="font-bold text-xl text-gray-800">{{ config('app.name', 'SKB') }}</span>
            </a>
        </div>
        
        <div class="flex flex-col flex-1 overflow-y-auto">
            <nav class="flex-1 px-2 py-4 space-y-1">
                <!-- Common Menu -->
                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="flex items-center px-4 py-2 text-sm font-medium rounded-md group {{ request()->routeIs('dashboard') ? 'bg-orange-50 text-orange-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="mr-3 h-5 w-5 {{ request()->routeIs('dashboard') ? 'text-orange-500' : 'text-gray-400 group-hover:text-gray-500' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dasbor
                </x-nav-link>

                @role('admin|teacher')
                <div class="pt-4 pb-2 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Manajemen Pembelajaran
                </div>
                
                <x-nav-link :href="route('courses.index')" :active="request()->routeIs('courses.*')" class="flex items-center px-4 py-2 text-sm font-medium rounded-md group {{ request()->routeIs('courses.*') ? 'bg-orange-50 text-orange-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="mr-3 h-5 w-5 {{ request()->routeIs('courses.*') ? 'text-orange-500' : 'text-gray-400 group-hover:text-gray-500' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    Pelajaran
                </x-nav-link>
                @endrole

                @role('student')
                <div class="pt-4 pb-2 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Pembelajaran Saya
                </div>
                <!-- Student Menu Items -->
                <a href="#" class="flex items-center px-4 py-2 text-sm font-medium text-gray-600 rounded-md hover:bg-gray-50 hover:text-gray-900 group">
                    <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    Pelajaran Saya
                </a>
                @endrole

                @role('admin')
                <div class="pt-4 pb-2 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Sistem
                </div>
                
                <x-nav-link :href="route('news.index')" :active="request()->routeIs('news.*')" class="flex items-center px-4 py-2 text-sm font-medium rounded-md group {{ request()->routeIs('news.*') ? 'bg-orange-50 text-orange-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="mr-3 h-5 w-5 {{ request()->routeIs('news.*') ? 'text-orange-500' : 'text-gray-400 group-hover:text-gray-500' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                    Berita & Pengumuman
                </x-nav-link>

                <x-nav-link :href="route('settings.index')" :active="request()->routeIs('settings.*')" class="flex items-center px-4 py-2 text-sm font-medium rounded-md group {{ request()->routeIs('settings.*') ? 'bg-orange-50 text-orange-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="mr-3 h-5 w-5 {{ request()->routeIs('settings.*') ? 'text-orange-500' : 'text-gray-400 group-hover:text-gray-500' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Pengaturan
                </x-nav-link>
                @endrole
            </nav>
        </div>
        
        <!-- User Profile (Bottom Sidebar) -->
        <div class="border-t border-gray-200 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="h-8 w-8 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 font-bold">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-700 group-hover:text-gray-900">
                        {{ Auth::user()->name }}
                    </p>
                    <p class="text-xs font-medium text-gray-500 group-hover:text-gray-700">
                        {{ Auth::user()->roles->pluck('name')->implode(', ') }}
                    </p>
                </div>
            </div>
            <div class="mt-4 flex justify-between items-center">
                 <a href="{{ route('profile.edit') }}" class="text-xs text-gray-500 hover:text-orange-600 transition">Profile</a>
                 <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-xs text-gray-500 hover:text-red-600 transition">Logout</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Mobile Header & Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Mobile Header -->
        <div class="md:hidden flex items-center justify-between bg-white border-b border-gray-200 px-4 py-2">
            <div class="flex items-center">
                <button @click="sidebarOpen = true" class="text-gray-500 hover:text-gray-600 focus:outline-none">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <a href="{{ route('dashboard') }}" class="ml-4 flex items-center gap-2">
                    <x-application-logo class="w-8 h-8 text-orange-600 fill-current" />
                    <span class="font-bold text-lg text-gray-800">{{ config('app.name', 'SKB') }}</span>
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
             {{ $slot }}
        </main>
    </div>
</div>
