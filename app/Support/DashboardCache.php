<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

class DashboardCache
{
    public const OVERVIEW_STATS = 'dashboard_overview_stats';

    public const OMZET_CHART = 'dashboard_chart_omzet_30_hari';

    public const STATUS_PESANAN_CHART = 'dashboard_chart_status_pesanan';

    public const RECENT_ORDER_IDS = 'dashboard_recent_order_ids_5';

    public const TOP_PELANGGAN = 'kpi_top_pelanggan';

    public const DASHBOARD_PAYLOAD = 'dashboard_payload_v2';

    public static function keys(): array
    {
        return [
            self::OVERVIEW_STATS,
            self::OMZET_CHART,
            self::STATUS_PESANAN_CHART,
            self::RECENT_ORDER_IDS,
            self::TOP_PELANGGAN,
            self::DASHBOARD_PAYLOAD,
        ];
    }

    public static function forgetAll(): void
    {
        foreach (self::keys() as $key) {
            Cache::forget($key);
        }
    }
}
