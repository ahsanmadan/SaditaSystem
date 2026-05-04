@extends('layouts.app')

@section('content')
    <section id="beranda" class="relative w-full h-[100svh] overflow-hidden bg-[#18181b]">
        <!-- Infinite Horizontal Carousel Background -->
        <div class="absolute inset-0 z-0 flex items-center overflow-hidden pointer-events-none opacity-40">
            <div class="flex flex-row items-center gap-3 sm:gap-5 animate-scroll-horizontal">
                <!-- Set A (8 Curated Best Photos) -->
                <div
                    class="flex-shrink-0 w-32 h-48 sm:w-44 sm:h-64 lg:w-56 lg:h-80 rounded-xl sm:rounded-2xl border border-white/10 sm:border-2 overflow-hidden shadow-md sm:shadow-lg rotate-3">
                    <img src="/images/hantaran-1.jpg" alt="" class="w-full h-full object-cover">
                </div>
                <div
                    class="flex-shrink-0 w-32 h-48 sm:w-44 sm:h-64 lg:w-56 lg:h-80 rounded-xl sm:rounded-2xl border border-white/10 sm:border-2 overflow-hidden shadow-md sm:shadow-lg -rotate-2">
                    <img src="/images/dekorasi-2.jpg" alt="" class="w-full h-full object-cover">
                </div>
                <div
                    class="flex-shrink-0 w-32 h-48 sm:w-44 sm:h-64 lg:w-56 lg:h-80 rounded-xl sm:rounded-2xl border border-white/10 sm:border-2 overflow-hidden shadow-md sm:shadow-lg rotate-6">
                    <img src="/images/hantaran-2.jpg" alt="" class="w-full h-full object-cover">
                </div>
                <div
                    class="flex-shrink-0 w-32 h-48 sm:w-44 sm:h-64 lg:w-56 lg:h-80 rounded-xl sm:rounded-2xl border border-white/10 sm:border-2 overflow-hidden shadow-md sm:shadow-lg -rotate-3">
                    <img src="/images/hero-2.jpg" alt="" class="w-full h-full object-cover">
                </div>
                <div
                    class="flex-shrink-0 w-32 h-48 sm:w-44 sm:h-64 lg:w-56 lg:h-80 rounded-xl sm:rounded-2xl border border-white/10 sm:border-2 overflow-hidden shadow-md sm:shadow-lg rotate-2">
                    <img src="/images/hantaran-3.jpg" alt="" class="w-full h-full object-cover">
                </div>
                <div
                    class="flex-shrink-0 w-32 h-48 sm:w-44 sm:h-64 lg:w-56 lg:h-80 rounded-xl sm:rounded-2xl border border-white/10 sm:border-2 overflow-hidden shadow-md sm:shadow-lg -rotate-6">
                    <img src="/images/dekorasi-1.jpg" alt="" class="w-full h-full object-cover">
                </div>
                <div
                    class="flex-shrink-0 w-32 h-48 sm:w-44 sm:h-64 lg:w-56 lg:h-80 rounded-xl sm:rounded-2xl border border-white/10 sm:border-2 overflow-hidden shadow-md sm:shadow-lg rotate-3">
                    <img src="/images/hantaran-4.jpg" alt="" class="w-full h-full object-cover">
                </div>
                <div
                    class="flex-shrink-0 w-32 h-48 sm:w-44 sm:h-64 lg:w-56 lg:h-80 rounded-xl sm:rounded-2xl border border-white/10 sm:border-2 overflow-hidden shadow-md sm:shadow-lg -rotate-2">
                    <img src="/images/papan-2.jpg" alt="" class="w-full h-full object-cover">
                </div>
                <!-- Set B (Duplicate for seamless infinite loop) -->
                <div
                    class="flex-shrink-0 w-32 h-48 sm:w-44 sm:h-64 lg:w-56 lg:h-80 rounded-xl sm:rounded-2xl border border-white/10 sm:border-2 overflow-hidden shadow-md sm:shadow-lg rotate-3">
                    <img src="/images/hantaran-1.jpg" alt="" class="w-full h-full object-cover">
                </div>
                <div
                    class="flex-shrink-0 w-32 h-48 sm:w-44 sm:h-64 lg:w-56 lg:h-80 rounded-xl sm:rounded-2xl border border-white/10 sm:border-2 overflow-hidden shadow-md sm:shadow-lg -rotate-2">
                    <img src="/images/dekorasi-2.jpg" alt="" class="w-full h-full object-cover">
                </div>
                <div
                    class="flex-shrink-0 w-32 h-48 sm:w-44 sm:h-64 lg:w-56 lg:h-80 rounded-xl sm:rounded-2xl border border-white/10 sm:border-2 overflow-hidden shadow-md sm:shadow-lg rotate-6">
                    <img src="/images/hantaran-2.jpg" alt="" class="w-full h-full object-cover">
                </div>
                <div
                    class="flex-shrink-0 w-32 h-48 sm:w-44 sm:h-64 lg:w-56 lg:h-80 rounded-xl sm:rounded-2xl border border-white/10 sm:border-2 overflow-hidden shadow-md sm:shadow-lg -rotate-3">
                    <img src="/images/hero-2.jpg" alt="" class="w-full h-full object-cover">
                </div>
                <div
                    class="flex-shrink-0 w-32 h-48 sm:w-44 sm:h-64 lg:w-56 lg:h-80 rounded-xl sm:rounded-2xl border border-white/10 sm:border-2 overflow-hidden shadow-md sm:shadow-lg rotate-2">
                    <img src="/images/hantaran-3.jpg" alt="" class="w-full h-full object-cover">
                </div>
                <div
                    class="flex-shrink-0 w-32 h-48 sm:w-44 sm:h-64 lg:w-56 lg:h-80 rounded-xl sm:rounded-2xl border border-white/10 sm:border-2 overflow-hidden shadow-md sm:shadow-lg -rotate-6">
                    <img src="/images/dekorasi-1.jpg" alt="" class="w-full h-full object-cover">
                </div>
                <div
                    class="flex-shrink-0 w-32 h-48 sm:w-44 sm:h-64 lg:w-56 lg:h-80 rounded-xl sm:rounded-2xl border border-white/10 sm:border-2 overflow-hidden shadow-md sm:shadow-lg rotate-3">
                    <img src="/images/hantaran-4.jpg" alt="" class="w-full h-full object-cover">
                </div>
                <div
                    class="flex-shrink-0 w-32 h-48 sm:w-44 sm:h-64 lg:w-56 lg:h-80 rounded-xl sm:rounded-2xl border border-white/10 sm:border-2 overflow-hidden shadow-md sm:shadow-lg -rotate-2">
                    <img src="/images/papan-2.jpg" alt="" class="w-full h-full object-cover">
                </div>
            </div>
        </div>

        <!-- Dimmed Gradient Overlay - stronger on mobile for readability -->
        <div
            class="absolute inset-0 bg-gradient-to-b from-[#18181b]/10 via-[#18181b]/50 to-[#18181b]/95 sm:from-transparent sm:via-[#18181b]/40 sm:to-[#18181b]/80 z-10 pointer-events-none">
        </div>

        <div class="hero-content relative z-20 h-full flex items-center justify-center pt-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                <div class="max-w-2xl mx-auto text-center">
                    <span
                        class="inline-block text-[10px] sm:text-sm uppercase tracking-[0.25em] sm:tracking-[0.3em] text-[#E8C87A] font-medium mb-3 sm:mb-4 reveal-on-scroll">Florist
                        & Gift · Padang</span>
                    <h1 class="text-3xl sm:text-5xl lg:text-7xl xl:text-8xl font-bold text-white leading-[1.1] sm:leading-[1.05] reveal-on-scroll"
                        style="font-family:'Playfair Display',serif; text-shadow: 0 2px 20px rgba(0,0,0,0.6);">
                        Papan Bunga,<br>Hantaran &amp; <em class="italic text-[#E8C87A]">Dekorasi</em>
                    </h1>
                    <p class="mt-3 sm:mt-5 text-sm sm:text-base lg:text-lg text-white/70 leading-relaxed max-w-sm sm:max-w-lg mx-auto reveal-on-scroll"
                        style="text-shadow: 0 1px 8px rgba(0,0,0,0.5);">
                        Sadita menyediakan papan bunga, hantaran, dan dekorasi untuk berbagai acara di Padang.
                    </p>
                    <div class="mt-6 sm:mt-8 flex flex-wrap justify-center gap-3 sm:gap-4 reveal-on-scroll">
                        <a href="#kategori"
                            class="btn-primary px-6 sm:px-8 py-3 sm:py-3.5 rounded-full bg-[#7A1F2B] text-white text-sm font-semibold tracking-wide">
                            Pesan Sekarang
                        </a>
                        <a href="#galeri"
                            class="btn-outline px-6 sm:px-8 py-3 sm:py-3.5 rounded-full border border-white/30 text-white text-sm font-semibold backdrop-blur-sm hover:bg-white/10 transition-all duration-300">
                            Lihat Koleksi
                        </a>
                    </div>
                    <div
                        class="mt-6 sm:mt-10 inline-flex items-center gap-4 sm:gap-8 px-5 sm:px-8 py-3 sm:py-4 rounded-xl sm:rounded-2xl bg-white/5 backdrop-blur-md border border-white/10 reveal-on-scroll">
                        <div class="text-center">
                            <div class="counter-number text-xl sm:text-2xl lg:text-3xl font-bold text-white"
                                data-target="500">0+</div>
                            <div class="text-[9px] sm:text-[10px] text-white/50 mt-0.5 uppercase tracking-wider">Pesanan
                            </div>
                        </div>
                        <div class="w-px h-6 sm:h-8 bg-white/15"></div>
                        <div class="text-center">
                            <div class="counter-number text-xl sm:text-2xl lg:text-3xl font-bold text-white"
                                data-target="200">0+</div>
                            <div class="text-[9px] sm:text-[10px] text-white/50 mt-0.5 uppercase tracking-wider">Klien Puas
                            </div>
                        </div>
                        <div class="w-px h-6 sm:h-8 bg-white/15"></div>
                        <div class="text-center">
                            <div class="text-xl sm:text-2xl lg:text-3xl font-bold text-[#E8C87A]">4.9★</div>
                            <div class="text-[9px] sm:text-[10px] text-white/50 mt-0.5 uppercase tracking-wider">Rating
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="absolute bottom-8 sm:bottom-12 left-1/2 -translate-x-1/2 z-20 flex flex-col items-center gap-2 animate-bounce">
            <span class="text-white/40 text-[10px] tracking-[0.3em] uppercase">Scroll</span>
            <svg class="w-4 h-4 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
            </svg>
        </div>
    </section>


    <section id="kategori" class="py-16 sm:py-20 bg-[#FFFDFB]">
        <div class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-8">
            <div class="text-center reveal-on-scroll">
                <span class="text-xs uppercase tracking-[0.2em] text-[#7A1F2B] font-semibold">Koleksi Kami</span>
                <h2 class="mt-3 text-3xl sm:text-4xl font-bold text-[#2D1E1E]">Kategori Produk</h2>
                <p class="mt-3 text-sm text-gray-500 max-w-md mx-auto">Berikan kesan tak terlupakan di setiap momen bahagia
                    Anda</p>
            </div>

            <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8 max-w-6xl mx-auto px-4">
                @php($categories = [['Papan Ucapan', 'Mulai Rp 85rb', 'Standing board & mirror elegan untuk momen berharga', '/images/cat-papan-ucapan.jpg', '#greeting-board'], ['Hantaran', 'Mulai Rp 30rb', 'Seserahan & gift box premium dengan detail cantik', '/images/cat-hantaran.jpg', '#hantaran'], ['Dekorasi', 'Mulai Rp 500rb', 'Wujudkan dekorasi impian untuk hari bahagia Anda', '/images/cat-dekorasi.jpg', '#dekorasi']])
                @foreach ($categories as $i => [$title, $price, $desc, $img, $link])
                    <div class="reveal-on-scroll {{ $i === 1 ? 'md:-translate-y-8' : '' }}"
                        style="animation-delay: {{ $i * 150 }}ms">
                        <a href="{{ $link }}" class="category-card group relative block h-full">
                            <div
                                class="relative overflow-hidden rounded-[2rem] aspect-[3/4] shadow-xl border border-gray-100 transition-all duration-500 group-hover:shadow-2xl group-hover:border-[#E8C87A]/50 bg-gray-100">
                                <!-- Image -->
                                <img src="{{ $img }}" alt="{{ $title }}"
                                    class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110"
                                    loading="lazy">
                                <!-- Gradient overlay with maroon hover -->
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent transition-colors duration-500 group-hover:from-[#7A1F2B]/95 group-hover:via-[#7A1F2B]/60">
                                </div>

                                <!-- Content -->
                                <div class="absolute inset-0 flex flex-col justify-end p-6 sm:p-8">
                                    <div class="transform transition-transform duration-500 group-hover:-translate-y-2">
                                        <div class="flex items-start justify-between">
                                            <div>
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
        <div
            class="max-w-7xl mx-auto px-4 flex justify-between sm:justify-center sm:gap-12 items-center text-[10px] sm:text-xs font-semibold tracking-wider uppercase whitespace-nowrap overflow-x-auto scrollbar-hide">
            <span class="flex items-center gap-1.5"><span class="text-white">✦</span> Custom Design</span>
            <span class="flex items-center gap-1.5"><span class="text-white">✦</span> Harga Terjangkau</span>
            <span class="flex items-center gap-1.5"><span class="text-white">✦</span> Pengiriman Padang</span>
            <span class="flex items-center gap-1.5"><span class="text-white">✦</span> 500+ Pelanggan</span>
        </div>
    </div>

    <?php
    $products = [
        'greeting-board' => [
            'title' => 'Papan Ucapan',
            'subtitle' => 'Greeting Board',
            'desc' => 'Hadirkan kesan pertama yang tak terlupakan',
            'items' => [['Papan Standing Mirror Premium', 'Rp 150.000', '/images/papan-1.jpg', 'Custom'], ['Papan Congratulations Eksklusif', 'Rp 120.000', '/images/papan-2.jpg', 'Terlaris'], ['Papan Rustic Custom', 'Rp 100.000', '/images/papan-3.jpg', 'Custom'], ['Papan Ucapan Selamatan', 'Rp 85.000', '/images/papan-4.jpg', 'Ready Stock'], ['Standing Mirror Besar', 'Rp 200.000', '/images/papan-5.jpg', 'Premium']],
        ],
        'hantaran' => [
            'title' => 'Hantaran',
            'subtitle' => 'Seserahan & Gift',
            'desc' => 'Persembahan terbaik untuk hari paling bahagia',
            'items' => [['Bridesmaid Gift Box', 'Rp 45.000', '/images/hantaran-1.jpg', 'Custom'], ['Set Hantaran Nikah', 'Rp 65.000', '/images/hantaran-2.jpg', 'Terlaris'], ['Hantaran Premium Wedding', 'Rp 85.000', '/images/hantaran-3.jpg', 'Premium'], ['Seserahan Adat Minang', 'Rp 75.000', '/images/hantaran-4.jpg', 'Custom'], ['Hantaran Gold Edition', 'Rp 100.000', '/images/hantaran-5.jpg', 'Premium']],
        ],
        'dekorasi' => [
            'title' => 'Dekorasi',
            'subtitle' => 'Event Decoration',
            'desc' => 'Ubah ruangan biasa menjadi momen luar biasa',
            'items' => [['Dekorasi Lamaran', 'Rp 400.000', '/images/dekorasi-1.jpg', 'Custom'], ['Dekorasi Tunangan', 'Rp 500.000', '/images/dekorasi-2.jpg', 'Terlaris'], ['Table Setting Premium', 'Rp 350.000', '/images/dekorasi-3.jpg', 'Custom'], ['Dekorasi Grand Opening', 'Rp 850.000', '/images/dekorasi-4.jpg', 'Premium'], ['Dekorasi Akad Nikah', 'Rp 1.500.000', '/images/dekorasi-5.jpg', 'Premium']],
        ],
    ];
    ?>

    @foreach ($products as $key => $category)
        <section id="{{ $key }}"
            class="pt-10 pb-4 sm:pt-14 sm:pb-8 {{ $loop->odd ? 'bg-[#FAF5F0]' : 'bg-white' }}">
            @if (!$loop->first)
                <div style="width:80px; height:2px; background:#C9A84C; margin: 0 auto 40px; opacity: 0.5;"></div>
            @endif
            <div class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-8">
                <div class="flex items-end justify-between mb-6 reveal-on-scroll">
                    <div>
                        <span
                            class="text-[10px] sm:text-xs uppercase tracking-[0.15em] text-[#7A1F2B]/70 font-semibold">{{ $category['subtitle'] }}</span>
                        <h2 class="text-2xl sm:text-3xl font-bold text-[#2D1E1E]">{{ $category['title'] }}</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">{{ $category['desc'] }}</p>
                    </div>
                    <a href="#"
                        class="text-xs sm:text-sm text-[#7A1F2B] font-semibold hover:underline whitespace-nowrap">Lihat
                        Semua →</a>
                </div>

                <div
                    class="product-scroll-container flex gap-4 overflow-x-auto pb-8 pt-2 snap-x snap-mandatory scrollbar-hide reveal-on-scroll">
                    @foreach ($category['items'] as $j => [$name, $price, $img, $tag])
                        <div
                            class="group product-card min-w-[150px] sm:min-w-[180px] max-w-[150px] sm:max-w-[180px] flex-shrink-0 snap-start rounded-2xl overflow-hidden bg-white border border-gray-100 transition-all duration-300 hover:-translate-y-2 hover:shadow-[0_12px_30px_rgba(122,31,43,0.15)] flex flex-col">
                            <div class="relative h-40 sm:h-48 w-full overflow-hidden flex-shrink-0">
                                <img src="{{ $img }}" alt="{{ $name }}"
                                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                    loading="lazy">
                                @if ($tag)
                                    <span
                                        class="absolute top-2 left-2 px-2.5 py-0.5 bg-white/95 backdrop-blur-sm text-[#7A1F2B] text-[9px] sm:text-[10px] font-bold rounded-full shadow-sm">{{ $tag }}</span>
                                @endif
                            </div>
                            <div class="p-3 sm:p-4 flex flex-col flex-1 bg-white relative z-10">
                                <div class="flex text-[#C9A84C] text-[10px] mb-1 tracking-widest">
                                    &#9733;&#9733;&#9733;&#9733;&#9733;</div>
                                <h4 class="text-xs sm:text-sm font-semibold text-[#2D1E1E] line-clamp-2 leading-tight flex-1"
                                    style="min-height: 2.5rem;">{{ $name }}</h4>
                                <div class="mt-2 text-[10px] sm:text-[11px] text-gray-500">Mulai <span
                                        class="font-extrabold text-[#7A1F2B] text-xs sm:text-sm">{{ $price }}</span>
                                </div>
                                <a href="https://wa.me/6289653090248?text=Halo+Sadita%2C+saya+tertarik+dengan+{{ urlencode($name) }}"
                                    target="_blank"
                                    class="mt-3 w-full py-1.5 sm:py-2 rounded-lg border border-[#7A1F2B] text-[#7A1F2B] text-[10px] sm:text-xs font-semibold text-center block transition-colors duration-300 group-hover:bg-[#7A1F2B] group-hover:text-white">
                                    Hubungi Kami
                                </a>
                            </div>
                        </div>
                    @endforeach

                    <!-- 6th Card CTA (WhatsApp) -->
                    <div class="group min-w-[150px] sm:min-w-[180px] max-w-[150px] sm:max-w-[180px] flex-shrink-0 snap-start rounded-2xl overflow-hidden bg-[#7A1F2B] text-white transition-all duration-300 hover:-translate-y-2 hover:shadow-[0_12px_30px_rgba(122,31,43,0.3)] flex flex-col justify-center items-center text-center p-4 sm:p-5 cursor-pointer relative"
                        onclick="window.open('https://wa.me/6289653090248?text=Halo+Sadita%2C+saya+ingin+konsultasi+mengenai+pesanan+saya', '_blank')">
                        <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                        <div
                            class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-white/10 border border-white/20 flex items-center justify-center mb-3 sm:mb-4 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7 text-[#E8C87A]" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z" />
                            </svg>
                        </div>
                        <h4 class="text-xs sm:text-sm font-bold tracking-wide leading-snug">Bingung Pilih<br>Produk?</h4>
                        <p class="text-[9px] sm:text-[10px] text-white/80 mt-2 mb-3 leading-relaxed">Konsultasi gratis via
                            WhatsApp</p>
                        <span
                            class="text-[9px] sm:text-[10px] font-bold text-[#7A1F2B] bg-[#E8C87A] px-3 py-1.5 rounded-full w-full block group-hover:bg-white transition-colors">Chat
                            Sekarang</span>
                    </div>
                </div>
            </div>
        </section>
    @endforeach

    <section class="py-16 sm:py-20 bg-[#FFFDFB] overflow-hidden">
        <div class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-8">
            <div class="text-center reveal-on-scroll">
                <span class="text-xs uppercase tracking-[0.2em] text-[#7A1F2B] font-semibold">Mengapa Kami</span>
                <h2 class="mt-3 text-3xl sm:text-4xl font-bold text-[#2D1E1E]">Kenapa Pilih Sadita?</h2>
            </div>
            <div class="mt-12 sm:mt-16 grid grid-cols-1 sm:grid-cols-2 gap-6 sm:gap-8 max-w-4xl mx-auto">
                @foreach ([['01', 'Kualitas Premium', 'Bahan material terbaik dengan detail pengerjaan yang teliti untuk hasil yang elegan dan memukau.', '/images/why-premium.png'], ['02', 'Proses Cepat', 'Pengerjaan profesional yang responsif dan tepat waktu untuk momen berharga Anda.', '/images/why-fast.png'], ['03', 'Gratis Ongkir', 'Layanan pengiriman aman dan gratis untuk seluruh wilayah Padang dan sekitarnya.', '/images/why-delivery.png'], ['04', 'Custom Request', 'Kebebasan berekspresi. Desain dapat disesuaikan sepenuhnya dengan keinginan Anda.', '/images/why-custom.png']] as $index => $feature)
                    <div
                        class="group relative bg-white border border-gray-100 rounded-2xl p-6 sm:p-8 hover:shadow-[0_12px_30px_rgba(122,31,43,0.08)] transition-all duration-300 reveal-on-scroll flex flex-col h-full min-h-[280px]">
                        <div
                            class="absolute top-4 right-6 text-6xl font-playfair font-black text-gray-200 group-hover:text-[#F3E8D6] transition-colors duration-300 pointer-events-none">
                            {{ $feature[0] }}</div>

                        <img src="{{ $feature[3] }}" alt="{{ $feature[1] }}"
                            class="w-[120px] h-[120px] object-contain block mb-6 relative z-10 group-hover:scale-110 transition-transform duration-300 drop-shadow-sm flex-shrink-0"
                            loading="lazy">

                        <div class="flex flex-col flex-1 relative z-10">
                            <h3 class="text-lg sm:text-xl font-bold text-[#2D1E1E] mb-3">{{ $feature[1] }}</h3>
                            <p class="text-sm text-gray-500 leading-relaxed">{{ $feature[2] }}</p>
                        </div>
                    </div>
                @endforeach
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
                @php($galleryImages = [['papan-1.jpg', 'tall', 'papan', '🌸 Papan Ucapan Premium'], ['hantaran-1.jpg', 'normal', 'hantaran', '🎁 Hantaran Seserahan'], ['dekorasi-1.jpg', 'normal', 'dekorasi', '✨ Dekorasi Pernikahan'], ['papan-2.jpg', 'wide', 'papan', '🌸 Papan Bunga Rustic'], ['hantaran-3.jpg', 'tall', 'hantaran', '🎁 Hantaran Eksklusif'], ['dekorasi-3.jpg', 'normal', 'dekorasi', '✨ Dekorasi Lamaran'], ['papan-3.jpg', 'normal', 'papan', '🌸 Papan Congratulations'], ['hantaran-4.jpg', 'normal', 'hantaran', '🎁 Hantaran Adat'], ['dekorasi-4.jpg', 'tall', 'dekorasi', '✨ Dekorasi Premium'], ['papan-4.jpg', 'wide', 'papan', '🌸 Standing Mirror'], ['hantaran-5.jpg', 'wide', 'hantaran', '🎁 Hantaran Gold'], ['dekorasi-5.jpg', 'normal', 'dekorasi', '✨ Grand Opening'], ['papan-5.jpg', 'normal', 'papan', '🌸 Ucapan Custom']])
                @foreach ($galleryImages as $i => [$gImg, $gSize, $gCat, $gLabel])
                    <div class="masonry-item masonry-{{ $gSize }} reveal-on-scroll"
                        data-category="{{ $gCat }}" style="animation-delay:{{ $i * 50 }}ms">
                        <div
                            class="gallery-card group relative overflow-hidden rounded-2xl shadow-sm hover:shadow-xl transition-shadow duration-300 cursor-pointer w-full h-full">
                            <img src="/images/{{ $gImg }}" alt="{{ $gLabel }}"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                loading="lazy">
                            <div
                                class="absolute inset-0 bg-black/0 group-hover:bg-black/50 transition-all duration-500 flex flex-col items-center justify-center gap-2 opacity-0 group-hover:opacity-100">
                                <span
                                    class="text-[#E8C87A] text-[10px] sm:text-xs font-bold tracking-widest uppercase transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">{{ $gLabel }}</span>
                                <span
                                    class="text-white text-xs sm:text-sm font-medium border-b border-white/50 pb-0.5 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500 delay-75">Lihat
                                    Detail</span>
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

    <!-- Vanilla JS for Gallery Filter -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterBtns = document.querySelectorAll('.filter-btn');
            const masonryItems = document.querySelectorAll('.masonry-item');

            filterBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    // Reset all buttons
                    filterBtns.forEach(b => {
                        b.classList.remove('bg-[#7A1F2B]', 'text-white', 'active');
                        b.classList.add('bg-white', 'text-gray-500', 'border',
                            'border-gray-200', 'hover:bg-gray-50');
                    });

                    // Activate clicked button
                    btn.classList.add('bg-[#7A1F2B]', 'text-white', 'active');
                    btn.classList.remove('bg-white', 'text-gray-500', 'border', 'border-gray-200',
                        'hover:bg-gray-50');

                    const filterValue = btn.getAttribute('data-filter');

                    masonryItems.forEach(item => {
                        if (filterValue === 'all' || item.getAttribute('data-category') ===
                            filterValue) {
                            item.style.display = 'block';
                            // Quick hack to re-trigger masonry layout if needed, though pure CSS column masonry handles display:none beautifully
                            setTimeout(() => {
                                item.style.opacity = '1';
                            }, 50);
                        } else {
                            item.style.opacity = '0';
                            setTimeout(() => {
                                item.style.display = 'none';
                            }, 300);
                        }
                    });
                });
            });
        });
    </script>

    <section id="cara-pesan" class="pt-16 pb-10 sm:pt-20 sm:pb-12 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-8">
            <div class="text-center reveal-on-scroll">
                <span class="text-xs uppercase tracking-[0.2em] text-[#7A1F2B] font-semibold">Mudah & Cepat</span>
                <h2 class="mt-3 text-3xl sm:text-4xl font-bold text-[#2D1E1E]">Cara Pesan</h2>
            </div>
            <div class="mt-10 flex flex-col md:flex-row gap-6 sm:gap-4 justify-between relative">
                @php(
    $steps = [
        ['01', 'Pilih Produk', 'Jelajahi koleksi dan pilih yang sesuai', '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg>'],
        ['02', 'Isi Detail', 'Lengkapi detail pesanan & alamat', '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>'],
        ['03', 'Bayar', 'Lakukan pembayaran yang aman', '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" /></svg>'],
        ['04', 'Lacak', 'Pantau status pesanan Anda', '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" /></svg>']
    ]
)
                @foreach ($steps as $i => [$num, $stepTitle, $stepDesc, $icon])
                    <div class="step-card relative text-center p-5 sm:p-6 pt-8 sm:pt-10 rounded-2xl bg-[#FAF5F0] reveal-on-scroll hover:shadow-lg transition-all duration-300 flex flex-col flex-1 h-full border border-gray-100 overflow-hidden"
                        style="animation-delay:{{ $i * 100 }}ms">
                        <div
                            class="absolute top-0 left-0 w-10 h-10 rounded-br-2xl bg-[#E8C87A] text-[#7A1F2B] font-bold flex items-center justify-center text-sm shadow-sm">
                            {{ $num }}</div>
                        <div
                            class="w-14 h-14 mx-auto rounded-full bg-[#7A1F2B] text-white flex items-center justify-center mb-4 shadow-md group-hover:scale-110 transition-transform flex-shrink-0">
                            {!! $icon !!}
                        </div>
                        <div class="flex flex-col flex-1">
                            <h3 class="text-sm sm:text-base font-bold text-[#2D1E1E]">{{ $stepTitle }}</h3>
                            <p class="mt-2 text-xs sm:text-sm text-gray-500 leading-relaxed">{{ $stepDesc }}</p>
                        </div>
                    </div>
                    @if ($i < count($steps) - 1)
                        <div class="hidden md:flex items-center justify-center text-gray-300 self-center">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </div>
                    @endif
                @endforeach
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

        <div class="max-w-2xl mx-auto px-5 text-center relative z-10">
            <div class="reveal-on-scroll">
                <div
                    class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-white/10 mb-4 border border-white/20">
                    <svg class="w-6 h-6 text-[#E8C87A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </div>
                <h2 class="text-3xl sm:text-4xl font-bold">Lacak Pesanan</h2>
                <p class="mt-3 text-sm sm:text-base text-white/70 max-w-lg mx-auto">Masukkan kode pesanan Anda di bawah ini
                    untuk melihat status terkini dari pesanan Anda.</p>
                <div class="mt-8 flex flex-col sm:flex-row gap-3 max-w-lg mx-auto relative">
                    <input id="trackingInput"
                        class="flex-1 rounded-full border border-white/40 bg-white/15 backdrop-blur-sm px-6 py-3.5 text-white placeholder-white/50 text-sm focus:outline-none focus:ring-2 focus:ring-[#E8C87A] focus:bg-white/20 transition-all shadow-inner"
                        placeholder="Contoh: SDT-20260411-001" autocomplete="off">
                    <button id="trackingBtn" onclick="trackOrder()"
                        class="btn-track px-8 py-3.5 rounded-full bg-[#E8C87A] hover:bg-white text-[#2D1E1E] font-bold text-sm transition-colors duration-300 shadow-lg flex items-center justify-center gap-2 whitespace-nowrap">
                        <span id="trackingBtnText">Lacak</span>
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

                <!-- Tracking Result State Container -->
                <div id="trackingResult"
                    class="hidden mt-8 text-left max-w-lg mx-auto bg-white rounded-2xl p-6 shadow-2xl transform transition-all translate-y-4 opacity-0">
                    <!-- Dynamic content will be injected here -->
                </div>
            </div>
        </div>
    </section>

    <!-- Tracking Logic JS -->
    <script>
        function trackOrder() {
            const input = document.getElementById('trackingInput');
            const btnText = document.getElementById('trackingBtnText');
            const spinner = document.getElementById('trackingSpinner');
            const resultBox = document.getElementById('trackingResult');

            const code = input.value.trim().toUpperCase();

            if (!code) {
                input.focus();
                input.classList.add('ring-2', 'ring-red-400');
                setTimeout(() => input.classList.remove('ring-2', 'ring-red-400'), 1000);
                return;
            }

            // Loading state
            btnText.textContent = "Mencari...";
            spinner.classList.remove('hidden');
            resultBox.classList.add('hidden');
            resultBox.classList.remove('translate-y-0', 'opacity-100');

            // Simulate API call
            setTimeout(() => {
                btnText.textContent = "Lacak";
                spinner.classList.add('hidden');

                resultBox.classList.remove('hidden');

                // Demo logic: If starts with SDT, assume success, else not found
                if (code.startsWith('SDT')) {
                    resultBox.innerHTML = `
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-[#2D1E1E] font-bold text-lg">Pesanan Ditemukan</h4>
                                <p class="text-xs text-gray-500 font-mono">${code}</p>
                            </div>
                        </div>
                        <div class="border-t border-gray-100 pt-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-500">Status</span>
                                <span class="text-xs font-bold text-[#7A1F2B] bg-[#FAF5F0] px-3 py-1 rounded-full">Dalam Proses Pengerjaan</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">Estimasi Selesai</span>
                                <span class="text-sm font-semibold text-[#2D1E1E]">Besok, 14:00 WIB</span>
                            </div>
                        </div>
                        <p class="text-[11px] text-center text-gray-400 mt-4">Hubungi admin jika terdapat kesalahan data.</p>
                    `;
                } else {
                    resultBox.innerHTML = `
                        <div class="flex flex-col items-center justify-center py-4 text-center">
                            <div class="w-12 h-12 rounded-full bg-red-50 flex items-center justify-center text-red-500 mb-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </div>
                            <h4 class="text-[#2D1E1E] font-bold">Kode Tidak Ditemukan</h4>
                            <p class="text-sm text-gray-500 mt-1">Pastikan Anda memasukkan kode pesanan yang benar (Contoh: SDT-...).</p>
                        </div>
                    `;
                }

                // Animate in
                setTimeout(() => {
                    resultBox.classList.add('translate-y-0', 'opacity-100');
                    resultBox.classList.remove('translate-y-4', 'opacity-0');
                }, 50);

            }, 1200);
        }
    </script>

    <section id="tentang" class="pt-16 pb-12 sm:pt-20 sm:pb-16 bg-[#FAF5F0]">
        <div class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-8 grid md:grid-cols-2 gap-10 md:gap-16">
            <div class="reveal-on-scroll flex flex-col h-full">
                <div>
                    <span class="text-xs uppercase tracking-[0.2em] text-[#7A1F2B] font-semibold">Tentang Kami</span>
                    <h2 class="mt-3 text-3xl sm:text-4xl font-bold text-[#2D1E1E]">Tentang Sadita</h2>
                    <p class="mt-4 text-sm sm:text-base text-[#5b4747] leading-relaxed">
                        Sadita adalah layanan florist dan hadiah premium berbasis di Padang yang berfokus pada keindahan,
                        detail, dan makna dalam setiap karya. Kami percaya setiap momen spesial layak dirayakan dengan keindahan
                        yang tak terlupakan.
                    </p>
                </div>
                <div class="mt-auto pt-8 grid grid-cols-3 gap-3 sm:gap-4">
                    <div class="text-center p-3 sm:p-4 rounded-xl bg-white shadow-sm border border-gray-50">
                        <div class="text-xl sm:text-3xl font-bold text-[#7A1F2B]">3+</div>
                        <div class="text-[10px] sm:text-xs text-gray-500 mt-1 font-medium leading-tight">Tahun Pengalaman</div>
                    </div>
                    <div class="text-center p-3 sm:p-4 rounded-xl bg-white shadow-sm border border-gray-50">
                        <div class="text-xl sm:text-3xl font-bold text-[#7A1F2B]">500+</div>
                        <div class="text-[10px] sm:text-xs text-gray-500 mt-1 font-medium leading-tight">Pelanggan</div>
                    </div>
                    <div class="text-center p-3 sm:p-4 rounded-xl bg-white shadow-sm border border-gray-50">
                        <div class="text-xl sm:text-3xl font-bold text-[#7A1F2B]">4.9</div>
                        <div class="text-[10px] sm:text-xs text-gray-500 mt-1 font-medium leading-tight">Rating Bintang</div>
                    </div>
                </div>
            </div>
            <div id="kontak" class="reveal-on-scroll flex flex-col h-full">
                <span class="text-xs uppercase tracking-[0.2em] text-[#7A1F2B] font-semibold">Hubungi Kami</span>
                <h2 class="mt-3 text-3xl sm:text-4xl font-bold text-[#2D1E1E]">Kontak</h2>
                <div class="mt-auto pt-6 space-y-3">
                    <a href="https://wa.me/6289653090248" target="_blank"
                        class="contact-card flex items-center gap-4 rounded-2xl bg-white p-4 shadow-sm border border-gray-100 hover:shadow-md hover:border-[#7A1F2B]/30 transition-all duration-300">
                        <div
                            class="w-12 h-12 rounded-full bg-[#7A1F2B]/10 flex items-center justify-center text-[#7A1F2B] flex-shrink-0">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z" />
                            </svg>
                        </div>
                        <div>
                            <div class="font-bold text-sm text-[#2D1E1E]">WhatsApp</div>
                            <div class="text-[11px] sm:text-xs text-gray-500 mt-0.5">0896-5309-0248 · Chat langsung</div>
                        </div>
                    </a>
                    <a href="https://instagram.com/sadita.decor" target="_blank"
                        class="contact-card flex items-center gap-4 rounded-2xl bg-white p-4 shadow-sm border border-gray-100 hover:shadow-md hover:border-[#7A1F2B]/30 transition-all duration-300">
                        <div
                            class="w-12 h-12 rounded-full bg-[#7A1F2B]/10 flex items-center justify-center text-[#7A1F2B] flex-shrink-0">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" />
                            </svg>
                        </div>
                        <div class="overflow-hidden">
                            <div class="font-bold text-sm text-[#2D1E1E]">Instagram</div>
                            <div class="text-[11px] sm:text-xs text-gray-500 mt-0.5 truncate tracking-tight">@sadita.decor · @sadita.hantaran · @sadita.florist</div>
                        </div>
                    </a>
                    <a href="https://maps.google.com/?q=Padang+Sumatera+Barat" target="_blank"
                        class="contact-card flex items-center gap-4 rounded-2xl bg-white p-4 shadow-sm border border-gray-100 hover:shadow-md hover:border-[#7A1F2B]/30 transition-all duration-300">
                        <div
                            class="w-12 h-12 rounded-full bg-[#7A1F2B]/10 flex items-center justify-center text-[#7A1F2B] flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="font-bold text-sm text-[#2D1E1E]">Lokasi</div>
                            <div class="text-[11px] sm:text-xs text-gray-500 mt-0.5">Padang, Sumatera Barat</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

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
