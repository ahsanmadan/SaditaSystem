@props([
    'variant' => 'default',
])

@php
    $base = 'inline-flex items-center rounded-full border px-2 py-0.5 text-[10px] font-medium tracking-wide';
    $variants = [
        'default' => 'border-slate-200 bg-slate-100 text-slate-700',
        'outline' => 'border-slate-200 bg-white text-slate-700',
        'destructive' => 'border-rose-200 bg-rose-50 text-rose-700',
    ];
@endphp

<span {{ $attributes->merge(['class' => $base . ' ' . ($variants[$variant] ?? $variants['default'])]) }}>
    {{ $slot }}
</span>
