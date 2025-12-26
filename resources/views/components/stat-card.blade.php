@props([
    'title',
    'value',
    'icon' => 'rectangle-stack',
    'scheme' => 'tertiary-secondary',
])
@php
    $schemes = [
        'tertiary-secondary' => 'from-tertiary to-secondary',
        'secondary-success' => 'from-secondary to-success',
        'primary-accent' => 'from-primary to-accent',
        'info-tertiary' => 'from-info to-tertiary',
        'success-secondary' => 'from-success to-secondary',
        'accent-primary' => 'from-accent to-primary',
    ];
    $gradient = $schemes[$scheme] ?? $schemes['tertiary-secondary'];
@endphp
<div class="relative overflow-hidden rounded-xl p-6 text-text-light shadow bg-gradient-to-br {{ $gradient }}">
    <div class="absolute inset-0 bg-black/15"></div>
    <div class="relative flex items-center justify-between">
        <div>
            <div class="text-xs uppercase opacity-90">{{ $title }}</div>
            <div class="text-4xl font-extrabold mt-2 drop-shadow-md">{{ $value }}</div>
        </div>
        <div class="h-16 w-16 rounded-xl bg-white/15 flex items-center justify-center text-text-light">
            <x-heroicon :name="$icon" class="h-8 w-8" />
        </div>
    </div>
</div>
