@php
    $customer = $order->pelanggan;
    $delivery = $order->pengiriman;
    $items = $order->detailItems ?? collect();
    $productNames = $items->pluck('nama_produk_snapshot')->filter()->implode(', ');
    $trackingUrl = route('tracking.page', ['code' => $order->kode_pesanan]);
    $invoiceUrl = route('invoice.show', ['order_id' => $order->kode_pesanan]);
    $payment = $order->pembayaranTerakhir;
    $lineItems = $items->map(function ($item) {
        return [
            'name' => $item->nama_produk_snapshot ?: 'Produk Sadita',
            'qty' => (int) ($item->kuantitas ?? 1),
            'unit_price' => (int) ($item->harga_satuan_snapshot ?? 0),
            'subtotal' => (int) ($item->subtotal ?? 0),
        ];
    });
    $shippingCost = (int) ($order->biaya_ongkir ?? 0);
    $discount = (int) ($order->diskon ?? 0);
    $subtotal = (int) ($order->total_harga ?? 0);
    $grandTotal = (int) ($order->grand_total ?? 0);
    $paymentDeadline = $order->batas_waktu_bayar?->translatedFormat('d M Y, H:i');
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tracking pesanan Sadita</title>
    <style>
        body { font-family: Arial, sans-serif; color: #2f2526; line-height: 1.6; background: #f8f4ef; }
        .container { max-width: 620px; margin: 0 auto; padding: 24px; background: #ffffff; border-radius: 18px; border: 1px solid #eadfd2; }
        .header { font-size: 22px; font-weight: 700; margin-bottom: 18px; color: #7A1F2B; }
        .badge { display: inline-block; padding: 8px 14px; background: #f8ede7; color: #7A1F2B; border-radius: 999px; font-weight: 700; margin-bottom: 18px; }
        .section { margin-bottom: 16px; }
        .label { font-weight: 700; color: #5f4b45; }
        .panel { padding: 14px 16px; background: #fbf7f2; border: 1px solid #efe4d7; border-radius: 14px; }
        .button { display: inline-block; margin-top: 14px; margin-right: 10px; padding: 12px 18px; background: #7A1F2B; color: #ffffff !important; text-decoration: none; border-radius: 12px; font-weight: 700; }
        .button.secondary { background: #ffffff; color: #7A1F2B !important; border: 1px solid #d8c8ba; }
        .table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        .table th, .table td { text-align: left; padding: 10px 0; border-bottom: 1px solid #efe4d7; vertical-align: top; }
        .table th { font-size: 12px; color: #6b5a54; }
        .amount { text-align: right; white-space: nowrap; }
        .summary-row { display: flex; justify-content: space-between; gap: 12px; margin-top: 10px; }
        .summary-row.total { margin-top: 14px; padding-top: 14px; border-top: 1px solid #e7d8ca; font-size: 18px; font-weight: 700; color: #7A1F2B; }
        .code-box { display: inline-block; padding: 10px 14px; background: #fff; border: 1px solid #e4d6c8; border-radius: 12px; font-weight: 700; letter-spacing: 0.08em; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">Invoice & link tracking pesanan Anda sudah siap</div>
        <div class="badge">{{ $order->kode_pesanan }}</div>

        <div class="section">
            Halo {{ $customer?->nama_lengkap ?? 'Pelanggan Sadita' }}, terima kasih sudah melanjutkan pesanan Anda di Sadita.
            @if(!empty($includeInvoicePdf))
                Pembayaran dengan <strong>{{ $paymentMethodLabel }}</strong> sudah lunas. Invoice PDF kami lampirkan di email ini dan status pesanan bisa dipantau lewat link di bawah.
            @else
                Setelah memilih metode pembayaran <strong>{{ $paymentMethodLabel }}</strong>, Anda bisa memantau status pesanan lewat link di bawah ini.
            @endif
        </div>

        <div class="section panel">
            <div><span class="label">Kode lacak:</span> <span class="code-box">{{ $order->kode_pesanan }}</span></div>
            <div style="margin-top: 12px;"><span class="label">Produk:</span> {{ $productNames ?: 'Produk Sadita' }}</div>
            <div><span class="label">Metode pembayaran:</span> {{ $paymentMethodLabel }}</div>
            <div><span class="label">Status pesanan:</span> {{ str($order->status)->replace('_', ' ')->title() }}</div>
            <div><span class="label">Tanggal pengiriman:</span>
                {{ $delivery?->tanggal_pengiriman?->translatedFormat('d M Y') ?? optional($delivery?->tanggal_pengiriman)->format('d M Y') ?? '-' }}
                @if($delivery?->jam_pengiriman)
                    | {{ \Carbon\Carbon::parse($delivery->jam_pengiriman)->format('H:i') }}
                @endif
            </div>
            <div><span class="label">Alamat kirim:</span> {{ $delivery?->alamat_lengkap ?? '-' }}</div>
            @if($paymentDeadline)
                <div><span class="label">Batas bayar:</span> {{ $paymentDeadline }}</div>
            @endif
        </div>

        <div class="section panel">
            <div class="label">Ringkasan invoice</div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th class="amount">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lineItems as $item)
                        <tr>
                            <td>
                                <div style="font-weight:700; color:#2f2526;">{{ $item['name'] }}</div>
                                <div style="font-size:13px; color:#6b5a54;">
                                    {{ $item['qty'] }} x Rp {{ number_format($item['unit_price'], 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="amount">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="summary-row">
                <span>Subtotal</span>
                <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>
            @if($discount > 0)
                <div class="summary-row">
                    <span>Diskon</span>
                    <span>-Rp {{ number_format($discount, 0, ',', '.') }}</span>
                </div>
            @endif
            <div class="summary-row">
                <span>Ongkir</span>
                <span>Rp {{ number_format($shippingCost, 0, ',', '.') }}</span>
            </div>
            <div class="summary-row total">
                <span>Total Tagihan</span>
                <span>Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="section">
            <a href="{{ $trackingUrl }}" class="button">Lacak Pesanan</a>
            <a href="{{ $invoiceUrl }}" class="button secondary">Lihat Invoice</a>
            @if($checkoutUrl)
                <a href="{{ $checkoutUrl }}" class="button secondary">Lanjut Bayar</a>
            @endif
        </div>

        @if(!empty($reviewLinks))
            <div class="section panel">
                <div class="label">Link ulasan pelanggan</div>
                <div style="margin-top: 10px; font-size: 14px; color: #6b5a54;">
                    Setelah pesanan selesai, Anda bisa kirim ulasan sekali isi lewat tombol berikut.
                </div>

                @foreach($reviewLinks as $reviewLink)
                    <div style="margin-top: 14px; padding-top: 14px; border-top: 1px solid #efe4d7;">
                        <div style="font-weight: 700; color: #2f2526;">{{ $reviewLink['product_name'] }}</div>
                        <a href="{{ $reviewLink['url'] }}" class="button" style="margin-top: 10px;">
                            {{ $reviewLink['used'] ? 'Ulasan Sudah Diisi' : 'Isi Ulasan' }}
                        </a>
                    </div>
                @endforeach
            </div>
        @endif

        @if(!empty($includeInvoicePdf))
            <div class="section" style="font-size: 14px; color: #6b5a54;">
                File invoice PDF sudah kami lampirkan di email ini agar bisa langsung Anda simpan.
            </div>
        @endif
    </div>
</body>
</html>
