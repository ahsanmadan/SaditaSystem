@props([
    'label',
    'value',
    'hint' => null,
    'period' => null,
])

@php
    $normalizedLabel = str($label)->lower()->toString();
    $period ??= match (true) {
        str_contains($normalizedLabel, 'hari ini') => 'Hari ini',
        str_contains($normalizedLabel, 'bulan') => 'Bulan ini',
        str_contains($normalizedLabel, 'rating') || str_contains($normalizedLabel, 'ulasan') => 'Ulasan',
        str_contains($normalizedLabel, 'produk') || str_contains($normalizedLabel, 'kategori') => 'Katalog',
        str_contains($normalizedLabel, 'pelanggan') => 'Pelanggan',
        str_contains($normalizedLabel, 'pembayaran') || str_contains($normalizedLabel, 'pesanan') => 'Transaksi',
        default => 'Ringkasan',
    };
@endphp

<x-ui.card {{ $attributes->merge(['class' => 'admin-insight-card rounded-2xl border-[#e6ddd5] bg-white p-5 shadow-none transition-colors hover:border-[#d9c9bd]']) }}>
    <div class="flex min-h-[10.75rem] h-full flex-col justify-between gap-5">
        <div>
            <div class="flex items-start justify-between gap-3">
                <p class="min-w-0 text-sm font-medium leading-5 text-[#725e58]">{{ $label }}</p>
                <span class="shrink-0 rounded-full border border-[#eadfd7] bg-[#fcfaf8] px-2 py-1 text-[10px] font-medium text-[#7b655e]">
                    {{ $period }}
                </span>
            </div>
            <p class="mt-3 text-[2rem] font-semibold leading-none tracking-[-0.04em] text-[#2c1d1d] tabular-nums">
                {{ $value }}
            </p>
        </div>

        @if ($hint)
            <p class="text-sm font-medium leading-5 text-[#3f302d]">{{ $hint }}</p>
        @endif
    </div>
</x-ui.card>
