@props([
    'paginator',
    'label' => 'data',
])

@if ($paginator->hasPages())
    <section class="mt-5 px-1 py-2 sm:px-0">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="min-w-0">
                <p class="text-sm font-semibold text-[#3d2529]">
                    {{ $paginator->firstItem() ?? 0 }}-{{ $paginator->lastItem() ?? 0 }}
                    <span class="font-normal text-[#7a625f]">dari {{ $paginator->total() }} {{ $label }}</span>
                </p>
                <p class="mt-1 text-xs text-[#8a6d64]">
                    Halaman {{ $paginator->currentPage() }} dari {{ $paginator->lastPage() }}
                </p>
            </div>

            <div class="flex items-center xl:hidden">
                <div class="flex w-full items-center gap-2 rounded-[16px] border border-[#eadfd6] bg-white/96 p-1.5 shadow-[0_10px_22px_rgba(67,34,34,0.06)] sm:w-auto">
                    @if ($paginator->onFirstPage())
                        <span class="inline-flex h-10 flex-1 items-center justify-center rounded-[14px] px-3 text-sm font-semibold text-[#9aa9c8] sm:flex-none" aria-disabled="true" aria-label="Halaman sebelumnya">
                            Sebelumnya
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex h-10 flex-1 items-center justify-center rounded-[14px] px-3 text-sm font-semibold text-[#7A1F2B] transition hover:bg-[#fcf2ec] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#7A1F2B] sm:flex-none" aria-label="Halaman sebelumnya">
                            Sebelumnya
                        </a>
                    @endif

                    <span class="inline-flex min-w-[76px] items-center justify-center rounded-[14px] bg-[#fff6ec] px-3 py-2 text-sm font-semibold text-[#3d2529] ring-1 ring-[#eadfd6]">
                        {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
                    </span>

                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex h-10 flex-1 items-center justify-center rounded-[14px] px-3 text-sm font-semibold text-[#7A1F2B] transition hover:bg-[#fcf2ec] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#7A1F2B] sm:flex-none" aria-label="Halaman berikutnya">
                            Berikutnya
                        </a>
                    @else
                        <span class="inline-flex h-10 flex-1 items-center justify-center rounded-[14px] px-3 text-sm font-semibold text-[#9aa9c8] sm:flex-none" aria-disabled="true" aria-label="Halaman berikutnya">
                            Berikutnya
                        </span>
                    @endif
                </div>
            </div>

            <div class="hidden min-w-0 overflow-x-auto scrollbar-hide xl:block">
                {{ $paginator->onEachSide(1)->links('vendor.pagination.admin') }}
            </div>
        </div>
    </section>
@endif

