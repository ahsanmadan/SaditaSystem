@extends('layouts.app')

@section('content')
    <section class="border-b border-[#E8DCCE] bg-[#FFFDFB] pt-22 pb-6 sm:pt-32 sm:pb-12">
        <div data-catalog-root data-selected-category="{{ $selectedCategory }}"
            class="mx-auto flex max-w-7xl flex-col gap-5 px-4 sm:px-6 lg:px-8">
            <div class="grid gap-3 sm:gap-6 lg:grid-cols-[minmax(0,1.2fr)_minmax(0,0.8fr)] lg:items-end">
                <div class="max-w-3xl">
                    <div class="hidden text-[11px] font-semibold uppercase tracking-[0.22em] text-[#7A1F2B] sm:block">Lihat Semua</div>
                    <h1 class="mt-1 sm:mt-3 text-[2rem] font-semibold leading-[1.02] text-[#2D1E1E] sm:text-5xl"
                        style="font-family:'Playfair Display',serif; text-wrap:balance;">
                        Semua koleksi Sadita.
                    </h1>
                </div>
                <p class="hidden max-w-md text-sm leading-relaxed text-[#6B5C57] sm:block sm:text-base">
                    Pilih kategori, lalu lihat produk yang paling sesuai.
                </p>
            </div>

            <div class="flex flex-wrap justify-center gap-2.5 border-t border-[#E8DCCE] pt-4 sm:gap-3 sm:pt-6">
                <button type="button" data-catalog-filter data-category-key="all"
                    aria-pressed="{{ $selectedCategory === 'all' ? 'true' : 'false' }}"
                    class="{{ $selectedCategory === 'all' ? 'border-[#7A1F2B] bg-[#7A1F2B] text-white shadow-[0_12px_26px_rgba(122,31,43,0.18)]' : 'border-[#DCCDBD] bg-white text-[#6F5D58] hover:border-[#CBB39A] hover:text-[#7A1F2B]' }} inline-flex min-h-10 items-center rounded-full border px-4 text-[13px] font-semibold transition-all duration-200 sm:min-h-11 sm:px-5 sm:text-sm">
                    Semua
                </button>
                @foreach ($kategoris as $kategori)
                    <button type="button" data-catalog-filter data-category-key="{{ $kategori->catalog_key }}"
                        aria-pressed="{{ $selectedCategory === $kategori->catalog_key ? 'true' : 'false' }}"
                        class="{{ $selectedCategory === $kategori->catalog_key ? 'border-[#7A1F2B] bg-[#7A1F2B] text-white shadow-[0_12px_26px_rgba(122,31,43,0.18)]' : 'border-[#DCCDBD] bg-white text-[#6F5D58] hover:border-[#CBB39A] hover:text-[#7A1F2B]' }} inline-flex min-h-10 items-center rounded-full border px-4 text-[13px] font-semibold transition-all duration-200 sm:min-h-11 sm:px-5 sm:text-sm">
                        {{ $kategori->nama }}
                    </button>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-[#FFFDFB] py-6 sm:py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @if ($bestSellers->isNotEmpty())
                <section id="catalog-best-sellers" data-catalog-global @if ($selectedCategory !== 'all') hidden @endif class="mb-10 sm:mb-20">
                    <div class="max-w-2xl">
                        <div class="max-w-2xl">
                            <div class="text-[11px] font-semibold uppercase tracking-[0.2em] text-[#7A1F2B]">
                                Best Seller
                            </div>
                            <h2 class="mt-1.5 text-[1.7rem] font-bold leading-tight text-[#2D1E1E] sm:mt-2 sm:text-3xl">
                                Produk paling sering selesai dipesan.
                            </h2>
                        </div>
                    </div>

                    <div class="mt-5 sm:mt-8 grid grid-cols-2 gap-3 sm:gap-5 xl:grid-cols-4">
                        @foreach ($bestSellers as $produk)
                            @include('pages.home.partials.catalog-product-card', ['produk' => $produk, 'kategori' => $produk->kategori])
                        @endforeach
                    </div>
                </section>
            @endif

            @forelse ($visibleKategoris as $kategori)
                <section id="catalog-{{ $kategori->catalog_key }}" data-catalog-section
                    data-category-key="{{ $kategori->catalog_key }}"
                    @if ($selectedCategory !== 'all' && $selectedCategory !== $kategori->catalog_key) hidden @endif
                    class="{{ $loop->first ? '' : 'mt-10 border-t border-[#E8DCCE] pt-8 sm:mt-20 sm:pt-16' }}">
                    <div class="max-w-2xl">
                        <div class="max-w-2xl">
                            <div class="text-[11px] font-semibold uppercase tracking-[0.2em] text-[#7A1F2B]">
                                Kategori
                            </div>
                            <h2 class="mt-1.5 text-[1.7rem] font-bold leading-tight text-[#2D1E1E] sm:mt-2 sm:text-3xl">
                                {{ $kategori->nama }}
                            </h2>
                            <p class="mt-2 text-sm leading-relaxed text-[#6B5C57] sm:mt-3 sm:text-base">
                                {{ $kategori->deskripsi ?: 'Pilihan produk yang bisa langsung Anda cek sebelum memesan.' }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-5 sm:mt-8 grid grid-cols-2 gap-3 sm:gap-5 xl:grid-cols-4">
                        @foreach ($kategori->daftarProduk as $produk)
                            @include('pages.home.partials.catalog-product-card', ['produk' => $produk, 'kategori' => $kategori])
                        @endforeach
                    </div>
                </section>
            @empty
                <div class="rounded-[2rem] border border-dashed border-[#DCC5AA] bg-white/80 px-6 py-12 text-center shadow-[0_14px_35px_rgba(80,44,33,0.04)]">
                    <div class="mx-auto max-w-xl">
                        <div class="text-[10px] uppercase tracking-[0.24em] text-[#7A1F2B]/65">Katalog belum tersedia</div>
                        <h2 class="mt-3 text-2xl font-semibold text-[#2D1E1E]" style="font-family:'Playfair Display',serif;">
                            Produk publik sedang kami rapikan
                        </h2>
                        <p class="mt-3 text-sm leading-relaxed text-[#6B5C57]">
                            Beberapa item belum ditampilkan di katalog ini, tapi Anda tetap bisa langsung konsultasi
                            untuk kebutuhan Sadita.
                        </p>
                        <a href="https://wa.me/62812616155335?text={{ rawurlencode('Halo Sadita, saya ingin konsultasi mengenai katalog produk.') }}"
                            target="_blank" rel="noopener noreferrer"
                            data-external-url="https://wa.me/62812616155335?text={{ rawurlencode('Halo Sadita, saya ingin konsultasi mengenai katalog produk.') }}"
                            class="mt-5 inline-flex min-h-11 items-center justify-center rounded-full bg-[#7A1F2B] px-5 text-sm font-semibold text-white transition-colors hover:bg-[#8A2432]">
                            Tanya via WhatsApp
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </section>

    <section class="border-t border-[#E8DCCE] bg-[#FAF5F0] py-12 sm:py-14">
        <div class="mx-auto grid max-w-7xl gap-8 px-5 sm:px-6 lg:grid-cols-[minmax(0,1fr)_auto] lg:items-center lg:px-8">
            <div class="max-w-2xl">
                <div class="text-[11px] font-semibold uppercase tracking-[0.2em] text-[#7A1F2B]">Masih bingung pilih?</div>
                <h2 class="mt-3 text-3xl font-semibold leading-tight text-[#2D1E1E]" style="font-family:'Playfair Display',serif;">
                    Ceritakan kebutuhan acaranya, nanti kami bantu arahkan produknya.
                </h2>
                <p class="mt-3 text-sm leading-relaxed text-[#6B5C57] sm:text-base">
                    Cocok untuk kebutuhan yang belum yakin mau masuk papan ucapan, hantaran, atau dekorasi, atau masih
                    butuh diskusi soal budget dan detail acara.
                </p>
            </div>
            <a href="https://wa.me/62812616155335?text={{ rawurlencode('Halo Sadita, saya ingin konsultasi untuk memilih produk yang paling sesuai.') }}"
                target="_blank" rel="noopener noreferrer"
                data-external-url="https://wa.me/62812616155335?text={{ rawurlencode('Halo Sadita, saya ingin konsultasi untuk memilih produk yang paling sesuai.') }}"
                class="inline-flex min-h-12 items-center justify-center rounded-full bg-[#7A1F2B] px-6 text-sm font-semibold text-white transition-all duration-200 hover:-translate-y-0.5 hover:bg-[#8A2432] hover:shadow-[0_10px_22px_rgba(122,31,43,0.16)]">
                Chat untuk konsultasi
            </a>
        </div>
    </section>

    @include('pages.home.partials.product-modal')
@endsection
