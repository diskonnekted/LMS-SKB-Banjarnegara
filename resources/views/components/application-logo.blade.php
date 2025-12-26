@props(['theme' => 'light'])

@php
    $src = asset('images/black.png');
    if ($theme === 'dark') {
        $src = asset('images/white.png');
    }
@endphp
<img src="{{ $src }}" alt="{{ config('app.name') }}" {{ $attributes }} />
