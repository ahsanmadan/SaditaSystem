@extends('layouts.app')

@section('content')
    <section id="beranda" class="relative w-full h-[100svh] overflow-hidden bg-[#18181b]">
        <!-- Infinite Horizontal Carousel Background -->
        <div class="hero-bg-carousel absolute inset-0 z-0 flex items-center overflow-hidden pointer-events-none opacity-40">
            <div class="flex flex-row items-center gap-3 sm:gap-5 animate-scroll-horizontal">
                <!-- Set A (8 Curated Best Photos) -->
                <div
                    class="flex-shrink-0 w-32 h-48 sm:w-44 sm:h-64 lg:w-56 lg:h-80 rounded-xl sm:rounded-2xl border border-white/10 sm:border-2 overflow-hidden shadow-md sm:shadow-lg rotate-3">
                    <img src="/images/bridesmaid-gift-box.jpg" alt="" class="w-full h-full object-cover"
                        decoding="async">
                </div>
                <div
                    class="flex-shrink-0 w-32 h-48 sm:w-44 sm:h-64 lg:w-56 lg:h-80 rounded-xl sm:rounded-2xl border border-white/10 sm:border-2 overflow-hidden shadow-md sm:shadow-lg -rotate-2">
                    <img src="/images/dekorasi-tunangan.jpg" alt="" class="w-full h-full object-cover"
                        decoding="async">
                </div>
                <div
                    class="flex-shrink-0 w-32 h-48 sm:w-44 sm:h-64 lg:w-56 lg:h-80 rounded-xl sm:rounded-2xl border border-white/10 sm:border-2 overflow-hidden shadow-md sm:shadow-lg rotate-6">
                    <img src="/images/set-hantaran-nikah.jpg" alt="" class="w-full h-full object-cover"
                        decoding="async">
                </div>
                <div
                    class="flex-shrink-0 w-32 h-48 sm:w-44 sm:h-64 lg:w-56 lg:h-80 rounded-xl sm:rounded-2xl border border-white/10 sm:border-2 overflow-hidden shadow-md sm:shadow-lg -rotate-3">
                    <img src="/images/hero-2.jpg" alt="" class="w-full h-full object-cover" decoding="async">
                </div>
                <div
                    class="flex-shrink-0 w-32 h-48 sm:w-44 sm:h-64 lg:w-56 lg:h-80 rounded-xl sm:rounded-2xl border border-white/10 sm:border-2 overflow-hidden shadow-md sm:shadow-lg rotate-2">
                    <img src="/images/hantaran-premium-wedding.jpg" alt="" class="w-full h-full object-cover"
                        decoding="async">
                </div>
                <div
                    class="flex-shrink-0 w-32 h-48 sm:w-44 sm:h-64 lg:w-56 lg:h-80 rounded-xl sm:rounded-2xl border border-white/10 sm:border-2 overflow-hidden shadow-md sm:shadow-lg -rotate-6">
                    <img src="/images/dekorasi-lamaran.jpg" alt="" class="w-full h-full object-cover"
                        decoding="async">
                </div>
                <div
                    class="flex-shrink-0 w-32 h-48 sm:w-44 sm:h-64 lg:w-56 lg:h-80 rounded-xl sm:rounded-2xl border border-white/10 sm:border-2 overflow-hidden shadow-md sm:shadow-lg rotate-3">
                    <img src="/images/seserahan-adat-minang.jpg" alt="" class="w-full h-full object-cover"
                        decoding="async">
                </div>
                <div
                    class="flex-shrink-0 w-32 h-48 sm:w-44 sm:h-64 lg:w-56 lg:h-80 rounded-xl sm:rounded-2xl border border-white/10 sm:border-2 overflow-hidden shadow-md sm:shadow-lg -rotate-2">
                    <img src="/images/papan-congratulations-eksklusif.jpg" alt=""
                        class="w-full h-full object-cover" decoding="async">
                </div>
                <!-- Set B (Duplicate for seamless infinite loop) -->
                <div
                    class="flex-shrink-0 w-32 h-48 sm:w-44 sm:h-64 lg:w-56 lg:h-80 rounded-xl sm:rounded-2xl border border-white/10 sm:border-2 overflow-hidden shadow-md sm:shadow-lg rotate-3">
                    <img src="/images/bridesmaid-gift-box.jpg" alt="" class="w-full h-full object-cover"
                        loading="lazy" decoding="async" fetchpriority="low">
                </div>
                <div
                    class="flex-shrink-0 w-32 h-48 sm:w-44 sm:h-64 lg:w-56 lg:h-80 rounded-xl sm:rounded-2xl border border-white/10 sm:border-2 overflow-hidden shadow-md sm:shadow-lg -rotate-2">
                    <img src="/images/dekorasi-tunangan.jpg" alt="" class="w-full h-full object-cover" loading="lazy"
                        decoding="async" fetchpriority="low">
                </div>
                <div
                    class="flex-shrink-0 w-32 h-48 sm:w-44 sm:h-64 lg:w-56 lg:h-80 rounded-xl sm:rounded-2xl border border-white/10 sm:border-2 overflow-hidden shadow-md sm:shadow-lg rotate-6">
                    <img src="/images/set-hantaran-nikah.jpg" alt="" class="w-full h-full object-cover" loading="lazy"
                        decoding="async" fetchpriority="low">
                </div>
                <div
                    class="flex-shrink-0 w-32 h-48 sm:w-44 sm:h-64 lg:w-56 lg:h-80 rounded-xl sm:rounded-2xl border border-white/10 sm:border-2 overflow-hidden shadow-md sm:shadow-lg -rotate-3">
                    <img src="/images/hero-2.jpg" alt="" class="w-full h-full object-cover" loading="lazy"
                        decoding="async" fetchpriority="low">
                </div>
                <div
                    class="flex-shrink-0 w-32 h-48 sm:w-44 sm:h-64 lg:w-56 lg:h-80 rounded-xl sm:rounded-2xl border border-white/10 sm:border-2 overflow-hidden shadow-md sm:shadow-lg rotate-2">
                    <img src="/images/hantaran-premium-wedding.jpg" alt="" class="w-full h-full object-cover"
                        loading="lazy" decoding="async" fetchpriority="low">
                </div>
                <div
                    class="flex-shrink-0 w-32 h-48 sm:w-44 sm:h-64 lg:w-56 lg:h-80 rounded-xl sm:rounded-2xl border border-white/10 sm:border-2 overflow-hidden shadow-md sm:shadow-lg -rotate-6">
                    <img src="/images/dekorasi-lamaran.jpg" alt="" class="w-full h-full object-cover" loading="lazy"
                        decoding="async" fetchpriority="low">
                </div>
                <div
                    class="flex-shrink-0 w-32 h-48 sm:w-44 sm:h-64 lg:w-56 lg:h-80 rounded-xl sm:rounded-2xl border border-white/10 sm:border-2 overflow-hidden shadow-md sm:shadow-lg rotate-3">
                    <img src="/images/seserahan-adat-minang.jpg" alt="" class="w-full h-full object-cover"
                        loading="lazy" decoding="async" fetchpriority="low">
                </div>
                <div
                    class="flex-shrink-0 w-32 h-48 sm:w-44 sm:h-64 lg:w-56 lg:h-80 rounded-xl sm:rounded-2xl border border-white/10 sm:border-2 overflow-hidden shadow-md sm:shadow-lg -rotate-2">
                    <img src="/images/papan-congratulations-eksklusif.jpg" alt=""
                        class="w-full h-full object-cover" loading="lazy" decoding="async" fetchpriority="low">
                </div>
            </div>
        </div>

        <!-- Dimmed Gradient Overlay - stronger on mobile for readability -->
        <div
            class="hero-mobile-overlay absolute inset-0 bg-gradient-to-b from-[#18181b]/10 via-[#18181b]/50 to-[#18181b]/95 sm:from-transparent sm:via-[#18181b]/40 sm:to-[#18181b]/80 z-10 pointer-events-none">
        </div>

        <div class="hero-content relative z-20 h-full flex items-center justify-center pt-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                <div class="hero-content-inner max-w-2xl mx-auto text-center">
                    <span
                        class="hero-kicker inline-block text-[10px] sm:text-sm uppercase tracking-[0.25em] sm:tracking-[0.3em] text-[#E8C87A] font-medium mb-3 sm:mb-4 reveal-on-scroll">Papan
                        Ucapan, Hantaran & Dekorasi - Padang</span>
                    <h1 class="text-3xl sm:text-5xl lg:text-6xl xl:text-7xl font-bold text-white leading-[1.08] sm:leading-[1.03] reveal-on-scroll"
                        style="font-family:'Playfair Display',serif; text-shadow: 0 2px 20px rgba(0,0,0,0.6);">
                        Papan Ucapan,<br>Hantaran &amp; <em class="italic text-[#E8C87A]">Dekorasi</em>
                    </h1>
                    <p class="hero-summary mt-3 sm:mt-5 text-sm sm:text-base lg:text-lg text-white/70 leading-relaxed max-w-sm sm:max-w-lg mx-auto reveal-on-scroll"
                        style="text-shadow: 0 1px 8px rgba(0,0,0,0.5);">
                        Sadita melayani papan ucapan, hantaran, dan dekorasi untuk berbagai momen spesial di Padang.
                    </p>
                    <div class="hero-cta-group mt-6 sm:mt-8 flex flex-wrap justify-center gap-3 sm:gap-4 reveal-on-scroll">
                        <a href="#kategori"
                            class="hero-cta-primary btn-primary px-6 sm:px-8 py-3 sm:py-3.5 rounded-full bg-[#7A1F2B] text-white text-sm font-semibold tracking-wide">
                            Pesan Sekarang
                        </a>
                        <a href="#galeri"
                            class="hero-cta-secondary btn-outline px-6 sm:px-8 py-3 sm:py-3.5 rounded-full border border-white/30 text-white text-sm font-semibold backdrop-blur-sm hover:bg-white/10 transition-all duration-300">
                            Lihat Koleksi
                        </a>
                    </div>
                    <div class="hero-quick-links mt-6 sm:mt-9 reveal-on-scroll">
                        <p class="text-xs sm:text-sm text-white/55">
                            Mulai dari layanan yang Anda butuhkan:
                        </p>
                        <div class="mt-3 flex flex-wrap items-center justify-center gap-x-4 gap-y-2 text-sm sm:text-[15px]">
                            <a href="#kategori-papan-ucapan"
                                class="text-white/82 transition-colors duration-300 hover:text-[#E8C87A]">
                                Papan Ucapan
                            </a>
                            <span class="text-white/28">/</span>
                            <a href="#kategori-hantaran"
                                class="text-white/82 transition-colors duration-300 hover:text-[#E8C87A]">
                                Hantaran
                            </a>
                            <span class="text-white/28">/</span>
                            <a href="#kategori-dekorasi"
                                class="text-white/82 transition-colors duration-300 hover:text-[#E8C87A]">
                                Dekorasi
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="absolute bottom-8 sm:bottom-12 left-1/2 -translate-x-1/2 z-20 flex flex-col items-center gap-2">
            <span class="text-white/40 text-[10px] tracking-[0.3em] uppercase">Scroll</span>
            <svg class="w-4 h-4 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
            </svg>
        </div>
    </section>


    <section id="kategori" class="py-16 sm:py-20 bg-[#FFFDFB]">
        <div class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-8">
            <div class="grid gap-8 lg:grid-cols-[minmax(0,320px)_minmax(0,1fr)] lg:items-end reveal-on-scroll">
                <div class="max-w-sm">
                    <span class="text-[11px] uppercase tracking-[0.24em] text-[#7A1F2B] font-semibold">Pilihan Utama</span>
                    <h2 class="mt-3 text-3xl sm:text-4xl font-bold leading-tight text-[#2D1E1E]">Tiga jalur layanan yang
                        paling sering dicari pelanggan Sadita.</h2>
                </div>
                <div class="lg:pb-1">
                    <p class="max-w-2xl text-sm sm:text-base leading-relaxed text-[#6B5C57]">
                        Supaya tidak terasa seperti katalog yang penuh pilihan acak, produk kami kami pecah berdasarkan cara
                        orang benar-benar memesan: papan ucapan untuk pesan cepat, hantaran untuk hadiah yang rapi, dan
                        dekorasi untuk momen yang perlu ditata lebih personal.
                    </p>
                </div>
            </div>

            <div class="mt-14 grid grid-cols-1 lg:grid-cols-12 gap-5 sm:gap-6">
                @php
                    $categories = [
                        [
                            'Papan Ucapan',
                            'Mulai Rp 85rb',
                            'Standing board & mirror elegan untuk momen berharga',
                            '/images/cat-papan-ucapan.jpg',
                            '#kategori-papan-ucapan',
                            'lg:col-span-5 lg:mt-10',
                            'aspect-[4/4.7]',
                        ],
                        [
                            'Hantaran',
                            'Mulai Rp 30rb',
                            'Seserahan & gift box premium dengan detail cantik',
                            '/images/cat-hantaran.jpg',
                            '#kategori-hantaran',
                            'lg:col-span-4',
                            'aspect-[4/5.2]',
                        ],
                        [
                            'Dekorasi',
                            'Mulai Rp 500rb',
                            'Wujudkan dekorasi impian untuk hari bahagia Anda',
                            '/images/cat-dekorasi.jpg',
                            '#kategori-dekorasi',
                            'lg:col-span-3 lg:mt-16',
                            'aspect-[4/5.4]',
                        ],
                    ];
                @endphp
                @foreach ($categories as $i => [$title, $price, $desc, $img, $link, $layoutClass, $aspectClass])
                    <div class="reveal-on-scroll {{ $layoutClass }}" style="animation-delay: {{ $i * 150 }}ms">
                        <a href="{{ $link }}" class="category-card group relative block h-full">
                            <div
                                class="relative overflow-hidden rounded-[2rem] {{ $aspectClass }} shadow-xl border border-[#EBDCCB] transition-all duration-500 group-hover:shadow-2xl group-hover:border-[#E8C87A]/60 bg-gray-100">
                                <!-- Image -->
                                <img src="{{ asset($img) }}" alt="{{ $title }}"
                                    class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110"
                                    loading="lazy">
                                <!-- Gradient overlay with maroon hover -->
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent transition-colors duration-500 group-hover:from-[#7A1F2B]/95 group-hover:via-[#7A1F2B]/60">
                                </div>

                                <!-- Content -->
                                <div class="absolute inset-0 flex flex-col justify-end p-6 sm:p-8">
                                    <div class="transform transition-transform duration-500 group-hover:-translate-y-2">
                                        <div class="flex items-start justify-between gap-4">
                                            <div>
                                                <div class="mb-3 text-[10px] uppercase tracking-[0.22em] text-white/70">
                                                    {{ $i === 0 ? 'Pesan cepat' : ($i === 1 ? 'Hadiah personal' : 'Penataan acara') }}
                                                </div>
                                                <h3 class="text-2xl sm:text-3xl font-bold text-white tracking-wide"
                                                    style="font-family:'Playfair Display',serif">{{ $title }}</h3>
                                                <span
                                                    class="inline-block mt-2 px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-white text-xs font-semibold tracking-wider">{{ $price }}</span>
                                            </div>
                                            <div
                                                class="w-10 h-10 rounded-full bg-[#E8C87A] flex items-center justify-center opacity-0 -translate-x-4 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-500">
                                                <svg class="w-5 h-5 text-[#2D1E1E]" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                </svg>
                                            </div>
                                        </div>
                                        <p class="text-sm sm:text-base text-white/80 mt-4 leading-relaxed">
                                            {{ $desc }}</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Why Choose Us Mini Bar -->
    <div class="w-full bg-[#7A1F2B] text-[#E8C87A] py-3 sm:py-4 overflow-hidden border-y border-[#E8C87A]/30">
        @php
            $miniMarqueeItems = [
                'Custom Design',
                'Harga Terjangkau',
                'Gratis Ongkir untuk area Padang',
                'Papan Ucapan',
                'Hantaran',
                'Dekorasi',
            ];
        @endphp
        <div class="mini-marquee">
            <div
                class="mini-marquee-track text-[10px] sm:text-xs font-semibold tracking-wider uppercase whitespace-nowrap">
                @for ($i = 0; $i < 3; $i++)
                    @foreach ($miniMarqueeItems as $label)
                        <span class="mini-marquee-item"><span class="text-white"></span> {{ $label }}</span>
                    @endforeach
                @endfor
            </div>
        </div>
    </div>

    @foreach ($kategoris as $kategori)
        @php
            $sectionAnchorId = $kategori->slug === 'papan-bunga' ? 'kategori-papan-ucapan' : 'kategori-' . $kategori->slug;
            $legacyAnchorId = $kategori->slug === 'papan-bunga' ? 'kategori-papan-bunga' : null;
        @endphp
        @if ($legacyAnchorId)
            <div id="{{ $legacyAnchorId }}" class="relative -top-24 sm:-top-28"></div>
        @endif
        <section id="{{ $sectionAnchorId }}"
            class="pt-10 pb-4 sm:pt-14 sm:pb-8 {{ $loop->odd ? 'bg-[#FAF5F0]' : 'bg-white' }}">
            @if (!$loop->first)
                <div style="width:80px; height:2px; background:#C9A84C; margin: 0 auto 40px; opacity: 0.5;"></div>
            @endif
            <div class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-8">
                <div class="flex items-end justify-between mb-6 reveal-on-scroll">
                    <div>
                        <span
                            class="text-[10px] sm:text-xs uppercase tracking-[0.15em] text-[#7A1F2B]/70 font-semibold">{{ $kategori->nama }}</span>
                        <h2 class="text-2xl sm:text-3xl font-bold text-[#2D1E1E]">{{ $kategori->nama }}</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">
                            {{ $kategori->deskripsi ?? 'Koleksi eksklusif dari Sadita' }}</p>
                    </div>
                    <a href="#"
                        class="text-xs sm:text-sm text-[#7A1F2B] font-semibold hover:underline whitespace-nowrap">Lihat
                        Semua -></a>
                </div>

                @if ($kategori->daftarProduk->isNotEmpty())
                    <div
                        class="product-scroll-container flex gap-4 overflow-x-auto pb-8 pt-2 snap-x snap-mandatory scrollbar-hide reveal-on-scroll">
                        @foreach ($kategori->daftarProduk as $produk)
                            @php
                                $isDecor = strtolower($kategori->nama) === 'dekorasi';
                                $imgUrl = $produk->fotoUtamaUrl();
                                $priceStr = 'Rp ' . number_format($produk->harga_dasar, 0, ',', '.');
                                $descStr = $produk->deskripsi ?? 'Detail produk ' . $produk->nama;
                            @endphp
                            <div data-product-card data-modal-title="{{ e($produk->nama) }}"
                                data-modal-price="{{ e($priceStr) }}" data-modal-image="{{ e($imgUrl) }}"
                                data-modal-desc="{{ e($descStr) }}" data-modal-tag="{{ e($kategori->nama) }}"
                                data-modal-is-decor="{{ $isDecor ? '1' : '0' }}"
                                class="group product-card cursor-pointer min-w-[160px] sm:min-w-[220px] max-w-[160px] sm:max-w-[220px] flex-shrink-0 snap-start rounded-2xl overflow-hidden bg-white border border-gray-100 transition-all duration-300 hover:-translate-y-2 hover:shadow-[0_12px_30px_rgba(122,31,43,0.15)] flex flex-col">
                                <div class="relative h-48 sm:h-60 w-full overflow-hidden flex-shrink-0 bg-gray-50">
                                    <img src="{{ $imgUrl }}" alt="{{ $produk->nama }}"
                                        class="w-full h-full object-cover object-center transition-transform duration-700 group-hover:scale-110"
                                        loading="lazy">
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                    </div>
                                    <div class="absolute top-2 left-2 z-10 flex gap-1 flex-wrap">
                                        <span
                                            class="px-2.5 py-0.5 bg-white/95 backdrop-blur-sm text-[#7A1F2B] text-[9px] sm:text-[10px] font-bold rounded-full shadow-sm">{{ $kategori->nama }}</span>
                                        <span
                                            class="px-2 py-0.5 bg-[#7A1F2B]/95 backdrop-blur-sm text-[#E8C87A] text-[9px] sm:text-[10px] font-bold rounded-full shadow-sm">
                                            {{ $produk->is_sewa ? 'Sewa' : 'Jasa' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="p-3 sm:p-4 flex flex-col flex-1 bg-white relative z-10">
                                    <div class="flex text-[#C9A84C] text-[10px] mb-1 tracking-widest">
                                        &#9733;&#9733;&#9733;&#9733;&#9733;</div>
                                    <h4 class="text-xs sm:text-sm font-semibold text-[#2D1E1E] line-clamp-2 leading-tight flex-1"
                                        style="min-height: 2.5rem;">{{ $produk->nama }}</h4>
                                    <div class="mt-2 mb-3 text-[10px] sm:text-[11px] text-gray-500">Mulai <span
                                            class="font-extrabold text-[#7A1F2B] text-xs sm:text-sm">{{ $priceStr }}</span>
                                    </div>
                                    <div class="mt-auto">
                                        @if ($isDecor)
                                            @php
                                                $waText = "Halo Sadita,\n\nSaya ingin konsultasi dekorasi.\n\nJenis Dekorasi: {$produk->nama}\n\nTanggal Acara:\nWaktu Acara:\n\nLokasi Acara:\n\nKonsep / Tema yang diinginkan:\n(Contoh: elegan, rustic, minimalis, dll)\n\nCatatan tambahan:\n(opsional)\n\nTerima kasih.";
                                            @endphp
                                            <button type="button"
                                                data-stop-modal
                                                data-external-url="https://wa.me/62812616155335?text={{ rawurlencode($waText) }}"
                                                class="w-full py-2 bg-[#7A1F2B] hover:bg-[#C9A84C] text-white text-[10px] sm:text-xs font-bold rounded-xl flex items-center justify-center gap-1.5 transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z" />
                                                </svg>
                                                Konsultasi
                                            </button>
                                        @else
                                            <button type="button"
                                                data-stop-modal
                                                data-order-url="{{ route('order') }}?product={{ rawurlencode($produk->nama) }}&price={{ rawurlencode($priceStr) }}&img={{ rawurlencode($imgUrl) }}&jenis={{ rawurlencode($kategori->nama) }}"
                                                class="w-full py-2 bg-[#7A1F2B] hover:bg-[#C9A84C] text-white text-[10px] sm:text-xs font-bold rounded-xl flex items-center justify-center gap-1.5 transition-colors">
                                                Pesan
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="group min-w-[160px] sm:min-w-[220px] max-w-[160px] sm:max-w-[220px] flex-shrink-0 snap-start rounded-2xl overflow-hidden bg-[#7A1F2B] text-white transition-all duration-300 hover:-translate-y-2 hover:shadow-[0_12px_30px_rgba(122,31,43,0.3)] flex flex-col justify-center items-center text-center p-4 sm:p-5 cursor-pointer relative"
                            data-external-url="https://wa.me/62812616155335?text=Halo+Sadita%2C+saya+ingin+konsultasi+mengenai+pesanan+saya">
                            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                            <div
                                class="w-10 h-10 sm:w-14 sm:h-14 rounded-full bg-white/10 border border-white/20 flex items-center justify-center mb-3 sm:mb-5 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-[#E8C87A]" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z" />
                                </svg>
                            </div>
                            <h4 class="text-xs sm:text-base font-bold tracking-wide leading-snug">Bingung Pilih<br>Produk?</h4>
                            <p class="text-[9px] sm:text-[11px] text-white/80 mt-2 mb-4 leading-relaxed">Konsultasi gratis via
                                WhatsApp</p>
                            <span
                                class="text-[9px] sm:text-xs font-bold text-[#7A1F2B] bg-[#E8C87A] px-4 py-2 rounded-full w-full block group-hover:bg-white transition-colors">Chat
                                Sekarang</span>
                        </div>
                    </div>
                @else
                    <div
                        class="reveal-on-scroll rounded-[2rem] border border-dashed border-[#DCC5AA] bg-white/80 px-6 py-10 text-center shadow-[0_14px_35px_rgba(80,44,33,0.04)]">
                        <div class="mx-auto max-w-2xl">
                            <div class="text-[10px] uppercase tracking-[0.24em] text-[#7A1F2B]/65">Katalog sedang disiapkan</div>
                            <h3 class="mt-3 text-2xl font-bold text-[#2D1E1E]" style="font-family:'Playfair Display',serif">
                                Koleksi {{ $kategori->nama }} akan tampil di sini
                            </h3>
                            <p class="mt-3 text-sm leading-relaxed text-[#6B5C57]">
                                Detail produknya belum dimasukkan ke katalog publik, tapi Anda tetap bisa langsung konsultasi
                                untuk kebutuhan {{ strtolower($kategori->nama) }}.
                            </p>
                            <button type="button"
                                data-external-url="https://wa.me/62812616155335?text={{ rawurlencode('Halo Sadita, saya ingin konsultasi mengenai ' . $kategori->nama . '.') }}"
                                class="mt-5 inline-flex items-center justify-center rounded-full bg-[#7A1F2B] px-5 py-3 text-sm font-semibold text-white transition-colors hover:bg-[#8f2734]">
                                Tanya via WhatsApp
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </section>
    @endforeach

    <section class="py-16 sm:py-20 bg-[#FFFDFB] overflow-hidden">
        <div class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-8">
            <div class="grid gap-10 lg:grid-cols-[minmax(0,340px)_minmax(0,1fr)] lg:items-start">
                <div class="reveal-on-scroll">
                    <span class="text-[11px] uppercase tracking-[0.22em] text-[#7A1F2B] font-semibold">Kenapa
                        Dipilih</span>
                    <h2 class="mt-3 text-3xl sm:text-4xl font-bold leading-tight text-[#2D1E1E]">Sadita terasa lebih
                        meyakinkan karena detail kecilnya ikut dijaga.</h2>
                    <p class="mt-4 max-w-sm text-sm sm:text-base leading-relaxed text-[#6B5C57]">
                        Kami tidak sedang mengejar kesan mewah yang berlebihan. Yang kami jaga justru hal-hal yang paling
                        sering diingat pelanggan: bahan, ketepatan waktu, komunikasi, dan hasil akhir yang rapi saat
                        difoto maupun dikirim.
                    </p>
                    <div
                        class="mt-8 rounded-[2rem] border border-[#EADCCB] bg-white p-6 shadow-[0_18px_50px_rgba(80,44,33,0.06)]">
                        <div class="text-[10px] uppercase tracking-[0.24em] text-[#7A1F2B]/65">Yang paling sering
                            diapresiasi</div>
                        <p class="mt-3 text-lg font-semibold leading-relaxed text-[#2D1E1E]"
                            style="font-family:'Playfair Display',serif">
                            "Hasilnya rapi, tidak ramai berlebihan, dan tetap terasa personal untuk acara masing-masing."
                        </p>
                    </div>
                </div>
                <div class="grid gap-5 sm:grid-cols-2 lg:max-w-4xl lg:mx-auto lg:self-center">
                    @foreach ([['Kualitas Premium', 'Bahan material terbaik dengan detail pengerjaan yang teliti untuk hasil yang elegan dan memukau.', '/images/why-premium.png', 'Pilihan bahan'], ['Proses Cepat', 'Pengerjaan profesional yang responsif dan tepat waktu untuk momen berharga Anda.', '/images/why-fast.png', 'Pengerjaan'], ['Gratis Ongkir', 'Layanan pengiriman aman dan gratis untuk seluruh wilayah Padang dan sekitarnya.', '/images/why-delivery.png', 'Pengantaran'], ['Custom Request', 'Desain dapat disesuaikan dengan kebutuhan acara, tone warna, dan preferensi pelanggan.', '/images/why-custom.png', 'Fleksibilitas']] as $index => $feature)
                        <div
                            class="reason-card group reveal-on-scroll rounded-[1.75rem] border border-[#EADCCB] bg-white p-5 sm:p-6 shadow-[0_14px_40px_rgba(80,44,33,0.05)] {{ $index % 2 === 1 ? 'sm:translate-y-8' : '' }}">
                            <div class="mb-4 flex items-center justify-between gap-4">
                                <div class="reason-card-meta text-[10px] uppercase tracking-[0.22em] text-[#7A1F2B]/65">
                                    {{ $feature[3] }}
                                </div>
                                <img src="{{ asset($feature[2]) }}" alt="{{ $feature[0] }}"
                                    class="reason-card-icon h-16 w-16 object-contain" loading="lazy">
                            </div>
                            <h3 class="reason-card-title text-xl font-bold text-[#2D1E1E]">{{ $feature[0] }}</h3>
                            <p class="mt-3 text-sm leading-relaxed text-[#6B5C57]">{{ $feature[1] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section id="galeri" class="py-16 sm:py-20 bg-[#FFFDFB]">
        <div class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-8">
            <div class="text-center reveal-on-scroll">
                <span class="text-xs uppercase tracking-[0.2em] text-[#7A1F2B] font-semibold">Portfolio</span>
                <h2 class="mt-3 text-3xl sm:text-4xl font-bold text-[#2D1E1E]">Galeri Karya Kami</h2>
            </div>

            <!-- Filter Tabs -->
            <div class="flex flex-wrap justify-center gap-3 mt-8 reveal-on-scroll">
                <button
                    class="filter-btn active px-5 py-2 rounded-full text-xs sm:text-sm font-semibold transition-colors duration-300 bg-[#7A1F2B] text-white"
                    data-filter="all">Semua</button>
                <button
                    class="filter-btn px-5 py-2 rounded-full text-xs sm:text-sm font-semibold transition-colors duration-300 bg-white text-gray-500 border border-gray-200 hover:bg-gray-50"
                    data-filter="dekorasi">Dekorasi</button>
                <button
                    class="filter-btn px-5 py-2 rounded-full text-xs sm:text-sm font-semibold transition-colors duration-300 bg-white text-gray-500 border border-gray-200 hover:bg-gray-50"
                    data-filter="hantaran">Hantaran</button>
                <button
                    class="filter-btn px-5 py-2 rounded-full text-xs sm:text-sm font-semibold transition-colors duration-300 bg-white text-gray-500 border border-gray-200 hover:bg-gray-50"
                    data-filter="papan">Papan Ucapan</button>
            </div>

            <div class="masonry-grid mt-10" id="gallery-container">
                @php
                    $galleryImages = [
                        ['papan-standing-mirror-premium.jpg', 'tall', 'papan', 'Papan Standing Mirror Premium'],
                        ['bridesmaid-gift-box.jpg', 'normal', 'hantaran', 'Bridesmaid Gift Box'],
                        ['dekorasi-lamaran.jpg', 'normal', 'dekorasi', 'Dekorasi Lamaran'],
                        ['papan-congratulations-eksklusif.jpg', 'wide', 'papan', 'Papan Congratulations Eksklusif'],
                        ['hantaran-premium-wedding.jpg', 'tall', 'hantaran', 'Hantaran Premium Wedding'],
                        ['table-setting-premium.jpg', 'normal', 'dekorasi', 'Table Setting Premium'],
                        ['papan-rustic-custom.jpg', 'normal', 'papan', 'Papan Rustic Custom'],
                        ['seserahan-adat-minang.jpg', 'normal', 'hantaran', 'Seserahan Adat Minang'],
                        ['dekorasi-grand-opening.jpg', 'tall', 'dekorasi', 'Dekorasi Grand Opening'],
                        ['standing-mirror-besar.jpg', 'wide', 'papan', 'Standing Mirror Besar'],
                        ['hantaran-gold-edition.jpg', 'wide', 'hantaran', 'Hantaran Gold Edition'],
                        ['dekorasi-akad-nikah.jpg', 'normal', 'dekorasi', 'Dekorasi Akad Nikah'],
                        ['papan-ucapan-selamatan.jpg', 'normal', 'papan', 'Papan Ucapan Selamatan'],
                    ];
                @endphp
                @foreach ($galleryImages as $i => [$gImg, $gSize, $gCat, $gLabel])
                    <div class="masonry-item masonry-{{ $gSize }} reveal-on-scroll"
                        data-category="{{ $gCat }}" style="animation-delay:{{ $i * 50 }}ms">
                        <div
                            class="gallery-card group relative overflow-hidden rounded-2xl shadow-sm hover:shadow-xl transition-shadow duration-300 cursor-pointer w-full h-full">
                            <img src="{{ asset('images/' . $gImg) }}" alt="{{ $gLabel }}"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                loading="lazy">
                            <div
                                class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent p-5 transition-all duration-500 opacity-0 group-hover:opacity-100 flex items-end">
                                <span
                                    class="text-[#E8C87A] text-[11px] sm:text-xs font-bold tracking-widest uppercase transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">{{ $gLabel }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- CTA Lihat Semua -->
            <div class="mt-12 sm:mt-16 text-center reveal-on-scroll">
                <a href="https://instagram.com/sadita.decor" target="_blank"
                    class="inline-flex items-center gap-2 px-8 py-3 sm:py-4 rounded-full border border-[#7A1F2B] text-[#7A1F2B] text-sm font-bold hover:bg-[#7A1F2B] hover:text-white transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                    Follow @sadita.decor untuk lebih banyak karya
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <section id="cara-pesan" class="pt-16 pb-10 sm:pt-20 sm:pb-12 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-8">
            <div class="grid gap-10 lg:grid-cols-[minmax(0,320px)_minmax(0,1fr)] lg:items-start">
                <div class="reveal-on-scroll lg:sticky lg:top-28">
                    <span class="text-[11px] uppercase tracking-[0.22em] text-[#7A1F2B] font-semibold">Cara Pesan</span>
                    <h2 class="mt-3 text-3xl sm:text-4xl font-bold leading-tight text-[#2D1E1E]">Alurnya singkat, tapi
                        tetap terasa dipandu.</h2>
                    <p class="mt-4 max-w-sm text-sm sm:text-base leading-relaxed text-[#6B5C57]">
                        Kami susun supaya orang yang baru pertama kali datang pun langsung paham harus mulai dari mana,
                        kapan perlu isi detail, dan kapan tinggal menunggu kabar dari tim Sadita.
                    </p>
                </div>
                <div class="relative">
                    <div class="absolute left-[1.15rem] top-4 bottom-4 hidden w-px bg-[#E6D7C4] sm:block"></div>
                    @php
                        $steps = [
                            [
                                '01',
                                'Pilih Produk',
                                'Jelajahi koleksi dan pilih kategori yang paling dekat dengan kebutuhan acara Anda.',
                                asset('images/icon-buy.png'),
                                'Langkah awal',
                            ],
                            [
                                '02',
                                'Isi Detail',
                                'Lengkapi detail pesanan, lokasi, waktu acara, dan catatan penting lain.',
                                asset('images/icon-form.png'),
                                'Data pesanan',
                            ],
                            [
                                '03',
                                'Bayar',
                                'Lanjutkan ke pembayaran setelah pesanan dan nominalnya siap diproses.',
                                asset('images/icon-payment.png'),
                                'Pembayaran',
                            ],
                            [
                                '04',
                                'Lacak',
                                'Pantau status pesanan Anda tanpa harus bolak-balik menanyakan progres.',
                                asset('images/icon-deliver.png'),
                                'Sesudah checkout',
                            ],
                        ];
                    @endphp
                    <div class="space-y-4 sm:space-y-5">
                        @foreach ($steps as $i => [$num, $stepTitle, $stepDesc, $iconPath, $stepLabel])
                            <div class="step-card relative reveal-on-scroll rounded-[1.75rem] border border-[#EADCCB] bg-[#FFFCF8] p-5 sm:p-6 shadow-[0_12px_30px_rgba(80,44,33,0.05)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_18px_44px_rgba(80,44,33,0.08)]"
                                style="animation-delay:{{ $i * 100 }}ms">
                                <div class="flex items-start gap-4 sm:gap-6">
                                    <div class="step-card-side flex w-[4.75rem] flex-shrink-0 flex-col items-center gap-3 sm:w-[5.5rem]">
                                        <div
                                            class="flex h-9 min-w-9 items-center justify-center rounded-full bg-[#7A1F2B] px-3 text-[11px] font-bold tracking-[0.16em] text-white shadow-sm">
                                            {{ $num }}
                                        </div>
                                        <div
                                            class="step-card-icon flex h-[4.5rem] w-[4.5rem] items-center justify-center rounded-[1.4rem] border border-[#E8D8C3] bg-[#F8ECDD] shadow-[inset_0_1px_0_rgba(255,255,255,0.5)] sm:h-[5rem] sm:w-[5rem]">
                                            <img src="{{ $iconPath }}" alt="{{ $stepTitle }}"
                                                class="h-10 w-10 object-contain sm:h-11 sm:w-11" loading="lazy">
                                        </div>
                                    </div>
                                    <div class="flex-1 pt-1">
                                        <div class="max-w-xl">
                                            <div class="text-[10px] uppercase tracking-[0.22em] text-[#7A1F2B]/60">
                                                {{ $stepLabel }}
                                            </div>
                                            <h3 class="mt-2 text-xl sm:text-2xl font-bold text-[#2D1E1E]">
                                                {{ $stepTitle }}
                                            </h3>
                                            <p class="mt-3 text-sm sm:text-base leading-relaxed text-[#6B5C57]">
                                                {{ $stepDesc }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="lacak"
        class="pt-16 pb-8 sm:pt-20 sm:pb-12 bg-gradient-to-br from-[#7A1F2B] to-[#4a1119] text-white relative overflow-hidden">
        <!-- Decorative background elements -->
        <div
            class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 rounded-full bg-white opacity-5 blur-3xl pointer-events-none">
        </div>
        <div
            class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 rounded-full bg-[#E8C87A] opacity-10 blur-3xl pointer-events-none">
        </div>

        <div class="max-w-4xl mx-auto px-5 text-center relative z-10">
            <div class="reveal-on-scroll">
                <div
                    class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-white/10 mb-4 border border-white/20">
                    <svg class="w-6 h-6 text-[#E8C87A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </div>
                <h2 class="text-3xl sm:text-4xl font-bold">Lacak Pesanan</h2>
                <p class="mt-3 text-sm sm:text-base text-white/72 max-w-xl mx-auto">Masukkan kode pesanan untuk melihat
                    status terbaru tanpa perlu login. Cocok untuk cek progres pesanan kapan saja.</p>

                <div
                    class="tracking-shell mt-7 max-w-3xl mx-auto rounded-[2rem] border border-white/14 bg-white/8 p-4 sm:p-5 backdrop-blur-md shadow-[0_18px_50px_rgba(20,7,9,0.22)]">
                    <div class="flex items-center justify-between gap-4 px-2 text-left">
                        <div>
                            <div class="text-[11px] sm:text-xs font-semibold uppercase tracking-[0.18em] text-[#E8C87A]">
                                Cek Status
                            </div>
                            <p class="mt-1 text-xs sm:text-sm text-white/68">Gunakan kode yang Anda terima setelah admin
                                mengonfirmasi pesanan.</p>
                        </div>
                    </div>

                    <div class="mt-4 flex flex-col sm:flex-row gap-3 relative">
                        <div class="tracking-field flex-1">
                            <input id="trackingInput"
                                class="w-full rounded-full border border-white/18 bg-white/12 px-6 py-4 text-white placeholder-white/42 text-sm focus:outline-none focus:ring-0"
                                placeholder="Contoh: SDT-20260411-001" autocomplete="off">
                        </div>
                        <button id="trackingBtn" type="button"
                            class="tracking-submit btn-track px-8 py-4 rounded-full bg-[#E8C87A] text-[#2D1E1E] font-bold text-sm shadow-lg flex items-center justify-center gap-2 whitespace-nowrap">
                            <span id="trackingBtnText">Lacak Sekarang</span>
                            <svg id="trackingSpinner" class="animate-spin -ml-1 mr-2 h-4 w-4 text-[#2D1E1E] hidden"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </button>
                    </div>

                    <div
                        class="mt-3 flex flex-col gap-2 px-2 text-left text-xs text-white/60 sm:flex-row sm:items-center sm:justify-between">
                        <p>Kode belum ketemu? Cek pesan WhatsApp atau hubungi admin Sadita.</p>
                        <span class="font-medium text-white/74">Format: SDT-tanggal-nomor</span>
                    </div>
                </div>

                <!-- Tracking Result State Container -->
                <div id="trackingResult"
                    class="hidden mt-8 text-left max-w-2xl mx-auto bg-white rounded-[1.75rem] p-6 shadow-2xl transform transition-all translate-y-4 opacity-0">
                    <!-- Dynamic content will be injected here -->
                </div>
            </div>
        </div>
    </section>

    <section id="tentang" class="pt-16 pb-12 sm:pt-20 sm:pb-16 bg-[#FAF5F0]">
        <div class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-8">
            <div class="grid gap-10 md:gap-12 lg:grid-cols-[minmax(0,1.05fr)_minmax(0,0.95fr)]">
                <div class="reveal-on-scroll flex flex-col">
                    <span class="text-[11px] uppercase tracking-[0.24em] text-[#7A1F2B] font-semibold">Tentang Kami</span>
                    <h2 class="mt-3 text-3xl sm:text-4xl font-bold leading-tight text-[#2D1E1E]">Tentang Sadita</h2>
                    <p class="mt-4 max-w-2xl text-sm sm:text-base leading-relaxed text-[#5b4747]">
                        Sadita adalah layanan papan bunga, hantaran, dan dekorasi berbasis di Padang yang berfokus pada
                        keindahan, detail, dan makna dalam setiap karya. Kami percaya setiap momen spesial layak
                        dirayakan dengan kesan yang tak terlupakan.
                    </p>
                    <div class="mt-8 grid gap-4 sm:grid-cols-[minmax(0,1.15fr)_minmax(0,0.85fr)]">
                        <div class="rounded-[1.8rem] border border-[#E8D9C8] bg-white px-6 py-6 shadow-[0_14px_36px_rgba(80,44,33,0.05)]">
                            <div class="text-[11px] uppercase tracking-[0.22em] text-[#7A1F2B]/65">Yang terus dijaga</div>
                            <p class="mt-4 text-lg sm:text-xl font-semibold leading-relaxed text-[#2D1E1E]" style="font-family:'Playfair Display',serif">
                                Detail yang rapi, komunikasi yang enak, dan hasil akhir yang pantas untuk momen penting.
                            </p>
                        </div>
                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-1">
                            <div class="rounded-[1.4rem] border border-[#E8D9C8] bg-white px-5 py-5 shadow-[0_12px_30px_rgba(80,44,33,0.04)]">
                                <div class="text-3xl sm:text-4xl font-bold leading-none text-[#7A1F2B]">3+</div>
                                <div class="mt-2 text-[11px] sm:text-xs font-medium uppercase tracking-[0.16em] text-[#74635d]">Tahun Pengalaman</div>
                            </div>
                            <div class="rounded-[1.4rem] border border-[#E8D9C8] bg-white px-5 py-5 shadow-[0_12px_30px_rgba(80,44,33,0.04)]">
                                <div class="text-3xl sm:text-4xl font-bold leading-none text-[#7A1F2B]">500+</div>
                                <div class="mt-2 text-[11px] sm:text-xs font-medium uppercase tracking-[0.16em] text-[#74635d]">Pelanggan</div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 rounded-[1.4rem] border border-[#E8D9C8] bg-white px-5 py-4 shadow-[0_12px_30px_rgba(80,44,33,0.04)] sm:max-w-[18rem]">
                        <div class="flex items-baseline gap-2">
                            <div class="text-3xl font-bold leading-none text-[#7A1F2B]">4.9</div>
                            <div class="text-xs uppercase tracking-[0.16em] text-[#8A7770]">Rating</div>
                        </div>
                        <p class="mt-2 text-sm leading-relaxed text-[#6B5C57]">Dipercaya untuk momen yang butuh hasil rapi dan terasa personal.</p>
                    </div>
                </div>
                <div id="kontak" class="reveal-on-scroll">
                    <div class="rounded-[2rem] border border-[#E8D9C8] bg-white/72 p-5 sm:p-6 shadow-[0_18px_40px_rgba(80,44,33,0.05)] backdrop-blur-sm">
                        <span class="text-[11px] uppercase tracking-[0.24em] text-[#7A1F2B] font-semibold">Hubungi Kami</span>
                        <div class="mt-3 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                            <h2 class="text-3xl sm:text-4xl font-bold text-[#2D1E1E]">Kontak</h2>
                            <p class="max-w-xs text-sm leading-relaxed text-[#7B6963]">
                                Pilih jalur komunikasi yang paling nyaman untuk Anda.
                            </p>
                        </div>
                        <div class="mt-6 space-y-3">
                    <a href="https://wa.me/6289653090248" target="_blank"
                        class="contact-card flex items-center gap-4 rounded-[1.5rem] border border-[#EFE4D8] bg-[#FFFDFC] p-4 sm:p-5 transition-all duration-300">
                        <div
                            class="flex h-13 w-13 flex-shrink-0 items-center justify-center rounded-[1.1rem] bg-[#F8ECDD] text-[#7A1F2B]">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z" />
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="font-bold text-base text-[#2D1E1E]">WhatsApp</div>
                            <div class="mt-0.5 text-sm text-[#6F5E58]">0896-5309-0248</div>
                            <div class="mt-1 text-xs uppercase tracking-[0.14em] text-[#9C877E]">Chat langsung</div>
                        </div>
                    </a>
                    <div
                        class="contact-card flex items-center gap-4 rounded-[1.5rem] border border-[#EFE4D8] bg-[#FFFDFC] p-4 sm:p-5 transition-all duration-300">
                        <div
                            class="flex h-13 w-13 flex-shrink-0 items-center justify-center rounded-[1.1rem] bg-[#F8ECDD] text-[#7A1F2B]">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" />
                            </svg>
                        </div>
                        <div class="overflow-hidden min-w-0 flex-1">
                            <div class="font-bold text-base text-[#2D1E1E]">Instagram</div>
                            <div
                                class="mt-1 flex flex-wrap items-center gap-x-1.5 gap-y-0.5 text-sm text-[#6F5E58]">
                                <a href="https://instagram.com/sadita.decor" target="_blank" rel="noopener noreferrer"
                                    class="hover:text-[#7A1F2B] hover:underline">@sadita.decor</a>
                                <span class="text-[#B29F97]">-</span>
                                <a href="https://instagram.com/sadita.hantaran" target="_blank" rel="noopener noreferrer"
                                    class="hover:text-[#7A1F2B] hover:underline">@sadita.hantaran</a>
                            </div>
                        </div>
                    </div>
                    <a href="https://maps.google.com/?q=Padang+Sumatera+Barat" target="_blank"
                        class="contact-card flex items-center gap-4 rounded-[1.5rem] border border-[#EFE4D8] bg-[#FFFDFC] p-4 sm:p-5 transition-all duration-300">
                        <div
                            class="flex h-13 w-13 flex-shrink-0 items-center justify-center rounded-[1.1rem] bg-[#F8ECDD] text-[#7A1F2B]">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="font-bold text-base text-[#2D1E1E]">Lokasi</div>
                            <div class="mt-1 text-sm text-[#6F5E58]">Padang, Sumatera Barat</div>
                        </div>
                    </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Product Detail Modal -->
    <div id="productModal" data-order-url="{{ route('order') }}"
        class="fixed inset-0 z-[100] hidden flex justify-center items-center p-4 sm:p-6 opacity-0 transition-opacity duration-300">

        <!-- Backdrop -->
        <div class="absolute inset-0 bg-[#2D1E1E]/40 backdrop-blur-sm transition-opacity"></div>

        <!-- Modal Card (Split Layout on Desktop) -->
        <div data-product-modal-card
            class="bg-white w-[95%] sm:w-full max-w-[360px] md:max-w-[750px] lg:max-w-[850px] rounded-[1.5rem] md:rounded-[2rem] shadow-[0_20px_50px_rgba(0,0,0,0.15)] transform scale-95 transition-transform duration-300 ease-out relative z-10 flex flex-col md:flex-row overflow-hidden max-h-[90vh]">

            <!-- Close Button -->
            <button type="button" data-product-modal-close
                class="absolute top-3 right-3 md:top-5 md:right-5 z-20 w-8 h-8 md:w-10 md:h-10 bg-white/80 md:bg-gray-100 backdrop-blur-sm md:backdrop-blur-none rounded-full flex items-center justify-center text-[#2D1E1E] hover:bg-white hover:text-[#7A1F2B] hover:shadow-md transition-all group">
                <svg class="w-4 h-4 md:w-5 md:h-5 transform group-hover:rotate-90 transition-transform duration-300"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>

            <!-- Image Area -->
            <div
                class="w-full md:w-[45%] aspect-[4/3] md:aspect-auto md:h-auto relative flex items-center justify-center bg-[#F9F9F9] overflow-hidden group">
                <img id="modalImg" alt=""
                    class="w-full h-full md:absolute md:inset-0 object-cover transition-transform duration-700 group-hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent md:hidden">
                </div>
            </div>

            <!-- Content Area -->
            <div class="w-full md:w-[55%] p-6 md:p-10 flex flex-col justify-center relative bg-white">
                <div
                    class="text-[#C9A84C] text-[10px] md:text-xs font-bold uppercase tracking-widest mb-3 md:mb-4 flex items-center gap-2">
                    <span class="w-4 md:w-6 h-[1px] bg-[#C9A84C]"></span> Sadita Collection
                </div>
                <h3 id="modalTitle" class="text-xl md:text-3xl font-bold text-[#2D1E1E] mb-3 md:mb-4 leading-tight"
                    style="font-family:'Playfair Display',serif"></h3>

                <div class="flex-1 overflow-y-auto pr-2 scrollbar-hide mb-6 md:mb-8">
                    <p id="modalDesc"
                        class="text-xs md:text-sm text-gray-500 leading-relaxed line-clamp-4 md:line-clamp-none mb-4 md:mb-6">
                    </p>

                    <!-- Detail List -->
                    <ul class="hidden md:flex flex-col space-y-3 text-xs md:text-sm text-gray-500">
                        <li class="flex items-center gap-3">
                            <div class="w-6 h-6 rounded-full bg-[#FAF5F0] flex items-center justify-center text-[#C9A84C]">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            Kualitas Premium & Eksklusif
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="w-6 h-6 rounded-full bg-[#FAF5F0] flex items-center justify-center text-[#C9A84C]">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            Desain Elegan dan Tahan Lama
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="w-6 h-6 rounded-full bg-[#FAF5F0] flex items-center justify-center text-[#C9A84C]">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            Dapat Disesuaikan (Custom)
                        </li>
                    </ul>
                </div>

                <div class="mt-auto pt-5 md:pt-6 border-t border-gray-100 flex items-center justify-between">
                    <div>
                        <div
                            class="text-[9px] md:text-[10px] text-gray-400 uppercase tracking-widest font-semibold mb-0.5 md:mb-1">
                            Mulai Dari</div>
                        <div id="modalPrice" class="text-lg md:text-2xl font-bold text-[#7A1F2B]">
                        </div>
                    </div>
                    <button id="modalOrderBtn"
                        class="py-2.5 md:py-3 px-6 md:px-8 bg-[#7A1F2B] hover:bg-[#C9A84C] text-white rounded-xl md:rounded-2xl text-xs md:text-sm font-bold uppercase tracking-widest transition-all duration-300 shadow-md hover:shadow-xl hover:-translate-y-1 flex items-center justify-center gap-2 group">
                        <span id="modalBtnText">Pesan</span>
                        <span id="modalBtnIcon">
                            <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes scroll-horizontal {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-50%);
            }
        }

        .animate-scroll-horizontal {
            animation: scroll-horizontal 35s linear infinite;
            will-change: transform;
        }
    </style>
@endsection
