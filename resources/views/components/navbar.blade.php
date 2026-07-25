@php
    $isHomePage = request()->routeIs('home');
    $isCatalogPage = request()->routeIs('catalog');
    $sectionBaseUrl = $isHomePage ? '' : url('/');
    $catalogNavItems = [
        [
            'label' => 'Semua Koleksi',
            'href' => route('catalog'),
            'description' => 'Semua produk publik Sadita dalam satu halaman.',
        ],
        [
            'label' => 'Papan Bunga',
            'href' => route('catalog', ['category' => 'papan-bunga']),
            'description' => 'Standing board dan papan ucapan siap kirim.',
        ],
        [
            'label' => 'Hantaran',
            'href' => route('catalog', ['category' => 'hantaran']),
            'description' => 'Gift set, seserahan, dan paket hantaran premium.',
        ],
        [
            'label' => 'Dekorasi',
            'href' => route('catalog', ['category' => 'dekorasi']),
            'description' => 'Dekorasi acara yang bisa disesuaikan cepat.',
        ],
    ];
    $catalogFeatureItems = [
        [
            'eyebrow' => 'Best Seller',
            'title' => 'Paling sering dipilih',
            'copy' => 'Lompat ke item yang paling sering selesai dipesan.',
            'href' => route('catalog') . '#catalog-best-sellers',
        ],
        [
            'eyebrow' => 'Custom Order',
            'title' => 'Mulai dari kebutuhan acara',
            'copy' => 'Pilih kategori yang paling relevan lalu lanjut ke detail.',
            'href' => route('catalog', ['category' => 'dekorasi']),
        ],
    ];
@endphp

