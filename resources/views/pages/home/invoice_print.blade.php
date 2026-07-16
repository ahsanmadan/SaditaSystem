<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $order->kode_pesanan }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            color-scheme: light;
            --bg: #f4efe9;
            --paper: #ffffff;
            --ink: #2d1e1e;
            --muted: #756a64;
            --line: #e8ddd3;
            --soft: #faf6f1;
            --brand: #7a1f2b;
            --brand-soft: #f6ece6;
            --gold: #d8b56b;
            --success-bg: #edf7ef;
            --success-text: #166534;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: radial-gradient(circle at top, #fbf7f2 0%, var(--bg) 48%, #efe8e0 100%);
            color: var(--ink);
            -webkit-font-smoothing: antialiased;
        }

        .screen-shell {
            min-height: 100vh;
            padding: 28px;
        }

        .screen-actions {
            width: min(100%, 210mm);
            margin: 0 auto 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            border-radius: 999px;
            padding: 12px 20px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            transition: transform .18s ease, box-shadow .18s ease, background-color .18s ease;
            cursor: pointer;
            border: 1px solid transparent;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, .86);
            border-color: rgba(122, 31, 43, .16);
            color: var(--brand);
            box-shadow: 0 12px 30px rgba(45, 30, 30, 0.06);
        }

        .btn-primary {
            background: var(--brand);
            color: #fff;
            box-shadow: 0 18px 34px rgba(122, 31, 43, 0.18);
        }

        .page {
            width: min(100%, 210mm);
            min-height: 297mm;
            margin: 0 auto;
            background: var(--paper);
            border: 1px solid rgba(122, 31, 43, 0.08);
            border-radius: 28px;
            box-shadow: 0 32px 80px rgba(45, 30, 30, 0.10);
            overflow: hidden;
        }

        .page-inner {
            padding: 18mm 18mm 16mm;
        }

        .hero {
            display: grid;
            grid-template-columns: minmax(0, 1.25fr) minmax(220px, 0.75fr);
            gap: 24px;
            align-items: start;
            padding-bottom: 24px;
            border-bottom: 1px solid var(--line);
        }

        .eyebrow {
            margin: 0 0 10px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .34em;
            text-transform: uppercase;
            color: var(--gold);
        }

        .title {
            margin: 0;
            font-family: 'Playfair Display', serif;
            font-size: 38px;
            line-height: 1.04;
            color: var(--ink);
        }

        .subtitle {
            margin-top: 8px;
            font-size: 14px;
            color: var(--muted);
        }

        .status-card {
            border: 1px solid var(--line);
            border-radius: 22px;
            background: linear-gradient(180deg, #fff 0%, #fbf7f2 100%);
            padding: 18px;
        }

        .status-list {
            display: grid;
            gap: 12px;
        }

        .label {
            display: block;
            margin-bottom: 4px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: #9a8a81;
        }

        .value {
            font-size: 14px;
            font-weight: 700;
            color: var(--ink);
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            background: var(--success-bg);
            color: var(--success-text);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .12em;
            text-transform: uppercase;
        }

        .status-pill.pending {
            background: #fdf4e6;
            color: #92400e;
        }

        .content-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 270px;
            gap: 22px;
            padding-top: 24px;
        }

        .stack {
            display: grid;
            gap: 18px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        .card {
            border: 1px solid var(--line);
            border-radius: 22px;
            background: var(--soft);
            padding: 18px;
        }

        .card.white {
            background: #fff;
        }

        .card-title {
            margin: 0 0 10px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .22em;
            text-transform: uppercase;
            color: var(--brand);
        }

        .person-name {
            margin: 0 0 8px;
            font-size: 18px;
            font-weight: 800;
            color: var(--ink);
        }

        .meta-text {
            margin: 0;
            font-size: 13px;
            line-height: 1.75;
            color: var(--muted);
            word-break: break-word;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
        }

        .items-table thead th {
            padding: 0 0 12px;
            border-bottom: 1px solid var(--line);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .16em;
            text-transform: uppercase;
            color: #998b84;
            text-align: left;
        }

        .items-table thead th:last-child,
        .items-table tbody td:last-child {
            text-align: right;
        }

        .items-table tbody td {
            padding: 16px 0;
            border-bottom: 1px solid var(--line);
            vertical-align: top;
        }

        .items-table tbody tr:last-child td {
            border-bottom: none;
            padding-bottom: 0;
        }

        .item-name {
            margin: 0;
            font-size: 17px;
            font-weight: 800;
            color: var(--ink);
        }

        .item-meta {
            margin-top: 6px;
            font-size: 13px;
            line-height: 1.65;
            color: var(--muted);
        }

        .item-price {
            font-size: 17px;
            font-weight: 800;
            color: var(--brand);
            white-space: nowrap;
        }

        .totals-card {
            border: 1px solid var(--line);
            border-radius: 24px;
            background: linear-gradient(180deg, #fff 0%, #fbf7f2 100%);
            padding: 20px;
        }

        .total-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 12px;
            font-size: 13px;
            color: var(--muted);
        }

        .total-row strong {
            color: var(--ink);
        }

        .total-row.discount {
            color: #166534;
        }

        .grand-total {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 12px;
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid var(--line);
        }

        .grand-total .amount {
            font-size: 34px;
            font-weight: 800;
            line-height: 1;
            color: var(--brand);
            white-space: nowrap;
        }

        .payment-card {
            border: 1px solid var(--line);
            border-radius: 24px;
            background: #fff;
            padding: 20px;
        }

        .payment-method {
            margin: 0;
            font-size: 18px;
            font-weight: 800;
            color: var(--ink);
            word-break: break-word;
        }

        .payment-note {
            margin-top: 10px;
            font-size: 13px;
            line-height: 1.75;
            color: var(--muted);
        }

        .footer {
            margin-top: 22px;
            padding-top: 18px;
            border-top: 1px solid var(--line);
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 20px;
        }

        .footer-note {
            max-width: 62%;
            font-size: 12px;
            line-height: 1.8;
            color: var(--muted);
        }

        .footer-brand {
            text-align: right;
            font-size: 12px;
            line-height: 1.8;
            color: var(--muted);
        }

        @page {
            size: A4;
            margin: 0;
        }

        @media (max-width: 960px) {
            .screen-shell {
                padding: 16px;
            }

            .screen-actions {
                width: 100%;
                flex-direction: column;
                align-items: stretch;
            }

            .page {
                width: 100%;
                min-height: auto;
                border-radius: 24px;
            }

            .page-inner {
                padding: 22px;
            }

            .hero,
            .content-grid,
            .info-grid,
            .footer {
                grid-template-columns: 1fr;
            }

            .footer-note,
            .footer-brand {
                max-width: none;
                text-align: left;
            }

            .grand-total .amount {
                font-size: 28px;
            }
        }

        @media print {
            body {
                background: #fff !important;
            }

            .screen-shell {
                padding: 0;
            }

            .screen-actions {
                display: none !important;
            }

            .page {
                width: 210mm !important;
                min-height: 297mm !important;
                margin: 0 !important;
                border: none !important;
                border-radius: 0 !important;
                box-shadow: none !important;
            }

            .page-inner {
                padding: 18mm 18mm 16mm !important;
            }

            * {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    @php
        $payment = $order->pembayaranTerakhir;
        $paymentLabel = collect(config('doku.payment_methods', []))->firstWhere('code', $payment?->metode_pembayaran ?? $payment?->metode)['label']
            ?? $payment?->metode_pembayaran
            ?? $payment?->metode
            ?? 'Belum dipilih';
        $paymentStatus = $payment?->status === 'lunas' ? 'Lunas' : 'Menunggu';
    @endphp

    <div class="screen-shell">
        <div class="screen-actions">
            <a href="{{ route('invoice.show', ['order_id' => $order->kode_pesanan]) }}" class="btn btn-secondary">
                &larr; Kembali ke invoice
            </a>
            <button onclick="window.print()" class="btn btn-primary">
                Cetak invoice
            </button>
        </div>

        <article class="page">
            <div class="page-inner">
                <header class="hero">
                    <div>
                        <p class="eyebrow">Sadita Decoration</p>
                        <h1 class="title">Invoice Pesanan</h1>
                        <div class="subtitle">Dokumen ringkasan pesanan dan pembayaran pelanggan.</div>
                    </div>

                    <div class="status-card">
                        <div class="status-list">
                            <div>
                                <span class="label">Kode pesanan</span>
                                <div class="value">{{ $order->kode_pesanan }}</div>
                            </div>
                            <div>
                                <span class="label">Tanggal dibuat</span>
                                <div class="value">{{ optional($order->created_at)->translatedFormat('d M Y, H:i') ?? '-' }}</div>
                            </div>
                            <div>
                                <span class="label">Status pembayaran</span>
                                <div class="status-pill {{ $payment?->status === 'lunas' ? '' : 'pending' }}">
                                    {{ $paymentStatus }}
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                <div class="content-grid">
                    <section class="stack">
                        <div class="info-grid">
                            <div class="card">
                                <p class="card-title">Pemesan</p>
                                <h2 class="person-name">{{ $order->pelanggan?->nama_lengkap ?? '-' }}</h2>
                                <p class="meta-text">
                                    {{ $order->pelanggan?->no_hp ?? '-' }}<br>
                                    {{ $order->pelanggan?->email ?? '-' }}
                                </p>
                            </div>

                            <div class="card">
                                <p class="card-title">Pengiriman</p>
                                <h2 class="person-name">{{ $order->pengiriman?->nama_penerima ?? '-' }}</h2>
                                <p class="meta-text">
                                    {{ optional($order->pengiriman?->tanggal_pengiriman)->translatedFormat('d M Y') ?? '-' }}<br>
                                    {{ $order->pengiriman?->alamat_lengkap ?? '-' }}<br>
                                    {{ $order->pengiriman?->patokan_lokasi ?? '' }}
                                </p>
                            </div>
                        </div>

                        <div class="card white">
                            <p class="card-title">Rincian item</p>
                            <table class="items-table">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($order->detailItems as $item)
                                        @php
                                            $itemType = $item->produk?->is_sewa
                                                ? 'Sewa'
                                                : ($order->tipe_layanan === 'dekorasi'
                                                    ? 'Jasa'
                                                    : ($order->tipe_layanan === 'hantaran' ? 'Hantaran' : 'Layanan'));
                                        @endphp
                                        <tr>
                                            <td>
                                                <p class="item-name">{{ $item->nama_produk_snapshot }}</p>
                                                <div class="item-meta">
                                                    {{ $itemType }} · {{ $item->kuantitas }} item
                                                    @if ($item->teks_ucapan)
                                                        <br>Ucapan: {{ $item->teks_ucapan }}
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="item-price">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="meta-text">Belum ada detail item pada pesanan ini.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </section>

                    <aside class="stack">
                        <div class="totals-card">
                            <p class="card-title">Ringkasan pembayaran</p>
                            <div class="total-row">
                                <span>Subtotal</span>
                                <strong>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</strong>
                            </div>

                            @if (($order->estimasi_belanja ?? 0) > 0)
                                <div class="total-row">
                                    <span>Estimasi belanja</span>
                                    <strong>Rp {{ number_format($order->estimasi_belanja, 0, ',', '.') }}</strong>
                                </div>
                            @endif

                            @if (($order->diskon ?? 0) > 0)
                                <div class="total-row discount">
                                    <span>Diskon {{ $order->kode_promo_snapshot ? '('.$order->kode_promo_snapshot.')' : '' }}</span>
                                    <strong>-Rp {{ number_format($order->diskon, 0, ',', '.') }}</strong>
                                </div>
                            @endif

                            <div class="total-row">
                                <span>Ongkir</span>
                                <strong>Rp {{ number_format($order->biaya_ongkir ?? 0, 0, ',', '.') }}</strong>
                            </div>

                            <div class="grand-total">
                                <div>
                                    <span class="label">Grand total</span>
                                    <div class="value">Tagihan akhir pesanan</div>
                                </div>
                                <div class="amount">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</div>
                            </div>
                        </div>

                        <div class="payment-card">
                            <p class="card-title">Metode pembayaran</p>
                            <h3 class="payment-method">{{ $paymentLabel }}</h3>
                            <div class="payment-note">
                                @if ($payment?->gateway_reference)
                                    Referensi pembayaran: {{ $payment->gateway_reference }}<br>
                                @endif
                                Invoice ini dapat digunakan sebagai ringkasan pesanan pelanggan dan bukti nominal tagihan yang telah diproses oleh Sadita.
                            </div>
                        </div>
                    </aside>
                </div>

                <footer class="footer">
                    <div class="footer-note">
                        Simpan invoice ini untuk referensi pelacakan pesanan dan konfirmasi layanan. Jika ada revisi data pesanan, gunakan kode pesanan saat menghubungi admin Sadita.
                    </div>
                    <div class="footer-brand">
                        <strong style="color: var(--ink);">Sadita Decoration</strong><br>
                        Padang, Sumatera Barat<br>
                        {{ config('app.url') }}
                    </div>
                </footer>
            </div>
        </article>
    </div>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => window.print(), 120);
        });
    </script>
</body>
</html>
