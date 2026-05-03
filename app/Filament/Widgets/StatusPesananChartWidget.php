<?php

namespace App\Filament\Widgets;

use App\Models\Pesanan;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class StatusPesananChartWidget extends ChartWidget
{
    protected ?string $heading = 'Distribusi Status Pesanan';

    protected static ?string $pollingInterval = null;

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $statuses = Pesanan::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $labelMap = [
            'menunggu_pembayaran' => 'Menunggu Bayar',
            'diproses'            => 'Diproses',
            'siap_kirim'          => 'Siap Kirim',
            'selesai'             => 'Selesai',
            'dibatalkan'          => 'Dibatalkan',
        ];

        $colorMap = [
            'menunggu_pembayaran' => 'rgb(251, 191, 36)',
            'diproses'            => 'rgb(59, 130, 246)',
            'siap_kirim'          => 'rgb(139, 92, 246)',
            'selesai'             => 'rgb(16, 185, 129)',
            'dibatalkan'          => 'rgb(239, 68, 68)',
        ];

        $labels = [];
        $values = [];
        $colors = [];

        foreach ($statuses as $status => $total) {
            $labels[] = $labelMap[$status] ?? $status;
            $values[] = $total;
            $colors[] = $colorMap[$status] ?? 'rgb(107, 114, 128)';
        }

        return [
            'datasets' => [
                [
                    'data' => $values,
                    'backgroundColor' => $colors,
                    'borderWidth' => 0,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
