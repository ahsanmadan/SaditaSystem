<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Hasil pencarian global - Sadita Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#f2ece7] text-[#241818] antialiased">
    @php
        $activeType = request('type', 'all');
        $elapsedMs = number_format(max(0, (microtime(true) - (float) request()->server('REQUEST_TIME_FLOAT', microtime(true))) * 1000), 2);
        $filters = collect([
            ['key' => 'all', 'label' => 'Semua', 'count' => $total],
            ...collect($groups)->map(fn ($group) => [
                'key' => $group['key'],
                'label' => $group['label'],
                'count' => $group['count'],
            ])->all(),
        ]);
        $visibleGroups = $activeType === 'all'
            ? collect($groups)
            : collect($groups)->filter(fn ($group) => $group['key'] === $activeType)->values();
        $streamItems = $visibleGroups
            ->flatMap(
                fn($group) => collect($group['items'])->map(
                    fn($item) => [
                        ...$item,
                        'group_key' => $group['key'],
                        'group_label' => $group['label'],
                    ],
                ),
            )
            ->values();
        $badgeClasses = [
            'Kategori' => 'border-slate-200 bg-slate-100 text-slate-700',
            'Produk' => 'border-blue-200 bg-blue-50 text-blue-700',
            'Pelanggan' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
            'Pesanan' => 'border-rose-200 bg-rose-50 text-[#7A1F2B]',
        ];
    @endphp

    <div class="mx-auto max-w-[1680px] px-4 py-6 pb-16 sm:px-6 lg:px-8 lg:py-8 lg:pb-16">
        <x-ui.card class="border-[#e6ddd5] bg-white/94 shadow-[0_24px_60px_rgba(74,35,41,0.08)]">
            <x-ui.card-header class="space-y-5 border-b border-[#efe7e0] pb-5">
                <div class="space-y-2">
                    <div>
                        <x-ui.button type="button" variant="outline" class="rounded-lg text-xs"
                            onclick="window.location.href='{{ route('admin.index') }}'">
                            Kembali ke dashboard
                        </x-ui.button>
                    </div>
                    <x-ui.card-title class="text-2xl font-semibold text-[#241818] sm:text-3xl">Hasil pencarian global</x-ui.card-title>
                    <x-ui.card-description class="text-sm text-[#6f5a54]">
                        Temukan kategori, produk, pelanggan, dan pesanan dari satu pencarian.
                    </x-ui.card-description>
                </div>

                <x-admin.search-form :action="route('admin.search')" input-id="global-search" :value="$query"
                    :hidden-fields="['type' => $activeType !== 'all' ? $activeType : null]" />
            </x-ui.card-header>

            <x-ui.card-content class="pt-6">
                <div class="grid min-h-[70vh] gap-6 lg:grid-cols-[260px_minmax(0,1fr)]">
                    <div class="space-y-2">
                        @foreach ($filters as $filter)
                            @php
                                $active = $activeType === $filter['key'];
                            @endphp
                            <a href="{{ route('admin.search', ['q' => $query, 'type' => $filter['key']]) }}"
                                class="{{ $active ? 'border-slate-900 bg-slate-900 text-white shadow-sm' : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50' }} flex items-center justify-between rounded-2xl border px-4 py-3 text-sm font-medium transition">
                                <span>{{ $filter['label'] }}</span>
                                <span
                                    class="{{ $active ? 'bg-white/15 text-white' : 'bg-slate-100 text-slate-600' }} rounded-full px-2 py-0.5 text-[11px] font-semibold">
                                    {{ $filter['count'] }}
                                </span>
                            </a>
                        @endforeach
                    </div>

                    <div class="flex min-h-[70vh] flex-col">
                        <x-ui.card class="flex min-h-[70vh] flex-col border-[#ece3dc] shadow-none">
                            <x-ui.card-header class="space-y-3 pb-4">
                                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                    <div class="text-sm text-slate-500">
                                        {{ $streamItems->count() }} hasil ditemukan ({{ $elapsedMs }} ms)
                                    </div>
                                    @if ($activeType !== 'all')
                                        <x-ui.badge variant="outline" class="rounded-full px-2.5 py-1 text-[11px] font-semibold">
                                            Filter: {{ $filters->firstWhere('key', $activeType)['label'] ?? ucfirst($activeType) }}
                                        </x-ui.badge>
                                    @endif
                                </div>
                                <x-ui.separator />
                            </x-ui.card-header>

                            <x-ui.card-content class="flex flex-1 flex-col pt-0">
                                @forelse ($streamItems as $item)
                                    <article class="py-5 first:pt-1">
                                        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                                            <div class="min-w-0 space-y-2.5">
                                                <div class="flex flex-wrap items-center gap-2">
                                                    <h3 class="truncate text-base font-semibold tracking-[-0.02em] text-slate-900">
                                                        {{ $item['title'] }}
                                                    </h3>
                                                    <span
                                                        class="inline-flex items-center rounded-full border px-2 py-0.5 text-[10px] font-medium tracking-wide {{ $badgeClasses[$item['badge']] ?? 'border-slate-200 bg-slate-100 text-slate-700' }}">
                                                        {{ $item['badge'] }}
                                                    </span>
                                                    @if (filled($item['status']))
                                                        <x-ui.badge variant="outline" class="rounded-full px-2 py-0.5 text-[10px] font-medium">
                                                            {{ $item['status'] }}
                                                        </x-ui.badge>
                                                    @endif
                                                </div>

                                                <p class="text-sm leading-6 text-slate-500">
                                                    {{ $item['description'] }}
                                                </p>

                                                <div class="flex flex-wrap items-center gap-3 text-xs text-[#6f5a54]">
                                                    <span>{{ $item['identifier'] }}</span>
                                                    <span class="inline-flex h-1 w-1 rounded-full bg-slate-300"></span>
                                                    <span>{{ $item['group_label'] }}</span>
                                                </div>
                                            </div>

                                            <div class="shrink-0">
                                                <x-ui.button type="button" variant="outline" class="rounded-lg text-xs"
                                                    onclick="window.location.href='{{ $item['url'] }}'">
                                                    {{ $item['action_label'] }}
                                                </x-ui.button>
                                            </div>
                                        </div>
                                    </article>

                                    @if (! $loop->last)
                                        <x-ui.separator class="my-0" />
                                    @endif
                                @empty
                                    <div class="flex min-h-[150px] flex-1 items-center justify-center text-center">
                                        <div>
                                            <p class="text-sm font-medium text-slate-700">Data tidak ditemukan.</p>
                                            <p class="mt-1 text-sm text-slate-500">Coba kata kunci lain yang lebih spesifik.</p>
                                        </div>
                                    </div>
                                @endforelse
                            </x-ui.card-content>
                        </x-ui.card>
                    </div>
                </div>
            </x-ui.card-content>
        </x-ui.card>
    </div>
</body>

</html>
