<div id="productModal" data-modal-order-base-url="{{ route('order') }}"
    class="fixed inset-0 z-[100] hidden items-center justify-center p-4 opacity-0 transition-opacity duration-150 sm:p-6">

    <button type="button" data-product-modal-close class="absolute inset-0 bg-[#2D1E1E]/55 backdrop-blur-sm" aria-label="Tutup detail produk"></button>

    <div data-product-modal-card
        class="relative z-10 flex max-h-[90vh] w-full max-w-[860px] scale-[0.985] flex-col overflow-hidden rounded-[1.6rem] border border-[#E8DCCE] bg-white shadow-[0_24px_60px_rgba(0,0,0,0.18)] transition-transform duration-150 md:flex-row">

        <button type="button" data-product-modal-close
            class="absolute right-3 top-3 z-20 flex h-10 w-10 items-center justify-center rounded-full bg-white/90 text-[#2D1E1E] transition hover:bg-white hover:text-[#7A1F2B]">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <div class="relative aspect-[4/3.2] w-full overflow-hidden bg-[#F6F0EA] md:h-auto md:w-[44%] md:aspect-auto">
            <img id="modalImg" alt="" class="h-full w-full object-cover object-center">
            <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-black/18 to-transparent md:hidden"></div>
        </div>

        <div class="flex w-full flex-col justify-between p-6 md:w-[56%] md:p-9">
            <div>
                <div class="flex items-center gap-3 text-[10px] font-semibold uppercase tracking-[0.22em] text-[#7A1F2B]/65">
                    <span class="h-px w-6 bg-[#C9A84C]"></span>
                    Detail Produk
                </div>
                <h3 id="modalTitle" class="mt-3 text-[1.85rem] font-semibold leading-[1.02] text-[#2D1E1E] md:text-[2.45rem]"
                    style="font-family:'Playfair Display',serif;"></h3>
                <p id="modalDesc" class="mt-4 max-w-xl text-sm leading-7 text-[#6B5C57] md:text-[15px]"></p>

                <div class="mt-5 grid gap-3 text-sm text-[#5E4D49] sm:grid-cols-2">
                    <div class="rounded-2xl border border-[#EFE4DA] bg-[#FCFAF8] px-4 py-3">
                        Dikerjakan rapi dan siap disesuaikan dengan kebutuhan acara.
                    </div>
                    <div class="rounded-2xl border border-[#EFE4DA] bg-[#FCFAF8] px-4 py-3">
                        Cocok untuk pemesanan cepat maupun konsultasi lebih detail.
                    </div>
                </div>
            </div>

            <div class="mt-7 border-t border-[#EFE4DA] pt-5">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <div class="text-[10px] font-semibold uppercase tracking-[0.2em] text-[#9D8B83]">Mulai dari</div>
                        <div id="modalPrice" class="mt-1 text-[1.9rem] font-bold leading-none text-[#7A1F2B]"></div>
                    </div>
                    <button id="modalOrderBtn"
                        class="inline-flex min-h-11 items-center justify-center gap-2 rounded-full bg-[#7A1F2B] px-6 text-sm font-semibold text-white transition hover:bg-[#651925]">
                        <span id="modalBtnText">Pesan</span>
                        <span id="modalBtnIcon">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
