<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Sadita - Layanan florist & hadiah premium di Padang. Bunga, papan ucapan, hantaran, dan dekorasi elegan untuk setiap momen spesial Anda.">

    <title>Sadita – Seni Memberi yang Bermakna</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500;1,600&display=swap"
        rel="stylesheet">
</head>

<body class="bg-[#FFFDFB] text-gray-900 overflow-x-hidden">

    @include('layouts.navbar')

    <main>
        @yield('content')
    </main>

    @include('layouts.footer')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ═══════════════════════════════════════════
            // NAVBAR SCROLL EFFECT
            // ═══════════════════════════════════════════
            const navbar = document.getElementById('main-navbar');
            const hamburgerBtn = document.getElementById('hamburger-btn');
            const mobileMenu = document.getElementById('mobile-menu');
            let menuOpen = false;

            function updateNavbar() {
                if (window.scrollY > 50) {
                    navbar.classList.add('navbar-scrolled');
                } else {
                    navbar.classList.remove('navbar-scrolled');
                }
            }
            window.addEventListener('scroll', updateNavbar, {
                passive: true
            });
            updateNavbar();

            // ═══════════════════════════════════════════
            // HAMBURGER MENU
            // ═══════════════════════════════════════════
            hamburgerBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                menuOpen = !menuOpen;
                if (menuOpen) {
                    mobileMenu.classList.remove('translate-x-full');
                    mobileMenu.classList.add('translate-x-0');
                    hamburgerBtn.classList.add('is-active');
                    document.body.style.overflow = 'hidden';
                } else {
                    closeMenu();
                }
            });

            function closeMenu() {
                menuOpen = false;
                mobileMenu.classList.add('translate-x-full');
                mobileMenu.classList.remove('translate-x-0');
                hamburgerBtn.classList.remove('is-active');
                document.body.style.overflow = '';
            }

            // Close menu on link click
            document.querySelectorAll('#mobile-menu a').forEach(function(link) {
                link.addEventListener('click', closeMenu);
            });

            // Close menu on outside click
            document.addEventListener('click', function(e) {
                if (menuOpen && !mobileMenu.contains(e.target) && !hamburgerBtn.contains(e.target)) {
                    closeMenu();
                }
            });

            // ═══════════════════════════════════════════
            // HERO SLIDER
            // ═══════════════════════════════════════════
            const slides = document.querySelectorAll('.hero-slide');
            let currentSlide = 0;
            const slideCount = slides.length;

            function nextSlide() {
                slides[currentSlide].classList.remove('active');
                currentSlide = (currentSlide + 1) % slideCount;
                slides[currentSlide].classList.add('active');
            }

            if (slideCount > 0) {
                setInterval(nextSlide, 4500);
            }

            // ═══════════════════════════════════════════
            // PARALLAX ON SCROLL
            // ═══════════════════════════════════════════
            const heroSection = document.getElementById('beranda');

            function parallaxHero() {
                if (!heroSection) return;
                const scrolled = window.scrollY;
                const rate = scrolled * 0.4;
                slides.forEach(function(slide) {
                    slide.style.transform = 'scale(1.15) translateY(' + rate + 'px)';
                });
                // Fade hero content
                const heroContent = document.querySelector('.hero-content');
                if (heroContent) {
                    heroContent.style.opacity = Math.max(0, 1 - scrolled / 600);
                    heroContent.style.transform = 'translateY(' + (scrolled * 0.2) + 'px)';
                }
            }
            window.addEventListener('scroll', parallaxHero, {
                passive: true
            });

            // ═══════════════════════════════════════════
            // SCROLL REVEAL ANIMATIONS
            // ═══════════════════════════════════════════
            const revealElements = document.querySelectorAll('.reveal-on-scroll');
            const revealObserver = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('revealed');
                        revealObserver.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            });

            revealElements.forEach(function(el) {
                revealObserver.observe(el);
            });

            // ═══════════════════════════════════════════
            // PRODUCT HORIZONTAL SCROLL (Touch-friendly)
            // ═══════════════════════════════════════════
            document.querySelectorAll('.product-scroll-container').forEach(function(container) {
                let isDown = false;
                let startX;
                let scrollLeft;

                container.addEventListener('mousedown', function(e) {
                    isDown = true;
                    container.classList.add('active-drag');
                    startX = e.pageX - container.offsetLeft;
                    scrollLeft = container.scrollLeft;
                });
                container.addEventListener('mouseleave', function() {
                    isDown = false;
                    container.classList.remove('active-drag');
                });
                container.addEventListener('mouseup', function() {
                    isDown = false;
                    container.classList.remove('active-drag');
                });
                container.addEventListener('mousemove', function(e) {
                    if (!isDown) return;
                    e.preventDefault();
                    var x = e.pageX - container.offsetLeft;
                    var walk = (x - startX) * 1.5;
                    container.scrollLeft = scrollLeft - walk;
                });
            });

            // ═══════════════════════════════════════════
            // COUNTER ANIMATION
            // ═══════════════════════════════════════════
            const counters = document.querySelectorAll('.counter-number');
            const counterObserver = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        const target = parseInt(entry.target.getAttribute('data-target'));
                        let count = 0;
                        const increment = Math.ceil(target / 60);
                        const timer = setInterval(function() {
                            count += increment;
                            if (count >= target) {
                                count = target;
                                clearInterval(timer);
                            }
                            entry.target.textContent = count + '+';
                        }, 30);
                        counterObserver.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.5
            });

            counters.forEach(function(c) {
                counterObserver.observe(c);
            });
        });
    </script>

</body>

</html>
