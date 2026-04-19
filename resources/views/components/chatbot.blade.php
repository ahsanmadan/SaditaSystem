{{-- Floating AI Chat Button & Modal --}}
<div id="chatbot-wrapper">
    <!-- Floating Button -->
    <button id="chatbot-toggle"
        class="fixed bottom-6 right-5 z-[70] w-14 h-14 rounded-full bg-[#7A1F2B] text-white shadow-lg flex items-center justify-center transition-all duration-300 hover:scale-110 hover:shadow-xl hover:bg-[#5e1721] active:scale-95"
        aria-label="Buka AI Asisten">
        <svg id="chatbot-icon-open" class="w-6 h-6 transition-transform duration-300" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
        </svg>
        <svg id="chatbot-icon-close" class="w-6 h-6 hidden transition-transform duration-300" fill="none"
            stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
        {{-- Pulse ring --}}
        <span class="absolute inset-0 rounded-full bg-[#7A1F2B]/30 animate-ping pointer-events-none"
            id="chatbot-ping"></span>
    </button>

    <!-- Chat Modal -->
    <div id="chatbot-modal"
        class="fixed bottom-24 right-5 z-[65] w-[calc(100vw-40px)] max-w-[380px] h-[500px] max-h-[70vh] rounded-2xl bg-white shadow-2xl border border-gray-100 flex-col overflow-hidden transition-all duration-300 origin-bottom-right scale-0 opacity-0 pointer-events-none"
        style="display:flex;">

        {{-- Header --}}
        <div class="flex items-center gap-3 px-4 py-3 bg-[#7A1F2B] text-white flex-shrink-0">
            <div class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center text-sm">🤖</div>
            <div class="flex-1 min-w-0">
                <div class="font-semibold text-sm">Sadita AI Asisten</div>
                <div class="text-[10px] text-white/70" id="chatbot-status">Online — siap membantu</div>
            </div>
            <button id="chatbot-close-btn"
                class="w-8 h-8 rounded-full hover:bg-white/10 flex items-center justify-center transition-colors"
                aria-label="Tutup chat">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Messages --}}
        <div id="chatbot-messages" class="flex-1 overflow-y-auto px-4 py-4 space-y-3 bg-[#FAFAFA]">
            {{-- Welcome message --}}
            <div class="chatbot-msg chatbot-msg-bot">
                <div class="chatbot-bubble chatbot-bubble-bot">
                    Halo! 👋 Saya asisten AI Sadita. Ada yang bisa saya bantu? Tanyakan tentang produk, harga, atau cara
                    pesan kami.
                </div>
            </div>
        </div>

        {{-- Input --}}
        <div class="flex-shrink-0 border-t border-gray-100 bg-white px-3 py-3">
            <form id="chatbot-form" class="flex gap-2 items-end">
                <textarea id="chatbot-input"
                    class="flex-1 resize-none rounded-xl border border-gray-200 px-3 py-2.5 text-sm bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#7A1F2B]/30 focus:border-[#7A1F2B]/50 placeholder-gray-400 max-h-24"
                    rows="1" placeholder="Ketik pesan..." autocomplete="off"></textarea>
                <button type="submit" id="chatbot-send"
                    class="w-10 h-10 rounded-xl bg-[#7A1F2B] text-white flex items-center justify-center flex-shrink-0 hover:bg-[#5e1721] transition-colors disabled:opacity-40 disabled:cursor-not-allowed"
                    disabled>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 19V5m0 0l-7 7m7-7l7 7" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    (function() {
        const GROQ_API_KEY = '{{ env('GROQ_API_KEY', '') }}';
        const GROQ_MODEL = '{{ env('GROQ_MODEL', 'llama-3.3-70b-versatile') }}';

        const toggle = document.getElementById('chatbot-toggle');
        const modal = document.getElementById('chatbot-modal');
        const closeBtn = document.getElementById('chatbot-close-btn');
        const form = document.getElementById('chatbot-form');
        const input = document.getElementById('chatbot-input');
        const sendBtn = document.getElementById('chatbot-send');
        const messages = document.getElementById('chatbot-messages');
        const iconOpen = document.getElementById('chatbot-icon-open');
        const iconClose = document.getElementById('chatbot-icon-close');
        const ping = document.getElementById('chatbot-ping');
        const statusEl = document.getElementById('chatbot-status');
        let chatOpen = false;
        let isTyping = false;

        const SYSTEM_PROMPT = `Kamu adalah asisten AI untuk Sadita, sebuah layanan florist dan hadiah premium di Padang, Sumatera Barat. tugas kamu adalah consultan jadi kalau customer bingung kamu akan menanyakan kamu ini untuk apa dan siapa gendernya apa terus kamu rekomendasikan.

Informasi tentang Sadita:
- Produk: Papan Ucapan (Rp 300rb-500rb), Bucket Bunga (Rp 150rb-350rb), Hantaran (Rp 800rb-2.5jt), Dekorasi (mulai Rp 1jt-8jt)
- Gratis ongkir area Padang
- hantaran itu untuk di pernikahan kaya lamaran gitu
- Tidak menerima pesanan di luar radius kami
- Menerima custom request
- Proses cepat dan profesional
- Bisa pesan via WhatsApp
- Rating 4.9 dari 500+ pesanan

Jawab dengan ramah, singkat, dan dalam Bahasa Indonesia. Jika ditanya hal di luar konteks Sadita, arahkan kembali ke layanan Sadita. Jangan pernah memberikan informasi yang tidak benar.
jika customer ingin custom berikan nomor ini 08XXXXXXXX  dan biar kan customer konsul langsung ke admin`;

        const chatHistory = [{
            role: 'system',
            content: SYSTEM_PROMPT
        }];

        function toggleChat() {
            chatOpen = !chatOpen;
            if (chatOpen) {
                modal.classList.remove('scale-0', 'opacity-0', 'pointer-events-none');
                modal.classList.add('scale-100', 'opacity-100', 'pointer-events-auto');
                iconOpen.classList.add('hidden');
                iconClose.classList.remove('hidden');
                ping.style.display = 'none';
                input.focus();
            } else {
                modal.classList.add('scale-0', 'opacity-0', 'pointer-events-none');
                modal.classList.remove('scale-100', 'opacity-100', 'pointer-events-auto');
                iconOpen.classList.remove('hidden');
                iconClose.classList.add('hidden');
            }
        }

        toggle.addEventListener('click', toggleChat);
        closeBtn.addEventListener('click', toggleChat);

        // Auto-resize textarea
        input.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 96) + 'px';
            sendBtn.disabled = !this.value.trim();
        });

        // Enter to send (Shift+Enter for newline)
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                if (this.value.trim()) form.dispatchEvent(new Event('submit'));
            }
        });

        function appendMessage(text, isBot) {
            const wrapper = document.createElement('div');
            wrapper.className = 'chatbot-msg ' + (isBot ? 'chatbot-msg-bot' : 'chatbot-msg-user');
            const bubble = document.createElement('div');
            bubble.className = 'chatbot-bubble ' + (isBot ? 'chatbot-bubble-bot' : 'chatbot-bubble-user');
            bubble.textContent = text;
            wrapper.appendChild(bubble);
            messages.appendChild(wrapper);
            messages.scrollTop = messages.scrollHeight;
            return bubble;
        }

        function showTyping() {
            const wrapper = document.createElement('div');
            wrapper.className = 'chatbot-msg chatbot-msg-bot';
            wrapper.id = 'chatbot-typing';
            wrapper.innerHTML =
                '<div class="chatbot-bubble chatbot-bubble-bot chatbot-typing"><span></span><span></span><span></span></div>';
            messages.appendChild(wrapper);
            messages.scrollTop = messages.scrollHeight;
        }

        function removeTyping() {
            const t = document.getElementById('chatbot-typing');
            if (t) t.remove();
        }

        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            const text = input.value.trim();
            if (!text || isTyping) return;

            appendMessage(text, false);
            chatHistory.push({
                role: 'user',
                content: text
            });
            input.value = '';
            input.style.height = 'auto';
            sendBtn.disabled = true;
            isTyping = true;
            statusEl.textContent = 'Mengetik...';
            showTyping();

            if (!GROQ_API_KEY) {
                removeTyping();
                appendMessage(
                    'Maaf, API key belum dikonfigurasi. Silakan tambahkan GROQ_API_KEY di file .env Anda.',
                    true);
                isTyping = false;
                statusEl.textContent = 'Online — siap membantu';
                return;
            }

            try {
                const res = await fetch('https://api.groq.com/openai/v1/chat/completions', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + GROQ_API_KEY
                    },
                    body: JSON.stringify({
                        model: GROQ_MODEL,
                        messages: chatHistory,
                        max_tokens: 512,
                        temperature: 0.7
                    })
                });

                const data = await res.json();
                removeTyping();

                if (data.choices && data.choices[0]) {
                    const reply = data.choices[0].message.content;
                    appendMessage(reply, true);
                    chatHistory.push({
                        role: 'assistant',
                        content: reply
                    });
                } else {
                    appendMessage('Maaf, terjadi kesalahan. Silakan coba lagi.', true);
                }
            } catch (err) {
                removeTyping();
                appendMessage('Gagal terhubung ke server. Periksa koneksi Anda.', true);
            }

            isTyping = false;
            statusEl.textContent = 'Online — siap membantu';
        });
    })();
</script>
