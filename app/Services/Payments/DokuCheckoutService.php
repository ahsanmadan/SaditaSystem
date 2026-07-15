<?php

namespace App\Services\Payments;

use App\Models\Pesanan;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RuntimeException;

class DokuCheckoutService
{
    private const CHECKOUT_PAYMENT_PATH = '/checkout/v1/payment';
    private const CHECK_STATUS_PATH = '/orders/v1/status';

    public function isConfigured(): bool
    {
        return filled(config('doku.client_id')) && filled(config('doku.secret_key'));
    }

    public function createCheckout(Pesanan $pesanan, array $paymentMethodTypes = []): array
    {
        if (! $this->isConfigured()) {
            throw new RuntimeException('Konfigurasi DOKU belum lengkap.');
        }

        $payload = $this->buildPayload($pesanan, $paymentMethodTypes);
        $requestId = (string) Str::uuid();
        $requestTimestamp = now()->utc()->format('Y-m-d\TH:i:s\Z');
        $body = json_encode($payload, JSON_UNESCAPED_SLASHES);

        if ($body === false) {
            throw new RuntimeException('Payload DOKU gagal dibentuk.');
        }

        $digest = $this->generateDigest($body);

        $url = rtrim((string) config('doku.base_url'), '/').self::CHECKOUT_PAYMENT_PATH;
        $signature = $this->generateSignature(
            clientId: (string) config('doku.client_id'),
            requestId: $requestId,
            requestTimestamp: $requestTimestamp,
            requestTarget: self::CHECKOUT_PAYMENT_PATH,
            digest: $digest,
            secret: (string) config('doku.secret_key'),
        );

        Log::info('DOKU checkout request prepared', [
            'url' => $url,
            'request_id' => $requestId,
            'request_timestamp' => $requestTimestamp,
            'request_target' => self::CHECKOUT_PAYMENT_PATH,
            'digest' => $digest,
            'has_override_notification_url' => data_get($payload, 'additional_info.override_notification_url') !== null,
            'override_notification_url' => data_get($payload, 'additional_info.override_notification_url'),
            'signature_component' => $this->buildSignatureComponent(
                clientId: (string) config('doku.client_id'),
                requestId: $requestId,
                requestTimestamp: $requestTimestamp,
                requestTarget: self::CHECKOUT_PAYMENT_PATH,
                digest: $digest,
            ),
        ]);

        $response = Http::acceptJson()
            ->withHeaders([
                'Client-Id' => config('doku.client_id'),
                'Request-Id' => $requestId,
                'Request-Timestamp' => $requestTimestamp,
                'Signature' => $signature,
                'Digest' => $digest,
                'Content-Type' => 'application/json',
            ])
            ->withBody($body, 'application/json')
            ->send('POST', $url);

        $this->throwIfUnsuccessful($response);

        $data = $response->json();

        return [
            'request_id' => $requestId,
            'payload' => $payload,
            'response' => $data,
            'checkout_url' => data_get($data, 'response.payment.url'),
            'token_id' => data_get($data, 'response.payment.token_id'),
            'expired_date' => data_get($data, 'response.payment.expired_date'),
        ];
    }

    public function verifyNotificationSignature(Request $request): bool
    {
        $signature = (string) $request->header('Signature', '');
        $clientId = (string) $request->header('Client-Id', '');
        $requestId = (string) $request->header('Request-Id', '');
        $requestTimestamp = (string) $request->header('Request-Timestamp', '');

        if ($signature === '' || $clientId === '' || $requestId === '' || $requestTimestamp === '') {
            return false;
        }

        $body = $request->getContent();
        $digest = $body !== '' ? $this->generateDigest($body) : '';
        $requestTarget = '/'.$request->path();

        $expected = $this->generateSignature(
            clientId: $clientId,
            requestId: $requestId,
            requestTimestamp: $requestTimestamp,
            requestTarget: $requestTarget,
            digest: $digest,
            secret: (string) config('doku.secret_key'),
        );

        return hash_equals($expected, $signature);
    }

