@props([
    'type' => 'line',
    'config' => [],
    'series' => [],
    'options' => [],
    'colors' => [],
    'height' => 250,
    'label' => 'Chart',
])

@php
    $payload = [
        'chart' => ['type' => $type, 'height' => (int) $height],
        'config' => $config,
        'series' => $series,
        'colors' => $colors,
        ...$options,
    ];
@endphp

<div
    data-slot="chart"
    role="img"
    aria-label="{{ $label }}"
    x-data="shadcnChart({{ \Illuminate\Support\Js::from($payload) }})"
    {{ $attributes->merge(['class' => 'w-full']) }}
>
    <div x-ref="canvas" class="w-full" style="min-height: {{ (int) $height }}px"></div>
</div>
