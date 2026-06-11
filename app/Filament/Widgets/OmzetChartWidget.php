<?php

namespace App\Filament\Widgets;

use App\Models\Pesanan;
use App\Support\DashboardCache;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class OmzetChartWidget extends ChartWidget
{
    protected ?string $heading = 'Omzet Penjualan (30 Hari Terakhir)';

    protected ?string $pollingInterval = null;

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        return Cache::remember(DashboardCache::OMZET_CHART, 60 * 10, function () {
            $data = Pesanan::select(
                DB::raw('DATE(waktu_selesai) as tanggal'),
                DB::raw('SUM(total_harga) as omzet')
            )
                ->whereNotNull('waktu_selesai')
                ->where('waktu_selesai', '>=', Carbon::now()->subDays(30))
                ->groupBy('tanggal')
                ->orderBy('tanggal')
                ->pluck('omzet', 'tanggal')
                ->toArray();

            $labels = [];
            $values = [];

            for ($i = 29; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i)->format('Y-m-d');
                $labelDate = Carbon::now()->subDays($i)->translatedFormat('d M');
                $labels[] = $labelDate;
                $values[] = $data[$date] ?? 0;
            }

            return [
                'datasets' => [
                    [
                        'label' => 'Omzet (Rp)',
                        'data' => $values,
                        'fill' => 'start',
                        'backgroundColor' => 'rgba(16, 185, 129, 0.15)',
                        'borderColor' => 'rgb(16, 185, 129)',
                        'borderWidth' => 2,
                        'tension' => 0.4,
                        'pointBackgroundColor' => 'rgb(16, 185, 129)',
                        'pointRadius' => 3,
                    ],
                ],
                'labels' => $labels,
            ];
        });
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => ['display' => false],
                'tooltip' => [
                    'callbacks' => [
                        'label' => "function(context) { return 'Rp ' + context.parsed.y.toLocaleString('id-ID'); }",
                    ],
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
        ];
    }
}
