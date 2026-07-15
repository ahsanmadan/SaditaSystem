<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            line-height: 1.6;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            padding: 30px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border: 1px solid #eef2f5;
        }
        .header {
            font-size: 22px;
            font-weight: bold;
            color: #7A1F2B;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #7A1F2B;
            text-align: center;
        }
        .section {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #fdfdfd;
            border-left: 3px solid #ddd;
            border-radius: 4px;
        }
        .section-title {
            font-weight: bold;
            font-size: 15px;
            color: #7A1F2B;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        .label {
            font-weight: bold;
            color: #555;
            display: inline-block;
            width: 130px;
        }
        .value {
            color: #222;
        }
        .highlight-box {
            font-size: 16px;
            font-weight: bold;
            color: #2e7d32;
            margin: 20px 0;
            padding: 15px;
            background: #e8f5e9;
            border-radius: 6px;
            border-left: 5px solid #2e7d32;
            text-align: center;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            text-align: center;
        }
        .btn {
            display: inline-block;
            background: #7A1F2B;
            color: #ffffff !important;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(122, 31, 43, 0.2);
            transition: background 0.3s ease;
        }
        .item-list {
            margin: 10px 0 0 0;
            padding-left: 20px;
        }
        .item-detail {
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            💵 PEMBAYARAN TERKONFIRMASI - SADITA
        </div>

        <div class="highlight-box">
            Kode Pesanan: {{ $pesanan->kode_pesanan ?? '-' }}
        </div>

        <div class="section">
            <div class="section-title">Informasi Pesanan</div>
            <div>
                <span class="label">📅 Tanggal Pesan:</span>
                <span class="value">{{ $pesanan->created_at ? $pesanan->created_at->format('d F Y H:i') : '-' }}</span>
            </div>
            <div>
                <span class="label">💼 Layanan:</span>
                <span class="value">{{ ucfirst($pesanan->tipe_layanan ?? '-') }}</span>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Rincian Pembayaran</div>
            <div>
                <span class="label">💰 Jumlah Dibayar:</span>
                <span class="value" style="font-weight: bold; color: #2e7d32;">Rp {{ number_format($pembayaran->jumlah_dibayar, 0, ',', '.') }}</span>
            </div>
            <div>
                <span class="label">💳 Metode:</span>
                <span class="value">{{ $pembayaran->resolvedMetodeLabel() }}</span>
            </div>
            <div>
                <span class="label">⏰ Waktu Bayar:</span>
                <span class="value">{{ $pembayaran->waktu_dibayar ? $pembayaran->waktu_dibayar->format('d F Y H:i') : '-' }}</span>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Detail Item</div>
            <ul class="item-list">
                @if($pesanan->detailItems && $pesanan->detailItems->count() > 0)
                    @foreach ($pesanan->detailItems as $item)
                        <li class="item-detail">
                            <strong>{{ $item->nama_produk_snapshot }}</strong> (x{{ $item->kuantitas }}) 
                            - Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            @if ($item->teks_ucapan)
                                <br><span style="font-size: 13px; color: #666; font-style: italic;">Ucapan: "{{ $item->teks_ucapan }}"</span>
                            @endif
                        </li>
                    @endforeach
                @else
                    <li>Tidak ada detail item produk.</li>
                @endif
            </ul>
        </div>

        <div class="section">
            <div class="section-title">Detail Pelanggan</div>
            <div>
                <span class="label">👤 Nama:</span>
                <span class="value">{{ $pesanan->pelanggan->nama_lengkap ?? '-' }}</span>
            </div>
            <div>
                <span class="label">📞 No. HP:</span>
                <span class="value">{{ $pesanan->pelanggan->no_hp ?? '-' }}</span>
            </div>
            <div>
                <span class="label">✉️ Email:</span>
                <span class="value">{{ $pesanan->pelanggan->email ?? '-' }}</span>
            </div>
        </div>

        @if ($pesanan->pengiriman)
            <div class="section">
                <div class="section-title">Pengiriman & Antar</div>
                <div>
                    <span class="label">👤 Penerima:</span>
                    <span class="value">{{ $pesanan->pengiriman->nama_penerima ?? '-' }} ({{ $pesanan->pengiriman->no_hp_penerima ?? '-' }})</span>
                </div>
                <div>
                    <span class="label">📅 Tanggal Kirim:</span>
                    <span class="value">{{ \Carbon\Carbon::parse($pesanan->pengiriman->tanggal_pengiriman)->format('d F Y') }} ({{ $pesanan->pengiriman->jam_pengiriman }})</span>
                </div>
                <div>
                    <span class="label">📍 Alamat Lengkap:</span><br>
                    <span class="value" style="display: block; margin-top: 5px;">{{ $pesanan->pengiriman->alamat_lengkap ?? '-' }}</span>
                </div>
                @if($pesanan->pengiriman->patokan_lokasi)
                    <div style="margin-top: 5px;">
                        <span class="label">📍 Patokan:</span>
                        <span class="value">{{ $pesanan->pengiriman->patokan_lokasi }}</span>
                    </div>
                @endif
            </div>
        @endif

        <div class="footer">
            <a href="{{ route('admin.edit', ['focus' => 'pesanan', 'record' => $pesanan->id]) }}" class="btn">🔗 Buka Dashboard Admin</a>
        </div>
    </div>
</body>
</html>