    public function checkTransactionStatus(string $identifier): array
    {
        if (! $this->isConfigured()) {
            throw new RuntimeException('Konfigurasi DOKU belum lengkap.');
        }

        if ($identifier === '') {
            throw new RuntimeException('Identifier transaksi DOKU tidak tersedia.');
        }

        $requestId = (string) Str::uuid();
        $requestTimestamp = now()->utc()->format('Y-m-d\TH:i:s\Z');
        $requestTarget = self::CHECK_STATUS_PATH.'/'.rawurlencode($identifier);
        $url = rtrim((string) config('doku.base_url'), '/').$requestTarget;

        $signature = $this->generateSignature(
            clientId: (string) config('doku.client_id'),
            requestId: $requestId,
            requestTimestamp: $requestTimestamp,
            requestTarget: $requestTarget,
            digest: '',
            secret: (string) config('doku.secret_key'),
        );

        $response = Http::acceptJson()
            ->withHeaders([
                'Client-Id' => config('doku.client_id'),
                'Request-Id' => $requestId,
                'Request-Timestamp' => $requestTimestamp,
                'Signature' => $signature,
            ])
            ->get($url);

        if (! $response->successful()) {
            $message = data_get($response->json(), 'response_message')
                ?? data_get($response->json(), 'error.message')
                ?? 'Gagal mengecek status pembayaran DOKU.';

            throw new RuntimeException($message);
        }

        return [
            'request_id' => $requestId,
            'identifier' => $identifier,
            'response' => $response->json(),
            'status' => strtoupper((string) (
                data_get($response->json(), 'transaction.status')
                ?? data_get($response->json(), 'transaction_status')
                ?? data_get($response->json(), 'latestTransactionStatus')
                ?? data_get($response->json(), 'status')
                ?? 'PENDING'
            )),
        ];
    }

    private function buildPayload(Pesanan $pesanan, array $paymentMethodTypes = []): array
    {
        $pelanggan = $pesanan->pelanggan;
        $pengiriman = $pesanan->pengiriman;
        $detailItems = $pesanan->detailItems;

        $customerName = $pelanggan?->nama_lengkap ?: 'Pelanggan Sadita';
        [$firstName, $lastName] = $this->splitName($customerName);
        $email = $pelanggan?->email ?: 'guest+'.strtolower($pesanan->kode_pesanan).'@sadita.local';
        $phone = $this->normalizePhone($pelanggan?->no_hp ?: $pengiriman?->no_hp_penerima ?: '');
        $address = $pengiriman?->alamat_lengkap ?: 'Alamat akan dikonfirmasi admin';

        $productNames = $detailItems->pluck('nama_produk_snapshot')->implode(', ');
        if (blank($productNames)) {
            $productNames = 'Produk Sadita';
        }

        $payload = [
            'order' => [
                'invoice_number' => $pesanan->kode_pesanan,
                'amount' => (int) $pesanan->grand_total,
                'currency' => 'IDR',
                'callback_url' => $this->resolveReturnUrl($pesanan),
                'line_items' => [
                    [
                        'name' => $productNames,
                        'price' => (int) $pesanan->grand_total,
                        'quantity' => 1,
                    ]
                ],
            ],
            'payment' => [
                'payment_due_date' => (int) config('doku.payment_due_minutes', 60),
            ],
            'customer' => [
                'id' => (string) $pesanan->pelanggan_id,
                'name' => $customerName,
                'email' => $email,
                'phone' => $phone,
                'address' => $address,
                'country' => 'ID',
            ],
            'shipping_address' => [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'address' => $address,
                'city' => (string) config('doku.default_city', 'Padang'),
                'postal_code' => (string) config('doku.default_postal_code', '25100'),
                'phone' => $phone,
                'country_code' => (string) config('doku.default_country_code', 'IDN'),
            ],
            'billing_address' => [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'address' => $address,
                'city' => (string) config('doku.default_city', 'Padang'),
                'postal_code' => (string) config('doku.default_postal_code', '25100'),
                'phone' => $phone,
                'country_code' => (string) config('doku.default_country_code', 'IDN'),
            ],
        ];

        if ($notificationUrl = $this->resolveNotificationUrl()) {
            $payload['additional_info'] = [
                'override_notification_url' => $notificationUrl,
            ];
        }

        if ($paymentMethodTypes !== []) {
            $payload['payment']['payment_method_types'] = array_values($paymentMethodTypes);
        }

        return $payload;
    }

