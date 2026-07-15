<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Invoice - {{ $order->kode_pesanan }}</title>
    
    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,500;0,700;1,500&display=swap" rel="stylesheet">
    
    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        playfair: ['Playfair Display', 'serif'],
                    }
                }
            }
        }
    </script>
    
    <style>
        /* Menggunakan font sans-serif bersih dengan style kasir modern */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #1e1e1e;
        }
        
        .receipt-card {
            width: 380px;
            background-color: #ffffff;
            color: #000000;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: #ffffff !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .receipt-card {
                box-shadow: none !important;
                border: none !important;
                width: 100% !important;
                max-width: 380px !important;
                margin: 0 auto !important;
                padding: 10px !important;
            }
        }
    </style>
</head>
<body class="min-h-screen py-8 flex flex-col items-center justify-start gap-4">

    {{-- Actions & Navigation (Hidden on Print) --}}
    <div class="no-print w-[380px] flex items-center justify-between px-2">
        <a href="{{ route('invoice.show', ['order_id' => $order->kode_pesanan]) }}" 
            class="inline-flex items-center gap-2 rounded-2xl border border-stone-700 bg-stone-800 px-4 py-2.5 text-xs font-bold text-white transition hover:bg-stone-700 shadow-md">
            &larr; Kembali
        </a>
        
        <button onclick="window.print()"
            class="inline-flex items-center gap-2 rounded-2xl bg-[#7A1F2B] px-5 py-2.5 text-xs font-bold text-white transition hover:bg-[#5e1721] shadow-md">
            Cetak Struk
        </button>
    </div>

    {{-- Minimalist Chic Thermal Receipt Wrapper --}}
    <div class="receipt-card px-6 py-10 shadow-2xl rounded-3xl border border-stone-200 text-xs">
        
        {{-- Store Header --}}
        <div class="text-center">
            <p class="text-[10px] uppercase tracking-[0.2em] font-semibold text-stone-500">Sadita Decoration</p>
            <h1 class="text-4xl font-extrabold uppercase tracking-widest mt-1 text-black font-sans">Sadita</h1>
            <p class="text-[9px] uppercase tracking-[0.15em] font-medium text-stone-400 mt-1">Premium Decoration Services</p>
        </div>

        <div class="mt-8 space-y-2 text-[10px] uppercase font-medium text-stone-700">
            <div class="flex justify-between">
                <span class="text-stone-400">Pesanan:</span>
                <span class="font-bold text-black">{{ $order->kode_pesanan }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-stone-400">Tanggal:</span>
                <span class="font-bold text-black">{{ $order->created_at ? $order->created_at->format('d M Y H:i') : '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-stone-400">Pelanggan:</span>
                <span class="font-bold text-black">{{ $order->pelanggan->nama_lengkap ?? '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-stone-400">Status:</span>
                <span class="font-bold text-black">{{ $order->status === 'menunggu_pembayaran' ? 'BELUM LUNAS' : 'LUNAS' }}</span>
            </div>
            @if ($order->pengiriman)
                <div class="flex justify-between">
                    <span class="text-stone-400">Penerima:</span>
                    <span class="font-bold text-black">{{ $order->pengiriman->nama_penerima }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-stone-400">Tanggal Kirim:</span>
                    <span class="font-bold text-black">{{ \Carbon\Carbon::parse($order->pengiriman->tanggal_pengiriman)->format('d M Y') }}</span>
                </div>
                <div class="flex justify-between items-start">
                    <span class="text-stone-400 shrink-0">Alamat:</span>
                    <span class="font-bold text-black text-right pl-4 leading-tight">{{ $order->pengiriman->alamat_lengkap }}</span>
                </div>
            @endif
        </div>

        {{-- Dotted Divider --}}
        <div class="border-b border-stone-300 border-dashed my-6"></div>

        {{-- Item List --}}
        <div class="space-y-4">
            @if($order->detailItems && $order->detailItems->count() > 0)
                @foreach ($order->detailItems as $item)
                    @php
                        $detailProduct = $item->produk;
                        $productType = $detailProduct?->is_sewa ? 'Sewa' : 'Jasa';
                    @endphp
                    <div>
                        <div class="flex justify-between font-bold text-stone-900 text-sm">
                            <span class="uppercase tracking-wide">{{ $item->nama_produk_snapshot }}</span>
                            <span>Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-[10px] text-stone-400 mt-0.5">
                            <span>Tipe: {{ $productType }} x {{ $item->kuantitas }}</span>
                            <span>Rp{{ number_format($item->harga_satuan_snapshot, 0, ',', '.') }} / item</span>
                        </div>
                        @if ($item->teks_ucapan)
                            <div class="mt-2 text-[10px] bg-stone-50 border-l-2 border-stone-300 p-2 text-stone-600 italic">
                                Ucapan: "{{ $item->teks_ucapan }}"
                            </div>
                        @endif
                    </div>
                @endforeach
            @else
                <div class="text-center text-stone-400 py-2">TIDAK ADA ITEM</div>
            @endif
        </div>

        {{-- Dotted Divider --}}
        <div class="border-b border-stone-300 border-dashed my-6"></div>

        {{-- Subtotals & Calculations --}}
        <div class="space-y-2 text-[11px] uppercase font-medium text-stone-700">
            <div class="flex justify-between">
                <span>Subtotal</span>
                <span class="text-stone-900">Rp{{ number_format($order->total_harga, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span>Diskon Promo {{ $order->kode_promo_snapshot ? '('.$order->kode_promo_snapshot.')' : '' }}</span>
                <span class="text-stone-900">
                    @if ($order->diskon > 0)
                        -Rp{{ number_format($order->diskon, 0, ',', '.') }}
                    @else
                        -
                    @endif
                </span>
            </div>
            <div class="flex justify-between">
                <span>Pengiriman</span>
                <span class="text-stone-900">Rp{{ number_format($order->biaya_ongkir, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Dotted Divider --}}
        <div class="border-b border-stone-300 border-dashed my-6"></div>

        {{-- Total --}}
        <div class="flex justify-between items-baseline py-1">
            <span class="text-base font-extrabold uppercase tracking-wider text-black">Total</span>
            <span class="text-2xl font-black text-black">Rp{{ number_format($order->grand_total, 0, ',', '.') }}</span>
        </div>

        {{-- Dotted Divider --}}
        <div class="border-b border-stone-300 border-dashed my-6"></div>

        {{-- Verification Section with Dynamic QR Code --}}
        <div class="text-center space-y-4">
            
            {{-- QR Code Image --}}
            <div class="inline-block p-2 bg-white border border-stone-200 rounded-xl shadow-sm">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=130x130&data={{ urlencode(route('order.track', ['order_id' => $order->kode_pesanan])) }}" 
                     alt="QR Code Lacak Pesanan" 
                     class="w-32 h-32 mx-auto">
            </div>

            <div class="space-y-1">
                <p class="text-[9px] uppercase tracking-[0.2em] font-bold text-stone-400">Kode Verifikasi / Lacak</p>
                <div class="inline-block px-3 py-1 bg-stone-100 text-stone-800 rounded font-mono font-bold tracking-wider text-xs">
                    {{ $order->kode_pesanan }}
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="text-center text-[9px] text-stone-400 mt-8 leading-relaxed uppercase">
            <p>Terima kasih atas pesanan Anda.</p>
            <p>Barang yang sudah disewa/dibeli tidak dapat ditukar.</p>
            <p class="mt-1">Kunjungi sadita.com/support jika ada kendala</p>
        </div>

    </div>

    {{-- Script untuk auto-trigger print dialog setelah halaman termuat --}}
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                window.print();
            }, 1000);
        });
    </script>
</body>
</html>
