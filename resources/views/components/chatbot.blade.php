{{-- Floating AI Chat Button & Modal --}}
<div id="chatbot-wrapper" data-groq-api-key="{{ env('GROQ_API_KEY', '') }}"
    data-groq-model="{{ env('GROQ_MODEL', 'llama-3.3-70b-versatile') }}">
    <button id="chatbot-toggle"
        class="chatbot-toggle-btn fixed bottom-6 right-5 z-[70] h-14 px-5 rounded-full shadow-lg flex items-center justify-center gap-2.5 transition-all duration-300 hover:scale-105 hover:shadow-2xl active:scale-95 group overflow-hidden"
        style="background: linear-gradient(135deg, #7A1F2B, #5e1721);" aria-label="Buka AI Asisten">
        <div class="relative flex items-center justify-center w-7 h-7 flex-shrink-0">
            <svg id="chatbot-icon-open" class="absolute w-6 h-6 text-white transition-transform duration-300"
                fill="currentColor" viewBox="0 0 24 24">
                <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z" />
            </svg>
            <svg id="chatbot-icon-close" class="absolute w-6 h-6 text-white hidden transition-transform duration-300"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </div>
        <span id="chatbot-label"
            class="text-white font-medium text-[14px] tracking-wide whitespace-nowrap transition-all duration-300"
            style="font-family:'Playfair Display',serif">Chat dengan kami</span>
        <span class="absolute inset-0 rounded-full animate-ping pointer-events-none opacity-30"
            style="background: #7A1F2B;" id="chatbot-ping"></span>
        <span
            class="absolute inset-0 rounded-full border-2 border-[#E8C87A]/30 pointer-events-none group-hover:border-[#E8C87A]/60 transition-colors duration-300"></span>
    </button>

    <div id="chatbot-modal"
        class="chatbot-modal-panel fixed bottom-24 right-5 z-[65] w-[calc(100vw-40px)] max-w-[400px] rounded-3xl shadow-2xl overflow-hidden transition-all duration-400 origin-bottom-right scale-0 opacity-0 pointer-events-none"
        style="display:flex; flex-direction:column; height:520px; max-height:75vh; background:#fff; box-shadow: 0 25px 60px rgba(0,0,0,0.3), 0 0 0 1px rgba(255,255,255,0.05);">
        <div class="chatbot-header flex items-center gap-3 px-5 py-4 relative overflow-hidden"
            style="flex-shrink:0; background: linear-gradient(135deg, #7A1F2B 0%, #5e1721 60%, #4a1018 100%);">
            <div class="absolute top-0 left-0 right-0 h-[2px]"
                style="background: linear-gradient(90deg, transparent, #E8C87A, transparent);"></div>

            <div class="w-11 h-11 rounded-xl overflow-hidden flex-shrink-0 border border-[#E8C87A]/30"
                style="background: rgba(232,200,122,0.1);">
                <img src="/images/chatbot-avatar.png" alt="Sadita AI" class="w-full h-full object-cover" loading="lazy"
                    decoding="async" width="44" height="44">
            </div>
            <div class="flex-1 min-w-0">
                <div class="font-semibold text-sm text-white tracking-wide"
                    style="font-family:'Playfair Display',serif">Sadita AI</div>
                <div class="flex items-center gap-1.5 mt-0.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-[10px] text-white/60" id="chatbot-status">Online - siap membantu</span>
                </div>
            </div>
            <button id="chatbot-close-btn"
                class="w-8 h-8 rounded-lg hover:bg-white/10 flex items-center justify-center transition-all duration-200 text-white/60 hover:text-white"
                aria-label="Tutup chat">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div id="chatbot-messages" class="chatbot-messages-area overflow-y-auto px-4 py-5 space-y-4"
            style="flex:1 1 0%; min-height:0; overflow-y:auto; background: linear-gradient(180deg, #FFFDFB 0%, #FFF9F5 100%);">
            <div class="chatbot-msg chatbot-msg-bot">
                <div class="chatbot-bubble chatbot-bubble-bot">
                    Halo! Saya asisten AI Sadita. Ada yang bisa saya bantu? Tanyakan tentang produk, harga, atau cara
                    pesan kami.
                </div>
            </div>
            <div class="flex flex-wrap gap-2 pl-1" id="chatbot-chips">
                <button class="chatbot-chip" data-msg="Produk apa saja yang tersedia?">Produk</button>
                <button class="chatbot-chip" data-msg="Berapa harga papan bunga?">Harga</button>
                <button class="chatbot-chip" data-msg="Bagaimana cara pesan?">Cara Pesan</button>
            </div>
        </div>

        <div class="bg-white px-4 py-3 relative" style="flex-shrink:0; border-top: 1px solid rgba(122,31,43,0.08);">
            <div class="absolute top-0 left-4 right-4 h-[1px]"
                style="background: linear-gradient(90deg, transparent, rgba(232,200,122,0.3), transparent);"></div>
            <form id="chatbot-form" class="flex gap-2.5 items-end">
                <textarea id="chatbot-input"
                    class="chatbot-input-field flex-1 resize-none rounded-xl px-4 py-2.5 text-sm leading-relaxed placeholder-gray-400 max-h-24 transition-all duration-200"
                    rows="1" placeholder="Ketik pesan..." autocomplete="off"
                    style="background: #F8F5F2; border: 1px solid rgba(122,31,43,0.1); outline: none;"></textarea>
                <button type="submit" id="chatbot-send"
                    class="chatbot-send-btn w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 transition-all duration-300 disabled:opacity-30 disabled:cursor-not-allowed disabled:scale-95"
                    style="background: linear-gradient(135deg, #7A1F2B, #5e1721);" disabled>
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                    </svg>
                </button>
            </form>
            <div class="text-center mt-2">
                <span class="text-[9px] text-gray-300 tracking-wide">Powered by AI - Sadita System</span>
            </div>
        </div>
    </div>
</div>
