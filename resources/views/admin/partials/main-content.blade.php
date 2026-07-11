                    @if ($focus === 'dashboard')
                        <section class="mt-6 grid min-w-0 grid-cols-1 gap-6 xl:grid-cols-[minmax(0,1fr)_390px] xl:items-start">
                            <div class="min-w-0 space-y-6">
                                <x-ui.card
                                    x-data="{ range: '{{ $defaultOmzetRange ?? '30d' }}', showScale: false }"
                                    class="relative overflow-hidden border-[#e6ddd5] shadow-[0_16px_34px_rgba(56,35,27,0.05)]">
                                    <x-ui.card-header
                                        class="flex flex-col gap-3 border-b border-slate-100 px-4 pb-3 pt-4 pr-14 sm:flex-row sm:items-center sm:justify-between sm:px-6 sm:pb-4 sm:pt-6">
                                        <div class="min-w-0">
                                            <x-ui.card-title class="text-lg font-semibold text-slate-900">
                                                Pergerakan pendapatan
                                            </x-ui.card-title>
                                            <x-ui.card-description class="mt-1 text-xs text-slate-500">
                                                Tren omzet operasional
                                            </x-ui.card-description>
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

                                    <x-ui.button type="button" variant="outline" size="icon"
                                        @click="showScale = !showScale; $dispatch('toggle-chart-scale', showScale)"
                                        x-bind:aria-expanded="showScale.toString()"
                                        x-bind:aria-label="showScale ? 'Sembunyikan skala omzet' : 'Tampilkan skala omzet'"
                                        class="absolute right-3 top-3 z-20 h-8 w-8 rounded-lg border-slate-200 bg-white/95 shadow-sm sm:hidden">
                                        <svg x-show="!showScale" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" aria-hidden="true">
                                            <path d="m9 18 6-6-6-6" />
                                        </svg>
                                        <svg x-cloak x-show="showScale" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" aria-hidden="true">
                                            <path d="m15 18-6-6 6-6" />
                                        </svg>
                                    </x-ui.button>

                                    <x-ui.card-content class="px-3 pb-4 pt-3 sm:px-6 sm:pb-6 sm:pt-6">
                                        <div class="relative">
                                            <aside x-cloak x-show="showScale"
                                                x-transition:enter="transition ease-out duration-200"
                                                x-transition:enter-start="translate-x-3 opacity-0"
                                                x-transition:enter-end="translate-x-0 opacity-100"
                                                x-transition:leave="transition ease-in duration-150"
                                                x-transition:leave-start="translate-x-0 opacity-100"
                                                x-transition:leave-end="translate-x-3 opacity-0"
                                                class="pointer-events-none absolute inset-y-2 right-0 z-0 w-[68px] border-l border-slate-100 bg-white/90 backdrop-blur-sm">
                                                <span class="absolute right-2 top-2 text-[9px] font-semibold text-slate-400">RP</span>
                                            </aside>
                                            <div id="revenue-line-chart"
                                                class="relative z-10 min-h-[286px] w-full sm:min-h-[320px]"
                                                aria-label="Grafik tren omzet operasional"></div>
                                        </div>

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
                                                Belum ada omset tercatat. Grafik akan bergerak otomatis setelah transaksi
                                                masuk.
                                            </div>
                                        @endunless
                                    </x-ui.card-content>
                                </x-ui.card>

                                <x-ui.card
                                    class="overflow-hidden rounded-[28px] border-[#e6ddd5] shadow-[0_16px_34px_rgba(56,35,27,0.05)]">
                                    <x-ui.card-header>
                                        <x-ui.card-title
                                            class="text-[1.35rem] font-semibold tracking-[-0.04em] text-[#2c1d1d]">
                                            Pelanggan teratas
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

                            </div>

                            <aside class="min-w-0 space-y-6">
                                <x-ui.card
                                    class="overflow-hidden border-[#e6ddd5] text-[#241818] shadow-[0_16px_34px_rgba(56,35,27,0.05)]">
                                    <x-ui.card-header class="flex flex-row items-end justify-between gap-4">
                                        <div>
                                            <x-ui.card-description
                                                class="text-xs font-semibold tracking-[0.04em] text-[#7b655e]">
                                                Pesanan terbaru
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
                                                Belum ada pesanan terbaru.
                                            </div>
                                        @endforelse
                                    </x-ui.card-content>
                                </x-ui.card>

                                <x-ui.card
                                    class="overflow-hidden border-[#e6ddd5] p-5 shadow-[0_16px_34px_rgba(56,35,27,0.05)] sm:p-6">
                                    <p class="text-xs font-semibold tracking-[0.04em] text-[#7b655e]">
                                        Distribusi status</p>
                                    <h2 class="mt-2 text-[1.35rem] font-semibold tracking-[-0.04em] text-[#2c1d1d]">
                                        Alur pesanan aktif</h2>

                                    <div class="mt-5 space-y-4">
                                        @foreach ($statusBreakdown as $status)
                                            @php($statusVisual = $statusToneMeta($status['label']))
                                            <div class="rounded-[20px] border border-[#eee2db] bg-[#fcfaf8] p-4">
                                                <div class="mb-2 flex items-center justify-between gap-3">
                                                    <span
                                                        class="text-sm font-medium text-[#3f2e2a]">{{ $status['label'] }}</span>
                                                    <span
                                                        class="{{ $statusVisual['badge'] }} rounded-xl px-2.5 py-1 text-xs font-semibold tabular-nums">
                                                        {{ $status['count'] }}
                                                    </span>
                                                </div>
                                                <div class="flex h-2.5 w-full overflow-hidden rounded-full bg-[#efe7e0]"
                                                    role="progressbar"
                                                    aria-valuenow="{{ min(100, max(6, $status['percentage'])) }}"
                                                    aria-valuemin="0" aria-valuemax="100"
                                                    title="{{ $status['label'] }}: {{ $status['count'] }} pesanan">
                                                    <div class="flex flex-col justify-center overflow-hidden rounded-full text-center text-xs text-white whitespace-nowrap transition-all duration-500"
                                                        style="width: {{ min(100, max(6, $status['percentage'])) }}%; background: linear-gradient(90deg, {{ $statusVisual['bar'] }} 0%, color-mix(in srgb, {{ $statusVisual['bar'] }} 64%, white) 100%);">
                                                    </div>
                                                </div>
                                                <p class="mt-2 text-xs font-medium text-[#7b655e]">
                                                    {{ $status['percentage'] }}% dari antrean aktif</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </x-ui.card>
                            </aside>
                        </section>
                    @elseif ($focus === 'produk')
                        <section class="mt-6">
                            <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-[1.2fr_0.8fr_0.8fr]">
                                <article
                                    class="rounded-[24px] border border-[#e6ddd5] bg-white px-4 py-4 shadow-[0_14px_28px_rgba(56,35,27,0.05)]">
                                    <p class="text-sm font-semibold text-[#5f4742]">
                                        {{ $focusMetrics[0]['label'] ?? 'Produk Aktif' }}</p>
                                    <p
                                        class="mt-2 text-[2rem] font-semibold tracking-[-0.05em] text-[#2c1d1d] tabular-nums">
                                        {{ $focusMetrics[0]['value'] ?? '-' }}</p>
                                    <p class="mt-1 text-[15px] leading-6 text-[#5e4d49]">
                                        {{ $focusMetrics[0]['hint'] ?? 'Produk yang saat ini tampil di katalog publik.' }}
                                    </p>
                                </article>
                                <article
                                    class="rounded-[24px] border border-[#e6ddd5] bg-white px-4 py-4 shadow-[0_14px_28px_rgba(56,35,27,0.05)]">
                                    <p class="text-sm font-semibold text-[#5f4742]">
                                        {{ $focusMetrics[1]['label'] ?? 'Customizable' }}</p>
                                    <p
                                        class="mt-2 text-[1.8rem] font-semibold tracking-[-0.05em] text-[#2c1d1d] tabular-nums">
                                        {{ $focusMetrics[1]['value'] ?? '-' }}</p>
                                    <p class="mt-1 text-[15px] leading-6 text-[#5e4d49]">
                                        {{ $focusMetrics[1]['hint'] ?? 'Produk yang bisa menyesuaikan brief pelanggan.' }}
                                    </p>
                                </article>
                                <article
                                    class="rounded-[24px] border border-[#e6ddd5] bg-white px-4 py-4 shadow-[0_14px_28px_rgba(56,35,27,0.05)]">
                                    <p class="text-sm font-semibold text-[#5f4742]">
                                        {{ $focusMetrics[2]['label'] ?? 'Mode Sewa' }}</p>
                                    <p
                                        class="mt-2 text-[1.8rem] font-semibold tracking-[-0.05em] text-[#2c1d1d] tabular-nums">
                                        {{ $focusMetrics[2]['value'] ?? '-' }}</p>
                                    <p class="mt-1 text-[15px] leading-6 text-[#5e4d49]">
                                        {{ $focusMetrics[2]['hint'] ?? 'Item yang berjalan di mode rental atau sewa.' }}
                                    </p>
                                </article>
                            </div>
                        </section>

                        <div class="mb-4 mt-6 flex justify-end">
                            <x-admin.table-search-form value="{{ request('search') }}" placeholder="Search..." />
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
                            class="mt-6 hidden overflow-hidden rounded-[32px] border border-[#e6ddd5] bg-white shadow-[0_16px_34px_rgba(56,35,27,0.05)] sm:block">
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

                        <div class="mb-4 mt-6 flex justify-end">

                            <x-admin.table-search-form value="{{ request('search') }}" placeholder="Search..." />
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
                                    </article>
                                @empty
                                    <div class="px-5 py-8 text-center text-sm text-[#6a5854]">Belum ada pesanan.</div>
                                @endforelse
                            </div>
                        </section>

                        <section
                            class="mt-6 overflow-hidden rounded-[32px] border border-[#e6ddd5] bg-white shadow-[0_16px_34px_rgba(56,35,27,0.05)]">
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
                                            <div class="flex items-center gap-2">
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
                                        <article
                                            class="rounded-[22px] border border-[#e6ddd5] bg-white px-4 py-3 shadow-[0_14px_28px_rgba(56,35,27,0.05)]">
                                            <p class="text-sm font-semibold text-[#5f4742]">
                                                {{ $metric['label'] }}</p>
                                            <p
                                                class="mt-2 text-[1.45rem] font-semibold tracking-[-0.05em] text-[#2c1d1d] tabular-nums">
                                                {{ is_numeric($metric['value']) ? number_format((float) $metric['value'], fmod((float) $metric['value'], 1.0) === 0.0 ? 0 : 1, ',', '.') : $metric['value'] }}
                                            </p>
                                            <p class="mt-1 text-[14px] leading-5 text-[#5e4d49]">
                                                {{ $metric['hint'] }}
                                            </p>
                                        </article>
                                    @endforeach
                                </div>
                            </div>
                        </section>

                        <div class="mb-4 mt-6 flex justify-end">

                            <x-admin.table-search-form value="{{ request('search') }}" placeholder="Search..." />
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
                            class="mt-6 overflow-hidden rounded-[32px] border border-[#e6ddd5] bg-white shadow-[0_16px_34px_rgba(56,35,27,0.05)]">
                            <div>
                                <div
                                    class="hidden grid-cols-[minmax(0,1.5fr)_170px_240px_150px_150px] gap-4 border-b border-[#e6ddd5] bg-[#fbf8f5] px-6 py-4 text-[11px] font-semibold tracking-[0.04em] text-[#7b655e] lg:grid">
                                    @foreach ($focusPreview['columns'] as $column)
                                        <span>{{ $column }}</span>
                                    @endforeach
                                    <span>Aksi</span>
                                </div>
                                <div class="hidden divide-y divide-[#efe7e0] lg:block">
                                    @forelse ($focusPreview['rows'] as $row)
                                        <div
                                            class="grid gap-4 px-6 py-4 lg:grid-cols-[minmax(0,1.5fr)_170px_240px_150px_150px] lg:items-center">
                                            <div class="flex min-w-0 items-center gap-4">
                                                <div class="min-w-0">
                                                    <p class="truncate text-sm font-semibold text-[#17284b]">
                                                        {!! $highlightSearch($row['cells'][0] ?? '-') !!}</p>
                                                    <p class="mt-1 truncate text-xs text-[#6a5854]">
                                                        {!! $highlightSearch($row['cells'][2] ?? '-') !!}</p>
                                                </div>
                                            </div>
                                            <p class="truncate text-sm text-[#5e4d49]">{!! $highlightSearch($row['cells'][1] ?? '-') !!}
                                            </p>
                                            <p class="truncate text-sm text-[#5e4d49]">{!! $highlightSearch($row['cells'][2] ?? '-') !!}
                                            </p>
                                            <p class="text-sm font-medium text-[#17284b]">
                                                {{ $row['cells'][3] ?? '-' }}</p>
                                            <div>
                                                @if (!empty($row['edit_href']))
                                                    <div class="flex flex-wrap items-center gap-2">
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
                                                    <span
                                                        class="inline-flex items-center rounded-full bg-zinc-100 px-3.5 py-2 text-xs font-semibold text-zinc-500">
                                                        Tidak tersedia
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <div class="px-6 py-10 text-sm text-[#6a5854]">Belum ada pelanggan.</div>
                                    @endforelse
                                </div>
                            </div>
                        </section>

                        @if (method_exists($focusPreview['rows'], 'links'))
                            <x-admin.pagination-summary :paginator="$focusPreview['rows']" label="pelanggan" />
                        @endif
                    @elseif ($focus === 'pembayaran')
                        <section class="mt-6">
                            <div class="grid gap-2.5 xl:hidden">
                                <article
                                    class="rounded-[22px] border border-[#e6ddd5] bg-white px-4 py-4 text-sm text-[#5e4d49] shadow-[0_14px_28px_rgba(56,35,27,0.05)]">
                                    <p class="text-sm font-semibold text-[#5f4742]">
                                        {{ $focusMetrics[0]['label'] ?? '-' }}</p>
                                    <p
                                        class="mt-2 text-[1.8rem] font-semibold tracking-[-0.05em] text-[#2c1d1d] tabular-nums">
                                        {{ isset($focusMetrics[0]['value']) && is_numeric($focusMetrics[0]['value']) ? number_format((float) $focusMetrics[0]['value'], fmod((float) $focusMetrics[0]['value'], 1.0) === 0.0 ? 0 : 1, ',', '.') : $focusMetrics[0]['value'] ?? '-' }}
                                    </p>
                                    <p class="mt-1.5 text-[15px] leading-6 text-[#5e4d49]">
                                        {{ $focusMetrics[0]['hint'] ?? '-' }}</p>
                                </article>
                                <div class="grid grid-cols-2 gap-2.5">
                                    @foreach (array_slice($focusMetrics, 1, 2) as $metric)
                                        <article
                                            class="rounded-[20px] border border-[#e6ddd5] bg-white px-4 py-3.5 text-sm text-[#5e4d49] shadow-[0_14px_28px_rgba(56,35,27,0.05)]">
                                            <p class="text-sm font-semibold text-[#5f4742]">
                                                {{ $metric['label'] }}</p>
                                            <p
                                                class="mt-1.5 text-[1.55rem] font-semibold tracking-[-0.05em] text-[#2c1d1d] tabular-nums">
                                                {{ is_numeric($metric['value']) ? number_format((float) $metric['value'], fmod((float) $metric['value'], 1.0) === 0.0 ? 0 : 1, ',', '.') : $metric['value'] }}
                                            </p>
                                            <p class="mt-1 text-[14px] leading-5 text-[#5e4d49]">
                                                {{ $metric['hint'] }}</p>
                                        </article>
                                    @endforeach
                                </div>
                            </div>
                            <div class="hidden gap-4 xl:grid xl:grid-cols-3">
                                @foreach ($focusMetrics as $metric)
                                    <article
                                        class="rounded-[24px] border border-[#e6ddd5] bg-white p-5 shadow-[0_14px_28px_rgba(56,35,27,0.05)]">
                                        <p class="text-sm font-semibold text-[#5f4742]">
                                            {{ $metric['label'] }}</p>
                                        <p
                                            class="mt-4 text-[1.85rem] font-semibold tracking-[-0.05em] text-[#2c1d1d] tabular-nums">
                                            {{ is_numeric($metric['value']) ? number_format((float) $metric['value'], fmod((float) $metric['value'], 1.0) === 0.0 ? 0 : 1, ',', '.') : $metric['value'] }}
                                        </p>
                                        <p class="mt-2 text-[15px] leading-6 text-[#5e4d49]">{{ $metric['hint'] }}
                                        </p>
                                    </article>
                                @endforeach
                            </div>
                        </section>

                        <div class="mb-4 mt-6 flex justify-end">

                            <x-admin.table-search-form value="{{ request('search') }}" placeholder="Search..." />
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
                            class="mt-6 overflow-hidden rounded-[32px] border border-[#e6ddd5] bg-white shadow-[0_16px_34px_rgba(56,35,27,0.05)]">
                            <div>
                                <div
                                    class="hidden grid-cols-[repeat(4,minmax(0,1fr))_140px] gap-4 border-b border-[#e6ddd5] bg-[#fbf8f5] px-6 py-4 text-[11px] font-semibold tracking-[0.04em] text-[#7b655e] lg:grid">
                                    @foreach ($focusPreview['columns'] as $column)
                                        <span>{{ $column }}</span>
                                    @endforeach
                                    <span>Aksi</span>
                                </div>
                                <div class="hidden divide-y divide-[#efe7e0] lg:block">
                                    @forelse ($focusPreview['rows'] as $row)
                                        <div
                                            class="grid gap-4 px-6 py-4 lg:grid-cols-[repeat(4,minmax(0,1fr))_140px] lg:items-center">
                                            @foreach ($row['cells'] ?? [] as $index => $cell)
                                                <div class="min-w-0">
                                                    <p
                                                        class="text-xs font-semibold tracking-[0.04em] text-[#7b655e] lg:hidden">
                                                        {{ $focusPreview['columns'][$index] }}</p>
                                                    @if ($index === 2)
                                                        <span
                                                            class="{{ $statusPill($cell) }} inline-flex rounded-full px-2.5 py-1 text-xs font-semibold">{{ $cell }}</span>
                                                    @else
                                                        <p
                                                            class="truncate text-sm {{ $index === 3 ? 'font-semibold text-[#2c1d1d] tabular-nums' : 'text-[#5e4d49]' }}">
                                                            {!! $highlightSearch($cell) !!}</p>
                                                    @endif
                                                </div>
                                            @endforeach
                                            <div>
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
                                        </div>
                                    @empty
                                        <div class="px-6 py-10 text-sm text-[#6a5854]">Belum ada pembayaran.</div>
                                    @endforelse
                                </div>
                            </div>
                        </section>

                        @if (method_exists($focusPreview['rows'], 'links'))
                            <x-admin.pagination-summary :paginator="$focusPreview['rows']" label="pembayaran" />
                        @endif
                    @else
                        @php($showTableActions = $focusPreview['actions'] ?? true)
                        <section class="mt-6">
                            <div class="grid gap-2.5 xl:hidden">
                                <article
                                    class="rounded-[22px] border border-[#e6ddd5] bg-white px-4 py-4 text-sm text-[#5e4d49] shadow-[0_14px_28px_rgba(56,35,27,0.05)]">
                                    <p class="text-sm font-semibold text-[#5f4742]">
                                        {{ $focusMetrics[0]['label'] ?? '-' }}</p>
                                    <p
                                        class="mt-2 text-[1.8rem] font-semibold tracking-[-0.05em] text-[#2c1d1d] tabular-nums">
                                        {{ isset($focusMetrics[0]['value']) && is_numeric($focusMetrics[0]['value']) ? number_format((float) $focusMetrics[0]['value'], fmod((float) $focusMetrics[0]['value'], 1.0) === 0.0 ? 0 : 1, ',', '.') : $focusMetrics[0]['value'] ?? '-' }}
                                    </p>
                                    <p class="mt-1.5 text-[15px] leading-6 text-[#5e4d49]">
                                        {{ $focusMetrics[0]['hint'] ?? '-' }}</p>
                                </article>
                                <div class="grid grid-cols-2 gap-2.5">
                                    @foreach (array_slice($focusMetrics, 1, 2) as $metric)
                                        <article
                                            class="rounded-[20px] border border-[#e6ddd5] bg-white px-4 py-3.5 text-sm text-[#5e4d49] shadow-[0_14px_28px_rgba(56,35,27,0.05)]">
                                            <p class="text-sm font-semibold text-[#5f4742]">
                                                {{ $metric['label'] }}</p>
                                            <p
                                                class="mt-1.5 text-[1.55rem] font-semibold tracking-[-0.05em] text-[#2c1d1d] tabular-nums">
                                                {{ is_numeric($metric['value']) ? number_format((float) $metric['value'], fmod((float) $metric['value'], 1.0) === 0.0 ? 0 : 1, ',', '.') : $metric['value'] }}
                                            </p>
                                            <p class="mt-1 text-[14px] leading-5 text-[#5e4d49]">
                                                {{ $metric['hint'] }}</p>
                                        </article>
                                    @endforeach
                                </div>
                            </div>
                            <div class="hidden gap-4 xl:grid xl:grid-cols-3">
                                @foreach ($focusMetrics as $metric)
                                    <article
                                        class="rounded-[24px] border border-[#e6ddd5] bg-white p-5 shadow-[0_14px_28px_rgba(56,35,27,0.05)]">
                                        <p class="text-sm font-semibold text-[#5f4742]">
                                            {{ $metric['label'] }}</p>
                                        <p
                                            class="mt-4 text-[1.85rem] font-semibold tracking-[-0.05em] text-[#2c1d1d] tabular-nums">
                                            {{ is_numeric($metric['value']) ? number_format((float) $metric['value'], fmod((float) $metric['value'], 1.0) === 0.0 ? 0 : 1, ',', '.') : $metric['value'] }}
                                        </p>
                                        <p class="mt-2 text-[15px] leading-6 text-[#5e4d49]">{{ $metric['hint'] }}
                                        </p>
                                    </article>
                                @endforeach
                            </div>
                        </section>

                        <div class="mb-4 mt-6 flex justify-end">

                            <x-admin.table-search-form value="{{ request('search') }}" placeholder="Search..." />
                        </div>

                        <section class="mt-6 lg:hidden">
                            <div
                                class="overflow-hidden rounded-[24px] border border-[#e6ddd5] bg-white shadow-[0_14px_28px_rgba(56,35,27,0.05)]">
                                <div
                                    class="grid {{ $showTableActions ? 'grid-cols-[minmax(0,1fr)_88px_86px]' : 'grid-cols-[minmax(0,1fr)_110px]' }} gap-3 border-b border-[#e6ddd5] bg-[#fbf8f5] px-4 py-3 text-[11px] font-semibold tracking-[0.04em] text-[#7b655e]">
                                    <span>{{ $focusPreview['columns'][0] ?? 'Data' }}</span>
                                    <span>{{ $focusPreview['columns'][2] ?? ($focusPreview['columns'][1] ?? 'Info') }}</span>
                                    @if ($showTableActions)
                                        <span class="text-right">Aksi</span>
                                    @endif
                                </div>
                                @forelse ($focusPreview['rows'] as $row)
                                    <article
                                        class="grid {{ $showTableActions ? 'grid-cols-[minmax(0,1fr)_88px_86px]' : 'grid-cols-[minmax(0,1fr)_110px]' }} gap-3 border-b border-[#edf2fb] px-4 py-3.5 last:border-b-0">
                                        <div class="min-w-0">
                                            <p class="truncate text-[13px] font-semibold text-[#17284b]">
                                                {!! $highlightSearch($row['cells'][0] ?? '-') !!}</p>
                                            <p class="mt-0.5 truncate text-[11px] text-[#6a5854]">
                                                {{ $focusPreview['columns'][1] ?? 'Info' }}:
                                                {!! $highlightSearch($row['cells'][1] ?? '-') !!}
                                            </p>
                                        </div>
                                        <div class="flex items-center">
                                            @php($summaryCell = $row['cells'][2] ?? ($row['cells'][1] ?? '-'))
                                            @if (in_array(mb_strtolower((string) $summaryCell), ['aktif', 'nonaktif', 'ditampilkan', 'disembunyikan'], true))
                                                <span
                                                    class="{{ $statusPill($summaryCell) }} inline-flex rounded-full px-2 py-1 text-[10px] font-semibold">
                                                    {{ $summaryCell }}
                                                </span>
                                            @else
                                                <p class="truncate text-[12px] text-[#5e4d49]">{{ $summaryCell }}
                                                </p>
                                            @endif
                                        </div>
                                        @if ($showTableActions)
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
                                        @endif
                                    </article>
                                @empty
                                    <div class="px-5 py-8 text-center text-sm text-[#6a5854]">Belum ada data untuk
                                        modul ini.</div>
                                @endforelse
                            </div>
                        </section>

                        <section
                            class="mt-6 overflow-hidden rounded-[32px] border border-[#e6ddd5] bg-white shadow-[0_16px_34px_rgba(56,35,27,0.05)]">
                            <div>
                                <div
                                    class="hidden {{ $showTableActions ? 'grid-cols-[repeat(4,minmax(0,1fr))_140px]' : 'grid-cols-[repeat(4,minmax(0,1fr))]' }} gap-4 border-b border-[#e6ddd5] bg-[#fbf8f5] px-6 py-4 text-[11px] font-semibold tracking-[0.04em] text-[#7b655e] lg:grid">
                                    @foreach ($focusPreview['columns'] as $column)
                                        <span>{{ $column }}</span>
                                    @endforeach
                                    @if ($showTableActions)
                                        <span>Aksi</span>
                                    @endif
                                </div>
                                <div class="hidden divide-y divide-[#efe7e0] lg:block">
                                    @forelse ($focusPreview['rows'] as $row)
                                        <div
                                            class="grid gap-4 px-6 py-4 {{ $showTableActions ? 'lg:grid-cols-[repeat(4,minmax(0,1fr))_140px]' : 'lg:grid-cols-[repeat(4,minmax(0,1fr))]' }} lg:items-center">
                                            @foreach ($row['cells'] ?? [] as $index => $cell)
                                                <div class="min-w-0">
                                                    <p
                                                        class="text-xs font-semibold tracking-[0.04em] text-[#7b655e] lg:hidden">
                                                        {{ $focusPreview['columns'][$index] }}</p>
                                                    <p
                                                        class="truncate text-sm {{ $index === 0 ? 'font-semibold text-[#17284b]' : 'text-[#5e4d49]' }}">
                                                        {!! $highlightSearch($cell) !!}</p>
                                                </div>
                                            @endforeach
                                            @if ($showTableActions && !empty($row['edit_href']))
                                                <div class="flex flex-wrap items-center gap-2">
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
                                            @elseif ($showTableActions)
                                                <div class="hidden lg:block"></div>
                                            @endif
                                        </div>
                                    @empty
                                        <div class="px-6 py-10 text-sm text-[#6a5854]">Belum ada data untuk modul ini.
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </section>

                        @if (method_exists($focusPreview['rows'], 'links'))
                            <x-admin.pagination-summary :paginator="$focusPreview['rows']" label="data" />
                        @endif
                    @endif
