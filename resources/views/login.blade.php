<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SaditaSystem</title>
    {{-- Favicon --}}
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="shortcut icon" href="/favicon-32x32.png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    {{-- reCAPTCHA v2 --}}
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .font-playfair {
            font-family: 'Playfair Display', serif;
        }
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(28px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-slide-in {
            animation: slideInRight 0.6s cubic-bezier(0.22, 1, 0.36, 1) both;
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.4s ease both;
        }
        .btn-loading {
            pointer-events: none;
            opacity: 0.75;
        }
    </style>
</head>

<body class="bg-[#FAF6F0] min-h-screen flex items-center justify-center selection:bg-[#6B1B2A] selection:text-white">

    <div class="flex w-full min-h-screen">

        {{-- ─────────────── Left Panel: Visual ─────────────────────────────── --}}
        <div class="hidden lg:flex lg:w-[52%] relative bg-[#6B1B2A] flex-col items-center justify-center text-white overflow-hidden">
            {{-- Background Image Carousel --}}
            <div class="absolute inset-0 z-0">
                <img id="bg-img-0" src="/images/hero-1.jpg" alt="Sadita"
                    class="carousel-bg absolute inset-0 w-full h-full object-cover opacity-25 transition-opacity duration-1000">
                <img id="bg-img-1" src="/images/bridesmaid-gift-box.jpg" alt="Hantaran"
                    class="carousel-bg absolute inset-0 w-full h-full object-cover opacity-0 transition-opacity duration-1000">
                <img id="bg-img-2" src="/images/dekorasi-lamaran.jpg" alt="Dekorasi"
                    class="carousel-bg absolute inset-0 w-full h-full object-cover opacity-0 transition-opacity duration-1000">
                <img id="bg-img-3" src="/images/papan-standing-mirror-premium.jpg" alt="Papan Ucapan"
                    class="carousel-bg absolute inset-0 w-full h-full object-cover opacity-0 transition-opacity duration-1000">
                <div class="absolute inset-0 bg-gradient-to-t from-[#6B1B2A] via-[#6B1B2A]/80 to-[#3d0a14]/50"></div>
            </div>

            {{-- Content --}}
            <div class="relative z-10 text-center px-12 max-w-lg">
                {{-- Divider ornament --}}
                <div class="mb-8 flex items-center justify-center gap-3">
                    <div class="w-14 h-px bg-[#E8C87A]/40"></div>
                    <div class="w-1.5 h-1.5 rounded-full bg-[#E8C87A]"></div>
                    <div class="w-14 h-px bg-[#E8C87A]/40"></div>
                </div>
                <h1 class="carousel-text text-6xl xl:text-7xl font-bold italic font-playfair tracking-wide text-[#E8C87A] drop-shadow-lg mb-5 transition-opacity duration-500">
                    Sadita
                </h1>
                <p class="carousel-tagline text-base xl:text-lg font-light text-white/75 tracking-wide max-w-sm mx-auto leading-relaxed transition-opacity duration-500">
                    Hadiah bermakna untuk setiap momen spesial
                </p>

                {{-- Dot indicators --}}
                <div class="mt-12 flex gap-2.5 justify-center" id="carouselDots">
                    <div class="dot w-10 h-1 bg-[#E8C87A] rounded-full transition-all duration-500"></div>
                    <div class="dot w-3 h-1 bg-white/20 rounded-full transition-all duration-500"></div>
                    <div class="dot w-3 h-1 bg-white/20 rounded-full transition-all duration-500"></div>
                    <div class="dot w-3 h-1 bg-white/20 rounded-full transition-all duration-500"></div>
                </div>

                <div class="mt-16 text-white/25 text-[10px] tracking-widest uppercase font-medium">
                    Sistem Manajemen Sadita · {{ now()->year }}
                </div>
            </div>
        </div>

        {{-- ─────────────── Right Panel: Form ───────────────────────────────── --}}
        <div class="w-full lg:w-[48%] flex items-center justify-center p-6 sm:p-12 lg:p-16 bg-[#FAF6F0]">
            <div class="w-full max-w-sm animate-slide-in">

                {{-- Mobile Logo --}}
                <div class="lg:hidden text-center mb-8">
                    <h1 class="carousel-text text-4xl font-bold italic font-playfair tracking-wide text-[#6B1B2A]">Sadita</h1>
                    <p class="text-[10px] text-gray-400 mt-1 tracking-widest uppercase">Sistem Manajemen</p>
                </div>

                {{-- Header --}}
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-[#2D1E1E] tracking-tight">Selamat Datang</h2>
                    <p class="mt-1 text-sm text-gray-400">Masuk ke panel manajemen Sadita.</p>
                </div>

                {{-- Alert: Status Sukses --}}
                @if (session('status'))
                    <div class="mb-5 bg-green-50 border border-green-200 rounded-xl p-3.5 flex items-start gap-3 animate-fade-in-up">
                        <svg class="w-4 h-4 text-green-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-xs text-green-700 font-medium">{{ session('status') }}</p>
                    </div>
                @endif

                {{-- Alert: Error login --}}
                @if ($errors->has('email'))
                    <div class="mb-5 bg-red-50 border border-red-200 rounded-xl p-3.5 flex items-start gap-3 animate-fade-in-up">
                        <svg class="w-4 h-4 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-xs text-red-700 font-medium">{{ $errors->first('email') }}</p>
                    </div>
                @endif

                {{-- Alert: reCAPTCHA error --}}
                @if ($errors->has('captcha') || $errors->has('g-recaptcha-response'))
                    <div class="mb-5 bg-amber-50 border border-amber-200 rounded-xl p-3.5 flex items-start gap-3 animate-fade-in-up">
                        <svg class="w-4 h-4 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"></path>
                        </svg>
                        <p class="text-xs text-amber-700 font-medium">
                            {{ $errors->first('captcha') ?: $errors->first('g-recaptcha-response') }}
                        </p>
                    </div>
                @endif

                {{-- Alert: Password error --}}
                @if ($errors->has('password'))
                    <div class="mb-5 bg-red-50 border border-red-200 rounded-xl p-3.5 flex items-start gap-3 animate-fade-in-up">
                        <svg class="w-4 h-4 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-xs text-red-700 font-medium">{{ $errors->first('password') }}</p>
                    </div>
                @endif

                {{-- Info Demo --}}
                <div class="mb-6 bg-[#6B1B2A]/5 border border-[#6B1B2A]/12 rounded-xl p-3 flex items-center gap-3">
                    <div class="shrink-0 w-6 h-6 rounded-lg bg-[#6B1B2A]/10 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-[#6B1B2A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-xs text-gray-500">
                        Demo: email&nbsp;<code class="text-[#6B1B2A] font-semibold bg-[#6B1B2A]/8 px-1 py-0.5 rounded">admin</code>
                        &nbsp;/ password&nbsp;<code class="text-[#6B1B2A] font-semibold bg-[#6B1B2A]/8 px-1 py-0.5 rounded">admin</code>
                    </p>
                </div>

                {{-- Form --}}
                <form method="POST" action="{{ route('login') }}" class="space-y-4" id="loginForm">
                    @csrf

                    {{-- Email / Username --}}
                    <div>
                        <label for="email" class="block text-xs font-semibold text-gray-500 mb-1.5 tracking-wide uppercase">
                            Email / Username
                        </label>
                        <input type="text" id="email" name="email" required
                            autocomplete="username"
                            placeholder="admin atau nama@sadita.com"
                            value="{{ old('email', 'admin') }}"
                            class="w-full px-4 py-3 rounded-xl border text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#6B1B2A]/15 focus:border-[#6B1B2A] transition-all placeholder:text-gray-300 {{ $errors->has('email') ? 'border-red-300 bg-red-50/50' : 'border-gray-200 bg-white' }}">
                    </div>

                    {{-- Password --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-xs font-semibold text-gray-500 tracking-wide uppercase">
                                Password
                            </label>
                            <a href="{{ route('password.request') }}"
                                class="text-[11px] font-semibold text-[#6B1B2A] hover:text-[#5a1623] hover:underline transition-colors">
                                Lupa Password?
                            </a>
                        </div>
                        <div class="relative">
                            <input type="password" id="password" name="password" required
                                autocomplete="current-password"
                                placeholder="••••••••"
                                value="{{ app()->environment('local') ? 'admin' : '' }}"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#6B1B2A]/15 focus:border-[#6B1B2A] transition-all placeholder:text-gray-300 pr-11">
                            <button type="button" id="togglePassword"
                                class="absolute inset-y-0 right-0 px-3.5 flex items-center text-gray-300 hover:text-[#6B1B2A] transition-colors"
                                title="Tampilkan/Sembunyikan password"
                                aria-label="Toggle password visibility">
                                <svg id="eyeIcon" class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- reCAPTCHA v2 Widget (Test Keys — always passes on any domain) --}}
                    <div class="pt-0.5">
                        <div class="g-recaptcha"
                            data-sitekey="{{ config('services.recaptcha.site_key', '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI') }}"
                            data-theme="light"
                            data-size="normal">
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" id="submitBtn"
                        class="w-full py-3 px-4 bg-[#6B1B2A] hover:bg-[#5a1623] text-white text-sm font-semibold rounded-xl shadow-[0_6px_18px_rgba(107,27,42,0.20)] transform transition-all duration-300 hover:-translate-y-0.5 hover:shadow-[0_10px_24px_rgba(107,27,42,0.30)] active:translate-y-0 flex items-center justify-center gap-2">
                        <svg id="btnSpinner" class="w-4 h-4 animate-spin hidden" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        <span id="btnText">Masuk ke Panel</span>
                    </button>
                </form>

                {{-- Footer link --}}
                <div class="mt-8 text-center">
                    <a href="{{ url('/') }}"
                        class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-400 hover:text-[#6B1B2A] transition-colors group">
                        <svg class="w-3.5 h-3.5 transition-transform group-hover:-translate-x-0.5" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke Beranda
                    </a>
                </div>

            </div>
        </div>
    </div>

    <script>
        // ── Toggle Password Visibility ─────────────────────────────────────────
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput  = document.getElementById('password');
        const eyeIcon        = document.getElementById('eyeIcon');

        if (togglePassword) {
            togglePassword.addEventListener('click', function () {
                const isPassword = passwordInput.getAttribute('type') === 'password';
                passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                eyeIcon.innerHTML = isPassword
                    ? `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />`
                    : `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />`;
            });
        }

        // ── Submit Loading State ───────────────────────────────────────────────
        const loginForm  = document.getElementById('loginForm');
        const submitBtn  = document.getElementById('submitBtn');
        const btnSpinner = document.getElementById('btnSpinner');
        const btnText    = document.getElementById('btnText');

        if (loginForm) {
            loginForm.addEventListener('submit', function () {
                if (submitBtn) {
                    submitBtn.classList.add('btn-loading');
                    btnSpinner.classList.remove('hidden');
                    btnText.textContent = 'Sedang masuk...';
                }
            });
        }

        // ── Unified Carousel ──────────────────────────────────────────────────
        const carouselData = [
            { text: 'Sadita',              tagline: 'Hadiah bermakna untuk setiap momen spesial',   img: 0 },
            { text: 'Sadita Hantaran',     tagline: 'Hantaran elegan yang berkesan di hati',         img: 1 },
            { text: 'Sadita Dekorasi',     tagline: 'Dekorasi mewah untuk momen tak terlupakan',     img: 2 },
            { text: 'Sadita Papan Ucapan', tagline: 'Papan ucapan rapi untuk momen penting',        img: 3 },
        ];

        let currentIndex   = 0;
        const textElements = document.querySelectorAll('.carousel-text');
        const taglineEl    = document.querySelector('.carousel-tagline');
        const bgImages     = document.querySelectorAll('.carousel-bg');
        const dots         = document.querySelectorAll('#carouselDots .dot');

        function updateDots(active) {
            dots.forEach((dot, i) => {
                if (i === active) {
                    dot.classList.remove('w-3', 'bg-white/20');
                    dot.classList.add('w-10', 'bg-[#E8C87A]');
                } else {
                    dot.classList.remove('w-10', 'bg-[#E8C87A]');
                    dot.classList.add('w-3', 'bg-white/20');
                }
            });
        }

        function runCarousel() {
            textElements.forEach(el => el.classList.add('opacity-0'));
            if (taglineEl) taglineEl.classList.add('opacity-0');

            const oldIndex = currentIndex;
            currentIndex   = (currentIndex + 1) % carouselData.length;
            const data     = carouselData[currentIndex];

            if (bgImages.length > 0) {
                bgImages[oldIndex].style.opacity = '0';
                bgImages[currentIndex].style.opacity = '0.25';
            }

            setTimeout(() => {
                textElements.forEach(el => {
                    el.textContent = data.text;
                    el.classList.remove('opacity-0');
                });
                if (taglineEl) {
                    taglineEl.textContent = data.tagline;
                    taglineEl.classList.remove('opacity-0');
                }
                updateDots(currentIndex);
            }, 500);
        }

        if (textElements.length > 0) {
            setInterval(runCarousel, 3500);
        }
    </script>
</body>

</html>
