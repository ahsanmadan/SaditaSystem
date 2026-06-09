<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - SaditaSystem</title>
    {{-- Favicon --}}
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon.png">
    <link class="apple-touch-icon" sizes="180x180" href="/favicon.png">
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

<body class="bg-[#FAF6F0] min-h-screen flex items-center justify-center p-4 selection:bg-[#6B1B2A] selection:text-white">

    <div class="w-full max-w-md bg-[#FAF6F0] border border-gray-100 rounded-3xl p-8 sm:p-10 shadow-2xl relative overflow-hidden bg-gradient-to-br from-white to-[#FAF6F0]">
        <!-- Top accent decoration -->
        <div class="absolute top-0 inset-x-0 h-2 bg-gradient-to-r from-[#6B1B2A] via-[#E8C87A] to-[#6B1B2A]"></div>

        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold italic font-playfair tracking-wide text-[#6B1B2A] mb-3">Sadita</h1>
            <h2 class="text-2xl font-bold text-[#2D1E1E]">Lupa Password?</h2>
            <p class="mt-2 text-xs text-gray-500 leading-relaxed">Masukkan email Anda untuk menerima tautan reset password secara mandiri.</p>
        </div>

        @if (session('status'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 text-sm rounded-xl p-4 flex items-start gap-3">
                <svg class="w-5 h-5 text-green-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <span class="font-semibold text-green-900">Sukses!</span>
                    <p class="text-xs text-green-700 mt-1">{{ session('status') }}</p>
                </div>
            </div>
        @endif

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

        <!-- Form Lupa Password via Email -->
        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf

            <div>
                <label for="email" class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Alamat Email</label>
                <input type="email" id="email" name="email" required placeholder="nama@sadita.com" value="{{ old('email') }}"
                    class="w-full px-5 py-3.5 rounded-xl border border-gray-200 bg-white text-gray-900 text-sm focus:outline-none focus:ring-4 focus:ring-[#6B1B2A]/10 focus:border-[#6B1B2A] transition-all shadow-sm placeholder:text-gray-400">
            </div>

            <button type="submit"
                class="w-full py-3.5 px-4 bg-[#6B1B2A] hover:bg-[#5a1623] text-white text-sm font-bold rounded-xl shadow-[0_8px_20px_rgba(107,27,42,0.25)] transform transition-all duration-300 hover:-translate-y-0.5 hover:shadow-[0_12px_25px_rgba(107,27,42,0.35)] active:translate-y-0">
                Kirim Tautan Reset
            </button>
        </form>

        <!-- WhatsApp Fallback Box for Staff -->
        <div class="mt-8 border-t border-dashed border-gray-200 pt-8">
            <div class="bg-[#6B1B2A]/5 border border-[#6B1B2A]/10 rounded-2xl p-5 text-center">
                <h3 class="text-sm font-bold text-[#6B1B2A] mb-1">Butuh Bantuan Cepat?</h3>
                <p class="text-[11px] text-gray-500 leading-relaxed mb-4">
                    Jika Anda adalah Staff atau Admin, Anda dapat meminta Owner untuk melakukan reset password secara langsung.
                </p>
                <a href="https://wa.me/6289653090248?text=Halo%20Owner%2C%20saya%20lupa%20password%20akun%20Sadita%20System%20saya.%20Mohon%20bantuannya%20untuk%20melakukan%20reset%20password%20sementara." 
                   target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center justify-center gap-2 w-full py-3 px-4 bg-[#25D366] hover:bg-[#20ba59] text-white text-xs font-bold rounded-xl shadow-[0_4px_12px_rgba(37,211,102,0.2)] transform transition-all duration-300 hover:-translate-y-0.5 hover:shadow-[0_6px_15px_rgba(37,211,102,0.3)] active:translate-y-0">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/>
                    </svg>
                    Hubungi Owner via WhatsApp
                </a>
            </div>
        </div>

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
