<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    // ─── Konstanta Status Pembayaran ─────────────────────────────────────────
    const STATUS_MENUNGGU = 'menunggu';

    const STATUS_LUNAS = 'lunas';

    const STATUS_DITOLAK = 'ditolak';

    const METODE_DOKU_CHECKOUT = 'doku_checkout';

    const GATEWAY_DOKU = 'doku';

    protected $table = 'pembayaran';

    protected $guarded = ['id'];

    protected $casts = [
        'waktu_dibayar' => 'datetime',
        'waktu_diverifikasi' => 'datetime',
        'expires_at' => 'datetime',
        'gateway_payload' => 'array',
        'gateway_response' => 'array',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id', 'id');
    }

    public function verifikator()
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh', 'id');
    }

    public function isGatewayDoku(): bool
    {
        return $this->gateway_provider === self::GATEWAY_DOKU;
    }

    public function resolvedMetodeCode(): string
    {
        if ($this->isGatewayDoku() && $this->metode === self::METODE_DOKU_CHECKOUT) {
            return self::extractDokuMethodCode($this->gateway_response, $this->gateway_payload, $this->metode);
        }

        return (string) $this->metode;
    }

    public function resolvedMetodeLabel(): string
    {
        return self::formatMetodeLabel($this->resolvedMetodeCode());
    }

    public static function extractDokuMethodCode(?array $response, ?array $payload = null, ?string $fallback = null): string
    {
        $candidates = [
            data_get($response, 'channel.id'),
            data_get($response, 'response.channel.id'),
            data_get($response, 'payment.channel.id'),
            data_get($response, 'additionalInfo.channel.id'),
            data_get($response, 'additional_info.channel.id'),
            data_get($response, 'additionalInfo.acquirer.id'),
            data_get($response, 'additional_info.acquirer.id'),
            data_get($response, 'acquirer.id'),
            data_get($response, 'service.id'),
            data_get($payload, 'payment.payment_method_types.0'),
        ];

        foreach ($candidates as $candidate) {
            if (filled($candidate)) {
                return (string) $candidate;
            }
        }

        return $fallback ?: self::METODE_DOKU_CHECKOUT;
    }

    public static function formatMetodeLabel(?string $method): string
    {
        $method = trim((string) $method);

        if ($method === '') {
            return '-';
        }

        return match ($method) {
            self::METODE_DOKU_CHECKOUT => 'DOKU Checkout',
            'transfer_bank' => 'Transfer Bank',
            'cash' => 'Cash',
            'qris' => 'QRIS',
            'cod' => 'COD',
            'VIRTUAL_ACCOUNT_BCA' => 'Virtual Account BCA',
            'VIRTUAL_ACCOUNT_BRI' => 'Virtual Account BRI',
            'VIRTUAL_ACCOUNT_BNI' => 'Virtual Account BNI',
            'VIRTUAL_ACCOUNT_BANK_MANDIRI' => 'Virtual Account Mandiri',
            'VIRTUAL_ACCOUNT_BANK_PERMATA' => 'Virtual Account Permata',
            'VIRTUAL_ACCOUNT_DOKU' => 'Virtual Account DOKU',
            'VIRTUAL_ACCOUNT_BANK_CIMB' => 'Virtual Account CIMB',
            'VIRTUAL_ACCOUNT_BANK_DANAMON' => 'Virtual Account Danamon',
            'VIRTUAL_ACCOUNT_BANK_SYARIAH_MANDIRI' => 'Virtual Account BSM',
            'VIRTUAL_ACCOUNT_MAYBANK' => 'Virtual Account Maybank',
            'VIRTUAL_ACCOUNT_BTN' => 'Virtual Account BTN',
            'VIRTUAL_ACCOUNT_BNC' => 'Virtual Account BNC',
            'VIRTUAL_ACCOUNT_SINARMAS' => 'Virtual Account Sinarmas',
            'EMONEY_OVO' => 'OVO',
            'EMONEY_DANA' => 'DANA',
            'EMONEY_SHOPEE_PAY' => 'ShopeePay',
            'EMONEY_LINKAJA' => 'LinkAja',
            'EMONEY_DOKU' => 'DOKU e-Wallet',
            'CREDIT_CARD' => 'Kartu Kredit',
            'DIRECT_DEBIT_BRI' => 'Direct Debit BRI',
            'DIRECT_DEBIT_CIMB' => 'Direct Debit CIMB',
            'DIRECT_DEBIT_ALLO' => 'Direct Debit Allo',
            'KLIKPAY_BCA' => 'KlikBCA',
            'PERMATA_NET' => 'PermataNet',
            'DANAMON_ONLINE_BANKING' => 'Danamon Online Banking',
            'PEER_TO_PEER_KREDIVO' => 'Kredivo',
            'PEER_TO_PEER_INDODANA' => 'Indodana',
            'PEER_TO_PEER_BRI_CERIA' => 'BRI Ceria',
            'ONLINE_TO_OFFLINE_ALFA' => 'Alfamart',
            'ONLINE_TO_OFFLINE_INDOMARET' => 'Indomaret',
            'JENIUS_PAY' => 'Jenius Pay',
            'OCTO_CLICKS' => 'OCTO Clicks',
            default => str($method)->replace('_', ' ')->title()->toString(),
        };
    }
}
