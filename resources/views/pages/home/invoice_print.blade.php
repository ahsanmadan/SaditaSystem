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
            --bg: #f5efe9;
            --paper: #ffffff;
            --paper-soft: #fcf9f5;
            --ink: #2d1e1e;
            --muted: #786b65;
            --line: #e6dbd0;
            --line-strong: #dccfc1;
            --brand: #7a1f2b;
            --brand-dark: #631924;
            --gold: #d3b16a;
            --success-bg: #edf7ef;
            --success-text: #166534;
            --pending-bg: #fdf3e5;
            --pending-text: #92400e;
            --shadow-lg: 0 28px 68px rgba(47, 27, 20, 0.10);
            --shadow-md: 0 18px 40px rgba(47, 27, 20, 0.08);
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at top, rgba(255, 255, 255, 0.72), transparent 42%),
                linear-gradient(180deg, #f8f2ec 0%, var(--bg) 52%, #efe7df 100%);
            -webkit-font-smoothing: antialiased;
        }

        .screen-shell {
            padding: 24px;
        }

        .screen-actions {
            width: min(100%, 1120px);
            margin: 0 auto 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            min-height: 48px;
            border-radius: 999px;
            padding: 12px 20px;
            border: 1px solid transparent;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition: background-color .18s ease, border-color .18s ease, box-shadow .18s ease, transform .18s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.92);
            border-color: rgba(122, 31, 43, 0.12);
            color: var(--brand);
            box-shadow: 0 14px 30px rgba(45, 30, 30, 0.05);
        }

        .btn-secondary:hover {
            border-color: rgba(122, 31, 43, 0.22);
            background: #fff;
        }

        .btn-primary {
            background: var(--brand);
            color: #fff;
            box-shadow: 0 18px 34px rgba(122, 31, 43, 0.18);
        }

        .btn-primary:hover {
            background: var(--brand-dark);
        }

        .page {
            width: min(100%, 1120px);
            margin: 0 auto;
            background: var(--paper);
            border: 1px solid rgba(122, 31, 43, 0.08);
            border-radius: 30px;
            box-shadow: var(--shadow-lg);
            overflow: hidden;
        }

        .page-inner {
            padding: 42px;
        }

        .hero {
            display: grid;
            grid-template-columns: minmax(0, 1.3fr) minmax(320px, 0.85fr);
            gap: 24px;
            align-items: start;
            padding-bottom: 26px;
            border-bottom: 1px solid var(--line);
        }

        .eyebrow,
        .section-kicker,
        .mini-label {
            margin: 0;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.28em;
            text-transform: uppercase;
        }

        .eyebrow {
            color: var(--gold);
        }

        .title {
            margin: 12px 0 0;
            font-family: 'Playfair Display', serif;
            font-size: clamp(2.2rem, 4vw, 3.35rem);
            line-height: 0.98;
            color: var(--ink);
        }

        .subtitle {
            margin-top: 12px;
            max-width: 56ch;
            font-size: 15px;
            line-height: 1.7;
            color: var(--muted);
        }

        .status-card {
            border: 1px solid var(--line);
            border-radius: 24px;
            background: linear-gradient(180deg, #fff 0%, #fbf7f2 100%);
            padding: 22px;
            box-shadow: var(--shadow-md);
        }

        .status-list {
            display: grid;
            gap: 16px;
        }

        .mini-label {
            margin-bottom: 6px;
            color: #a08d83;
        }

        .meta-value {
            font-size: 1.02rem;
            font-weight: 800;
            line-height: 1.45;
            color: var(--ink);
            word-break: break-word;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 36px;
            padding: 8px 14px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            background: var(--success-bg);
            color: var(--success-text);
        }

        .status-pill.pending {
            background: var(--pending-bg);
            color: var(--pending-text);
        }

        .content-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.18fr) minmax(320px, 0.82fr);
            gap: 24px;
            padding-top: 26px;
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
            border-radius: 24px;
            background: var(--paper-soft);
            padding: 22px;
        }

        .card.white {
            background: #fff;
        }

        .section-kicker {
            color: var(--brand);
        }

        .person-name {
            margin: 10px 0 8px;
            font-size: clamp(1.35rem, 2vw, 1.65rem);
            font-weight: 800;
            line-height: 1.08;
            color: var(--ink);
            text-wrap: balance;
        }

        .meta-text {
            margin: 0;
            font-size: 14px;
            line-height: 1.8;
            color: var(--muted);
            word-break: break-word;
        }

        .item-card {
            border: 1px solid var(--line);
            border-radius: 22px;
            background: #fff;
            padding: 18px 18px 16px;
        }

        .item-card + .item-card {
            margin-top: 12px;
        }

        .item-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 18px;
        }

        .item-name {
            margin: 0;
            font-size: 1.12rem;
            font-weight: 800;
            line-height: 1.35;
            color: var(--ink);
            text-wrap: balance;
        }

        .item-meta {
            margin-top: 6px;
            font-size: 13px;
            line-height: 1.7;
            color: var(--muted);
        }

        .item-price {
            flex-shrink: 0;
            font-size: 1.18rem;
            font-weight: 800;
            line-height: 1.2;
            color: var(--brand);
            text-align: right;
            white-space: nowrap;
        }

        .totals-card,
        .payment-card {
            border: 1px solid var(--line);
            border-radius: 24px;
            padding: 22px;
        }

        .totals-card {
            background: linear-gradient(180deg, #fff 0%, #fbf7f2 100%);
        }

        .payment-card {
            background: #fff;
        }

        .total-row {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 12px;
            font-size: 14px;
            line-height: 1.6;
            color: var(--muted);
        }

        .total-row strong {
            flex-shrink: 0;
            color: var(--ink);
            text-align: right;
            white-space: nowrap;
        }

        .total-row.discount {
            color: var(--success-text);
        }

        .grand-total {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 16px;
            align-items: end;
            margin-top: 18px;
            padding-top: 18px;
            border-top: 1px solid var(--line);
        }

        .grand-total .grand-copy {
            min-width: 0;
        }

        .grand-total .amount {
            font-size: clamp(2rem, 3.4vw, 3rem);
            font-weight: 800;
            line-height: 0.96;
            letter-spacing: -0.04em;
            color: var(--brand);
            white-space: nowrap;
            text-align: right;
        }

        .payment-method {
            margin: 10px 0 0;
            font-size: 1.55rem;
            font-weight: 800;
            line-height: 1.2;
            color: var(--ink);
            word-break: break-word;
        }

        .payment-note {
            margin-top: 12px;
            font-size: 13px;
            line-height: 1.78;
            color: var(--muted);
            word-break: break-word;
        }

        .footer {
            display: grid;
            grid-template-columns: minmax(0, 1.2fr) minmax(220px, 0.8fr);
            gap: 18px;
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid var(--line);
        }

        .footer-note,
        .footer-brand {
            font-size: 12px;
            line-height: 1.8;
            color: var(--muted);
        }

        .footer-brand {
            text-align: right;
        }

        .footer-brand strong {
            color: var(--ink);
        }

        @media (max-width: 1024px) {
            .page-inner {
                padding: 30px;
            }

            .hero,
            .content-grid {
                grid-template-columns: 1fr;
            }

            .hero {
                gap: 18px;
            }

            .status-card {
                box-shadow: none;
            }
        }

        @media (max-width: 720px) {
            .screen-shell {
                padding: 14px;
            }

            .screen-actions {
                width: 100%;
                flex-direction: column-reverse;
                align-items: stretch;
            }

            .page {
                width: 100%;
                border-radius: 24px;
            }

            .page-inner {
                padding: 22px 18px;
            }

            .info-grid,
            .footer,
            .grand-total {
                grid-template-columns: 1fr;
            }

            .item-row {
                flex-direction: column;
                gap: 10px;
            }

            .item-price,
            .footer-brand,
            .grand-total .amount {
                text-align: left;
            }

            .btn {
                width: 100%;
            }

            .status-card,
            .card,
            .totals-card,
            .payment-card {
                padding: 18px;
            }
        }

        @page {
            size: A4;
            margin: 0;
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

            .hero {
                grid-template-columns: minmax(0, 1.3fr) minmax(280px, 0.8fr) !important;
                gap: 20px !important;
            }

            .content-grid {
                grid-template-columns: minmax(0, 1.1fr) minmax(265px, 0.9fr) !important;
                gap: 20px !important;
            }

            .info-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            }

            .footer {
                grid-template-columns: minmax(0, 1.15fr) minmax(210px, 0.85fr) !important;
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
            <button type="button" onclick="window.print()" class="btn btn-primary">
                Cetak invoice
            </button>
        </div>

        <article class="page">
            <div class="page-inner">
                <header class="hero">
                    <div>
                        <p class="eyebrow">Sadita Decoration</p>
                        <h1 class="title">Invoice Pesanan</h1>
                        <p class="subtitle">Dokumen ringkas untuk detail pesanan, nominal pembayaran, dan referensi layanan pelanggan Sadita.</p>
                    </div>

                    <section class="status-card">
                        <div class="status-list">
                            <div>
                                <p class="mini-label">Kode pesanan</p>
                                <div class="meta-value">{{ $order->kode_pesanan }}</div>
                            </div>
                            <div>
                                <p class="mini-label">Tanggal dibuat</p>
                                <div class="meta-value">{{ optional($order->created_at)->translatedFormat('d M Y, H:i') ?? '-' }}</div>
                            </div>
                            <div>
                                <p class="mini-label">Status pembayaran</p>
                                <div class="status-pill {{ $payment?->status === 'lunas' ? '' : 'pending' }}">
                                    {{ $paymentStatus }}
                                </div>
                            </div>
                        </div>
                    </section>
                </header>

                <div class="content-grid">
                    <section class="stack">
                        <div class="info-grid">
                            <section class="card">
                                <p class="section-kicker">Pemesan</p>
                                <h2 class="person-name">{{ $order->pelanggan?->nama_lengkap ?? '-' }}</h2>
                                <p class="meta-text">
                                    {{ $order->pelanggan?->no_hp ?? '-' }}<br>
                                    {{ $order->pelanggan?->email ?? '-' }}
                                </p>
                            </section>

                            <section class="card">
                                <p class="section-kicker">Pengiriman</p>
                                <h2 class="person-name">{{ $order->pengiriman?->nama_penerima ?? '-' }}</h2>
                                <p class="meta-text">
                                    {{ optional($order->pengiriman?->tanggal_pengiriman)->translatedFormat('d M Y') ?? '-' }}<br>
                                    {{ $order->pengiriman?->alamat_lengkap ?? '-' }}
                                    @if ($order->pengiriman?->patokan_lokasi)
                                        <br>{{ $order->pengiriman->patokan_lokasi }}
                                    @endif
                                </p>
                            </section>
                        </div>

                        <section class="card white">
                            <p class="section-kicker">Rincian item</p>
                            <div>
                                @forelse ($order->detailItems as $item)
                                    @php
                                        $itemType = $item->produk?->is_sewa
                                            ? 'Sewa'
                                            : ($order->tipe_layanan === 'dekorasi'
                                                ? 'Jasa'
                                                : ($order->tipe_layanan === 'hantaran' ? 'Hantaran' : 'Layanan'));
                                    @endphp
                                    <article class="item-card">
                                        <div class="item-row">
                                            <div>
                                                <h3 class="item-name">{{ $item->nama_produk_snapshot }}</h3>
                                                <div class="item-meta">
                                                    {{ $itemType }} · {{ $item->kuantitas }} item
                                                    @if ($item->teks_ucapan)
                                                        <br>Ucapan: {{ $item->teks_ucapan }}
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="item-price">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                                        </div>
                                    </article>
                                @empty
                                    <div class="item-card">
                                        <div class="item-meta">Belum ada detail item pada pesanan ini.</div>
                                    </div>
                                @endforelse
                            </div>
                        </section>
                    </section>

                    <aside class="stack">
                        <section class="totals-card">
                            <p class="section-kicker">Ringkasan pembayaran</p>
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
                                    <span>Diskon {{ $order->kode_promo_snapshot ? '(' . $order->kode_promo_snapshot . ')' : '' }}</span>
                                    <strong>-Rp {{ number_format($order->diskon, 0, ',', '.') }}</strong>
                                </div>
                            @endif

                            <div class="total-row">
                                <span>Ongkir</span>
                                <strong>Rp {{ number_format($order->biaya_ongkir ?? 0, 0, ',', '.') }}</strong>
                            </div>

                            <div class="grand-total">
                                <div class="grand-copy">
                                    <p class="mini-label">Grand total</p>
                                    <div class="meta-value">Tagihan akhir pesanan</div>
                                </div>
                                <div class="amount">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</div>
                            </div>
                        </section>

                        <section class="payment-card">
                            <p class="section-kicker">Metode pembayaran</p>
                            <h3 class="payment-method">{{ $paymentLabel }}</h3>
                            <div class="payment-note">
                                @if ($payment?->gateway_reference)
                                    Referensi pembayaran: {{ $payment->gateway_reference }}<br>
                                @endif
                                Invoice ini dapat digunakan sebagai ringkasan nominal tagihan dan konfirmasi pembayaran pelanggan Sadita.
                            </div>
                        </section>
                    </aside>
                </div>

                <footer class="footer">
                    <div class="footer-note">
                        Simpan invoice ini untuk referensi pelacakan pesanan dan konfirmasi layanan. Jika ada perubahan data, gunakan kode pesanan saat menghubungi admin Sadita.
                    </div>
                    <div class="footer-brand">
                        <strong>Sadita Decoration</strong><br>
                        Padang, Sumatera Barat<br>
                        {{ config('app.url') }}
                    </div>
                </footer>
            </div>
        </article>
    </div>
</body>
</html>
