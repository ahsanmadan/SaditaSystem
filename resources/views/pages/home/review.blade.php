@extends('layouts.app')

@section('content')
    @php
        $customerName = $review->pesanan?->pelanggan?->nama_lengkap ?? $review->nama_pengulas ?? 'Pelanggan Sadita';
        $productName = $review->produk?->nama ?? 'Produk Sadita';
        $categoryName = $review->produk?->kategori?->nama ?? 'Layanan Sadita';
    @endphp

    <section class="bg-[#FFFDFB] py-16 sm:py-20">
        <div class="mx-auto max-w-3xl px-5 sm:px-6 lg:px-8">
            <div class="rounded-[2rem] border border-[#eadfd4] bg-white shadow-[0_20px_50px_rgba(80,44,33,0.06)]">
                <div class="border-b border-[#f1e7de] px-6 py-6 sm:px-8">
                    <div class="text-[11px] font-semibold uppercase tracking-[0.22em] text-[#7A1F2B]/70">Ulasan Sadita</div>
                    <h1 class="mt-2 text-3xl font-bold text-[#2D1E1E] sm:text-4xl">Bagikan pengalaman Anda</h1>
                    <p class="mt-3 text-sm leading-7 text-[#75645d]">
                        {{ $productName }} &bull; {{ $categoryName }}
                    </p>
                </div>

                <div class="px-6 py-6 sm:px-8 sm:py-8">
                    @if (session('success'))
                        <div class="mb-6 rounded-[1.25rem] border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('info'))
                        <div class="mb-6 rounded-[1.25rem] border border-amber-200 bg-amber-50 px-5 py-4 text-sm font-medium text-amber-800">
                            {{ session('info') }}
                        </div>
                    @endif

                    @if ($isUsed)
                        <div class="rounded-[1.5rem] border border-[#eadfd4] bg-[#fffaf6] px-6 py-8 text-center">
                            <h2 class="text-xl font-semibold text-[#2D1E1E]">Ulasan sudah terkirim</h2>
                            <p class="mt-3 text-sm leading-7 text-[#75645d]">
                                Terima kasih, link ini sudah pernah dipakai untuk mengirim ulasan.
                            </p>
                            <a
                                href="{{ route('home') }}"
                                class="mt-5 inline-flex items-center justify-center rounded-full bg-[#7A1F2B] px-6 py-3 text-sm font-semibold text-white transition hover:bg-[#65202a]"
                            >
                                Kembali ke beranda
                            </a>
                        </div>
                    @else
                        <form method="POST" action="{{ route('review.store', ['token' => $review->token_ulasan]) }}" class="space-y-5">
                            @csrf

                            <div>
                                <label class="mb-2 block text-sm font-semibold text-[#4d3b37]">Nama pengulas</label>
                                <input
                                    type="text"
                                    name="nama_pengulas"
                                    value="{{ old('nama_pengulas', $customerName) }}"
                                    class="w-full rounded-[1.1rem] border border-[#eadfd4] bg-[#fffdfa] px-4 py-3 text-sm text-[#2D1E1E] outline-none transition focus:border-[#c9a27a]"
                                >
                                @error('nama_pengulas')
                                    <p class="mt-2 text-sm text-rose-700">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-semibold text-[#4d3b37]">Rating</label>
                                <div class="grid grid-cols-2 gap-3 sm:grid-cols-5">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <label class="group cursor-pointer">
                                            <input
                                                type="radio"
                                                name="rating"
                                                value="{{ $i }}"
                                                class="peer sr-only"
                                                {{ (int) old('rating', 5) === $i ? 'checked' : '' }}
                                            >
                                            <span class="flex min-h-[82px] flex-col items-center justify-center rounded-[1.1rem] border border-[#eadfd4] bg-[#fffdfa] px-3 py-4 text-center text-sm font-semibold text-[#6b4a43] shadow-[0_8px_20px_rgba(122,31,43,0.04)] transition duration-200 group-hover:-translate-y-0.5 group-hover:border-[#c89b70] group-hover:bg-[#fff7f0] group-hover:text-[#7A1F2B] peer-checked:border-[#7A1F2B] peer-checked:bg-[#7A1F2B] peer-checked:text-white peer-checked:shadow-[0_14px_28px_rgba(122,31,43,0.18)]">
                                                <span class="flex items-center gap-1 text-[#D1A44F] transition-colors duration-200 peer-checked:text-white">
                                                    @for ($star = 1; $star <= $i; $star++)
                                                        <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20" aria-hidden="true">
                                                            <path d="M10 2.5l2.2 4.46 4.93.72-3.56 3.47.84 4.9L10 13.72 5.59 16.05l.84-4.9L2.87 7.68l4.93-.72L10 2.5Z" />
                                                        </svg>
                                                    @endfor
                                                </span>
                                                <span class="mt-2 text-xs uppercase tracking-[0.16em]">{{ $i }} bintang</span>
                                            </span>
                                        </label>
                                    @endfor
                                </div>
                                @error('rating')
                                    <p class="mt-2 text-sm text-rose-700">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-semibold text-[#4d3b37]">Komentar</label>
                                <textarea
                                    name="komentar"
                                    rows="6"
                                    class="w-full rounded-[1.1rem] border border-[#eadfd4] bg-[#fffdfa] px-4 py-3 text-sm text-[#2D1E1E] outline-none transition focus:border-[#c9a27a]"
                                    placeholder="Tulis kesan Anda tentang produk dan pelayanan Sadita..."
                                >{{ old('komentar') }}</textarea>
                                @error('komentar')
                                    <p class="mt-2 text-sm text-rose-700">{{ $message }}</p>
                                @enderror
                            </div>

                            <button
                                type="submit"
                                class="inline-flex items-center justify-center rounded-full bg-[#7A1F2B] px-6 py-3 text-sm font-semibold text-white transition hover:bg-[#65202a]"
                            >
                                Kirim ulasan
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
