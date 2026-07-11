@props([
    'align' => 'default',
])

@php
    $alignment = [
        'default' => 'shrink-0',
        'inline-end' => 'shrink-0 ml-auto',
    ];
@endphp

<div {{ $attributes->merge(['class' => 'flex items-center text-[#9f93a2] ' . ($alignment[$align] ?? $alignment['default'])]) }}>
    {{ $slot }}
</div>
