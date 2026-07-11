<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Ulang Password - SaditaSystem</title>
    {{-- Favicon --}}
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link class="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="shortcut icon" href="/favicon-32x32.png">
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

<body class="bg-[#FAF6F0] min-h-screen flex items-center justify-center p-4 selection:bg-[#6B1B2A] selection:text-white">

    <div class="w-full max-w-md bg-[#FAF6F0] border border-gray-100 rounded-3xl p-8 sm:p-10 shadow-2xl relative overflow-hidden bg-gradient-to-br from-white to-[#FAF6F0]">
        <!-- Top accent decoration -->
        <div class="absolute top-0 inset-x-0 h-2 bg-gradient-to-r from-[#6B1B2A] via-[#E8C87A] to-[#6B1B2A]"></div>

        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold italic font-playfair tracking-wide text-[#6B1B2A] mb-3">Sadita</h1>
            <h2 class="text-2xl font-bold text-[#2D1E1E]">Atur Ulang Password</h2>
            <p class="mt-2 text-xs text-gray-500 leading-relaxed">Silakan masukkan email Anda dan tentukan password baru.</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 text-sm rounded-xl p-4 flex items-start gap-3">
                <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <span class="font-semibold text-red-900">Gagal!</span>
                    <ul class="list-disc list-inside text-xs text-red-700 mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Form Reset Password -->
        <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
            @csrf

            <!-- Hidden Token -->
            <input type="hidden" name="token" value="{{ $token }}">

            <!-- Email Input -->
            <div>
                <label for="email" class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Alamat Email</label>
                <input type="email" id="email" name="email" required placeholder="nama@sadita.com" value="{{ old('email', $email) }}"
                    class="w-full px-5 py-3.5 rounded-xl border border-gray-200 bg-white text-gray-900 text-sm focus:outline-none focus:ring-4 focus:ring-[#6B1B2A]/10 focus:border-[#6B1B2A] transition-all shadow-sm placeholder:text-gray-400">
            </div>

            <!-- Password Input -->
            <div>
                <label for="password" class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Password Baru</label>
                <input type="password" id="password" name="password" required placeholder="••••••••"
                    class="w-full px-5 py-3.5 rounded-xl border border-gray-200 bg-white text-gray-900 text-sm focus:outline-none focus:ring-4 focus:ring-[#6B1B2A]/10 focus:border-[#6B1B2A] transition-all shadow-sm placeholder:text-gray-400">
            </div>

            <!-- Confirm Password Input -->
            <div>
                <label for="password_confirmation" class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Konfirmasi Password Baru</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="••••••••"
                    class="w-full px-5 py-3.5 rounded-xl border border-gray-200 bg-white text-gray-900 text-sm focus:outline-none focus:ring-4 focus:ring-[#6B1B2A]/10 focus:border-[#6B1B2A] transition-all shadow-sm placeholder:text-gray-400">
            </div>

            <button type="submit"
                class="w-full py-3.5 px-4 bg-[#6B1B2A] hover:bg-[#5a1623] text-white text-sm font-bold rounded-xl shadow-[0_8px_20px_rgba(107,27,42,0.25)] transform transition-all duration-300 hover:-translate-y-0.5 hover:shadow-[0_12px_25px_rgba(107,27,42,0.35)] active:translate-y-0">
                Ubah Password
            </button>
        </form>

        <div class="mt-8 text-center">
            <a href="{{ route('login') }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-[#6B1B2A] transition-colors group">
                <svg class="w-4 h-4 transform transition-transform group-hover:-translate-x-1" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Login
            </a>
        </div>
    </div>

</body>

</html>
