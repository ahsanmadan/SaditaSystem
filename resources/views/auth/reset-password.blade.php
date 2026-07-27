<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Ulang Password - SaditaSystem</title>
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

        /* Password strength indicator */
        .strength-bar {
            height: 3px;
            border-radius: 9999px;
            transition: width 0.3s ease, background-color 0.3s ease;
        }
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
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-[#2D1E1E] tracking-tight">Atur Ulang Password</h2>
                <p class="mt-1.5 text-sm text-gray-400 leading-relaxed max-w-xs mx-auto">
                    Tentukan password baru untuk akun Anda.
                </p>
            </div>

            {{-- Alert: Error --}}
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex items-start gap-3">
                    <svg class="w-4 h-4 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="text-xs font-semibold text-red-900">Terjadi kesalahan</p>
                        <ul class="text-xs text-red-700 mt-0.5 space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('password.update') }}" class="space-y-5" id="resetForm">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-xs font-semibold text-gray-500 mb-1.5 tracking-wide uppercase">
                        Alamat Email
                    </label>
                    <input type="email" id="email" name="email" required
                        placeholder="nama@sadita.com"
                        autocomplete="email"
                        value="{{ old('email', $email) }}"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#6B1B2A]/15 focus:border-[#6B1B2A] focus:bg-white transition-all placeholder:text-gray-300 {{ $errors->has('email') ? 'border-red-300 bg-red-50/50' : '' }}">
                </div>

                {{-- Password Baru --}}
                <div>
                    <label for="password" class="block text-xs font-semibold text-gray-500 mb-1.5 tracking-wide uppercase">
                        Password Baru
                    </label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required
                            placeholder="Minimal 8 karakter"
                            autocomplete="new-password"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#6B1B2A]/15 focus:border-[#6B1B2A] focus:bg-white transition-all placeholder:text-gray-300 pr-11">
                        <button type="button" id="toggleNew"
                            class="absolute inset-y-0 right-0 px-3.5 flex items-center text-gray-300 hover:text-[#6B1B2A] transition-colors"
                            aria-label="Toggle password visibility">
                            <svg id="eyeNew" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    {{-- Strength bar --}}
                    <div class="mt-2 flex gap-1">
                        <div id="bar1" class="strength-bar flex-1 bg-gray-200"></div>
                        <div id="bar2" class="strength-bar flex-1 bg-gray-200"></div>
                        <div id="bar3" class="strength-bar flex-1 bg-gray-200"></div>
                    </div>
                    <p id="strengthLabel" class="text-[10px] text-gray-400 mt-1"></p>
                </div>

                {{-- Konfirmasi Password --}}
                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold text-gray-500 mb-1.5 tracking-wide uppercase">
                        Konfirmasi Password Baru
                    </label>
                    <div class="relative">
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            placeholder="Ulangi password baru"
                            autocomplete="new-password"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#6B1B2A]/15 focus:border-[#6B1B2A] focus:bg-white transition-all placeholder:text-gray-300 pr-11">
                        <button type="button" id="toggleConfirm"
                            class="absolute inset-y-0 right-0 px-3.5 flex items-center text-gray-300 hover:text-[#6B1B2A] transition-colors"
                            aria-label="Toggle confirm password visibility">
                            <svg id="eyeConfirm" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    <p id="matchLabel" class="text-[10px] mt-1"></p>
                </div>

                <button type="submit" id="resetBtn"
                    class="w-full py-3 px-4 bg-[#6B1B2A] hover:bg-[#5a1623] text-white text-sm font-semibold rounded-xl shadow-[0_6px_18px_rgba(107,27,42,0.20)] transform transition-all duration-300 hover:-translate-y-0.5 hover:shadow-[0_10px_24px_rgba(107,27,42,0.30)] active:translate-y-0 flex items-center justify-center gap-2">
                    <svg id="resetSpinner" class="w-4 h-4 animate-spin hidden" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <span id="resetText">Ubah Password</span>
                </button>
            </form>
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
        // ── Toggle Password Visibility ─────────────────────────────────────────
        function setupToggle(btnId, inputId, iconId) {
            const btn   = document.getElementById(btnId);
            const input = document.getElementById(inputId);
            const icon  = document.getElementById(iconId);
            if (!btn || !input) return;
            btn.addEventListener('click', function () {
                const isPass = input.type === 'password';
                input.type = isPass ? 'text' : 'password';
                icon.innerHTML = isPass
                    ? `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />`
                    : `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />`;
            });
        }
        setupToggle('toggleNew',     'password',              'eyeNew');
        setupToggle('toggleConfirm', 'password_confirmation', 'eyeConfirm');

        // ── Password Strength ─────────────────────────────────────────────────
        const pwInput     = document.getElementById('password');
        const bar1 = document.getElementById('bar1');
        const bar2 = document.getElementById('bar2');
        const bar3 = document.getElementById('bar3');
        const strengthLabel = document.getElementById('strengthLabel');
        const levels = [
            { color: 'bg-red-400',    label: 'Lemah' },
            { color: 'bg-amber-400',  label: 'Sedang' },
            { color: 'bg-green-500',  label: 'Kuat' },
        ];

        if (pwInput) {
            pwInput.addEventListener('input', function () {
                const val = this.value;
                let score = 0;
                if (val.length >= 8)                      score++;
                if (/[A-Z]/.test(val) && /[a-z]/.test(val)) score++;
                if (/[0-9]/.test(val) || /[^a-zA-Z0-9]/.test(val)) score++;

                const bars = [bar1, bar2, bar3];
                bars.forEach((b, i) => {
                    b.className = 'strength-bar flex-1 ' + (i < score ? levels[score - 1].color : 'bg-gray-200');
                });
                strengthLabel.textContent = val.length > 0 ? (levels[score - 1]?.label ?? '') : '';
                strengthLabel.className   = 'text-[10px] mt-1 ' + (score === 1 ? 'text-red-500' : score === 2 ? 'text-amber-500' : score === 3 ? 'text-green-600' : 'text-gray-400');
            });
        }

        // ── Password Match ─────────────────────────────────────────────────────
        const confirmInput = document.getElementById('password_confirmation');
        const matchLabel   = document.getElementById('matchLabel');

        if (confirmInput) {
            confirmInput.addEventListener('input', function () {
                const match = this.value === pwInput.value;
                matchLabel.textContent  = this.value.length > 0 ? (match ? '✓ Password cocok' : '✗ Password tidak cocok') : '';
                matchLabel.className    = 'text-[10px] mt-1 ' + (match ? 'text-green-600' : 'text-red-500');
            });
        }

        // ── Submit Loading State ───────────────────────────────────────────────
        const resetForm    = document.getElementById('resetForm');
        const resetBtn     = document.getElementById('resetBtn');
        const resetSpinner = document.getElementById('resetSpinner');
        const resetText    = document.getElementById('resetText');

        if (resetForm) {
            resetForm.addEventListener('submit', function () {
                resetBtn.classList.add('btn-loading');
                resetSpinner.classList.remove('hidden');
                resetText.textContent = 'Menyimpan...';
            });
        }
    </script>
</body>

</html>
