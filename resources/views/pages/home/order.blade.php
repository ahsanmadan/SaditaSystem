@extends('layouts.app')

@section('content')
    @php
        $productName = request('product', 'Sadita Exclusive Product');
        $productPrice = request('price', 'Rp 0');
        $productImg = request('img', '/images/dekorasi-1.jpg');
    @endphp

    <div class="min-h-screen bg-[#FFFDFB] mt-8 md:h-[100svh] flex flex-col md:flex-row pt-[72px] font-sans">

        <!-- Left Column: Order Summary & Trust -->
        <div class="w-full md:w-[45%] lg:w-[40%] p-6 md:p-10 lg:p-14 flex flex-col overflow-y-auto bg-white border-r border-gray-100 shadow-[4px_0_24px_rgba(0,0,0,0.02)] z-10 relative">
            <a href="/#kategori" class="inline-flex items-center gap-2 text-xs font-semibold text-gray-400 hover:text-[#7A1F2B] transition-colors mb-8 group">
                <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                KEMBALI
            </a>

            <h1 class="text-3xl md:text-4xl font-bold text-[#2D1E1E] leading-tight mb-8"
                style="font-family: 'Playfair Display', serif;">
                Selesaikan<br>Pesanan Anda
            </h1>

            <div class="w-full aspect-[4/3] rounded-2xl bg-gray-50 mb-6 relative overflow-hidden shadow-inner group">
                <div class="absolute inset-0 bg-gradient-to-tr from-gray-100 to-gray-50"></div>
                <img src="{{ $productImg }}" alt="{{ $productName }}" class="w-full h-full object-cover relative z-10 transition-transform duration-700 group-hover:scale-105">
                <div class="absolute top-3 right-3 z-20 bg-white/90 backdrop-blur-sm px-3 py-1.5 rounded-full text-[10px] font-bold text-[#7A1F2B] uppercase tracking-widest shadow-sm">
                    Pilihan Tepat
                </div>
            </div>

            <div class="mb-8 bg-[#FAF5F0] rounded-2xl p-5 border border-[#7A1F2B]/10">
                <h2 class="text-lg font-bold text-[#2D1E1E]">{{ $productName }}</h2>
                <p class="text-xs text-gray-500 mt-1 mb-3">Kualitas Premium · Dirangkai oleh Profesional</p>
                <p class="text-xl font-bold text-[#7A1F2B]">{{ $productPrice }}</p>
            </div>

            <div class="mt-auto border-t border-gray-100 pt-6 space-y-4">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-green-50 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-[#2D1E1E] uppercase tracking-wider">Garansi Kualitas 100%</h4>
                        <p class="text-[11px] text-gray-500 mt-0.5">Bunga segar terbaik & pengerjaan detail</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-[#2D1E1E] uppercase tracking-wider">Transaksi Aman</h4>
                        <p class="text-[11px] text-gray-500 mt-0.5">Pembayaran terenkripsi & data terlindungi</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Order Form -->
        <div class="w-full md:w-[55%] lg:w-[60%] bg-[#FDFCFB] overflow-y-auto">
            <form action="{{ route('order.store') }}" method="POST" id="orderForm"
                class="p-6 md:p-10 lg:p-14 max-w-2xl mx-auto min-h-full flex flex-col pb-24 md:pb-16">
                @csrf
                <input type="hidden" name="product_name" value="{{ $productName }}">
                <input type="hidden" name="price" value="{{ $productPrice }}">

                <div class="bg-white rounded-2xl p-4 shadow-sm border border-green-100 flex items-center gap-3 mb-8">
                    <span class="relative flex h-3 w-3">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                    </span>
                    <p class="text-xs text-gray-600">Slot pemesanan <span class="font-bold text-[#2D1E1E]">tersedia</span>. Pesan sekarang sebelum kehabisan!</p>
                </div>

                <!-- 01 Informasi Pengirim & Produk -->
                <div class="mb-10">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-full bg-[#7A1F2B] text-white flex items-center justify-center font-bold text-xs shadow-md">1</div>
                        <div>
                            <h3 class="text-sm font-bold text-[#2D1E1E] uppercase tracking-wider">Informasi Pengirim</h3>
                            <p class="text-[10px] text-gray-400 mt-0.5">Kami akan menghubungi Anda untuk konfirmasi</p>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-bold text-[#2D1E1E] mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                                <input type="text" id="senderName" name="sender_name" required
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-[#2D1E1E] focus:outline-none focus:ring-2 focus:ring-[#7A1F2B]/20 focus:border-[#7A1F2B] transition-all"
                                    placeholder="Masukkan nama Anda">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-[#2D1E1E] mb-2">No. WhatsApp <span class="text-red-500">*</span></label>
                                <div class="flex relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-semibold text-gray-500">+62</span>
                                    <input type="tel" id="senderPhone" name="sender_phone" required
                                        class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-12 pr-4 py-3 text-sm text-[#2D1E1E] focus:outline-none focus:ring-2 focus:ring-[#7A1F2B]/20 focus:border-[#7A1F2B] transition-all"
                                        placeholder="8123456789">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 02 Logistik Pengiriman -->
                <div class="mb-10">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-full bg-[#7A1F2B] text-white flex items-center justify-center font-bold text-xs shadow-md">2</div>
                        <div>
                            <h3 class="text-sm font-bold text-[#2D1E1E] uppercase tracking-wider">Tujuan Pengiriman</h3>
                            <p class="text-[10px] text-gray-400 mt-0.5">Pastikan alamat dan nama penerima benar</p>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-bold text-[#2D1E1E] mb-2">Penerima di Lokasi <span class="text-red-500">*</span></label>
                                <input type="text" id="receiverName" name="receiver_name" required
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-[#2D1E1E] focus:outline-none focus:ring-2 focus:ring-[#7A1F2B]/20 focus:border-[#7A1F2B] transition-all"
                                    placeholder="Nama penerima/PIC">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-[#2D1E1E] mb-2">Ditujukan Kepada (Opsional)</label>
                                <input type="text" id="untuk" name="untuk" value="{{ request('untuk', '') }}"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-[#2D1E1E] focus:outline-none focus:ring-2 focus:ring-[#7A1F2B]/20 focus:border-[#7A1F2B] transition-all"
                                    placeholder="Cth: Bpk Budi / PT Merdeka">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#2D1E1E] mb-2">Alamat Lengkap Pengiriman <span class="text-red-500">*</span></label>
                            <textarea id="address" name="address" required rows="3"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-[#2D1E1E] focus:outline-none focus:ring-2 focus:ring-[#7A1F2B]/20 focus:border-[#7A1F2B] transition-all resize-none"
                                placeholder="Tuliskan alamat lengkap beserta patokan jika ada..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- 03 Personalisasi & Jadwal -->
                <div class="mb-10 flex-1">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-full bg-[#7A1F2B] text-white flex items-center justify-center font-bold text-xs shadow-md">3</div>
                        <div>
                            <h3 class="text-sm font-bold text-[#2D1E1E] uppercase tracking-wider">Personalisasi & Jadwal</h3>
                            <p class="text-[10px] text-gray-400 mt-0.5">Buat momen jadi lebih berkesan</p>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-bold text-[#2D1E1E] mb-2">Tanggal Pengiriman <span class="text-red-500">*</span></label>
                                <input type="date" id="deliveryDate" name="delivery_date" required
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-[#2D1E1E] focus:outline-none focus:ring-2 focus:ring-[#7A1F2B]/20 focus:border-[#7A1F2B] transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-[#2D1E1E] mb-2">Waktu Pengiriman <span class="text-red-500">*</span></label>
                                <select id="deliveryTime" name="delivery_time"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-[#2D1E1E] focus:outline-none focus:ring-2 focus:ring-[#7A1F2B]/20 focus:border-[#7A1F2B] transition-all appearance-none">
                                    <option value="Pagi (08:00 - 12:00)">Pagi (08:00 - 12:00)</option>
                                    <option value="Siang (12:00 - 16:00)">Siang (12:00 - 16:00)</option>
                                    <option value="Sore (16:00 - 20:00)">Sore (16:00 - 20:00)</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#2D1E1E] mb-2">Pesan / Tulisan di Produk</label>
                            <textarea id="greetingMsg" name="greeting_msg" rows="3"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-[#2D1E1E] focus:outline-none focus:ring-2 focus:ring-[#7A1F2B]/20 focus:border-[#7A1F2B] transition-all resize-none"
                                placeholder="Contoh: Happy Wedding Budi & Siti. Dari: Teman SD"></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#2D1E1E] mb-2">Instruksi Khusus (Opsional)</label>
                            <input type="text" id="specialInstruction" name="special_instruction"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-[#2D1E1E] focus:outline-none focus:ring-2 focus:ring-[#7A1F2B]/20 focus:border-[#7A1F2B] transition-all"
                                placeholder="Contoh: Letakkan di lobby jika penerima tidak ada">
                        </div>
                    </div>
                </div>

                <!-- Submit Area -->
                <div class="mt-auto pt-6 border-t border-gray-200">
                    <div class="flex items-center justify-between mb-6">
                        <span class="text-lg font-bold text-[#2D1E1E]">Total Pembayaran</span>
                        <span class="text-2xl font-bold text-[#7A1F2B]">{{ $productPrice }}</span>
                    </div>

                    <button type="submit"
                        class="w-full bg-[#7A1F2B] text-white py-4 px-6 rounded-2xl font-bold text-sm sm:text-base tracking-wide hover:bg-[#5e1721] transition-all duration-300 shadow-[0_8px_20px_rgba(122,31,43,0.25)] hover:shadow-[0_12px_25px_rgba(122,31,43,0.35)] hover:-translate-y-1 flex items-center justify-center gap-3 group">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        Lanjutkan ke Pembayaran Aman
                    </button>
                    
                    <div class="flex items-center justify-center gap-4 mt-6 opacity-60">
                        <svg class="h-6" viewBox="0 0 100 30" fill="currentColor"><path d="M46.7 17.5h-5.6v-5.6h5.6v5.6z M50.4 20h-13v-13h13v13z"/></svg> <!-- Placeholder untuk logo bank/qris -->
                        <div class="text-[10px] uppercase font-bold tracking-widest text-gray-500">Supported by Midtrans</div>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <script>
        // Set minimum date to today
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('deliveryDate').setAttribute('min', today);

        document.getElementById('orderForm').addEventListener('submit', function(e) {
            const btn = this.querySelector('button[type="submit"]');
            btn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses Pesanan...';
            btn.classList.add('opacity-80', 'cursor-not-allowed', 'pointer-events-none');
        });
    </script>
@endsection
