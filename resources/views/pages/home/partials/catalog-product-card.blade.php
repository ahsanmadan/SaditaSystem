@php
    $cardCategoryName = strtolower(trim($produk->kategori?->nama ?? $kategori->nama ?? ''));
    $cardIsDecor = $cardCategoryName === 'dekorasi';
    $cardServiceTypeLabel = match ($cardCategoryName) {
        'dekorasi' => 'Jasa',
        'papan bunga', 'papan ucapan' => 'Sewa',
        default => $produk->is_sewa ? 'Sewa' : 'Jasa',
    };
    $cardImgUrl = $produk->fotoUtamaUrl();
    $cardPriceStr = 'Rp ' . number_format($produk->harga_dasar, 0, ',', '.');
    $cardDescStr = $produk->deskripsi ?? 'Detail produk ' . $produk->nama;
    $cardCategoryLabel = $produk->kategori?->nama ?? $kategori->nama ?? 'Produk';
@endphp

<article data-product-card data-modal-title="{{ e($produk->nama) }}" data-modal-price="{{ e($cardPriceStr) }}"
    data-modal-image="{{ e($cardImgUrl) }}" data-modal-desc="{{ e($cardDescStr) }}"
    data-modal-tag="{{ e($cardCategoryLabel) }}" data-modal-is-decor="{{ $cardIsDecor ? '1' : '0' }}"
    class="group product-card flex h-full cursor-pointer flex-col overflow-hidden rounded-[1.15rem] sm:rounded-[1.65rem] border border-[#E8DCCE] bg-white transition-all duration-300 hover:shadow-[0_10px_24px_rgba(122,31,43,0.08)]">
    <div class="relative aspect-[4/3.45] sm:aspect-[4/3.25] overflow-hidden bg-[#F7F1EB]">
        <img src="{{ $cardImgUrl }}" alt="{{ $produk->nama }}"
            class="h-full w-full object-cover object-center transition-transform duration-700 group-hover:scale-[1.03]"
            loading="lazy">
        <div
            class="absolute inset-0 bg-gradient-to-t from-black/18 via-black/0 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100">
        </div>
        <div class="absolute left-3 top-3 flex flex-wrap gap-2">
            <span
                class="rounded-full bg-[#7A1F2B]/95 px-2.5 py-0.5 text-[9px] sm:px-3 sm:py-1 sm:text-[10px] font-semibold tracking-[0.12em] text-[#F2D48B] shadow-sm">
                {{ $cardServiceTypeLabel }}
            </span>
        </div>
    </div>

    <div class="flex flex-1 flex-col p-3 sm:p-3.5">
        <h3 class="text-[0.95rem] sm:text-[1.08rem] font-semibold leading-snug text-[#2D1E1E] line-clamp-2">
            {{ $produk->nama }}
        </h3>

        <div class="mt-3 border-t border-[#EFE5D9] pt-2.5 sm:mt-3.5 sm:pt-3">
            <div class="text-[9px] sm:text-[10px] font-semibold uppercase tracking-[0.16em] text-[#9D8B83]">
                Mulai dari
            </div>
            <div class="mt-1 text-[1.05rem] sm:mt-1.5 sm:text-[1.55rem] font-bold text-[#7A1F2B] leading-none">{{ $cardPriceStr }}</div>
        </div>

        <div class="mt-3 sm:mt-3.5">
            @if ($cardIsDecor)
                @php
                    $waText = "Halo Sadita,\n\nSaya ingin konsultasi dekorasi.\n\nJenis Dekorasi: {$produk->nama}\n\nTanggal Acara:\nWaktu Acara:\n\nLokasi Acara:\n\nKonsep / Tema yang diinginkan:\n(Contoh: elegan, rustic, minimalis, dll)\n\nCatatan tambahan:\n(opsional)\n\nTerima kasih.";
                @endphp
                <a href="https://wa.me/62812616155335?text={{ rawurlencode($waText) }}" target="_blank"
                    rel="noopener noreferrer" data-stop-modal
                    data-external-url="https://wa.me/62812616155335?text={{ rawurlencode($waText) }}"
                    class="group inline-flex min-h-9 sm:min-h-11 w-full items-center justify-center gap-1.5 sm:gap-2 rounded-full bg-[#7A1F2B] px-3.5 sm:px-5 text-[11px] sm:text-sm font-semibold text-white transition-all duration-200 hover:-translate-y-0.5 hover:bg-[#8A2432] hover:shadow-[0_8px_18px_rgba(122,31,43,0.16)]">
                    <svg class="h-3.5 w-3.5 sm:h-4 sm:w-4 transition-transform duration-300 group-hover:scale-105" fill="currentColor"
                        viewBox="0 0 24 24" aria-hidden="true">
                        <path
                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z" />
                    </svg>
                    Konsultasi
                </a>
            @else
                <a href="{{ route('order') }}?product={{ rawurlencode($produk->nama) }}&price={{ rawurlencode($cardPriceStr) }}&img={{ rawurlencode($cardImgUrl) }}&jenis={{ rawurlencode($cardCategoryLabel) }}"
                    data-stop-modal
                    data-order-url="{{ route('order') }}?product={{ rawurlencode($produk->nama) }}&price={{ rawurlencode($cardPriceStr) }}&img={{ rawurlencode($cardImgUrl) }}&jenis={{ rawurlencode($cardCategoryLabel) }}"
                    class="group inline-flex min-h-9 sm:min-h-11 w-full items-center justify-center gap-1.5 sm:gap-2 rounded-full bg-[#7A1F2B] px-3.5 sm:px-5 text-[11px] sm:text-sm font-semibold text-white transition-all duration-200 hover:-translate-y-0.5 hover:bg-[#8A2432] hover:shadow-[0_8px_18px_rgba(122,31,43,0.16)]">
                    <span>Pesan</span>
                    <svg class="h-3.5 w-3.5 sm:h-4 sm:w-4 transition-transform duration-300 group-hover:translate-x-0.5" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            @endif
        </div>
    </div>
</article>
