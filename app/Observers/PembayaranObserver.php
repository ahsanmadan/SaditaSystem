<?php

namespace App\Observers;

use App\Models\Pembayaran;
use App\Support\DashboardCache;
use App\Mail\PaymentConfirmedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class PembayaranObserver
{
    public function updated(Pembayaran $pembayaran): void
    {
        if ($pembayaran->isDirty('status')) {
            $this->invalidateCache("Pembayaran #{$pembayaran->id} status={$pembayaran->status}");

            if ($pembayaran->status === Pembayaran::STATUS_LUNAS) {
                $this->sendAdminNotification($pembayaran);
            }
        }
    }

    public function created(Pembayaran $pembayaran): void
    {
        $this->invalidateCache("Pembayaran baru #{$pembayaran->id}");

        if ($pembayaran->status === Pembayaran::STATUS_LUNAS) {
            $this->sendAdminNotification($pembayaran);
        }
    }

    private function sendAdminNotification(Pembayaran $pembayaran): void
    {
        try {
            $adminEmail = config('mail.admin_address');
            if (filled($adminEmail)) {
                Mail::to($adminEmail)->send(new PaymentConfirmedMail($pembayaran));
                Log::info("[EmailNotification] Email notifikasi pembayaran #{$pembayaran->id} terkirim ke admin: {$adminEmail}");
            } else {
                Log::warning("[EmailNotification] Gagal mengirim email: ADMIN_NOTIFICATION_EMAIL tidak diatur di .env.");
            }
        } catch (\Throwable $exception) {
            Log::error("[EmailNotification] Gagal mengirim email notifikasi pembayaran #{$pembayaran->id}: " . $exception->getMessage(), [
                'exception' => $exception
            ]);
        }
    }

    private function invalidateCache(string $reason): void
    {
        DashboardCache::forgetAll();

        Log::info("[CacheInvalidation] {$reason} -> cache dashboard di-reset.");
    }
}
