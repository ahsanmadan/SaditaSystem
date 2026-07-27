<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="description"
        content="Sadita - papan bunga, hantaran, dan dekorasi di Padang untuk momen spesial Anda.">

    <title>Sadita - Papan Bunga, Hantaran & Dekorasi di Padang</title>

    {{-- Favicon --}}
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="shortcut icon" href="/favicon-32x32.png">

    {{-- Preload critical assets --}}
    <link rel="preload" href="/images/bridesmaid-gift-box.jpg" as="image" fetchpriority="high">
    <link rel="preload"
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@600&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500;1,600&display=swap"
        as="style">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@600&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500;1,600&display=swap"
        rel="stylesheet">

    {{-- DNS prefetch for external services --}}
    <link rel="dns-prefetch" href="https://api.groq.com">
</head>

<body
    class="w-full overflow-x-hidden bg-[#FFFDFB] text-gray-900 {{ request()->routeIs('home') ? 'page-home' : '' }} {{ request()->routeIs('catalog') ? 'page-catalog' : '' }} {{ request()->routeIs('order') || request()->routeIs('order.edit') ? 'page-order' : '' }} {{ request()->routeIs('invoice.*') ? 'page-invoice' : '' }} {{ request()->routeIs('tracking.page') ? 'page-tracking' : '' }} {{ request()->routeIs('review.*') ? 'page-review' : '' }} {{ request()->is('admin*') ? 'page-admin' : '' }}">

    @include('components.navbar')

    <main class="w-full">
        @yield('content')
    </main>

    @include('components.footer')
    @include('components.chatbot')
</body>

</html>
