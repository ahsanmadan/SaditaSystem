@props([
    'variant' => 'default',
    'size' => 'default',
    'type' => 'button',
])

@php
    $base = 'inline-flex items-center justify-center whitespace-nowrap rounded-xl text-sm font-medium transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-300 disabled:pointer-events-none disabled:opacity-50';
    $variants = [
        'default' => 'bg-slate-900 text-white shadow-sm hover:bg-slate-800',
        'outline' => 'border border-slate-200 bg-white text-slate-700 hover:bg-slate-100',
        'secondary' => 'bg-slate-100 text-slate-700 hover:bg-slate-200',
        'ghost' => 'text-slate-700 hover:bg-slate-100',
        'destructive' => 'bg-rose-600 text-white shadow-sm hover:bg-rose-700',
    ];
    $sizes = [
        'sm' => 'h-9 px-3.5',
        'default' => 'h-10 px-4',
        'lg' => 'h-11 px-5',
        'icon' => 'h-10 w-10',
    ];
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $base . ' ' . ($variants[$variant] ?? $variants['default']) . ' ' . ($sizes[$size] ?? $sizes['default'])]) }}>
    {{ $slot }}
</button>
