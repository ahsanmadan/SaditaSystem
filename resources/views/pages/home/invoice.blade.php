@extends('layouts.app')

@section('content')
    @php
        $latestPayment = $order->pembayaranTerakhir;
        $paymentPending = $order->status === 'menunggu_pembayaran';
        $paymentPaid = ! $paymentPending;
        $selectedMethod = old('payment_method', 'ALL');
        $detailItem = $order->detailItems->first();
        $product = $detailItem?->produk;
        $productImage = $product?->fotoUtamaUrl() ?? asset('images/logo-sadita.png');
        $productType = $product?->is_sewa ? 'Sewa' : 'Layanan Sadita';
        $customerEmail = $order->pelanggan?->email;
    @endphp

    <div class="min-h-screen bg-[#F7F3EE] pb-16 pt-[108px]">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <div class="text-xs font-semibold uppercase tracking-[0.24em] text-[#7A1F2B]/60">Checkout Sadita</div>
                    <h1 class="mt-2 text-4xl font-bold text-[#2D1E1E]" style="font-family: 'Playfair Display', serif;">
                        {{ $paymentPending ? 'Pilih pembayaran' : 'Invoice pesanan' }}
                    </h1>
                </div>

                @if ($paymentPaid)
                    <a href="{{ route('home') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-2xl border border-[#7A1F2B]/20 bg-white px-4 py-3 text-sm font-semibold text-[#7A1F2B] shadow-sm transition hover:border-[#7A1F2B]/40 hover:bg-[#FFF9F5]">
                        Kembali ke beranda
                    </a>
                @endif
            </div>

            @if (session('success'))
                <div class="mb-5 rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('info'))
                <div class="mb-5 rounded-2xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-700">
                    {{ session('info') }}
                </div>
            @endif

            @if ($paymentPaid)
                <div class="mb-5 rounded-[26px] border border-green-200 bg-white px-5 py-4 shadow-sm">
                    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                        <div>
                            <div class="text-sm font-bold text-green-700">Pembayaran berhasil dikonfirmasi.</div>
                            <div class="mt-1 text-sm text-[#6F6560]">
                                @if ($customerEmail)
                                    Invoice sudah dikirim ke email <span class="font-semibold text-[#2D1E1E]">{{ $customerEmail }}</span>.
                                @else
                                    Invoice siap diunduh dari halaman ini.
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('invoice.download', ['order_id' => $order->kode_pesanan]) }}"
                            class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#7A1F2B] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#5e1721]">
                            Download invoice
                        </a>
                    </div>
                </div>
            @endif

            <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_420px]">
                <div class="space-y-5">
                    <section class="rounded-[28px] border border-[#7A1F2B]/10 bg-white p-6 shadow-sm">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                            <div>
                                <div class="text-xs font-semibold uppercase tracking-[0.2em] text-[#7A1F2B]/60">Alamat pengiriman</div>
                                <div class="mt-3 text-xl font-bold text-[#2D1E1E]">{{ $order->pengiriman?->nama_penerima }}</div>
                                <div class="mt-2 text-sm leading-6 text-[#6F6560]">
                                    {{ $order->pengiriman?->alamat_lengkap }}
                                </div>
                                @if ($order->pengiriman?->patokan_lokasi)
                                    <div class="mt-2 text-sm text-[#7A1F2B]/80">Ditujukan kepada: {{ $order->pengiriman->patokan_lokasi }}</div>
                                @endif
                                @if ($order->pengembalian?->tanggal_pengambilan)
                                    <div class="mt-3 rounded-2xl bg-[#FAF5F0] px-4 py-3 text-sm text-[#6F6560]">
                                        <div class="font-semibold text-[#2D1E1E]">Jadwal pengambilan kembali</div>
                                        <div class="mt-1">
                                            {{ optional($order->pengembalian->tanggal_pengambilan)->format('d M Y') }}
                                            @if ($order->pengembalian?->jam_pengambilan)
                                                | {{ \Carbon\Carbon::parse($order->pengembalian->jam_pengambilan)->format('H:i') }}
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="rounded-2xl bg-[#FAF5F0] px-4 py-3 text-sm text-[#6F6560]">
                                <div>Tanggal kirim</div>
                                <div class="mt-1 font-semibold text-[#2D1E1E]">
                                    {{ optional($order->pengiriman?->tanggal_pengiriman)->format('d M Y') ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="rounded-[28px] border border-[#7A1F2B]/10 bg-white p-6 shadow-sm">
                        <div class="flex gap-4">
                            <div class="h-28 w-28 shrink-0 overflow-hidden rounded-2xl bg-[#F7F1EB]">
                                <img src="{{ $productImage }}" alt="{{ $detailItem?->nama_produk_snapshot }}" class="h-full w-full object-cover">
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="inline-flex rounded-full bg-[#FAF5F0] px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-[#7A1F2B]">
                                    {{ $productType }}
                                </div>
                                <h2 class="mt-3 text-2xl font-bold leading-tight text-[#2D1E1E]" style="font-family: 'Playfair Display', serif;">
                                    {{ $detailItem?->nama_produk_snapshot }}
                                </h2>
                                <div class="mt-2 text-sm text-[#6F6560]">
                                    {{ $detailItem?->kuantitas ?? 1 }} item
                                </div>
                                @if ($detailItem?->teks_ucapan)
                                    <div class="mt-4 rounded-2xl bg-[#FAF5F0] px-4 py-3 text-sm leading-6 text-[#6F6560]">
                                        <div class="font-semibold text-[#2D1E1E]">Pesan/Tulisan</div>
                                        <div class="mt-1">{{ $detailItem->teks_ucapan }}</div>
                                    </div>
                                @endif
                                @if (in_array($order->tipe_layanan, ['hantaran', 'dekorasi'], true) && $detailItem?->referensi_desain)
                                    @php
                                        $meta = json_decode($detailItem->referensi_desain, true);
                                    @endphp
                                    @if ($meta)
                                        <div class="mt-4 rounded-2xl bg-[#FAF5F0] px-4 py-3 text-sm leading-6 text-[#6F6560] space-y-2">
                                            @if (!empty($meta['occasion']))
                                                <div>
                                                    <span class="font-semibold text-[#2D1E1E]">Momen / Acara:</span>
                                                    <span class="text-[#2D1E1E] ml-1">{{ $meta['occasion'] }}</span>
                                                </div>
                                            @endif
                                            @if (!empty($meta['theme']))
                                                <div>
                                                    <span class="font-semibold text-[#2D1E1E]">Tema / Gaya:</span>
                                                    <span class="text-[#2D1E1E] ml-1">{{ $meta['theme'] }}</span>
                                                </div>
                                            @endif
                                            @if (!empty($meta['items']))
                                                <div>
                                                    <span class="font-semibold text-[#2D1E1E]">{{ $order->tipe_layanan === 'dekorasi' ? 'Detail Kebutuhan:' : 'Detail Hantaran:' }}</span>
                                                    <div class="mt-1 whitespace-pre-line text-xs text-[#2D1E1E] bg-white border border-[#E8DCCE]/40 p-2.5 rounded-xl">{{ $meta['items'] }}</div>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                @endif
                            </div>
                            <div class="text-right">
                                <div class="text-sm text-[#6F6560]">Subtotal</div>
                                <div class="mt-2 text-2xl font-bold text-[#7A1F2B]">
                                    Rp {{ number_format($detailItem?->subtotal ?? $order->grand_total, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <aside class="h-fit xl:sticky xl:top-[116px]">
                    <div class="rounded-[30px] border border-[#7A1F2B]/10 bg-white p-6 shadow-sm">
                        <div class="text-2xl font-bold text-[#2D1E1E]" style="font-family: 'Playfair Display', serif;">
                            {{ $paymentPending ? 'Metode pembayaran' : 'Ringkasan invoice' }}
                        </div>

                        @if ($paymentPending)
                            <form action="{{ route('doku.checkout', ['order_id' => $order->kode_pesanan]) }}" method="POST" class="mt-5 space-y-4">
                                @csrf
                                <input type="hidden" name="payment_method" value="ALL">

                                <div class="rounded-2xl bg-[#FAF5F0] p-4">
                                    <div class="flex items-center justify-between text-sm text-[#6F6560]">
                                        <span>Total harga</span>
                                        <span>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
                                    </div>
                                    @if ($order->diskon > 0)
                                        <div class="mt-2 flex items-center justify-between text-sm text-green-700">
                                            <span>Voucher {{ $order->kode_promo_snapshot }}</span>
                                            <span>-Rp {{ number_format($order->diskon, 0, ',', '.') }}</span>
                                        </div>
                                    @endif
                                    <div class="mt-2 flex items-center justify-between text-sm text-[#6F6560]">
                                        <span>Pengiriman</span>
                                        <span>Rp {{ number_format($order->biaya_ongkir, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="mt-4 border-t border-[#7A1F2B]/10 pt-4">
                                        <div class="flex items-center justify-between">
                                            <span class="text-base font-semibold text-[#2D1E1E]">Total tagihan</span>
                                            <span class="text-2xl font-bold text-[#7A1F2B]">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit"
                                    class="flex w-full items-center justify-center gap-3 rounded-2xl bg-[#E8C87A] px-6 py-4 text-sm font-bold tracking-wide text-[#4A1C24] transition-all duration-300 hover:-translate-y-0.5 hover:bg-[#dfbc64] hover:shadow-[0_14px_28px_rgba(232,200,122,0.28)]">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    Bayar sekarang
                                </button>
                            </form>

                            @if ($latestPayment?->isGatewayDoku())
                                <form action="{{ route('doku.refresh', ['order_id' => $order->kode_pesanan]) }}" method="POST" class="mt-3">
                                    @csrf
                                    <button type="submit"
                                        class="flex w-full items-center justify-center gap-2 rounded-2xl border border-[#7A1F2B]/15 bg-white px-5 py-3 text-sm font-semibold text-[#7A1F2B] transition hover:border-[#7A1F2B]/35 hover:bg-[#FFF9F5]">
                                        Cek status pembayaran
                                    </button>
                                </form>
                            @endif
                        @else
                            <div class="mt-5 space-y-4">
                                <div class="rounded-2xl bg-[#FAF5F0] p-4">
                                    <div class="flex items-center justify-between text-sm text-[#6F6560]">
                                        <span>Total harga</span>
                                        <span>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
                                    </div>
                                    @if ($order->diskon > 0)
                                        <div class="mt-2 flex items-center justify-between text-sm text-green-700">
                                            <span>Voucher {{ $order->kode_promo_snapshot }}</span>
                                            <span>-Rp {{ number_format($order->diskon, 0, ',', '.') }}</span>
                                        </div>
                                    @endif
                                    <div class="mt-2 flex items-center justify-between text-sm text-[#6F6560]">
                                        <span>Pengiriman</span>
                                        <span>Rp {{ number_format($order->biaya_ongkir, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="mt-4 border-t border-[#7A1F2B]/10 pt-4">
                                        <div class="flex items-center justify-between">
                                            <span class="text-base font-semibold text-[#2D1E1E]">Total tagihan</span>
                                            <span class="text-2xl font-bold text-[#7A1F2B]">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="rounded-2xl bg-green-700 px-6 py-4 text-center text-sm font-bold tracking-wide text-white">
                                    Status: Lunas
                                </div>

                                <a href="{{ route('invoice.download', ['order_id' => $order->kode_pesanan]) }}"
                                    class="flex w-full items-center justify-center gap-3 rounded-2xl border border-[#7A1F2B]/20 bg-[#FFF9F5] px-6 py-4 text-sm font-bold tracking-wide text-[#7A1F2B] transition-all duration-300 hover:-translate-y-0.5 hover:bg-[#FAF5F0] hover:shadow-md">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4" />
                                    </svg>
                                    Download invoice
                                </a>
                            </div>
                        @endif
                    </div>
                </aside>
            </div>
        </div>
    </div>
@endsection

