@php
    $customer = $order->pelanggan;
    $delivery = $order->pengiriman;
    $items = $order->detailItems ?? collect();
    $firstItem = $items->first();
    $productNames = $items->pluck('nama_produk_snapshot')->filter()->implode(', ');
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pesanan baru Sadita</title>
    <style>
        body { font-family: Arial, sans-serif; color: #2f2526; line-height: 1.6; background: #f8f4ef; }
        .container { max-width: 620px; margin: 0 auto; padding: 24px; background: #ffffff; border-radius: 18px; border: 1px solid #eadfd2; }
        .header { font-size: 22px; font-weight: 700; margin-bottom: 18px; color: #7A1F2B; }
        .badge { display: inline-block; padding: 8px 14px; background: #f8ede7; color: #7A1F2B; border-radius: 999px; font-weight: 700; margin-bottom: 18px; }
        .section { margin-bottom: 16px; }
        .label { font-weight: 700; color: #5f4b45; }
        .panel { padding: 14px 16px; background: #fbf7f2; border: 1px solid #efe4d7; border-radius: 14px; }
        .button { display: inline-block; margin-top: 14px; padding: 12px 18px; background: #7A1F2B; color: #ffffff !important; text-decoration: none; border-radius: 12px; font-weight: 700; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">Pesanan baru masuk</div>
        <div class="badge">{{ $order->kode_pesanan }}</div>

        <div class="section panel">
            <div><span class="label">Tanggal masuk:</span> {{ $order->created_at?->translatedFormat('d M Y, H:i') ?? '-' }}</div>
            <div><span class="label">Status:</span> {{ str($order->status)->replace('_', ' ')->title() }}</div>
            <div><span class="label">Total:</span> Rp {{ number_format((int) $order->grand_total, 0, ',', '.') }}</div>
        </div>

        <div class="section">
            <div class="label">Pelanggan</div>
            <div>{{ $customer?->nama_lengkap ?? '-' }}</div>
            <div>{{ $customer?->no_hp ?? '-' }}</div>
        </div>

        <div class="section">
            <div class="label">Produk</div>
            <div>{{ $productNames ?: 'Produk Sadita' }}</div>
        </div>

        <div class="section">
            <div class="label">Pengiriman</div>
            <div>{{ $delivery?->nama_penerima ?? '-' }}</div>
            <div>{{ $delivery?->alamat_lengkap ?? '-' }}</div>
            <div>
                {{ $delivery?->tanggal_pengiriman?->translatedFormat('d M Y') ?? optional($delivery?->tanggal_pengiriman)->format('d M Y') ?? '-' }}
                @if($delivery?->jam_pengiriman)
                    | {{ \Carbon\Carbon::parse($delivery->jam_pengiriman)->format('H:i') }}
                @endif
            </div>
        </div>

        <div class="section">
            <div class="label">Ucapan</div>
            <div>{{ $firstItem?->teks_ucapan ?: '-' }}</div>
        </div>

        <div class="section">
            <div class="label">Catatan</div>
            <div>{{ $order->catatan_pembeli ?: '-' }}</div>
        </div>

        <a href="{{ route('admin.index', ['focus' => 'pesanan', 'mode' => 'manage']) }}" class="button">Buka dashboard pesanan</a>
    </div>
</body>
</html>
