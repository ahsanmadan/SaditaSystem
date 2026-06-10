<?php

namespace App\Filament\Widgets;

use App\Services\Analytics\DashboardKpiService;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected ?string $pollingInterval = null;

    protected ?string $heading = 'Ringkasan Bisnis Sadita';

    protected function getStats(): array
    {
        $kpi = new DashboardKpiService;
        $stats = $kpi->getOverviewStats();

        $omzet = $stats['omzet'];
        $kasIn = $stats['kas_masuk'];
        $profit = $stats['profit'];
        $pending = $stats['pending'];
        $repeat = $stats['repeat'];

        return [
            Stat::make('Omzet Hari Ini', 'Rp '.number_format($omzet, 0, ',', '.'))
                ->description('Nilai produk/jasa terjual (selesai hari ini)')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color($omzet > 0 ? 'success' : 'gray')
                ->chart([0, $omzet]),

            Stat::make('Kas Masuk Hari Ini', 'Rp '.number_format($kasIn, 0, ',', '.'))
                ->description('Pembayaran lunas yang masuk hari ini')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color($kasIn > 0 ? 'info' : 'gray'),

            Stat::make('Profit Kotor Bulan Ini', 'Rp '.number_format($profit, 0, ',', '.'))
                ->description('Omzet dikurangi pengeluaran modal')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color($profit > 0 ? 'success' : 'danger'),

            Stat::make('Menunggu Pembayaran', $pending.' pesanan')
                ->description('Pesanan belum dikonfirmasi pelanggan')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pending > 10 ? 'danger' : 'warning'),

            Stat::make('Repeat Customer', $repeat.' pelanggan')
                ->description('Pelanggan dengan lebih dari 1 pesanan')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('info'),
        ];
    }
}
