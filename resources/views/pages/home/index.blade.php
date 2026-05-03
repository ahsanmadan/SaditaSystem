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

    <section class="py-16 sm:py-20 bg-[#2D1E1E] text-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-8">
            <div class="text-center reveal-on-scroll">
                <span class="text-xs uppercase tracking-[0.2em] text-[#E8C87A] font-semibold">Mengapa Kami</span>
                <h2 class="mt-3 text-3xl sm:text-4xl font-bold">Kenapa Pilih Sadita?</h2>
            </div>
            <div class="mt-10 grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-6">

                <div class="reason-card rounded-2xl bg-white/5 backdrop-blur-sm border border-white/10 p-4 sm:p-6 text-center reveal-on-scroll hover:bg-white/10 transition-all duration-300" style="animation-delay:0ms">
                    <div class="flex justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-[#E8C87A]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456Z" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-sm sm:text-base font-bold">Kualitas Premium</h3>
                    <p class="mt-2 text-[10px] sm:text-xs text-white/60 leading-relaxed">Material terbaik &amp; bunga segar pilihan</p>
                </div>

                <div class="reason-card rounded-2xl bg-white/5 backdrop-blur-sm border border-white/10 p-4 sm:p-6 text-center reveal-on-scroll hover:bg-white/10 transition-all duration-300" style="animation-delay:100ms">
                    <div class="flex justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-[#E8C87A]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-sm sm:text-base font-bold">Proses Cepat</h3>
                    <p class="mt-2 text-[10px] sm:text-xs text-white/60 leading-relaxed">Pengerjaan profesional tepat waktu</p>
                </div>

                <div class="reason-card rounded-2xl bg-white/5 backdrop-blur-sm border border-white/10 p-4 sm:p-6 text-center reveal-on-scroll hover:bg-white/10 transition-all duration-300" style="animation-delay:200ms">
                    <div class="flex justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-[#E8C87A]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-sm sm:text-base font-bold">Gratis Ongkir</h3>
                    <p class="mt-2 text-[10px] sm:text-xs text-white/60 leading-relaxed">Free delivery area Padang &amp; sekitarnya</p>
                </div>

                <div class="reason-card rounded-2xl bg-white/5 backdrop-blur-sm border border-white/10 p-4 sm:p-6 text-center reveal-on-scroll hover:bg-white/10 transition-all duration-300" style="animation-delay:300ms">
                    <div class="flex justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-[#E8C87A]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.43l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-sm sm:text-base font-bold">Custom Request</h3>
                    <p class="mt-2 text-[10px] sm:text-xs text-white/60 leading-relaxed">Desain sesuai keinginan Anda</p>
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

            <div class="masonry-grid mt-10">
                @php($galleryImages = [
                    ['papan-1.jpg',    'tall'],
                    ['hantaran-1.jpg', 'normal'],
                    ['dekorasi-1.jpg', 'normal'],
                    ['papan-2.jpg',    'wide'],
                    ['gallery-1.jpg',  'tall'],
                    ['hantaran-2.jpg', 'normal'],
                    ['dekorasi-2.jpg', 'normal'],
                    ['papan-3.jpg',    'normal'],
                    ['gallery-2.jpg',  'wide'],
                    ['hantaran-3.jpg', 'tall'],
                    ['dekorasi-3.jpg', 'normal'],
                    ['gallery-3.jpg',  'normal'],
                    ['papan-4.jpg',    'wide'],
                    ['hantaran-4.jpg', 'normal'],
                    ['dekorasi-4.jpg', 'tall'],
                    ['gallery-4.jpg',  'normal'],
                    ['papan-5.jpg',    'normal'],
                    ['hantaran-5.jpg', 'wide'],
                    ['dekorasi-5.jpg', 'normal'],
                    ['gallery-5.jpg',  'tall'],
                    ['gallery-6.jpg',  'normal'],
                    ['gallery-7.jpg',  'normal'],
                    ['gallery-8.jpg',  'wide'],
                ])
                @foreach ($galleryImages as $i => [$gImg, $gSize])
                    <div class="masonry-item masonry-{{ $gSize }} reveal-on-scroll"
                        style="animation-delay:{{ $i * 80 }}ms">
                        <div class="gallery-card group">
                            <img src="/images/{{ $gImg }}" alt="Galeri Sadita {{ $i + 1 }}"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                loading="lazy">
                            <div
                                class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-all duration-500 flex items-center justify-center">
                                <span
                                    class="text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300 text-sm font-medium">Lihat
                                    Detail</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

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
