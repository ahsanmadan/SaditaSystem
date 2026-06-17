@extends('layouts.app')

@section('content')
    @php
        $productName = request('product', 'Sadita Exclusive Product');
        $productPrice = request('price', 'Rp 0');
        $productImg = request('img', '/images/dekorasi-1.jpg');
        // Convert 'Rp 150.000' to clean integer
        $rawPrice = (int) preg_replace('/[^0-9]/', '', $productPrice);
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
                    <span class="text-xs font-bold text-[#2D1E1E] uppercase" id="displaySubtotal">{{ $productPrice }}</span>
                </div>
                <!-- Discount Row -->
                <div class="flex justify-between items-center mb-3 hidden text-[#7A1F2B]" id="discountRow">
                    <span class="text-xs font-semibold uppercase tracking-wider">Diskon (<span id="displayDiscountCode"></span>)</span>
                    <span class="text-xs font-bold uppercase" id="displayDiscountAmount">- Rp 0</span>
                </div>
                <div class="flex justify-between items-center mb-6">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Pengiriman</span>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Dihitung Otomatis</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm font-bold text-[#2D1E1E] uppercase tracking-wider">Total</span>
                    <span class="text-base font-bold text-[#2D1E1E] uppercase" id="displayTotal">{{ $productPrice }}</span>
                </div>
            </div>
        </div>

        <!-- Right Column: Order Form -->
        <div class="w-full md:w-1/2 bg-[#F8F9FA] overflow-y-auto">
            <form action="#" method="POST" id="orderForm"
                class="p-6 md:p-12 lg:p-16 max-w-xl mx-auto min-h-full flex flex-col pb-24 md:pb-16">
                @csrf

                <!-- Hidden inputs for order details -->
                <input type="hidden" name="text" id="waText" value="">
                <input type="hidden" name="diskon_id" id="hiddenDiskonId" value="">
                <input type="hidden" name="kode_diskon" id="hiddenPromoCode" value="">
                <input type="hidden" name="potongan_diskon" id="hiddenDiscountAmount" value="0">

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
                <div class="mb-10">
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

                <!-- 04 Kode Promo / Diskon -->
                <div class="mb-10 flex-1">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="bg-black text-white text-[10px] font-bold px-2 py-1 tracking-widest">04</span>
                        <h3 class="text-xs font-bold text-[#2D1E1E] uppercase tracking-[0.2em]">Kode Promo (Opsional)</h3>
                    </div>

                    <div class="flex gap-4 items-end">
                        <div class="flex-1">
                            <label class="block text-[10px] font-semibold text-gray-500 uppercase tracking-widest mb-2">Masukkan Kode Promo</label>
                            <input type="text" id="promoCodeInput"
                                class="w-full bg-transparent border-b border-gray-300 px-0 py-2 text-sm text-[#2D1E1E] focus:outline-none focus:border-black transition-colors uppercase"
                                placeholder="Contoh: SADITA10">
                        </div>
                        <button type="button" id="applyPromoBtn"
                            class="px-5 py-2.5 bg-[#2D1E1E] text-white text-xs font-bold uppercase tracking-widest rounded-xl hover:bg-black transition-all duration-300">
                            Terapkan
                        </button>
                    </div>
                    <p class="text-xs mt-2 text-red-600 hidden font-semibold" id="promoError"></p>
                    <p class="text-xs mt-2 text-green-600 hidden font-semibold" id="promoSuccess"></p>
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
        // Discount check & apply script
        const rawPrice = {{ $rawPrice }};
        let currentDiscount = 0;
        let appliedCode = '';
        let appliedId = null;

        const promoCodeInput = document.getElementById('promoCodeInput');
        const applyPromoBtn = document.getElementById('applyPromoBtn');
        const promoError = document.getElementById('promoError');
        const promoSuccess = document.getElementById('promoSuccess');

        const discountRow = document.getElementById('discountRow');
        const displayDiscountCode = document.getElementById('displayDiscountCode');
        const displayDiscountAmount = document.getElementById('displayDiscountAmount');
        const displayTotal = document.getElementById('displayTotal');

        const hiddenDiskonId = document.getElementById('hiddenDiskonId');
        const hiddenPromoCode = document.getElementById('hiddenPromoCode');
        const hiddenDiscountAmount = document.getElementById('hiddenDiscountAmount');

        applyPromoBtn.addEventListener('click', function() {
            if (appliedCode) {
                removePromo();
                return;
            }

            const code = promoCodeInput.value.trim();
            if (!code) {
                showError('Silakan masukkan kode promo terlebih dahulu.');
                return;
            }

            applyPromoBtn.disabled = true;
            applyPromoBtn.innerText = 'Memproses...';
            
            fetch('{{ route('discount.apply') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    code: code,
                    subtotal: rawPrice
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Server error');
                }
                return response.json();
            })
            .then(data => {
                applyPromoBtn.disabled = false;
                if (data.success) {
                    applyPromo(data);
                } else {
                    showError(data.message);
                }
            })
            .catch(error => {
                applyPromoBtn.disabled = false;
                showError('Terjadi kesalahan koneksi. Silakan coba lagi.');
                console.error(error);
            });
        });

        function applyPromo(data) {
            appliedCode = data.kode;
            appliedId = data.id;
            currentDiscount = data.potongan;

            // Update display summary
            displayDiscountCode.innerText = data.kode;
            displayDiscountAmount.innerText = '- ' + data.formatted_potongan;
            discountRow.classList.remove('hidden');
            displayTotal.innerText = data.formatted_grand_total;

            // Update hidden inputs
            hiddenDiskonId.value = data.id;
            hiddenPromoCode.value = data.kode;
            hiddenDiscountAmount.value = data.potongan;

            // Update input and button state
            promoCodeInput.disabled = true;
            applyPromoBtn.innerText = 'Hapus';
            applyPromoBtn.classList.remove('bg-[#2D1E1E]', 'hover:bg-black');
            applyPromoBtn.classList.add('bg-[#7A1F2B]', 'hover:bg-[#5e1721]');
            
            promoError.classList.add('hidden');
            promoSuccess.innerText = `Kode promo "${data.kode}" berhasil diterapkan. Potongan: ${data.formatted_potongan}`;
            promoSuccess.classList.remove('hidden');
        }

        function removePromo() {
            appliedCode = '';
            appliedId = null;
            currentDiscount = 0;

            // Reset display summary
            discountRow.classList.add('hidden');
            displayTotal.innerText = '{{ $productPrice }}';

            // Reset hidden inputs
            hiddenDiskonId.value = '';
            hiddenPromoCode.value = '';
            hiddenDiscountAmount.value = '0';

            // Reset input and button state
            promoCodeInput.value = '';
            promoCodeInput.disabled = false;
            applyPromoBtn.innerText = 'Terapkan';
            applyPromoBtn.classList.remove('bg-[#7A1F2B]', 'hover:bg-[#5e1721]');
            applyPromoBtn.classList.add('bg-[#2D1E1E]', 'hover:bg-black');

            promoSuccess.classList.add('hidden');
            promoError.classList.add('hidden');
        }

        function showError(msg) {
            promoSuccess.classList.add('hidden');
            promoError.innerText = msg;
            promoError.classList.remove('hidden');
        }

        // Original submit behavior
        document.getElementById('orderForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Akan diteruskan ke sistem Payment Gateway & Database. (Alur masih dalam tahap pengembangan)');
        });
    </script>
@endsection
