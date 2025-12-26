@props(['name', 'variant' => 'outline', 'class' => 'h-6 w-6'])
@php
    $isSolid = $variant === 'solid';
@endphp
@switch($name)
    @case('home')
        @if($isSolid)
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" {{ $attributes->merge(['class' => $class]) }} fill="currentColor"><path d="M11.47 3.84a.75.75 0 0 1 1.06 0l8.25 8.25a.75.75 0 0 1-1.06 1.06L19.5 11.53V19.5A2.25 2.25 0 0 1 17.25 21.75h-3a.75.75 0 0 1-.75-.75v-4.5a1.5 1.5 0 0 0-1.5-1.5h-3a1.5 1.5 0 0 0-1.5 1.5v4.5a.75.75 0 0 1-.75.75h-3A2.25 2.25 0 0 1 2.25 19.5V11.53L3.22 12.65a.75.75 0 1 1-1.06-1.06l8.25-8.25z"/></svg>
        @else
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" {{ $attributes->merge(['class' => $class]) }} fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m3 10 9-7 9 7v8a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2V12H9v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
        @endif
        @break
    @case('book-open')
        @if($isSolid)
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" {{ $attributes->merge(['class' => $class]) }} fill="currentColor"><path d="M12 5.25c-1.592-1.06-3.61-1.5-5.25-1.5A4.5 4.5 0 0 0 2.25 8.25v8.25a.75.75 0 0 0 .75.75c2.065 0 4.172.5 5.7 1.425.3.18.3.6 0 .78A12.152 12.152 0 0 1 3 18.75V8.25a3 3 0 0 1 3-3c1.602 0 3.232.347 4.5 1.05V19.5a.75.75 0 0 0 1.5 0V6.3c1.268-.703 2.898-1.05 4.5-1.05a3 3 0 0 1 3 3v10.5a12.152 12.152 0 0 1-5.7 1.425.45.45 0 0 1-.45-.45V5.25z"/></svg>
        @else
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" {{ $attributes->merge(['class' => $class]) }} fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.75c-1.8-1.2-4.2-1.5-6-1.5A3.75 3.75 0 0 0 2.25 9v9.75s3.75-1.5 7.5 0V9m2.25-2.25c1.8-1.2 4.2-1.5 6-1.5A3.75 3.75 0 0 1 21.75 9v9.75s-3.75-1.5-7.5 0V9"/></svg>
        @endif
        @break
    @case('newspaper')
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" {{ $attributes->merge(['class' => $class]) }} fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.5 6.75h10.5M4.5 10.5h10.5M4.5 14.25H12M18 6.75v10.5A2.25 2.25 0 0 1 15.75 19.5H6A3.75 3.75 0 0 1 2.25 15.75V6A3.75 3.75 0 0 1 6 2.25h9.75A2.25 2.25 0 0 1 18 4.5v2.25z"/></svg>
        @break
    @case('user')
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" {{ $attributes->merge(['class' => $class]) }} fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 7.5a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0zM4.5 19.5a8.25 8.25 0 1 1 16.5 0v.75H4.5v-.75z"/></svg>
        @break
    @case('user-group')
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" {{ $attributes->merge(['class' => $class]) }} fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 7.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0zM9 9a3 3 0 1 1-6 0 3 3 0 0 1 6 0zM2.25 19.5a6.75 6.75 0 0 1 10.5-5.58M21.75 19.5a6.75 6.75 0 0 0-6.75-6.75"/></svg>
        @break
    @case('cog')
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" {{ $attributes->merge(['class' => $class]) }} fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 3.75h4.5l.75 2.25 2.25.75 1.5-1.5 3 3-1.5 1.5.75 2.25 2.25.75v4.5l-2.25.75-.75 2.25 1.5 1.5-3 3-1.5-1.5-2.25.75-.75 2.25h-4.5l-.75-2.25-2.25-.75-1.5 1.5-3-3 1.5-1.5-.75-2.25-2.25-.75v-4.5l2.25-.75.75-2.25-1.5-1.5 3-3 1.5 1.5 2.25-.75.75-2.25zM12 9.75a2.25 2.25 0 1 0 0 4.5 2.25 2.25 0 0 0 0-4.5z"/></svg>
        @break
    @case('academic-cap')
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" {{ $attributes->merge(['class' => $class]) }} fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m12 3 9 4.5-9 4.5-9-4.5L12 3zM6 10.5V15a6 6 0 0 0 12 0v-4.5"/></svg>
        @break
    @case('rectangle-stack')
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" {{ $attributes->merge(['class' => $class]) }} fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 5.25h16.5v9h-16.5v-9zM6 17.25h12"/></svg>
        @break
@endswitch
