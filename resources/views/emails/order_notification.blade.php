<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
        }
        .header {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #7A1F2B;
        }
        .section {
            margin-bottom: 15px;
        }
        .label {
            font-weight: bold;
            color: #555;
        }
        .highlight {
            font-size: 18px;
            font-weight: bold;
            color: #7A1F2B;
            margin: 15px 0;
            padding: 10px;
            background: #FAF5F0;
            border-radius: 5px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            background: #7A1F2B;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            📦 PESANAN BARU - SADITA
        </div>

        <div class="highlight">
            🆔 {{ $order->order_id }}
        </div>

        <div class="section">
            <span class="label">📅 Tanggal Pesan:</span> {{ $order->created_at->format('d F Y') }}<br>
            <span class="label">🚚 Tanggal Antar:</span> {{ \Carbon\Carbon::parse($order->delivery_date)->format('d F Y') }} ({{ $order->delivery_time }})
        </div>

        <div class="section">
            <span class="label">📦 Produk:</span> {{ $order->product_name }}<br>
            <span class="label">🎨 Jenis:</span> {{ $order->jenis ?: '-' }}
        </div>

        <div class="section">
            <span class="label">👤 Pemesan:</span> {{ $order->sender_name }} ({{ $order->sender_phone }})<br>
            <span class="label">🏷️ Untuk:</span> {{ $order->untuk ?: $order->receiver_name }}
        </div>

        <div class="section">
            <span class="label">💬 Ucapan:</span><br>
            <i>{!! nl2br(e($order->greeting_msg ?: '-')) !!}</i>
        </div>
        
        <div class="section">
            <span class="label">📝 Instruksi Khusus:</span><br>
            {{ $order->special_instruction ?: '-' }}
        </div>

        <div class="section">
            <span class="label">📍 Alamat Pengiriman:</span><br>
            {{ $order->address }}
        </div>

        <div class="highlight" style="background: #e8f5e9; color: #2e7d32;">
            💰 Status: LUNAS
        </div>

        <div class="footer">
            <a href="{{ url('/admin/orders') }}" class="btn">🔗 Buka Dashboard Admin</a>
        </div>
    </div>
</body>
</html>
