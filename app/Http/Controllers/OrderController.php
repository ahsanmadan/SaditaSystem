<?php

namespace App\Http\Controllers;

use App\Mail\OrderNotification;
use App\Models\DetailPesanan;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use App\Models\Pengiriman;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\User;
use App\Services\ActivityLogger;
use App\Services\InvoicePdfService;
use App\Services\PromoCodeService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

class OrderController extends Controller
{
    public function create(Request $request): View
    {
        return view('pages.home.order', $this->buildOrderViewData($request));
    }

    public function edit(string $order_id): View
    {
        $order = Pesanan::with(['pelanggan', 'pengiriman', 'pengembalian', 'detailItems.produk'])->where('kode_pesanan', $order_id)->firstOrFail();

        return view('pages.home.order', $this->buildOrderViewData(request(), $order));
    }

    public function store(Request $request): RedirectResponse
    {
        $serviceType = $this->normalizeServiceType(
            (string) $request->input('jenis', ''),
            (string) $request->input('product_name', '')
        );

        $request->validate([
            'order_id' => ['nullable', 'string', 'exists:pesanan,kode_pesanan'],
            'sender_name' => ['required', 'string', 'max:255'],
            'sender_phone' => ['required', 'string', 'max:30'],
            'sender_email' => ['required', 'email', 'max:100'],
            'receiver_name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'delivery_date' => ['required', 'date'],
            'delivery_time' => ['required', 'string'],
            'pickup_date' => [
                Rule::requiredIf($this->isRentalOrderRequest($request, $serviceType)),
                'nullable',
                'date',
            ],
            'pickup_time' => [
                Rule::requiredIf($this->isRentalOrderRequest($request, $serviceType)),
                'nullable',
                'string',
            ],
            'mode_hantaran' => [
                Rule::requiredIf($serviceType === 'hantaran'),
                'nullable',
                Rule::in(['box_only', 'titip_belanja']),
            ],
            'hantaran_occasion' => [
                Rule::requiredIf(in_array($serviceType, ['hantaran', 'dekorasi'], true)),
                'nullable',
                'string',
                'max:255',
            ],
            'hantaran_items' => [
                Rule::requiredIf(in_array($serviceType, ['hantaran', 'dekorasi'], true)),
                'nullable',
                'string',
            ],
            'hantaran_theme' => [
                Rule::requiredIf($serviceType === 'dekorasi'),
                'nullable',
                'string',
                'max:255',
            ],
            'hantaran_budget' => ['nullable', 'string', 'max:30'],
        ]);

        $rawPrice = $this->parseCurrencyInput((string) $request->price);
        $numericPrice = $rawPrice ? (int) $rawPrice : 0;
        $estimasiBelanja = $serviceType === 'hantaran' && $request->mode_hantaran === 'titip_belanja'
            ? $this->parseCurrencyInput((string) $request->hantaran_budget)
            : 0;
        $orderTotal = $numericPrice + $estimasiBelanja;
        $jamPengiriman = $this->normalizeDeliveryTime($request->delivery_time);

        $pesanan = DB::transaction(function () use ($request, $numericPrice, $estimasiBelanja, $orderTotal, $jamPengiriman, $serviceType) {
            $existingOrder = filled($request->order_id)
                ? Pesanan::with(['detailItems', 'pengiriman', 'pembayaranTerakhir'])->where('kode_pesanan', $request->order_id)->lockForUpdate()->first()
                : null;

            $pelanggan = $this->resolvePelangganForOrder($request, $existingOrder?->pelanggan);

            $kodePesanan = $existingOrder?->kode_pesanan ?? 'SDT-'.date('Ymd').'-'.strtoupper(Str::random(5));

            $pesanan = $existingOrder ?? new Pesanan;
            $pesanan->fill([
                'pelanggan_id' => $pelanggan->id,
                'kode_pesanan' => $kodePesanan,
                'tipe_layanan' => $serviceType,
                'mode_hantaran' => $serviceType === 'hantaran' ? $request->mode_hantaran : null,
                'status' => Pesanan::STATUS_MENUNGGU,
                'total_harga' => $orderTotal,
                'subtotal_produk_jasa' => $numericPrice,
                'estimasi_belanja' => $estimasiBelanja,
                'realisasi_belanja' => $existingOrder?->realisasi_belanja ?? 0,
                'biaya_tambahan' => $existingOrder?->biaya_tambahan ?? 0,
                'biaya_ongkir' => 0,
                'diskon' => 0,
                'grand_total' => $orderTotal,
                'total_dibayar' => $existingOrder?->total_dibayar ?? 0,
                'sisa_tagihan' => max($orderTotal - (int) ($existingOrder?->total_dibayar ?? 0), 0),
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
                'referensi_desain' => in_array($serviceType, ['hantaran', 'dekorasi'], true)
                    ? json_encode([
                        'occasion' => $request->hantaran_occasion,
                        'items' => $request->hantaran_items,
                        'theme' => $request->hantaran_theme,
                    ], JSON_UNESCAPED_UNICODE)
                    : null,
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

            if ($this->isRentalOrderRequest($request, $serviceType)) {
                $pengembalian = $pesanan->pengembalian ?? $pesanan->pengembalian()->make();
                $pengembalian->fill([
                    'tanggal_pengambilan' => $request->pickup_date,
                    'jam_pengambilan' => $this->normalizeSimpleTime($request->pickup_time),
                ]);
                $pengembalian->save();
            }

            if ($existingOrder?->pembayaranTerakhir?->isGatewayDoku()) {
                $existingOrder->pembayaranTerakhir->update([
                    'jumlah_dibayar' => $orderTotal,
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

            $this->notifyOwnersByEmail($pesanan->fresh(['pelanggan', 'detailItems', 'pengiriman']));
        }

        return redirect()
            ->route('invoice.show', ['order_id' => $pesanan->kode_pesanan])
            ->with('success', 'Data pesanan tersimpan. Lanjut pilih pembayaran ya.');
    }

    private function resolvePelangganForOrder(Request $request, ?Pelanggan $existingPelanggan = null): Pelanggan
    {
        $attributes = [
            'nama_lengkap' => $request->sender_name,
            'no_hp' => $request->sender_phone,
            'email' => $request->sender_email,
        ];

        $pelangganByPhone = Pelanggan::query()
            ->where('no_hp', $request->sender_phone)
            ->lockForUpdate()
            ->first();

        if ($existingPelanggan) {
            if ($pelangganByPhone && ! $pelangganByPhone->is($existingPelanggan)) {
                $pelangganByPhone->update($attributes);

                return $pelangganByPhone;
            }

            $existingPelanggan->update($attributes);

            return $existingPelanggan;
        }

        if ($pelangganByPhone) {
            $pelangganByPhone->update($attributes);

            return $pelangganByPhone;
        }

        return Pelanggan::create($attributes);
    }

    public function show(string $order_id)
    {
        if (session('print_invoice')) {
            return redirect()
                ->route('invoice.print', ['order_id' => $order_id])
                ->with('success', session('success'));
        }

        $order = Pesanan::with([
            'pelanggan',
            'detailItems.produk',
            'pengiriman',
            'pengembalian',
            'kodePromo',
            'pembayaranTerakhir',
        ])->where('kode_pesanan', $order_id)->firstOrFail();

        $paymentMethods = collect(config('doku.payment_methods', []));

        return view('pages.home.invoice', [
            'order' => $order,
            'paymentMethods' => $paymentMethods,
        ]);
    }

    public function print(string $order_id): View
    {
        $order = Pesanan::with([
            'pelanggan',
            'detailItems.produk',
            'pengiriman',
            'pengembalian',
            'kodePromo',
            'pembayaranTerakhir',
        ])->where('kode_pesanan', $order_id)->firstOrFail();

        return view('pages.home.invoice_print', [
            'order' => $order,
        ]);
    }

    public function download(string $order_id, InvoicePdfService $invoicePdfService)
    {
        $order = Pesanan::with([
            'pelanggan',
            'detailItems.produk',
            'pengiriman',
            'pengembalian',
            'kodePromo',
            'pembayaranTerakhir',
        ])->where('kode_pesanan', $order_id)->firstOrFail();

        return response()->streamDownload(function () use ($invoicePdfService, $order) {
            echo $invoicePdfService->output($order);
        }, $invoicePdfService->filename($order), [
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function trackingPage(Request $request): View
    {
        $trackingCode = trim((string) $request->query('code', ''));
        $trackingData = null;

        if ($trackingCode !== '') {
            $pesanan = Pesanan::with(['detailItems', 'pengiriman', 'pembayaranTerakhir'])
                ->where('kode_pesanan', $trackingCode)
                ->first();

            if ($pesanan) {
                $trackingData = $this->buildTrackingPayload($pesanan);
            }
        }

        return view('pages.home.tracking', [
            'trackingCode' => $trackingCode,
            'trackingData' => $trackingData,
        ]);
    }

    public function applyPromo(Request $request, string $order_id, PromoCodeService $promoCodeService): RedirectResponse
    {
        $request->validate([
            'promo_code' => ['nullable', 'string', 'min:4', 'max:20'],
        ]);

        $pesanan = Pesanan::with(['pembayaranTerakhir'])->where('kode_pesanan', $order_id)->firstOrFail();
        $promoResult = $promoCodeService->applyToOrder($pesanan, $request->promo_code);

        if (! $promoResult['valid']) {
            return back()->withErrors(['promo_code' => $promoResult['message']])->withInput();
        }

        $this->resetPendingDokuPayment($pesanan);

        return back()->with('success', $promoResult['message']);
    }

    public function track($order_id)
    {
        $pesanan = Pesanan::with(['detailItems', 'pengiriman', 'pembayaranTerakhir'])->where('kode_pesanan', $order_id)->first();

        if ($pesanan) {
            return response()->json($this->buildTrackingPayload($pesanan));
        }

        return response()->json(['found' => false], 404);
    }

    private function buildTrackingPayload(Pesanan $pesanan): array
    {
        $productNames = $pesanan->detailItems->pluck('nama_produk_snapshot')->implode(', ');
        $pengiriman = $pesanan->pengiriman;
        $pembayaran = $pesanan->pembayaranTerakhir;
        $canContinuePayment = $pesanan->status === Pesanan::STATUS_MENUNGGU
            || in_array($pembayaran?->status, [null, Pembayaran::STATUS_MENUNGGU, Pembayaran::STATUS_DITOLAK], true);
        $canContinuePayment = $canContinuePayment
            && ! in_array($pesanan->status, [Pesanan::STATUS_SELESAI, Pesanan::STATUS_DIBATALKAN], true);

        return [
            'found' => true,
            'order_id' => $pesanan->kode_pesanan,
            'product_name' => $productNames ?: 'Produk Sadita',
            'status' => $this->resolveTrackingStatusCode($pesanan->status),
            'status_label' => $this->resolveTrackingStatusLabel($pesanan->status),
            'status_key' => $pesanan->status,
            'payment_status' => $this->resolveTrackingPaymentStatusLabel($pembayaran?->status),
            'payment_method' => $pembayaran?->resolvedMetodeLabel() ?? '-',
            'total' => 'Rp '.number_format((int) $pesanan->grand_total, 0, ',', '.'),
            'delivery_date' => $pengiriman ? Carbon::parse($pengiriman->tanggal_pengiriman)->format('d M Y') : '-',
            'delivery_time' => $pengiriman ? Carbon::parse($pengiriman->jam_pengiriman)->format('H:i') : '-',
            'deadline' => $pesanan->batas_waktu_bayar?->translatedFormat('d M Y, H:i'),
            'invoice_url' => route('invoice.show', ['order_id' => $pesanan->kode_pesanan]),
            'track_url' => route('tracking.page', ['code' => $pesanan->kode_pesanan]),
            'can_continue_payment' => $canContinuePayment,
            'timeline' => $this->buildTrackingTimeline($pesanan, $pembayaran),
        ];
    }

    private function resolveTrackingStatusLabel(string $status): string
    {
        return match ($status) {
            Pesanan::STATUS_MENUNGGU => 'Menunggu Pembayaran',
            Pesanan::STATUS_DIPROSES => 'Sedang Diproses',
            Pesanan::STATUS_SIAPKIRIM => 'Siap Dikirim',
            Pesanan::STATUS_SELESAI => 'Selesai',
            Pesanan::STATUS_DIBATALKAN => 'Dibatalkan',
            default => str($status)->replace('_', ' ')->title()->toString(),
        };
    }

    private function resolveTrackingStatusCode(string $status): string
    {
        return match ($status) {
            Pesanan::STATUS_MENUNGGU => 'UNPAID',
            Pesanan::STATUS_DIPROSES => 'PAID',
            Pesanan::STATUS_SIAPKIRIM => 'DELIVERED',
            Pesanan::STATUS_SELESAI => 'SELESAI',
            Pesanan::STATUS_DIBATALKAN => 'DIBATALKAN',
            default => strtoupper($status),
        };
    }

    private function resolveTrackingPaymentStatusLabel(?string $status): string
    {
        return match ($status) {
            Pembayaran::STATUS_LUNAS => 'Pembayaran Lunas',
            Pembayaran::STATUS_DITOLAK => 'Pembayaran Ditolak',
            Pembayaran::STATUS_MENUNGGU => 'Menunggu Verifikasi',
            default => 'Belum Ada Pembayaran',
        };
    }

    private function buildTrackingTimeline(Pesanan $pesanan, ?Pembayaran $pembayaran): array
    {
        $isPaid = $pembayaran?->status === Pembayaran::STATUS_LUNAS;

        return [
            [
                'label' => 'Pesanan dibuat',
                'done' => true,
            ],
            [
                'label' => 'Pembayaran',
                'done' => $isPaid,
                'active' => ! $isPaid && $pesanan->status === Pesanan::STATUS_MENUNGGU,
            ],
            [
                'label' => 'Diproses',
                'done' => in_array($pesanan->status, [Pesanan::STATUS_DIPROSES, Pesanan::STATUS_SIAPKIRIM, Pesanan::STATUS_SELESAI], true),
                'active' => $pesanan->status === Pesanan::STATUS_DIPROSES,
            ],
            [
                'label' => 'Pengiriman',
                'done' => in_array($pesanan->status, [Pesanan::STATUS_SIAPKIRIM, Pesanan::STATUS_SELESAI], true),
                'active' => $pesanan->status === Pesanan::STATUS_SIAPKIRIM,
            ],
            [
                'label' => 'Selesai',
                'done' => $pesanan->status === Pesanan::STATUS_SELESAI,
                'active' => $pesanan->status === Pesanan::STATUS_SELESAI,
            ],
        ];
    }

    private function buildOrderViewData(Request $request, ?Pesanan $order = null): array
    {
        $detailItem = $order?->detailItems?->first();
        $produk = $detailItem?->produk;
        $pengiriman = $order?->pengiriman;
        $pelanggan = $order?->pelanggan;
        $pengembalian = $order?->pengembalian;

        $productName = $request->query('product', $detailItem?->nama_produk_snapshot ?? 'Sadita Exclusive Product');

        if (! $produk && $productName !== '') {
            $produk = Produk::where('nama', $productName)->first();
        }

        $priceNumeric = $detailItem?->harga_satuan_snapshot ?? (int) preg_replace('/[^0-9]/', '', (string) $request->query('price', '0'));
        $referenceMeta = $this->decodeReferenceMeta($detailItem?->referensi_desain);
        $serviceType = $this->normalizeServiceType(
            (string) $request->query('jenis', $order?->tipe_layanan ?? $produk?->kategori?->nama ?? ''),
            $productName
        );
        $isRentalOrder = (bool) ($produk?->is_sewa);

        return [
            'existingOrder' => $order,
            'productName' => $productName,
            'productPrice' => 'Rp '.number_format($priceNumeric, 0, ',', '.'),
            'productImg' => $request->query('img', $produk?->fotoUtamaUrl() ?? '/images/dekorasi-lamaran.jpg'),
            'productType' => $request->query('jenis', $order?->tipe_layanan ?? $produk?->kategori?->nama ?? ($produk?->is_sewa ? 'Sewa' : 'Layanan Sadita')),
            'orderFlowType' => $serviceType,
            'isRentalOrder' => $isRentalOrder,
            'formValues' => [
                'sender_name' => old('sender_name', $pelanggan?->nama_lengkap),
                'sender_phone' => old('sender_phone', $this->stripPhonePrefix($pelanggan?->no_hp)),
                'sender_email' => old('sender_email', $pelanggan?->email),
                'receiver_name' => old('receiver_name', $pengiriman?->nama_penerima),
                'untuk' => old('untuk', $pengiriman?->patokan_lokasi),
                'address' => old('address', $pengiriman?->alamat_lengkap),
                'delivery_date' => old('delivery_date', optional($pengiriman?->tanggal_pengiriman)->format('Y-m-d')),
                'delivery_time' => old('delivery_time', $this->formatDeliveryTimeForForm($pengiriman?->jam_pengiriman)),
                'pickup_date' => old('pickup_date', optional($pengembalian?->tanggal_pengambilan)->format('Y-m-d')),
                'pickup_time' => old('pickup_time', $this->formatSimpleTimeForForm($pengembalian?->jam_pengambilan)),
                'greeting_msg' => old('greeting_msg', $detailItem?->teks_ucapan),
                'special_instruction' => old('special_instruction', $order?->catatan_pembeli),
                'mode_hantaran' => old('mode_hantaran', $order?->mode_hantaran),
                'hantaran_occasion' => old('hantaran_occasion', $referenceMeta['occasion'] ?? ''),
                'hantaran_items' => old('hantaran_items', $referenceMeta['items'] ?? ''),
                'hantaran_theme' => old('hantaran_theme', $referenceMeta['theme'] ?? ''),
                'hantaran_budget' => old('hantaran_budget', $order?->estimasi_belanja ? (string) $order->estimasi_belanja : ''),
            ],
        ];
    }

    private function normalizeServiceType(string $jenis, string $productName = ''): string
    {
        $normalized = Str::slug($jenis !== '' ? $jenis : $productName);

        if (str_contains($normalized, 'hantaran') || str_contains($normalized, 'seserahan') || str_contains($normalized, 'gift-box')) {
            return 'hantaran';
        }

        if (str_contains($normalized, 'dekorasi') || str_contains($normalized, 'table-setting')) {
            return 'dekorasi';
        }

        return 'papan';
    }

    private function parseCurrencyInput(string $value): int
    {
        $numeric = preg_replace('/[^0-9]/', '', $value);

        return $numeric ? (int) $numeric : 0;
    }

    /**
     * @return array<string, string>
     */
    private function decodeReferenceMeta(?string $reference): array
    {
        if (! $reference) {
            return [];
        }

        $decoded = json_decode($reference, true);

        return is_array($decoded) ? $decoded : [];
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

    private function normalizeSimpleTime(?string $time): ?string
    {
        if (empty($time)) {
            return null;
        }

        if (preg_match('/^\d{2}:\d{2}$/', $time)) {
            return $time.':00';
        }

        return preg_match('/^\d{2}:\d{2}:\d{2}$/', $time) ? $time : null;
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

    private function formatSimpleTimeForForm(mixed $time): ?string
    {
        if ($time instanceof Carbon) {
            return $time->format('H:i');
        }

        if (is_string($time) && $time !== '') {
            return substr($time, 0, 5);
        }

        return null;
    }

    private function isRentalOrderRequest(Request $request, string $serviceType): bool
    {
        if ($serviceType === 'hantaran') {
            return false;
        }

        $productName = (string) $request->input('product_name', '');

        if ($productName === '') {
            return false;
        }

        return Produk::query()
            ->where('nama', $productName)
            ->where('is_sewa', true)
            ->exists();
    }

    private function notifyOwnersByEmail(Pesanan $pesanan): void
    {
        $owners = User::query()
            ->where('role', User::ROLE_OWNER)
            ->whereNotNull('email')
            ->get(['id', 'name', 'email']);

        foreach ($owners as $owner) {
            try {
                Mail::to($owner->email)->send(new OrderNotification($pesanan));

                $pesanan->emailLogs()->create([
                    'email_tujuan' => $owner->email,
                    'jenis' => 'pesanan_baru_owner',
                    'status' => 'sent',
                ]);
            } catch (Throwable) {
                $pesanan->emailLogs()->create([
                    'email_tujuan' => $owner->email,
                    'jenis' => 'pesanan_baru_owner',
                    'status' => 'failed',
                ]);
            }
        }
    }
}