<header id="main-navbar"
    class="fixed top-0 inset-x-0 z-50 transition-all duration-500 {{ $isHomePage ? '' : 'navbar-solid' }}">
    <div class="navbar-inner max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-18 flex items-center justify-between py-4">
        <!-- Logo -->
        <a href="/" class="navbar-logo transition-colors duration-300 hover:opacity-90 italic"
            style="font-family:'Playfair Display',serif">
            Sadita
        </a>

        <!-- Desktop Navigation -->
        <nav class="hidden md:flex items-center space-x-8 text-[13px] font-medium tracking-wide uppercase">
            <a href="{{ $sectionBaseUrl }}#beranda" class="nav-link" data-section-link="beranda">Beranda</a>
            <div class="relative">
                <button id="catalog-nav-trigger" type="button"
                    class="nav-link nav-dropdown-trigger inline-flex items-center gap-2" data-nav-dropdown-trigger
                    aria-expanded="false" aria-controls="catalog-nav-menu"
                    @if ($isCatalogPage) aria-current="page" @endif>
                    <span>Katalog</span>
                    <svg class="h-3.5 w-3.5 transition-transform duration-200" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9l6 6 6-6" />
                    </svg>
                </button>
            </div>
            <a href="{{ $sectionBaseUrl }}#kategori" class="nav-link" data-section-link="kategori">Kategori</a>
            <a href="{{ $sectionBaseUrl }}#galeri" class="nav-link" data-section-link="galeri">Galeri</a>
            <a href="{{ $sectionBaseUrl }}#cara-pesan" class="nav-link" data-section-link="cara-pesan">Cara Pesan</a>
            <a href="{{ $sectionBaseUrl }}#lacak" class="nav-link" data-section-link="lacak">Lacak</a>
            <a href="{{ $sectionBaseUrl }}#tentang" class="nav-link" data-section-link="tentang">Tentang</a>
        </nav>

        <!-- Desktop CTA -->
        <a href="/login"
            class="hidden md:inline-flex px-5 py-2 rounded-full text-[13px] font-semibold tracking-wide transition-all duration-300 nav-cta-btn border border-[#E8C87A]/40 hover:border-[#d4b468]">
            Login
        </a>

        <!-- Mobile Hamburger Button -->
        <button id="hamburger-btn" class="md:hidden relative w-10 h-10 flex items-center justify-center z-[60]"
            aria-label="Toggle menu">
            <div class="hamburger-box w-6 h-5 relative flex flex-col justify-between">
                <span
                    class="hamburger-line block w-full h-[2px] rounded-full transition-all duration-300 ease-in-out"></span>
                <span
                    class="hamburger-line block w-full h-[2px] rounded-full transition-all duration-300 ease-in-out"></span>
                <span
                    class="hamburger-line block w-4 h-[2px] rounded-full transition-all duration-300 ease-in-out ml-auto"></span>
            </div>
        </button>
    </div>

    <div id="catalog-nav-menu" data-nav-dropdown class="catalog-mega-menu pointer-events-none opacity-0 -translate-y-3"
        aria-hidden="true">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="catalog-mega-menu__panel">
                <div class="catalog-mega-menu__layout">
                    <div class="catalog-mega-menu__intro">
                        <div class="catalog-mega-menu__eyebrow">Katalog Sadita</div>
                        <a href="{{ route('catalog') }}" class="catalog-mega-menu__all-link">
                            <span>Lihat semua koleksi</span>
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                        <p class="catalog-mega-menu__intro-copy">
                            Pilih jalur layanan yang paling pas dulu, lalu lanjut ke produk yang ingin dilihat.
                        </p>
                    </div>

                    <div class="catalog-mega-menu__columns">
                        @foreach (array_slice($catalogNavItems, 1) as $item)
                            <div class="catalog-mega-menu__column">
                                <div class="catalog-mega-menu__column-label">{{ $item['label'] }}</div>
                                <a href="{{ $item['href'] }}" class="catalog-mega-menu__column-link">
                                    {{ $item['label'] }}
                                </a>
                                <p class="catalog-mega-menu__column-copy">{{ $item['description'] }}</p>
                            </div>
                        @endforeach
                    </div>

                    <div class="catalog-mega-menu__feature">
                        <div class="catalog-mega-menu__feature-heading">Pilihan cepat</div>
                        <div class="catalog-mega-menu__feature-grid">
                            @foreach ($catalogFeatureItems as $feature)
                                <a href="{{ $feature['href'] }}" class="catalog-mega-menu__feature-card">
                                    <div class="catalog-mega-menu__feature-eyebrow">{{ $feature['eyebrow'] }}</div>
                                    <div class="catalog-mega-menu__feature-title">{{ $feature['title'] }}</div>
                                    <p class="catalog-mega-menu__feature-copy">{{ $feature['copy'] }}</p>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Menu Overlay -->
    <div id="mobile-menu"
        class="mobile-menu-panel md:hidden fixed inset-x-0 top-0 h-screen bg-[#FFFDFB] transform translate-x-full transition-transform duration-300 ease-in-out z-[70]">
        <button id="mobile-menu-close" type="button" class="mobile-menu-close flex items-center justify-center"
            aria-label="Tutup menu">
            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 6l12 12M18 6L6 18" />
            </svg>
        </button>
        <div class="flex flex-col px-6 pt-24 pb-8 gap-1 h-full overflow-y-auto">
            <a href="{{ $sectionBaseUrl }}#beranda" class="mobile-nav-link" data-section-link="beranda">Beranda</a>
            <div class="mobile-catalog">
                <button type="button" class="mobile-catalog-trigger" data-mobile-catalog-trigger
                    aria-expanded="{{ $isCatalogPage ? 'true' : 'false' }}" aria-controls="mobile-catalog-panel">
                    <span>Katalog</span>
                    <svg class="h-5 w-5 transition-transform duration-200" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 9l6 6 6-6" />
                    </svg>
                </button>
                <div id="mobile-catalog-panel" class="mobile-catalog-panel {{ $isCatalogPage ? 'is-open' : '' }}"
                    data-mobile-catalog-panel @if (!$isCatalogPage) hidden @endif>
                    @foreach ($catalogNavItems as $item)
                        <a href="{{ $item['href'] }}" class="mobile-catalog-link">
                            <span class="mobile-catalog-link__title">{{ $item['label'] }}</span>
                            <span class="mobile-catalog-link__copy">{{ $item['description'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
            <a href="{{ $sectionBaseUrl }}#kategori" class="mobile-nav-link"
                data-section-link="kategori">Kategori</a>
            <a href="{{ $sectionBaseUrl }}#galeri" class="mobile-nav-link" data-section-link="galeri">Galeri</a>
            <a href="{{ $sectionBaseUrl }}#cara-pesan" class="mobile-nav-link" data-section-link="cara-pesan">Cara
                Pesan</a>
            <a href="{{ $sectionBaseUrl }}#lacak" class="mobile-nav-link" data-section-link="lacak">Lacak</a>
            <a href="{{ $sectionBaseUrl }}#tentang" class="mobile-nav-link" data-section-link="tentang">Tentang</a>
            <a href="{{ $sectionBaseUrl }}#kontak" class="mobile-nav-link">Kontak</a>
            <div class="mt-6 pt-6 border-t border-[#E8C87A]/10">
                <a href="/login"
                    class="block w-full text-center py-3 rounded-full text-[13px] font-semibold tracking-wide transition-all duration-300 bg-[#E8C87A] text-[#7A1F2B] hover:bg-[#d4b468] hover:shadow-md">
                    Login
                </a>
            </div>
        </div>
    </div>
</header>
