<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $order->kode_pesanan }}</title>
    <style>
        @page { margin: 28px 30px; }
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #2d1e1e;
            font-size: 12px;
            line-height: 1.55;
        }
        .page { width: 100%; }
        .table { width: 100%; border-collapse: collapse; }
        .header-table td { vertical-align: top; }
        .muted {
            color: #7e726c;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .18em;
            font-weight: 700;
        }
        .title {
            font-size: 28px;
            font-weight: 700;
            margin: 8px 0 4px;
        }
        .subtle { color: #6d635f; }
        .status-box {
            border: 1px solid #e7ddd2;
            border-radius: 12px;
            padding: 12px 14px;
            background: #fbf8f4;
        }
        .status-pill {
            display: inline-block;
            margin-top: 6px;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: #166534;
            background: #edf7ef;
        }
        .status-pill.pending {
            color: #92400e;
            background: #fdf3e5;
        }
        .divider {
            border-top: 1px solid #e7ddd2;
            margin: 22px 0;
        }
        .info-card {
            width: 48.5%;
            border: 1px solid #e7ddd2;
            border-radius: 14px;
            padding: 14px;
            background: #faf6f1;
        }
        .info-name {
            font-size: 15px;
            font-weight: 700;
            margin: 8px 0 6px;
        }
        .items-table th,
        .items-table td {
            padding: 12px 0;
            border-bottom: 1px solid #ece3d9;
            vertical-align: top;
        }
        .items-table th {
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .14em;
            color: #8f8079;
        }
        .items-table th:last-child,
        .items-table td:last-child {
            text-align: right;
        }
        .item-title {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 4px;
        }
        .item-meta { color: #6d635f; font-size: 11px; }
        .price { font-size: 14px; font-weight: 700; color: #7a1f2b; }
        .summary-box {
            border: 1px solid #e7ddd2;
            border-radius: 14px;
            padding: 16px;
            background: #fbf8f4;
        }
        .summary-row td {
            padding: 5px 0;
            font-size: 12px;
        }
        .summary-row .value {
            text-align: right;
            font-weight: 700;
        }
        .grand-row td {
            padding-top: 12px;
            border-top: 1px solid #e7ddd2;
            font-size: 14px;
            font-weight: 700;
        }
        .grand-row .value {
            font-size: 22px;
            color: #7a1f2b;
        }
        .footer {
            margin-top: 24px;
            color: #736964;
            font-size: 11px;
        }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    @php
        $payment = $order->pembayaranTerakhir;
        $paymentLabel = $payment?->resolvedMetodeLabel() ?? 'Belum dipilih';
        $paymentStatus = $payment?->status === 'lunas' ? 'Lunas' : 'Menunggu';
    @endphp

    <div class="page">
        <table class="table header-table">
            <tr>
                <td style="width: 62%; padding-right: 18px;">
                    <div class="muted">Sadita Decoration</div>
                    <div class="title">Invoice Pesanan</div>
                    <div class="subtle">Kode pesanan: {{ $order->kode_pesanan }}</div>
                </td>
                <td style="width: 38%;">
                    <div class="status-box">
                        <div class="muted" style="letter-spacing:.1em;">Tanggal dibuat</div>
                        <div style="font-weight:700; margin:6px 0 10px;">{{ optional($order->created_at)->translatedFormat('d M Y, H:i') ?? '-' }}</div>
                        <div class="muted" style="letter-spacing:.1em;">Status pembayaran</div>
                        <div class="status-pill {{ $payment?->status === 'lunas' ? '' : 'pending' }}">{{ $paymentStatus }}</div>
                    </div>
                </td>
            </tr>
        </table>

        <div class="divider"></div>

        <table class="table" style="margin-bottom: 18px;">
            <tr>
                <td class="info-card" style="padding-right:18px;">
                    <div class="muted" style="letter-spacing:.12em;">Pemesan</div>
                    <div class="info-name">{{ $order->pelanggan?->nama_lengkap ?? '-' }}</div>
                    <div class="subtle">{{ $order->pelanggan?->no_hp ?? '-' }}</div>
                    <div class="subtle">{{ $order->pelanggan?->email ?? '-' }}</div>
                </td>
                <td style="width: 3%;"></td>
                <td class="info-card">
                    <div class="muted" style="letter-spacing:.12em;">Pengiriman</div>
                    <div class="info-name">{{ $order->pengiriman?->nama_penerima ?? '-' }}</div>
                    <div class="subtle">{{ optional($order->pengiriman?->tanggal_pengiriman)->translatedFormat('d M Y') ?? '-' }}</div>
                    <div class="subtle">{{ $order->pengiriman?->alamat_lengkap ?? '-' }}</div>
                    @if ($order->pengiriman?->patokan_lokasi)
                        <div class="subtle">{{ $order->pengiriman->patokan_lokasi }}</div>
                    @endif
                </td>
            </tr>
        </table>

        <table class="table items-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->detailItems as $item)
                    @php
                        $itemType = $item->produk?->is_sewa
                            ? 'Sewa'
                            : ($order->tipe_layanan === 'dekorasi'
                                ? 'Jasa'
                                : ($order->tipe_layanan === 'hantaran' ? 'Hantaran' : 'Layanan'));
                    @endphp
                    <tr>
                        <td>
                            <div class="item-title">{{ $item->nama_produk_snapshot }}</div>
                            <div class="item-meta">{{ $itemType }} · {{ $item->kuantitas }} item</div>
                            @if ($item->teks_ucapan)
                                <div class="item-meta" style="margin-top: 4px;">Ucapan: {{ $item->teks_ucapan }}</div>
                            @endif
                        </td>
                        <td class="price">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="table" style="margin-top: 20px;">
            <tr>
                <td style="width: 55%; vertical-align: top; padding-right: 18px;">
                    <div class="summary-box">
                        <div class="muted" style="letter-spacing:.12em;">Metode pembayaran</div>
                        <div style="font-size: 15px; font-weight:700; margin:8px 0 6px;">{{ $paymentLabel }}</div>
                        <div class="subtle">
                            @if ($payment?->gateway_reference)
                                Referensi: {{ $payment->gateway_reference }}<br>
                            @endif
                            Invoice ini digunakan sebagai ringkasan tagihan dan bukti pesanan pelanggan Sadita.
                        </div>
                    </div>
                </td>
                <td style="width: 45%; vertical-align: top;">
                    <div class="summary-box">
                        <div class="muted" style="letter-spacing:.12em;">Ringkasan pembayaran</div>
                        <table class="table" style="margin-top: 10px;">
                            <tr class="summary-row">
                                <td>Subtotal</td>
                                <td class="value">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                            </tr>
                            @if (($order->estimasi_belanja ?? 0) > 0)
                                <tr class="summary-row">
                                    <td>Estimasi belanja</td>
                                    <td class="value">Rp {{ number_format($order->estimasi_belanja, 0, ',', '.') }}</td>
                                </tr>
                            @endif
                            @if (($order->diskon ?? 0) > 0)
                                <tr class="summary-row">
                                    <td>Diskon {{ $order->kode_promo_snapshot ? '('.$order->kode_promo_snapshot.')' : '' }}</td>
                                    <td class="value">-Rp {{ number_format($order->diskon, 0, ',', '.') }}</td>
                                </tr>
                            @endif
                            <tr class="summary-row">
                                <td>Ongkir</td>
                                <td class="value">Rp {{ number_format($order->biaya_ongkir ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            <tr class="grand-row">
                                <td>Grand total</td>
                                <td class="value text-right">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>

        <div class="footer">
            Simpan invoice ini untuk referensi pelacakan pesanan dan konfirmasi layanan. Jika ada revisi, gunakan kode pesanan saat menghubungi admin Sadita.
        </div>
    </div>
</body>
</html>
