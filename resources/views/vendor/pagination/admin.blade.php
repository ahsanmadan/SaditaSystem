@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi halaman" class="flex items-center justify-end">
        <div class="inline-flex items-center gap-1.5 rounded-[16px] border border-[#eadfd6] bg-white/96 p-1.5 shadow-[0_10px_22px_rgba(67,34,34,0.06)]">
            @if ($paginator->onFirstPage())
                <span class="inline-flex h-10 w-10 cursor-not-allowed items-center justify-center rounded-[14px] text-[#9aa9c8]" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="M11.75 5.5L7.25 10L11.75 14.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex h-10 w-10 items-center justify-center rounded-[14px] text-[#7A1F2B] transition hover:bg-[#fcf2ec] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#7A1F2B]" aria-label="@lang('pagination.previous')">
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="M11.75 5.5L7.25 10L11.75 14.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="inline-flex h-10 min-w-10 items-center justify-center rounded-[14px] px-2 text-sm font-semibold text-[#8a6d64]">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" class="inline-flex h-10 min-w-10 items-center justify-center rounded-[14px] bg-[linear-gradient(135deg,#7A1F2B,#5E1721)] px-3 text-sm font-semibold text-white shadow-[0_10px_18px_rgba(94,23,33,0.2)]">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="inline-flex h-10 min-w-10 items-center justify-center rounded-[14px] px-3 text-sm font-semibold text-[#6d4546] transition hover:bg-[#fcf2ec] hover:text-[#7A1F2B] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#7A1F2B]" aria-label="Halaman {{ $page }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex h-10 w-10 items-center justify-center rounded-[14px] text-[#7A1F2B] transition hover:bg-[#fcf2ec] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#7A1F2B]" aria-label="@lang('pagination.next')">
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="M8.25 5.5L12.75 10L8.25 14.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            @else
                <span class="inline-flex h-10 w-10 cursor-not-allowed items-center justify-center rounded-[14px] text-[#9aa9c8]" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="M8.25 5.5L12.75 10L8.25 14.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
            @endif
        </div>
    </nav>
@endif
