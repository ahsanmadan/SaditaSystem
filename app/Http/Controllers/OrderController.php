<?php

namespace App\Http\Controllers;

use App\Models\DetailPesanan;
use App\Models\KodePromo;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use App\Models\Pengiriman;
use App\Models\Pesanan;
use App\Models\Produk;
use Carbon\Carbon;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function create(Request $request): View
    {
        return view('pages.home.order', $this->buildOrderViewData($request));
    }

    public function edit(string $order_id): View
    {
        $order = Pesanan::with(['pelanggan', 'pengiriman', 'detailItems.produk'])->where('kode_pesanan', $order_id)->firstOrFail();

        return view('pages.home.order', $this->buildOrderViewData(request(), $order));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'order_id' => ['nullable', 'string', 'exists:pesanan,kode_pesanan'],
            'sender_name' => ['required', 'string', 'max:255'],
            'sender_phone' => ['required', 'string', 'max:30'],
            'receiver_name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'delivery_date' => ['required', 'date'],
            'delivery_time' => ['required', 'string'],
        ]);

        $rawPrice = preg_replace('/[^0-9]/', '', (string) $request->price);
        $numericPrice = $rawPrice ? (int) $rawPrice : 0;
        $jamPengiriman = $this->normalizeDeliveryTime($request->delivery_time);

        $pesanan = DB::transaction(function () use ($request, $numericPrice, $jamPengiriman) {
            $existingOrder = filled($request->order_id)
                ? Pesanan::with(['detailItems', 'pengiriman', 'pembayaranTerakhir'])->where('kode_pesanan', $request->order_id)->lockForUpdate()->first()
                : null;

            $pelanggan = $existingOrder?->pelanggan;

            if ($pelanggan) {
                $pelanggan->update([
                    'nama_lengkap' => $request->sender_name,
                    'no_hp' => $request->sender_phone,
                ]);
            } else {
                $pelanggan = Pelanggan::firstOrCreate(
                    ['no_hp' => $request->sender_phone],
                    [
                        'nama_lengkap' => $request->sender_name,
                        'email' => null,
                    ]
                );
            }

            $kodePesanan = $existingOrder?->kode_pesanan ?? 'SDT-'.date('Ymd').'-'.strtoupper(Str::random(5));

            $pesanan = $existingOrder ?? new Pesanan();
            $pesanan->fill([
                'pelanggan_id' => $pelanggan->id,
                'kode_pesanan' => $kodePesanan,
                'status' => Pesanan::STATUS_MENUNGGU,
                'total_harga' => $numericPrice,
                'biaya_ongkir' => 0,
                'diskon' => 0,
                'grand_total' => $numericPrice,
                'kode_promo_id' => null,
                'kode_promo_snapshot' => null,
                'batas_waktu_bayar' => now()->addHours(24),
                'catatan_pembeli' => $request->special_instruction,
            ]);
            $pesanan->save();

            $produk = Produk::where('nama', $request->product_name)->first();
            $produkId = $produk?->id ?? Produk::query()->value('id') ?? 1;

            $detailPesanan = $pesanan->detailItems()->first() ?? new DetailPesanan(['pesanan_id' => $pesanan->id]);
            $detailPesanan->fill([
                'produk_id' => $produkId,
                'nama_produk_snapshot' => $request->product_name ?? 'Produk Sadita',
                'harga_satuan_snapshot' => $numericPrice,
                'kuantitas' => 1,
                'subtotal' => $numericPrice,
                'teks_ucapan' => $request->greeting_msg,
                'referensi_desain' => null,
            ]);
            $detailPesanan->save();

            $pengiriman = $pesanan->pengiriman ?? new Pengiriman(['pesanan_id' => $pesanan->id]);
            $pengiriman->fill([
                'nama_penerima' => $request->receiver_name ?? $request->sender_name,
                'no_hp_penerima' => $request->sender_phone,
                'alamat_lengkap' => $request->address ?? 'Ambil di Toko',
                'patokan_lokasi' => $request->untuk,
                'tanggal_pengiriman' => $request->delivery_date ?? now()->toDateString(),
                'jam_pengiriman' => $jamPengiriman,
                'status' => 'menunggu_jadwal',
            ]);
            $pengiriman->save();

            if ($existingOrder?->pembayaranTerakhir?->isGatewayDoku()) {
                $existingOrder->pembayaranTerakhir->update([
                    'jumlah_dibayar' => $numericPrice,
                    'checkout_url' => null,
                    'gateway_reference' => null,
                    'gateway_request_id' => null,
                    'gateway_status' => null,
                    'gateway_payload' => null,
                    'gateway_response' => null,
                    'expires_at' => null,
                    'status' => Pembayaran::STATUS_MENUNGGU,
                ]);
            }

            return $pesanan;
        });

        if (blank($request->order_id)) {
            ActivityLogger::log('pesanan_baru_masuk', $pesanan, [
                'kode_pesanan' => $pesanan->kode_pesanan,
                'label' => 'Pesanan "'.$pesanan->kode_pesanan.'"',
                'nama' => $pesanan->pelanggan?->nama_lengkap ?? $request->sender_name,
                'grand_total' => $pesanan->grand_total,
            ]);
        }

        return redirect()
            ->route('invoice.show', ['order_id' => $pesanan->kode_pesanan])
            ->with('success', 'Data pesanan tersimpan. Lanjut pilih pembayaran ya.');
    }

    public function show(string $order_id): View
    {
        $order = Pesanan::with([
            'pelanggan',
            'detailItems.produk',
            'pengiriman',
            'kodePromo',
            'pembayaranTerakhir',
        ])->where('kode_pesanan', $order_id)->firstOrFail();

        $paymentMethods = collect(config('doku.payment_methods', []));

        return view('pages.home.invoice', [
            'order' => $order,
            'paymentMethods' => $paymentMethods,
        ]);
    }

    public function applyPromo(Request $request, string $order_id): RedirectResponse
    {
        $request->validate([
            'promo_code' => ['nullable', 'string', 'min:4', 'max:20'],
        ]);

        $pesanan = Pesanan::with(['pembayaranTerakhir'])->where('kode_pesanan', $order_id)->firstOrFail();
        $subtotal = (int) $pesanan->total_harga;

        if (blank($request->promo_code)) {
            $pesanan->update([
                'kode_promo_id' => null,
                'kode_promo_snapshot' => null,
                'diskon' => 0,
                'grand_total' => $subtotal,
            ]);

            $this->resetPendingDokuPayment($pesanan);

            return back()->with('success', 'Kode promo dibersihkan.');
        }

        $promoResult = $this->resolvePromo($request->promo_code, $subtotal);

        if (! $promoResult['valid']) {
            return back()->withErrors(['promo_code' => $promoResult['message']])->withInput();
        }

        $promo = $promoResult['promo'];
        $discount = $promoResult['discount'];

        $pesanan->update([
            'kode_promo_id' => $promo->id,
            'kode_promo_snapshot' => $promo->kode,
            'diskon' => $discount,
            'grand_total' => max(0, $subtotal - $discount),
        ]);

        $this->resetPendingDokuPayment($pesanan);

        return back()->with('success', 'Kode promo berhasil dipakai.');
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

    private function buildOrderViewData(Request $request, ?Pesanan $order = null): array
    {
        $detailItem = $order?->detailItems?->first();
        $produk = $detailItem?->produk;
        $pengiriman = $order?->pengiriman;
        $pelanggan = $order?->pelanggan;

        $productName = $request->query('product', $detailItem?->nama_produk_snapshot ?? 'Sadita Exclusive Product');
        $priceNumeric = $detailItem?->harga_satuan_snapshot ?? (int) preg_replace('/[^0-9]/', '', (string) $request->query('price', '0'));

        return [
            'existingOrder' => $order,
            'productName' => $productName,
            'productPrice' => 'Rp '.number_format($priceNumeric, 0, ',', '.'),
            'productImg' => $request->query('img', $produk?->fotoUtamaUrl() ?? '/images/dekorasi-lamaran.jpg'),
            'productType' => $request->query('jenis', $produk?->is_sewa ? 'Sewa' : 'Layanan Sadita'),
            'formValues' => [
                'sender_name' => old('sender_name', $pelanggan?->nama_lengkap),
                'sender_phone' => old('sender_phone', $this->stripPhonePrefix($pelanggan?->no_hp)),
                'receiver_name' => old('receiver_name', $pengiriman?->nama_penerima),
                'untuk' => old('untuk', $pengiriman?->patokan_lokasi),
                'address' => old('address', $pengiriman?->alamat_lengkap),
                'delivery_date' => old('delivery_date', optional($pengiriman?->tanggal_pengiriman)->format('Y-m-d')),
                'delivery_time' => old('delivery_time', $this->formatDeliveryTimeForForm($pengiriman?->jam_pengiriman)),
                'greeting_msg' => old('greeting_msg', $detailItem?->teks_ucapan),
                'special_instruction' => old('special_instruction', $order?->catatan_pembeli),
            ],
        ];
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

    private function resetPendingDokuPayment(Pesanan $pesanan): void
    {
        $payment = $pesanan->pembayaranTerakhir;

        if (! $payment || ! $payment->isGatewayDoku() || $payment->status === Pembayaran::STATUS_LUNAS) {
            return;
        }

        $payment->update([
            'jumlah_dibayar' => $pesanan->grand_total,
            'checkout_url' => null,
            'gateway_reference' => null,
            'gateway_request_id' => null,
            'gateway_status' => null,
            'gateway_payload' => null,
            'gateway_response' => null,
            'expires_at' => null,
        ]);
    }

    private function stripPhonePrefix(?string $phone): ?string
    {
        if (! $phone) {
            return null;
        }

        $normalized = preg_replace('/\D+/', '', $phone) ?? '';

        return str_starts_with($normalized, '62') ? substr($normalized, 2) : ltrim($normalized, '0');
    }

    private function formatDeliveryTimeForForm(mixed $deliveryTime): ?string
    {
        if ($deliveryTime instanceof Carbon) {
            return $deliveryTime->format('H:i:s');
        }

        if (is_string($deliveryTime) && $deliveryTime !== '') {
            return $deliveryTime;
        }

        return null;
    }
}
