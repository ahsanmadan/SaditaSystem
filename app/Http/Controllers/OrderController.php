<?php

namespace App\Http\Controllers;

use App\Models\DetailPesanan;
<<<<<<< HEAD
use App\Models\KodePromo;
=======
>>>>>>> d0b6b6ba9cdf3f67230427ce2b808f72b0cc6532
use App\Models\Pelanggan;
use App\Models\Pengiriman;
use App\Models\Pesanan;
use App\Models\Produk;
<<<<<<< HEAD
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
=======
use Carbon\Carbon;
use Illuminate\Http\Request;
>>>>>>> d0b6b6ba9cdf3f67230427ce2b808f72b0cc6532
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'sender_name' => ['required', 'string', 'max:255'],
            'sender_phone' => ['required', 'string', 'max:30'],
            'receiver_name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'delivery_date' => ['required', 'date'],
            'delivery_time' => ['required', 'string'],
            'promo_code' => ['nullable', 'string', 'min:6', 'max:12'],
        ]);

        // For Midtrans, prices should be numeric. We strip non-numeric characters.
        $rawPrice = preg_replace('/[^0-9]/', '', $request->price);
        $numericPrice = $rawPrice ? (int) $rawPrice : 0;
        $jamPengiriman = $this->normalizeDeliveryTime($request->delivery_time);
        $promoResult = $this->resolvePromo($request->promo_code, $numericPrice);

<<<<<<< HEAD
        if (! $promoResult['valid']) {
            return back()
                ->withInput()
                ->withErrors(['promo_code' => $promoResult['message']]);
        }

        $promo = $promoResult['promo'];
        $discount = $promoResult['discount'];
        $grandTotal = max(0, $numericPrice - $discount);
=======
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
            'total_harga' => $numericPrice,
            'biaya_ongkir' => 0,
            'grand_total' => $numericPrice,
            'batas_waktu_bayar' => now()->addHours(24),
            'catatan_pembeli' => $request->special_instruction,
        ]);
>>>>>>> d0b6b6ba9cdf3f67230427ce2b808f72b0cc6532

        $pesanan = DB::transaction(function () use ($request, $numericPrice, $jamPengiriman, $promo, $discount, $grandTotal) {
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
                'total_harga' => $numericPrice,
                'biaya_ongkir' => 0,
                'diskon' => $discount,
                'grand_total' => $grandTotal,
                'kode_promo_id' => $promo?->id,
                'kode_promo_snapshot' => $promo?->kode,
                'batas_waktu_bayar' => now()->addHours(24),
                'catatan_pembeli' => $request->special_instruction,
            ]);

            // 3. Find Product (if not found, use first product as fallback to avoid crash)
            $produk = Produk::where('nama', $request->product_name)->first();
            $produkId = $produk?->id ?? Produk::query()->value('id') ?? 1;

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
                'jam_pengiriman' => $jamPengiriman,
                'status' => 'menunggu_jadwal',
            ]);

            if ($promo) {
                $promo->increment('dipakai');
            }

            return $pesanan;
        });

        return redirect()->route('invoice.show', ['order_id' => $pesanan->kode_pesanan])
            ->with('success', 'Pesanan berhasil dibuat. Silakan selesaikan pembayaran.');
    }

    public function show($order_id)
    {
        // Now using Pesanan model to match Filament admin
<<<<<<< HEAD
        $order = Pesanan::with(['pelanggan', 'detailItems', 'pengiriman', 'kodePromo'])
                    ->where('kode_pesanan', $order_id)
                    ->firstOrFail();
        
=======
        $order = Pesanan::with(['pelanggan', 'detailItems', 'pengiriman'])
            ->where('kode_pesanan', $order_id)
            ->firstOrFail();

>>>>>>> d0b6b6ba9cdf3f67230427ce2b808f72b0cc6532
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

    private function resolvePromo(?string $promoCode, int $subtotal): array
    {
        if (blank($promoCode)) {
            return [
                'valid' => true,
                'promo' => null,
                'discount' => 0,
                'message' => null,
            ];
        }

        $normalizedCode = strtoupper(trim($promoCode));
        $promo = KodePromo::where('kode', $normalizedCode)->first();

        if (! $promo) {
            return [
                'valid' => false,
                'promo' => null,
                'discount' => 0,
                'message' => 'Kode promo tidak ditemukan.',
            ];
        }

        if (! $promo->is_aktif) {
            return [
                'valid' => false,
                'promo' => null,
                'discount' => 0,
                'message' => 'Kode promo sedang tidak aktif.',
            ];
        }

        if ($promo->isNotStarted()) {
            return [
                'valid' => false,
                'promo' => null,
                'discount' => 0,
                'message' => 'Kode promo belum mulai berlaku.',
            ];
        }

        if ($promo->isExpired()) {
            return [
                'valid' => false,
                'promo' => null,
                'discount' => 0,
                'message' => 'Kode promo sudah melewati masa berlaku.',
            ];
        }

        if ($promo->isQuotaExceeded()) {
            return [
                'valid' => false,
                'promo' => null,
                'discount' => 0,
                'message' => 'Kuota penggunaan kode promo sudah habis.',
            ];
        }

        if ($subtotal < $promo->minimum_order) {
            return [
                'valid' => false,
                'promo' => null,
                'discount' => 0,
                'message' => 'Minimal transaksi untuk promo ini adalah Rp '.number_format($promo->minimum_order, 0, ',', '.').'.',
            ];
        }

        return [
            'valid' => true,
            'promo' => $promo,
            'discount' => $promo->calculateDiscount($subtotal),
            'message' => null,
        ];
    }
}
