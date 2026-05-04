<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SaditaSystem</title>
    {{-- Favicon --}}
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon.png">
    <link rel="shortcut icon" href="/favicon.png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .font-playfair {
            font-family: 'Playfair Display', serif;
        }
    </style>
</head>

<body class="bg-[#FAF6F0] min-h-screen flex items-center justify-center selection:bg-[#6B1B2A] selection:text-white">

    <div class="flex w-full min-h-screen">
        <!-- Left Panel: Visual -->
        <div
            class="hidden lg:flex lg:w-1/2 relative bg-[#6B1B2A] flex-col items-center justify-center text-white overflow-hidden shadow-2xl">
            <!-- Background Image Carousel -->
            <div class="absolute inset-0 z-0" id="bgCarouselContainer">
                <img id="bg-img-0" src="/images/hero-1.jpg" alt="Sadita" class="carousel-bg absolute inset-0 w-full h-full object-cover opacity-20 transition-opacity duration-1000">
                <img id="bg-img-1" src="/images/hantaran-1.jpg" alt="Hantaran" class="carousel-bg absolute inset-0 w-full h-full object-cover opacity-0 transition-opacity duration-1000">
                <img id="bg-img-2" src="/images/dekorasi-1.jpg" alt="Dekorasi" class="carousel-bg absolute inset-0 w-full h-full object-cover opacity-0 transition-opacity duration-1000">
                <img id="bg-img-3" src="/images/papan-1.jpg" alt="Papan Ucapan" class="carousel-bg absolute inset-0 w-full h-full object-cover opacity-0 transition-opacity duration-1000">
                <div class="absolute inset-0 bg-gradient-to-t from-[#6B1B2A] via-[#6B1B2A]/90 to-[#6B1B2A]/40"></div>
            </div>

            <!-- Content -->
            <div class="relative z-10 text-center px-12">
                <h1
                    class="carousel-text text-6xl xl:text-7xl font-bold italic font-playfair tracking-wide text-[#E8C87A] drop-shadow-lg mb-6 transition-opacity duration-500">
                    Sadita</h1>
                <p class="carousel-tagline text-lg xl:text-xl font-medium text-white/90 tracking-wide max-w-sm mx-auto leading-relaxed transition-opacity duration-500">
                    Hadiah bermakna untuk setiap momen spesial
                </p>

                <!-- Dynamic dot indicators -->
                <div class="mt-16 flex gap-3 justify-center" id="carouselDots">
                    <div class="dot w-16 h-1.5 bg-[#E8C87A] rounded-full transition-all duration-500"></div>
                    <div class="dot w-3 h-1.5 bg-white/20 rounded-full transition-all duration-500"></div>
                    <div class="dot w-3 h-1.5 bg-white/20 rounded-full transition-all duration-500"></div>
                    <div class="dot w-3 h-1.5 bg-white/20 rounded-full transition-all duration-500"></div>
                </div>
            </div>
        </div>

        <!-- Right Panel: Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-16 lg:p-24 bg-[#FAF6F0]">
            <div class="w-full max-w-md">
                <!-- Mobile Logo -->
                <div class="lg:hidden text-center mb-10">
                    <h1 class="carousel-text text-4xl font-bold italic font-playfair tracking-wide text-[#6B1B2A] transition-opacity duration-500">Sadita</h1>
                </div>

                <div>
                    <h2 class="text-3xl font-bold text-[#2D1E1E]">Selamat Datang</h2>
                    <p class="mt-2 text-sm text-gray-500 leading-relaxed">Silakan masukkan kredensial Anda untuk
                        mengakses sistem Sadita.</p>
                </div>

                <!-- Info Demo -->
                <div class="mt-6 bg-[#6B1B2A]/10 border border-[#6B1B2A]/20 rounded-xl p-4 flex items-start gap-3">
                    <svg class="w-5 h-5 text-[#6B1B2A] mt-0.5 shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h3 class="text-sm font-semibold text-[#6B1B2A]">Info</h3>
                        <p class="text-xs text-gray-600 mt-1">Gunakan <strong>Username: admin</strong> dan
                            <strong>Password: admin</strong> untuk mencoba login.
                        </p>
                    </div>
                </div>

                <form method="POST" action="" class="mt-8 space-y-6">
                    @csrf

                    <!-- Email/Username Input -->
                    <div>
                        <label for="email"
                            class="block text-sm font-semibold text-gray-700 mb-2 tracking-wide">Username /
                            Email</label>
                        <input type="text" id="email" name="email" required placeholder="admin" value="admin"
                            class="w-full px-5 py-3.5 rounded-xl border border-gray-200 bg-white text-gray-900 text-sm focus:outline-none focus:ring-4 focus:ring-[#6B1B2A]/10 focus:border-[#6B1B2A] transition-all shadow-sm placeholder:text-gray-400">
                    </div>

                    <!-- Password Input -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="password"
                                class="block text-sm font-semibold text-gray-700 tracking-wide">Password</label>
                            <a href="#"
                                class="text-xs font-bold text-[#6B1B2A] hover:text-[#5a1623] hover:underline transition-colors">Lupa
                                Password?</a>
                        </div>
                        <div class="relative">
                            <input type="password" id="password" name="password" required placeholder="••••••••"
                                value="admin"
                                class="w-full px-5 py-3.5 rounded-xl border border-gray-200 bg-white text-gray-900 text-sm focus:outline-none focus:ring-4 focus:ring-[#6B1B2A]/10 focus:border-[#6B1B2A] transition-all shadow-sm placeholder:text-gray-400 pr-12">
                            <button type="button" id="togglePassword"
                                class="absolute inset-y-0 right-0 px-4 flex items-center text-gray-400 hover:text-[#6B1B2A] transition-colors"
                                title="Tampilkan/Sembunyikan password">
                                <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Verify Bot -->
                    <div class="flex items-center gap-3 p-3 mt-4 border border-gray-200 rounded-xl bg-gray-50/50">
                        <input type="checkbox" id="verify_bot" name="verify_bot" required
                            class="w-5 h-5 text-[#6B1B2A] bg-white border-gray-300 rounded focus:ring-[#6B1B2A] focus:ring-2 cursor-pointer transition-colors">
                        <label for="verify_bot" class="text-sm font-medium text-gray-700 cursor-pointer select-none">
                            Saya bukan robot
                        </label>
                        <svg class="w-6 h-6 text-green-500 ml-auto hidden transition-opacity" id="checkIcon"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full mt-8 py-3.5 px-4 bg-[#6B1B2A] hover:bg-[#5a1623] text-white text-sm font-bold rounded-xl shadow-[0_8px_20px_rgba(107,27,42,0.25)] transform transition-all duration-300 hover:-translate-y-0.5 hover:shadow-[0_12px_25px_rgba(107,27,42,0.35)] active:translate-y-0">
                        Login
                    </button>
                </form>

                <div class="mt-10 text-center">
                    <a href="{{ url('/') }}"
                        class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-[#6B1B2A] transition-colors group">
                        <svg class="w-4 h-4 transform transition-transform group-hover:-translate-x-1" fill="none"
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
        // Toggle Password Visibility
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);

            if (type === 'text') {
                eyeIcon.innerHTML =
                    `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />`;
            } else {
                eyeIcon.innerHTML =
                    `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />`;
            }
        });

        // Verify Bot Checkbox Toggle
        const verifyBot = document.getElementById('verify_bot');
        const checkIcon = document.getElementById('checkIcon');
        if (verifyBot && checkIcon) {
            verifyBot.addEventListener('change', function() {
                if (this.checked) {
                    checkIcon.classList.remove('hidden');
                } else {
                    checkIcon.classList.add('hidden');
                }
            });
        }

        // ── Unified Carousel (Text + Background Image + Tagline + Dots) ───────
        const carouselData = [
            { text: "Sadita",          tagline: "Hadiah bermakna untuk setiap momen spesial",    img: 0 },
            { text: "Sadita Florist",  tagline: "Rangkaian bunga segar penuh keindahan",          img: 1 },
            { text: "Sadita Hantaran", tagline: "Hantaran elegan yang berkesan di hati",          img: 2 },
            { text: "Sadita Decor",    tagline: "Dekorasi mewah untuk momen tak terlupakan",      img: 3 },
        ];

        let currentIndex = 0;
        const textElements   = document.querySelectorAll('.carousel-text');
        const taglineEl      = document.querySelector('.carousel-tagline');
        const bgImages       = document.querySelectorAll('.carousel-bg');
        const dots           = document.querySelectorAll('#carouselDots .dot');

        function updateDots(active) {
            dots.forEach((dot, i) => {
                if (i === active) {
                    dot.classList.remove('w-3', 'bg-white/20');
                    dot.classList.add('w-16', 'bg-[#E8C87A]');
                } else {
                    dot.classList.remove('w-16', 'bg-[#E8C87A]');
                    dot.classList.add('w-3', 'bg-white/20');
                }
            });
        }

        function runCarousel() {
            // 1. Fade out text & tagline
            textElements.forEach(el => el.classList.add('opacity-0'));
            if (taglineEl) taglineEl.classList.add('opacity-0');

            // 2. Cross-fade background image
            const oldIndex = currentIndex;
            currentIndex = (currentIndex + 1) % carouselData.length;
            const data = carouselData[currentIndex];

            // Fade in new bg, fade out old bg
            if (bgImages.length > 0) {
                bgImages[oldIndex].style.opacity = '0';
                bgImages[currentIndex].style.opacity = '0.20';
            }

            // 3. After text fades out, swap text content and fade in
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
