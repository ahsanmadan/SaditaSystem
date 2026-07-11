@props([
    'orientation' => 'horizontal',
])

@php
    $classes = $orientation === 'vertical'
        ? 'h-full w-px shrink-0 bg-slate-200'
        : 'h-px w-full bg-slate-200';
@endphp

<div
    {{ $attributes->merge(['class' => $classes]) }}
    role="separator"
    aria-orientation="{{ $orientation === 'vertical' ? 'vertical' : 'horizontal' }}"></div>