    private function throwIfUnsuccessful(Response $response): void
    {
        if ($response->successful() && filled(data_get($response->json(), 'response.payment.url'))) {
            return;
        }

        $message = data_get($response->json(), 'message.0')
            ?? data_get($response->json(), 'error.message')
            ?? 'Gagal membuat pembayaran DOKU.';

        throw new RuntimeException($message);
    }

    private function generateDigest(string $body): string
    {
        return base64_encode(hash('sha256', $body, true));
    }

    private function generateSignature(
        string $clientId,
        string $requestId,
        string $requestTimestamp,
        string $requestTarget,
        string $digest,
        string $secret
    ): string {
        $component = $this->buildSignatureComponent(
            clientId: $clientId,
            requestId: $requestId,
            requestTimestamp: $requestTimestamp,
            requestTarget: $requestTarget,
            digest: $digest,
        );

        return 'HMACSHA256='.base64_encode(hash_hmac('sha256', $component, $secret, true));
    }

    private function buildSignatureComponent(
        string $clientId,
        string $requestId,
        string $requestTimestamp,
        string $requestTarget,
        string $digest
    ): string {
        return implode("\n", array_filter([
            'Client-Id:'.$clientId,
            'Request-Id:'.$requestId,
            'Request-Timestamp:'.$requestTimestamp,
            'Request-Target:'.$requestTarget,
            $digest !== '' ? 'Digest:'.$digest : null,
        ]));
    }

    private function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/\D+/', '', $phone);

        if ($phone === null || $phone === '') {
            return '6280000000000';
        }

        if (str_starts_with($phone, '0')) {
            return '62'.substr($phone, 1);
        }

        if (! str_starts_with($phone, '62')) {
            return '62'.$phone;
        }

        return $phone;
    }

    private function splitName(string $name): array
    {
        $parts = preg_split('/\s+/', trim($name)) ?: [];
        $firstName = $parts[0] ?? 'Pelanggan';
        $lastName = count($parts) > 1 ? implode(' ', array_slice($parts, 1)) : '-';

        return [$firstName, $lastName];
    }

    private function resolveReturnUrl(Pesanan $pesanan): string
    {
        $configuredUrl = (string) config('doku.return_url', '');

        if ($configuredUrl !== '') {
            return str_replace('{order_id}', $pesanan->kode_pesanan, $configuredUrl);
        }

        return route('doku.return', ['order_id' => $pesanan->kode_pesanan]);
    }

    private function resolveNotificationUrl(): ?string
    {
        $configuredUrl = (string) config('doku.notification_url', '');
        $url = $configuredUrl !== '' ? $configuredUrl : route('doku.notify');

        return $this->isPublicUrl($url) ? $url : null;
    }

    private function isPublicUrl(string $url): bool
    {
        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        $host = strtolower((string) parse_url($url, PHP_URL_HOST));

        if ($host === '' || in_array($host, ['localhost', '127.0.0.1', '::1'], true)) {
            return false;
        }

        if (str_ends_with($host, '.local')) {
            return false;
        }

        return ! str_starts_with($host, '192.168.')
            && ! str_starts_with($host, '10.')
            && ! preg_match('/^172\.(1[6-9]|2\d|3[0-1])\./', $host);
    }
}
