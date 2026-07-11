@props([
    'placeholder' => 'Search...',
    'value' => '',
])

<div class="ml-auto w-full max-w-[13rem]">
    <form method="GET" action="{{ url()->current() }}" data-local-table-search="true">
        <x-ui.input-group class="rounded-[18px] border-[#ddd3cb] bg-white shadow-[0_6px_16px_rgba(74,35,41,0.05)]">
            <x-ui.input-group-input
                type="search"
                name="search"
                value="{{ $value }}"
                placeholder="{{ $placeholder }}"
                autocomplete="off"
                class="h-7.5 px-3 text-[11.5px] font-medium placeholder:text-[#a59aa7]"
            />
            <x-ui.input-group-addon class="pr-3 text-slate-400">
                <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                    <path d="M13.75 13.75L16.5 16.5M15.25 9.25A6 6 0 1 1 3.25 9.25A6 6 0 0 1 15.25 9.25Z"
                        stroke="currentColor" stroke-width="1.6" stroke-linecap="round" />
                </svg>
            </x-ui.input-group-addon>
        </x-ui.input-group>
    </form>
</div>
