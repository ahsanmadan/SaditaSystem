                    @php
                        $tableSortKey = fn (string $column) => match ($column) {
                            'Nama', 'Produk', 'Pengulas', 'Pelanggan' => 'name',
                            'Slug' => 'slug',
                            'Status' => 'status',
                            'Dibuat', 'Terdaftar', 'Waktu' => 'created_at',
                            'Kode' => 'kode',
                            'Diskon' => 'nilai_diskon',
                            'Harga Dasar' => 'harga_dasar',
                            'Email' => 'email',
                            'Role' => 'role',
                            'Admin' => 'is_admin',
                            'Rating' => 'rating',
                            'Total' => 'grand_total',
                            'Nominal' => 'jumlah_dibayar',
                            'Metode' => 'metode',
                            default => null,
                        };
                        $canBulkDelete = $isManageMode && $focus !== 'aktivitas' && $user?->isOwner();
                        $canManageData = $canManageData ?? false;
                    @endphp

                    @if ($canBulkDelete)
                        <form id="admin-bulk-delete-form" action="{{ route('admin.bulk-destroy') }}" method="POST" data-delete-confirm="true" class="hidden">
                            @csrf
                            <input type="hidden" name="focus" value="{{ $focus }}">
                            <span data-bulk-selected-inputs></span>
                        </form>
                    @endif

                    @if ($focus === 'dashboard')
                        <section class="mt-6 space-y-6">
                            <x-ui.card
                                x-data="{ range: '{{ $defaultOmzetRange ?? '30d' }}', showScale: false }"
                                class="relative overflow-hidden border-[#e6ddd5] shadow-[0_16px_34px_rgba(56,35,27,0.05)]">
                                    <x-ui.card-header
                                        class="flex flex-col gap-3 border-b border-slate-100 px-4 pb-3 pt-4 pr-14 sm:flex-row sm:items-center sm:justify-between sm:px-6 sm:pb-4 sm:pt-6">
                                        <div class="min-w-0">
                                            <x-ui.card-title class="text-lg font-semibold text-slate-900">
                                                Penjualan
                                            </x-ui.card-title>
                                        </div>

                                        <div
                                            class="hidden items-center gap-1 rounded-xl border border-slate-200/60 bg-slate-100 p-1 sm:inline-flex">
                                            @foreach ([
                                                '30d' => '30 Hari',
                                                '6m' => '6 Bulan',
                                                '1y' => '1 Tahun',
                                                'ytd' => 'YTD',
                                                '5y' => '5 Tahun',
                                                'all' => 'All Time',
                                            ] as $rangeKey => $rangeLabel)
                                                <button type="button"
                                                    @click="range = '{{ $rangeKey }}'; $dispatch('filter-chart', '{{ $rangeKey }}')"
                                                    :class="range === '{{ $rangeKey }}' ? 'bg-white text-slate-900 shadow-sm' :
                                                        'text-slate-600 hover:text-slate-900'"
                                                    class="rounded-lg px-2.5 py-1 text-xs font-medium transition duration-150">
                                                    {{ $rangeLabel }}
                                                </button>
                                            @endforeach
                                        </div>
                                    </x-ui.card-header>

                                    <x-ui.card-content class="px-3 pb-4 pt-3 sm:px-6 sm:pb-6 sm:pt-6">
                                        <x-ui.chart id="revenue-area-chart" type="area"
                                            :config="[
                                                'sales' => ['label' => 'Penjualan', 'color' => '#7A1F2B'],
                                            ]"
                                            :series="[['name' => 'Penjualan', 'data' => []]]"
                                            :colors="['#7A1F2B']"
                                            :options="[
                                                'fill' => [
                                                    'type' => 'gradient',
                                                    'gradient' => [
                                                        'shadeIntensity' => 1,
                                                        'opacityFrom' => 0.52,
                                                        'opacityTo' => 0.06,
                                                        'stops' => [5, 95],
                                                    ],
                                                ],
                                                'stroke' => ['width' => 2.5, 'curve' => 'smooth'],
                                                'yaxis' => ['show' => false],
                                                'legend' => ['show' => false],
                                                'tooltip' => ['x' => ['show' => true]],
                                            ]"
                                            height="320"
                                            label="Grafik penjualan"
                                            class="aspect-auto h-[250px] sm:h-[320px]" />

                                        <div class="mt-3 sm:hidden">
                                            <div class="grid w-full grid-cols-6 items-center gap-1 rounded-xl border border-slate-200/60 bg-slate-100 p-1">
                                                @foreach ([
                                                    '30d' => '30H',
                                                    '6m' => '6B',
                                                    '1y' => '1T',
                                                    'ytd' => 'YTD',
                                                    '5y' => '5T',
                                                    'all' => 'All',
                                                ] as $rangeKey => $rangeLabel)
                                                    <button type="button"
                                                        @click="range = '{{ $rangeKey }}'; $dispatch('filter-chart', '{{ $rangeKey }}')"
                                                        :class="range === '{{ $rangeKey }}' ? 'bg-white text-slate-900 shadow-sm' :
                                                            'text-slate-600 hover:text-slate-900'"
                                                        class="min-w-0 rounded-lg px-1 py-1.5 text-[10px] font-medium transition duration-150">
                                                        {{ $rangeLabel }}
                                                    </button>
                                                @endforeach
                                            </div>
                                        </div>

                                        @unless ($hasOmzetData)
                                            <div
                                                class="mt-4 rounded-2xl border border-dashed border-slate-200 bg-slate-50/70 px-4 py-3 text-sm text-slate-500">
                                                Belum ada penjualan.
                                            </div>
                                        @endunless
                                </x-ui.card-content>
                            </x-ui.card>

                            <div class="grid min-w-0 grid-cols-1 gap-6 xl:grid-cols-12 xl:items-start">
                                <div class="min-w-0 xl:col-span-7">
                                <div class="flex h-full flex-col gap-6">
                                    <x-ui.card
                                        class="overflow-hidden rounded-[28px] border-[#e6ddd5] shadow-[0_16px_34px_rgba(56,35,27,0.05)]">
                                        <x-ui.card-header>
                                            <x-ui.card-title
                                                class="text-[1.35rem] font-semibold tracking-[-0.04em] text-[#2c1d1d]">
                                                Pelanggan langganan
                                            </x-ui.card-title>
                                        </x-ui.card-header>

                                        <x-ui.card-content class="pt-0">
                                            <div class="overflow-hidden rounded-[24px] border border-[#e6ddd5]">
                                                <div
                                                    class="hidden grid-cols-[minmax(0,1.4fr)_140px_190px] gap-4 bg-[#fbf8f5] px-5 py-3 text-[11px] font-semibold tracking-[0.04em] text-[#7b655e] md:grid">
                                                    <span>Pelanggan</span>
                                                    <span>Total order</span>
                                                    <span>Revenue selesai</span>
                                                </div>
                                                <div class="divide-y divide-[#efe7e0]">
                                                    @forelse ($topCustomers as $customer)
                                                        <div
                                                            class="grid gap-4 px-4 py-4 md:grid-cols-[minmax(0,1.4fr)_140px_190px] md:items-center md:px-5">
                                                            <div class="min-w-0">
                                                                <div class="min-w-0">
                                                                    <p
                                                                        class="truncate text-sm font-semibold text-[#2c1d1d]">
                                                                        {{ $customer['nama'] }}</p>
                                                                    <p class="mt-0.5 text-[13px] text-[#8b746d]">
                                                                        {{ $customer['kontak'] }}</p>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="text-sm font-semibold text-[#5f4742] tabular-nums md:text-base">
                                                                {{ $customer['total_order'] }}x
                                                            </div>
                                                            <div
                                                                class="text-sm font-semibold text-[#7A1F2B] tabular-nums md:text-base">
                                                                {{ $customer['total_revenue'] }}
                                                            </div>
                                                        </div>
                                                    @empty
                                                        <div class="px-5 py-8 text-sm text-[#6a5854]">Belum ada data pelanggan
                                                            unggulan.</div>
                                                    @endforelse
                                                </div>
                                            </div>
                                        </x-ui.card-content>
                                    </x-ui.card>

                                    <x-ui.card
                                        class="overflow-hidden rounded-[28px] border-[#e6ddd5] shadow-[0_16px_34px_rgba(56,35,27,0.05)]">
                                        <x-ui.card-header class="flex flex-row items-end justify-between gap-4">
                                            <div>
                                                <x-ui.card-description
                                                    class="text-xs font-semibold tracking-[0.04em] text-[#7b655e]">
                                                    Fokus tim
                                                </x-ui.card-description>
                                                <x-ui.card-title
                                                    class="mt-2 text-[1.2rem] font-semibold tracking-[-0.04em] text-[#2c1d1d]">
                                                    Prioritas cepat
                                                </x-ui.card-title>
                                            </div>
                                            @if ($primaryAction)
                                                <a href="{{ $primaryAction['href'] }}"
                                                    class="hidden rounded-full border border-[#e3d5cb] bg-[#fbf8f5] px-3.5 py-2 text-xs font-semibold text-[#7A1F2B] transition hover:border-[#d6c2b4] hover:bg-white sm:inline-flex">
                                                    {{ $primaryAction['label'] }}
                                                </a>
                                            @endif
                                        </x-ui.card-header>

                                        <x-ui.card-content class="grid gap-3 pt-0 md:grid-cols-3">
                                            @foreach (array_slice($dashboardHighlights, 0, 3) as $highlight)
                                                <a href="{{ $highlight['href'] }}"
                                                    class="group rounded-[22px] border border-[#e6ddd5] bg-[#fbf8f5] p-4 transition hover:border-[#d7c5b9] hover:bg-white">
                                                    <div class="flex items-start justify-between gap-3">
                                                        <p class="text-sm font-semibold text-[#4f3836]">
                                                            {{ $highlight['label'] }}
                                                        </p>
                                                        <span
                                                            class="rounded-full border border-[#ead9cf] bg-white px-2.5 py-1 text-xs font-semibold tabular-nums text-[#7A1F2B]">
                                                            {{ $highlight['value'] }}
                                                        </span>
                                                    </div>
                                                    <p class="mt-2 text-sm leading-6 text-[#7b655e]">
                                                        {{ $highlight['hint'] }}
                                                    </p>
                                                    <div
                                                        class="mt-4 inline-flex items-center gap-2 text-xs font-semibold tracking-[0.03em] text-[#7A1F2B]">
                                                        <span>Lihat detail</span>
                                                        <svg class="h-3.5 w-3.5 transition group-hover:translate-x-0.5"
                                                            viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                                            <path d="M4 10H16M16 10L11 5M16 10L11 15" stroke="currentColor"
                                                                stroke-width="1.6" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                    </div>
                                                </a>
                                            @endforeach
                                        </x-ui.card-content>
                                    </x-ui.card>
                                </div>
                                </div>

                                <div class="min-w-0 xl:col-span-5">
                                <x-ui.card
                                    class="overflow-hidden border-[#e6ddd5] text-[#241818] shadow-[0_16px_34px_rgba(56,35,27,0.05)]">
                                    <x-ui.card-header class="flex flex-row items-end justify-between gap-4">
                                        <div>
                                            <x-ui.card-description
                                                class="text-xs font-semibold tracking-[0.04em] text-[#7b655e]">
                                                Order baru
                                            </x-ui.card-description>
                                            <x-ui.card-title
                                                class="mt-2 text-[1.35rem] font-semibold tracking-[-0.04em] text-[#2c1d1d]">
                                                Pesanan
                                            </x-ui.card-title>
                                        </div>
                                        <a href="{{ route('admin.index', ['focus' => 'pesanan', 'mode' => 'manage']) }}"
                                            class="text-sm font-semibold text-[#7A1F2B] transition hover:text-[#651925]">
                                            Lihat semua
                                        </a>
                                    </x-ui.card-header>

                                    <x-ui.card-content class="space-y-3 pt-0">
                                        @forelse ($recentOrders as $order)
                                            <article
                                                class="rounded-2xl border border-[#e6ddd5] bg-[#fbf8f5] px-4 py-4 sm:px-4.5">
                                                <div class="flex items-start justify-between gap-4">
                                                    <div class="min-w-0 flex-1">
                                                        <p
                                                            class="truncate font-mono text-[12px] font-semibold text-[#56353a] sm:text-[13px]">
                                                            {{ $order['kode'] }}</p>
                                                        <p
                                                            class="mt-1 truncate text-[15px] font-medium text-[#2c1d1d]">
                                                            {{ $order['pelanggan'] }}</p>
                                                    </div>
                                                    <span
                                                        class="{{ $statusPill($order['status']) }} shrink-0 rounded-full px-2.5 py-1 text-[11px] font-semibold">
                                                        {{ $order['status'] }}
                                                    </span>
                                                </div>
                                                <div
                                                    class="mt-4 flex items-end justify-between gap-4 text-[12px] text-[#8b746d] sm:text-sm">
                                                    <span
                                                        class="tabular-nums text-[15px] font-semibold text-[#2c1d1d]">{{ $order['total'] }}</span>
                                                    <span
                                                        class="shrink-0 text-right">{{ $order['created_at'] }}</span>
                                                </div>
                                            </article>
                                        @empty
                                            <div
                                                class="rounded-2xl border border-dashed border-[#ddcec1] bg-[#fbf8f5] p-4 text-sm text-[#8b746d]">
                                                Belum ada order baru.
                                            </div>
                                        @endforelse
                                    </x-ui.card-content>
                                </x-ui.card>
                                </div>

                                <div class="min-w-0 xl:col-span-12">
                                    <x-ui.card class="overflow-hidden border-[#e6ddd5] shadow-[0_16px_34px_rgba(56,35,27,0.05)]">
                                        <x-ui.card-header class="flex flex-row items-end justify-between gap-4 border-b border-[#efe7e0] px-5 py-4 sm:px-6">
                                            <div>
                                                <x-ui.card-description class="text-xs font-semibold tracking-[0.04em] text-[#7b655e]">Order</x-ui.card-description>
                                                <x-ui.card-title class="mt-1 text-xl font-semibold tracking-[-0.03em] text-[#2c1d1d]">Status order</x-ui.card-title>
                                            </div>
                                            <span class="text-xs font-medium text-[#8b746d]">{{ collect($statusBreakdown)->sum('count') }} order</span>
                                        </x-ui.card-header>

                                        <x-ui.card-content class="grid divide-y divide-[#efe7e0] p-0 md:grid-cols-2 md:divide-x md:divide-y-0 xl:grid-cols-5">
                                            @foreach ($statusBreakdown as $status)
                                                @php($statusVisual = $statusToneMeta($status['label']))
                                                <div class="min-w-0 px-5 py-4 sm:px-6">
                                                    <div class="flex items-center justify-between gap-3">
                                                        <span class="truncate text-sm font-medium text-[#3f2e2a]">{{ $status['label'] }}</span>
                                                        <span class="{{ $statusVisual['badge'] }} shrink-0 rounded-full px-2 py-0.5 text-xs font-semibold tabular-nums">{{ $status['count'] }}</span>
                                                    </div>
                                                    <div class="mt-3 flex h-2 w-full overflow-hidden rounded-full bg-[#efe7e0]"
                                                        role="progressbar"
                                                        aria-valuenow="{{ min(100, max(6, $status['percentage'])) }}"
                                                        aria-valuemin="0" aria-valuemax="100"
                                                        title="{{ $status['label'] }}: {{ $status['count'] }} pesanan">
                                                        <div class="h-full rounded-full transition-[width] duration-500"
                                                            style="width: {{ min(100, max(6, $status['percentage'])) }}%; background: {{ $statusVisual['bar'] }};"></div>
                                                    </div>
                                                    <p class="mt-2 text-xs text-[#7b655e]">{{ $status['percentage'] }}% dari order</p>
                                                </div>
                                            @endforeach
                                        </x-ui.card-content>
                                    </x-ui.card>
                                </div>
                            </div>
                        </section>
                    @elseif ($focus === 'produk')
                        <section class="mt-6">
                            <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-[1.2fr_0.8fr_0.8fr]">
                                @foreach ($focusMetrics as $metric)
                                    <x-admin.insight-card :label="$metric['label'] ?? 'Produk'" :value="$metric['value'] ?? '-'" :hint="$metric['hint'] ?? null" />
                                @endforeach
                            </div>
                        </section>

                        <div data-table-toolbar class="mb-3 mt-5 flex items-center gap-2 sm:mb-4 sm:mt-6 sm:justify-between">
                            @if ($primaryAction)
                                <a href="{{ $primaryAction['href'] }}"
                                    class="inline-flex shrink-0 items-center justify-center rounded-xl bg-[#7A1F2B] px-3.5 py-2 text-xs font-semibold text-white shadow-[0_6px_12px_rgba(94,23,33,0.14)] transition hover:bg-[#651925] sm:rounded-full sm:px-4 sm:py-2.5 sm:text-sm">
                                    {{ $primaryAction['label'] }}
                                </a>
                            @endif
                            <div class="sm:ml-auto">
                                <x-admin.table-search-form value="{{ request('search') }}" placeholder="Search..." />
                            </div>
                        </div>

                        <section class="mt-6 sm:hidden">
                            <div
                                class="overflow-hidden rounded-[24px] border border-[#e6ddd5] bg-white shadow-[0_14px_28px_rgba(56,35,27,0.05)]">
                                <div
                                    class="grid grid-cols-[minmax(0,1fr)_92px_86px] gap-3 border-b border-[#e6ddd5] bg-[#fbf8f5] px-4 py-3 text-[11px] font-semibold tracking-[0.04em] text-[#7b655e]">
                                    <span>Produk</span>
                                    <span>Harga</span>
                                    <span class="text-right">Aksi</span>
                                </div>
                                @forelse ($focusPreview['rows'] as $row)
                                    <article
                                        class="grid grid-cols-[minmax(0,1fr)_92px_86px] gap-3 border-b border-[#edf2fb] px-4 py-3.5 last:border-b-0">
                                        <div class="flex min-w-0 items-center gap-3">
                                            <div
                                                class="h-12 w-12 shrink-0 overflow-hidden rounded-[16px] bg-[#eef3ff] ring-1 ring-[#dbe5fb]">
                                                <img src="{{ $row['thumbnail_url'] ?? asset('images/hero-1.jpg') }}"
                                                    alt="{{ data_get($row, 'cells.0', 'Produk') }}"
                                                    class="h-full w-full object-cover" loading="lazy">
                                            </div>
                                            <div class="min-w-0">
                                                <p class="truncate text-[13px] font-semibold text-[#17284b]">
                                                    {!! $highlightSearch(data_get($row, 'cells.0', 'Produk')) !!}</p>
                                                <p class="mt-0.5 truncate text-[11px] text-[#6a5854]">
                                                    {!! $highlightSearch(data_get($row, 'cells.1', 'Kategori')) !!}</p>
                                                <p
                                                    class="{{ $statusPill(data_get($row, 'cells.2', 'Mode')) }} mt-1 inline-flex rounded-full px-2.5 py-0.5 text-[10px] font-semibold">
                                                    {{ data_get($row, 'cells.2', 'Mode') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-center">
                                            <p class="text-[13px] font-semibold text-[#56353a] tabular-nums">
                                                {{ data_get($row, 'cells.3', 'Rp 0') }}</p>
                                        </div>
                                        <div class="flex items-center justify-end gap-2">
                                            <div class="flex items-center gap-2">
                                                <a href="{{ $row['edit_href'] ?? route('admin.index', ['focus' => 'produk', 'mode' => 'manage']) }}"
                                                    class="inline-flex h-8 items-center justify-center rounded-full border border-[#e6ddd5] bg-[#fbf8f5] px-3 text-[11px] font-semibold text-[#56353a] transition hover:bg-white"
                                                    aria-label="Edit produk {{ data_get($row, 'cells.0', 'Produk') }}">
                                                    Edit
                                                </a>
                                                <form method="POST"
                                                    action="{{ route('admin.destroy', ['focus' => $focus, 'record' => $row['id']]) }}"
                                                    data-delete-confirm="true">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="inline-flex h-8 items-center justify-center rounded-full border border-rose-200 bg-white px-2.5 text-[11px] font-semibold text-rose-700 transition hover:bg-rose-50"
                                                        aria-label="Hapus produk {{ data_get($row, 'cells.0', 'Produk') }}">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </article>
                                @empty
                                    <div class="px-5 py-8 text-center text-sm text-[#6a5854]">
                                        Belum ada produk untuk ditampilkan.
                                    </div>
                                @endforelse
                            </div>
                        </section>

                        <section
                            class="mt-6 hidden overflow-hidden rounded-2xl border border-[#ded1c7] bg-white shadow-[0_4px_10px_rgba(56,35,27,0.04)] sm:block">
                            <div
                                class="grid grid-cols-[minmax(0,2fr)_190px_140px_170px_170px] gap-4 border-b border-[#e6ddd5] bg-[#fbf8f5] px-6 py-4 text-[11px] font-semibold tracking-[0.04em] text-[#7b655e]">
                                <span>Produk</span>
                                <span>Kategori</span>
                                <span>Mode</span>
                                <span>Harga dasar</span>
                                <span class="text-right">Aksi</span>
                            </div>
                            <div class="divide-y divide-[#efe7e0]">
                                @forelse ($focusPreview['rows'] as $row)
                                    <article
                                        class="grid grid-cols-[minmax(0,2fr)_190px_140px_170px_170px] items-center gap-4 px-6 py-4">
                                        <div class="flex min-w-0 items-center gap-4">
                                            <div
                                                class="h-14 w-14 shrink-0 overflow-hidden rounded-[18px] bg-[#eef3ff] ring-1 ring-[#dbe5fb]">
                                                <img src="{{ $row['thumbnail_url'] ?? asset('images/hero-1.jpg') }}"
                                                    alt="{{ data_get($row, 'cells.0', 'Produk') }}"
                                                    class="h-full w-full object-cover" loading="lazy">
                                            </div>
                                            <div class="min-w-0">
                                                <p class="truncate text-sm font-semibold text-[#17284b]">
                                                    {!! $highlightSearch(data_get($row, 'cells.0', 'Produk')) !!}</p>
                                                <p class="mt-1 truncate text-xs text-[#6a5854]">
                                                    Item katalog aktif untuk workspace admin.
                                                </p>
                                            </div>
                                        </div>
                                        <p class="truncate text-sm text-[#5e4d49]">
                                            {!! $highlightSearch(data_get($row, 'cells.1', 'Kategori')) !!}</p>
                                        <div>
                                            <span
                                                class="{{ $statusPill(data_get($row, 'cells.2', 'Mode')) }} inline-flex rounded-full px-2.5 py-1 text-xs font-semibold">
                                                {{ data_get($row, 'cells.2', 'Mode') }}
                                            </span>
                                        </div>
                                        <p class="text-sm font-semibold text-[#56353a] tabular-nums">
                                            {{ data_get($row, 'cells.3', 'Rp 0') }}</p>
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ $row['edit_href'] ?? route('admin.index', ['focus' => 'produk', 'mode' => 'manage']) }}"
                                                class="inline-flex items-center rounded-full border border-[#e6ddd5] bg-[#fbf8f5] px-3.5 py-2 text-xs font-semibold text-[#56353a] transition hover:bg-white"
                                                aria-label="Edit produk {{ data_get($row, 'cells.0', 'Produk') }}">
                                                Edit
                                            </a>
                                            <form method="POST"
                                                action="{{ route('admin.destroy', ['focus' => $focus, 'record' => $row['id']]) }}"
                                                data-delete-confirm="true">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center rounded-full border border-rose-200 bg-white px-3.5 py-2 text-xs font-semibold text-rose-700 transition hover:bg-rose-50">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </article>
                                @empty
                                    <div class="px-6 py-12 text-center text-sm text-[#6a5854]">
                                        Belum ada produk untuk ditampilkan.
                                    </div>
                                @endforelse
                            </div>
                        </section>

                        @if (method_exists($focusPreview['rows'], 'links'))
                            <x-admin.pagination-summary :paginator="$focusPreview['rows']" label="produk" />
                        @endif
                    @elseif ($focus === 'pesanan')
                        @if (session('admin_status'))
                            <div
                                class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                                {{ session('admin_status') }}
                            </div>
                        @endif

                        <section class="mt-6">
                            <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($statusBreakdown as $status)
                                        <span
                                            class="{{ $statusPill($status['label']) }} inline-flex rounded-full px-4 py-2 text-sm font-semibold shadow-[inset_0_1px_0_rgba(255,255,255,0.65)]">
                                            {{ $status['label'] }}
                                        </span>
                                    @endforeach
                                </div>
                                <p class="text-sm leading-6 text-[#5e4d49]">Filter data berdasarkan tahapan pemrosesan
                                    transaksi aktif.</p>
                            </div>
                        </section>

                        <div data-table-toolbar class="mb-3 mt-5 flex items-center gap-2 sm:mb-4 sm:mt-6 sm:justify-between">
                            @if ($primaryAction)
                                <a href="{{ $primaryAction['href'] }}"
                                    class="inline-flex shrink-0 items-center justify-center rounded-xl bg-[#7A1F2B] px-3.5 py-2 text-xs font-semibold text-white shadow-[0_6px_12px_rgba(94,23,33,0.14)] transition hover:bg-[#651925] sm:rounded-full sm:px-4 sm:py-2.5 sm:text-sm">
                                    {{ $primaryAction['label'] }}
                                </a>
                            @endif
                            <div class="sm:ml-auto">
                                <x-admin.table-search-form value="{{ request('search') }}" placeholder="Search..." />
                            </div>
                        </div>

                        <section class="mt-6 lg:hidden">
                            <div
                                class="overflow-hidden rounded-[24px] border border-[#e6ddd5] bg-white shadow-[0_14px_28px_rgba(56,35,27,0.05)]">
                                <div
                                    class="grid grid-cols-[minmax(0,1fr)_88px_86px] gap-3 border-b border-[#e6ddd5] bg-[#fbf8f5] px-4 py-3 text-[11px] font-semibold tracking-[0.04em] text-[#7b655e]">
                                    <span>Pesanan</span>
                                    <span>Total</span>
                                    <span class="text-right">Status</span>
                                </div>
                                @forelse ($focusPreview['rows'] as $row)
                                    <article
                                        class="grid grid-cols-[minmax(0,1fr)_88px_86px] gap-3 border-b border-[#edf2fb] px-4 py-3.5 last:border-b-0">
                                        <div class="min-w-0">
                                            <p class="truncate font-mono text-[12px] font-semibold text-[#56353a]">
                                                {!! $highlightSearch($row['cells'][0] ?? '-') !!}</p>
                                            <p class="mt-0.5 truncate text-[12px] text-[#5e4d49]">
                                                {!! $highlightSearch($row['cells'][1] ?? '-') !!}</p>
                                        </div>
                                        <div class="flex items-center">
                                            <p class="text-[13px] font-semibold text-[#17284b] tabular-nums">
                                                {{ $row['cells'][3] ?? '-' }}</p>
                                        </div>
                                        <div class="flex items-center justify-end">
                                            <span
                                                class="{{ $statusPill($row['cells'][2] ?? '-') }} inline-flex rounded-full px-2 py-1 text-[10px] font-semibold">
                                                {{ $row['cells'][2] ?? '-' }}
                                            </span>
                                        </div>
                                        @if (!empty($row['status_note']) || !empty($row['quick_action']['buttons']))
                                            <div class="col-span-3 space-y-2 pt-1">
                                                @if (!empty($row['status_note']))
                                                    <p class="text-[11px] leading-5 text-[#7b655e]">
                                                        {{ $row['status_note'] }}
                                                    </p>
                                                @endif
                                                @if (!empty($row['quick_action']['buttons']))
                                                    <div class="flex flex-wrap gap-2">
                                                        @foreach ($row['quick_action']['buttons'] as $button)
                                                            <form method="POST"
                                                                action="{{ route('admin.orders.quick-action', $row['id']) }}">
                                                                @csrf
                                                                <input type="hidden" name="action"
                                                                    value="{{ $button['action'] }}">
                                                                <button type="submit"
                                                                    class="inline-flex items-center rounded-full border px-3 py-1.5 text-[11px] font-semibold transition {{ $button['class'] }}">
                                                                    {{ $button['label'] }}
                                                                </button>
                                                            </form>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </article>
                                @empty
                                    <div class="px-5 py-8 text-center text-sm text-[#6a5854]">Belum ada pesanan.</div>
                                @endforelse
                            </div>
                        </section>

                        <section
                            class="mt-6 overflow-hidden rounded-2xl border border-[#ded1c7] bg-white shadow-[0_4px_10px_rgba(56,35,27,0.04)]">
                            <div>
                                <div
                                    class="hidden grid-cols-[170px_minmax(0,1.3fr)_180px_170px_150px] gap-4 border-b border-[#e6ddd5] bg-[#fbf8f5] px-6 py-4 text-[11px] font-semibold tracking-[0.04em] text-[#7b655e] lg:grid">
                                    <span>{{ $focusPreview['columns'][0] ?? 'Kode' }}</span>
                                    <span>{{ $focusPreview['columns'][1] ?? 'Pelanggan' }}</span>
                                    <span>{{ $focusPreview['columns'][2] ?? 'Status' }}</span>
                                    <span>{{ $focusPreview['columns'][3] ?? 'Total' }}</span>
                                    <span>Aksi</span>
                                </div>
                                <div class="hidden divide-y divide-[#efe7e0] lg:block">
                                    @forelse ($focusPreview['rows'] as $row)
                                        <div
                                            class="grid gap-4 px-6 py-4 lg:grid-cols-[170px_minmax(0,1.3fr)_180px_170px_150px] lg:items-center">
                                            <div>
                                                <p
                                                    class="text-xs font-semibold tracking-[0.04em] text-[#7b655e] lg:hidden">
                                                    {{ $focusPreview['columns'][0] ?? 'Kode' }}</p>
                                                <p class="font-mono text-sm font-semibold text-[#56353a]">
                                                    {!! $highlightSearch($row['cells'][0] ?? '-') !!}</p>
                                            </div>
                                            <div class="min-w-0">
                                                <p
                                                    class="text-xs font-semibold tracking-[0.04em] text-[#7b655e] lg:hidden">
                                                    {{ $focusPreview['columns'][1] ?? 'Pelanggan' }}</p>
                                                <p class="truncate text-sm font-medium text-[#17284b]">
                                                    {!! $highlightSearch($row['cells'][1] ?? '-') !!}</p>
                                            </div>
                                            <div>
                                                <p
                                                    class="text-xs font-semibold tracking-[0.04em] text-[#7b655e] lg:hidden">
                                                    {{ $focusPreview['columns'][2] ?? 'Status' }}</p>
                                                <span
                                                    class="{{ $statusPill($row['cells'][2] ?? '-') }} inline-flex rounded-full px-2.5 py-1 text-xs font-semibold">
                                                    {{ $row['cells'][2] ?? '-' }}
                                                </span>
                                            </div>
                                            <div>
                                                <p
                                                    class="text-xs font-semibold tracking-[0.04em] text-[#7b655e] lg:hidden">
                                                    {{ $focusPreview['columns'][3] ?? 'Total' }}</p>
                                                <p class="text-sm font-semibold text-[#17284b] tabular-nums">
                                                    {{ $row['cells'][3] ?? '-' }}</p>
                                            </div>
                                            <div class="space-y-2">
                                                @if (!empty($row['status_note']))
                                                    <p class="text-[11px] leading-5 text-[#7b655e]">
                                                        {{ $row['status_note'] }}
                                                    </p>
                                                @endif
                                                <div class="flex flex-wrap items-center gap-2">
                                                    @if (!empty($row['quick_action']['buttons']))
                                                        @foreach ($row['quick_action']['buttons'] as $button)
                                                            <form method="POST"
                                                                action="{{ route('admin.orders.quick-action', $row['id']) }}">
                                                                @csrf
                                                                <input type="hidden" name="action"
                                                                    value="{{ $button['action'] }}">
                                                                <button type="submit"
                                                                    class="inline-flex items-center rounded-full border px-3.5 py-2 text-xs font-semibold transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#7A1F2B] {{ $button['class'] }}">
                                                                    {{ $button['label'] }}
                                                                </button>
                                                            </form>
                                                        @endforeach
                                                    @endif

                                                    @if ($canManageData && !empty($row['edit_href']))
                                                        <a href="{{ $row['edit_href'] }}"
                                                            class="inline-flex items-center rounded-full border border-[#e6ddd5] bg-[#fbf8f5] px-3.5 py-2 text-xs font-semibold text-[#56353a] transition hover:bg-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#7A1F2B]">
                                                            Edit
                                                        </a>
                                                    @elseif (empty($row['quick_action']['buttons']))
                                                        <span
                                                            class="inline-flex items-center rounded-full bg-zinc-100 px-3.5 py-2 text-xs font-semibold text-zinc-500">
                                                            Tidak tersedia
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="px-6 py-10 text-sm text-[#6a5854]">Belum ada pesanan.</div>
                                    @endforelse
                                </div>
                            </div>
                        </section>

                        @if (method_exists($focusPreview['rows'], 'links'))
                            <x-admin.pagination-summary :paginator="$focusPreview['rows']" label="pesanan" />
                        @endif
                    @elseif ($focus === 'pelanggan')
                        <section class="mt-6">
                            <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                                <div class="grid gap-2 sm:grid-cols-3 xl:min-w-[620px] xl:flex-1">
                                    @foreach ($focusMetrics as $metric)
                                        <x-admin.insight-card :label="$metric['label']" :value="is_numeric($metric['value']) ? number_format((float) $metric['value'], fmod((float) $metric['value'], 1.0) === 0.0 ? 0 : 1, ',', '.') : $metric['value']" :hint="$metric['hint'] ?? null" />
                                    @endforeach
                                </div>
                            </div>
                        </section>

                        <div data-table-toolbar class="mb-3 mt-5 flex items-center gap-2 sm:mb-4 sm:mt-6 sm:justify-between">
                            @if ($primaryAction)
                                <a href="{{ $primaryAction['href'] }}"
                                    class="inline-flex shrink-0 items-center justify-center rounded-xl bg-[#7A1F2B] px-3.5 py-2 text-xs font-semibold text-white shadow-[0_6px_12px_rgba(94,23,33,0.14)] transition hover:bg-[#651925] sm:rounded-full sm:px-4 sm:py-2.5 sm:text-sm">
                                    {{ $primaryAction['label'] }}
                                </a>
                            @endif
                            <div class="sm:ml-auto">
                                <x-admin.table-search-form value="{{ request('search') }}" placeholder="Search..." />
                            </div>
                        </div>

                        <section class="mt-6 lg:hidden">
                            <div
                                class="overflow-hidden rounded-[24px] border border-[#e6ddd5] bg-white shadow-[0_14px_28px_rgba(56,35,27,0.05)]">
                                <div
                                    class="grid grid-cols-[minmax(0,1fr)_110px_86px] gap-3 border-b border-[#e6ddd5] bg-[#fbf8f5] px-4 py-3 text-[11px] font-semibold tracking-[0.04em] text-[#7b655e]">
                                    <span>Pelanggan</span>
                                    <span>Kontak</span>
                                    <span class="text-right">Aksi</span>
                                </div>
                                @forelse ($focusPreview['rows'] as $row)
                                    <article
                                        class="grid grid-cols-[minmax(0,1fr)_110px_86px] gap-3 border-b border-[#edf2fb] px-4 py-3.5 last:border-b-0">
                                        <div class="flex min-w-0 items-center gap-3">
                                            <div class="min-w-0">
                                                <p class="truncate text-[13px] font-semibold text-[#17284b]">
                                                    {!! $highlightSearch($row['cells'][0] ?? '-') !!}</p>
                                                <p class="mt-0.5 truncate text-[11px] text-[#6a5854]">
                                                    {!! $highlightSearch($row['cells'][2] ?? '-') !!}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center">
                                            <p class="truncate text-[12px] text-[#5e4d49]">
                                                {!! $highlightSearch($row['cells'][1] ?? '-') !!}</p>
                                        </div>
                                        <div class="flex items-center justify-end">
                                            @if (!empty($row['edit_href']))
                                                <div class="flex items-center gap-2">
                                                    <a href="{{ $row['edit_href'] }}"
                                                        class="inline-flex h-8 items-center justify-center rounded-full border border-[#e6ddd5] bg-[#fbf8f5] px-3 text-[11px] font-semibold text-[#56353a] transition hover:bg-white">
                                                        Edit
                                                    </a>
                                                    @if ($focus !== 'users' || ($row['id'] ?? null) !== ($user?->id ?? null))
                                                        <form method="POST"
                                                            action="{{ route('admin.destroy', ['focus' => $focus, 'record' => $row['id']]) }}"
                                                            data-delete-confirm="true">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="inline-flex h-8 items-center justify-center rounded-full border border-rose-200 bg-white px-2.5 text-[11px] font-semibold text-rose-700 transition hover:bg-rose-50">
                                                                Hapus
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            @else
                                                <span
                                                    class="inline-flex h-8 items-center justify-center rounded-xl border border-zinc-200 px-3 text-[11px] font-semibold text-zinc-500">
                                                    Nihil
                                                </span>
                                            @endif
                                        </div>
                                    </article>
                                @empty
                                    <div class="px-5 py-8 text-center text-sm text-[#6a5854]">Belum ada pelanggan.
                                    </div>
                                @endforelse
                            </div>
                        </section>

                        <section
                            class="mt-6 overflow-hidden rounded-2xl border border-[#ded1c7] bg-white shadow-[0_4px_10px_rgba(56,35,27,0.04)]">
                            <div class="hidden lg:block">
                                <table data-admin-table class="min-w-full table-fixed border-collapse">
                                    <thead>
                                        <tr
                                            class="border-b border-[#e6ddd5] bg-[#f8f4ef] text-left text-[11px] font-semibold tracking-[0.04em] text-[#7b655e]">
                                            <x-admin.sortable-header :label="$focusPreview['columns'][0] ?? 'Pelanggan'" sort-key="name" class="w-[27%] px-6 py-4" />
                                            <x-admin.sortable-header :label="$focusPreview['columns'][1] ?? 'Kontak'" class="w-[18%] px-4 py-4" />
                                            <x-admin.sortable-header :label="$focusPreview['columns'][2] ?? 'Email'" sort-key="email" class="w-[26%] px-4 py-4" />
                                            <x-admin.sortable-header :label="$focusPreview['columns'][3] ?? 'Terdaftar'" sort-key="created_at" class="w-[14%] px-4 py-4" />
                                            <th class="w-[15%] px-4 py-4 text-right">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[#efe7e0]">
                                        @forelse ($focusPreview['rows'] as $row)
                                            <tr data-record-id="{{ $row['id'] }}" class="group transition hover:bg-[#fcfaf8]">
                                                <td class="px-6 py-4 align-middle">
                                                    <div class="flex min-w-0 items-center gap-3">
                                                        <span
                                                            class="inline-flex h-8 w-8 flex-none items-center justify-center rounded-2xl border border-[#efe4da] bg-[#fcf8f4] text-[11px] font-semibold text-[#8b5e3c]">
                                                            {{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                                        </span>
                                                        <div class="min-w-0">
                                                            <p class="truncate text-sm font-semibold text-[#17284b]">
                                                                {!! $highlightSearch($row['cells'][0] ?? '-') !!}
                                                            </p>
                                                            <p class="mt-1 truncate text-xs text-[#8d7770]">
                                                                {!! $highlightSearch($row['cells'][2] ?? '-') !!}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-4 align-middle">
                                                    <p class="truncate text-[13px] text-[#5e4d49]">
                                                        {!! $highlightSearch($row['cells'][1] ?? '-') !!}
                                                    </p>
                                                </td>
                                                <td class="px-4 py-4 align-middle">
                                                    <p class="truncate text-[13px] text-[#5e4d49]">
                                                        {!! $highlightSearch($row['cells'][2] ?? '-') !!}
                                                    </p>
                                                </td>
                                                <td class="px-4 py-4 align-middle">
                                                    <p class="text-[13px] font-medium text-[#17284b] tabular-nums">
                                                        {{ $row['cells'][3] ?? '-' }}
                                                    </p>
                                                </td>
                                                <td class="px-4 py-4 align-middle">
                                                    <div class="flex items-center justify-end gap-2">
                                                        @if (!empty($row['edit_href']))
                                                            <a href="{{ $row['edit_href'] }}"
                                                                class="inline-flex items-center rounded-full border border-[#e6ddd5] bg-[#fbf8f5] px-3.5 py-2 text-xs font-semibold text-[#56353a] transition hover:bg-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#7A1F2B]">
                                                                Edit
                                                            </a>
                                                            @if ($focus !== 'users' || ($row['id'] ?? null) !== ($user?->id ?? null))
                                                                <form method="POST"
                                                                    action="{{ route('admin.destroy', ['focus' => $focus, 'record' => $row['id']]) }}"
                                                                    data-delete-confirm="true">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="inline-flex items-center rounded-full border border-rose-200 bg-white px-3.5 py-2 text-xs font-semibold text-rose-700 transition hover:bg-rose-50">
                                                                        Hapus
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        @else
                                                            <span
                                                                class="inline-flex items-center rounded-full bg-zinc-100 px-3.5 py-2 text-xs font-semibold text-zinc-500">
                                                                Tidak tersedia
                                                            </span>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="px-6 py-10 text-sm text-[#6a5854]">
                                                    Belum ada pelanggan.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </section>

                        @if (method_exists($focusPreview['rows'], 'links'))
                            <x-admin.pagination-summary :paginator="$focusPreview['rows']" label="pelanggan" />
                        @endif
                    @elseif ($focus === 'pembayaran')
                        <section class="mt-6">
                            <div class="grid gap-2.5 xl:hidden">
                                @foreach ($focusMetrics as $metric)
                                    <x-admin.insight-card :label="$metric['label']" :value="is_numeric($metric['value']) ? number_format((float) $metric['value'], fmod((float) $metric['value'], 1.0) === 0.0 ? 0 : 1, ',', '.') : $metric['value']" :hint="$metric['hint'] ?? null" />
                                @endforeach
                            </div>
                            <div class="hidden gap-4 xl:grid xl:grid-cols-3">
                                @foreach ($focusMetrics as $metric)
                                    <x-admin.insight-card :label="$metric['label']" :value="is_numeric($metric['value']) ? number_format((float) $metric['value'], fmod((float) $metric['value'], 1.0) === 0.0 ? 0 : 1, ',', '.') : $metric['value']" :hint="$metric['hint'] ?? null" />
                                @endforeach
                            </div>
                        </section>

                        <div data-table-toolbar class="mb-3 mt-5 flex items-center gap-2 sm:mb-4 sm:mt-6 sm:justify-between">
                            @if ($primaryAction)
                                <a href="{{ $primaryAction['href'] }}"
                                    class="inline-flex shrink-0 items-center justify-center rounded-xl bg-[#7A1F2B] px-3.5 py-2 text-xs font-semibold text-white shadow-[0_6px_12px_rgba(94,23,33,0.14)] transition hover:bg-[#651925] sm:rounded-full sm:px-4 sm:py-2.5 sm:text-sm">
                                    {{ $primaryAction['label'] }}
                                </a>
                            @endif
                            <div class="sm:ml-auto">
                                <x-admin.table-search-form value="{{ request('search') }}" placeholder="Search..." />
                            </div>
                        </div>

                        <section class="mt-6 lg:hidden">
                            <div
                                class="overflow-hidden rounded-[24px] border border-[#e6ddd5] bg-white shadow-[0_14px_28px_rgba(56,35,27,0.05)]">
                                <div
                                    class="grid grid-cols-[minmax(0,1fr)_88px_86px] gap-3 border-b border-[#e6ddd5] bg-[#fbf8f5] px-4 py-3 text-[11px] font-semibold tracking-[0.04em] text-[#7b655e]">
                                    <span>Pembayaran</span>
                                    <span>Nominal</span>
                                    <span class="text-right">Status</span>
                                </div>
                                @forelse ($focusPreview['rows'] as $row)
                                    <article
                                        class="grid grid-cols-[minmax(0,1fr)_88px_86px] gap-3 border-b border-[#edf2fb] px-4 py-3.5 last:border-b-0">
                                        <div class="min-w-0">
                                            <p class="truncate text-[13px] font-semibold text-[#17284b]">
                                                {!! $highlightSearch($row['cells'][0] ?? '-') !!}</p>
                                            <p class="mt-0.5 truncate text-[11px] text-[#6a5854]">
                                                {!! $highlightSearch($row['cells'][1] ?? '-') !!}</p>
                                        </div>
                                        <div class="flex items-center">
                                            <p class="text-[13px] font-semibold text-[#17284b] tabular-nums">
                                                {{ $row['cells'][3] ?? '-' }}</p>
                                        </div>
                                        <div class="flex items-center justify-end">
                                            <span
                                                class="{{ $statusPill($row['cells'][2] ?? '-') }} inline-flex rounded-full px-2 py-1 text-[10px] font-semibold">
                                                {{ $row['cells'][2] ?? '-' }}
                                            </span>
                                        </div>
                                    </article>
                                @empty
                                    <div class="px-5 py-8 text-center text-sm text-[#6a5854]">Belum ada pembayaran.
                                    </div>
                                @endforelse
                            </div>
                        </section>

                        <section
                            class="mt-6 overflow-hidden rounded-2xl border border-[#ded1c7] bg-white shadow-[0_4px_10px_rgba(56,35,27,0.04)]">
                            <div class="hidden lg:block">
                                <table data-admin-table class="min-w-full table-fixed border-collapse">
                                    <thead>
                                        <tr
                                            class="border-b border-[#e6ddd5] bg-[#f8f4ef] text-left text-[11px] font-semibold tracking-[0.04em] text-[#7b655e]">
                                            @foreach ($focusPreview['columns'] as $column)
                                                <x-admin.sortable-header :label="$column" :sort-key="$tableSortKey($column)" class="px-6 py-4" />
                                            @endforeach
                                            <th class="w-[112px] px-6 py-4 text-right">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[#efe7e0]">
                                        @forelse ($focusPreview['rows'] as $row)
                                            <tr data-record-id="{{ $row['id'] }}" class="group transition hover:bg-[#fcfaf8]">
                                                @foreach ($row['cells'] ?? [] as $index => $cell)
                                                    <td class="px-6 py-4 align-middle">
                                                        @if ($index === 0)
                                                            <div class="flex min-w-0 items-center gap-3">
                                                                <span
                                                                    class="inline-flex h-8 w-8 flex-none items-center justify-center rounded-2xl border border-[#efe4da] bg-[#fcf8f4] text-[11px] font-semibold text-[#8b5e3c]">
                                                                    {{ str_pad((string) $loop->parent->iteration, 2, '0', STR_PAD_LEFT) }}
                                                                </span>
                                                                <p class="truncate text-sm font-semibold text-[#17284b]">
                                                                    {!! $highlightSearch($cell) !!}
                                                                </p>
                                                            </div>
                                                        @elseif ($index === 2)
                                                            <span
                                                                class="{{ $statusPill($cell) }} inline-flex rounded-full px-2.5 py-1 text-[11px] font-semibold">
                                                                {{ $cell }}
                                                            </span>
                                                        @else
                                                            <p
                                                                class="truncate text-[13px] {{ $index === 3 ? 'font-semibold text-[#2c1d1d] tabular-nums' : 'text-[#5e4d49]' }}">
                                                                {!! $highlightSearch($cell) !!}
                                                            </p>
                                                        @endif
                                                    </td>
                                                @endforeach
                                                <td class="px-6 py-4 align-middle">
                                                    <div class="flex items-center justify-end gap-2">
                                                        @if (!empty($row['edit_href']))
                                                            <a href="{{ $row['edit_href'] }}"
                                                                class="inline-flex items-center rounded-full border border-[#e6ddd5] bg-[#fbf8f5] px-3.5 py-2 text-xs font-semibold text-[#56353a] transition hover:bg-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#7A1F2B]">
                                                                Edit
                                                            </a>
                                                        @else
                                                            <span
                                                                class="inline-flex items-center rounded-full bg-zinc-100 px-3.5 py-2 text-xs font-semibold text-zinc-500">
                                                                Tidak tersedia
                                                            </span>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="px-6 py-10 text-sm text-[#6a5854]">
                                                    Belum ada pembayaran.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </section>

                        @if (method_exists($focusPreview['rows'], 'links'))
                            <x-admin.pagination-summary :paginator="$focusPreview['rows']" label="pembayaran" />
                        @endif
                    @else
                        @php($showTableActions = $focusPreview['actions'] ?? true)
                        <section class="mt-6">
                            <div class="grid gap-2.5 xl:hidden">
                                @foreach ($focusMetrics as $metric)
                                    <x-admin.insight-card :label="$metric['label']" :value="is_numeric($metric['value']) ? number_format((float) $metric['value'], fmod((float) $metric['value'], 1.0) === 0.0 ? 0 : 1, ',', '.') : $metric['value']" :hint="$metric['hint'] ?? null" />
                                @endforeach
                            </div>
                            <div class="hidden gap-4 xl:grid xl:grid-cols-3">
                                @foreach ($focusMetrics as $metric)
                                    <x-admin.insight-card :label="$metric['label']" :value="is_numeric($metric['value']) ? number_format((float) $metric['value'], fmod((float) $metric['value'], 1.0) === 0.0 ? 0 : 1, ',', '.') : $metric['value']" :hint="$metric['hint'] ?? null" />
                                @endforeach
                            </div>
                        </section>

                        <div data-table-toolbar class="mb-3 mt-5 flex items-center gap-2 sm:mb-4 sm:mt-6 sm:justify-between">
                            @if ($primaryAction)
                                <a href="{{ $primaryAction['href'] }}"
                                    class="inline-flex shrink-0 items-center justify-center rounded-xl bg-[#7A1F2B] px-3.5 py-2 text-xs font-semibold text-white shadow-[0_6px_12px_rgba(94,23,33,0.14)] transition hover:bg-[#651925] sm:rounded-full sm:px-4 sm:py-2.5 sm:text-sm">
                                    {{ $primaryAction['label'] }}
                                </a>
                            @endif
                            <div class="sm:ml-auto">
                                <x-admin.table-search-form value="{{ request('search') }}" placeholder="Search..." />
                            </div>
                        </div>

                        <section class="mt-3 lg:hidden">
                            <div class="overflow-hidden rounded-2xl border border-[#ded1c7] bg-white shadow-[0_4px_10px_rgba(56,35,27,0.04)]">
                                @forelse ($focusPreview['rows'] as $row)
                                    <article class="border-b border-[#efe7e0] px-4 py-3.5 last:border-b-0">
                                        <div class="flex items-start justify-between gap-4">
                                            <div class="min-w-0">
                                                <p class="truncate text-sm font-semibold text-[#17284b]">
                                                    {!! $highlightSearch($row['cells'][0] ?? '-') !!}</p>
                                                <p class="mt-1 truncate text-xs text-[#6a5854]">
                                                    {{ $focusPreview['columns'][1] ?? 'Info' }}:
                                                    {!! $highlightSearch($row['cells'][1] ?? '-') !!}
                                                </p>
                                            </div>
                                            @php($summaryCell = $row['cells'][2] ?? ($row['cells'][1] ?? '-'))
                                            @if (in_array(mb_strtolower((string) $summaryCell), ['aktif', 'nonaktif', 'ditampilkan', 'disembunyikan'], true))
                                                <span
                                                    class="{{ $statusPill($summaryCell) }} inline-flex shrink-0 rounded-full px-2.5 py-1 text-[10px] font-semibold">
                                                    {{ $summaryCell }}
                                                </span>
                                            @else
                                                <p class="shrink-0 text-xs font-medium text-[#5e4d49]">{{ $summaryCell }}
                                                </p>
                                            @endif
                                        </div>
                                        @if ($showTableActions)
                                            <div class="mt-3 flex items-center justify-end gap-2 border-t border-[#f0e9e3] pt-3">
                                                @if (!empty($row['edit_href']))
                                                    <a href="{{ $row['edit_href'] }}"
                                                        class="inline-flex h-8 items-center justify-center rounded-lg border border-[#e6ddd5] bg-[#fbf8f5] px-3 text-[11px] font-semibold text-[#56353a] transition hover:bg-white">
                                                        Edit
                                                    </a>
                                                    @if ($focus !== 'users' || ($row['id'] ?? null) !== ($user?->id ?? null))
                                                        <form method="POST"
                                                            action="{{ route('admin.destroy', ['focus' => $focus, 'record' => $row['id']]) }}"
                                                            data-delete-confirm="true">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="inline-flex h-8 items-center justify-center rounded-lg border border-rose-200 bg-white px-3 text-[11px] font-semibold text-rose-700 transition hover:bg-rose-50">
                                                                Hapus
                                                            </button>
                                                        </form>
                                                    @endif
                                                @else
                                                    <span
                                                        class="inline-flex h-8 items-center justify-center rounded-lg border border-zinc-200 px-3 text-[11px] font-semibold text-zinc-500">
                                                        Nihil
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                    </article>
                                @empty
                                    <div class="px-5 py-8 text-center text-sm text-[#6a5854]">Belum ada data untuk
                                        modul ini.</div>
                                @endforelse
                            </div>
                        </section>

                        <section
                            class="mt-6 overflow-hidden rounded-2xl border border-[#ded1c7] bg-white shadow-[0_4px_10px_rgba(56,35,27,0.04)]">
                            <div class="hidden lg:block">
                                <table data-admin-table class="min-w-full table-fixed border-collapse">
                                    <thead>
                                        <tr
                                            class="border-b border-[#e6ddd5] bg-[#f8f4ef] text-left text-[11px] font-semibold tracking-[0.04em] text-[#7b655e]">
                                            @foreach ($focusPreview['columns'] as $column)
                                                <x-admin.sortable-header :label="$column" :sort-key="$tableSortKey($column)" class="px-6 py-4" />
                                            @endforeach
                                            @if ($showTableActions)
                                                <th class="w-[128px] px-6 py-4 text-right">Aksi</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[#efe7e0]">
                                        @forelse ($focusPreview['rows'] as $row)
                                            <tr data-record-id="{{ $row['id'] }}" class="group transition hover:bg-[#fcfaf8]">
                                                @foreach ($row['cells'] ?? [] as $index => $cell)
                                                    <td class="px-6 py-4 align-middle">
                                                        @php($normalizedCell = mb_strtolower(trim((string) $cell)))
                                                        @if ($index === 0)
                                                            <div class="flex min-w-0 items-center gap-3">
                                                                <span
                                                                    class="inline-flex h-8 w-8 flex-none items-center justify-center rounded-2xl border border-[#efe4da] bg-[#fcf8f4] text-[11px] font-semibold text-[#8b5e3c]">
                                                                    {{ str_pad((string) $loop->parent->iteration, 2, '0', STR_PAD_LEFT) }}
                                                                </span>
                                                                <p class="truncate text-sm font-semibold text-[#17284b]">
                                                                    {!! $highlightSearch($cell) !!}
                                                                </p>
                                                            </div>
                                                        @elseif (in_array($normalizedCell, ['aktif', 'nonaktif', 'ditampilkan', 'disembunyikan'], true))
                                                            <span
                                                                class="{{ $statusPill($cell) }} inline-flex rounded-full px-2.5 py-1 text-[11px] font-semibold">
                                                                {{ $cell }}
                                                            </span>
                                                        @else
                                                            <p
                                                                class="truncate text-[13px] {{ $index === 3 ? 'font-medium text-[#2c1d1d] tabular-nums' : 'text-[#5e4d49]' }}">
                                                                {!! $highlightSearch($cell) !!}
                                                            </p>
                                                        @endif
                                                    </td>
                                                @endforeach
                                                @if ($showTableActions)
                                                    <td class="px-6 py-4 align-middle">
                                                        @if ($canManageData && !empty($row['edit_href']))
                                                            <div class="flex items-center justify-end gap-2">
                                                                <a href="{{ $row['edit_href'] }}"
                                                                    class="inline-flex items-center rounded-full border border-[#e6ddd5] bg-[#fbf8f5] px-3.5 py-2 text-xs font-semibold text-[#56353a] transition hover:bg-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#7A1F2B]">
                                                                    Edit
                                                                </a>
                                                                @if ($focus !== 'users' || ($row['id'] ?? null) !== ($user?->id ?? null))
                                                                    <form method="POST"
                                                                        action="{{ route('admin.destroy', ['focus' => $focus, 'record' => $row['id']]) }}"
                                                                        data-delete-confirm="true">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit"
                                                                            class="inline-flex items-center rounded-full border border-rose-200 bg-white px-3.5 py-2 text-xs font-semibold text-rose-700 transition hover:bg-rose-50">
                                                                            Hapus
                                                                        </button>
                                                                    </form>
                                                                @endif
                                                            </div>
                                                        @else
                                                            <div class="flex justify-end">
                                                                <span
                                                                    class="inline-flex items-center rounded-full bg-zinc-100 px-3.5 py-2 text-xs font-semibold text-zinc-500">
                                                                    Tidak tersedia
                                                                </span>
                                                            </div>
                                                        @endif
                                                    </td>
                                                @endif
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="{{ $showTableActions ? count($focusPreview['columns']) + 1 : count($focusPreview['columns']) }}"
                                                    class="px-6 py-10 text-sm text-[#6a5854]">
                                                    Belum ada data untuk modul ini.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </section>

                        @if (method_exists($focusPreview['rows'], 'links'))
                            <x-admin.pagination-summary :paginator="$focusPreview['rows']" label="data" />
                        @endif
                    @endif
