@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-[#1a1a1a] pt-[100px] pb-20 flex flex-col items-center justify-center relative font-mono">

        <!-- Top Right Button -->
        <div class="absolute top-24 right-4 md:right-10 print:hidden">
            <button onclick="window.print()"
                class="bg-black border border-gray-700 text-white text-[10px] px-4 py-2 hover:bg-gray-800 transition-colors uppercase tracking-widest flex items-center gap-2">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                Unduh PDF
            </button>
        </div>

        <!-- Receipt Container -->
        <div class="bg-white w-[92%] sm:w-full max-w-[380px] p-6 sm:p-8 shadow-2xl relative mt-4">
            <!-- Jagged top border effect (optional, css pseudo element) -->

            <!-- Header -->
            <div class="text-center mb-8">
                <div class="text-[10px] tracking-widest uppercase mb-2 text-gray-500">Sadita Decoration</div>
                <h1 class="text-3xl font-bold uppercase tracking-widest text-black mb-2"
                    style="font-family: 'Playfair Display', serif;">SADITA</h1>
                <div class="text-[9px] tracking-[0.2em] uppercase text-gray-500">Premium Essential Goods</div>
            </div>

            <!-- Metadata -->
            <div class="mb-6 space-y-1">
                <div class="flex justify-between text-xs">
                    <span class="text-gray-500">PESANAN:</span>
                    <span class="font-bold">{{ $order->kode_pesanan }}</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-gray-500">TANGGAL:</span>
                    <span class="font-bold">{{ $order->created_at->format('d M Y H:i') }}</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-gray-500">PELANGGAN:</span>
                    <span class="font-bold uppercase">{{ $order->pelanggan->nama_lengkap ?? 'Umum' }}</span>
                </div>
            </div>

            <div class="border-t border-dashed border-gray-300 my-6"></div>

            <!-- Items -->
            <div class="mb-6 space-y-4">
                @foreach($order->detailItems as $detail)
                <div class="flex justify-between items-start text-xs">
                    <div>
                        <div class="font-bold uppercase pr-4">{{ $detail->nama_produk_snapshot }}</div>
                        <div class="text-gray-500">{{ $detail->kuantitas }}x</div>
                    </div>
                    <div class="font-bold">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</div>
                </div>
                @endforeach
            </div>

            <div class="border-t border-dashed border-gray-300 my-6"></div>

            <!-- Summary -->
            <div class="mb-6 space-y-2">
                <div class="flex justify-between text-xs">
                    <span class="text-gray-500">SUBTOTAL</span>
                    <span class="font-bold">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-gray-500">PENGIRIMAN</span>
                    <span class="font-bold">Rp {{ number_format($order->biaya_ongkir, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="border-t border-dashed border-gray-300 my-6"></div>

            <!-- Total -->
            <div class="flex justify-between items-center mb-8">
                <span class="text-lg font-bold">TOTAL</span>
                <span class="text-lg font-bold">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
            </div>

            <div class="border-t border-dashed border-gray-300 my-6"></div>

            <!-- Verification/QR Placeholder -->
            <div class="flex flex-col items-center justify-center mb-8">
                <div class="w-16 h-16 bg-gray-200 mb-3 grid grid-cols-3 grid-rows-3 border border-black p-1">
                    <div class="bg-black"></div>
                    <div class="bg-white"></div>
                    <div class="bg-black"></div>
                    <div class="bg-white"></div>
                    <div class="bg-black"></div>
                    <div class="bg-white"></div>
                    <div class="bg-black"></div>
                    <div class="bg-white"></div>
                    <div class="bg-black"></div>
                </div>
                <div class="text-[8px] tracking-widest text-gray-500 mb-1">KODE VERIFIKASI</div>
                <div class="bg-gray-100 px-3 py-1 text-xs font-bold font-mono tracking-widest border border-gray-200">
                    {{ substr(md5($order->kode_pesanan), 0, 10) }}
                </div>
            </div>

            <!-- Footer Message -->
            <div class="text-center text-[7px] leading-relaxed text-gray-400 tracking-widest uppercase mb-6">
                Terima kasih atas pesanan Anda.<br>
                Barang yang sudah dibeli tidak dapat dikembalikan.<br>
                Kunjungi sadita.com/support
            </div>

            <!-- Pay Button (if UNPAID) -->
            <div
                class="w-full bg-green-800 text-white py-3 mt-4 text-xs font-bold tracking-[0.2em] uppercase text-center print:hidden">
                LUNAS
            </div>

        </div>
    </div>

    <style>
        /* Styling for jagged edges to make it look like a receipt */
        .bg-white.w-full::before {
            content: "";
            position: absolute;
            top: -4px;
            left: 0;
            right: 0;
            height: 4px;
            background-size: 8px 100%;
            background-image: linear-gradient(135deg, white 25%, transparent 25%), linear-gradient(225deg, white 25%, transparent 25%);
            background-position: 0 0, 4px 0;
        }

        .bg-white.w-full::after {
            content: "";
            position: absolute;
            bottom: -4px;
            left: 0;
            right: 0;
            height: 4px;
            background-size: 8px 100%;
            background-image: linear-gradient(135deg, transparent 75%, white 75%), linear-gradient(225deg, transparent 75%, white 75%);
            background-position: 0 0, 4px 0;
        }

        @media print {
            body * {
                visibility: hidden;
            }

            .bg-white.w-full,
            .bg-white.w-full * {
                visibility: visible;
            }

            .bg-white.w-full {
                position: absolute;
                left: 0;
                top: 0;
                margin: 0;
                padding: 0;
                box-shadow: none;
            }

            .print\:hidden {
                display: none !important;
            }
        }
    </style>
@endsection
