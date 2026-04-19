<header id="main-navbar" class="fixed top-0 inset-x-0 z-50 transition-all duration-500">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        <!-- Logo -->
        <a href="#beranda" class="navbar-logo text-2xl font-bold tracking-tight transition-colors duration-300">
            Sadita
        </a>

        <!-- Desktop Navigation -->
        <nav class="hidden md:flex items-center gap-6 text-sm font-medium">
            <a href="#beranda" class="nav-link">Beranda</a>
            <a href="#kategori" class="nav-link">Kategori</a>
            <a href="#galeri" class="nav-link">Galeri</a>
            <a href="#cara-pesan" class="nav-link">Cara Pesan</a>
            <a href="#lacak" class="nav-link">Lacak</a>
            <a href="#tentang" class="nav-link">Tentang</a>
        </nav>

        <!-- Desktop CTA -->
        <a href="#kategori"
            class="hidden md:inline-flex px-5 py-2 rounded-full text-sm font-semibold transition-all duration-300 nav-cta-btn">
            Pesan Sekarang
        </a>

        <!-- Mobile Hamburger Button -->
        <button id="hamburger-btn" class="md:hidden relative w-10 h-10 flex items-center justify-center z-[60]" aria-label="Toggle menu">
            <div class="hamburger-box w-6 h-5 relative flex flex-col justify-between">
                <span class="hamburger-line block w-full h-[2px] rounded-full transition-all duration-300 ease-in-out"></span>
                <span class="hamburger-line block w-full h-[2px] rounded-full transition-all duration-300 ease-in-out"></span>
                <span class="hamburger-line block w-4 h-[2px] rounded-full transition-all duration-300 ease-in-out ml-auto"></span>
            </div>
        </button>
    </div>

    <!-- Mobile Menu Overlay -->
    <div id="mobile-menu"
        class="mobile-menu-panel md:hidden fixed inset-x-0 top-0 h-screen bg-[#FFFDFB] transform translate-x-full transition-transform duration-300 ease-in-out z-[55]">
        <div class="flex flex-col px-6 pt-24 pb-8 gap-1 h-full overflow-y-auto">
            <a href="#beranda" class="mobile-nav-link">Beranda</a>
            <a href="#kategori" class="mobile-nav-link">Kategori</a>
            <a href="#galeri" class="mobile-nav-link">Galeri</a>
            <a href="#cara-pesan" class="mobile-nav-link">Cara Pesan</a>
            <a href="#lacak" class="mobile-nav-link">Lacak</a>
            <a href="#tentang" class="mobile-nav-link">Tentang</a>
            <a href="#kontak" class="mobile-nav-link">Kontak</a>
            <div class="mt-6 pt-6 border-t border-[#7A1F2B]/10">
                <a href="#kategori"
                    class="block w-full text-center py-3 rounded-full bg-[#7A1F2B] text-white font-semibold text-sm hover:bg-[#5e1721] transition-colors duration-300">
                    Pesan Sekarang
                </a>
            </div>
        </div>
    </div>
</header>
