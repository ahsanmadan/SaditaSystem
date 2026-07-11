<?php

namespace App\Services\Analytics;

use App\Models\Pelanggan;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\Ulasan;
use App\Support\DashboardCache;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardPayloadService
{
    private const BUSINESS_START_DATE = '2026-01-06';

    public function get(): array
    {
        return Cache::remember(DashboardCache::DASHBOARD_PAYLOAD, 60 * 5, fn () => [
            'generated_at' => now()->timezone('Asia/Jakarta')->format('d M Y, H:i') . ' WIB',
            'stats' => $this->getStats(),
            'status_breakdown' => $this->getStatusBreakdown(),
            'omzet_trend' => $this->getOmzetTrend(),
            'recent_orders' => $this->getRecentOrders(),
            'top_customers' => $this->getTopCustomers(),
        ]);
    }

    protected function getStats(): array
    {
        $today = Carbon::today();
        $yesterday = $today->copy()->subDay();
        $monthStart = now()->startOfMonth();
        $previousMonthStart = $monthStart->copy()->subMonth();

        $omzetToday = (int) Pesanan::query()
            ->whereDate('waktu_selesai', $today)
            ->sum('total_harga');

        $omzetYesterday = (int) Pesanan::query()
            ->whereDate('waktu_selesai', $yesterday)
            ->sum('total_harga');

        $completedOrdersToday = Pesanan::query()
            ->whereDate('waktu_selesai', $today)
            ->count();

        $cashInToday = (int) Pembayaran::query()
            ->where('status', Pembayaran::STATUS_LUNAS)
            ->whereDate('waktu_dibayar', $today)
            ->sum('jumlah_dibayar');

        $cashInYesterday = (int) Pembayaran::query()
            ->where('status', Pembayaran::STATUS_LUNAS)
            ->whereDate('waktu_dibayar', $yesterday)
            ->sum('jumlah_dibayar');

        $paidInvoicesToday = Pembayaran::query()
            ->where('status', Pembayaran::STATUS_LUNAS)
            ->whereDate('waktu_dibayar', $today)
            ->count();

        $profitThisMonth = (int) (
            Pesanan::query()
                ->where('waktu_selesai', '>=', $monthStart)
                ->sum('total_harga')
            - DB::table('pengeluaran_pesanan')
                ->join('pesanan', 'pengeluaran_pesanan.pesanan_id', '=', 'pesanan.id')
                ->where('pesanan.waktu_selesai', '>=', $monthStart)
                ->sum('pengeluaran_pesanan.nominal')
        );

        $profitLastMonth = (int) (
            Pesanan::query()
                ->whereBetween('waktu_selesai', [$previousMonthStart, $monthStart])
                ->sum('total_harga')
            - DB::table('pengeluaran_pesanan')
                ->join('pesanan', 'pengeluaran_pesanan.pesanan_id', '=', 'pesanan.id')
                ->whereBetween('pesanan.waktu_selesai', [$previousMonthStart, $monthStart])
                ->sum('pengeluaran_pesanan.nominal')
        );

        $pendingOrders = Pesanan::query()
            ->where('status', Pesanan::STATUS_MENUNGGU)
            ->count();

        $readyToShipOrders = Pesanan::query()
            ->where('status', Pesanan::STATUS_SIAPKIRIM)
            ->count();

        $repeatCustomers = DB::table('pesanan')
            ->select('pelanggan_id')
            ->groupBy('pelanggan_id')
            ->havingRaw('COUNT(id) > 1')
            ->get()
            ->count();

        $ratingSummary = Ulasan::query()
            ->selectRaw('COUNT(*) as total_ulasan')
            ->selectRaw('COALESCE(AVG(rating), 0) as rata_rating')
            ->where('is_tampil', true)
            ->first();

        $publishedReviews = (int) ($ratingSummary->total_ulasan ?? 0);
        $averageRating = round((float) ($ratingSummary->rata_rating ?? 0), 1);

        return [
            [
                'label' => 'Omzet Hari Ini',
                'value' => $this->rupiah($omzetToday),
                'description' => 'Nilai pesanan selesai hari ini',
                'context' => $completedOrdersToday > 0
                    ? number_format($completedOrdersToday, 0, ',', '.') . ' pesanan selesai hari ini'
                    : 'Belum ada pesanan selesai hari ini',
                'trend' => $this->comparisonLabel($omzetToday, $omzetYesterday, 'dibanding kemarin'),
                'tone' => $omzetToday > 0 ? 'success' : 'neutral',
            ],
            [
                'label' => 'Kas Masuk Hari Ini',
                'value' => $this->rupiah($cashInToday),
                'description' => 'Pembayaran lunas yang masuk hari ini',
                'context' => $paidInvoicesToday > 0
                    ? number_format($paidInvoicesToday, 0, ',', '.') . ' pembayaran lunas terverifikasi'
                    : 'Belum ada pembayaran lunas terverifikasi',
                'trend' => $this->comparisonLabel($cashInToday, $cashInYesterday, 'dibanding kemarin'),
                'tone' => $cashInToday > 0 ? 'info' : 'neutral',
            ],
            [
                'label' => 'Profit Kotor Bulan Ini',
                'value' => $this->rupiah($profitThisMonth),
                'description' => 'Omzet selesai dikurangi modal',
                'context' => $profitThisMonth >= 0
                    ? 'Margin kotor masih terjaga di bulan berjalan'
                    : 'Modal bulan ini masih lebih besar dari omzet selesai',
                'trend' => $this->comparisonLabel($profitThisMonth, $profitLastMonth, 'dibanding bulan lalu'),
                'tone' => $profitThisMonth >= 0 ? 'success' : 'danger',
            ],
            [
                'label' => 'Menunggu Pembayaran',
                'value' => number_format($pendingOrders, 0, ',', '.') . ' pesanan',
                'description' => 'Perlu follow-up pelanggan',
                'context' => $readyToShipOrders > 0
                    ? number_format($readyToShipOrders, 0, ',', '.') . ' pesanan lain sudah siap kirim'
                    : 'Belum ada antrean siap kirim saat ini',
                'trend' => number_format($repeatCustomers, 0, ',', '.') . ' pelanggan pernah order lebih dari sekali',
                'tone' => $pendingOrders > 10 ? 'warning' : 'neutral',
            ],
            [
                'label' => 'Total Rating Kita',
                'value' => number_format($averageRating, 1, ',', '.') . '/5',
                'description' => 'Rata-rata rating dari ulasan tampil',
                'context' => $publishedReviews > 0
                    ? number_format($publishedReviews, 0, ',', '.') . ' ulasan terpublikasi'
                    : 'Belum ada ulasan yang ditampilkan',
                'trend' => number_format($repeatCustomers, 0, ',', '.') . ' pelanggan pernah order lebih dari sekali',
                'tone' => $averageRating >= 4 ? 'success' : ($averageRating > 0 ? 'info' : 'neutral'),
            ],
        ];
    }

    protected function getStatusBreakdown(): array
    {
        $rows = Pesanan::query()
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $map = [
            Pesanan::STATUS_MENUNGGU => ['Menunggu Bayar', 'warning'],
            Pesanan::STATUS_DIPROSES => ['Diproses', 'info'],
            Pesanan::STATUS_SIAPKIRIM => ['Siap Kirim', 'primary'],
            Pesanan::STATUS_SELESAI => ['Selesai', 'success'],
            Pesanan::STATUS_DIBATALKAN => ['Dibatalkan', 'danger'],
        ];

        $total = max((int) $rows->sum(), 1);

        return collect($map)
            ->map(function (array $meta, string $status) use ($rows, $total): array {
                $count = (int) ($rows[$status] ?? 0);

                return [
                    'label' => $meta[0],
                    'count' => $count,
                    'percentage' => round(($count / $total) * 100, 1),
                    'tone' => $meta[1],
                ];
            })
            ->values()
            ->all();
    }

    protected function getOmzetTrend(): array
    {
        $raw = Pembayaran::query()
            ->select(DB::raw('DATE(waktu_dibayar) as tanggal'), DB::raw('SUM(jumlah_dibayar) as omzet'))
            ->where('status', Pembayaran::STATUS_LUNAS)
            ->whereNotNull('waktu_dibayar')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->pluck('omzet', 'tanggal');

        if ($raw->isEmpty()) {
            $startDate = Carbon::parse(self::BUSINESS_START_DATE)->startOfDay();
            $endDate = now()->startOfDay();

            return collect()
                ->range(0, $startDate->diffInDays($endDate))
                ->map(fn (int $i): array => [
                    'date' => $startDate->copy()->addDays($i)->translatedFormat('d M'),
                    'raw_date' => $startDate->copy()->addDays($i)->toDateString(),
                    'value' => 0,
                    'height' => 8,
                ])
                ->all();
        }

        $points = [];
        $max = max(1, (int) $raw->max());
        $firstTransactionDate = Carbon::parse((string) $raw->keys()->first());
        $businessStartDate = Carbon::parse(self::BUSINESS_START_DATE)->startOfDay();
        $startDate = $firstTransactionDate->lt($businessStartDate) ? $firstTransactionDate : $businessStartDate;
        $endDate = now()->startOfDay();

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $value = (int) ($raw[$date->format('Y-m-d')] ?? 0);

            $points[] = [
                'date' => $date->translatedFormat('d M'),
                'raw_date' => $date->toDateString(),
                'value' => $value,
                'height' => max(8, (int) round(($value / $max) * 100)),
            ];
        }

        return $points;
    }

    protected function getRecentOrders(): array
    {
        return Pesanan::query()
            ->with('pelanggan:id,nama_lengkap')
            ->latest()
            ->limit(5)
            ->get(['id', 'pelanggan_id', 'kode_pesanan', 'status', 'grand_total', 'created_at'])
            ->map(fn (Pesanan $order): array => [
                'kode' => $order->kode_pesanan,
                'pelanggan' => $order->pelanggan?->nama_lengkap ?? '-',
                'status' => $this->statusLabel($order->status),
                'status_tone' => $this->statusTone($order->status),
                'total' => $this->rupiah((int) $order->grand_total),
                'created_at' => optional($order->created_at)->timezone('Asia/Jakarta')->format('d M Y, H:i'),
            ])
            ->all();
    }

    protected function getTopCustomers(): array
    {
        return Pelanggan::query()
            ->select('pelanggan.id', 'pelanggan.nama_lengkap', 'pelanggan.no_hp')
            ->join('pesanan', 'pelanggan.id', '=', 'pesanan.pelanggan_id')
            ->groupBy('pelanggan.id', 'pelanggan.nama_lengkap', 'pelanggan.no_hp')
            ->selectRaw('COUNT(pesanan.id) as total_order')
            ->selectRaw("SUM(CASE WHEN pesanan.status = 'selesai' THEN pesanan.grand_total ELSE 0 END) as total_revenue")
            ->orderByDesc('total_order')
            ->limit(5)
            ->get()
            ->map(fn ($customer): array => [
                'nama' => $customer->nama_lengkap,
                'kontak' => $customer->no_hp,
                'total_order' => (int) $customer->total_order,
                'total_revenue' => $this->rupiah((int) $customer->total_revenue),
            ])
            ->all();
    }

    protected function statusLabel(string $status): string
    {
        return match ($status) {
            Pesanan::STATUS_MENUNGGU => 'Menunggu Bayar',
            Pesanan::STATUS_DIPROSES => 'Diproses',
            Pesanan::STATUS_SIAPKIRIM => 'Siap Kirim',
            Pesanan::STATUS_SELESAI => 'Selesai',
            Pesanan::STATUS_DIBATALKAN => 'Dibatalkan',
            default => $status,
        };
    }

    protected function statusTone(string $status): string
    {
        return match ($status) {
            Pesanan::STATUS_MENUNGGU => 'warning',
            Pesanan::STATUS_DIPROSES => 'info',
            Pesanan::STATUS_SIAPKIRIM => 'primary',
            Pesanan::STATUS_SELESAI => 'success',
            Pesanan::STATUS_DIBATALKAN => 'danger',
            default => 'neutral',
        };
    }

    protected function rupiah(int $value): string
    {
        return 'Rp ' . number_format($value, 0, ',', '.');
    }

    protected function comparisonLabel(int $current, int $previous, string $suffix): string
    {
        if ($current === 0 && $previous === 0) {
            return 'Belum ada perubahan ' . $suffix;
        }

        $difference = $current - $previous;

        if ($difference === 0) {
            return 'Stabil ' . $suffix;
        }

        $prefix = $difference > 0 ? 'Naik ' : 'Turun ';

        return $prefix . $this->rupiah(abs($difference)) . ' ' . $suffix;
    }
}
