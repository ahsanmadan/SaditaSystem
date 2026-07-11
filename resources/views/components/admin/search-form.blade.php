@props([
    'action',
    'inputId' => 'admin-search',
    'value' => '',
    'placeholder' => 'Cari kategori, produk, pelanggan, atau kode pesanan',
    'class' => '',
    'hiddenFields' => [],
])

<form method="GET" action="{{ $action }}" data-admin-search-form="true" autocomplete="off"
    {{ $attributes->class(['group w-full', $class]) }}>
    @foreach ($hiddenFields as $field => $fieldValue)
        @if (filled($fieldValue))
            <input type="hidden" name="{{ $field }}" value="{{ $fieldValue }}">
        @endif
    @endforeach

    <label for="{{ $inputId }}" class="sr-only">Cari data SaditaSystem</label>
    <div>
        <x-ui.input-group
            class="rounded-[18px] border-[#ddd3cb] bg-white/96 shadow-[0_8px_18px_rgba(74,35,41,0.05)]">
            <x-ui.input-group-addon class="pl-3.5 text-[#8e8290] transition group-focus-within:text-[#7A1F2B]">
                <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                    <path d="M13.75 13.75L16.5 16.5M15.25 9.25A6 6 0 1 1 3.25 9.25A6 6 0 0 1 15.25 9.25Z"
                        stroke="currentColor" stroke-width="1.6" stroke-linecap="round" />
                </svg>
            </x-ui.input-group-addon>

            <x-ui.input-group-input id="{{ $inputId }}"
                type="search"
                name="q"
                value="{{ $value }}"
                placeholder="{{ $placeholder }}"
                aria-label="Search"
                data-search-input="true"
                autocomplete="off"
                autocorrect="off"
                autocapitalize="none"
                spellcheck="false"
                class="h-9 px-2 text-[12px] font-medium placeholder:text-[#9f93a2]" />

            <x-ui.input-group-addon align="inline-end" class="pr-3">
                <x-ui.input-group-text
                    class="rounded-md border border-slate-200 bg-slate-50 px-2 py-1 text-[11px] font-semibold tracking-wide text-[#7b6a61]">
                    Enter
                </x-ui.input-group-text>
                <x-ui.spinner data-search-spinner="true" class="ml-2 hidden h-3.5 w-3.5 text-[#7A1F2B]" />
            </x-ui.input-group-addon>
        </x-ui.input-group>
    </div>

    <button type="submit" data-search-submit="true" class="sr-only" tabindex="-1">
        Cari
    </button>
</form>
