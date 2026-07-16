@props([
    'label',
    'sortKey' => null,
])

@php
    $currentSort = request('sort', 'created_at');
    $currentDirection = request('direction', 'desc');
    $isActive = $sortKey && $currentSort === $sortKey;
    $nextDirection = $isActive && $currentDirection === 'asc' ? 'desc' : 'asc';
@endphp

<th {{ $attributes->merge(['class' => 'px-6 py-4 text-left']) }}>
    @if ($sortKey)
        <a href="{{ request()->fullUrlWithQuery(['sort' => $sortKey, 'direction' => $nextDirection, 'page' => null]) }}"
            class="inline-flex items-center gap-1.5 transition hover:text-[#56353a] {{ $isActive ? 'text-[#56353a]' : '' }}"
            aria-label="Urutkan {{ $label }}">
            <span>{{ $label }}</span>
            <svg class="h-3 w-3 {{ $isActive ? 'text-[#7A1F2B]' : 'text-[#b19d95]' }}" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                <path d="m5.5 6 2.5-2.5L10.5 6M10.5 10 8 12.5 5.5 10" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </a>
    @else
        {{ $label }}
    @endif
</th>
