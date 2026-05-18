<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelanggan;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Pengiriman;
use App\Models\Produk;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // For Midtrans, prices should be numeric. We strip non-numeric characters.
        $rawPrice = preg_replace('/[^0-9]/', '', $request->price);
        $numericPrice = $rawPrice ? (int) $rawPrice : 0;

        // 1. Create or Find Pelanggan (Sender)
        $pelanggan = Pelanggan::firstOrCreate(
            ['no_hp' => $request->sender_phone],
            [
                'nama_lengkap' => $request->sender_name,
                'email' => null
            ]
        );

        // 2. Create Pesanan
        $kodePesanan = 'SDT-' . date('Ymd') . '-' . strtoupper(Str::random(5));
        $pesanan = Pesanan::create([
            'pelanggan_id' => $pelanggan->id,
            'kode_pesanan' => $kodePesanan,
            'status' => 'menunggu_pembayaran',
            'total_harga' => $numericPrice,
            'biaya_ongkir' => 0,
            'grand_total' => $numericPrice,
            'batas_waktu_bayar' => now()->addHours(24),
            'catatan_pembeli' => $request->special_instruction,
        ]);

        // 3. Find Product (if not found, use first product as fallback to avoid crash)
        $produk = Produk::where('nama', $request->product_name)->first();
        $produkId = $produk ? $produk->id : Produk::first()->id ?? 1;

        // 4. Create DetailPesanan
        DetailPesanan::create([
            'pesanan_id' => $pesanan->id,
            'produk_id' => $produkId,
            'nama_produk_snapshot' => $request->product_name ?? 'Produk Sadita',
            'harga_satuan_snapshot' => $numericPrice,
            'kuantitas' => 1,
            'subtotal' => $numericPrice,
            'teks_ucapan' => $request->greeting_msg,
            'referensi_desain' => null,
        ]);

        // 5. Create Pengiriman
        Pengiriman::create([
            'pesanan_id' => $pesanan->id,
            'nama_penerima' => $request->receiver_name ?? $request->sender_name,
            'no_hp_penerima' => $request->sender_phone,
            'alamat_lengkap' => $request->address ?? 'Ambil di Toko',
            'patokan_lokasi' => null,
            'tanggal_pengiriman' => $request->delivery_date ?? now()->toDateString(),
            'jam_pengiriman' => $request->delivery_time ?? '09:00:00',
            'status' => 'menunggu_jadwal',
        ]);

        return redirect()->route('invoice.show', ['order_id' => $kodePesanan])
            ->with('success', 'Pesanan berhasil dibuat. Silakan selesaikan pembayaran.');
    }

    public function show($order_id)
    {
        // Now using Pesanan model to match Filament admin
        $order = Pesanan::with(['pelanggan', 'detailItems', 'pengiriman'])
                    ->where('kode_pesanan', $order_id)
                    ->firstOrFail();
        
        $snapToken = null;

        return view('pages.home.invoice', compact('order', 'snapToken'));
    }

    public function track($order_id)
    {
        $pesanan = Pesanan::with(['detailItems', 'pengiriman'])->where('kode_pesanan', $order_id)->first();

        if ($pesanan) {
            $productNames = $pesanan->detailItems->pluck('nama_produk_snapshot')->implode(', ');
            $statusMap = [
                'menunggu_pembayaran' => 'UNPAID',
                'diproses' => 'PAID',
                'dikirim' => 'DELIVERED',
                'selesai' => 'SELESAI',
                'dibatalkan' => 'DIBATALKAN'
            ];
            $mappedStatus = $statusMap[$pesanan->status] ?? strtoupper($pesanan->status);
            $pengiriman = $pesanan->pengiriman;

            return response()->json([
                'found' => true,
                'order_id' => $pesanan->kode_pesanan,
                'product_name' => $productNames ?: 'Produk Sadita',
                'status' => $mappedStatus,
                'delivery_date' => $pengiriman ? \Carbon\Carbon::parse($pengiriman->tanggal_pengiriman)->format('d M Y') : '-',
                'delivery_time' => $pengiriman ? \Carbon\Carbon::parse($pengiriman->jam_pengiriman)->format('H:i') : '-'
            ]);
        }

        return response()->json(['found' => false], 404);
    }
}
