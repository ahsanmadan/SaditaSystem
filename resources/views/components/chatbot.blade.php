{{-- Floating AI Chat Button & Modal --}}
<div id="chatbot-wrapper">
    <!-- Floating Button -->
    <button id="chatbot-toggle"
        class="fixed bottom-6 right-5 z-[70] w-14 h-14 rounded-full shadow-lg flex items-center justify-center transition-all duration-300 hover:scale-110 hover:shadow-2xl active:scale-95 group"
        style="background: linear-gradient(135deg, #7A1F2B, #5e1721);" aria-label="Buka AI Asisten">
        <img id="chatbot-icon-open" src="/images/chatbot-fab-icon.png" alt="Chat"
            class="w-8 h-8 object-contain transition-transform duration-300">
        <svg id="chatbot-icon-close" class="w-6 h-6 text-white hidden transition-transform duration-300" fill="none"
            stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
        {{-- Pulse ring --}}
        <span class="absolute inset-0 rounded-full animate-ping pointer-events-none opacity-30"
            style="background: #7A1F2B;" id="chatbot-ping"></span>
        {{-- Gold ring accent --}}
        <span
            class="absolute inset-0 rounded-full border-2 border-[#E8C87A]/30 pointer-events-none group-hover:border-[#E8C87A]/60 transition-colors duration-300"></span>
    </button>

    <!-- Chat Modal -->
    <div id="chatbot-modal"
        class="fixed bottom-24 right-5 z-[65] w-[calc(100vw-40px)] max-w-[400px] rounded-3xl shadow-2xl overflow-hidden transition-all duration-400 origin-bottom-right scale-0 opacity-0 pointer-events-none"
        style="display:flex; flex-direction:column; height:520px; max-height:75vh; background:#fff; box-shadow: 0 25px 60px rgba(0,0,0,0.3), 0 0 0 1px rgba(255,255,255,0.05);">

        {{-- Header --}}
        <div class="chatbot-header flex items-center gap-3 px-5 py-4 relative overflow-hidden"
            style="flex-shrink:0; background: linear-gradient(135deg, #7A1F2B 0%, #5e1721 60%, #4a1018 100%);">
            {{-- Decorative gold line at top --}}
            <div class="absolute top-0 left-0 right-0 h-[2px]"
                style="background: linear-gradient(90deg, transparent, #E8C87A, transparent);"></div>

            <div class="w-11 h-11 rounded-xl overflow-hidden flex-shrink-0 border border-[#E8C87A]/30"
                style="background: rgba(232,200,122,0.1);">
                <img src="/images/chatbot-avatar.png" alt="Sadita AI" class="w-full h-full object-cover">
            </div>
            <div class="flex-1 min-w-0">
                <div class="font-semibold text-sm text-white tracking-wide"
                    style="font-family:'Playfair Display',serif">Sadita AI</div>
                <div class="flex items-center gap-1.5 mt-0.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-[10px] text-white/60" id="chatbot-status">Online — siap membantu</span>
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

        {{-- Messages --}}
        <div id="chatbot-messages" class="chatbot-messages-area overflow-y-auto px-4 py-5 space-y-4"
            style="flex:1 1 0%; min-height:0; overflow-y:auto; background: linear-gradient(180deg, #FFFDFB 0%, #FFF9F5 100%);">
            {{-- Welcome message --}}
            <div class="chatbot-msg chatbot-msg-bot">
                <div class="chatbot-bubble chatbot-bubble-bot">
                    Halo! 👋 Saya asisten AI Sadita. Ada yang bisa saya bantu? Tanyakan tentang produk, harga, atau cara
                    pesan kami.
                </div>
            </div>
            {{-- Quick action chips --}}
            <div class="flex flex-wrap gap-2 pl-1" id="chatbot-chips">
                <button class="chatbot-chip" data-msg="Produk apa saja yang tersedia?">Produk</button>
                <button class="chatbot-chip" data-msg="Berapa harga papan bunga?">Harga</button>
                <button class="chatbot-chip" data-msg="Bagaimana cara pesan?">Cara Pesan</button>
            </div>
        </div>

        {{-- Input --}}
        <div class="bg-white px-4 py-3 relative" style="flex-shrink:0; border-top: 1px solid rgba(122,31,43,0.08);">
            {{-- Gold accent line --}}
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
                <span class="text-[9px] text-gray-300 tracking-wide">Powered by AI · Sadita System</span>
            </div>
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

        const SYSTEM_PROMPT = `Kamu adalah "Sadita AI", asisten virtual premium untuk Sadita — layanan florist & gift terkemuka di Padang, Sumatera Barat.

## PERAN UTAMA
Kamu adalah KONSULTAN, bukan mesin penjual. Tugasmu:
1. Pahami kebutuhan customer dengan bertanya: acara apa, untuk siapa, budget berapa.
2. Rekomendasikan produk yang PALING COCOK berdasarkan konteks.
3. Bimbing customer sampai mereka yakin ingin pesan.

## DATA PRODUK
- **Papan Ucapan Standard (Outdoor)**: Rp 350.000 – Rp 850.000+ (untuk pembukaan toko, duka cita, pernikahan, dll)
- **Papan Bunga Mini (Portable/Kado)**: Rp 85.000 – Rp 150.000 (cocok untuk wisuda/hadiah, bahan artificial/akrilik)
- **Papan Bunga Kertas/Akrilik Custom**: Rp 135.000 – Rp 250.000 (kado estetik & modern)
- **Sewa Papan Indoor**: mulai dari Rp 100.000 (untuk keperluan foto)
- **Hantaran (Jasa Hias Saja)**: Rp 30.000 – Rp 50.000 / kotak (jika customer bawa box sendiri)
- **Hantaran (Paket Sewa Box + Hias)**: Rp 250.000 – Rp 800.000 / paket (isi 5-8 kotak. Pilihan bahan: Akrilik, Kayu Jati, Rotan)
- **Dekorasi Akad / Intimate (Sederhana)**: Rp 4.000.000 – Rp 10.000.000
- **Dekorasi Pelaminan (Bagonjong Modern / Mewah)**: Rp 10.000.000 – Rp 30.000.000+ (menyesuaikan bahan bunga segar/artificial dan skala gedung/rumah)

## INFO LAYANAN
- Gratis ongkir area Padang
- Menerima custom request (arahkan ke admin: 089653090248)
- Proses cepat & profesional
- Bisa pesan via WhatsApp
- Rating 4.9★ dari 500+ pesanan
- Minimal pemesanan: H-1 (satu hari sebelum acara)
- TIDAK melayani di luar radius area Padang

## ATURAN MENJAWAB
1. Bahasa Indonesia, ramah, hangat, seperti teman curhat.
2. JANGAN langsung dump semua info produk sekaligus. Jawab sesuai pertanyaan saja.
3. Kalau customer bilang "mau pesan" atau bingung pilih → tanya dulu: "Boleh tau ini untuk acara apa, Kak?" atau "Untuk siapa ya, Kak?"
4. Gunakan emoji secukupnya (1-2 per pesan) untuk kesan friendly.
5. Jawab SINGKAT & PADAT, maksimal 3-4 kalimat per pesan kecuali diminta detail.
6. Gunakan **bold** untuk nama produk dan harga.
7. Kalau customer mau custom → berikan nomor admin: **089653090248**
8. Kalau ditanya di luar konteks Sadita → arahkan kembali dengan sopan.
9. JANGAN pernah memberikan informasi yang tidak ada di data di atas. Jika tidak tahu, bilang "Untuk detail lebih lanjut, bisa langsung hubungi admin kami ya, Kak 😊"
10. Jangan gunakan format tabel markdown. Gunakan list biasa saja dengan bullet point.`;

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

        // Close chatbot when clicking outside
        document.addEventListener('click', function(event) {
            if (chatOpen && !modal.contains(event.target) && !toggle.contains(event.target)) {
                toggleChat();
            }
        });

        // Quick action chips
        document.querySelectorAll('.chatbot-chip').forEach(chip => {
            chip.addEventListener('click', function() {
                input.value = this.dataset.msg;
                sendBtn.disabled = false;
                form.dispatchEvent(new Event('submit'));
                // Hide chips after use
                const chipsContainer = document.getElementById('chatbot-chips');
                if (chipsContainer) {
                    chipsContainer.style.opacity = '0';
                    chipsContainer.style.transform = 'translateY(-8px)';
                    setTimeout(() => chipsContainer.remove(), 300);
                }
            });
        });

        // Auto-resize textarea
        input.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 96) + 'px';
            sendBtn.disabled = !this.value.trim();
        });

        // Focus styling
        input.addEventListener('focus', function() {
            this.style.borderColor = 'rgba(122,31,43,0.3)';
            this.style.boxShadow = '0 0 0 3px rgba(122,31,43,0.08)';
        });
        input.addEventListener('blur', function() {
            this.style.borderColor = 'rgba(122,31,43,0.1)';
            this.style.boxShadow = 'none';
        });

        // Enter to send (Shift+Enter for newline)
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                if (this.value.trim()) form.dispatchEvent(new Event('submit'));
            }
        });

        function getTimeString() {
            return new Date().toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        // Simple markdown parser for bot responses
        function parseMarkdown(text) {
            // Escape HTML first to prevent XSS
            let html = text
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;');

            // Bold: **text**
            html = html.replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>');
            // Italic: *text* (but not inside bold)
            html = html.replace(/(?<!\*)\*(?!\*)(.+?)(?<!\*)\*(?!\*)/g, '<em>$1</em>');

            // Unordered lists: lines starting with - or •
            html = html.replace(/^[\-•]\s+(.+)$/gm, '<li>$1</li>');
            html = html.replace(/((?:<li>.*<\/li>\n?)+)/g, '<ul class="chatbot-list">$1</ul>');

            // Numbered lists: lines starting with 1. 2. etc
            html = html.replace(/^\d+\.\s+(.+)$/gm, '<li>$1</li>');
            // Wrap consecutive <li> not already in <ul> into <ol>
            html = html.replace(/(?<!<\/ul>\n?)((?:<li>.*<\/li>\n?)+)/g, function(match) {
                if (match.includes('chatbot-list')) return match;
                return '<ol class="chatbot-list chatbot-ol">' + match + '</ol>';
            });

            // Line breaks
            html = html.replace(/\n/g, '<br>');
            // Clean up <br> inside lists
            html = html.replace(/<br><li>/g, '<li>');
            html = html.replace(/<\/li><br>/g, '</li>');
            html = html.replace(/<br>(<\/?[uo]l)/g, '$1');
            html = html.replace(/(<\/[uo]l>)<br>/g, '$1');

            return html;
        }

        function appendMessage(text, isBot) {
            // Remove chips on first user message
            const chips = document.getElementById('chatbot-chips');
            if (chips && !isBot) {
                chips.style.opacity = '0';
                setTimeout(() => chips.remove(), 200);
            }

            const wrapper = document.createElement('div');
            wrapper.className = 'chatbot-msg ' + (isBot ? 'chatbot-msg-bot' : 'chatbot-msg-user');

            const bubble = document.createElement('div');
            bubble.className = 'chatbot-bubble ' + (isBot ? 'chatbot-bubble-bot' : 'chatbot-bubble-user');

            if (isBot) {
                bubble.innerHTML = parseMarkdown(text);
            } else {
                bubble.textContent = text;
            }

            const time = document.createElement('div');
            time.className = 'chatbot-time ' + (isBot ? 'chatbot-time-bot' : 'chatbot-time-user');
            time.textContent = getTimeString();

            wrapper.appendChild(bubble);
            wrapper.appendChild(time);
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
