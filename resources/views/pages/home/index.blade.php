@extends('layouts.app')

@section('content')
    <section id="beranda" class="relative w-full h-[100svh] overflow-hidden bg-[#18181b]">
        <!-- Infinite Horizontal Carousel Background -->
        <!-- Infinite Horizontal Carousel Background -->
        <div class="absolute inset-0 z-0 flex items-center overflow-hidden pointer-events-none opacity-40">
            <div class="flex flex-row items-center gap-3 sm:gap-5 animate-scroll-horizontal">
                <!-- Set A (8 Curated Best Photos) -->
                <div class="flex-shrink-0 w-32 h-48 sm:w-44 sm:h-64 lg:w-56 lg:h-80 rounded-xl sm:rounded-2xl border border-white/10 sm:border-2 overflow-hidden shadow-md sm:shadow-lg rotate-3">
                    <img src="/images/hantaran-1.jpg" alt="" class="w-full h-full object-cover">
                </div>
                <div class="flex-shrink-0 w-32 h-48 sm:w-44 sm:h-64 lg:w-56 lg:h-80 rounded-xl sm:rounded-2xl border border-white/10 sm:border-2 overflow-hidden shadow-md sm:shadow-lg -rotate-2">
                    <img src="/images/dekorasi-2.jpg" alt="" class="w-full h-full object-cover">
                </div>
                <div class="flex-shrink-0 w-32 h-48 sm:w-44 sm:h-64 lg:w-56 lg:h-80 rounded-xl sm:rounded-2xl border border-white/10 sm:border-2 overflow-hidden shadow-md sm:shadow-lg rotate-6">
                    <img src="/images/hantaran-2.jpg" alt="" class="w-full h-full object-cover">
                </div>
                <div class="flex-shrink-0 w-32 h-48 sm:w-44 sm:h-64 lg:w-56 lg:h-80 rounded-xl sm:rounded-2xl border border-white/10 sm:border-2 overflow-hidden shadow-md sm:shadow-lg -rotate-3">
                    <img src="/images/hero-2.jpg" alt="" class="w-full h-full object-cover">
                </div>
                <div class="flex-shrink-0 w-32 h-48 sm:w-44 sm:h-64 lg:w-56 lg:h-80 rounded-xl sm:rounded-2xl border border-white/10 sm:border-2 overflow-hidden shadow-md sm:shadow-lg rotate-2">
                    <img src="/images/hantaran-3.jpg" alt="" class="w-full h-full object-cover">
                </div>
                <div class="flex-shrink-0 w-32 h-48 sm:w-44 sm:h-64 lg:w-56 lg:h-80 rounded-xl sm:rounded-2xl border border-white/10 sm:border-2 overflow-hidden shadow-md sm:shadow-lg -rotate-6">
                    <img src="/images/dekorasi-1.jpg" alt="" class="w-full h-full object-cover">
                </div>
                <div class="flex-shrink-0 w-32 h-48 sm:w-44 sm:h-64 lg:w-56 lg:h-80 rounded-xl sm:rounded-2xl border border-white/10 sm:border-2 overflow-hidden shadow-md sm:shadow-lg rotate-3">
                    <img src="/images/hantaran-4.jpg" alt="" class="w-full h-full object-cover">
                </div>
                <div class="flex-shrink-0 w-32 h-48 sm:w-44 sm:h-64 lg:w-56 lg:h-80 rounded-xl sm:rounded-2xl border border-white/10 sm:border-2 overflow-hidden shadow-md sm:shadow-lg -rotate-2">
                    <img src="/images/papan-2.jpg" alt="" class="w-full h-full object-cover">
                </div>
                <!-- Set B (Duplicate for seamless infinite loop) -->
                <div class="flex-shrink-0 w-32 h-48 sm:w-44 sm:h-64 lg:w-56 lg:h-80 rounded-xl sm:rounded-2xl border border-white/10 sm:border-2 overflow-hidden shadow-md sm:shadow-lg rotate-3">
                    <img src="/images/hantaran-1.jpg" alt="" class="w-full h-full object-cover">
                </div>
                <div class="flex-shrink-0 w-32 h-48 sm:w-44 sm:h-64 lg:w-56 lg:h-80 rounded-xl sm:rounded-2xl border border-white/10 sm:border-2 overflow-hidden shadow-md sm:shadow-lg -rotate-2">
                    <img src="/images/dekorasi-2.jpg" alt="" class="w-full h-full object-cover">
                </div>
                <div class="flex-shrink-0 w-32 h-48 sm:w-44 sm:h-64 lg:w-56 lg:h-80 rounded-xl sm:rounded-2xl border border-white/10 sm:border-2 overflow-hidden shadow-md sm:shadow-lg rotate-6">
                    <img src="/images/hantaran-2.jpg" alt="" class="w-full h-full object-cover">
                </div>
                <div class="flex-shrink-0 w-32 h-48 sm:w-44 sm:h-64 lg:w-56 lg:h-80 rounded-xl sm:rounded-2xl border border-white/10 sm:border-2 overflow-hidden shadow-md sm:shadow-lg -rotate-3">
                    <img src="/images/hero-2.jpg" alt="" class="w-full h-full object-cover">
                </div>
                <div class="flex-shrink-0 w-32 h-48 sm:w-44 sm:h-64 lg:w-56 lg:h-80 rounded-xl sm:rounded-2xl border border-white/10 sm:border-2 overflow-hidden shadow-md sm:shadow-lg rotate-2">
                    <img src="/images/hantaran-3.jpg" alt="" class="w-full h-full object-cover">
                </div>
                <div class="flex-shrink-0 w-32 h-48 sm:w-44 sm:h-64 lg:w-56 lg:h-80 rounded-xl sm:rounded-2xl border border-white/10 sm:border-2 overflow-hidden shadow-md sm:shadow-lg -rotate-6">
                    <img src="/images/dekorasi-1.jpg" alt="" class="w-full h-full object-cover">
                </div>
                <div class="flex-shrink-0 w-32 h-48 sm:w-44 sm:h-64 lg:w-56 lg:h-80 rounded-xl sm:rounded-2xl border border-white/10 sm:border-2 overflow-hidden shadow-md sm:shadow-lg rotate-3">
                    <img src="/images/hantaran-4.jpg" alt="" class="w-full h-full object-cover">
                </div>
                <div class="flex-shrink-0 w-32 h-48 sm:w-44 sm:h-64 lg:w-56 lg:h-80 rounded-xl sm:rounded-2xl border border-white/10 sm:border-2 overflow-hidden shadow-md sm:shadow-lg -rotate-2">
                    <img src="/images/papan-2.jpg" alt="" class="w-full h-full object-cover">
                </div>
            </div>
        </div>

        <!-- Dimmed Gradient Overlay - stronger on mobile for readability -->
        <div class="absolute inset-0 bg-gradient-to-b from-[#18181b]/10 via-[#18181b]/50 to-[#18181b]/95 sm:from-transparent sm:via-[#18181b]/40 sm:to-[#18181b]/80 z-10 pointer-events-none"></div>

        <div class="hero-content relative z-20 h-full flex items-center justify-center pt-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                <div class="max-w-2xl mx-auto text-center">
                    <span class="inline-block text-[10px] sm:text-sm uppercase tracking-[0.25em] sm:tracking-[0.3em] text-[#E8C87A] font-medium mb-3 sm:mb-4 reveal-on-scroll">Florist & Gift · Padang</span>
                    <h1 class="text-3xl sm:text-5xl lg:text-7xl xl:text-8xl font-bold text-white leading-[1.1] sm:leading-[1.05] reveal-on-scroll"
                        style="font-family:'Playfair Display',serif; text-shadow: 0 2px 20px rgba(0,0,0,0.6);">
                        Papan Bunga,<br>Hantaran &amp; <em class="italic text-[#E8C87A]">Dekorasi</em>
                    </h1>
                    <p class="mt-3 sm:mt-5 text-sm sm:text-base lg:text-lg text-white/70 leading-relaxed max-w-sm sm:max-w-lg mx-auto reveal-on-scroll" style="text-shadow: 0 1px 8px rgba(0,0,0,0.5);">
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
                    <div class="mt-6 sm:mt-10 inline-flex items-center gap-4 sm:gap-8 px-5 sm:px-8 py-3 sm:py-4 rounded-xl sm:rounded-2xl bg-white/5 backdrop-blur-md border border-white/10 reveal-on-scroll">
                        <div class="text-center">
                            <div class="counter-number text-xl sm:text-2xl lg:text-3xl font-bold text-white" data-target="500">0+</div>
                            <div class="text-[9px] sm:text-[10px] text-white/50 mt-0.5 uppercase tracking-wider">Pesanan</div>
                        </div>
                        <div class="w-px h-6 sm:h-8 bg-white/15"></div>
                        <div class="text-center">
                            <div class="counter-number text-xl sm:text-2xl lg:text-3xl font-bold text-white" data-target="200">0+</div>
                            <div class="text-[9px] sm:text-[10px] text-white/50 mt-0.5 uppercase tracking-wider">Klien Puas</div>
                        </div>
                        <div class="w-px h-6 sm:h-8 bg-white/15"></div>
                        <div class="text-center">
                            <div class="text-xl sm:text-2xl lg:text-3xl font-bold text-[#E8C87A]">4.9★</div>
                            <div class="text-[9px] sm:text-[10px] text-white/50 mt-0.5 uppercase tracking-wider">Rating</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="absolute bottom-8 sm:bottom-12 left-1/2 -translate-x-1/2 z-20 flex flex-col items-center gap-2 animate-bounce">
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
                <p class="mt-3 text-sm text-gray-500 max-w-md mx-auto">Berikan kesan tak terlupakan di setiap momen bahagia Anda</p>
            </div>

            <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8 max-w-6xl mx-auto px-4">
                @php($categories = [
                    ['Papan Ucapan', 'Mulai Rp 85rb', 'Standing board & mirror elegan untuk momen berharga', '/images/cat-papan-ucapan.jpg', '#greeting-board'], 
                    ['Hantaran', 'Mulai Rp 30rb', 'Seserahan & gift box premium dengan detail cantik', '/images/cat-hantaran.jpg', '#hantaran'], 
                    ['Dekorasi', 'Mulai Rp 500rb', 'Wujudkan dekorasi impian untuk hari bahagia Anda', '/images/cat-dekorasi.jpg', '#dekorasi']
                ])
                @foreach ($categories as $i => [$title, $price, $desc, $img, $link])
                    <div class="reveal-on-scroll {{ $i === 1 ? 'md:-translate-y-8' : '' }}" style="animation-delay: {{ $i * 150 }}ms">
                        <a href="{{ $link }}" class="category-card group relative block h-full">
                            <div class="relative overflow-hidden rounded-[2rem] aspect-[3/4] shadow-xl border border-gray-100 transition-all duration-500 group-hover:shadow-2xl group-hover:border-[#E8C87A]/50 bg-gray-100">
                                <!-- Image -->
                                <img src="{{ $img }}" alt="{{ $title }}"
                                    class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110"
                                    loading="lazy">
                                <!-- Gradient overlay with maroon hover -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent transition-colors duration-500 group-hover:from-[#7A1F2B]/95 group-hover:via-[#7A1F2B]/60"></div>
                                
                                <!-- Content -->
                                <div class="absolute inset-0 flex flex-col justify-end p-6 sm:p-8">
                                    <div class="transform transition-transform duration-500 group-hover:-translate-y-2">
                                        <div class="flex items-start justify-between">
                                            <div>
                                                <h3 class="text-2xl sm:text-3xl font-bold text-white tracking-wide" style="font-family:'Playfair Display',serif">{{ $title }}</h3>
                                                <span class="inline-block mt-2 px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-white text-xs font-semibold tracking-wider">{{ $price }}</span>
                                            </div>
                                            <div class="w-10 h-10 rounded-full bg-[#E8C87A] flex items-center justify-center opacity-0 -translate-x-4 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-500">
                                                <svg class="w-5 h-5 text-[#2D1E1E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <p class="text-sm sm:text-base text-white/80 mt-4 leading-relaxed">{{ $desc }}</p>
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
        <div class="max-w-7xl mx-auto px-4 flex justify-between sm:justify-center sm:gap-12 items-center text-[10px] sm:text-xs font-semibold tracking-wider uppercase whitespace-nowrap overflow-x-auto scrollbar-hide">
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
            'items' => [
                ['Papan Standing Mirror Premium', 'Rp 150.000', '/images/papan-1.jpg', 'Custom'],
                ['Papan Congratulations Eksklusif', 'Rp 120.000', '/images/papan-2.jpg', 'Terlaris'],
                ['Papan Rustic Custom', 'Rp 100.000', '/images/papan-3.jpg', 'Custom'],
                ['Papan Ucapan Selamatan', 'Rp 85.000', '/images/papan-4.jpg', 'Ready Stock'],
                ['Standing Mirror Besar', 'Rp 200.000', '/images/papan-5.jpg', 'Premium'],
            ],
        ],
        'hantaran' => [
            'title' => 'Hantaran',
            'subtitle' => 'Seserahan & Gift',
            'desc' => 'Persembahan terbaik untuk hari paling bahagia',
            'items' => [
                ['Bridesmaid Gift Box', 'Rp 45.000', '/images/hantaran-1.jpg', 'Custom'],
                ['Set Hantaran Nikah', 'Rp 65.000', '/images/hantaran-2.jpg', 'Terlaris'],
                ['Hantaran Premium Wedding', 'Rp 85.000', '/images/hantaran-3.jpg', 'Premium'],
                ['Seserahan Adat Minang', 'Rp 75.000', '/images/hantaran-4.jpg', 'Custom'],
                ['Hantaran Gold Edition', 'Rp 100.000', '/images/hantaran-5.jpg', 'Premium'],
            ],
        ],
        'dekorasi' => [
            'title' => 'Dekorasi',
            'subtitle' => 'Event Decoration',
            'desc' => 'Ubah ruangan biasa menjadi momen luar biasa',
            'items' => [
                ['Dekorasi Lamaran', 'Rp 400.000', '/images/dekorasi-1.jpg', 'Custom'],
                ['Dekorasi Tunangan', 'Rp 500.000', '/images/dekorasi-2.jpg', 'Terlaris'],
                ['Table Setting Premium', 'Rp 350.000', '/images/dekorasi-3.jpg', 'Custom'],
                ['Dekorasi Grand Opening', 'Rp 850.000', '/images/dekorasi-4.jpg', 'Premium'],
                ['Dekorasi Akad Nikah', 'Rp 1.500.000', '/images/dekorasi-5.jpg', 'Premium'],
            ],
        ],
    ];
    ?>

    @foreach ($products as $key => $category)
        <section id="{{ $key }}" class="pt-10 pb-4 sm:pt-14 sm:pb-8 {{ $loop->odd ? 'bg-[#FAF5F0]' : 'bg-white' }}">
            @if(!$loop->first)
                <div style="width:80px; height:2px; background:#C9A84C; margin: 0 auto 40px; opacity: 0.5;"></div>
            @endif
            <div class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-8">
                <div class="flex items-end justify-between mb-6 reveal-on-scroll">
                    <div>
                        <span class="text-[10px] sm:text-xs uppercase tracking-[0.15em] text-[#7A1F2B]/70 font-semibold">{{ $category['subtitle'] }}</span>
                        <h2 class="text-2xl sm:text-3xl font-bold text-[#2D1E1E]">{{ $category['title'] }}</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">{{ $category['desc'] }}</p>
                    </div>
                    <a href="#" class="text-xs sm:text-sm text-[#7A1F2B] font-semibold hover:underline whitespace-nowrap">Lihat Semua →</a>
                </div>

                <div class="product-scroll-container flex gap-4 overflow-x-auto pb-8 pt-2 snap-x snap-mandatory scrollbar-hide reveal-on-scroll">
                    @foreach ($category['items'] as $j => [$name, $price, $img, $tag])
                        <div class="group product-card min-w-[150px] sm:min-w-[180px] max-w-[150px] sm:max-w-[180px] flex-shrink-0 snap-start rounded-2xl overflow-hidden bg-white border border-gray-100 transition-all duration-300 hover:-translate-y-2 hover:shadow-[0_12px_30px_rgba(122,31,43,0.15)] flex flex-col">
                            <div class="relative h-40 sm:h-48 w-full overflow-hidden flex-shrink-0">
                                <img src="{{ $img }}" alt="{{ $name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy">
                                @if($tag)
                                    <span class="absolute top-2 left-2 px-2.5 py-0.5 bg-white/95 backdrop-blur-sm text-[#7A1F2B] text-[9px] sm:text-[10px] font-bold rounded-full shadow-sm">{{ $tag }}</span>
                                @endif
                            </div>
                            <div class="p-3 sm:p-4 flex flex-col flex-1 bg-white relative z-10">
                                <div class="flex text-[#C9A84C] text-[10px] mb-1 tracking-widest">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                                <h4 class="text-xs sm:text-sm font-semibold text-[#2D1E1E] line-clamp-2 leading-tight flex-1" style="min-height: 2.5rem;">{{ $name }}</h4>
                                <div class="mt-2 text-[10px] sm:text-[11px] text-gray-500">Mulai <span class="font-extrabold text-[#7A1F2B] text-xs sm:text-sm">{{ $price }}</span></div>
                                <a href="https://wa.me/6289653090248?text=Halo+Sadita%2C+saya+tertarik+dengan+{{ urlencode($name) }}"
                                    target="_blank"
                                    class="mt-3 w-full py-1.5 sm:py-2 rounded-lg border border-[#7A1F2B] text-[#7A1F2B] text-[10px] sm:text-xs font-semibold text-center block transition-colors duration-300 group-hover:bg-[#7A1F2B] group-hover:text-white">
                                    Hubungi Kami
                                </a>
                            </div>
                        </div>
                    @endforeach
                    
                    <!-- 6th Card CTA (WhatsApp) -->
                    <div class="group min-w-[150px] sm:min-w-[180px] max-w-[150px] sm:max-w-[180px] flex-shrink-0 snap-start rounded-2xl overflow-hidden bg-[#7A1F2B] text-white transition-all duration-300 hover:-translate-y-2 hover:shadow-[0_12px_30px_rgba(122,31,43,0.3)] flex flex-col justify-center items-center text-center p-4 sm:p-5 cursor-pointer relative" onclick="window.open('https://wa.me/6289653090248?text=Halo+Sadita%2C+saya+ingin+konsultasi+mengenai+pesanan+saya', '_blank')">
                        <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-white/10 border border-white/20 flex items-center justify-center mb-3 sm:mb-4 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7 text-[#E8C87A]" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/>
                            </svg>
                        </div>
                        <h4 class="text-xs sm:text-sm font-bold tracking-wide leading-snug">Bingung Pilih<br>Produk?</h4>
                        <p class="text-[9px] sm:text-[10px] text-white/80 mt-2 mb-3 leading-relaxed">Konsultasi gratis via WhatsApp</p>
                        <span class="text-[9px] sm:text-[10px] font-bold text-[#7A1F2B] bg-[#E8C87A] px-3 py-1.5 rounded-full w-full block group-hover:bg-white transition-colors">Chat Sekarang</span>
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
                @foreach([
                    [
                        '01', 'Kualitas Premium', 
                        'Bahan material terbaik dengan detail pengerjaan yang teliti untuk hasil yang elegan dan memukau.', 
                        '/images/why-premium.png'
                    ],
                    [
                        '02', 'Proses Cepat', 
                        'Pengerjaan profesional yang responsif dan tepat waktu untuk momen berharga Anda.', 
                        '/images/why-fast.png'
                    ],
                    [
                        '03', 'Gratis Ongkir', 
                        'Layanan pengiriman aman dan gratis untuk seluruh wilayah Padang dan sekitarnya.', 
                        '/images/why-delivery.png'
                    ],
                    [
                        '04', 'Custom Request', 
                        'Kebebasan berekspresi. Desain dapat disesuaikan sepenuhnya dengan keinginan Anda.', 
                        '/images/why-custom.png'
                    ]
                ] as $index => $feature)
                <div class="group relative bg-white border border-gray-100 rounded-2xl p-6 sm:p-8 hover:shadow-[0_12px_30px_rgba(122,31,43,0.08)] transition-all duration-300 reveal-on-scroll flex flex-col h-full min-h-[280px]">
                    <div class="absolute top-4 right-6 text-6xl font-playfair font-black text-gray-200 group-hover:text-[#F3E8D6] transition-colors duration-300 pointer-events-none">{{ $feature[0] }}</div>
                    
                    <img src="{{ $feature[3] }}" alt="{{ $feature[1] }}" class="w-[120px] h-[120px] object-contain block mb-6 relative z-10 group-hover:scale-110 transition-transform duration-300 drop-shadow-sm flex-shrink-0" loading="lazy">
                    
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
                <button class="filter-btn active px-5 py-2 rounded-full text-xs sm:text-sm font-semibold transition-colors duration-300 bg-[#7A1F2B] text-white" data-filter="all">Semua</button>
                <button class="filter-btn px-5 py-2 rounded-full text-xs sm:text-sm font-semibold transition-colors duration-300 bg-white text-gray-500 border border-gray-200 hover:bg-gray-50" data-filter="dekorasi">Dekorasi</button>
                <button class="filter-btn px-5 py-2 rounded-full text-xs sm:text-sm font-semibold transition-colors duration-300 bg-white text-gray-500 border border-gray-200 hover:bg-gray-50" data-filter="hantaran">Hantaran</button>
                <button class="filter-btn px-5 py-2 rounded-full text-xs sm:text-sm font-semibold transition-colors duration-300 bg-white text-gray-500 border border-gray-200 hover:bg-gray-50" data-filter="papan">Papan Ucapan</button>
            </div>

            <div class="masonry-grid mt-10" id="gallery-container">
                @php($galleryImages = [
                    ['papan-1.jpg',    'tall',   'papan',    '🌸 Papan Ucapan Premium'],
                    ['hantaran-1.jpg', 'normal', 'hantaran', '🎁 Hantaran Seserahan'],
                    ['dekorasi-1.jpg', 'normal', 'dekorasi', '✨ Dekorasi Pernikahan'],
                    ['papan-2.jpg',    'wide',   'papan',    '🌸 Papan Bunga Rustic'],
                    ['hantaran-3.jpg', 'tall',   'hantaran', '🎁 Hantaran Eksklusif'],
                    ['dekorasi-3.jpg', 'normal', 'dekorasi', '✨ Dekorasi Lamaran'],
                    ['papan-3.jpg',    'normal', 'papan',    '🌸 Papan Congratulations'],
                    ['hantaran-4.jpg', 'normal', 'hantaran', '🎁 Hantaran Adat'],
                    ['dekorasi-4.jpg', 'tall',   'dekorasi', '✨ Dekorasi Premium'],
                    ['papan-4.jpg',    'wide',   'papan',    '🌸 Standing Mirror'],
                    ['hantaran-5.jpg', 'wide',   'hantaran', '🎁 Hantaran Gold'],
                    ['dekorasi-5.jpg', 'normal', 'dekorasi', '✨ Grand Opening'],
                    ['papan-5.jpg',    'normal', 'papan',    '🌸 Ucapan Custom'],
                ])
                @foreach ($galleryImages as $i => [$gImg, $gSize, $gCat, $gLabel])
                    <div class="masonry-item masonry-{{ $gSize }} reveal-on-scroll" data-category="{{ $gCat }}" style="animation-delay:{{ $i * 50 }}ms">
                        <div class="gallery-card group relative overflow-hidden rounded-2xl shadow-sm hover:shadow-xl transition-shadow duration-300 cursor-pointer w-full h-full">
                            <img src="/images/{{ $gImg }}" alt="{{ $gLabel }}"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy">
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/50 transition-all duration-500 flex flex-col items-center justify-center gap-2 opacity-0 group-hover:opacity-100">
                                <span class="text-[#E8C87A] text-[10px] sm:text-xs font-bold tracking-widest uppercase transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">{{ $gLabel }}</span>
                                <span class="text-white text-xs sm:text-sm font-medium border-b border-white/50 pb-0.5 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500 delay-75">Lihat Detail</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- CTA Lihat Semua -->
            <div class="mt-12 sm:mt-16 text-center reveal-on-scroll">
                <a href="https://instagram.com/sadita.decor" target="_blank" class="inline-flex items-center gap-2 px-8 py-3 sm:py-4 rounded-full border border-[#7A1F2B] text-[#7A1F2B] text-sm font-bold hover:bg-[#7A1F2B] hover:text-white transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                    Follow @sadita.decor untuk lebih banyak karya
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
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
                        b.classList.add('bg-white', 'text-gray-500', 'border', 'border-gray-200', 'hover:bg-gray-50');
                    });
                    
                    // Activate clicked button
                    btn.classList.add('bg-[#7A1F2B]', 'text-white', 'active');
                    btn.classList.remove('bg-white', 'text-gray-500', 'border', 'border-gray-200', 'hover:bg-gray-50');

                    const filterValue = btn.getAttribute('data-filter');

                    masonryItems.forEach(item => {
                        if (filterValue === 'all' || item.getAttribute('data-category') === filterValue) {
                            item.style.display = 'block';
                            // Quick hack to re-trigger masonry layout if needed, though pure CSS column masonry handles display:none beautifully
                            setTimeout(() => { item.style.opacity = '1'; }, 50);
                        } else {
                            item.style.opacity = '0';
                            setTimeout(() => { item.style.display = 'none'; }, 300);
                        }
                    });
                });
            });
        });
    </script>

    <section id="cara-pesan" class="py-16 sm:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-8">
            <div class="text-center reveal-on-scroll">
                <span class="text-xs uppercase tracking-[0.2em] text-[#7A1F2B] font-semibold">Mudah & Cepat</span>
                <h2 class="mt-3 text-3xl sm:text-4xl font-bold text-[#2D1E1E]">Cara Pesan</h2>
            </div>
            <div class="mt-10 grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-6">
                @php($steps = [['01', 'Pilih Produk', 'Jelajahi koleksi dan pilih yang sesuai'], ['02', 'Isi Detail', 'Lengkapi detail pesanan & alamat'], ['03', 'Bayar', 'Lakukan pembayaran yang aman'], ['04', 'Lacak Pesanan', 'Pantau status pesanan Anda']])
                @foreach ($steps as $i => [$num, $stepTitle, $stepDesc])
                    <div class="step-card text-center p-4 sm:p-6 rounded-2xl bg-[#FAF5F0] reveal-on-scroll hover:shadow-lg transition-all duration-300"
                        style="animation-delay:{{ $i * 100 }}ms">
                        <div
                            class="w-10 h-10 sm:w-12 sm:h-12 mx-auto rounded-full bg-[#7A1F2B] text-white grid place-items-center font-bold text-sm sm:text-base">
                            {{ $num }}</div>
                        <h3 class="mt-3 text-sm sm:text-base font-bold text-[#2D1E1E]">{{ $stepTitle }}</h3>
                        <p class="mt-2 text-[10px] sm:text-xs text-gray-500 leading-relaxed">{{ $stepDesc }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="lacak" class="py-16 sm:py-20 bg-gradient-to-br from-[#7A1F2B] to-[#4a1119] text-white">
        <div class="max-w-2xl mx-auto px-5 text-center">
            <div class="reveal-on-scroll">
                <h2 class="text-3xl sm:text-4xl font-bold">Lacak Pesanan</h2>
                <p class="mt-3 text-sm text-white/70">Masukkan kode pesanan untuk melihat status terkini.</p>
                <div class="mt-8 flex flex-col sm:flex-row gap-3">
                    <input
                        class="flex-1 rounded-full border border-white/20 bg-white/10 backdrop-blur-sm px-5 py-3 text-white placeholder-white/40 text-sm focus:outline-none focus:ring-2 focus:ring-[#E8C87A]"
                        placeholder="SDT-20260411-001">
                    <button class="btn-track px-6 py-3 rounded-full bg-[#E8C87A] text-[#2D1E1E] font-semibold text-sm">
                        Lacak Sekarang
                    </button>
                </div>
            </div>
        </div>
    </section>

    <section id="tentang" class="py-16 sm:py-20 bg-[#FAF5F0]">
        <div class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-8 grid md:grid-cols-2 gap-10">
            <div class="reveal-on-scroll">
                <span class="text-xs uppercase tracking-[0.2em] text-[#7A1F2B] font-semibold">Tentang Kami</span>
                <h2 class="mt-3 text-3xl sm:text-4xl font-bold text-[#2D1E1E]">Tentang Sadita</h2>
                <p class="mt-4 text-sm sm:text-base text-[#5b4747] leading-relaxed">
                    Sadita adalah layanan florist dan hadiah premium berbasis di Padang yang berfokus pada keindahan,
                    detail, dan makna dalam setiap karya. Kami percaya setiap momen spesial layak dirayakan dengan keindahan
                    yang tak terlupakan.
                </p>
                <div class="mt-6 grid grid-cols-3 gap-4">
                    <div class="text-center p-3 rounded-xl bg-white shadow-sm">
                        <div class="text-xl sm:text-2xl font-bold text-[#7A1F2B]">3+</div>
                        <div class="text-[10px] sm:text-xs text-gray-500 mt-1">Tahun Pengalaman</div>
                    </div>
                    <div class="text-center p-3 rounded-xl bg-white shadow-sm">
                        <div class="text-xl sm:text-2xl font-bold text-[#7A1F2B]">500+</div>
                        <div class="text-[10px] sm:text-xs text-gray-500 mt-1">Pesanan Selesai</div>
                    </div>
                    <div class="text-center p-3 rounded-xl bg-white shadow-sm">
                        <div class="text-xl sm:text-2xl font-bold text-[#7A1F2B]">4.9</div>
                        <div class="text-[10px] sm:text-xs text-gray-500 mt-1">Rating</div>
                    </div>
                </div>
            </div>
            <div id="kontak" class="reveal-on-scroll">
                <span class="text-xs uppercase tracking-[0.2em] text-[#7A1F2B] font-semibold">Hubungi Kami</span>
                <h2 class="mt-3 text-3xl sm:text-4xl font-bold text-[#2D1E1E]">Kontak</h2>
                <div class="mt-6 space-y-3">
                    <a href="https://wa.me/6289653090248" target="_blank"
                        class="contact-card flex items-center gap-4 rounded-2xl bg-white p-4 shadow-sm border border-gray-100 hover:shadow-md hover:border-[#25D366]/30 transition-all duration-300">
                        <div class="w-10 h-10 rounded-full bg-[#25D366]/10 flex items-center justify-center text-[#25D366] text-lg">💬</div>
                        <div>
                            <div class="font-semibold text-sm text-[#2D1E1E]">WhatsApp</div>
                            <div class="text-xs text-gray-500">0896-5309-0248 · Chat langsung</div>
                        </div>
                    </a>
                    <a href="https://instagram.com/sadita.florist" target="_blank"
                        class="contact-card flex items-center gap-4 rounded-2xl bg-white p-4 shadow-sm border border-gray-100 hover:shadow-md hover:border-[#E4405F]/30 transition-all duration-300">
                        <div class="w-10 h-10 rounded-full bg-[#E4405F]/10 flex items-center justify-center text-[#E4405F] text-lg">📸</div>
                        <div>
                            <div class="font-semibold text-sm text-[#2D1E1E]">Instagram</div>
                            <div class="text-xs text-gray-500">@sadita.florist · @sadita.hantaran · @sadita.decor</div>
                        </div>
                    </a>
                    <a href="https://maps.google.com/?q=Padang+Sumatera+Barat" target="_blank"
                        class="contact-card flex items-center gap-4 rounded-2xl bg-white p-4 shadow-sm border border-gray-100 hover:shadow-md hover:border-[#7A1F2B]/30 transition-all duration-300">
                        <div class="w-10 h-10 rounded-full bg-[#7A1F2B]/10 flex items-center justify-center text-[#7A1F2B] text-lg">📍</div>
                        <div>
                            <div class="font-semibold text-sm text-[#2D1E1E]">Lokasi</div>
                            <div class="text-xs text-gray-500">Padang, Sumatera Barat</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <style>
        @keyframes scroll-horizontal {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        .animate-scroll-horizontal {
            animation: scroll-horizontal 35s linear infinite;
            will-change: transform;
        }
    </style>
@endsection
