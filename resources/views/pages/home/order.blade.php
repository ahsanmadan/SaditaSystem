@extends('layouts.app')

@section('content')
    @php
        $productName = request('product', 'Sadita Exclusive Product');
        $productPrice = request('price', 'Rp 0');
        $productImg = request('img', '/images/dekorasi-1.jpg');
        // Convert 'Rp 150.000' to just '150.000' or similar if needed, or just display as is.
    @endphp

    <div class="min-h-screen bg-white mt-8 md:h-[100svh] flex flex-col md:flex-row pt-[72px] font-sans">

        <!-- Left Column: Order Summary -->
        <div class="w-full md:w-1/2 p-6 md:p-12 lg:p-16 flex flex-col overflow-y-auto bg-white border-r border-gray-100">
            <h1 class="text-3xl md:text-5xl font-bold text-[#2D1E1E] leading-none mb-8"
                style="font-family: 'Playfair Display', serif;">
                KONFIRMASI<br>PESANAN
            </h1>

            <div class="w-full aspect-square bg-gray-100 mb-6 relative overflow-hidden">
                <!-- Diagonal lines placeholder if image fails -->
                <div class="absolute inset-0 opacity-10 pointer-events-none"
                    style="background-image: linear-gradient(45deg, #000 25%, transparent 25%, transparent 75%, #000 75%, #000), linear-gradient(45deg, #000 25%, transparent 25%, transparent 75%, #000 75%, #000); background-size: 20px 20px; background-position: 0 0, 10px 10px;">
                </div>

                <img src="{{ $productImg }}" alt="{{ $productName }}" class="w-full h-full object-cover relative z-10">
            </div>

            <div class="mb-8">
                <h2 class="text-lg md:text-xl font-bold text-[#2D1E1E] uppercase tracking-wide">{{ $productName }}</h2>
                <p class="text-xs text-gray-500 uppercase tracking-widest mt-1 mb-2">Sadita Collection</p>
                <p class="text-base font-semibold text-[#2D1E1E]">{{ $productPrice }}</p>
            </div>

            <div class="border-t border-gray-200 pt-6 mt-auto">
                <div class="flex justify-between items-center mb-3">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Subtotal</span>
                    <span class="text-xs font-bold text-[#2D1E1E] uppercase">{{ $productPrice }}</span>
                </div>
                <div class="flex justify-between items-center mb-6">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Pengiriman</span>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Dihitung Otomatis</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm font-bold text-[#2D1E1E] uppercase tracking-wider">Total</span>
                    <span class="text-base font-bold text-[#2D1E1E] uppercase">{{ $productPrice }}</span>
                </div>
            </div>
        </div>

        <!-- Right Column: Order Form -->
        <div class="w-full md:w-1/2 bg-[#F8F9FA] overflow-y-auto">
            <form action="#" method="POST" id="orderForm"
                class="p-6 md:p-12 lg:p-16 max-w-xl mx-auto min-h-full flex flex-col pb-24 md:pb-16">
                @csrf

                <!-- Hidden input for WhatsApp text generation -->
                <input type="hidden" name="text" id="waText" value="">

                <!-- 01 Informasi Pengirim -->
                <div class="mb-10">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="bg-black text-white text-[10px] font-bold px-2 py-1 tracking-widest">01</span>
                        <h3 class="text-xs font-bold text-[#2D1E1E] uppercase tracking-[0.2em]">Informasi Pengirim</h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-semibold text-gray-500 uppercase tracking-widest mb-2">Nama
                                Lengkap</label>
                            <input type="text" id="senderName" required
                                class="w-full bg-transparent border-b border-gray-300 px-0 py-2 text-sm text-[#2D1E1E] focus:outline-none focus:border-black transition-colors"
                                placeholder="Masukkan nama Anda">
                        </div>
                        <div>
                            <label class="block text-[10px] font-semibold text-gray-500 uppercase tracking-widest mb-2">No.
                                WhatsApp</label>
                            <div class="flex">
                                <span class="border-b border-gray-300 py-2 pr-2 text-sm text-gray-400">+62</span>
                                <input type="tel" id="senderPhone" required
                                    class="w-full bg-transparent border-b border-gray-300 px-0 py-2 text-sm text-[#2D1E1E] focus:outline-none focus:border-black transition-colors"
                                    placeholder="8123456789">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 02 Logistik Pengiriman -->
                <div class="mb-10">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="bg-black text-white text-[10px] font-bold px-2 py-1 tracking-widest">02</span>
                        <h3 class="text-xs font-bold text-[#2D1E1E] uppercase tracking-[0.2em]">Logistik Pengiriman</h3>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-semibold text-gray-500 uppercase tracking-widest mb-2">Nama
                                Penerima</label>
                            <input type="text" id="receiverName" required
                                class="w-full bg-transparent border-b border-gray-300 px-0 py-2 text-sm text-[#2D1E1E] focus:outline-none focus:border-black transition-colors"
                                placeholder="Nama penerima di lokasi">
                        </div>
                        <div>
                            <label
                                class="block text-[10px] font-semibold text-gray-500 uppercase tracking-widest mb-2">Alamat
                                Acara / Lokasi</label>
                            <textarea id="address" required rows="2"
                                class="w-full bg-transparent border-b border-gray-300 px-0 py-2 text-sm text-[#2D1E1E] focus:outline-none focus:border-black transition-colors resize-none"
                                placeholder="Jl. Arsitektur No. 10, Jakarta Selatan"></textarea>
                        </div>
                    </div>
                </div>

                <!-- 03 Personalisasi & Jadwal -->
                <div class="mb-10 flex-1">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="bg-black text-white text-[10px] font-bold px-2 py-1 tracking-widest">03</span>
                        <h3 class="text-xs font-bold text-[#2D1E1E] uppercase tracking-[0.2em]">Personalisasi & Jadwal</h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label
                                class="block text-[10px] font-semibold text-gray-500 uppercase tracking-widest mb-2">Tanggal
                                Pengiriman</label>
                            <input type="date" id="deliveryDate" required
                                class="w-full bg-transparent border-b border-gray-300 px-0 py-2 text-sm text-[#2D1E1E] focus:outline-none focus:border-black transition-colors">
                        </div>
                        <div>
                            <label
                                class="block text-[10px] font-semibold text-gray-500 uppercase tracking-widest mb-2">Waktu
                                Pengiriman</label>
                            <select id="deliveryTime"
                                class="w-full bg-transparent border-b border-gray-300 px-0 py-2 text-sm text-[#2D1E1E] focus:outline-none focus:border-black transition-colors appearance-none">
                                <option value="Pagi (08:00 - 12:00)">Pagi (08:00 - 12:00)</option>
                                <option value="Siang (12:00 - 16:00)">Siang (12:00 - 16:00)</option>
                                <option value="Sore (16:00 - 20:00)">Sore (16:00 - 20:00)</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label
                                class="block text-[10px] font-semibold text-gray-500 uppercase tracking-widest mb-2">Pesan
                                Kartu Ucapan</label>
                            <textarea id="greetingMsg" rows="3"
                                class="w-full bg-transparent border-b border-gray-300 px-0 py-2 text-sm text-[#2D1E1E] focus:outline-none focus:border-black transition-colors resize-none"
                                placeholder="Tuliskan pesan personal Anda di sini..."></textarea>
                        </div>
                        <div>
                            <label
                                class="block text-[10px] font-semibold text-gray-500 uppercase tracking-widest mb-2">Instruksi
                                Khusus (Opsional)</label>
                            <input type="text" id="specialInstruction"
                                class="w-full bg-transparent border-b border-gray-300 px-0 py-2 text-sm text-[#2D1E1E] focus:outline-none focus:border-black transition-colors"
                                placeholder="Contoh: Letakkan di lobby jika penerima tidak ada">
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-auto pt-6">
                    <button type="submit"
                        class="w-full btn-primary bg-[#7A1F2B] text-white py-4 px-6 rounded-2xl font-bold text-sm uppercase tracking-widest hover:bg-[#5e1721] transition-all duration-300 shadow-lg hover:shadow-xl flex items-center justify-center gap-3 group">
                        Bayar
                        <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </button>
                    <p class="text-[9px] sm:text-[10px] text-center text-gray-400 uppercase tracking-widest mt-4">
                        Dengan melanjutkan, Anda menyetujui ketentuan layanan Sadita Floral Architecture.
                    </p>
                </div>

            </form>
        </div>
    </div>

    <script>
        document.getElementById('orderForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Payment gateway flow will go here later
            alert('Akan diteruskan ke sistem Payment Gateway & Database. (Alur masih dalam tahap pengembangan)');
        });
    </script>
@endsection
