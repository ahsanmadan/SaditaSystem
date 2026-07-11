<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>{{ $focusModule['title'] }} - Sadita Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&family=Playfair+Display:wght@600;700&display=swap"
        rel="stylesheet">
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
</head>

<body class="admin-shell min-h-screen bg-[#f2ece7] text-[#241818] antialiased">
    @php
        $stats = $dashboardPayload['stats'] ?? [];
        $recentOrders = $dashboardPayload['recent_orders'] ?? [];
        $topCustomers = $dashboardPayload['top_customers'] ?? [];
        $omzetTrend = $dashboardPayload['omzet_trend'] ?? [];
        $maxHeight = !empty($omzetTrend) ? max(array_map(fn($point) => $point['height'], $omzetTrend)) : 0;
        $roleLabel = $user?->isOwner() ? 'Owner' : ($user?->isAdmin() ? 'Admin' : 'Staff');
        $firstName = str($user?->name ?? 'Tim Sadita')
            ->before(' ')
            ->toString();
        $focusSummary = $moduleSummaries[$focus] ?? null;
        $primaryAction = $quickActions[0] ?? null;
        $secondaryAction = $quickActions[1] ?? null;
        $sidebarAction = $primaryAction ?? [
            'label' => 'Buat Pesanan',
            'href' => route('admin.index', ['focus' => 'pesanan', 'mode' => 'create']),
        ];
        $isDashboardView = $focus === 'dashboard';
        $isManageMode = !$isDashboardView && $mode === 'manage';
        $manageUrl = $focus === 'dashboard' ? url('/admin-lite') : url('/admin-lite/' . $focus . '/manage');
        $heroTitle = $focus === 'dashboard' ? 'Dashboard operasional' : $focusModule['title'];
        $heroDescription = $isDashboardView
            ? 'Pantau order masuk, pembayaran tertahan, dan tindak lanjut tim hari ini.'
            : ($isManageMode
                ? 'Kelola antrean order masuk, pantau status pengiriman, dan lakukan pembaruan transaksi tim harian.'
                : $focusModule['subtitle']);
        $dashboardStats = collect($stats)
            ->reject(fn ($stat) => ($stat['label'] ?? null) === 'Menunggu Pembayaran')
            ->take(4)
            ->values()
            ->all();
        $dashboardHighlights = [
            [
                'label' => 'Pesanan menunggu',
                'value' => $operationalQueues[0]['value'] ?? 0,
                'hint' => $operationalQueues[0]['hint'] ?? 'Perlu tindak lanjut',
                'href' => route('admin.index', ['focus' => 'pesanan', 'mode' => 'manage']),
            ],
            [
                'label' => 'Pembayaran pending',
                'value' => $operationalQueues[1]['value'] ?? 0,
                'hint' => $operationalQueues[1]['hint'] ?? 'Butuh verifikasi admin',
                'href' => route('admin.index', ['focus' => 'pembayaran', 'mode' => 'manage']),
            ],
            [
                'label' => 'Promo aktif',
                'value' => $operationalQueues[2]['value'] ?? 0,
                'hint' => $operationalQueues[2]['hint'] ?? 'Sedang dipakai kampanye',
                'href' => route('admin.index', ['focus' => 'promo', 'mode' => 'manage']),
            ],
        ];
        $overviewLabel = match ($focus) {
            'produk' => 'Inventaris layanan',
            'pesanan' => 'Kontrol pesanan',
            'pelanggan' => 'Relasi pelanggan',
            'pembayaran' => 'Verifikasi transaksi',
            default => $focusModule['eyebrow'],
        };
        $workspaceDate = now()->translatedFormat('d M Y');
        $notificationFeed = $activityFeed ?? [];
        $notificationCount = count($notificationFeed);
        $notificationUnreadCount = (int) ($notificationUnreadCount ?? 0);
        $notificationScopeLabel = $notificationScopeLabel ?? 'Aktivitas admin dan pesanan baru';
        $latestNotificationStamp = $notificationFeed[0]['stamp'] ?? null;
        $notificationFilters = auth()->user()?->isStaff()
            ? [
                ['key' => 'all', 'label' => 'Semua'],
                ['key' => 'order', 'label' => 'Pesanan'],
            ]
            : [
                ['key' => 'all', 'label' => 'Semua'],
                ['key' => 'order', 'label' => 'Pesanan'],
                ['key' => 'system', 'label' => 'Perubahan'],
            ];
        $dashboardTrendTotal = collect($omzetTrend)->sum(fn($point) => (int) ($point['value'] ?? 0));
        $hasOmzetData = $dashboardTrendTotal > 0;
        $omzetSourcePoints = collect(
            !empty($omzetTrend)
                ? $omzetTrend
                : collect(range(6, 0))->map(
                    fn($offset) => [
                        'date' => now()->subDays($offset)->translatedFormat('d M'),
                        'value' => 0,
                    ],
                ),
        )->values();
        $omzetPointCount = $omzetSourcePoints->count();
        $omzetChartPoints = $omzetSourcePoints
            ->map(
                fn($point, $index) => [
                    'label' => $point['date'] ?? now()->translatedFormat('d M'),
                    'value' => (int) ($point['value'] ?? 0),
                    'date' => !empty($point['raw_date'])
                        ? \Illuminate\Support\Carbon::parse($point['raw_date'])->toDateString()
                        : (!empty($point['date_iso'])
                            ? \Illuminate\Support\Carbon::parse($point['date_iso'])->toDateString()
                            : now()->subDays(max($omzetPointCount - $index - 1, 0))->toDateString()),
                ],
            )
            ->values();
        $defaultOmzetRange = '30d';
        $lastThirtyDayOmzet = $omzetChartPoints
            ->filter(fn ($point) => !empty($point['raw_date']) && \Illuminate\Support\Carbon::parse($point['raw_date'])->gte(now()->subDays(29)))
            ->sum('value');
        $yearToDateOmzet = $omzetChartPoints
            ->filter(fn ($point) => !empty($point['raw_date']) && \Illuminate\Support\Carbon::parse($point['raw_date'])->year === now()->year)
            ->sum('value');

        if ($lastThirtyDayOmzet <= 0 && $yearToDateOmzet > 0) {
            $defaultOmzetRange = 'ytd';
        } elseif ($lastThirtyDayOmzet <= 0 && $yearToDateOmzet <= 0 && $omzetChartPoints->sum('value') > 0) {
            $defaultOmzetRange = 'all';
        }
        $highlightSearch = function ($text) use ($searchTerm) {
            $value = (string) ($text ?? '');
            $escaped = e($value);

            if ($searchTerm === '') {
                return $escaped;
            }

            return preg_replace(
                '/' . preg_quote($searchTerm, '/') . '/iu',
                '<mark class="rounded-sm bg-amber-200 px-0.5 text-amber-950 shadow-[0_0_0_1px_rgba(251,191,36,0.2)]">$0</mark>',
                $escaped,
            ) ?? $escaped;
        };
        $statusToneMeta = function (string $status): array {
            $value = strtolower($status);

            return match (true) {
                str_contains($value, 'selesai'),
                str_contains($value, 'lunas'),
                str_contains($value, 'aktif'),
                str_contains($value, 'tampil')
                    => [
                    'badge' => 'bg-[#E7F0E4] text-[#4A7A3D] ring-1 ring-[#cfddc8]',
                    'bar' => '#5A8A4A',
                ],
                str_contains($value, 'diproses'), str_contains($value, 'info') => [
                    'badge' => 'bg-[#FDF1DA] text-[#B8752E] ring-1 ring-[#efd9b7]',
                    'bar' => '#C98A2E',
                ],
                str_contains($value, 'siap') => [
                    'badge' => 'bg-[#E4EEF7] text-[#3A6EA5] ring-1 ring-[#cad9ea]',
                    'bar' => '#3A6EA5',
                ],
                str_contains($value, 'menunggu'), str_contains($value, 'pending') => [
                    'badge' => 'bg-[#FDE8EC] text-[#B8385C] ring-1 ring-[#f3ccd5]',
                    'bar' => '#B8385C',
                ],
                str_contains($value, 'nonaktif'),
                str_contains($value, 'ditolak'),
                str_contains($value, 'batal'),
                str_contains($value, 'sembunyi')
                    => [
                    'badge' => 'bg-[#F1E4E4] text-[#8C4A47] ring-1 ring-[#dec7c7]',
                    'bar' => '#8C4A47',
                ],
                default => [
                    'badge' => 'bg-[#f7ece8] text-[#7A1F2B] ring-1 ring-[#ead1c9]',
                    'bar' => '#7A1F2B',
                ],
            };
        };
        $statusPill = fn(string $status): string => $statusToneMeta($status)['badge'];
        $statTone = function (string $tone): string {
            return match ($tone) {
                'success' => 'bg-emerald-50 text-emerald-700',
                'warning' => 'bg-amber-50 text-amber-700',
                'danger' => 'bg-rose-50 text-rose-700',
                'info', 'primary' => 'bg-[#f7ece8] text-[#7A1F2B]',
                default => 'bg-zinc-100 text-zinc-600',
            };
        };
        $metricIcon = function (string $label): string {
            $value = Str::lower($label);

            if (
                str_contains($value, 'omzet') ||
                str_contains($value, 'pendapatan') ||
                str_contains($value, 'revenue')
            ) {
                return 'money';
            }

            if (
                str_contains($value, 'pesanan') ||
                str_contains($value, 'order') ||
                str_contains($value, 'pembayaran') ||
                str_contains($value, 'antrean') ||
                str_contains($value, 'antrian')
            ) {
                return 'package';
            }

            if (str_contains($value, 'produk') || str_contains($value, 'kategori') || str_contains($value, 'promo')) {
                return 'tag';
            }

            if (str_contains($value, 'rating') || str_contains($value, 'bintang')) {
                return 'star';
            }

            if (str_contains($value, 'pelanggan') || str_contains($value, 'user') || str_contains($value, 'ulasan')) {
                return 'users';
            }

            return 'package';
        };
    @endphp

    <a href="#admin-main"
        class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[90] focus:rounded-full focus:bg-[#7A1F2B] focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:text-white">
        Langsung ke konten utama
    </a>

    <div class="min-h-screen px-0 pb-12 lg:px-6 lg:py-6 lg:pb-12">
        <div class="mx-auto max-w-[1760px] lg:grid lg:grid-cols-[290px_minmax(0,1fr)] lg:gap-6">
            <aside id="admin-sidebar"
                class="fixed inset-y-0 left-0 z-[80] w-[min(84vw,290px)] -translate-x-full overflow-y-auto bg-[#35191d] px-4 pb-[max(1.5rem,env(safe-area-inset-bottom))] pt-[max(1rem,env(safe-area-inset-top))] text-white shadow-[0_24px_70px_rgba(44,20,24,0.32)] transition-transform duration-300 ease-out lg:sticky lg:top-6 lg:block lg:h-[calc(100dvh-3rem)] lg:w-auto lg:translate-x-0 lg:overflow-hidden lg:rounded-[28px] lg:border lg:border-[#ffffff10] lg:bg-[#35191d] lg:px-5 lg:py-5 lg:shadow-[0_20px_48px_rgba(62,27,35,0.18)]"
                aria-label="Navigasi panel admin Sadita">
                <div class="flex h-full flex-col">
                    <div class="relative flex items-center justify-center pb-5 pt-1">
                        <div class="min-w-0 text-center">
                            <p class="text-[2.12rem] font-semibold italic tracking-[-0.045em] text-white"
                                style="font-family:'Playfair Display',serif;">SaditaSystem</p>
                        </div>
                        <button type="button" id="admin-close"
                            class="absolute right-0 top-1/2 inline-flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full border border-white/10 bg-white/6 text-[#f5e8cf] transition hover:bg-white/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#E8C87A] lg:hidden"
                            aria-label="Tutup menu admin">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                <path d="M5 5L15 15M15 5L5 15" stroke="currentColor" stroke-width="1.8"
                                    stroke-linecap="round" />
                            </svg>
                        </button>
                    </div>

                    <nav class="flex-1 space-y-5 overflow-y-auto pr-1">
                        @foreach ($menu as $group)
                            <section aria-labelledby="group-{{ \Illuminate\Support\Str::slug($group['group']) }}">
                                <h2 id="group-{{ \Illuminate\Support\Str::slug($group['group']) }}"
                                    class="px-2 text-[12px] font-semibold tracking-[0.04em] text-[#d1b58c]">
                                    {{ $group['group'] }}
                                </h2>
                                <div class="mt-2.5 space-y-1.5">
                                    @foreach ($group['items'] as $item)
                                        @php $isActive = $focus === $item['key']; @endphp
                                        <a href="{{ $item['href'] }}"
                                            class="{{ $isActive ? 'bg-[#5b232d] text-white ring-1 ring-[#ffffff12]' : 'text-[#f0e4d0] hover:bg-white/6 hover:text-white' }} group flex items-center gap-3 rounded-[16px] px-3 py-3 text-sm font-medium transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#E8C87A]">
                                            <span
                                                class="{{ $isActive ? 'text-[#F5E6BF]' : 'text-[#d6b98a] group-hover:text-white' }} flex h-5 w-5 shrink-0 items-center justify-center">
                                                @switch($item['icon'])
                                                    @case('dashboard')
                                                        <svg class="h-4.5 w-4.5" viewBox="0 0 20 20" fill="none"
                                                            aria-hidden="true">
                                                            <path
                                                                d="M4 4H9V9H4V4ZM11 4H16V12H11V4ZM4 11H9V16H4V11ZM11 14H16V16H11V14Z"
                                                                fill="currentColor" />
                                                        </svg>
                                                    @break

                                                    @case('tag')
                                                        <svg class="h-4.5 w-4.5" viewBox="0 0 20 20" fill="none"
                                                            aria-hidden="true">
                                                            <path d="M4.5 9.5V4.5H9.5L15.5 10.5L10.5 15.5L4.5 9.5Z"
                                                                stroke="currentColor" stroke-width="1.6"
                                                                stroke-linejoin="round" />
                                                            <circle cx="7.5" cy="7.5" r="1" fill="currentColor" />
                                                        </svg>
                                                    @break

                                                    @case('bag')
                                                        <svg class="h-4.5 w-4.5" viewBox="0 0 20 20" fill="none"
                                                            aria-hidden="true">
                                                            <path
                                                                d="M6.5 7V6.25C6.5 4.45 7.95 3 9.75 3C11.55 3 13 4.45 13 6.25V7M5 7H14.5L13.7 16H5.8L5 7Z"
                                                                stroke="currentColor" stroke-width="1.6" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                    @break

                                                    @case('ticket')
                                                        <svg class="h-4.5 w-4.5" viewBox="0 0 20 20" fill="none"
                                                            aria-hidden="true">
                                                            <path
                                                                d="M4 6.5A1.5 1.5 0 0 1 5.5 5H14.5A1.5 1.5 0 0 1 16 6.5V8A1.5 1.5 0 0 0 16 12V13.5A1.5 1.5 0 0 1 14.5 15H5.5A1.5 1.5 0 0 1 4 13.5V12A1.5 1.5 0 0 0 4 8V6.5Z"
                                                                stroke="currentColor" stroke-width="1.6" />
                                                            <path d="M10 6.5V13.5" stroke="currentColor" stroke-width="1.6"
                                                                stroke-dasharray="1.5 1.5" />
                                                        </svg>
                                                    @break

                                                    @case('users')
                                                        <svg class="h-4.5 w-4.5" viewBox="0 0 20 20" fill="none"
                                                            aria-hidden="true">
                                                            <path
                                                                d="M7 9.25A2.75 2.75 0 1 0 7 3.75A2.75 2.75 0 0 0 7 9.25ZM13.5 10.25A2.25 2.25 0 1 0 13.5 5.75A2.25 2.25 0 0 0 13.5 10.25ZM3.5 15.75C3.5 13.82 5.07 12.25 7 12.25C8.93 12.25 10.5 13.82 10.5 15.75M11.5 15.75C11.5 14.3 12.68 13.12 14.13 13.12C15.58 13.12 16.76 14.3 16.76 15.75"
                                                                stroke="currentColor" stroke-width="1.6"
                                                                stroke-linecap="round" />
                                                        </svg>
                                                    @break

                                                    @case('star')
                                                        <svg class="h-4.5 w-4.5" viewBox="0 0 20 20" fill="none"
                                                            aria-hidden="true">
                                                            <path
                                                                d="M10 3.5L11.85 7.25L16 7.86L13 10.79L13.7 14.93L10 12.98L6.3 14.93L7 10.79L4 7.86L8.15 7.25L10 3.5Z"
                                                                stroke="currentColor" stroke-width="1.5"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                    @break

                                                    @case('clipboard')
                                                        <svg class="h-4.5 w-4.5" viewBox="0 0 20 20" fill="none"
                                                            aria-hidden="true">
                                                            <path
                                                                d="M7 4.5H13M7.75 3H12.25C12.66 3 13 3.34 13 3.75V5H15C15.83 5 16.5 5.67 16.5 6.5V15C16.5 15.83 15.83 16.5 15 16.5H5C4.17 16.5 3.5 15.83 3.5 15V6.5C3.5 5.67 4.17 5 5 5H7V3.75C7 3.34 7.34 3 7.75 3Z"
                                                                stroke="currentColor" stroke-width="1.6"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                    @break

                                                    @case('banknotes')
                                                        <svg class="h-4.5 w-4.5" viewBox="0 0 20 20" fill="none"
                                                            aria-hidden="true">
                                                            <rect x="3.5" y="5.5" width="13" height="9" rx="1.5"
                                                                stroke="currentColor" stroke-width="1.6" />
                                                            <circle cx="10" cy="10" r="1.7"
                                                                stroke="currentColor" stroke-width="1.4" />
                                                            <path d="M5.5 8.5H5.52M14.48 11.5H14.5" stroke="currentColor"
                                                                stroke-width="2" stroke-linecap="round" />
                                                        </svg>
                                                    @break

                                                    @case('shield')
                                                        <svg class="h-4.5 w-4.5" viewBox="0 0 20 20" fill="none"
                                                            aria-hidden="true">
                                                            <path
                                                                d="M10 3L15 5V9.5C15 12.67 12.92 15.54 10 16.5C7.08 15.54 5 12.67 5 9.5V5L10 3Z"
                                                                stroke="currentColor" stroke-width="1.6"
                                                                stroke-linejoin="round" />
                                                            <path d="M8.2 10L9.35 11.15L11.8 8.7" stroke="currentColor"
                                                                stroke-width="1.6" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                    @break
                                                @endswitch
                                            </span>
                                            <span class="min-w-0 flex-1 truncate">{{ $item['label'] }}</span>
                                            @if ($isActive)
                                                <span class="h-2 w-2 rounded-full bg-[#e8c87a]"></span>
                                            @endif
                                        </a>
                                    @endforeach
                                </div>
                            </section>
                        @endforeach
                    </nav>

                    <div class="mt-6 border-t border-white/10 pt-5">
                        <p class="truncate text-[15px] font-semibold leading-tight text-white">
                            {{ $user?->name ?? 'Admin Sadita' }}</p>
                        <div class="mt-2 flex items-center gap-2 text-xs text-[#d7c2b0]">
                            <span class="inline-block h-1.5 w-1.5 rounded-full bg-[#e8c87a]"></span>
                            <span class="font-medium">{{ $roleLabel }}</span>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="mt-4">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/6 px-3.5 py-2.5 text-xs font-semibold text-[#f2e7dc] transition hover:bg-white/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#e8c87a]">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                    <path
                                        d="M8 4.5H5.75C5.06 4.5 4.5 5.06 4.5 5.75V14.25C4.5 14.94 5.06 15.5 5.75 15.5H8"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                    <path d="M11.5 6.5L15 10M15 10L11.5 13.5M15 10H7.5" stroke="currentColor"
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            <div id="admin-backdrop" class="fixed inset-0 z-[70] hidden bg-[#221215]/45 backdrop-blur-[2px] lg:hidden"
                aria-hidden="true">
            </div>

            <div class="min-w-0">
                <header class="sticky top-0 z-50 lg:px-0"
                    x-data="{
                        showMobileSearch: false,
                        showNotifications: false,
                        activeNotificationFilter: 'all',
                        unreadNotifications: {{ $notificationUnreadCount }},
                        latestNotificationStamp: @js($latestNotificationStamp),
                        notificationStorageKey: 'sadita-notifications-seen-{{ auth()->id() ?? 'guest' }}',
                        init() {
                            const seenStamp = window.localStorage.getItem(this.notificationStorageKey);

                            if (
                                seenStamp &&
                                this.latestNotificationStamp &&
                                new Date(seenStamp).getTime() >= new Date(this.latestNotificationStamp).getTime()
                            ) {
                                this.unreadNotifications = 0;
                            }
                        },
                        openNotifications() {
                            this.showNotifications = !this.showNotifications;
                            this.showMobileSearch = false;

                            if (this.showNotifications) {
                                this.markNotificationsSeen();
                            }
                        },
                        markNotificationsSeen() {
                            if (this.unreadNotifications < 1) {
                                return;
                            }

                            this.unreadNotifications = 0;
                            window.localStorage.setItem(
                                this.notificationStorageKey,
                                this.latestNotificationStamp ?? new Date().toISOString()
                            );

                            fetch('{{ route('admin.notifications.seen') }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json',
                                },
                            }).catch(() => {});
                        }
                    }"
                    @keydown.escape.window="showMobileSearch = false; showNotifications = false"
                    x-effect="if (showMobileSearch) { $nextTick(() => $refs.mobileSearchInput.focus()) }">
                    <div class="mx-4 mt-3 overflow-hidden rounded-[24px] border border-slate-100 bg-white/95 px-4 py-3 shadow-[0_12px_28px_rgba(56,35,27,0.06)] backdrop-blur-md transition-all duration-300 ease-out sm:mx-6 lg:hidden"
                        :class="showMobileSearch ? 'shadow-[0_16px_34px_rgba(56,35,27,0.10)]' : 'shadow-[0_12px_28px_rgba(56,35,27,0.06)]'">
                        <div class="flex items-center justify-between gap-3">
                            <x-ui.button type="button" id="admin-open" variant="outline" size="icon"
                                class="h-9 w-9 shrink-0 rounded-xl border-slate-200 bg-white text-[#56353a] shadow-sm hover:bg-slate-50 focus-visible:ring-[#7A1F2B]"
                                aria-controls="admin-sidebar" aria-expanded="false" aria-label="Buka menu admin">
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                    <path d="M4 6H16M4 10H16M4 14H12" stroke="currentColor" stroke-width="1.8"
                                        stroke-linecap="round" />
                                </svg>
                            </x-ui.button>

                            <div class="flex-1 px-2 text-center">
                                <p class="truncate text-[1.45rem] font-semibold italic tracking-[-0.045em] text-[#7A1F2B]"
                                    style="font-family:'Playfair Display',serif;">Sadita</p>
                            </div>

                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.index', ['focus' => 'pesanan', 'mode' => 'manage']) }}"
                                    class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-700 shadow-sm transition hover:bg-slate-50"
                                    aria-label="Pesanan">
                                    <svg class="h-3.5 w-3.5 text-[#7A1F2B]" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                        <line x1="3" y1="6" x2="21" y2="6"></line>
                                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                                    </svg>
                                </a>

                                @if ($isDashboardView)
                                    <x-ui.button type="button"
                                        @click.stop.prevent="openNotifications()"
                                        x-bind:aria-expanded="showNotifications.toString()" variant="outline"
                                        size="icon"
                                        class="relative h-9 w-9 shrink-0 rounded-xl border-slate-200 bg-white text-slate-700 shadow-sm hover:bg-slate-50 focus-visible:ring-[#7A1F2B]"
                                        aria-label="Buka notifikasi">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" aria-hidden="true">
                                            <path d="M15 17h5l-1.4-1.4A2 2 0 0 1 18 14.2V11a6 6 0 1 0-12 0v3.2a2 2 0 0 1-.6 1.4L4 17h5">
                                            </path>
                                            <path d="M9 17a3 3 0 0 0 6 0"></path>
                                        </svg>
                                        <span x-cloak x-show="unreadNotifications > 0"
                                            class="absolute right-1.5 top-1.5 inline-flex h-2 w-2 rounded-full bg-[#b8385c] ring-2 ring-white"></span>
                                    </x-ui.button>
                                @endif

                                @if ($isDashboardView)
                                    <x-ui.button type="button"
                                        @click.stop.prevent="showMobileSearch = !showMobileSearch; showNotifications = false"
                                        x-bind:aria-expanded="showMobileSearch.toString()" variant="outline"
                                        size="icon"
                                        class="h-9 w-9 shrink-0 rounded-xl border-slate-200 bg-white text-slate-700 shadow-sm hover:bg-slate-50 focus-visible:ring-[#7A1F2B]"
                                        aria-label="Buka pencarian">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" aria-hidden="true">
                                            <circle cx="11" cy="11" r="8"></circle>
                                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                        </svg>
                                    </x-ui.button>

                                @endif
                            </div>
                        </div>

                        @if ($isDashboardView)
                            <div x-cloak x-show="showMobileSearch" x-collapse.duration.280ms class="overflow-hidden">
                                <div class="mt-3 border-t border-[#efe6de] pt-3 transition-all duration-250 ease-out"
                                    x-transition:enter="transform-gpu transition ease-out duration-250"
                                    x-transition:enter-start="-translate-y-2 opacity-0"
                                    x-transition:enter-end="translate-y-0 opacity-100"
                                    x-transition:leave="transform-gpu transition ease-in duration-180"
                                    x-transition:leave-start="translate-y-0 opacity-100"
                                    x-transition:leave-end="-translate-y-1 opacity-0">
                                    <form method="GET" action="{{ route('admin.search') }}"
                                        class="flex items-center">
                                        <x-ui.input-group
                                            class="min-w-0 flex-1 rounded-[18px] border-[#ddd3cb] bg-white/96 shadow-[0_8px_18px_rgba(74,35,41,0.05)] transition-all duration-200 ease-out focus-within:border-[#d8c1b5] focus-within:shadow-[0_12px_24px_rgba(74,35,41,0.08)]">
                                            <x-ui.input-group-input x-ref="mobileSearchInput" type="search"
                                                name="q" value="{{ request('q') }}"
                                                placeholder="Cari kategori, produk, pelanggan, atau kode pesanan"
                                                autocomplete="off" autocorrect="off" autocapitalize="none"
                                                spellcheck="false"
                                                class="h-9 min-w-0 px-2 text-[11.5px] font-medium text-slate-900 placeholder:text-[#9f93a2]" />
                                            <x-ui.input-group-addon
                                                class="pr-3 text-[#8e8290] transition group-focus-within:text-[#7A1F2B]">
                                                <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="none"
                                                    aria-hidden="true">
                                                    <path
                                                        d="M13.75 13.75L16.5 16.5M15.25 9.25A6 6 0 1 1 3.25 9.25A6 6 0 0 1 15.25 9.25Z"
                                                        stroke="currentColor" stroke-width="1.6"
                                                        stroke-linecap="round" />
                                                </svg>
                                            </x-ui.input-group-addon>
                                        </x-ui.input-group>
                                        <button type="submit" class="sr-only" tabindex="-1">Cari</button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="hidden px-4 pt-[max(0.75rem,env(safe-area-inset-top))] sm:px-6 lg:block lg:px-0">
                        <div
                            class="flex items-center gap-3 rounded-[24px] border border-[#e4dbd3] bg-[rgba(255,252,249,0.92)] px-4 py-3 shadow-[0_14px_30px_rgba(56,35,27,0.06)] backdrop-blur-md">
                            @if ($isDashboardView)
                                <div class="min-w-0 flex-1 lg:max-w-lg">
                                    <x-admin.search-form :action="route('admin.search')" input-id="module-search" :value="request('q')" />
                                </div>
                            @else
                                <div class="min-w-0 flex-1"></div>
                            @endif
                            <div class="ml-auto flex items-center gap-2 sm:gap-3">
                                <div
                                    class="hidden rounded-full border border-[#e6ddd5] bg-[#fbf8f5] px-4 py-2 text-xs font-semibold text-[#6f5c55] xl:inline-flex">
                                    {{ $workspaceDate }}
                                </div>
                                @if ($isDashboardView)
                                    <x-ui.button type="button" @click.stop.prevent="openNotifications()"
                                        x-bind:aria-expanded="showNotifications.toString()" variant="outline"
                                        size="icon"
                                        class="relative h-11 w-11 rounded-2xl border-[#e6ddd5] bg-white text-[#56353a] shadow-sm hover:border-[#d3c3b7] hover:bg-[#fbf7f3] focus-visible:ring-[#7A1F2B]"
                                        aria-label="Buka notifikasi">
                                        <svg class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" aria-hidden="true">
                                            <path d="M15 17h5l-1.4-1.4A2 2 0 0 1 18 14.2V11a6 6 0 1 0-12 0v3.2a2 2 0 0 1-.6 1.4L4 17h5">
                                            </path>
                                            <path d="M9 17a3 3 0 0 0 6 0"></path>
                                        </svg>
                                        <span x-cloak x-show="unreadNotifications > 0"
                                            class="absolute right-2 top-2 inline-flex min-w-[18px] items-center justify-center rounded-full bg-[#b8385c] px-1.5 py-[2px] text-[10px] font-semibold text-white"
                                            x-text="unreadNotifications > 9 ? '9+' : unreadNotifications"></span>
                                    </x-ui.button>
                                @endif
                                <a href="{{ route('admin.index', ['focus' => 'pesanan', 'mode' => 'manage']) }}"
                                    class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-[#e6ddd5] bg-white text-[#56353a] shadow-sm transition hover:border-[#d3c3b7] hover:bg-[#fbf7f3] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#7A1F2B] sm:w-auto sm:gap-2 sm:px-4">
                                    <svg class="h-4.5 w-4.5" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                        <path
                                            d="M7 4.5H13M7.75 3H12.25C12.66 3 13 3.34 13 3.75V5H15C15.83 5 16.5 5.67 16.5 6.5V15C16.5 15.83 15.83 16.5 15 16.5H5C4.17 16.5 3.5 15.83 3.5 15V6.5C3.5 5.67 4.17 5 5 5H7V3.75C7 3.34 7.34 3 7.75 3Z"
                                            stroke="currentColor" stroke-width="1.6" stroke-linejoin="round" />
                                    </svg>
                                    <span class="hidden text-sm font-semibold sm:inline">Pesanan</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    @if ($isDashboardView)
                        <div x-cloak x-show="showNotifications"
                            x-transition:enter="transform-gpu transition ease-out duration-220"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            x-transition:leave="transform-gpu transition ease-in duration-180"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="fixed inset-0 z-[85] bg-[#221215]/38 backdrop-blur-[2px]"
                            @click="showNotifications = false"></div>

                        <aside x-cloak x-show="showNotifications"
                            x-transition:enter="transform-gpu transition ease-out duration-260"
                            x-transition:enter-start="translate-x-full opacity-0"
                            x-transition:enter-end="translate-x-0 opacity-100"
                            x-transition:leave="transform-gpu transition ease-in duration-200"
                            x-transition:leave-start="translate-x-0 opacity-100"
                            x-transition:leave-end="translate-x-full opacity-0"
                            class="fixed right-0 top-0 z-[90] flex h-[100dvh] w-full max-w-[360px] flex-col border-l border-[#e6ddd5] bg-white shadow-[-24px_0_52px_rgba(56,35,27,0.14)]">
                            <div
                                class="border-b border-[#efe7e0] px-4 pb-4 pt-[max(1rem,env(safe-area-inset-top))] sm:px-5">
                                <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-base font-semibold text-[#2c1d1d]">Notifikasi</p>
                                    <p class="mt-1 text-xs text-[#8b746d]">{{ $notificationScopeLabel }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span x-cloak x-show="unreadNotifications > 0"
                                        class="rounded-full bg-[#f9efe4] px-2.5 py-1 text-[11px] font-semibold text-[#7A1F2B]"
                                        x-text="`${unreadNotifications} baru`"></span>
                                    <button type="button" @click="showNotifications = false"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-[#e6ddd5] bg-[#fbf8f5] text-[#56353a] transition hover:bg-white"
                                        aria-label="Tutup notifikasi">
                                        <svg class="h-4.5 w-4.5" viewBox="0 0 20 20" fill="none"
                                            aria-hidden="true">
                                            <path d="M5 5L15 15M15 5L5 15" stroke="currentColor" stroke-width="1.8"
                                                stroke-linecap="round" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                                <div class="mt-4 flex flex-wrap gap-2">
                                    @foreach ($notificationFilters as $filter)
                                        <button type="button" @click="activeNotificationFilter = '{{ $filter['key'] }}'"
                                            x-bind:class="activeNotificationFilter === '{{ $filter['key'] }}' ? 'border-[#7A1F2B] bg-[#7A1F2B] text-white' : 'border-[#e6ddd5] bg-white text-[#6a5854] hover:border-[#d7c8bc] hover:text-[#2c1d1d]'"
                                            class="inline-flex items-center rounded-full border px-3 py-1.5 text-[11px] font-semibold transition">
                                            {{ $filter['label'] }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <div class="flex-1 overflow-y-auto px-4 py-4 sm:px-5">
                                <div class="divide-y divide-[#efe7e0]">
                                    @forelse ($notificationFeed as $notification)
                                        <article
                                            x-show="activeNotificationFilter === 'all' || activeNotificationFilter === '{{ $notification['category'] }}'"
                                            class="py-3 first:pt-0 last:pb-0">
                                            <div class="flex items-start">
                                                <div class="min-w-0 flex-1">
                                                    <div class="flex items-start justify-between gap-3">
                                                        <p class="text-[13px] font-semibold leading-5 text-[#2c1d1d]">
                                                            {{ $notification['title'] }}</p>
                                                        <span class="shrink-0 text-[10px] text-[#9a847d]">
                                                            {{ $notification['time'] }}</span>
                                                    </div>
                                                    <p class="mt-1 text-[12px] leading-5 text-[#6a5854]">
                                                        {{ $notification['subject'] }}</p>
                                                    <div
                                                        class="mt-2 flex flex-wrap items-center gap-x-2 gap-y-1 text-[10px] text-[#9a847d]">
                                                        <span>{{ $notification['user'] }}</span>
                                                        <span class="text-[#d1c1b9]">•</span>
                                                        <span>{{ $notification['category'] === 'order' ? 'Pesanan' : 'Perubahan' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </article>
                                    @empty
                                        <div
                                            class="py-8 text-sm text-[#8b746d]">
                                            Belum ada notifikasi untuk role ini.
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </aside>
                    @endif
                </header>

                <main id="admin-main" class="admin-module-stage px-4 py-4 sm:px-6 sm:py-5 lg:px-0 lg:py-6">
                    @if (session('danger'))
                        <section aria-live="polite"
                            class="mb-4 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm font-medium text-rose-800">
                            {{ session('danger') }}
                        </section>
                    @endif

                    @if ($errors->any())
                        <section id="admin-error-summary" role="alert" aria-live="polite"
                            class="mb-4 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-800">
                            <p class="font-semibold">Masih ada field yang perlu diperbaiki.</p>
                            <ul class="mt-2 list-disc space-y-1 pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <p class="mt-3 text-xs text-rose-700">Periksa field yang ditandai, lalu simpan ulang.</p>
                        </section>
                    @endif

                    <section class="pb-1">
                        <x-ui.card
                            class="rounded-[28px] border-[#e6ddd5] p-5 shadow-[0_18px_42px_rgba(56,35,27,0.06)] sm:p-7 xl:p-8">
                            <div
                                class="relative {{ $isManageMode ? 'flex flex-col gap-5 xl:flex-row xl:items-end xl:justify-between' : ($isDashboardView ? 'grid gap-6 xl:grid-cols-[minmax(0,1fr)_360px] xl:items-stretch' : 'grid gap-6 xl:grid-cols-[minmax(0,1.5fr)_360px] xl:items-start') }}">
                                <div class="{{ $isDashboardView ? 'min-w-0 xl:flex xl:h-full xl:flex-col' : 'min-w-0' }}">
                                    <p class="text-xs font-semibold tracking-[0.04em] text-[#7b655e] sm:text-sm">
                                        {{ $overviewLabel }}
                                    </p>
                                    <h1
                                        class="mt-3 max-w-[11ch] text-[1.95rem] font-semibold leading-[0.95] tracking-[-0.045em] text-[#2c1d1d] sm:max-w-3xl sm:text-[3rem] [text-wrap:balance]">
                                        {{ $heroTitle }}
                                    </h1>
                                    @unless ($isDashboardView)
                                        <p
                                            class="mt-4 max-w-2xl text-[14px] leading-6 text-[#6a5854] sm:text-[15px] sm:leading-7">
                                            {{ $heroDescription }}
                                        </p>
                                    @endunless

                                    @if (!$isDashboardView && !$isManageMode)
                                        <div class="mt-5 flex flex-wrap gap-3">
                                            @if ($primaryAction)
                                                <a href="{{ $primaryAction['href'] }}"
                                                    class="inline-flex items-center rounded-full bg-[#7A1F2B] px-4 py-2.5 text-sm font-semibold text-white shadow-[0_12px_28px_rgba(94,23,33,0.18)] transition hover:bg-[#651925]">
                                                    {{ $primaryAction['label'] }}
                                                </a>
                                            @endif
                                            @if ($secondaryAction)
                                                <a href="{{ $secondaryAction['href'] }}"
                                                    class="inline-flex items-center rounded-full border border-[#e6ddd5] bg-[#fbf8f5] px-4 py-2.5 text-sm font-semibold text-[#56353a] transition hover:border-[#d6c6ba] hover:bg-white">
                                                    {{ $secondaryAction['label'] }}
                                                </a>
                                            @endif
                                        </div>
                                    @endif

                                    @if ($isDashboardView)
                                        <section
                                            class="mt-6 grid grid-cols-1 gap-3 sm:grid-cols-2 xl:mt-8 xl:flex-1 xl:auto-rows-fr">
                                            @foreach ($dashboardStats as $stat)
                                                @php $icon = $metricIcon((string) ($stat['label'] ?? '')); @endphp
                                                <x-ui.card
                                                    class="overflow-hidden border-[#e6ddd5] bg-white/90 p-4 shadow-[0_14px_28px_rgba(56,35,27,0.05)] xl:h-full">
                                                    <div class="flex h-full flex-col justify-between gap-4">
                                                        <div class="flex items-start justify-between gap-3">
                                                            <div class="min-w-0">
                                                                <p
                                                                    class="text-[11px] font-semibold tracking-[0.04em] text-[#7b655e]">
                                                                    {{ $stat['label'] }}
                                                                </p>
                                                                <p
                                                                    class="mt-3 text-[1.45rem] font-semibold tracking-[-0.05em] text-[#2c1d1d] tabular-nums sm:text-[2.05rem]">
                                                                    {{ $stat['value'] }}
                                                                </p>

                                                                @if (($stat['label'] ?? '') === 'Total Rating Kita')
                                                                    <div class="mt-3 flex items-center gap-1 text-[#C18B2F]">
                                                                        @for ($i = 1; $i <= 5; $i++)
                                                                            <svg class="h-3.5 w-3.5" viewBox="0 0 20 20"
                                                                                fill="{{ $i <= round((float) str_replace(',', '.', strtok((string) $stat['value'], '/'))) ? 'currentColor' : 'none' }}"
                                                                                stroke="currentColor" aria-hidden="true">
                                                                                <path
                                                                                    d="M10 3.5L11.85 7.25L16 7.86L13 10.79L13.7 14.93L10 12.98L6.3 14.93L7 10.79L4 7.86L8.15 7.25L10 3.5Z"
                                                                                    stroke-width="1.2"
                                                                                    stroke-linejoin="round" />
                                                                            </svg>
                                                                        @endfor
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div
                                                                class="rounded-xl border border-slate-100 bg-slate-50 p-2 text-slate-400">
                                                                @if ($icon === 'money')
                                                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                                                        stroke="currentColor" stroke-width="2"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        aria-hidden="true">
                                                                        <line x1="12" y1="1" x2="12"
                                                                            y2="23"></line>
                                                                        <path
                                                                            d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6">
                                                                        </path>
                                                                    </svg>
                                                                @elseif ($icon === 'tag')
                                                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                                                        stroke="currentColor" stroke-width="2"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        aria-hidden="true">
                                                                        <path
                                                                            d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z">
                                                                        </path>
                                                                        <line x1="7" y1="7" x2="7.01"
                                                                            y2="7"></line>
                                                                    </svg>
                                                                @elseif ($icon === 'star')
                                                                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none"
                                                                        stroke="currentColor" aria-hidden="true">
                                                                        <path
                                                                            d="M10 3.5L11.85 7.25L16 7.86L13 10.79L13.7 14.93L10 12.98L6.3 14.93L7 10.79L4 7.86L8.15 7.25L10 3.5Z"
                                                                            stroke-width="1.5"
                                                                            stroke-linejoin="round" />
                                                                    </svg>
                                                                @elseif ($icon === 'users')
                                                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                                                        stroke="currentColor" stroke-width="2"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        aria-hidden="true">
                                                                        <path
                                                                            d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2">
                                                                        </path>
                                                                        <circle cx="9" cy="7" r="4"></circle>
                                                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                                                    </svg>
                                                                @else
                                                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                                                        stroke="currentColor" stroke-width="2"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        aria-hidden="true">
                                                                        <path
                                                                            d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
                                                                        </path>
                                                                        <polyline
                                                                            points="3.27 6.96 12 12.01 20.73 6.96">
                                                                        </polyline>
                                                                        <line x1="12" y1="22.08" x2="12"
                                                                            y2="12"></line>
                                                                    </svg>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="space-y-1.5">
                                                            @if (!empty($stat['description']))
                                                                <p class="text-sm leading-6 text-[#5f4b45]">
                                                                    {{ $stat['description'] }}
                                                                </p>
                                                            @endif
                                                            @if (!empty($stat['context']))
                                                                <p class="text-[13px] leading-5 text-[#8a746d]">
                                                                    {{ $stat['context'] }}
                                                                </p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </x-ui.card>
                                            @endforeach
                                        </section>
                                    @endif
                                </div>

                                @if ($isManageMode)
                                    <div class="flex flex-wrap gap-3">
                                        @if ($primaryAction)
                                            <a href="{{ $primaryAction['href'] }}"
                                                class="inline-flex items-center rounded-full bg-[#7A1F2B] px-4 py-2.5 text-sm font-semibold text-white shadow-[0_12px_28px_rgba(94,23,33,0.18)] transition hover:bg-[#651925]">
                                                {{ $primaryAction['label'] }}
                                            </a>
                                        @endif
                                        @if ($secondaryAction)
                                            <a href="{{ $secondaryAction['href'] }}"
                                                class="inline-flex items-center rounded-full border border-[#e6ddd5] bg-[#fbf8f5] px-4 py-2.5 text-sm font-semibold text-[#56353a] transition hover:border-[#d6c6ba] hover:bg-white">
                                                {{ $secondaryAction['label'] }}
                                            </a>
                                        @endif
                                    </div>
                                @else
                                    <x-ui.card class="border-[#ece3db] bg-[#faf7f4] shadow-none">
                                        <x-ui.card-header
                                            class="flex flex-row items-center justify-between gap-3 border-b border-[#e7dcd4] pb-4">
                                            <x-ui.card-title
                                                class="text-xs font-semibold tracking-[0.04em] text-[#7b655e]">
                                                {{ $isDashboardView ? 'Pulse hari ini' : 'Ringkasan modul' }}
                                            </x-ui.card-title>
                                            <x-ui.badge variant="outline"
                                                class="rounded-full bg-white px-3 py-1 text-[11px] font-semibold text-[#6f5c55]">
                                                {{ $workspaceDate }}
                                            </x-ui.badge>
                                        </x-ui.card-header>

                                        <x-ui.card-content class="pt-4">
                                            @if ($isDashboardView)
                                                <div
                                                    class="divide-y divide-[#e7dcd4] sm:grid sm:grid-cols-3 sm:divide-y-0 sm:gap-3 xl:block xl:space-y-0 xl:divide-y">
                                                    @foreach (array_slice($dashboardHighlights, 0, 3) as $highlight)
                                                        <div
                                                            class="py-3 first:pt-0 last:pb-0 sm:rounded-2xl sm:border sm:border-[#e6ddd5] sm:bg-white sm:p-4 xl:rounded-none xl:border-0 xl:bg-transparent xl:px-0 xl:py-3">
                                                            <div class="flex items-center justify-between gap-3">
                                                                <p class="text-sm font-semibold text-[#5f4742]">
                                                                    {{ $highlight['label'] }}
                                                                </p>
                                                                @if (str_contains(Str::lower($highlight['label']), 'promo'))
                                                                    <div class="flex items-center gap-2">
                                                                        <span class="relative flex h-2 w-2">
                                                                            <span
                                                                                class="relative inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                                                                        </span>
                                                                        <x-ui.badge variant="outline"
                                                                            class="rounded-full border-emerald-200 bg-emerald-50 px-2 py-0.5 text-[10px] font-medium tracking-wide text-emerald-700">
                                                                            AKTIF
                                                                        </x-ui.badge>
                                                                    </div>
                                                                @else
                                                                    <div class="flex items-center gap-2">
                                                                        <span class="relative flex h-2 w-2">
                                                                            <span
                                                                                class="absolute inline-flex h-full w-full animate-ping rounded-full bg-red-400 opacity-75"></span>
                                                                            <span
                                                                                class="relative inline-flex h-2 w-2 rounded-full bg-red-600"></span>
                                                                        </span>
                                                                        <x-ui.badge variant="destructive"
                                                                            class="rounded-full px-2 py-0.5 text-[10px] font-medium tracking-wide">
                                                                            BUTUH ATENSI
                                                                        </x-ui.badge>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <p
                                                                class="mt-2 text-[1.7rem] font-semibold tracking-[-0.04em] text-[#2c1d1d] tabular-nums">
                                                                {{ $highlight['value'] }}
                                                            </p>
                                                            <p class="mt-1 text-[15px] leading-6 text-[#5e4d49]">
                                                                {{ $highlight['hint'] }}</p>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div>
                                                    <p class="text-sm font-medium text-[#7b655e]">Data terikat</p>
                                                    <p
                                                        class="mt-2 text-[2rem] font-semibold tracking-[-0.05em] text-[#2c1d1d] tabular-nums">
                                                        {{ $focusSummary['count'] ?? ($focusMetrics[0]['value'] ?? '-') }}
                                                    </p>
                                                    <p class="mt-1 text-sm leading-6 text-[#6a5854]">
                                                        {{ $focusSummary['detail'] ?? ($focusMetrics[0]['hint'] ?? 'Ringkasan utama modul aktif.') }}
                                                    </p>
                                                </div>
                                            @endif
                                        </x-ui.card-content>
                                    </x-ui.card>
                                @endif
                            </div>

                        </x-ui.card>
                    </section>

                    @if ($focus !== 'dashboard' && filled($searchTerm))
                        <section
                            class="mt-6 flex flex-col gap-3 rounded-2xl border border-[#e6ddd5] bg-[#fbf8f5] p-4 shadow-[0_12px_24px_rgba(56,35,27,0.05)] sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="text-xs font-semibold tracking-[0.04em] text-[#7b655e]">Pencarian
                                    aktif
                                </p>
                                <p class="mt-1 text-sm text-[#6a5854]">
                                    Menampilkan hasil untuk <span
                                        class="font-semibold text-[#7A1F2B]">"{{ $searchTerm }}"</span>.
                                </p>
                            </div>
                            <a href="{{ route('admin.index', ['focus' => $focus, 'mode' => 'manage']) }}"
                                class="inline-flex items-center justify-center rounded-full border border-[#e6ddd5] bg-white px-4 py-2 text-sm font-semibold text-[#56353a] transition hover:bg-[#fbf7f3] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#7A1F2B] focus-visible:ring-offset-2 focus-visible:ring-offset-[#fbf8f5]">
                                Reset pencarian
                            </a>
                        </section>
                    @endif

                    @php
                        $isEditing = $mode === 'edit' && isset($currentRecord) && $currentRecord;
                        $formAction = $isEditing
                            ? route('admin.update', ['focus' => $focus, 'record' => $currentRecord->id])
                            : route('admin.store', ['focus' => $focus]);
                        $formTitle = $isEditing ? 'Edit data ' . ucfirst($focus) : $createBlueprint['title'];
                        $formSubtitle = $isEditing
                            ? 'Perbarui data yang sudah masuk ke database tanpa pindah ke panel lain.'
                            : $createBlueprint['subtitle'];
                        $submitLabel = $isEditing ? 'Simpan Perubahan' : 'Simpan ke Database';
                        $validationErrors = $errors->getMessages();
                        $editorSectionClass =
                            'rounded-[24px] border border-[#eadfd6] bg-white/85 p-5 shadow-[0_12px_28px_rgba(67,34,34,0.05)]';
                        $editorLabelClass = 'block text-sm font-semibold text-[#432226]';
                        $editorFieldClass =
                            'mt-2 w-full rounded-[16px] border border-[#eadfd6] bg-white px-4 py-2.5 text-[15px] leading-6 text-[#2d1e1e] shadow-[0_8px_18px_rgba(67,34,34,0.03)] placeholder:text-[#9a7f77] transition focus:border-[#7A1F2B] focus:outline-none focus:ring-4 focus:ring-[#7A1F2B]/10';
                        $editorToggleClass =
                            'flex items-center gap-3 rounded-[16px] border border-[#eadfd6] bg-white px-4 py-3 text-sm font-medium text-[#4a2b2f] shadow-[0_8px_18px_rgba(67,34,34,0.03)] transition';
                        $editorCheckboxClass = 'h-4 w-4 rounded border-[#cfb6ac] text-[#7A1F2B] focus:ring-[#7A1F2B]';
                    @endphp

                    @include('admin.partials.main-content')
                </main>
            </div>
        </div>


        @if (session('success'))
            <div id="admin-success-toast"
                class="pointer-events-none fixed bottom-5 right-5 z-[120] w-full max-w-sm translate-y-6 opacity-0 transition-all duration-300"
                role="status" aria-live="polite">
                <div
                    class="pointer-events-auto overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-lg shadow-slate-900/10">
                    <div class="flex items-start gap-3 p-4">
                        <span
                            class="mt-0.5 inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-50 text-emerald-600 ring-1 ring-emerald-100">
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                <path d="M5 10.5L8.2 13.5L15 6.5" stroke="currentColor" stroke-width="1.8"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-slate-900">Perubahan disimpan</p>
                            <p class="mt-1 text-sm leading-6 text-slate-600">{{ session('success') }}</p>
                        </div>
                        <button type="button" id="admin-success-toast-close"
                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-700">
                            <span class="sr-only">Tutup toast</span>
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                <path d="M5 5L15 15M15 5L5 15" stroke="currentColor" stroke-width="1.7"
                                    stroke-linecap="round" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <x-ui.dialog id="admin-delete-dialog"
            class="pointer-events-none hidden px-4 opacity-0 transition duration-200" role="dialog"
            aria-modal="true" aria-labelledby="admin-delete-dialog-title">
            <x-ui.dialog-content>
                <div class="space-y-2">
                    <h2 id="admin-delete-dialog-title" class="text-lg font-semibold text-slate-900">Hapus data?</h2>
                    <p class="text-sm leading-6 text-slate-600">
                        Tindakan ini tidak bisa dibatalkan. Data akan dihapus permanen dari panel admin.
                    </p>
                </div>
                <x-ui.dialog-footer>
                    <x-ui.button type="button" id="admin-delete-cancel" variant="outline">
                        Cancel
                    </x-ui.button>
                    <x-ui.button type="button" id="admin-delete-confirm" variant="destructive">
                        Continue
                    </x-ui.button>
                </x-ui.dialog-footer>
            </x-ui.dialog-content>
        </x-ui.dialog>

        @include('admin.partials.record-editor')


        <script>
            (() => {
                const omzetChartPoints = @json($omzetChartPoints ?? []);
                const defaultOmzetRange = @json($defaultOmzetRange ?? '30d');
                const openButton = document.getElementById('admin-open');
                const closeButton = document.getElementById('admin-close');
                const sidebar = document.getElementById('admin-sidebar');
                const backdrop = document.getElementById('admin-backdrop');
                const errorSummary = document.getElementById('admin-error-summary');
                const editorForm = document.querySelector('[data-admin-editor-form="true"]');
                const chartElement = document.getElementById('revenue-line-chart');
                const successToast = document.getElementById('admin-success-toast');
                const successToastClose = document.getElementById('admin-success-toast-close');
                const deleteDialog = document.getElementById('admin-delete-dialog');
                const deleteCancelButton = document.getElementById('admin-delete-cancel');
                const deleteConfirmButton = document.getElementById('admin-delete-confirm');
                const deleteForms = Array.from(document.querySelectorAll('form[data-delete-confirm="true"]'));
                const validationErrors = @json($validationErrors ?? []);
                let pendingDeleteForm = null;
                let omzetChart = null;

                const parseChartDate = (value) => {
                    const date = new Date(value);
                    return Number.isNaN(date.getTime()) ? null : date;
                };

                const formatCurrency = (value) => `Rp ${new Intl.NumberFormat('id-ID').format(value)}`;

                const formatChartLabel = (point, range) => {
                    const parsedDate = parseChartDate(point.raw_date ?? point.date);

                    if (!parsedDate) {
                        return point.label ?? point.date;
                    }

                    if (range === '6m' || range === '1y' || range === '5y' || range === 'all') {
                        return new Intl.DateTimeFormat('id-ID', {
                            month: 'short',
                            year: '2-digit',
                        }).format(parsedDate);
                    }

                    return new Intl.DateTimeFormat('id-ID', {
                        day: '2-digit',
                        month: 'short',
                    }).format(parsedDate);
                };

                const getFilteredOmzetPoints = (range = '30d') => {
                    if (!Array.isArray(omzetChartPoints) || omzetChartPoints.length === 0) {
                        return [];
                    }

                    const allPoints = [...omzetChartPoints];
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);

                    if (!parseChartDate(allPoints[allPoints.length - 1]?.raw_date ?? allPoints[allPoints.length - 1]
                            ?.date)) {
                        const fallbackSlices = {
                            '30d': 30,
                            '6m': 180,
                            '1y': 365,
                            '5y': 1825,
                        };

                        return fallbackSlices[range] ? allPoints.slice(-fallbackSlices[range]) : allPoints;
                    }

                    if (range === 'all') {
                        return allPoints;
                    }

                    if (range === 'ytd') {
                        const yearStart = new Date(today.getFullYear(), 0, 1);
                        return allPoints.filter((point) => {
                            const pointDate = parseChartDate(point.raw_date ?? point.date);
                            return pointDate && pointDate >= yearStart && pointDate <= today;
                        });
                    }

                    const rangeDays = {
                        '30d': 30,
                        '6m': 183,
                        '1y': 365,
                        '5y': 1825,
                    };

                    const days = rangeDays[range];

                    if (!days) {
                        return allPoints;
                    }

                    const minDate = new Date(today);
                    minDate.setDate(minDate.getDate() - (days - 1));

                    return allPoints.filter((point) => {
                        const pointDate = parseChartDate(point.raw_date ?? point.date);
                        return pointDate && pointDate >= minDate && pointDate <= today;
                    });
                };

                const renderOmzetChart = () => {
                    if (!chartElement || typeof ApexCharts === 'undefined') {
                        return;
                    }

                    const initialPoints = getFilteredOmzetPoints(defaultOmzetRange);
                    const seriesData = initialPoints.map((point) => ({
                        x: point.raw_date ?? point.date,
                        y: Number(point.value ?? 0),
                    }));
                    const rangeBounds = (() => {
                        const today = new Date();
                        today.setHours(0, 0, 0, 0);

                        if (defaultOmzetRange === 'all') {
                            return {
                                min: parseChartDate(initialPoints[0]?.raw_date ?? initialPoints[0]?.date)?.getTime(),
                                max: today.getTime(),
                            };
                        }

                        if (defaultOmzetRange === 'ytd') {
                            return {
                                min: new Date(today.getFullYear(), 0, 1).getTime(),
                                max: today.getTime(),
                            };
                        }

                        const days = {
                            '30d': 30,
                            '6m': 183,
                            '1y': 365,
                            '5y': 1825,
                        }[defaultOmzetRange] ?? 30;
                        const minDate = new Date(today);
                        minDate.setDate(minDate.getDate() - (days - 1));

                        return {
                            min: minDate.getTime(),
                            max: today.getTime(),
                        };
                    })();

                    omzetChart = new ApexCharts(chartElement, {
                        chart: {
                            type: 'line',
                            height: 320,
                            toolbar: {
                                show: false,
                            },
                            dropShadow: {
                                enabled: true,
                                color: '#7A1F2B',
                                top: 3,
                                left: 1,
                                blur: 4,
                                opacity: 0.15,
                            },
                            animations: {
                                enabled: true,
                                easing: 'easeinout',
                                speed: 720,
                            },
                            fontFamily: 'Manrope, Inter, sans-serif',
                        },
                        series: [{
                            name: 'Omzet',
                            data: seriesData,
                        }],
                        colors: ['#7A1F2B'],
                        dataLabels: {
                            enabled: false,
                        },
                        stroke: {
                            curve: 'smooth',
                            width: 3,
                        },
                        markers: {
                            size: 0,
                            hover: {
                                size: 5,
                            },
                        },
                        grid: {
                            borderColor: '#eadfd6',
                            strokeDashArray: 4,
                            padding: {
                                left: 4,
                                right: 10,
                                top: 8,
                                bottom: 0,
                            },
                        },
                        xaxis: {
                            type: 'datetime',
                            min: rangeBounds.min,
                            max: rangeBounds.max,
                            axisBorder: {
                                show: false,
                            },
                            axisTicks: {
                                show: false,
                            },
                            tickAmount: 6,
                            labels: {
                                rotate: 0,
                                hideOverlappingLabels: true,
                                trim: true,
                                style: {
                                    colors: '#8b746d',
                                    fontSize: '11px',
                                },
                                formatter: (_value, timestamp) => {
                                    if (!timestamp) {
                                        return '';
                                    }

                                    const date = new Date(timestamp);
                                    return new Intl.DateTimeFormat('id-ID', {
                                        day: '2-digit',
                                        month: 'short',
                                    }).format(date);
                                },
                            },
                        },
                        yaxis: {
                            labels: {
                                style: {
                                    colors: '#8b746d',
                                    fontSize: '11px',
                                },
                                formatter: (value) => `Rp ${new Intl.NumberFormat('id-ID').format(value)}`,
                            },
                        },
                        tooltip: {
                            x: {
                                formatter: (_value, context) => {
                                    const point = initialPoints[context.dataPointIndex];

                                    if (!point) {
                                        return _value;
                                    }

                                    const parsedDate = parseChartDate(point.raw_date ?? point.date);
                                    return parsedDate ? new Intl.DateTimeFormat('id-ID', {
                                        day: '2-digit',
                                        month: 'long',
                                        year: 'numeric',
                                    }).format(parsedDate) : point.label;
                                },
                            },
                            y: {
                                formatter: (value) => formatCurrency(value),
                            },
                        },
                        states: {
                            hover: {
                                filter: {
                                    type: 'lighten',
                                    value: 0.08,
                                },
                            },
                        },
                    });

                    omzetChart.render();

                    document.addEventListener('filter-chart', (event) => {
                        const range = typeof event.detail === 'string' ? event.detail : '30d';
                        const filteredPoints = getFilteredOmzetPoints(range);
                        const today = new Date();
                        today.setHours(0, 0, 0, 0);
                        const rangeBounds = (() => {
                            if (range === 'all') {
                                return {
                                    min: parseChartDate(filteredPoints[0]?.raw_date ?? filteredPoints[0]?.date)
                                        ?.getTime(),
                                    max: today.getTime(),
                                };
                            }

                            if (range === 'ytd') {
                                return {
                                    min: new Date(today.getFullYear(), 0, 1).getTime(),
                                    max: today.getTime(),
                                };
                            }

                            const days = {
                                '30d': 30,
                                '6m': 183,
                                '1y': 365,
                                '5y': 1825,
                            } [range] ?? 30;
                            const minDate = new Date(today);
                            minDate.setDate(minDate.getDate() - (days - 1));

                            return {
                                min: minDate.getTime(),
                                max: today.getTime(),
                            };
                        })();

                        omzetChart.updateOptions({
                            series: [{
                                name: 'Omzet',
                                data: filteredPoints.map((point) => ({
                                    x: point.raw_date ?? point.date,
                                    y: Number(point.value ?? 0),
                                })),
                            }],
                            xaxis: {
                                type: 'datetime',
                                min: rangeBounds.min,
                                max: rangeBounds.max,
                                tickAmount: range === '30d' ? 6 : 7,
                            },
                            tooltip: {
                                x: {
                                    formatter: (_value, context) => {
                                        const point = filteredPoints[context.dataPointIndex];

                                        if (!point) {
                                            return _value;
                                        }

                                        const parsedDate = parseChartDate(point.raw_date ?? point.date);
                                        return parsedDate ? new Intl.DateTimeFormat('id-ID', {
                                            day: '2-digit',
                                            month: 'long',
                                            year: 'numeric',
                                        }).format(parsedDate) : point.label;
                                    },
                                },
                            },
                        }, false, true);
                    });
                };

                const showSuccessToast = () => {
                    if (!successToast) {
                        return;
                    }

                    const hideToast = () => {
                        successToast.classList.add('translate-y-6', 'opacity-0');
                        successToast.classList.remove('translate-y-0', 'opacity-100');
                    };

                    window.requestAnimationFrame(() => {
                        successToast.classList.remove('translate-y-6', 'opacity-0');
                        successToast.classList.add('translate-y-0', 'opacity-100');
                    });

                    const timeoutId = window.setTimeout(hideToast, 4200);

                    successToastClose?.addEventListener('click', () => {
                        window.clearTimeout(timeoutId);
                        hideToast();
                    }, {
                        once: true
                    });
                };

                const openDeleteDialog = (form) => {
                    if (!deleteDialog || !deleteConfirmButton) {
                        form.submit();
                        return;
                    }

                    pendingDeleteForm = form;
                    deleteDialog.classList.remove('hidden', 'pointer-events-none', 'opacity-0');
                    deleteDialog.classList.add('flex', 'opacity-100');
                    document.body.classList.add('overflow-hidden');
                };

                const closeDeleteDialog = () => {
                    if (!deleteDialog) {
                        return;
                    }

                    deleteDialog.classList.add('pointer-events-none', 'opacity-0');
                    deleteDialog.classList.remove('opacity-100');
                    window.setTimeout(() => {
                        deleteDialog.classList.add('hidden');
                        deleteDialog.classList.remove('flex');
                    }, 180);
                    document.body.classList.remove('overflow-hidden');
                    pendingDeleteForm = null;
                };

                const bindDeleteDialog = () => {
                    if (!deleteForms.length) {
                        return;
                    }

                    deleteForms.forEach((form) => {
                        form.addEventListener('submit', (event) => {
                            event.preventDefault();
                            openDeleteDialog(form);
                        });
                    });

                    deleteCancelButton?.addEventListener('click', closeDeleteDialog);
                    deleteDialog?.addEventListener('click', (event) => {
                        if (event.target === deleteDialog) {
                            closeDeleteDialog();
                        }
                    });
                    deleteConfirmButton?.addEventListener('click', () => {
                        if (pendingDeleteForm) {
                            pendingDeleteForm.submit();
                        }
                    });
                };

                const appendFieldErrors = () => {
                    if (!editorForm || !validationErrors || Object.keys(validationErrors).length === 0) {
                        return;
                    }

                    Object.entries(validationErrors).forEach(([fieldName, messages]) => {
                        const field = editorForm.querySelector(`[name="${fieldName}"]`);

                        if (!(field instanceof HTMLElement) || !Array.isArray(messages) || messages.length ===
                            0) {
                            return;
                        }

                        field.setAttribute('aria-invalid', 'true');

                        if (field instanceof HTMLInputElement && field.type === 'checkbox') {
                            const toggle = field.closest('label');

                            if (toggle instanceof HTMLElement) {
                                toggle.classList.add('border-rose-300', 'bg-rose-50');

                                if (!toggle.querySelector(`[data-field-error="${fieldName}"]`)) {
                                    const message = document.createElement('p');
                                    message.dataset.fieldError = fieldName;
                                    message.className =
                                        'w-full text-sm font-medium leading-6 text-rose-700';
                                    message.textContent = messages[0];
                                    toggle.appendChild(message);
                                }
                            }

                            return;
                        }

                        field.classList.add('border-rose-300', 'bg-rose-50/70', 'focus:border-rose-400',
                            'focus:ring-rose-100');

                        const fieldWrapper = field.closest('div');

                        if (!(fieldWrapper instanceof HTMLElement) || fieldWrapper.querySelector(
                                `[data-field-error="${fieldName}"]`)) {
                            return;
                        }

                        const message = document.createElement('p');
                        message.dataset.fieldError = fieldName;
                        message.className = 'mt-2 text-sm font-medium leading-6 text-rose-700';
                        message.textContent = messages[0];
                        fieldWrapper.appendChild(message);
                    });
                };

                const bindSubmitLoadingState = () => {
                    if (!editorForm) {
                        return;
                    }

                    editorForm.addEventListener('submit', () => {
                        const submitButton = editorForm.querySelector('[data-submit-button="true"]');

                        if (!(submitButton instanceof HTMLButtonElement) || submitButton.disabled) {
                            return;
                        }

                        submitButton.disabled = true;
                        submitButton.dataset.loading = 'true';
                        submitButton.setAttribute('aria-busy', 'true');
                        submitButton.classList.add('cursor-wait', 'opacity-80', 'hover:translate-y-0');

                        const submitLabel = submitButton.querySelector('[data-submit-label="true"]');

                        if (submitLabel instanceof HTMLElement) {
                            submitLabel.textContent = 'Loading...';
                        } else {
                            submitButton.textContent = 'Loading...';
                        }
                    });
                };

                appendFieldErrors();
                bindSubmitLoadingState();
                bindDeleteDialog();
                renderOmzetChart();
                showSuccessToast();

                if (!openButton || !closeButton || !sidebar || !backdrop) {
                    if (errorSummary) {
                        const invalidField = document.querySelector(
                            '#record-editor [aria-invalid="true"], #record-editor .border-rose-300, #record-editor input:invalid, #record-editor select:invalid, #record-editor textarea:invalid'
                        );

                        if (invalidField instanceof HTMLElement) {
                            window.requestAnimationFrame(() => invalidField.focus());
                        }
                    }

                    return;
                }

                const openMenu = () => {
                    sidebar.classList.remove('-translate-x-full');
                    backdrop.classList.remove('hidden');
                    openButton.setAttribute('aria-expanded', 'true');
                    document.body.classList.add('overflow-hidden');
                };

                const closeMenu = () => {
                    sidebar.classList.add('-translate-x-full');
                    backdrop.classList.add('hidden');
                    openButton.setAttribute('aria-expanded', 'false');
                    document.body.classList.remove('overflow-hidden');
                };

                openButton.addEventListener('click', openMenu);
                closeButton.addEventListener('click', closeMenu);
                backdrop.addEventListener('click', closeMenu);

                window.addEventListener('keydown', (event) => {
                    if (event.key === 'Escape' && deleteDialog && !deleteDialog.classList.contains('hidden')) {
                        closeDeleteDialog();
                        return;
                    }

                    if (event.key === 'Escape') {
                        closeMenu();
                    }
                });

                window.addEventListener('resize', () => {
                    if (window.innerWidth >= 1024) {
                        sidebar.classList.remove('-translate-x-full');
                        backdrop.classList.add('hidden');
                        openButton.setAttribute('aria-expanded', 'false');
                        document.body.classList.remove('overflow-hidden');
                    } else {
                        sidebar.classList.add('-translate-x-full');
                    }
                }, {
                    passive: true
                });

                if (errorSummary) {
                    const invalidField = document.querySelector(
                        '#record-editor [aria-invalid="true"], #record-editor .border-rose-300, #record-editor input:invalid, #record-editor select:invalid, #record-editor textarea:invalid'
                    );

                    if (invalidField instanceof HTMLElement) {
                        window.requestAnimationFrame(() => invalidField.focus());
                    }
                }
            })();
        </script>
</body>

</html>
