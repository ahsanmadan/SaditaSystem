@extends('layouts.app')

@section('content')
    <section id="beranda" class="relative h-screen overflow-hidden">
        <div class="hero-slide active" style="background-image:url('/images/hero-1.png')"></div>
        <div class="hero-slide" style="background-image:url('/images/hero-2.png')"></div>
        <div class="hero-slide" style="background-image:url('/images/hero-3.png')"></div>

        <div class="absolute inset-0 bg-gradient-to-b from-black/50 via-black/30 to-black/60 z-10"></div>

        <div class="hero-content relative z-20 h-full flex items-center">
            <div class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-8 w-full">
                <div class="max-w-xl">
                    <h1 class="mt-5 text-4xl sm:text-5xl lg:text-6xl font-bold text-white leading-[1.1] reveal-on-scroll"
                        style="font-family:'Playfair Display',serif">
                        Seni Memberi<br>yang <em class="italic text-[#E8C87A]">Bermakna</em>
                    </h1>
                    <p class="mt-4 text-base sm:text-lg text-white/80 leading-relaxed max-w-md reveal-on-scroll">
                        Bunga segar, papan ucapan, hantaran, dan dekorasi elegan untuk setiap momen spesial Anda.
                    </p>
                    <div class="mt-7 flex flex-wrap gap-3 reveal-on-scroll">
                        <a href="#kategori"
                            class="btn-primary px-6 py-3 rounded-full bg-[#7A1F2B] text-white text-sm font-semibold">
                            Pesan Sekarang
                        </a>
                        <a href="#galeri"
                            class="btn-outline px-6 py-3 rounded-full border border-white/40 text-white text-sm font-semibold backdrop-blur-sm">
                            Lihat Koleksi
                        </a>
                    </div>
                    <div class="mt-8 flex gap-6 sm:gap-8 reveal-on-scroll">
                        <div class="text-center">
                            <div class="counter-number text-2xl sm:text-3xl font-bold text-white" data-target="500">0+</div>
                            <div class="text-xs text-white/60 mt-1">Pesanan</div>
                        </div>
                        <div class="text-center">
                            <div class="counter-number text-2xl sm:text-3xl font-bold text-white" data-target="200">0+</div>
                            <div class="text-xs text-white/60 mt-1">Klien Puas</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl sm:text-3xl font-bold text-[#E8C87A]">4.9★</div>
                            <div class="text-xs text-white/60 mt-1">Rating</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 z-20 flex flex-col items-center gap-2 animate-bounce">
            <span class="text-white/50 text-xs tracking-widest uppercase">Scroll</span>
            <svg class="w-4 h-4 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
            </svg>
        </div>
    </section>


    <section id="kategori" class="py-16 sm:py-20 bg-[#FFFDFB]">
        <div class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-8">
            <div class="text-center reveal-on-scroll">
                <span class="text-xs uppercase tracking-[0.2em] text-[#7A1F2B] font-semibold">Koleksi Kami</span>
                <h2 class="mt-3 text-3xl sm:text-4xl font-bold text-[#2D1E1E]">Kategori Produk</h2>
                <p class="mt-3 text-sm text-gray-500 max-w-md mx-auto">Temukan hadiah sempurna untuk setiap momen berharga
                    dalam hidup Anda</p>
            </div>

            <div class="mt-10 grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-5">
                @php($categories = [['Papan Ucapan', 'Ucapan elegan untuk momen penting', '/images/product-greeting.png', '#greeting-board'], ['Bucket Bunga', 'Rangkaian bunga segar penuh makna', '/images/product-bouquet.png', '#florist-bucket'], ['Hantaran', 'Hantaran cantik penuh detail', '/images/product-hantaran.png', '#hantaran'], ['Dekorasi', 'Dekorasi custom sesuai konsep Anda', '/images/product-decoration.png', '#dekorasi']])
                @foreach ($categories as $i => [$title, $desc, $img, $link])
                    <a href="{{ $link }}" class="category-card group reveal-on-scroll"
                        style="animation-delay: {{ $i * 100 }}ms">
                        <div class="relative overflow-hidden rounded-2xl aspect-[3/4] sm:aspect-square">
                            <img src="{{ $img }}" alt="{{ $title }}"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                loading="lazy">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                            <div class="absolute bottom-0 left-0 right-0 p-3 sm:p-5">
                                <h3 class="text-sm sm:text-lg font-bold text-white">{{ $title }}</h3>
                                <p class="text-[10px] sm:text-xs text-white/70 mt-1 line-clamp-2">{{ $desc }}</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>


    <?php
    $products = [
        'greeting-board' => [
            'title' => 'Papan Ucapan',
            'subtitle' => 'Greeting Board',
            'items' => [['Papan Congratulations', 'Rp 350.000', '/images/product-greeting.png'], ['Papan Happy Wedding', 'Rp 400.000', '/images/product-greeting.png'], ['Papan Duka Cita', 'Rp 300.000', '/images/product-greeting.png'], ['Papan Peresmian', 'Rp 450.000', '/images/product-greeting.png'], ['Papan Ucapan Custom', 'Rp 500.000', '/images/product-greeting.png']],
        ],
        'florist-bucket' => [
            'title' => 'Bucket Bunga',
            'subtitle' => 'Florist Bucket',
            'items' => [['Bucket Mawar Merah', 'Rp 150.000', '/images/product-bouquet.png'], ['Bucket Lily Putih', 'Rp 200.000', '/images/product-bouquet.png'], ['Bucket Matahari', 'Rp 175.000', '/images/product-bouquet.png'], ['Bucket Mixed Premium', 'Rp 250.000', '/images/product-bouquet.png'], ['Bucket Tulip Import', 'Rp 350.000', '/images/product-bouquet.png']],
        ],
        'hantaran' => [
            'title' => 'Hantaran',
            'subtitle' => 'Seserahan',
            'items' => [['Set Hantaran 5 Item', 'Rp 800.000', '/images/product-hantaran.png'], ['Set Hantaran 7 Item', 'Rp 1.200.000', '/images/product-hantaran.png'], ['Set Hantaran 9 Item', 'Rp 1.500.000', '/images/product-hantaran.png'], ['Hantaran Adat Minang', 'Rp 2.000.000', '/images/product-hantaran.png'], ['Hantaran Premium Gold', 'Rp 2.500.000', '/images/product-hantaran.png']],
        ],
        'dekorasi' => [
            'title' => 'Dekorasi',
            'subtitle' => 'Decoration',
            'items' => [['Dekorasi Lamaran', 'Mulai Rp 1.500.000', '/images/product-decoration.png'], ['Dekorasi Akad Nikah', 'Mulai Rp 3.000.000', '/images/product-decoration.png'], ['Dekorasi Resepsi', 'Mulai Rp 5.000.000', '/images/product-decoration.png'], ['Dekorasi Tunangan', 'Mulai Rp 1.000.000', '/images/product-decoration.png'], ['Paket Dekorasi Full', 'Mulai Rp 8.000.000', '/images/product-decoration.png']],
        ],
    ];
    ?>

    @foreach ($products as $key => $category)
        <section id="{{ $key }}" class="py-12 sm:py-16 {{ $loop->even ? 'bg-[#FAF5F0]' : 'bg-white' }}">
            <div class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-8">
                <div class="flex items-end justify-between mb-6 reveal-on-scroll">
                    <div>
                        <span
                            class="text-[10px] sm:text-xs uppercase tracking-[0.15em] text-[#7A1F2B]/70 font-semibold">{{ $category['subtitle'] }}</span>
                        <h2 class="text-2xl sm:text-3xl font-bold text-[#2D1E1E]">{{ $category['title'] }}</h2>
                    </div>
                    <a href="#"
                        class="text-xs sm:text-sm text-[#7A1F2B] font-semibold hover:underline whitespace-nowrap">Lihat
                        Semua →</a>
                </div>

                <div
                    class="product-scroll-container flex gap-3 overflow-x-auto pb-4 snap-x snap-mandatory scrollbar-hide reveal-on-scroll">
                    @foreach ($category['items'] as $j => [$name, $price, $img])
                        <div
                            class="product-card min-w-[140px] sm:min-w-[160px] max-w-[160px] sm:max-w-[180px] flex-shrink-0 snap-start rounded-xl overflow-hidden bg-white shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-300">
                            <div class="relative aspect-square overflow-hidden">
                                <img src="{{ $img }}" alt="{{ $name }}"
                                    class="w-full h-full object-cover transition-transform duration-500 hover:scale-105"
                                    loading="lazy">
                                @if ($j === 0)
                                    <span
                                        class="absolute top-2 left-2 px-2 py-0.5 bg-[#7A1F2B] text-white text-[9px] sm:text-[10px] font-semibold rounded-full">Terlaris</span>
                                @endif
                            </div>
                            <div class="p-2.5 sm:p-3">
                                <h4 class="text-xs sm:text-sm font-medium text-[#2D1E1E] line-clamp-2 leading-tight">
                                    {{ $name }}</h4>
                                <p class="text-xs sm:text-sm font-bold text-[#7A1F2B] mt-1.5">{{ $price }}</p>
                                <button
                                    class="btn-order mt-2 w-full py-1.5 sm:py-2 rounded-lg bg-[#7A1F2B] text-white text-[10px] sm:text-xs font-semibold">
                                    Pesan
                                </button>
                            </div>
                        </div>
                    @endforeach
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
                @php($reasons = [['🌸', 'Kualitas Premium', 'Material terbaik & bunga segar pilihan'], ['⚡', 'Proses Cepat', 'Pengerjaan profesional tepat waktu'], ['🚚', 'Gratis Ongkir', 'Free delivery area Padang & sekitarnya'], ['✨', 'Custom Request', 'Desain sesuai keinginan Anda']])
                @foreach ($reasons as $i => [$icon, $reasonTitle, $reasonDesc])
                    <div class="reason-card rounded-2xl bg-white/5 backdrop-blur-sm border border-white/10 p-4 sm:p-6 text-center reveal-on-scroll hover:bg-white/10 transition-all duration-300"
                        style="animation-delay:{{ $i * 100 }}ms">
                        <div class="text-2xl sm:text-3xl">{{ $icon }}</div>
                        <h3 class="mt-3 text-sm sm:text-base font-bold">{{ $reasonTitle }}</h3>
                        <p class="mt-2 text-[10px] sm:text-xs text-white/60 leading-relaxed">{{ $reasonDesc }}</p>
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

            <div class="masonry-grid mt-10">
                @php($galleryImages = [['gallery-1.png', 'tall'], ['gallery-2.png', 'wide'], ['gallery-3.png', 'normal'], ['gallery-4.png', 'normal'], ['gallery-5.png', 'tall'], ['gallery-6.png', 'wide'], ['product-bouquet.png', 'normal'], ['product-decoration.png', 'normal']])
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
                    <a href="#"
                        class="contact-card flex items-center gap-4 rounded-2xl bg-white p-4 shadow-sm border border-gray-100 hover:shadow-md hover:border-[#25D366]/30 transition-all duration-300">
                        <div
                            class="w-10 h-10 rounded-full bg-[#25D366]/10 flex items-center justify-center text-[#25D366] text-lg">
                            💬</div>
                        <div>
                            <div class="font-semibold text-sm text-[#2D1E1E]">WhatsApp</div>
                            <div class="text-xs text-gray-500">Chat langsung dengan kami</div>
                        </div>
                    </a>
                    <a href="#"
                        class="contact-card flex items-center gap-4 rounded-2xl bg-white p-4 shadow-sm border border-gray-100 hover:shadow-md hover:border-[#E4405F]/30 transition-all duration-300">
                        <div
                            class="w-10 h-10 rounded-full bg-[#E4405F]/10 flex items-center justify-center text-[#E4405F] text-lg">
                            📸</div>
                        <div>
                            <div class="font-semibold text-sm text-[#2D1E1E]">Instagram</div>
                            <div class="text-xs text-gray-500">@sadita.florist</div>
                        </div>
                    </a>
                    <a href="#"
                        class="contact-card flex items-center gap-4 rounded-2xl bg-white p-4 shadow-sm border border-gray-100 hover:shadow-md hover:border-[#7A1F2B]/30 transition-all duration-300">
                        <div
                            class="w-10 h-10 rounded-full bg-[#7A1F2B]/10 flex items-center justify-center text-[#7A1F2B] text-lg">
                            📍</div>
                        <div>
                            <div class="font-semibold text-sm text-[#2D1E1E]">Lokasi</div>
                            <div class="text-xs text-gray-500">Padang, Sumatera Barat</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
