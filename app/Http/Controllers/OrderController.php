<?php

namespace App\Http\Controllers;

use App\Models\DetailPesanan;
use App\Models\KodePromo;
use App\Models\Pelanggan;
use App\Models\Pengiriman;
use App\Models\Pesanan;
use App\Models\Produk;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // For Midtrans, prices should be numeric. We strip non-numeric characters.
        $rawPrice = preg_replace('/[^0-9]/', '', $request->price);
        $numericPrice = $rawPrice ? (int) $rawPrice : 0;

        $jamPengiriman = $this->normalizeDeliveryTime($request->delivery_time);

        // Promo Code Verification
        $diskon = 0;
        $kodePromoId = null;

        if ($request->filled('promo_code')) {
            $promoCodeStr = trim($request->promo_code);
            $promo = KodePromo::whereRaw('LOWER(kode) = ?', [strtolower($promoCodeStr)])->first();

            if (! $promo || ! $promo->is_aktif) {
                return back()->withInput()->withErrors(['promo_code' => 'Kode promo tidak valid atau tidak aktif.']);
            }

            if ($promo->berlaku_sampai && $promo->berlaku_sampai->isPast()) {
                return back()->withInput()->withErrors(['promo_code' => 'Kode promo sudah kedaluwarsa.']);
            }

            if ($promo->kuota !== null && $promo->dipakai >= $promo->kuota) {
                return back()->withInput()->withErrors(['promo_code' => 'Kuota kode promo sudah habis.']);
            }

            if ($numericPrice < $promo->minimum_order) {
                return back()->withInput()->withErrors(['promo_code' => 'Minimal order untuk promo ini belum terpenuhi.']);
            }

            if ($promo->tipe_diskon === 'persentase') {
                $diskon = (int) round(($promo->nilai_diskon / 100) * $numericPrice);
            } else {
                $diskon = (int) $promo->nilai_diskon;
            }

            if ($diskon > $numericPrice) {
                $diskon = $numericPrice;
            }

            $kodePromoId = $promo->id;

            // Increment usage count
            $promo->increment('dipakai');
        }

        $grandTotal = $numericPrice - $diskon;

        // 1. Create or Find Pelanggan (Sender)
        $pelanggan = Pelanggan::firstOrCreate(
            ['no_hp' => $request->sender_phone],
            [
                'nama_lengkap' => $request->sender_name,
                'email' => null,
            ]
        );

        // 2. Create Pesanan
        $kodePesanan = 'SDT-'.date('Ymd').'-'.strtoupper(Str::random(5));
        $pesanan = Pesanan::create([
            'pelanggan_id' => $pelanggan->id,
            'kode_pesanan' => $kodePesanan,
            'status' => 'menunggu_pembayaran',
            'kode_promo_id' => $kodePromoId,
            'total_harga' => $numericPrice,
            'biaya_ongkir' => 0,
            'diskon' => $diskon,
            'grand_total' => $grandTotal,
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
        $timeMap = [
            'Pagi (08:00 - 12:00)' => '08:00:00',
            'Siang (12:00 - 16:00)' => '12:00:00',
            'Sore (16:00 - 20:00)' => '16:00:00',
        ];
        $jamPengiriman = $timeMap[$request->delivery_time] ?? '09:00:00';

        Pengiriman::create([
            'pesanan_id' => $pesanan->id,
            'nama_penerima' => $request->receiver_name ?? $request->sender_name,
            'no_hp_penerima' => $request->sender_phone,
            'alamat_lengkap' => $request->address ?? 'Ambil di Toko',
            'patokan_lokasi' => null,
            'tanggal_pengiriman' => $request->delivery_date ?? now()->toDateString(),
            'jam_pengiriman' => $jamPengiriman,
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
                'dibatalkan' => 'DIBATALKAN',
            ];
            $mappedStatus = $statusMap[$pesanan->status] ?? strtoupper($pesanan->status);
            $pengiriman = $pesanan->pengiriman;

            return response()->json([
                'found' => true,
                'order_id' => $pesanan->kode_pesanan,
                'product_name' => $productNames ?: 'Produk Sadita',
                'status' => $mappedStatus,
                'delivery_date' => $pengiriman ? Carbon::parse($pengiriman->tanggal_pengiriman)->format('d M Y') : '-',
                'delivery_time' => $pengiriman ? Carbon::parse($pengiriman->jam_pengiriman)->format('H:i') : '-',
            ]);
        }

        return response()->json(['found' => false], 404);
    }

    private function normalizeDeliveryTime(?string $deliveryTime): string
    {
        $timeMap = [
            'Pagi (08:00 - 12:00)' => '09:00:00',
            'Siang (12:00 - 16:00)' => '13:00:00',
            'Sore (16:00 - 20:00)' => '17:00:00',
        ];

        if (empty($deliveryTime)) {
            return '09:00:00';
        }

        if (isset($timeMap[$deliveryTime])) {
            return $timeMap[$deliveryTime];
        }

        return preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $deliveryTime)
            ? (strlen($deliveryTime) === 5 ? $deliveryTime.':00' : $deliveryTime)
            : '09:00:00';
    }

    public function validatePromo(Request $request)
    {
        $code = trim($request->input('code'));
        $subtotal = (int) $request->input('subtotal');

        if (empty($code)) {
            return response()->json([
                'success' => false,
                'message' => 'Kode promo tidak boleh kosong.',
            ], 400);
        }

        $promo = KodePromo::whereRaw('LOWER(kode) = ?', [strtolower($code)])->first();

        if (! $promo) {
            return response()->json([
                'success' => false,
                'message' => 'Kode promo tidak ditemukan.',
            ], 404);
        }

        if (! $promo->is_aktif) {
            return response()->json([
                'success' => false,
                'message' => 'Kode promo tidak aktif.',
            ], 422);
        }

        if ($promo->berlaku_sampai && $promo->berlaku_sampai->isPast()) {
            return response()->json([
                'success' => false,
                'message' => 'Kode promo sudah kedaluwarsa.',
            ], 422);
        }

        if ($promo->kuota !== null && $promo->dipakai >= $promo->kuota) {
            return response()->json([
                'success' => false,
                'message' => 'Kuota kode promo sudah habis.',
            ], 422);
        }

        if ($subtotal < $promo->minimum_order) {
            return response()->json([
                'success' => false,
                'message' => 'Minimal order untuk menggunakan promo ini adalah Rp '.number_format($promo->minimum_order, 0, ',', '.'),
            ], 422);
        }

        // Calculate discount
        $discount = 0;
        if ($promo->tipe_diskon === 'persentase') {
            $discount = (int) round(($promo->nilai_diskon / 100) * $subtotal);
        } else {
            $discount = (int) $promo->nilai_diskon;
        }

        // Cap discount at subtotal
        if ($discount > $subtotal) {
            $discount = $subtotal;
        }

        $newTotal = $subtotal - $discount;

        return response()->json([
            'success' => true,
            'message' => 'Kode promo berhasil diterapkan!',
            'code' => $promo->kode,
            'discount' => $discount,
            'formatted_discount' => 'Rp '.number_format($discount, 0, ',', '.'),
            'new_total' => $newTotal,
            'formatted_new_total' => 'Rp '.number_format($newTotal, 0, ',', '.'),
        ]);
    }
}
