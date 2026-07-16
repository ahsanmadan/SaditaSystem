<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - SaditaSystem</title>
    {{-- Favicon --}}
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="shortcut icon" href="/favicon-32x32.png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .font-playfair {
            font-family: 'Playfair Display', serif;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.5s cubic-bezier(0.22, 1, 0.36, 1) both;
        }
        .btn-loading { pointer-events: none; opacity: 0.75; }
    </style>
</head>

<body class="bg-[#FAF6F0] min-h-screen flex items-center justify-center p-4 selection:bg-[#6B1B2A] selection:text-white">

    <div class="w-full max-w-md animate-fade-in-up">

        {{-- Card --}}
        <div class="bg-white border border-gray-100 rounded-2xl p-8 sm:p-10 shadow-[0_8px_40px_rgba(0,0,0,0.07)] relative overflow-hidden">
            {{-- Top accent bar --}}
            <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-[#6B1B2A] via-[#E8C87A] to-[#6B1B2A]"></div>

            {{-- Header --}}
            <div class="text-center mb-8">
                <a href="{{ route('login') }}" class="inline-block mb-4">
                    <h1 class="text-3xl font-bold italic font-playfair tracking-wide text-[#6B1B2A]">Sadita</h1>
                </a>
                <div class="w-12 h-12 rounded-full bg-[#6B1B2A]/8 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-[#6B1B2A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-[#2D1E1E] tracking-tight">Lupa Password?</h2>
                <p class="mt-1.5 text-sm text-gray-400 leading-relaxed max-w-xs mx-auto">
                    Masukkan email Anda dan kami akan mengirimkan tautan untuk mengatur ulang password.
                </p>
            </div>

            {{-- Alert: Sukses --}}
            @if (session('status'))
                <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4 flex items-start gap-3">
                    <svg class="w-4 h-4 text-green-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="text-xs font-semibold text-green-900">Tautan Dikirim!</p>
                        <p class="text-xs text-green-700 mt-0.5">{{ session('status') }}</p>
                    </div>
                </div>
            @endif

            {{-- Alert: Error --}}
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex items-start gap-3">
                    <svg class="w-4 h-4 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="text-xs font-semibold text-red-900">Gagal mengirim tautan</p>
                        <ul class="text-xs text-red-700 mt-0.5 space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('password.email') }}" class="space-y-5" id="forgotForm">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-semibold text-gray-500 mb-1.5 tracking-wide uppercase">
                        Alamat Email
                    </label>
                    <input type="email" id="email" name="email" required
                        placeholder="nama@sadita.com"
                        autocomplete="email"
                        value="{{ old('email') }}"
                        class="w-full px-4 py-3 rounded-xl border {{ $errors->has('email') ? 'border-red-300 bg-red-50/50' : 'border-gray-200 bg-gray-50' }} text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#6B1B2A]/15 focus:border-[#6B1B2A] focus:bg-white transition-all placeholder:text-gray-300">
                </div>

                <button type="submit" id="sendBtn"
                    class="w-full py-3 px-4 bg-[#6B1B2A] hover:bg-[#5a1623] text-white text-sm font-semibold rounded-xl shadow-[0_6px_18px_rgba(107,27,42,0.20)] transform transition-all duration-300 hover:-translate-y-0.5 hover:shadow-[0_10px_24px_rgba(107,27,42,0.30)] active:translate-y-0 flex items-center justify-center gap-2">
                    <svg id="sendSpinner" class="w-4 h-4 animate-spin hidden" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <span id="sendText">Kirim Tautan Reset</span>
                </button>
            </form>

            {{-- WhatsApp Fallback untuk Staff --}}
            <div class="mt-6 pt-6 border-t border-gray-100">
                <p class="text-[11px] text-gray-400 text-center mb-3">
                    Tidak punya akses email? Minta owner reset langsung.
                </p>
                <a href="https://wa.me/6289653090248?text=Halo%20Owner%2C%20saya%20lupa%20password%20akun%20Sadita%20System%20saya.%20Mohon%20bantuannya%20untuk%20melakukan%20reset%20password%20sementara."
                    target="_blank" rel="noopener noreferrer"
                    class="flex items-center justify-center gap-2 w-full py-2.5 px-4 bg-[#25D366]/10 hover:bg-[#25D366]/15 text-[#128C4A] border border-[#25D366]/25 text-xs font-semibold rounded-xl transition-all duration-200">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z" />
                    </svg>
                    Hubungi Owner via WhatsApp
                </a>
            </div>
        </div>

        {{-- Kembali --}}
        <div class="mt-6 text-center">
            <a href="{{ route('login') }}"
                class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-400 hover:text-[#6B1B2A] transition-colors group">
                <svg class="w-3.5 h-3.5 transition-transform group-hover:-translate-x-0.5" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Login
            </a>
        </div>
    </div>

    <script>
        const forgotForm  = document.getElementById('forgotForm');
        const sendBtn     = document.getElementById('sendBtn');
        const sendSpinner = document.getElementById('sendSpinner');
        const sendText    = document.getElementById('sendText');

        if (forgotForm) {
            forgotForm.addEventListener('submit', function () {
                sendBtn.classList.add('btn-loading');
                sendSpinner.classList.remove('hidden');
                sendText.textContent = 'Mengirim...';
            });
        }
    </script>
</body>

</html>
