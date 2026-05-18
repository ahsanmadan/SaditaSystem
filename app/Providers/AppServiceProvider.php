<?php

namespace App\Providers;

use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\PengeluaranPesanan;
use App\Models\Ulasan;
use App\Observers\PembayaranObserver;
use App\Observers\PesananObserver;
use App\Observers\PengeluaranPesananObserver;
use App\Observers\UlasanObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (app()->environment('production') && str_starts_with((string) config('app.url'), 'https://')) {
            \URL::forceScheme('https');
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Cache Invalidation Observers (Sprint 4 - Hardening)
        Pembayaran::observe(PembayaranObserver::class);
        Pesanan::observe(PesananObserver::class);
        PengeluaranPesanan::observe(PengeluaranPesananObserver::class);
        Ulasan::observe(UlasanObserver::class);
    }
}
