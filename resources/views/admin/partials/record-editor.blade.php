        @if (in_array($mode, ['create', 'edit'], true) && $focus !== 'dashboard')
            <div class="fixed inset-0 z-[90] overflow-y-auto bg-slate-900/50 backdrop-blur-md" aria-modal="true"
                role="dialog" aria-labelledby="record-editor-title">
                <div class="flex min-h-full items-start justify-center px-4 py-6 sm:px-6 sm:py-10">
                    <div id="record-editor"
                        class="relative flex max-h-[calc(100dvh-3rem)] w-full max-w-4xl flex-col overflow-hidden rounded-[28px] border border-[#eadfd6] bg-[linear-gradient(180deg,#fffdfb_0%,#f7f0eb_100%)] shadow-[0_34px_90px_rgba(28,18,20,0.34)] sm:max-h-[calc(100dvh-5rem)]">
                        <div
                            class="flex items-start justify-between gap-4 border-b border-[#eee2db] bg-[linear-gradient(180deg,rgba(255,255,255,0.98),rgba(251,247,243,0.96))] px-6 py-5">
                            <div class="min-w-0">
                                <h2 id="record-editor-title"
                                    class="text-[1.25rem] font-semibold tracking-tight text-[#2d1e1e] sm:text-[1.55rem]">
                                    {{ $formTitle }}</h2>
                                <p class="mt-1 max-w-[32rem] text-sm leading-6 text-[#5e4d49]">
                                    {{ $formSubtitle }}</p>
                            </div>
                            <a href="{{ $manageUrl }}"
                                class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-full border border-[#e5d6cc] bg-white text-[#7A1F2B] transition hover:bg-[#f8f1ec] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#7A1F2B]"
                                aria-label="Tutup editor">
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                    <path d="M5 5L15 15M15 5L5 15" stroke="currentColor" stroke-width="1.7"
                                        stroke-linecap="round" />
                                </svg>
                            </a>
                        </div>

                        <div class="overflow-y-auto px-6 py-6 sm:px-7 sm:py-7">
                            <div class="grid gap-6">
                                <div>
                                    <form method="POST" action="{{ $formAction }}" class="space-y-5"
                                        data-admin-editor-form="true">
                                        @csrf
                                        @if ($isEditing)
                                            @method('PUT')
                                        @endif

                                        @if ($focus === 'kategori')
                                            <section class="{{ $editorSectionClass }}">
                                                <h3 class="text-base font-semibold text-[#17284b]">Informasi
                                                    kategori</h3>
                                                <div class="mt-5 grid gap-4 md:grid-cols-2">
                                                    <div class="max-w-md">
                                                        <label for="nama" class="{{ $editorLabelClass }}">Nama
                                                            kategori</label>
                                                        <input id="nama" name="nama" type="text"
                                                            value="{{ old('nama', $currentRecord?->nama) }}" required
                                                            class="{{ $editorFieldClass }}">
                                                    </div>
                                                    <div class="max-w-md">
                                                        <label for="slug"
                                                            class="{{ $editorLabelClass }}">Slug</label>
                                                        <input id="slug" name="slug" type="text"
                                                            value="{{ old('slug', $currentRecord?->slug) }}" required
                                                            spellcheck="false" class="{{ $editorFieldClass }}">
                                                    </div>
                                                    <div class="max-w-2xl md:col-span-2">
                                                        <label for="deskripsi"
                                                            class="{{ $editorLabelClass }}">Deskripsi</label>
                                                        <textarea id="deskripsi" name="deskripsi" rows="3" class="{{ $editorFieldClass }}">{{ old('deskripsi', $currentRecord?->deskripsi) }}</textarea>
                                                    </div>
                                                    <label class="{{ $editorToggleClass }} max-w-md md:col-span-2">
                                                        <input type="checkbox" name="is_aktif" value="1"
                                                            {{ old('is_aktif', $isEditing ? (int) $currentRecord?->is_aktif : 1) ? 'checked' : '' }}
                                                            class="{{ $editorCheckboxClass }}">
                                                        Status aktif
                                                    </label>
                                                </div>
                                            </section>
                                        @elseif ($focus === 'produk')
                                            <section class="{{ $editorSectionClass }}">
                                                <h3 class="text-base font-semibold text-[#17284b]">Data utama
                                                    produk</h3>
                                                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                                                    <div>
                                                        <label for="kategori_id"
                                                            class="{{ $editorLabelClass }}">Kategori</label>
                                                        <select id="kategori_id" name="kategori_id" required
                                                            class="{{ $editorFieldClass }}">
                                                            <option value="">Pilih kategori</option>
                                                            @foreach ($formOptions['kategori'] as $option)
                                                                <option value="{{ $option->id }}"
                                                                    @selected(old('kategori_id', $currentRecord?->kategori_id) == $option->id)>
                                                                    {{ $option->nama }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @if (!empty($formOptionMeta['kategori']['hint']))
                                                            <p class="mt-1 text-xs leading-5 text-[#7b655e]">
                                                                {{ $formOptionMeta['kategori']['hint'] }}</p>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <label for="harga_dasar"
                                                            class="{{ $editorLabelClass }}">Harga
                                                            dasar</label>
                                                        <input id="harga_dasar" name="harga_dasar" type="number"
                                                            min="0"
                                                            value="{{ old('harga_dasar', $currentRecord?->harga_dasar) }}"
                                                            required class="{{ $editorFieldClass }}">
                                                    </div>
                                                    <div>
                                                        <label for="nama" class="{{ $editorLabelClass }}">Nama
                                                            produk</label>
                                                        <input id="nama" name="nama" type="text"
                                                            value="{{ old('nama', $currentRecord?->nama) }}" required
                                                            class="{{ $editorFieldClass }}">
                                                    </div>
                                                    <div>
                                                        <label for="slug"
                                                            class="{{ $editorLabelClass }}">Slug</label>
                                                        <input id="slug" name="slug" type="text"
                                                            value="{{ old('slug', $currentRecord?->slug) }}" required
                                                            spellcheck="false" class="{{ $editorFieldClass }}">
                                                    </div>
                                                    <div class="sm:col-span-2">
                                                        <label for="deskripsi"
                                                            class="{{ $editorLabelClass }}">Deskripsi</label>
                                                        <textarea id="deskripsi" name="deskripsi" rows="4" class="{{ $editorFieldClass }}">{{ old('deskripsi', $currentRecord?->deskripsi) }}</textarea>
                                                    </div>
                                                    <div class="sm:col-span-2">
                                                        <label for="foto_utama" class="{{ $editorLabelClass }}">Path
                                                            foto
                                                            utama</label>
                                                        <input id="foto_utama" name="foto_utama" type="text"
                                                            value="{{ old('foto_utama', $currentRecord?->foto_utama) }}"
                                                            class="{{ $editorFieldClass }}">
                                                    </div>
                                                    <label class="{{ $editorToggleClass }}">
                                                        <input type="checkbox" name="is_customizable" value="1"
                                                            {{ old('is_customizable', (int) ($currentRecord?->is_customizable ?? 0)) ? 'checked' : '' }}
                                                            class="{{ $editorCheckboxClass }}">
                                                        Bisa custom
                                                    </label>
                                                    <label class="{{ $editorToggleClass }}">
                                                        <input type="checkbox" name="is_sewa" value="1"
                                                            {{ old('is_sewa', (int) ($currentRecord?->is_sewa ?? 0)) ? 'checked' : '' }}
                                                            class="{{ $editorCheckboxClass }}">
                                                        Mode sewa
                                                    </label>
                                                    <label class="{{ $editorToggleClass }} sm:col-span-2">
                                                        <input type="checkbox" name="is_aktif" value="1"
                                                            {{ old('is_aktif', $isEditing ? (int) $currentRecord?->is_aktif : 1) ? 'checked' : '' }}
                                                            class="{{ $editorCheckboxClass }}">
                                                        Produk aktif
                                                    </label>
                                                </div>
                                            </section>
                                        @elseif ($focus === 'promo')
                                            <section class="{{ $editorSectionClass }}">
                                                <h3 class="text-base font-semibold text-[#17284b]">Konfigurasi
                                                    promo</h3>
                                                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                                                    <div>
                                                        <label for="kode" class="{{ $editorLabelClass }}">Kode
                                                            promo</label>
                                                        <input id="kode" name="kode" type="text"
                                                            value="{{ old('kode', $currentRecord?->kode) }}" required
                                                            class="{{ $editorFieldClass }}">
                                                    </div>
                                                    <div>
                                                        <label for="tipe_diskon"
                                                            class="{{ $editorLabelClass }}">Tipe
                                                            diskon</label>
                                                        <select id="tipe_diskon" name="tipe_diskon" required
                                                            class="{{ $editorFieldClass }}">
                                                            <option value="persentase" @selected(old('tipe_diskon', $currentRecord?->tipe_diskon) === 'persentase')>
                                                                Persentase</option>
                                                            <option value="nominal" @selected(old('tipe_diskon', $currentRecord?->tipe_diskon) === 'nominal')>
                                                                Nominal
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label for="nilai_diskon"
                                                            class="{{ $editorLabelClass }}">Nilai
                                                            diskon</label>
                                                        <input id="nilai_diskon" name="nilai_diskon" type="number"
                                                            min="0"
                                                            value="{{ old('nilai_diskon', $currentRecord?->nilai_diskon) }}"
                                                            required class="{{ $editorFieldClass }}">
                                                    </div>
                                                    <div>
                                                        <label for="minimum_order"
                                                            class="{{ $editorLabelClass }}">Minimum
                                                            order</label>
                                                        <input id="minimum_order" name="minimum_order" type="number"
                                                            min="0"
                                                            value="{{ old('minimum_order', $currentRecord?->minimum_order ?? 0) }}"
                                                            class="{{ $editorFieldClass }}">
                                                    </div>
                                                    <div>
                                                        <label for="kuota"
                                                            class="{{ $editorLabelClass }}">Kuota</label>
                                                        <input id="kuota" name="kuota" type="number"
                                                            min="1"
                                                            value="{{ old('kuota', $currentRecord?->kuota) }}"
                                                            class="{{ $editorFieldClass }}">
                                                    </div>
                                                    <div>
                                                        <label for="tanggal_mulai"
                                                            class="{{ $editorLabelClass }}">Tanggal
                                                            mulai</label>
                                                        <input id="tanggal_mulai" name="tanggal_mulai" type="date"
                                                            value="{{ old('tanggal_mulai', $currentRecord?->tanggal_mulai?->format('Y-m-d')) }}"
                                                            class="{{ $editorFieldClass }}">
                                                    </div>
                                                    <div>
                                                        <label for="tanggal_berakhir"
                                                            class="{{ $editorLabelClass }}">Tanggal
                                                            berakhir</label>
                                                        <input id="tanggal_berakhir" name="tanggal_berakhir"
                                                            type="date"
                                                            value="{{ old('tanggal_berakhir', $currentRecord?->tanggal_berakhir?->format('Y-m-d')) }}"
                                                            class="{{ $editorFieldClass }}">
                                                    </div>
                                                    <div class="sm:col-span-2">
                                                        <label for="deskripsi"
                                                            class="{{ $editorLabelClass }}">Deskripsi</label>
                                                        <textarea id="deskripsi" name="deskripsi" rows="4" class="{{ $editorFieldClass }}">{{ old('deskripsi', $currentRecord?->deskripsi) }}</textarea>
                                                    </div>
                                                    <label class="{{ $editorToggleClass }} sm:col-span-2">
                                                        <input type="checkbox" name="is_aktif" value="1"
                                                            {{ old('is_aktif', $isEditing ? (int) $currentRecord?->is_aktif : 1) ? 'checked' : '' }}
                                                            class="{{ $editorCheckboxClass }}">
                                                        Promo aktif
                                                    </label>
                                                </div>
                                            </section>
                                        @elseif ($focus === 'pelanggan')
                                            <section class="{{ $editorSectionClass }}">
                                                <h3 class="text-base font-semibold text-[#17284b]">Kontak
                                                    pelanggan</h3>
                                                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                                                    <div class="sm:col-span-2">
                                                        <label for="nama_lengkap"
                                                            class="{{ $editorLabelClass }}">Nama
                                                            lengkap</label>
                                                        <input id="nama_lengkap" name="nama_lengkap" type="text"
                                                            value="{{ old('nama_lengkap', $currentRecord?->nama_lengkap) }}"
                                                            required class="{{ $editorFieldClass }}">
                                                    </div>
                                                    <div>
                                                        <label for="no_hp" class="{{ $editorLabelClass }}">No.
                                                            HP</label>
                                                        <input id="no_hp" name="no_hp" type="text"
                                                            value="{{ old('no_hp', $currentRecord?->no_hp) }}"
                                                            required class="{{ $editorFieldClass }}">
                                                    </div>
                                                    <div>
                                                        <label for="email"
                                                            class="{{ $editorLabelClass }}">Email</label>
                                                        <input id="email" name="email" type="email"
                                                            value="{{ old('email', $currentRecord?->email) }}"
                                                            class="{{ $editorFieldClass }}">
                                                    </div>
                                                </div>
                                            </section>
                                        @elseif ($focus === 'ulasan')
                                            <section class="{{ $editorSectionClass }}">
                                                <h3 class="text-base font-semibold text-[#17284b]">Input ulasan
                                                </h3>
                                                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                                                    <div>
                                                        <label for="pesanan_id"
                                                            class="{{ $editorLabelClass }}">Pesanan</label>
                                                        <select id="pesanan_id" name="pesanan_id" required
                                                            class="{{ $editorFieldClass }}">
                                                            <option value="">Pilih pesanan</option>
                                                            @foreach ($formOptions['pesanan'] as $option)
                                                                <option value="{{ $option->id }}"
                                                                    @selected(old('pesanan_id', $currentRecord?->pesanan_id) == $option->id)>
                                                                    {{ $option->kode_pesanan }}</option>
                                                            @endforeach
                                                        </select>
                                                        @if (!empty($formOptionMeta['pesanan']['hint']))
                                                            <p class="mt-1 text-xs leading-5 text-[#7b655e]">
                                                                {{ $formOptionMeta['pesanan']['hint'] }}</p>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <label for="produk_id"
                                                            class="{{ $editorLabelClass }}">Produk</label>
                                                        <select id="produk_id" name="produk_id" required
                                                            class="{{ $editorFieldClass }}">
                                                            <option value="">Pilih produk</option>
                                                            @foreach ($formOptions['produk'] as $option)
                                                                <option value="{{ $option->id }}"
                                                                    @selected(old('produk_id', $currentRecord?->produk_id) == $option->id)>
                                                                    {{ $option->nama }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @if (!empty($formOptionMeta['produk']['hint']))
                                                            <p class="mt-1 text-xs leading-5 text-[#7b655e]">
                                                                {{ $formOptionMeta['produk']['hint'] }}</p>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <label for="nama_pengulas"
                                                            class="{{ $editorLabelClass }}">Nama
                                                            pengulas</label>
                                                        <input id="nama_pengulas" name="nama_pengulas" type="text"
                                                            value="{{ old('nama_pengulas', $currentRecord?->nama_pengulas) }}"
                                                            required class="{{ $editorFieldClass }}">
                                                    </div>
                                                    <div>
                                                        <label for="rating"
                                                            class="{{ $editorLabelClass }}">Rating</label>
                                                        <input id="rating" name="rating" type="number"
                                                            min="1" max="5"
                                                            value="{{ old('rating', $currentRecord?->rating ?? 5) }}"
                                                            required class="{{ $editorFieldClass }}">
                                                    </div>
                                                    <div class="sm:col-span-2">
                                                        <label for="komentar"
                                                            class="{{ $editorLabelClass }}">Komentar</label>
                                                        <textarea id="komentar" name="komentar" rows="4" class="{{ $editorFieldClass }}">{{ old('komentar', $currentRecord?->komentar) }}</textarea>
                                                    </div>
                                                    <div class="sm:col-span-2">
                                                        <label for="foto_ulasan"
                                                            class="{{ $editorLabelClass }}">Path foto
                                                            ulasan</label>
                                                        <input id="foto_ulasan" name="foto_ulasan" type="text"
                                                            value="{{ old('foto_ulasan', $currentRecord?->foto_ulasan) }}"
                                                            class="{{ $editorFieldClass }}">
                                                    </div>
                                                    <label class="{{ $editorToggleClass }} sm:col-span-2">
                                                        <input type="checkbox" name="is_tampil" value="1"
                                                            {{ old('is_tampil', $isEditing ? (int) $currentRecord?->is_tampil : 1) ? 'checked' : '' }}
                                                            class="{{ $editorCheckboxClass }}">
                                                        Tampilkan di publik
                                                    </label>
                                                </div>
                                            </section>
                                        @elseif ($focus === 'pesanan')
                                            <section class="{{ $editorSectionClass }}">
                                                <h3 class="text-base font-semibold text-[#17284b]">Data pesanan
                                                </h3>
                                                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                                                    <div>
                                                        <label for="pelanggan_id"
                                                            class="{{ $editorLabelClass }}">Pelanggan</label>
                                                        <select id="pelanggan_id" name="pelanggan_id" required
                                                            class="{{ $editorFieldClass }}">
                                                            <option value="">Pilih pelanggan</option>
                                                            @foreach ($formOptions['pelanggan'] as $option)
                                                                <option value="{{ $option->id }}"
                                                                    @selected(old('pelanggan_id', $currentRecord?->pelanggan_id) == $option->id)>
                                                                    {{ $option->nama_lengkap }}</option>
                                                            @endforeach
                                                        </select>
                                                        @if (!empty($formOptionMeta['pelanggan']['hint']))
                                                            <p class="mt-1 text-xs leading-5 text-[#7b655e]">
                                                                {{ $formOptionMeta['pelanggan']['hint'] }}</p>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <label for="kode_promo_id"
                                                            class="{{ $editorLabelClass }}">Kode
                                                            promo</label>
                                                        <select id="kode_promo_id" name="kode_promo_id"
                                                            class="{{ $editorFieldClass }}">
                                                            <option value="">Tanpa promo</option>
                                                            @foreach ($formOptions['promo'] as $option)
                                                                <option value="{{ $option->id }}"
                                                                    @selected(old('kode_promo_id', $currentRecord?->kode_promo_id) == $option->id)>
                                                                    {{ $option->kode }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @if (!empty($formOptionMeta['promo']['hint']))
                                                            <p class="mt-1 text-xs leading-5 text-[#7b655e]">
                                                                {{ $formOptionMeta['promo']['hint'] }}</p>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <label for="kode_pesanan"
                                                            class="{{ $editorLabelClass }}">Kode
                                                            pesanan</label>
                                                        <input id="kode_pesanan" name="kode_pesanan"
                                                            type="text"
                                                            value="{{ old('kode_pesanan', $currentRecord?->kode_pesanan ?? 'SDT-' . now()->format('Ymd-His')) }}"
                                                            required class="{{ $editorFieldClass }}">
                                                    </div>
                                                    <div>
                                                        <label for="status"
                                                            class="{{ $editorLabelClass }}">Status</label>
                                                        <select id="status" name="status" required
                                                            class="{{ $editorFieldClass }}">
                                                            <option
                                                                value="{{ \App\Models\Pesanan::STATUS_MENUNGGU }}"
                                                                @selected(old('status', $currentRecord?->status) === \App\Models\Pesanan::STATUS_MENUNGGU)>Menunggu pembayaran
                                                            </option>
                                                            <option
                                                                value="{{ \App\Models\Pesanan::STATUS_DIPROSES }}"
                                                                @selected(old('status', $currentRecord?->status) === \App\Models\Pesanan::STATUS_DIPROSES)>Diproses</option>
                                                            <option
                                                                value="{{ \App\Models\Pesanan::STATUS_SIAPKIRIM }}"
                                                                @selected(old('status', $currentRecord?->status) === \App\Models\Pesanan::STATUS_SIAPKIRIM)>Siap kirim</option>
                                                            <option
                                                                value="{{ \App\Models\Pesanan::STATUS_SELESAI }}"
                                                                @selected(old('status', $currentRecord?->status) === \App\Models\Pesanan::STATUS_SELESAI)>Selesai</option>
                                                            <option
                                                                value="{{ \App\Models\Pesanan::STATUS_DIBATALKAN }}"
                                                                @selected(old('status', $currentRecord?->status) === \App\Models\Pesanan::STATUS_DIBATALKAN)>Dibatalkan
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label for="total_harga"
                                                            class="{{ $editorLabelClass }}">Total
                                                            harga</label>
                                                        <input id="total_harga" name="total_harga" type="number"
                                                            min="0"
                                                            value="{{ old('total_harga', $currentRecord?->total_harga) }}"
                                                            required class="{{ $editorFieldClass }}">
                                                    </div>
                                                    <div>
                                                        <label for="biaya_ongkir"
                                                            class="{{ $editorLabelClass }}">Biaya
                                                            ongkir</label>
                                                        <input id="biaya_ongkir" name="biaya_ongkir"
                                                            type="number" min="0"
                                                            value="{{ old('biaya_ongkir', $currentRecord?->biaya_ongkir ?? 0) }}"
                                                            class="{{ $editorFieldClass }}">
                                                    </div>
                                                    <div>
                                                        <label for="diskon"
                                                            class="{{ $editorLabelClass }}">Diskon</label>
                                                        <input id="diskon" name="diskon" type="number"
                                                            min="0"
                                                            value="{{ old('diskon', $currentRecord?->diskon ?? 0) }}"
                                                            class="{{ $editorFieldClass }}">
                                                    </div>
                                                    <div>
                                                        <label for="grand_total"
                                                            class="{{ $editorLabelClass }}">Grand
                                                            total</label>
                                                        <input id="grand_total" name="grand_total" type="number"
                                                            min="0"
                                                            value="{{ old('grand_total', $currentRecord?->grand_total) }}"
                                                            required class="{{ $editorFieldClass }}">
                                                    </div>
                                                    <div class="sm:col-span-2">
                                                        <label for="batas_waktu_bayar"
                                                            class="{{ $editorLabelClass }}">Batas waktu
                                                            bayar</label>
                                                        <input id="batas_waktu_bayar" name="batas_waktu_bayar"
                                                            type="datetime-local"
                                                            value="{{ old('batas_waktu_bayar', $currentRecord?->batas_waktu_bayar?->format('Y-m-d\\TH:i')) }}"
                                                            class="{{ $editorFieldClass }}">
                                                    </div>
                                                    <div class="sm:col-span-2">
                                                        <label for="catatan_pembeli"
                                                            class="{{ $editorLabelClass }}">Catatan
                                                            pembeli</label>
                                                        <textarea id="catatan_pembeli" name="catatan_pembeli" rows="4" class="{{ $editorFieldClass }}">{{ old('catatan_pembeli', $currentRecord?->catatan_pembeli) }}</textarea>
                                                    </div>
                                                </div>
                                            </section>
                                        @elseif ($focus === 'pembayaran')
                                            <section class="{{ $editorSectionClass }}">
                                                <h3 class="text-base font-semibold text-[#17284b]">Data
                                                    pembayaran</h3>
                                                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                                                    <div>
                                                        <label for="pesanan_id"
                                                            class="{{ $editorLabelClass }}">Pesanan</label>
                                                        <select id="pesanan_id" name="pesanan_id" required
                                                            class="{{ $editorFieldClass }}">
                                                            <option value="">Pilih pesanan</option>
                                                            @foreach ($formOptions['pesanan'] as $option)
                                                                <option value="{{ $option->id }}"
                                                                    @selected(old('pesanan_id', $currentRecord?->pesanan_id) == $option->id)>
                                                                    {{ $option->kode_pesanan }}</option>
                                                            @endforeach
                                                        </select>
                                                        @if (!empty($formOptionMeta['pesanan']['hint']))
                                                            <p class="mt-1 text-xs leading-5 text-[#7b655e]">
                                                                {{ $formOptionMeta['pesanan']['hint'] }}</p>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <label for="metode"
                                                            class="{{ $editorLabelClass }}">Metode
                                                            bayar</label>
                                                        <input id="metode" name="metode" type="text"
                                                            value="{{ old('metode', $currentRecord?->metode) }}"
                                                            required class="{{ $editorFieldClass }}">
                                                    </div>
                                                    <div>
                                                        <label for="jumlah_dibayar"
                                                            class="{{ $editorLabelClass }}">Jumlah
                                                            dibayar</label>
                                                        <input id="jumlah_dibayar" name="jumlah_dibayar"
                                                            type="number" min="0"
                                                            value="{{ old('jumlah_dibayar', $currentRecord?->jumlah_dibayar) }}"
                                                            required class="{{ $editorFieldClass }}">
                                                    </div>
                                                    <div>
                                                        <label for="status"
                                                            class="{{ $editorLabelClass }}">Status</label>
                                                        <select id="status" name="status" required
                                                            class="{{ $editorFieldClass }}">
                                                            <option
                                                                value="{{ \App\Models\Pembayaran::STATUS_MENUNGGU }}"
                                                                @selected(old('status', $currentRecord?->status) === \App\Models\Pembayaran::STATUS_MENUNGGU)>Menunggu</option>
                                                            <option
                                                                value="{{ \App\Models\Pembayaran::STATUS_LUNAS }}"
                                                                @selected(old('status', $currentRecord?->status) === \App\Models\Pembayaran::STATUS_LUNAS)>Lunas</option>
                                                            <option
                                                                value="{{ \App\Models\Pembayaran::STATUS_DITOLAK }}"
                                                                @selected(old('status', $currentRecord?->status) === \App\Models\Pembayaran::STATUS_DITOLAK)>Ditolak</option>
                                                        </select>
                                                    </div>
                                                    <div class="sm:col-span-2">
                                                        <label for="bukti_transfer"
                                                            class="{{ $editorLabelClass }}">Path bukti
                                                            transfer</label>
                                                        <input id="bukti_transfer" name="bukti_transfer"
                                                            type="text"
                                                            value="{{ old('bukti_transfer', $currentRecord?->bukti_transfer) }}"
                                                            required class="{{ $editorFieldClass }}">
                                                    </div>
                                                    <div class="sm:col-span-2">
                                                        <label for="alasan_penolakan"
                                                            class="{{ $editorLabelClass }}">Alasan
                                                            penolakan</label>
                                                        <textarea id="alasan_penolakan" name="alasan_penolakan" rows="3" class="{{ $editorFieldClass }}">{{ old('alasan_penolakan', $currentRecord?->alasan_penolakan) }}</textarea>
                                                    </div>
                                                    <div class="sm:col-span-2">
                                                        <label for="waktu_dibayar"
                                                            class="{{ $editorLabelClass }}">Waktu
                                                            dibayar</label>
                                                        <input id="waktu_dibayar" name="waktu_dibayar"
                                                            type="datetime-local"
                                                            value="{{ old('waktu_dibayar', $currentRecord?->waktu_dibayar?->format('Y-m-d\\TH:i')) }}"
                                                            class="{{ $editorFieldClass }}">
                                                    </div>
                                                </div>
                                            </section>
                                        @elseif ($focus === 'users')
                                            <section class="{{ $editorSectionClass }}">
                                                <h3 class="text-base font-semibold text-[#17284b]">Akun admin
                                                </h3>
                                                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                                                    <div>
                                                        <label for="name"
                                                            class="{{ $editorLabelClass }}">Nama</label>
                                                        <input id="name" name="name" type="text"
                                                            value="{{ old('name', $currentRecord?->name) }}"
                                                            required class="{{ $editorFieldClass }}">
                                                    </div>
                                                    <div>
                                                        <label for="email"
                                                            class="{{ $editorLabelClass }}">Email</label>
                                                        <input id="email" name="email" type="email"
                                                            value="{{ old('email', $currentRecord?->email) }}"
                                                            required class="{{ $editorFieldClass }}">
                                                    </div>
                                                    <div>
                                                        <label for="password"
                                                            class="{{ $editorLabelClass }}">Password
                                                            {{ $isEditing ? '(opsional)' : '' }}</label>
                                                        <input id="password" name="password" type="password"
                                                            {{ $isEditing ? '' : 'required' }}
                                                            class="{{ $editorFieldClass }}">
                                                    </div>
                                                    <div>
                                                        <label for="role"
                                                            class="{{ $editorLabelClass }}">Role</label>
                                                        <select id="role" name="role" required
                                                            class="{{ $editorFieldClass }}">
                                                            @foreach ($formOptions['roles'] as $option)
                                                                <option value="{{ $option['value'] }}"
                                                                    @selected(old('role', $currentRecord?->role) === $option['value'])>
                                                                    {{ $option['label'] }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <label class="{{ $editorToggleClass }} sm:col-span-2">
                                                        <input type="checkbox" name="is_admin" value="1"
                                                            {{ old('is_admin', $isEditing ? (int) $currentRecord?->is_admin : 1) ? 'checked' : '' }}
                                                            class="{{ $editorCheckboxClass }}">
                                                        Beri akses admin panel
                                                    </label>
                                                </div>
                                            </section>
                                        @endif

                                        <div class="flex flex-wrap gap-3 border-t border-[#e9ddd5] pt-5">
                                            <button type="submit" data-submit-button="true"
                                                class="inline-flex items-center rounded-full bg-[linear-gradient(135deg,#7A1F2B,#5E1721)] px-5 py-3 text-sm font-semibold text-white shadow-[0_14px_28px_rgba(94,23,33,0.22)] transition hover:translate-y-[-1px] hover:opacity-95 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#7A1F2B]">
                                                <span data-submit-label="true">{{ $submitLabel }}</span>
                                            </button>
                                            <a href="{{ $manageUrl }}"
                                                class="inline-flex items-center rounded-full border border-gray-300 bg-gray-100 px-5 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-300">
                                                Batal
                                            </a>
                                        </div>
                                    </form>
                                    @if ($isEditing && !($focus === 'users' && ($currentRecord?->id ?? null) === ($user?->id ?? null)))
                                        <form method="POST"
                                            action="{{ route('admin.destroy', ['focus' => $focus, 'record' => $currentRecord->id]) }}"
                                            data-delete-confirm="true" class="mt-3">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center rounded-full border border-rose-200 bg-white px-5 py-3 text-sm font-semibold text-rose-600 transition hover:border-rose-300 hover:bg-rose-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-rose-400">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>

                                <aside class="hidden space-y-4">
                                    <section class="rounded-[22px] border border-[#e6ddd5] bg-white p-5">
                                        <p class="text-xs font-semibold tracking-[0.04em] text-[#7b655e]">
                                            Field penting</p>
                                        <div class="mt-4 space-y-3">
                                            @foreach ($createBlueprint['fields'] as $field)
                                                <div
                                                    class="rounded-[18px] bg-[#fbf8f5] px-4 py-3 text-sm font-medium text-[#56353a] ring-1 ring-[#e6ddd5]">
                                                    {{ $field }}
                                                </div>
                                            @endforeach
                                        </div>
                                    </section>

                                    <section class="rounded-[22px] border border-[#e6ddd5] bg-white p-5">
                                        <p class="text-xs font-semibold tracking-[0.04em] text-[#7b655e]">
                                            Catatan singkat</p>
                                        <div class="mt-4 space-y-2 text-sm leading-6 text-[#6a5854]">
                                            <p>Isi field wajib dulu, lalu cek relasi utama sebelum simpan.</p>
                                            <p>Setelah tersimpan, data kembali ke daftar kelola modul.</p>
                                        </div>
                                    </section>
                                </aside>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
