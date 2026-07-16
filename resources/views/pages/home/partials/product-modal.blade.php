<!-- Product Detail Modal -->
<div id="productModal" data-modal-order-base-url="{{ route('order') }}"
    class="fixed inset-0 z-[100] hidden flex justify-center items-center p-4 sm:p-6 opacity-0 transition-opacity duration-200">

    <div data-product-modal-close class="absolute inset-0 bg-[#2D1E1E]/48"></div>

    <div data-product-modal-card
        class="bg-white w-[95%] sm:w-full max-w-[360px] md:max-w-[750px] lg:max-w-[850px] rounded-[1.5rem] md:rounded-[2rem] shadow-[0_20px_50px_rgba(0,0,0,0.15)] transform scale-95 transition-transform duration-200 ease-out relative z-10 flex flex-col md:flex-row overflow-hidden max-h-[90vh]">

        <button type="button" data-product-modal-close
            class="absolute top-3 right-3 md:top-5 md:right-5 z-20 w-8 h-8 md:w-10 md:h-10 bg-white/80 md:bg-gray-100 rounded-full flex items-center justify-center text-[#2D1E1E] hover:bg-white hover:text-[#7A1F2B] hover:shadow-md transition-all group">
            <svg class="w-4 h-4 md:w-5 md:h-5 transform group-hover:rotate-90 transition-transform duration-300"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12">
                </path>
            </svg>
        </button>

        <div
            class="w-full md:w-[45%] aspect-[4/3] md:aspect-auto md:h-auto relative flex items-center justify-center bg-[#F9F9F9] overflow-hidden group">
            <img id="modalImg" alt=""
                class="w-full h-full md:absolute md:inset-0 object-cover transition-transform duration-700 group-hover:scale-105">
            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent md:hidden"></div>
        </div>

        <div class="w-full md:w-[55%] p-6 md:p-10 flex flex-col justify-center relative bg-white">
            <div
                class="text-[#C9A84C] text-[10px] md:text-xs font-bold uppercase tracking-widest mb-3 md:mb-4 flex items-center gap-2">
                <span class="w-4 md:w-6 h-[1px] bg-[#C9A84C]"></span> Sadita Collection
            </div>
            <h3 id="modalTitle" class="text-xl md:text-3xl font-bold text-[#2D1E1E] mb-3 md:mb-4 leading-tight"
                style="font-family:'Playfair Display',serif"></h3>

            <div class="flex-1 overflow-y-auto pr-2 scrollbar-hide mb-6 md:mb-8">
                <p id="modalDesc"
                    class="text-xs md:text-sm text-gray-500 leading-relaxed line-clamp-4 md:line-clamp-none mb-4 md:mb-6">
                </p>

                <ul class="hidden md:flex flex-col space-y-3 text-xs md:text-sm text-gray-500">
                    <li class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-[#FAF5F0] flex items-center justify-center text-[#C9A84C]">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        Kualitas Premium & Eksklusif
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-[#FAF5F0] flex items-center justify-center text-[#C9A84C]">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        Desain Elegan dan Tahan Lama
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-[#FAF5F0] flex items-center justify-center text-[#C9A84C]">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        Dapat Disesuaikan (Custom)
                    </li>
                </ul>
            </div>

            <div class="mt-auto pt-5 md:pt-6 border-t border-gray-100 flex items-center justify-between">
                <div>
                    <div class="text-[9px] md:text-[10px] text-gray-400 uppercase tracking-widest font-semibold mb-0.5 md:mb-1">
                        Mulai Dari
                    </div>
                    <div id="modalPrice" class="text-lg md:text-2xl font-bold text-[#7A1F2B]"></div>
                </div>
                <button id="modalOrderBtn"
                    class="py-2.5 md:py-3 px-6 md:px-8 bg-[#7A1F2B] hover:bg-[#8A2432] text-white rounded-xl md:rounded-2xl text-xs md:text-sm font-bold uppercase tracking-widest transition-all duration-300 shadow-md hover:shadow-xl hover:-translate-y-0.5 flex items-center justify-center gap-2 group">
                    <span id="modalBtnText">Pesan</span>
                    <span id="modalBtnIcon">
                        <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
