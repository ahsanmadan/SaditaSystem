<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Services\Payments\DokuCheckoutService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class DokuPaymentController extends Controller
{
    public function checkout(string $order_id, DokuCheckoutService $dokuCheckoutService): RedirectResponse
    {
        $pesanan = Pesanan::with(['pelanggan', 'pengiriman', 'detailItems', 'pembayaranTerakhir'])
            ->where('kode_pesanan', $order_id)
            ->firstOrFail();

        $selectedMethod = request()->input('payment_method', 'ALL');
        $availableMethods = collect(config('doku.payment_methods', []))->pluck('code')->all();

        if (! in_array($selectedMethod, $availableMethods, true)) {
            return back()->with('error', 'Metode pembayaran yang dipilih tidak valid.');
        }

        $methodTypes = $selectedMethod !== 'ALL' ? [$selectedMethod] : [];

        if (! $dokuCheckoutService->isConfigured()) {
            return back()->with('error', 'Konfigurasi DOKU belum diisi. Hubungi admin untuk melengkapi credential sandbox/production.');
        }

        $existingPayment = $pesanan->pembayaranTerakhir;

        if (
            $existingPayment &&
            $existingPayment->isGatewayDoku() &&
            $existingPayment->status === Pembayaran::STATUS_MENUNGGU &&
            filled($existingPayment->checkout_url) &&
            (blank($existingPayment->expires_at) || now()->lt($existingPayment->expires_at))
        ) {
            return redirect()->away($existingPayment->checkout_url);
        }

        try {
            $checkout = $dokuCheckoutService->createCheckout($pesanan, $methodTypes);
        } catch (RuntimeException $exception) {
            Log::warning('DOKU checkout gagal dibuat', [
                'order_id' => $pesanan->kode_pesanan,
                'message' => $exception->getMessage(),
            ]);

            return back()->with('error', 'Gagal membuat link pembayaran DOKU: '.$exception->getMessage());
        }

        $payment = $existingPayment && $existingPayment->isGatewayDoku()
            ? $existingPayment
            : new Pembayaran([
                'pesanan_id' => $pesanan->id,
                'metode' => $selectedMethod !== 'ALL' ? $selectedMethod : Pembayaran::METODE_DOKU_CHECKOUT,
            ]);

        $payment->fill([
            'metode' => $selectedMethod !== 'ALL' ? $selectedMethod : ($payment->metode ?: Pembayaran::METODE_DOKU_CHECKOUT),
            'jumlah_dibayar' => $pesanan->grand_total,
            'bukti_transfer' => '',
            'status' => Pembayaran::STATUS_MENUNGGU,
            'gateway_provider' => Pembayaran::GATEWAY_DOKU,
            'gateway_request_id' => $checkout['request_id'],
            'gateway_reference' => $checkout['token_id'],
            'checkout_url' => $checkout['checkout_url'],
            'gateway_status' => 'PENDING',
            'gateway_payload' => $checkout['payload'],
            'gateway_response' => $checkout['response'],
            'expires_at' => $this->parseDokuExpiry($checkout['expired_date']),
        ]);

        $payment->save();

        return redirect()->away($checkout['checkout_url']);
    }

    public function handleReturn(string $order_id): RedirectResponse
    {
        return $this->syncPaymentStatus($order_id, app(DokuCheckoutService::class), true);
    }

    public function refreshStatus(string $order_id, DokuCheckoutService $dokuCheckoutService): RedirectResponse
    {
        return $this->syncPaymentStatus($order_id, $dokuCheckoutService, false);
    }

    public function handleNotification(Request $request, DokuCheckoutService $dokuCheckoutService): JsonResponse
    {
        if (! $dokuCheckoutService->verifyNotificationSignature($request)) {
            return response()->json([
                'responseCode' => '401',
                'responseMessage' => 'Invalid signature',
            ], 401);
        }

        $payload = $request->json()->all();
        $invoiceNumber = data_get($payload, 'order.invoice_number')
            ?? data_get($payload, 'response.order.invoice_number')
            ?? data_get($payload, 'transaction.order')
            ?? data_get($payload, 'virtual_account_info.invoice_number');

        if (! $invoiceNumber) {
            return response()->json([
                'responseCode' => '400',
                'responseMessage' => 'Invoice number not found',
            ], 400);
        }

        $pesanan = Pesanan::where('kode_pesanan', $invoiceNumber)->first();

        if (! $pesanan) {
            return response()->json([
                'responseCode' => '404',
                'responseMessage' => 'Order not found',
            ], 404);
        }

        $paymentStatus = strtoupper(
            (string) (
                data_get($payload, 'transaction.status')
                ?? data_get($payload, 'latestTransactionStatus')
                ?? data_get($payload, 'response.transaction.status')
                ?? data_get($payload, 'status')
                ?? 'PENDING'
            )
        );

        $amount = (int) (
            data_get($payload, 'transaction.amount')
            ?? data_get($payload, 'amount.value')
            ?? data_get($payload, 'order.amount')
            ?? $pesanan->grand_total
        );

        $payment = Pembayaran::query()
            ->where('pesanan_id', $pesanan->id)
            ->where('gateway_provider', Pembayaran::GATEWAY_DOKU)
            ->latest('id')
            ->first()
            ?? new Pembayaran([
                'pesanan_id' => $pesanan->id,
                'metode' => Pembayaran::METODE_DOKU_CHECKOUT,
                'gateway_provider' => Pembayaran::GATEWAY_DOKU,
            ]);
        $wasPaid = $payment->exists && $payment->status === Pembayaran::STATUS_LUNAS;

        $payment->fill([
            'metode' => $this->resolveDokuMethod($payload, $payment->metode),
            'jumlah_dibayar' => $amount,
            'bukti_transfer' => $payment->bukti_transfer ?? '',
            'status' => $this->mapInternalPaymentStatus($paymentStatus),
            'gateway_provider' => Pembayaran::GATEWAY_DOKU,
            'gateway_request_id' => (string) $request->header('Request-Id', ''),
            'gateway_reference' => data_get($payload, 'response.payment.token_id')
                ?? data_get($payload, 'transaction.virtualAccountNo')
                ?? data_get($payload, 'originalReferenceNo'),
            'gateway_status' => $paymentStatus,
            'gateway_response' => $payload,
            'waktu_dibayar' => $this->isPaidStatus($paymentStatus) ? now() : $payment->waktu_dibayar,
        ]);

        $payment->save();

        if ($this->isPaidStatus($paymentStatus)) {
            $pesanan->update(['status' => Pesanan::STATUS_DIPROSES]);

            if (! $wasPaid && $pesanan->kodePromo && ! $pesanan->kodePromo->isQuotaExceeded()) {
                $pesanan->kodePromo->increment('dipakai');
            }
        }

        return response()->json([
            'responseCode' => '200',
            'responseMessage' => 'Success',
        ]);
    }

    private function parseDokuExpiry(?string $expiredDate): ?Carbon
    {
        if (blank($expiredDate)) {
            return null;
        }

        return rescue(
            fn () => Carbon::createFromFormat('YmdHis', (string) $expiredDate, 'UTC')->setTimezone(config('app.timezone')),
            report: false,
        );
    }

    private function mapInternalPaymentStatus(string $gatewayStatus): string
    {
        return match ($gatewayStatus) {
            'SUCCESS', 'PAID', '00' => Pembayaran::STATUS_LUNAS,
            'FAILED', 'EXPIRED', 'CANCELLED', 'CANCELED', '05', '06' => Pembayaran::STATUS_DITOLAK,
            default => Pembayaran::STATUS_MENUNGGU,
        };
    }

    private function isPaidStatus(string $gatewayStatus): bool
    {
        return in_array($gatewayStatus, ['SUCCESS', 'PAID', '00'], true);
    }

    private function resolveDokuMethod(array $payload, ?string $fallback = null): string
    {
        return Pembayaran::extractDokuMethodCode($payload, null, $fallback);
    }

    private function syncPaymentStatus(
        string $order_id,
        DokuCheckoutService $dokuCheckoutService,
        bool $fromReturnPage
    ): RedirectResponse {
        $pesanan = Pesanan::with(['kodePromo', 'pembayaranTerakhir'])
            ->where('kode_pesanan', $order_id)
            ->firstOrFail();

        $payment = $pesanan->pembayaranTerakhir;

        if (! $payment || ! $payment->isGatewayDoku()) {
            return redirect()
                ->route('invoice.show', ['order_id' => $order_id])
                ->with('error', 'Data pembayaran DOKU untuk pesanan ini tidak ditemukan.');
        }

        if ($payment->status === Pembayaran::STATUS_LUNAS) {
            return redirect()
                ->route('invoice.show', ['order_id' => $order_id])
                ->with('success', 'Pembayaran sudah terkonfirmasi.');
        }

        $identifier = $payment->gateway_request_id ?: $pesanan->kode_pesanan;

        try {
            $statusResult = $dokuCheckoutService->checkTransactionStatus($identifier);
        } catch (RuntimeException $exception) {
            Log::warning('DOKU check status gagal', [
                'order_id' => $pesanan->kode_pesanan,
                'identifier' => $identifier,
                'message' => $exception->getMessage(),
            ]);

            return redirect()
                ->route('invoice.show', ['order_id' => $order_id])
                ->with('error', 'Belum bisa sinkron status pembayaran DOKU: '.$exception->getMessage());
        }

        $previouslyPaid = $payment->status === Pembayaran::STATUS_LUNAS;
        $gatewayStatus = $statusResult['status'];

        $payment->fill([
            'metode' => $this->resolveDokuMethod($statusResult['response'], $payment->metode),
            'status' => $this->mapInternalPaymentStatus($gatewayStatus),
            'gateway_status' => $gatewayStatus,
            'gateway_response' => $statusResult['response'],
            'waktu_dibayar' => $this->isPaidStatus($gatewayStatus) ? ($payment->waktu_dibayar ?? now()) : $payment->waktu_dibayar,
        ]);
        $payment->save();

        if ($this->isPaidStatus($gatewayStatus)) {
            $pesanan->update(['status' => Pesanan::STATUS_DIPROSES]);

            if (! $previouslyPaid && $pesanan->kodePromo && ! $pesanan->kodePromo->isQuotaExceeded()) {
                $pesanan->kodePromo->increment('dipakai');
            }

            return redirect()
                ->route('invoice.show', ['order_id' => $order_id])
                ->with('success', 'Pembayaran DOKU berhasil terkonfirmasi.');
        }

        $message = $fromReturnPage
            ? 'Anda sudah kembali dari DOKU, tetapi status pembayaran masih '.$gatewayStatus.'. Coba cek status lagi dalam 1 menit.'
            : 'Status pembayaran saat ini masih '.$gatewayStatus.'. Coba lagi sebentar lagi.';

        return redirect()
            ->route('invoice.show', ['order_id' => $order_id])
            ->with('info', $message);
    }
}
