@extends('layouts.app')

@section('content')
    @php
        $productName = request('product', 'Sadita Exclusive Product');
        $productPrice = request('price', 'Rp 0');
        $productImg = request('img', '/images/dekorasi-lamaran.jpg');
        $productType = request('jenis', 'Layanan Sadita');
    @endphp

    <div class="bg-[#FFFDFB] pt-[108px] pb-16 md:pb-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid gap-8 xl:grid-cols-[minmax(0,1fr)_380px]">
                <div class="order-2 min-w-0 xl:order-1">
                    <a href="/#kategori"
                        class="inline-flex items-center gap-2 text-sm font-semibold text-[#7A1F2B]/70 hover:text-[#7A1F2B] transition-colors group">
                        <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali ke koleksi
                    </a>

                    <div class="mt-6 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                        <div class="max-w-xl">
                            <h1 class="mt-4 text-4xl md:text-5xl font-bold leading-[1.05] text-[#2D1E1E]"
                                style="font-family: 'Playfair Display', serif;">
                                Isi detail pesanan tanpa ribet.
                            </h1>
                        </div>

                        <div
                            class="rounded-2xl border border-green-200 bg-white px-4 py-3 text-sm text-[#3F4A43] shadow-sm lg:min-w-[300px]">
                            <div class="flex items-start gap-3">
                                <span class="mt-1 inline-flex h-3 w-3 rounded-full bg-green-500 shadow-[0_0_0_4px_rgba(34,197,94,0.15)]"></span>
                                <div>
                                    <div class="font-semibold text-[#2D1E1E]">Slot pemesanan tersedia</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('order.store') }}" method="POST" id="orderForm" class="mt-6 space-y-6">
                        @csrf
                        <input type="hidden" name="product_name" value="{{ $productName }}">
                        <input type="hidden" name="price" value="{{ $productPrice }}">
                        <input type="hidden" name="jenis" value="{{ $productType }}">

                        <section class="rounded-[28px] border border-[#7A1F2B]/10 bg-white p-6 md:p-8 shadow-sm">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                <div class="flex items-start gap-4">
                                    <div
                                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-[#7A1F2B] text-sm font-bold text-white shadow-[0_10px_24px_rgba(122,31,43,0.24)]">
                                        1
                                    </div>
                                    <div>
                                        <h2 class="text-xl font-bold text-[#2D1E1E]"
                                            style="font-family: 'Playfair Display', serif;">
                                            Informasi pemesan
                                        </h2>
                                    </div>
                                </div>
                                <div class="rounded-full bg-[#FAF5F0] px-3 py-1 text-[11px] font-semibold text-[#7A1F2B]">
                                    Wajib diisi
                                </div>
                            </div>

                            <div class="mt-6 grid gap-5 sm:grid-cols-2">
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-[#2D1E1E]">
                                        Nama lengkap <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="senderName" name="sender_name" required
                                        class="w-full rounded-2xl border border-[#D9D3CE] bg-[#FCFAF8] px-4 py-3.5 text-sm text-[#2D1E1E] placeholder:text-[#A49A95] focus:border-[#7A1F2B] focus:outline-none focus:ring-4 focus:ring-[#7A1F2B]/10 transition-all"
                                        placeholder="Contoh: Ahsan Ramadan">
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-[#2D1E1E]">
                                        No. WhatsApp <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <span
                                            class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-semibold text-[#7A726F]">+62</span>
                                        <input type="tel" id="senderPhone" name="sender_phone" required
                                            class="w-full rounded-2xl border border-[#D9D3CE] bg-[#FCFAF8] py-3.5 pl-12 pr-4 text-sm text-[#2D1E1E] placeholder:text-[#A49A95] focus:border-[#7A1F2B] focus:outline-none focus:ring-4 focus:ring-[#7A1F2B]/10 transition-all"
                                            placeholder="81234567890">
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section class="rounded-[28px] border border-[#7A1F2B]/10 bg-white p-6 md:p-8 shadow-sm">
                            <div class="flex items-start gap-4">
                                <div
                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-[#7A1F2B] text-sm font-bold text-white shadow-[0_10px_24px_rgba(122,31,43,0.24)]">
                                    2
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-[#2D1E1E]"
                                        style="font-family: 'Playfair Display', serif;">
                                        Tujuan pengiriman
                                    </h2>
                                </div>
                            </div>

                            <div class="mt-6 grid gap-5 sm:grid-cols-2">
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-[#2D1E1E]">
                                        Penerima di lokasi <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="receiverName" name="receiver_name" required
                                        class="w-full rounded-2xl border border-[#D9D3CE] bg-[#FCFAF8] px-4 py-3.5 text-sm text-[#2D1E1E] placeholder:text-[#A49A95] focus:border-[#7A1F2B] focus:outline-none focus:ring-4 focus:ring-[#7A1F2B]/10 transition-all"
                                        placeholder="Nama penerima atau PIC">
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-[#2D1E1E]">
                                        Ditujukan kepada
                                    </label>
                                    <input type="text" id="untuk" name="untuk" value="{{ request('untuk', '') }}"
                                        class="w-full rounded-2xl border border-[#D9D3CE] bg-[#FCFAF8] px-4 py-3.5 text-sm text-[#2D1E1E] placeholder:text-[#A49A95] focus:border-[#7A1F2B] focus:outline-none focus:ring-4 focus:ring-[#7A1F2B]/10 transition-all"
                                        placeholder="Contoh: Bpk Budi / PT Merdeka">
                                </div>
                            </div>

                            <div class="mt-5">
                                <label class="mb-2 block text-sm font-semibold text-[#2D1E1E]">
                                    Alamat lengkap pengiriman <span class="text-red-500">*</span>
                                </label>
                                <textarea id="address" name="address" required rows="4"
                                    class="w-full rounded-2xl border border-[#D9D3CE] bg-[#FCFAF8] px-4 py-3.5 text-sm leading-6 text-[#2D1E1E] placeholder:text-[#A49A95] focus:border-[#7A1F2B] focus:outline-none focus:ring-4 focus:ring-[#7A1F2B]/10 transition-all resize-none"
                                    placeholder="Tuliskan alamat lengkap, nama gedung/jalan, patokan lokasi, dan catatan akses jika perlu."></textarea>
                            </div>
                        </section>

                        <section class="rounded-[28px] border border-[#7A1F2B]/10 bg-white p-6 md:p-8 shadow-sm">
                            <div class="flex items-start gap-4">
                                <div
                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-[#7A1F2B] text-sm font-bold text-white shadow-[0_10px_24px_rgba(122,31,43,0.24)]">
                                    3
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-[#2D1E1E]"
                                        style="font-family: 'Playfair Display', serif;">
                                        Jadwal dan personalisasi
                                    </h2>
                                </div>
                            </div>

                            <div class="mt-6 grid gap-5 sm:grid-cols-2">
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-[#2D1E1E]">
                                        Tanggal pengiriman <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" id="deliveryDate" name="delivery_date" required
                                        class="w-full rounded-2xl border border-[#D9D3CE] bg-[#FCFAF8] px-4 py-3.5 text-sm text-[#2D1E1E] focus:border-[#7A1F2B] focus:outline-none focus:ring-4 focus:ring-[#7A1F2B]/10 transition-all">
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-[#2D1E1E]">
                                        Waktu pengiriman <span class="text-red-500">*</span>
                                    </label>
                                    <select id="deliveryTime" name="delivery_time"
                                        class="w-full rounded-2xl border border-[#D9D3CE] bg-[#FCFAF8] px-4 py-3.5 text-sm text-[#2D1E1E] focus:border-[#7A1F2B] focus:outline-none focus:ring-4 focus:ring-[#7A1F2B]/10 transition-all appearance-none">
                                        <option value="09:00:00">Pagi (08:00 - 12:00)</option>
                                        <option value="13:00:00">Siang (12:00 - 16:00)</option>
                                        <option value="17:00:00">Sore (16:00 - 20:00)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-5">
                                <label class="mb-2 block text-sm font-semibold text-[#2D1E1E]">
                                    Pesan atau tulisan di produk
                                </label>
                                <textarea id="greetingMsg" name="greeting_msg" rows="4"
                                    class="w-full rounded-2xl border border-[#D9D3CE] bg-[#FCFAF8] px-4 py-3.5 text-sm leading-6 text-[#2D1E1E] placeholder:text-[#A49A95] focus:border-[#7A1F2B] focus:outline-none focus:ring-4 focus:ring-[#7A1F2B]/10 transition-all resize-none"
                                    placeholder="Contoh: Turut berduka cita atas berpulangnya almarhumah ibunda kami. Dari keluarga besar PT Merdeka."></textarea>
                            </div>

                            <div class="mt-5">
                                <label class="mb-2 block text-sm font-semibold text-[#2D1E1E]">
                                    Instruksi khusus
                                </label>
                                <input type="text" id="specialInstruction" name="special_instruction"
                                    class="w-full rounded-2xl border border-[#D9D3CE] bg-[#FCFAF8] px-4 py-3.5 text-sm text-[#2D1E1E] placeholder:text-[#A49A95] focus:border-[#7A1F2B] focus:outline-none focus:ring-4 focus:ring-[#7A1F2B]/10 transition-all"
                                    placeholder="Contoh: Tolong hubungi satpam dulu sebelum masuk area gedung.">
                            </div>
                        </section>

                        <section class="rounded-[28px] border border-[#7A1F2B]/10 bg-white p-6 md:p-8 shadow-sm">
                            <div class="flex items-start gap-4">
                                <div
                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-[#7A1F2B] text-sm font-bold text-white shadow-[0_10px_24px_rgba(122,31,43,0.24)]">
                                    4
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-[#2D1E1E]"
                                        style="font-family: 'Playfair Display', serif;">
                                        Kode promo
                                    </h2>
                                </div>
                            </div>

                            <div class="mt-6 space-y-3">
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-[#2D1E1E]">
                                        Masukkan kode promo
                                    </label>
                                    <input type="text" id="promoCode" name="promo_code"
                                        value="{{ old('promo_code') }}"
                                        class="w-full rounded-2xl border border-[#D9D3CE] bg-[#FCFAF8] px-4 py-3.5 text-sm uppercase tracking-[0.2em] text-[#2D1E1E] placeholder:text-[#A49A95] focus:border-[#7A1F2B] focus:outline-none focus:ring-4 focus:ring-[#7A1F2B]/10 transition-all"
                                        placeholder="Contoh: SADITA10">
                                </div>

                                @error('promo_code')
                                    <p class="text-sm font-semibold text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </section>

                        <section class="rounded-[28px] border border-[#7A1F2B]/10 bg-[#2D1E1E] p-6 md:p-8 text-white shadow-[0_24px_50px_rgba(45,30,30,0.18)]">
                            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                                <div>
                                    <div class="text-xs font-semibold uppercase tracking-[0.24em] text-[#E8C87A]">
                                        Langkah terakhir
                                    </div>
                                    <h2 class="mt-2 text-2xl font-bold" style="font-family: 'Playfair Display', serif;">
                                        Lanjut ke pembayaran
                                    </h2>
                                </div>
                                <div class="rounded-2xl bg-white/8 px-4 py-3 text-sm">
                                    <div class="text-white/60">Estimasi total</div>
                                    <div class="mt-1 text-2xl font-bold text-[#E8C87A]">{{ $productPrice }}</div>
                                </div>
                            </div>

                            <div class="mt-6 grid gap-3 md:grid-cols-3">
                                <div class="rounded-2xl border border-white/10 bg-white/6 px-4 py-4">
                                    <div class="text-xs uppercase tracking-[0.18em] text-white/50">Admin</div>
                                    <div class="mt-2 text-sm font-semibold">Konfirmasi WhatsApp</div>
                                </div>
                                <div class="rounded-2xl border border-white/10 bg-white/6 px-4 py-4">
                                    <div class="text-xs uppercase tracking-[0.18em] text-white/50">Bayar</div>
                                    <div class="mt-2 text-sm font-semibold">Pembayaran aman</div>
                                </div>
                                <div class="rounded-2xl border border-white/10 bg-white/6 px-4 py-4">
                                    <div class="text-xs uppercase tracking-[0.18em] text-white/50">Data</div>
                                    <div class="mt-2 text-sm font-semibold">Tersimpan aman</div>
                                </div>
                            </div>

                            <button type="submit"
                                class="mt-6 flex w-full items-center justify-center gap-3 rounded-2xl bg-[#E8C87A] px-6 py-4 text-sm font-bold tracking-wide text-[#4A1C24] transition-all duration-300 hover:-translate-y-0.5 hover:bg-[#dfbc64] hover:shadow-[0_14px_28px_rgba(232,200,122,0.28)]">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                                Lanjutkan ke pembayaran aman
                            </button>
                        </section>
                    </form>
                </div>

                <aside class="order-1 xl:order-2 xl:pt-16">
                    <div class="xl:sticky xl:top-[116px] space-y-5">
                        <div class="overflow-hidden rounded-[30px] border border-[#7A1F2B]/10 bg-white shadow-sm">
                            <div class="relative aspect-[4/3] overflow-hidden bg-[#F7F1EB]">
                                <img src="{{ $productImg }}" alt="{{ $productName }}"
                                    class="h-full w-full object-cover object-center">
                                <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-[#2D1E1E]/70 via-[#2D1E1E]/10 to-transparent p-5">
                                    <div class="inline-flex rounded-full bg-white/90 px-3 py-1 text-[10px] font-bold uppercase tracking-[0.22em] text-[#7A1F2B]">
                                        {{ $productType }}
                                    </div>
                                </div>
                            </div>

                            <div class="p-6">
                                <h2 class="mt-3 text-2xl font-bold leading-tight text-[#2D1E1E]"
                                    style="font-family: 'Playfair Display', serif;">
                                    {{ $productName }}
                                </h2>

                                <div class="mt-5 rounded-2xl bg-[#FAF5F0] p-4">
                                    <div class="flex items-center justify-between gap-4">
                                        <div>
                                            <div class="text-xs uppercase tracking-[0.18em] text-[#7A1F2B]/60">Estimasi harga</div>
                                            <div class="mt-1 text-2xl font-bold text-[#7A1F2B]">{{ $productPrice }}</div>
                                        </div>
                                        <div class="rounded-full bg-white px-3 py-1 text-[11px] font-semibold text-[#7A1F2B] shadow-sm">
                                            1 item
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-5 grid gap-2 text-sm text-[#6F6560]">
                                    <div class="flex items-center gap-2">
                                        <span class="h-2 w-2 rounded-full bg-green-500"></span>
                                        Kualitas premium
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="h-2 w-2 rounded-full bg-[#7A1F2B]"></span>
                                        Konfirmasi via WhatsApp
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                                        Pembayaran aman
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-[28px] border border-[#7A1F2B]/10 bg-[#2D1E1E] p-5 text-white shadow-sm">
                            <a href="https://wa.me/62812616155335?text=Halo%20Sadita%2C%20saya%20ingin%20bertanya%20sebelum%20melanjutkan%20pemesanan."
                                target="_blank" rel="noopener noreferrer"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-white px-4 py-3 text-sm font-semibold text-[#7A1F2B] transition hover:bg-[#FAF5F0]">
                                Konsultasi via WhatsApp
                            </a>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>

    <script>
        const deliveryDateInput = document.getElementById('deliveryDate');
        const orderForm = document.getElementById('orderForm');

        if (deliveryDateInput) {
            const today = new Date().toISOString().split('T')[0];
            deliveryDateInput.setAttribute('min', today);
        }

        if (orderForm) {
            orderForm.addEventListener('submit', function() {
                const btn = this.querySelector('button[type="submit"]');

                if (!btn) return;

                btn.innerHTML =
                    '<svg class="h-5 w-5 animate-spin text-[#4A1C24]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses pesanan...';
                btn.classList.add('opacity-80', 'cursor-not-allowed', 'pointer-events-none');
            });
        }
    </script>
@endsection
