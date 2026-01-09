@php
    $items = [];
    if (!auth()->check()) {
        $items = [
            ['label' => 'Pelajaran', 'href' => route('courses.catalog'), 'active' => request()->routeIs('courses.catalog')],
            ['label' => 'Daftar', 'href' => route('register'), 'active' => request()->routeIs('register')],
            ['label' => 'Akun', 'href' => route('login'), 'active' => request()->routeIs('login')],
        ];
    } elseif (auth()->user()->hasRole('student')) {
        $items = [
            ['label' => 'Beranda', 'href' => route('dashboard'), 'active' => request()->routeIs('dashboard')],
            ['label' => 'Pelajaran', 'href' => route('courses.index'), 'active' => request()->routeIs('courses.*')],
            ['label' => 'Akun', 'href' => route('profile.edit'), 'active' => request()->routeIs('profile.edit')],
        ];
    } elseif (auth()->user()->hasRole('teacher')) {
        $items = [
            ['label' => 'Dasbor', 'href' => route('dashboard'), 'active' => request()->routeIs('dashboard')],
            ['label' => 'Pelajaran', 'href' => route('courses.index'), 'active' => request()->routeIs('courses.*')],
            ['label' => 'Akun', 'href' => route('profile.edit'), 'active' => request()->routeIs('profile.edit')],
        ];
    } else {
        $items = [
            ['label' => 'Dasbor', 'href' => route('dashboard'), 'active' => request()->routeIs('dashboard')],
            ['label' => 'Pelajaran', 'href' => route('courses.index'), 'active' => request()->routeIs('courses.*')],
            ['label' => 'Pengguna', 'href' => route('admin.users.index'), 'active' => request()->routeIs('admin.users.*')],
            ['label' => 'Pengaturan', 'href' => route('settings.index'), 'active' => request()->routeIs('settings.*')],
        ];
    }
@endphp
<nav class="fixed bottom-0 left-0 right-0 bg-white border-t shadow-sm z-50 md:hidden mobile-safe">
    <div class="flex items-center justify-around text-center">
        @foreach($items as $item)
            <a href="{{ $item['href'] }}" class="py-2.5 text-xs flex flex-col items-center flex-1 {{ $item['active'] ? 'text-tertiary font-semibold' : 'text-gray-600' }}" aria-current="{{ $item['active'] ? 'page' : 'false' }}">
                @php
                    $label = $item['label'];
                @endphp
                <span class="h-6 w-6 mb-1">
                    @if($label === 'Beranda' || $label === 'Dasbor')
                        <x-heroicon name="home" class="h-6 w-6" />
                    @elseif($label === 'Pelajaran')
                        <x-heroicon name="book-open" class="h-6 w-6" />
                    @elseif($label === 'Daftar')
                        <x-heroicon name="clipboard-list" class="h-6 w-6" />
                    @elseif($label === 'Profil' || $label === 'Masuk' || $label === 'Akun')
                        <x-heroicon name="user" class="h-6 w-6" />
                    @elseif($label === 'Pengguna')
                        <x-heroicon name="user-group" class="h-6 w-6" />
                    @elseif($label === 'Pengaturan')
                        <x-heroicon name="cog" class="h-6 w-6" />
                    @endif
                </span>
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </div>
</nav>
