<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Kategori;
use App\Models\KodePromo;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Ulasan;
use App\Models\User;
use App\Services\ActivityLogger;
use App\Services\Analytics\DashboardPayloadService;
use App\Support\DashboardCache;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Throwable;

class AdminController extends Controller
{
    public function index(
        Request $request,
        DashboardPayloadService $dashboardPayloadService,
        ?string $focus = null,
        ?string $mode = null
    ) {
        return $this->renderPage($request, $dashboardPayloadService, $focus, $mode);
    }

    public function edit(
        Request $request,
        DashboardPayloadService $dashboardPayloadService,
        string $focus,
        int $record
    ) {
        return $this->renderPage(
            $request,
            $dashboardPayloadService,
            $focus,
            'edit',
            $this->findRecord($focus, $record)
        );
    }

    public function globalSearch(Request $request)
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        $search = trim((string) ($validated['q'] ?? ''));
        $payload = $this->buildGlobalSearchPayload($search);

        return response()->json([
            'query' => $payload['query'],
            'groups' => $payload['groups']
                ->filter(fn (array $group) => ! empty($group['items']))
                ->map(fn (array $group) => [
                    'key' => $group['key'],
                    'label' => $group['label'],
                    'items' => $group['items'],
                ])
                ->values()
                ->all(),
            'total' => $payload['total'],
        ]);
    }

    public function showSearchResults(Request $request)
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        $search = trim((string) ($validated['q'] ?? ''));
        $payload = $this->buildGlobalSearchPayload($search, 8);
        $user = $request->user();

        return view('admin.search', [
            'query' => $search,
            'groups' => $payload['groups'],
            'total' => $payload['total'],
            'user' => $user,
        ]);
    }

    public function update(Request $request, string $focus, int $record)
    {
        $user = $request->user();
        $recordModel = $this->findRecord($focus, $record);
        $beforeSnapshot = $this->snapshotRecordForLog($recordModel);

        match ($focus) {
            'kategori' => $recordModel->update($request->validate([
                'nama' => ['required', 'string', 'max:100'],
                'slug' => ['required', 'string', 'max:100', Rule::unique('kategori', 'slug')->ignore($recordModel->id)],
                'deskripsi' => ['nullable', 'string'],
                'is_aktif' => ['nullable', 'boolean'],
            ]) + [
                'is_aktif' => $request->boolean('is_aktif'),
            ]),

            'produk' => $recordModel->update($request->validate([
                'kategori_id' => ['required', 'exists:kategori,id'],
                'nama' => ['required', 'string', 'max:200'],
                'slug' => ['required', 'string', 'max:200', Rule::unique('produk', 'slug')->ignore($recordModel->id)],
                'deskripsi' => ['nullable', 'string'],
                'harga_dasar' => ['required', 'integer', 'min:0'],
                'foto_utama' => ['nullable', 'string', 'max:255'],
                'is_customizable' => ['nullable', 'boolean'],
                'is_sewa' => ['nullable', 'boolean'],
                'is_aktif' => ['nullable', 'boolean'],
            ]) + [
                'is_customizable' => $request->boolean('is_customizable'),
                'is_sewa' => $request->boolean('is_sewa'),
                'is_aktif' => $request->boolean('is_aktif'),
            ]),

            'promo' => $recordModel->update($request->validate([
                'kode' => ['required', 'string', 'max:20', Rule::unique('kode_promo', 'kode')->ignore($recordModel->id)],
                'tipe_diskon' => ['required', Rule::in(['persentase', 'nominal'])],
                'nilai_diskon' => ['required', 'integer', 'min:0'],
                'minimum_order' => ['nullable', 'integer', 'min:0'],
                'kuota' => ['nullable', 'integer', 'min:1'],
                'tanggal_mulai' => ['nullable', 'date'],
                'tanggal_berakhir' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
                'deskripsi' => ['nullable', 'string'],
                'is_aktif' => ['nullable', 'boolean'],
            ]) + [
                'minimum_order' => (int) $request->input('minimum_order', 0),
                'is_aktif' => $request->boolean('is_aktif'),
            ]),

            'pelanggan' => $recordModel->update($request->validate([
                'nama_lengkap' => ['required', 'string', 'max:150'],
                'no_hp' => ['required', 'string', 'max:20', Rule::unique('pelanggan', 'no_hp')->ignore($recordModel->id)],
                'email' => ['nullable', 'email', 'max:100'],
            ])),

            'ulasan' => $recordModel->update($request->validate([
                'pesanan_id' => ['required', 'exists:pesanan,id'],
                'produk_id' => [
                    'required',
                    'exists:produk,id',
                    Rule::unique('ulasan')->ignore($recordModel->id)->where(fn ($query) => $query
                        ->where('pesanan_id', $request->input('pesanan_id'))
                        ->where('produk_id', $request->input('produk_id'))),
                ],
                'nama_pengulas' => ['required', 'string', 'max:255'],
                'rating' => ['required', 'integer', 'min:1', 'max:5'],
                'komentar' => ['nullable', 'string'],
                'foto_ulasan' => ['nullable', 'string', 'max:255'],
                'is_tampil' => ['nullable', 'boolean'],
            ]) + [
                'is_tampil' => $request->boolean('is_tampil'),
            ]),

            'pesanan' => $recordModel->update($request->validate([
                'pelanggan_id' => ['required', 'exists:pelanggan,id'],
                'kode_promo_id' => ['nullable', 'exists:kode_promo,id'],
                'kode_pesanan' => ['required', 'string', 'max:50', Rule::unique('pesanan', 'kode_pesanan')->ignore($recordModel->id)],
                'status' => ['required', Rule::in([
                    Pesanan::STATUS_MENUNGGU,
                    Pesanan::STATUS_DIPROSES,
                    Pesanan::STATUS_SIAPKIRIM,
                    Pesanan::STATUS_SELESAI,
                    Pesanan::STATUS_DIBATALKAN,
                ])],
                'total_harga' => ['required', 'integer', 'min:0'],
                'biaya_ongkir' => ['nullable', 'integer', 'min:0'],
                'diskon' => ['nullable', 'integer', 'min:0'],
                'grand_total' => ['required', 'integer', 'min:0'],
                'batas_waktu_bayar' => ['nullable', 'date'],
                'catatan_pembeli' => ['nullable', 'string'],
            ]) + [
                'biaya_ongkir' => (int) $request->input('biaya_ongkir', 0),
                'diskon' => (int) $request->input('diskon', 0),
                'kode_promo_snapshot' => optional(KodePromo::find($request->input('kode_promo_id')))->kode,
            ]),

            'pembayaran' => $recordModel->update($request->validate([
                'pesanan_id' => ['required', 'exists:pesanan,id'],
                'metode' => ['required', 'string', 'max:50'],
                'jumlah_dibayar' => ['required', 'integer', 'min:0'],
                'bukti_transfer' => ['required', 'string', 'max:255'],
                'status' => ['required', Rule::in([
                    Pembayaran::STATUS_MENUNGGU,
                    Pembayaran::STATUS_LUNAS,
                    Pembayaran::STATUS_DITOLAK,
                ])],
                'alasan_penolakan' => ['nullable', 'string'],
                'waktu_dibayar' => ['nullable', 'date'],
            ]) + [
                'diverifikasi_oleh' => in_array($request->input('status'), [Pembayaran::STATUS_LUNAS, Pembayaran::STATUS_DITOLAK], true) ? $user?->id : null,
                'waktu_diverifikasi' => in_array($request->input('status'), [Pembayaran::STATUS_LUNAS, Pembayaran::STATUS_DITOLAK], true) ? now() : null,
            ]),

            'users' => $this->updateUser($request, $recordModel),

            default => abort(404),
        };

        $this->logAdminUpdate($focus, $recordModel, $beforeSnapshot);

        $this->flushAdminCaches();

        return redirect()
            ->route('admin.index', ['focus' => $focus, 'mode' => 'manage'])
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(Request $request, string $focus, int $record)
    {
        $recordModel = $this->findRecord($focus, $record);

        abort_unless($this->canDeleteRecord($focus, $recordModel, $request->user()), 403);

        try {
            $recordLabel = $this->resolveRecordLabel($focus, $recordModel);
            $this->logAdminDelete($focus, $recordModel, $recordLabel);
            $recordModel->delete();
            $this->flushAdminCaches();

            return redirect()
                ->route('admin.index', ['focus' => $focus, 'mode' => 'manage'])
                ->with('success', $recordLabel.' berhasil dihapus.');
        } catch (Throwable $exception) {
            report($exception);

            return redirect()
                ->route('admin.index', ['focus' => $focus, 'mode' => 'manage'])
                ->with('danger', 'Data ini belum bisa dihapus karena masih terhubung dengan data lain.');
        }
    }

    public function markNotificationsSeen(Request $request): JsonResponse
    {
        $user = $request->user();

        abort_unless($user?->isAdmin(), 403);

        $user->forceFill([
            'notifications_seen_at' => now(),
        ])->save();

        return response()->json(['ok' => true]);
    }

    private function renderPage(
        Request $request,
        DashboardPayloadService $dashboardPayloadService,
        ?string $focus = null,
        ?string $mode = null,
        mixed $currentRecord = null
    ) {
        $user = $request->user();
        $focus = blank($focus) ? 'dashboard' : $focus;
        $mode = blank($mode) ? ($focus === 'dashboard' ? 'overview' : 'manage') : $mode;

        if ($focus === 'aktivitas' && $mode === 'create') {
            $mode = 'manage';
        }

        if ($focus === 'users' && ! $user?->isOwner()) {
            abort(403);
        }

        $menu = [
            [
                'group' => 'Ringkasan',
                'items' => [
                    ['key' => 'dashboard', 'label' => 'Dasbor', 'href' => route('admin.index'), 'icon' => 'dashboard'],
                ],
            ],
            [
                'group' => 'Master Data',
                'items' => [
                    ['key' => 'kategori', 'label' => 'Kategori', 'href' => route('admin.index', ['focus' => 'kategori', 'mode' => 'manage']), 'icon' => 'tag'],
                    ['key' => 'produk', 'label' => 'Produk', 'href' => route('admin.index', ['focus' => 'produk', 'mode' => 'manage']), 'icon' => 'bag'],
                    ['key' => 'promo', 'label' => 'Kode Promo', 'href' => route('admin.index', ['focus' => 'promo', 'mode' => 'manage']), 'icon' => 'ticket'],
                ],
            ],
            [
                'group' => 'Data Pelanggan',
                'items' => [
                    ['key' => 'pelanggan', 'label' => 'Pelanggan', 'href' => route('admin.index', ['focus' => 'pelanggan', 'mode' => 'manage']), 'icon' => 'users'],
                    ['key' => 'ulasan', 'label' => 'Ulasan & Moderasi', 'href' => route('admin.index', ['focus' => 'ulasan', 'mode' => 'manage']), 'icon' => 'star'],
                ],
            ],
            [
                'group' => 'Operasional',
                'items' => [
                    ['key' => 'pesanan', 'label' => 'Pesanan', 'href' => route('admin.index', ['focus' => 'pesanan', 'mode' => 'manage']), 'icon' => 'clipboard'],
                    ['key' => 'pembayaran', 'label' => 'Verifikasi Pembayaran', 'href' => route('admin.index', ['focus' => 'pembayaran', 'mode' => 'manage']), 'icon' => 'banknotes'],
                ],
            ],
        ];

        if ($user?->isOwner()) {
            $menu[] = [
                'group' => 'Manajemen Pengguna',
                'items' => [
                    ['key' => 'users', 'label' => 'Users', 'href' => route('admin.index', ['focus' => 'users', 'mode' => 'manage']), 'icon' => 'shield'],
                ],
            ];
        }

        $modules = [
            'dashboard' => [
                'title' => 'Dasbor Operasional',
                'subtitle' => 'Ringkasan lintas modul dengan fokus pada antrian kerja hari ini.',
                'eyebrow' => 'Ringkasan',
                'accent' => 'from-[#4f6dff] via-[#6f6bff] to-[#9b6dff]',
            ],
            'kategori' => [
                'title' => 'Kategori Produk',
                'subtitle' => 'Atur struktur katalog inti yang dipakai tim untuk mengelompokkan layanan.',
                'eyebrow' => 'Master Data',
                'accent' => 'from-[#3d5af1] via-[#4f6dff] to-[#7b8cff]',
            ],
            'produk' => [
                'title' => 'Manajemen Produk',
                'subtitle' => 'Pantau produk aktif, item custom, dan performa stok display katalog.',
                'eyebrow' => 'Master Data',
                'accent' => 'from-[#3650d8] via-[#5674ff] to-[#8ba2ff]',
            ],
            'promo' => [
                'title' => 'Strategi Kode Promo',
                'subtitle' => 'Lihat promo aktif, batas kuota, dan kampanye yang perlu diperbarui.',
                'eyebrow' => 'Master Data',
                'accent' => 'from-[#5162f6] via-[#6e6dff] to-[#9c7cff]',
            ],
            'pelanggan' => [
                'title' => 'Relasi Pelanggan',
                'subtitle' => 'Monitor repeat customer, pelanggan pasif, dan antrian follow-up.',
                'eyebrow' => 'Data Pelanggan',
                'accent' => 'from-[#2f4bb6] via-[#4968ea] to-[#7f96ff]',
            ],
            'ulasan' => [
                'title' => 'Ulasan & Moderasi',
                'subtitle' => 'Kurasi testimoni yang tampil dan tangkap sinyal kualitas layanan.',
                'eyebrow' => 'Data Pelanggan',
                'accent' => 'from-[#3149b2] via-[#4d68dd] to-[#758efc]',
            ],
            'pesanan' => [
                'title' => 'Orkestrasi Pesanan',
                'subtitle' => 'Lihat beban kerja order dari menunggu hingga selesai kirim.',
                'eyebrow' => 'Operasional',
                'accent' => 'from-[#4d5df2] via-[#6d72ff] to-[#8e84ff]',
            ],
            'pembayaran' => [
                'title' => 'Verifikasi Pembayaran',
                'subtitle' => 'Sorot pembayaran pending supaya proses order tidak tertahan.',
                'eyebrow' => 'Operasional',
                'accent' => 'from-[#3651d1] via-[#5574ff] to-[#8d9dff]',
            ],
            'aktivitas' => [
                'title' => 'Log Aktivitas Admin',
                'subtitle' => 'Pantau jejak perubahan panel dan telusuri histori aksi admin dari database.',
                'eyebrow' => 'Operasional',
                'accent' => 'from-[#41506d] via-[#5f6f92] to-[#8693b4]',
            ],
            'users' => [
                'title' => 'Kontrol Tim Admin',
                'subtitle' => 'Akses pengguna, distribusi role, dan kapasitas operasional panel.',
                'eyebrow' => 'Manajemen Pengguna',
                'accent' => 'from-[#2e46aa] via-[#4f65df] to-[#7f95ff]',
            ],
        ];

        $focusModule = $modules[$focus] ?? $modules['dashboard'];
        $isOwner = $user?->isOwner() === true;
        $adminSnapshot = $this->buildAdminSnapshot($isOwner);
        $statusBreakdown = $this->buildStatusBreakdown($adminSnapshot['pesanan']['by_status']);
        $dashboardPayload = $this->shouldLoadDashboardAnalytics($focus, $mode)
            ? $dashboardPayloadService->get()
            : null;

        $operationalQueues = [
            [
                'label' => 'Pesanan Menunggu',
                'value' => $adminSnapshot['pesanan']['by_status'][Pesanan::STATUS_MENUNGGU] ?? 0,
                'hint' => 'Perlu follow-up pembayaran',
            ],
            [
                'label' => 'Pembayaran Pending',
                'value' => $adminSnapshot['pembayaran']['by_status'][Pembayaran::STATUS_MENUNGGU] ?? 0,
                'hint' => 'Butuh verifikasi admin',
            ],
            [
                'label' => 'Promo Aktif',
                'value' => $adminSnapshot['promo']['aktif'],
                'hint' => 'Sedang dipakai kampanye',
            ],
            [
                'label' => 'Ulasan Disembunyikan',
                'value' => $adminSnapshot['ulasan']['hidden'],
                'hint' => 'Perlu dipilih tampil/tidak',
            ],
        ];

        $moduleSummaries = [
            'kategori' => [
                'count' => $adminSnapshot['kategori']['total'],
                'detail' => $adminSnapshot['produk']['total'].' produk terikat',
                'cta' => route('admin.index', ['focus' => 'kategori', 'mode' => 'manage']),
            ],
            'produk' => [
                'count' => $adminSnapshot['produk']['total'],
                'detail' => $adminSnapshot['produk']['aktif'].' aktif',
                'cta' => route('admin.index', ['focus' => 'produk', 'mode' => 'manage']),
            ],
            'promo' => [
                'count' => $adminSnapshot['promo']['total'],
                'detail' => $adminSnapshot['promo']['aktif'].' aktif',
                'cta' => route('admin.index', ['focus' => 'promo', 'mode' => 'manage']),
            ],
            'pelanggan' => [
                'count' => $adminSnapshot['pelanggan']['total'],
                'detail' => $adminSnapshot['pelanggan']['pernah_order'].' pernah order',
                'cta' => route('admin.index', ['focus' => 'pelanggan', 'mode' => 'manage']),
            ],
            'ulasan' => [
                'count' => $adminSnapshot['ulasan']['total'],
                'detail' => $adminSnapshot['ulasan']['hidden'].' perlu moderasi',
                'cta' => route('admin.index', ['focus' => 'ulasan', 'mode' => 'manage']),
            ],
            'pesanan' => [
                'count' => $adminSnapshot['pesanan']['total'],
                'detail' => ($adminSnapshot['pesanan']['by_status'][Pesanan::STATUS_MENUNGGU] ?? 0).' menunggu',
                'cta' => route('admin.index', ['focus' => 'pesanan', 'mode' => 'manage']),
            ],
            'pembayaran' => [
                'count' => $adminSnapshot['pembayaran']['total'],
                'detail' => ($adminSnapshot['pembayaran']['by_status'][Pembayaran::STATUS_MENUNGGU] ?? 0).' pending',
                'cta' => route('admin.index', ['focus' => 'pembayaran', 'mode' => 'manage']),
            ],
            'aktivitas' => [
                'count' => $adminSnapshot['activity_logs']['total'],
                'detail' => 'Riwayat tersimpan di database',
                'cta' => route('admin.index', ['focus' => 'aktivitas', 'mode' => 'manage']),
            ],
            'users' => [
                'count' => $adminSnapshot['users']['total'],
                'detail' => $adminSnapshot['users']['admin'].' admin aktif',
                'cta' => route('admin.index', ['focus' => 'users', 'mode' => 'manage']),
            ],
        ];

        $focusMetrics = $this->buildFocusMetrics($focus, $adminSnapshot);
        $focusPreview = $this->buildFocusPreview($focus, $request);
        $quickActions = $this->buildQuickActions($focus, $isOwner);
        $activityFeed = $focus === 'dashboard' && $user ? $this->buildActivityFeed($user) : [];
        $notificationUnreadCount = $user ? $this->buildNotificationUnreadCount($user) : 0;
        $notificationScopeLabel = $user?->isStaff()
            ? 'Pesanan baru masuk'
            : 'Aktivitas admin dan pesanan baru';
        $modeTabs = $this->buildModeTabs($focus, $isOwner, $currentRecord !== null);
        $createBlueprint = $this->buildCreateBlueprint($focus);
        $formSections = $this->buildFormSections($focus);
        $formOptionsPayload = in_array($mode, ['create', 'edit'], true) && $focus !== 'dashboard'
            ? $this->buildFormOptions($focus, $isOwner, $currentRecord)
            : ['options' => [], 'meta' => []];
        $searchTerm = trim((string) $request->query('search', ''));

        return view('admin.index', [
            'dashboardPayload' => $dashboardPayload,
            'statusBreakdown' => $statusBreakdown,
            'menu' => $menu,
            'focus' => $focus,
            'mode' => $mode,
            'focusModule' => $focusModule,
            'moduleSummaries' => $moduleSummaries,
            'operationalQueues' => $operationalQueues,
            'focusMetrics' => $focusMetrics,
            'focusPreview' => $focusPreview,
            'quickActions' => $quickActions,
            'activityFeed' => $activityFeed,
            'notificationUnreadCount' => $notificationUnreadCount,
            'notificationScopeLabel' => $notificationScopeLabel,
            'modeTabs' => $modeTabs,
            'createBlueprint' => $createBlueprint,
            'formSections' => $formSections,
            'formOptions' => $formOptionsPayload['options'],
            'formOptionMeta' => $formOptionsPayload['meta'],
            'currentRecord' => $currentRecord,
            'user' => $user,
            'searchTerm' => $searchTerm,
            'searchPlaceholder' => $this->resolveSearchPlaceholder($focus),
        ]);
    }

    private function buildAdminSnapshot(bool $isOwner): array
    {
        return Cache::remember(
            $this->adminSnapshotCacheKey($isOwner),
            now()->addMinutes(5),
            function () use ($isOwner): array {
                $pelangganCutoff = now()->subDays(30);

                $kategori = DB::table('kategori')
                    ->selectRaw('COUNT(*) as total')
                    ->selectRaw('SUM(CASE WHEN is_aktif = 1 THEN 1 ELSE 0 END) as aktif')
                    ->selectRaw('SUM(CASE WHEN is_aktif = 0 THEN 1 ELSE 0 END) as nonaktif')
                    ->first();

                $produk = DB::table('produk')
                    ->selectRaw('COUNT(*) as total')
                    ->selectRaw('SUM(CASE WHEN is_aktif = 1 THEN 1 ELSE 0 END) as aktif')
                    ->selectRaw('SUM(CASE WHEN is_customizable = 1 THEN 1 ELSE 0 END) as customizable')
                    ->selectRaw('SUM(CASE WHEN is_sewa = 1 THEN 1 ELSE 0 END) as sewa')
                    ->first();

                $promo = DB::table('kode_promo')
                    ->selectRaw('COUNT(*) as total')
                    ->selectRaw('SUM(CASE WHEN is_aktif = 1 THEN 1 ELSE 0 END) as aktif')
                    ->selectRaw('SUM(CASE WHEN kuota IS NOT NULL AND dipakai >= kuota THEN 1 ELSE 0 END) as kuota_habis')
                    ->selectRaw('SUM(CASE WHEN minimum_order >= 500000 THEN 1 ELSE 0 END) as minimum_order_tinggi')
                    ->first();

                $pelanggan = DB::table('pelanggan')
                    ->selectRaw('COUNT(*) as total')
                    ->selectRaw('SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as baru_30_hari', [$pelangganCutoff])
                    ->selectRaw("SUM(CASE WHEN email IS NOT NULL AND email <> '' THEN 1 ELSE 0 END) as email_terisi")
                    ->first();

                $ulasan = DB::table('ulasan')
                    ->selectRaw('COUNT(*) as total')
                    ->selectRaw('SUM(CASE WHEN is_tampil = 0 THEN 1 ELSE 0 END) as hidden')
                    ->selectRaw('SUM(CASE WHEN is_tampil = 1 THEN 1 ELSE 0 END) as tampil')
                    ->selectRaw('SUM(CASE WHEN rating >= 4 THEN 1 ELSE 0 END) as rating_bagus')
                    ->first();

                $pesananByStatus = DB::table('pesanan')
                    ->select('status')
                    ->selectRaw('COUNT(*) as total')
                    ->groupBy('status')
                    ->pluck('total', 'status')
                    ->map(fn ($total): int => (int) $total)
                    ->all();

                $pembayaranByStatus = DB::table('pembayaran')
                    ->select('status')
                    ->selectRaw('COUNT(*) as total')
                    ->groupBy('status')
                    ->pluck('total', 'status')
                    ->map(fn ($total): int => (int) $total)
                    ->all();

                $repeatCustomers = (int) DB::query()
                    ->fromSub(
                        DB::table('pesanan')
                            ->select('pelanggan_id')
                            ->whereNotNull('pelanggan_id')
                            ->groupBy('pelanggan_id')
                            ->havingRaw('COUNT(id) > 1'),
                        'repeat_customers'
                    )
                    ->count();

                $users = $isOwner
                    ? DB::table('users')
                        ->selectRaw('COUNT(*) as total')
                        ->selectRaw('SUM(CASE WHEN is_admin = 1 THEN 1 ELSE 0 END) as admin')
                        ->selectRaw('SUM(CASE WHEN role = ? THEN 1 ELSE 0 END) as owner', [User::ROLE_OWNER])
                        ->selectRaw('SUM(CASE WHEN role = ? THEN 1 ELSE 0 END) as role_admin', [User::ROLE_ADMIN])
                        ->selectRaw('SUM(CASE WHEN role = ? THEN 1 ELSE 0 END) as staff', [User::ROLE_STAFF])
                        ->first()
                    : null;

                return [
                    'kategori' => [
                        'total' => $this->intValue($kategori, 'total'),
                        'aktif' => $this->intValue($kategori, 'aktif'),
                        'nonaktif' => $this->intValue($kategori, 'nonaktif'),
                    ],
                    'produk' => [
                        'total' => $this->intValue($produk, 'total'),
                        'aktif' => $this->intValue($produk, 'aktif'),
                        'customizable' => $this->intValue($produk, 'customizable'),
                        'sewa' => $this->intValue($produk, 'sewa'),
                    ],
                    'promo' => [
                        'total' => $this->intValue($promo, 'total'),
                        'aktif' => $this->intValue($promo, 'aktif'),
                        'kuota_habis' => $this->intValue($promo, 'kuota_habis'),
                        'minimum_order_tinggi' => $this->intValue($promo, 'minimum_order_tinggi'),
                    ],
                    'pelanggan' => [
                        'total' => $this->intValue($pelanggan, 'total'),
                        'pernah_order' => (int) DB::table('pesanan')->distinct()->count('pelanggan_id'),
                        'repeat' => $repeatCustomers,
                        'baru_30_hari' => $this->intValue($pelanggan, 'baru_30_hari'),
                        'email_terisi' => $this->intValue($pelanggan, 'email_terisi'),
                    ],
                    'ulasan' => [
                        'total' => $this->intValue($ulasan, 'total'),
                        'hidden' => $this->intValue($ulasan, 'hidden'),
                        'tampil' => $this->intValue($ulasan, 'tampil'),
                        'rating_bagus' => $this->intValue($ulasan, 'rating_bagus'),
                    ],
                    'pesanan' => [
                        'total' => array_sum($pesananByStatus),
                        'by_status' => $pesananByStatus,
                    ],
                    'pembayaran' => [
                        'total' => array_sum($pembayaranByStatus),
                        'by_status' => $pembayaranByStatus,
                    ],
                    'users' => [
                        'total' => $this->intValue($users, 'total'),
                        'admin' => $this->intValue($users, 'admin'),
                        'owner' => $this->intValue($users, 'owner'),
                        'role_admin' => $this->intValue($users, 'role_admin'),
                        'staff' => $this->intValue($users, 'staff'),
                    ],
                    'activity_logs' => [
                        'total' => $this->safeTableCount('activity_logs'),
                    ],
                    'email_logs' => [
                        'total' => $this->safeTableCount('email_logs'),
                    ],
                ];
            }
        );
    }

    public function store(Request $request, string $focus)
    {
        $user = $request->user();

        $createdRecord = match ($focus) {
            'kategori' => Kategori::query()->create($request->validate([
                'nama' => ['required', 'string', 'max:100'],
                'slug' => ['required', 'string', 'max:100', 'unique:kategori,slug'],
                'deskripsi' => ['nullable', 'string'],
                'is_aktif' => ['nullable', 'boolean'],
            ]) + [
                'is_aktif' => $request->boolean('is_aktif'),
            ]),

            'produk' => Produk::query()->create($request->validate([
                'kategori_id' => ['required', 'exists:kategori,id'],
                'nama' => ['required', 'string', 'max:200'],
                'slug' => ['required', 'string', 'max:200', 'unique:produk,slug'],
                'deskripsi' => ['nullable', 'string'],
                'harga_dasar' => ['required', 'integer', 'min:0'],
                'foto_utama' => ['nullable', 'string', 'max:255'],
                'is_customizable' => ['nullable', 'boolean'],
                'is_sewa' => ['nullable', 'boolean'],
                'is_aktif' => ['nullable', 'boolean'],
            ]) + [
                'is_customizable' => $request->boolean('is_customizable'),
                'is_sewa' => $request->boolean('is_sewa'),
                'is_aktif' => $request->boolean('is_aktif'),
            ]),

            'promo' => KodePromo::query()->create($request->validate([
                'kode' => ['required', 'string', 'max:20', 'unique:kode_promo,kode'],
                'tipe_diskon' => ['required', Rule::in(['persentase', 'nominal'])],
                'nilai_diskon' => ['required', 'integer', 'min:0'],
                'minimum_order' => ['nullable', 'integer', 'min:0'],
                'kuota' => ['nullable', 'integer', 'min:1'],
                'tanggal_mulai' => ['nullable', 'date'],
                'tanggal_berakhir' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
                'deskripsi' => ['nullable', 'string'],
                'is_aktif' => ['nullable', 'boolean'],
            ]) + [
                'minimum_order' => (int) $request->input('minimum_order', 0),
                'dipakai' => 0,
                'is_aktif' => $request->boolean('is_aktif'),
            ]),

            'pelanggan' => Pelanggan::query()->create($request->validate([
                'nama_lengkap' => ['required', 'string', 'max:150'],
                'no_hp' => ['required', 'string', 'max:20', 'unique:pelanggan,no_hp'],
                'email' => ['nullable', 'email', 'max:100'],
            ])),

            'ulasan' => Ulasan::query()->create($request->validate([
                'pesanan_id' => ['required', 'exists:pesanan,id'],
                'produk_id' => [
                    'required',
                    'exists:produk,id',
                    Rule::unique('ulasan')->where(fn ($query) => $query
                        ->where('pesanan_id', $request->input('pesanan_id'))
                        ->where('produk_id', $request->input('produk_id'))),
                ],
                'nama_pengulas' => ['required', 'string', 'max:255'],
                'rating' => ['required', 'integer', 'min:1', 'max:5'],
                'komentar' => ['nullable', 'string'],
                'foto_ulasan' => ['nullable', 'string', 'max:255'],
                'is_tampil' => ['nullable', 'boolean'],
            ]) + [
                'token_ulasan' => Str::upper(Str::random(24)),
                'is_tampil' => $request->boolean('is_tampil'),
            ]),

            'pesanan' => Pesanan::query()->create($request->validate([
                'pelanggan_id' => ['required', 'exists:pelanggan,id'],
                'kode_promo_id' => ['nullable', 'exists:kode_promo,id'],
                'kode_pesanan' => ['required', 'string', 'max:50', 'unique:pesanan,kode_pesanan'],
                'status' => ['required', Rule::in([
                    Pesanan::STATUS_MENUNGGU,
                    Pesanan::STATUS_DIPROSES,
                    Pesanan::STATUS_SIAPKIRIM,
                    Pesanan::STATUS_SELESAI,
                    Pesanan::STATUS_DIBATALKAN,
                ])],
                'total_harga' => ['required', 'integer', 'min:0'],
                'biaya_ongkir' => ['nullable', 'integer', 'min:0'],
                'diskon' => ['nullable', 'integer', 'min:0'],
                'grand_total' => ['required', 'integer', 'min:0'],
                'batas_waktu_bayar' => ['nullable', 'date'],
                'catatan_pembeli' => ['nullable', 'string'],
            ]) + [
                'biaya_ongkir' => (int) $request->input('biaya_ongkir', 0),
                'diskon' => (int) $request->input('diskon', 0),
                'kode_promo_snapshot' => optional(KodePromo::find($request->input('kode_promo_id')))->kode,
            ]),

            'pembayaran' => Pembayaran::query()->create($request->validate([
                'pesanan_id' => ['required', 'exists:pesanan,id'],
                'metode' => ['required', 'string', 'max:50'],
                'jumlah_dibayar' => ['required', 'integer', 'min:0'],
                'bukti_transfer' => ['required', 'string', 'max:255'],
                'status' => ['required', Rule::in([
                    Pembayaran::STATUS_MENUNGGU,
                    Pembayaran::STATUS_LUNAS,
                    Pembayaran::STATUS_DITOLAK,
                ])],
                'alasan_penolakan' => ['nullable', 'string'],
                'waktu_dibayar' => ['nullable', 'date'],
            ]) + [
                'diverifikasi_oleh' => in_array($request->input('status'), [Pembayaran::STATUS_LUNAS, Pembayaran::STATUS_DITOLAK], true) ? $user?->id : null,
                'waktu_diverifikasi' => in_array($request->input('status'), [Pembayaran::STATUS_LUNAS, Pembayaran::STATUS_DITOLAK], true) ? now() : null,
            ]),

            'users' => $this->storeUser($request),

            default => abort(404),
        };

        $this->logAdminCreate($focus, $createdRecord);

        $this->flushAdminCaches();

        return redirect()
            ->route('admin.index', ['focus' => $focus, 'mode' => 'manage'])
            ->with('success', 'Data berhasil disimpan ke database.');
    }

    private function storeUser(Request $request): User
    {
        abort_unless($request->user()?->isOwner(), 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', Rule::in([User::ROLE_OWNER, User::ROLE_ADMIN, User::ROLE_STAFF])],
            'is_admin' => ['nullable', 'boolean'],
        ]);

        return User::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'is_admin' => $request->boolean('is_admin'),
        ]);
    }

    private function updateUser(Request $request, User $user): bool
    {
        abort_unless($request->user()?->isOwner(), 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['required', Rule::in([User::ROLE_OWNER, User::ROLE_ADMIN, User::ROLE_STAFF])],
            'is_admin' => ['nullable', 'boolean'],
        ]);

        $payload = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'is_admin' => $request->boolean('is_admin'),
        ];

        if (filled($validated['password'] ?? null)) {
            $payload['password'] = Hash::make($validated['password']);
        }

        return $user->update($payload);
    }

    private function canDeleteRecord(string $focus, mixed $recordModel, ?User $user): bool
    {
        if ($focus === 'users') {
            return $user?->isOwner() === true && $user?->id !== $recordModel->id;
        }

        return true;
    }

    private function resolveRecordLabel(string $focus, mixed $recordModel): string
    {
        return match ($focus) {
            'kategori' => 'Kategori "'.($recordModel->nama ?? 'data').'"',
            'produk' => 'Produk "'.($recordModel->nama ?? 'data').'"',
            'promo' => 'Promo "'.($recordModel->kode ?? 'data').'"',
            'pelanggan' => 'Pelanggan "'.($recordModel->nama_lengkap ?? 'data').'"',
            'ulasan' => 'Ulasan "'.($recordModel->nama_pengulas ?? 'data').'"',
            'pesanan' => 'Pesanan "'.($recordModel->kode_pesanan ?? 'data').'"',
            'pembayaran' => 'Pembayaran #'.($recordModel->id ?? 'data'),
            'users' => 'User "'.($recordModel->name ?? 'data').'"',
            default => 'Data',
        };
    }

    private function findRecord(string $focus, int $record): mixed
    {
        return match ($focus) {
            'kategori' => Kategori::query()->findOrFail($record),
            'produk' => Produk::query()->with('kategori:id,nama,slug')->findOrFail($record),
            'promo' => KodePromo::query()->findOrFail($record),
            'pelanggan' => Pelanggan::query()->findOrFail($record),
            'ulasan' => Ulasan::query()->findOrFail($record),
            'pesanan' => Pesanan::query()->findOrFail($record),
            'pembayaran' => Pembayaran::query()->findOrFail($record),
            'users' => User::query()->findOrFail($record),
            default => abort(404),
        };
    }

    private function buildModeTabs(string $focus, bool $isOwner, bool $hasEditingRecord = false): array
    {
        if ($focus === 'dashboard') {
            return [
                ['key' => 'overview', 'label' => 'Ringkasan', 'href' => route('admin.index')],
            ];
        }

        if ($focus === 'aktivitas') {
            return [
                ['key' => 'manage', 'label' => 'Riwayat log', 'href' => route('admin.index', ['focus' => 'aktivitas', 'mode' => 'manage'])],
            ];
        }

        $tabs = [
            ['key' => 'overview', 'label' => 'Ringkasan', 'href' => route('admin.index', ['focus' => $focus, 'mode' => 'overview'])],
            ['key' => 'manage', 'label' => 'Kelola', 'href' => route('admin.index', ['focus' => $focus, 'mode' => 'manage'])],
        ];

        if ($focus !== 'users' || $isOwner) {
            $tabs[] = ['key' => 'create', 'label' => 'Tambah', 'href' => route('admin.index', ['focus' => $focus, 'mode' => 'create'])];
        }

        return $tabs;
    }

    private function shouldLoadDashboardAnalytics(string $focus, string $mode): bool
    {
        return $focus === 'dashboard' && $mode === 'overview';
    }

    private function buildStatusBreakdown(array $pesananByStatus): array
    {
        $map = [
            Pesanan::STATUS_MENUNGGU => ['Menunggu Bayar', 'warning'],
            Pesanan::STATUS_DIPROSES => ['Diproses', 'info'],
            Pesanan::STATUS_SIAPKIRIM => ['Siap Kirim', 'primary'],
            Pesanan::STATUS_SELESAI => ['Selesai', 'success'],
            Pesanan::STATUS_DIBATALKAN => ['Dibatalkan', 'danger'],
        ];

        $total = max(array_sum($pesananByStatus), 1);

        return collect($map)
            ->map(function (array $meta, string $status) use ($pesananByStatus, $total): array {
                $count = (int) ($pesananByStatus[$status] ?? 0);

                return [
                    'label' => $meta[0],
                    'count' => $count,
                    'percentage' => round(($count / $total) * 100, 1),
                    'tone' => $meta[1],
                ];
            })
            ->values()
            ->all();
    }

    private function adminSnapshotCacheKey(bool $isOwner): string
    {
        return 'admin_snapshot:'.($isOwner ? 'owner' : 'admin');
    }

    private function flushAdminCaches(): void
    {
        Cache::forget($this->adminSnapshotCacheKey(true));
        Cache::forget($this->adminSnapshotCacheKey(false));
        DashboardCache::forgetAll();
    }

    private function intValue(mixed $row, string $key): int
    {
        return (int) data_get($row, $key, 0);
    }

    private function safeTableCount(string $table): int
    {
        if (! Schema::hasTable($table)) {
            return 0;
        }

        return (int) DB::table($table)->count();
    }

    private function buildCreateBlueprint(string $focus): array
    {
        return match ($focus) {
            'kategori' => [
                'title' => 'Blueprint Form Kategori',
                'subtitle' => 'Field inti untuk bikin kategori baru.',
                'fields' => ['Nama kategori', 'Slug', 'Deskripsi', 'Status aktif'],
            ],
            'produk' => [
                'title' => 'Blueprint Form Produk',
                'subtitle' => 'Field dasar produk yang perlu disiapkan tim.',
                'fields' => ['Kategori', 'Nama produk', 'Slug', 'Deskripsi', 'Harga dasar', 'Produk customizable', 'Mode sewa', 'Status aktif'],
            ],
            'promo' => [
                'title' => 'Blueprint Form Kode Promo',
                'subtitle' => 'Kontrol kampanye dan kuota promo.',
                'fields' => ['Kode promo', 'Tipe diskon', 'Nilai diskon', 'Minimum order', 'Kuota', 'Tanggal mulai', 'Tanggal berakhir', 'Status aktif'],
            ],
            'pelanggan' => [
                'title' => 'Blueprint Form Pelanggan',
                'subtitle' => 'Input kontak dasar pelanggan.',
                'fields' => ['Nama lengkap', 'Nomor HP', 'Email'],
            ],
            'ulasan' => [
                'title' => 'Blueprint Moderasi Ulasan',
                'subtitle' => 'Bukan create manual penuh, tapi alur moderasi dan publikasi.',
                'fields' => ['Nama pengulas', 'Produk', 'Rating', 'Komentar', 'Foto ulasan', 'Status tampil'],
            ],
            'pesanan' => [
                'title' => 'Blueprint Form Pesanan',
                'subtitle' => 'Input order inti untuk admin operasional.',
                'fields' => ['Pelanggan', 'Produk/item', 'Status pesanan', 'Grand total', 'Batas bayar', 'Catatan pembeli'],
            ],
            'pembayaran' => [
                'title' => 'Blueprint Form Pembayaran',
                'subtitle' => 'Input transaksi dan verifikasi pembayaran.',
                'fields' => ['Pesanan', 'Metode bayar', 'Jumlah dibayar', 'Bukti transfer', 'Status', 'Waktu bayar'],
            ],
            'users' => [
                'title' => 'Blueprint Form User',
                'subtitle' => 'Kontrol akses admin panel.',
                'fields' => ['Nama', 'Email', 'Password', 'Role', 'Akses admin'],
            ],
            default => [
                'title' => 'Blueprint Dasbor',
                'subtitle' => 'Dashboard tidak punya form create.',
                'fields' => [],
            ],
        };
    }

    private function buildFormOptions(string $focus, bool $isOwner, mixed $currentRecord = null): array
    {
        $limit = 60;
        $payload = [];

        if ($focus === 'produk') {
            $payload['kategori'] = $this->buildLimitedOptionSet(
                Kategori::query()->orderBy('nama'),
                ['id', 'nama'],
                'nama',
                $this->selectedOptionIds([old('kategori_id'), data_get($currentRecord, 'kategori_id')]),
                $limit
            );
        }

        if ($focus === 'ulasan') {
            $payload['pesanan'] = $this->buildLimitedOptionSet(
                Pesanan::query()->orderByDesc('id'),
                ['id', 'kode_pesanan'],
                'kode_pesanan',
                $this->selectedOptionIds([old('pesanan_id'), data_get($currentRecord, 'pesanan_id')]),
                $limit
            );

            $payload['produk'] = $this->buildLimitedOptionSet(
                Produk::query()->orderBy('nama'),
                ['id', 'nama'],
                'nama',
                $this->selectedOptionIds([old('produk_id'), data_get($currentRecord, 'produk_id')]),
                $limit
            );
        }

        if ($focus === 'pesanan') {
            $payload['pelanggan'] = $this->buildLimitedOptionSet(
                Pelanggan::query()->orderByDesc('id'),
                ['id', 'nama_lengkap'],
                'nama_lengkap',
                $this->selectedOptionIds([old('pelanggan_id'), data_get($currentRecord, 'pelanggan_id')]),
                $limit
            );

            $payload['promo'] = $this->buildLimitedOptionSet(
                KodePromo::query()->where('is_aktif', true)->orderBy('kode'),
                ['id', 'kode'],
                'kode',
                $this->selectedOptionIds([old('kode_promo_id'), data_get($currentRecord, 'kode_promo_id')]),
                $limit
            );
        }

        if ($focus === 'pembayaran') {
            $payload['pesanan'] = $this->buildLimitedOptionSet(
                Pesanan::query()->orderByDesc('id'),
                ['id', 'kode_pesanan'],
                'kode_pesanan',
                $this->selectedOptionIds([old('pesanan_id'), data_get($currentRecord, 'pesanan_id')]),
                $limit
            );
        }

        if ($focus === 'users') {
            $payload['roles'] = [
                'options' => collect($isOwner ? [
                    ['value' => User::ROLE_OWNER, 'label' => 'Owner'],
                    ['value' => User::ROLE_ADMIN, 'label' => 'Admin'],
                    ['value' => User::ROLE_STAFF, 'label' => 'Staff'],
                ] : []),
                'meta' => [
                    'truncated' => false,
                    'limit' => 0,
                    'hint' => null,
                ],
            ];
        }

        return [
            'options' => collect($payload)->map(fn (array $set) => $set['options'])->all(),
            'meta' => collect($payload)->map(fn (array $set) => $set['meta'])->all(),
        ];
    }

    private function buildLimitedOptionSet(
        Builder $query,
        array $columns,
        string $labelColumn,
        array $selectedIds = [],
        int $limit = 60
    ): array {
        $items = (clone $query)
            ->limit($limit + 1)
            ->get($columns);

        $truncated = $items->count() > $limit;

        if ($truncated) {
            $items = $items->take($limit)->values();
        }

        foreach ($selectedIds as $selectedId) {
            if ($items->contains('id', $selectedId)) {
                continue;
            }

            $selected = (clone $query)
                ->whereKey($selectedId)
                ->first($columns);

            if ($selected) {
                $items->prepend($selected);
            }
        }

        return [
            'options' => $items->unique('id')->values(),
            'meta' => [
                'truncated' => $truncated,
                'limit' => $limit,
                'hint' => $truncated
                    ? 'Opsi dibatasi ke '.$limit.' data teratas agar form tetap ringan. Item yang sedang dipakai tetap dimunculkan.'
                    : null,
                'label_column' => $labelColumn,
            ],
        ];
    }

    private function selectedOptionIds(array $values): array
    {
        return collect($values)
            ->filter(fn ($value) => filled($value) && is_numeric($value))
            ->map(fn ($value) => (int) $value)
            ->unique()
            ->values()
            ->all();
    }

    private function buildFocusMetrics(string $focus, array $snapshot): array
    {
        return match ($focus) {
            'kategori' => [
                ['label' => 'Kategori Aktif', 'value' => $snapshot['kategori']['aktif'], 'hint' => 'Siap tampil di katalog'],
                ['label' => 'Kategori Nonaktif', 'value' => $snapshot['kategori']['nonaktif'], 'hint' => 'Perlu review'],
                ['label' => 'Produk per Kategori', 'value' => round(max(1, $snapshot['produk']['total']) / max(1, $snapshot['kategori']['total']), 1), 'hint' => 'Rata-rata distribusi'],
            ],
            'produk' => [
                ['label' => 'Produk Aktif', 'value' => $snapshot['produk']['aktif'], 'hint' => 'Tampil di etalase'],
                ['label' => 'Customizable', 'value' => $snapshot['produk']['customizable'], 'hint' => 'Bisa disesuaikan'],
                ['label' => 'Mode Sewa', 'value' => $snapshot['produk']['sewa'], 'hint' => 'Produk rental'],
            ],
            'promo' => [
                ['label' => 'Promo Aktif', 'value' => $snapshot['promo']['aktif'], 'hint' => 'Sedang berjalan'],
                ['label' => 'Kuota Hampir Habis', 'value' => $snapshot['promo']['kuota_habis'], 'hint' => 'Perlu ganti kampanye'],
                ['label' => 'Minimum Order Tinggi', 'value' => $snapshot['promo']['minimum_order_tinggi'], 'hint' => 'Segmen high-value'],
            ],
            'pelanggan' => [
                ['label' => 'Repeat Customer', 'value' => $snapshot['pelanggan']['repeat'], 'hint' => 'Pernah order >1x'],
                ['label' => 'Pelanggan Baru 30 Hari', 'value' => $snapshot['pelanggan']['baru_30_hari'], 'hint' => 'Akuisisi terbaru'],
                ['label' => 'Kontak Lengkap', 'value' => $snapshot['pelanggan']['email_terisi'], 'hint' => 'Punya email terisi'],
            ],
            'ulasan' => [
                ['label' => 'Tampil', 'value' => $snapshot['ulasan']['tampil'], 'hint' => 'Live di publik'],
                ['label' => 'Disembunyikan', 'value' => $snapshot['ulasan']['hidden'], 'hint' => 'Menunggu moderasi'],
                ['label' => 'Rating >= 4', 'value' => $snapshot['ulasan']['rating_bagus'], 'hint' => 'Bahan social proof'],
            ],
            'pesanan' => [
                ['label' => 'Menunggu', 'value' => $snapshot['pesanan']['by_status'][Pesanan::STATUS_MENUNGGU] ?? 0, 'hint' => 'Perlu pembayaran'],
                ['label' => 'Diproses', 'value' => $snapshot['pesanan']['by_status'][Pesanan::STATUS_DIPROSES] ?? 0, 'hint' => 'Sedang dikerjakan'],
                ['label' => 'Selesai', 'value' => $snapshot['pesanan']['by_status'][Pesanan::STATUS_SELESAI] ?? 0, 'hint' => 'Closed order'],
            ],
            'pembayaran' => [
                ['label' => 'Pending', 'value' => $snapshot['pembayaran']['by_status'][Pembayaran::STATUS_MENUNGGU] ?? 0, 'hint' => 'Butuh verifikasi'],
                ['label' => 'Lunas', 'value' => $snapshot['pembayaran']['by_status'][Pembayaran::STATUS_LUNAS] ?? 0, 'hint' => 'Siap lanjut proses'],
                ['label' => 'Ditolak', 'value' => $snapshot['pembayaran']['by_status'][Pembayaran::STATUS_DITOLAK] ?? 0, 'hint' => 'Butuh follow-up'],
            ],
            'aktivitas' => [
                ['label' => 'Total log', 'value' => $snapshot['activity_logs']['total'], 'hint' => 'Riwayat aksi admin'],
                ['label' => 'Email log', 'value' => $snapshot['email_logs']['total'], 'hint' => 'Jejak notifikasi sistem'],
                ['label' => 'Modul aktif', 'value' => 9, 'hint' => 'Area panel terpantau'],
            ],
            'users' => [
                ['label' => 'Owner', 'value' => $snapshot['users']['owner'], 'hint' => 'Akses penuh'],
                ['label' => 'Admin', 'value' => $snapshot['users']['role_admin'], 'hint' => 'Operasional inti'],
                ['label' => 'Staff', 'value' => $snapshot['users']['staff'], 'hint' => 'Operator harian'],
            ],
            default => [
                ['label' => 'Email Log', 'value' => $snapshot['email_logs']['total'], 'hint' => 'Jejak notifikasi'],
                ['label' => 'Aktivitas Audit', 'value' => $snapshot['activity_logs']['total'], 'hint' => 'Perubahan tercatat'],
                ['label' => 'Modul Aktif', 'value' => 8, 'hint' => 'Area kerja panel'],
            ],
        };
    }

    private function buildFocusPreview(string $focus, Request $request): array
    {
        $search = trim((string) $request->query('search', ''));

        return match ($focus) {
            'kategori' => [
                'title' => 'Preview Tabel Kategori',
                'subtitle' => 'Data kategori dimuat bertahap per halaman agar panel tetap ringan.',
                'columns' => ['Nama', 'Slug', 'Status', 'Dibuat'],
                'rows' => $this->applyFocusSearch(Kategori::query(), 'kategori', $search)
                    ->latest()
                    ->paginate(10)
                    ->withQueryString()
                    ->through(fn (Kategori $kategori) => [
                        'id' => $kategori->id,
                        'cells' => [
                            $kategori->nama,
                            $kategori->slug,
                            $kategori->is_aktif ? 'Aktif' : 'Nonaktif',
                            optional($kategori->created_at)->format('d M Y'),
                        ],
                        'edit_href' => route('admin.edit', ['focus' => 'kategori', 'record' => $kategori->id]),
                    ]),
            ],
            'produk' => [
                'title' => 'Preview Tabel Produk',
                'subtitle' => 'Produk dimuat per halaman supaya katalog admin tidak berat.',
                'columns' => ['Produk', 'Kategori', 'Mode', 'Harga Dasar'],
                'rows' => $this->applyFocusSearch(Produk::query(), 'produk', $search)
                    ->with('kategori:id,nama')
                    ->latest()
                    ->paginate(8)
                    ->withQueryString()
                    ->through(fn (Produk $produk) => [
                        'id' => $produk->id,
                        'thumbnail_url' => $produk->fotoUtamaUrl(),
                        'cells' => [
                            $produk->nama,
                            $produk->kategori?->nama ?? '-',
                            $produk->is_sewa ? 'Sewa' : 'Jual/Jasa',
                            'Rp '.number_format((int) $produk->harga_dasar, 0, ',', '.'),
                        ],
                        'edit_href' => route('admin.edit', ['focus' => 'produk', 'record' => $produk->id]),
                    ]),
            ],
            'promo' => [
                'title' => 'Preview Kode Promo',
                'subtitle' => 'Promo dimuat sedikit-sedikit per halaman supaya evaluasi kampanye tetap cepat.',
                'columns' => ['Kode', 'Diskon', 'Dipakai', 'Status'],
                'rows' => $this->applyFocusSearch(KodePromo::query(), 'promo', $search)
                    ->latest()
                    ->paginate(10)
                    ->withQueryString()
                    ->through(fn (KodePromo $promo) => [
                        'id' => $promo->id,
                        'cells' => [
                            $promo->kode,
                            $promo->tipe_diskon === 'persentase'
                                ? $promo->nilai_diskon.'%'
                                : 'Rp '.number_format((int) $promo->nilai_diskon, 0, ',', '.'),
                            ($promo->dipakai ?? 0).($promo->kuota ? '/'.$promo->kuota : ''),
                            $promo->is_aktif ? 'Aktif' : 'Nonaktif',
                        ],
                        'edit_href' => route('admin.edit', ['focus' => 'promo', 'record' => $promo->id]),
                    ]),
            ],
            'pelanggan' => [
                'title' => 'Preview Pelanggan',
                'subtitle' => 'Daftar pelanggan dimuat per halaman untuk menjaga performa saat data membesar.',
                'columns' => ['Nama', 'No. HP', 'Email', 'Terdaftar'],
                'rows' => $this->applyFocusSearch(Pelanggan::query(), 'pelanggan', $search)
                    ->latest()
                    ->paginate(10)
                    ->withQueryString()
                    ->through(fn (Pelanggan $pelanggan) => [
                        'id' => $pelanggan->id,
                        'cells' => [
                            $pelanggan->nama_lengkap,
                            $pelanggan->no_hp,
                            $pelanggan->email ?: '-',
                            optional($pelanggan->created_at)->format('d M Y'),
                        ],
                        'edit_href' => route('admin.edit', ['focus' => 'pelanggan', 'record' => $pelanggan->id]),
                    ]),
            ],
            'ulasan' => [
                'title' => 'Preview Ulasan',
                'subtitle' => 'Ulasan dimuat bertahap agar moderasi tetap responsif.',
                'columns' => ['Pengulas', 'Rating', 'Komentar', 'Status'],
                'rows' => $this->applyFocusSearch(Ulasan::query(), 'ulasan', $search)
                    ->latest()
                    ->paginate(10)
                    ->withQueryString()
                    ->through(fn (Ulasan $ulasan) => [
                        'id' => $ulasan->id,
                        'cells' => [
                            $ulasan->nama_pengulas,
                            $ulasan->rating.'/5',
                            str($ulasan->komentar ?: '-')->limit(34)->toString(),
                            $ulasan->is_tampil ? 'Tampil' : 'Sembunyi',
                        ],
                        'edit_href' => route('admin.edit', ['focus' => 'ulasan', 'record' => $ulasan->id]),
                    ]),
            ],
            'pesanan' => [
                'title' => 'Preview Tabel Pesanan',
                'subtitle' => 'Pesanan diambil per halaman supaya beban query tidak meledak saat order bertambah.',
                'columns' => ['Kode', 'Pelanggan', 'Status', 'Total'],
                'rows' => $this->applyFocusSearch(Pesanan::query(), 'pesanan', $search)
                    ->with('pelanggan:id,nama_lengkap')
                    ->latest()
                    ->paginate(10)
                    ->withQueryString()
                    ->through(fn (Pesanan $pesanan) => [
                        'id' => $pesanan->id,
                        'cells' => [
                            $pesanan->kode_pesanan,
                            $pesanan->pelanggan?->nama_lengkap ?? '-',
                            $this->statusLabel($pesanan->status),
                            'Rp '.number_format((int) $pesanan->grand_total, 0, ',', '.'),
                        ],
                        'edit_href' => route('admin.edit', ['focus' => 'pesanan', 'record' => $pesanan->id]),
                    ]),
            ],
            'pembayaran' => [
                'title' => 'Preview Verifikasi Pembayaran',
                'subtitle' => 'Pembayaran dimuat bertahap agar verifikasi lebih ringan.',
                'columns' => ['Pesanan', 'Metode', 'Status', 'Nominal'],
                'rows' => $this->applyFocusSearch(Pembayaran::query(), 'pembayaran', $search)
                    ->with('pesanan:id,kode_pesanan')
                    ->latest()
                    ->paginate(10)
                    ->withQueryString()
                    ->through(fn (Pembayaran $pembayaran) => [
                        'id' => $pembayaran->id,
                        'cells' => [
                            $pembayaran->pesanan?->kode_pesanan ?? '-',
                            $pembayaran->resolvedMetodeLabel(),
                            ucfirst($pembayaran->status),
                            'Rp '.number_format((int) $pembayaran->jumlah_dibayar, 0, ',', '.'),
                        ],
                        'edit_href' => route('admin.edit', ['focus' => 'pembayaran', 'record' => $pembayaran->id]),
                    ]),
            ],
            'users' => [
                'title' => 'Preview Users',
                'subtitle' => 'Data user dimuat per halaman untuk menjaga panel tetap stabil.',
                'columns' => ['Nama', 'Email', 'Role', 'Admin'],
                'rows' => $this->applyFocusSearch(User::query(), 'users', $search)
                    ->latest()
                    ->paginate(10)
                    ->withQueryString()
                    ->through(fn (User $user) => [
                        'id' => $user->id,
                        'cells' => [
                            $user->name,
                            $user->email,
                            ucfirst((string) $user->role),
                            $user->is_admin ? 'Ya' : 'Tidak',
                        ],
                        'edit_href' => route('admin.edit', ['focus' => 'users', 'record' => $user->id]),
                    ]),
            ],
            'aktivitas' => [
                'title' => 'Riwayat Aktivitas Admin',
                'subtitle' => 'Dasbor hanya menampilkan 5 log terbaru. Di sini riwayat lengkap bisa dipantau dan dicari.',
                'columns' => ['Aksi', 'Subjek', 'User', 'Waktu'],
                'actions' => false,
                'rows' => $this->applyFocusSearch(ActivityLog::query(), 'aktivitas', $search)
                    ->with('user:id,name')
                    ->latest()
                    ->paginate(15)
                    ->withQueryString()
                    ->through(fn (ActivityLog $log) => [
                        'id' => $log->id,
                        'cells' => [
                            match ($log->action) {
                                'verifikasi_pembayaran' => 'Pembayaran diverifikasi',
                                'ubah_status_pesanan' => 'Status pesanan diubah',
                                'moderasi_ulasan' => 'Moderasi ulasan',
                                'edit_harga_produk' => 'Harga produk diubah',
                                default => str($log->action)->replace('_', ' ')->title()->toString(),
                            },
                            $log->payload['label']
                                ?? $log->payload['kode_pesanan']
                                ?? $log->payload['nama']
                                ?? (class_basename((string) $log->subject_type) ?: '-'),
                            $log->user?->name ?? 'System',
                            optional($log->created_at)?->timezone('Asia/Jakarta')->format('h:i A, j M Y') ?? '-',
                        ],
                        'edit_href' => null,
                    ]),
            ],
            default => [
                'title' => 'Preview Aktivitas Panel',
                'subtitle' => 'Pusat pembanding sebelum masuk ke benchmark performa.',
                'columns' => ['Aksi', 'Subjek', 'User', 'Waktu'],
                'rows' => $this->applyFocusSearch(ActivityLog::query(), 'dashboard', $search)
                    ->with('user:id,name')
                    ->latest()
                    ->paginate(10)
                    ->withQueryString()
                    ->through(fn (ActivityLog $log) => [
                        'id' => $log->id,
                        'cells' => [
                            str($log->action)->replace('_', ' ')->title()->toString(),
                            $log->payload['label']
                                ?? $log->payload['kode_pesanan']
                                ?? $log->payload['nama']
                                ?? (class_basename((string) $log->subject_type) ?: '-'),
                            $log->user?->name ?? 'System',
                            optional($log->created_at)?->timezone('Asia/Jakarta')->format('h:i A, j M Y') ?? '-',
                        ],
                        'edit_href' => null,
                    ]),
            ],
        };
    }

    private function resolveSearchPlaceholder(string $focus): string
    {
        return match ($focus) {
            'kategori' => 'Cari nama atau slug kategori',
            'produk' => 'Cari produk, kategori, atau deskripsi',
            'promo' => 'Cari kode promo atau tipe diskon',
            'pelanggan' => 'Cari nama, WhatsApp, atau email pelanggan',
            'ulasan' => 'Cari nama pengulas atau isi ulasan',
            'pesanan' => 'Cari kode pesanan, pelanggan, atau status',
            'pembayaran' => 'Cari kode pesanan, metode, atau status pembayaran',
            'aktivitas' => 'Cari aksi, subjek, atau user pada log aktivitas',
            'users' => 'Cari nama, email, atau role admin',
            default => 'Cari kategori, produk, pelanggan, atau kode pesanan',
        };
    }

    private function buildGlobalSearchPayload(string $search, int $groupLimit = 5): array
    {
        $search = trim($search);

        $emptyGroups = collect([
            ['key' => 'kategori', 'label' => 'Kategori', 'count' => 0, 'items' => []],
            ['key' => 'produk', 'label' => 'Produk', 'count' => 0, 'items' => []],
            ['key' => 'pelanggan', 'label' => 'Pelanggan', 'count' => 0, 'items' => []],
            ['key' => 'pesanan', 'label' => 'Pesanan', 'count' => 0, 'items' => []],
        ]);

        if (mb_strlen($search) < 2) {
            return [
                'query' => $search,
                'groups' => $emptyGroups,
                'total' => 0,
            ];
        }

        $like = '%'.$search.'%';
        $hasProdukSku = Schema::hasColumn('produk', 'sku');

        $kategoriQuery = Kategori::query()
            ->select(['id', 'nama', 'slug', 'is_aktif'])
            ->where(function (Builder $query) use ($like) {
                $query->where('nama', 'like', $like)
                    ->orWhere('slug', 'like', $like);
            });

        $kategoriCount = (clone $kategoriQuery)->count();
        $kategoriItems = $kategoriQuery
            ->orderBy('nama')
            ->limit($groupLimit)
            ->get()
            ->map(fn (Kategori $kategori) => [
                'id' => $kategori->id,
                'title' => $kategori->nama,
                'identifier' => $kategori->slug,
                'description' => 'Kategori produk untuk pengelompokan katalog Sadita.',
                'badge' => 'Kategori',
                'status' => $kategori->is_aktif ? 'Aktif' : 'Nonaktif',
                'url' => route('admin.edit', ['focus' => 'kategori', 'record' => $kategori->id]),
                'action_label' => 'Edit kategori',
            ])
            ->values()
            ->all();

        $produkQuery = Produk::query()
            ->select(['id', 'nama', 'slug', 'kategori_id', 'is_aktif'])
            ->with('kategori:id,nama')
            ->where(function (Builder $query) use ($like, $hasProdukSku) {
                $query->where('nama', 'like', $like)
                    ->orWhere('slug', 'like', $like);

                if ($hasProdukSku) {
                    $query->orWhere('sku', 'like', $like);
                }
            });

        $produkCount = (clone $produkQuery)->count();
        $produkItems = $produkQuery
            ->orderBy('nama')
            ->limit($groupLimit)
            ->get()
            ->map(fn (Produk $produk) => [
                'id' => $produk->id,
                'title' => $produk->nama,
                'identifier' => $produk->slug,
                'description' => $produk->kategori?->nama
                    ? 'Kategori: '.$produk->kategori->nama
                    : 'Produk Sadita tanpa kategori aktif.',
                'badge' => 'Produk',
                'status' => $produk->is_aktif ? 'Aktif' : 'Nonaktif',
                'url' => route('admin.edit', ['focus' => 'produk', 'record' => $produk->id]),
                'action_label' => 'Edit produk',
            ])
            ->values()
            ->all();

        $pelangganQuery = Pelanggan::query()
            ->select(['id', 'nama_lengkap', 'email', 'no_hp'])
            ->where(function (Builder $query) use ($like) {
                $query->where('nama_lengkap', 'like', $like)
                    ->orWhere('email', 'like', $like)
                    ->orWhere('no_hp', 'like', $like);
            });

        $pelangganCount = (clone $pelangganQuery)->count();
        $pelangganItems = $pelangganQuery
            ->orderBy('nama_lengkap')
            ->limit($groupLimit)
            ->get()
            ->map(fn (Pelanggan $pelanggan) => [
                'id' => $pelanggan->id,
                'title' => $pelanggan->nama_lengkap,
                'identifier' => $pelanggan->no_hp,
                'description' => $pelanggan->email ?: 'Kontak email belum diisi.',
                'badge' => 'Pelanggan',
                'status' => null,
                'url' => route('admin.edit', ['focus' => 'pelanggan', 'record' => $pelanggan->id]),
                'action_label' => 'Kelola pelanggan',
            ])
            ->values()
            ->all();

        $pesananQuery = Pesanan::query()
            ->select(['id', 'kode_pesanan', 'status', 'pelanggan_id'])
            ->with('pelanggan:id,nama_lengkap')
            ->where('kode_pesanan', 'like', $like);

        $pesananCount = (clone $pesananQuery)->count();
        $pesananItems = $pesananQuery
            ->latest('id')
            ->limit($groupLimit)
            ->get()
            ->map(fn (Pesanan $pesanan) => [
                'id' => $pesanan->id,
                'title' => $pesanan->kode_pesanan,
                'identifier' => $pesanan->pelanggan?->nama_lengkap ?? 'Tanpa pelanggan',
                'description' => 'Status transaksi: '.Str::headline($pesanan->status),
                'badge' => 'Pesanan',
                'status' => Str::headline($pesanan->status),
                'url' => route('admin.edit', ['focus' => 'pesanan', 'record' => $pesanan->id]),
                'action_label' => 'Buka pesanan',
            ])
            ->values()
            ->all();

        $groups = collect([
            ['key' => 'kategori', 'label' => 'Kategori', 'count' => $kategoriCount, 'items' => $kategoriItems],
            ['key' => 'produk', 'label' => 'Produk', 'count' => $produkCount, 'items' => $produkItems],
            ['key' => 'pelanggan', 'label' => 'Pelanggan', 'count' => $pelangganCount, 'items' => $pelangganItems],
            ['key' => 'pesanan', 'label' => 'Pesanan', 'count' => $pesananCount, 'items' => $pesananItems],
        ]);

        return [
            'query' => $search,
            'groups' => $groups,
            'total' => (int) $groups->sum('count'),
        ];
    }

    private function applyFocusSearch(Builder $query, string $focus, string $search): Builder
    {
        if ($search === '') {
            return $query;
        }

        $like = '%'.$search.'%';

        return match ($focus) {
            'kategori' => $query->where(function (Builder $inner) use ($like) {
                $inner->where('nama', 'like', $like)
                    ->orWhere('slug', 'like', $like)
                    ->orWhere('deskripsi', 'like', $like);
            }),
            'produk' => $query->where(function (Builder $inner) use ($like) {
                $inner->where('nama', 'like', $like)
                    ->orWhere('slug', 'like', $like)
                    ->orWhere('deskripsi', 'like', $like)
                    ->orWhereHas('kategori', fn (Builder $kategori) => $kategori->where('nama', 'like', $like));
            }),
            'promo' => $query->where(function (Builder $inner) use ($like) {
                $inner->where('kode', 'like', $like)
                    ->orWhere('tipe_diskon', 'like', $like)
                    ->orWhere('deskripsi', 'like', $like);
            }),
            'pelanggan' => $query->where(function (Builder $inner) use ($like) {
                $inner->where('nama_lengkap', 'like', $like)
                    ->orWhere('no_hp', 'like', $like)
                    ->orWhere('email', 'like', $like);
            }),
            'ulasan' => $query->where(function (Builder $inner) use ($like) {
                $inner->where('nama_pengulas', 'like', $like)
                    ->orWhere('komentar', 'like', $like);
            }),
            'pesanan' => $query->where(function (Builder $inner) use ($like) {
                $inner->where('kode_pesanan', 'like', $like)
                    ->orWhere('status', 'like', $like)
                    ->orWhereHas('pelanggan', fn (Builder $pelanggan) => $pelanggan->where('nama_lengkap', 'like', $like));
            }),
            'pembayaran' => $query->where(function (Builder $inner) use ($like) {
                $inner->where('metode', 'like', $like)
                    ->orWhere('status', 'like', $like)
                    ->orWhere('bukti_transfer', 'like', $like)
                    ->orWhereHas('pesanan', fn (Builder $pesanan) => $pesanan->where('kode_pesanan', 'like', $like));
            }),
            'users' => $query->where(function (Builder $inner) use ($like) {
                $inner->where('name', 'like', $like)
                    ->orWhere('email', 'like', $like)
                    ->orWhere('role', 'like', $like);
            }),
            'aktivitas' => $query->where(function (Builder $inner) use ($like) {
                $inner->where('action', 'like', $like)
                    ->orWhere('subject_type', 'like', $like)
                    ->orWhereHas('user', fn (Builder $user) => $user->where('name', 'like', $like));
            }),
            default => $query->where(function (Builder $inner) use ($like) {
                $inner->where('action', 'like', $like)
                    ->orWhere('subject_type', 'like', $like)
                    ->orWhereHas('user', fn (Builder $user) => $user->where('name', 'like', $like));
            }),
        };
    }

    private function buildQuickActions(string $focus, bool $isOwner): array
    {
        $base = [
            ['label' => 'Kembali ke dasbor', 'href' => route('admin.index'), 'kind' => 'secondary'],
            ['label' => 'Buka daftar pesanan', 'href' => route('admin.index', ['focus' => 'pesanan', 'mode' => 'manage']), 'kind' => 'secondary'],
        ];

        $focusActions = match ($focus) {
            'kategori' => [
                ['label' => 'Tambah kategori', 'href' => route('admin.index', ['focus' => 'kategori', 'mode' => 'create']), 'kind' => 'primary'],
                ['label' => 'Kelola produk', 'href' => route('admin.index', ['focus' => 'produk', 'mode' => 'manage']), 'kind' => 'secondary'],
            ],
            'produk' => [
                ['label' => 'Tambah produk', 'href' => route('admin.index', ['focus' => 'produk', 'mode' => 'create']), 'kind' => 'primary'],
                ['label' => 'Kelola kategori', 'href' => route('admin.index', ['focus' => 'kategori', 'mode' => 'manage']), 'kind' => 'secondary'],
            ],
            'promo' => [
                ['label' => 'Buat promo', 'href' => route('admin.index', ['focus' => 'promo', 'mode' => 'create']), 'kind' => 'primary'],
                ['label' => 'Cek order terpengaruh promo', 'href' => route('admin.index', ['focus' => 'pesanan', 'mode' => 'overview']), 'kind' => 'secondary'],
            ],
            'pelanggan' => [
                ['label' => 'Tambah pelanggan', 'href' => route('admin.index', ['focus' => 'pelanggan', 'mode' => 'create']), 'kind' => 'primary'],
                ['label' => 'Lihat pelanggan loyal', 'href' => route('admin.index', ['focus' => 'pelanggan', 'mode' => 'overview']), 'kind' => 'secondary'],
            ],
            'ulasan' => [
                ['label' => 'Moderasi ulasan', 'href' => route('admin.index', ['focus' => 'ulasan', 'mode' => 'manage']), 'kind' => 'primary'],
                ['label' => 'Lihat produk terkait', 'href' => route('admin.index', ['focus' => 'produk', 'mode' => 'manage']), 'kind' => 'secondary'],
            ],
            'pesanan' => [
                ['label' => 'Buat pesanan', 'href' => route('admin.index', ['focus' => 'pesanan', 'mode' => 'create']), 'kind' => 'primary'],
                ['label' => 'Lihat pembayaran', 'href' => route('admin.index', ['focus' => 'pembayaran', 'mode' => 'manage']), 'kind' => 'secondary'],
            ],
            'pembayaran' => [
                ['label' => 'Tambah pembayaran', 'href' => route('admin.index', ['focus' => 'pembayaran', 'mode' => 'create']), 'kind' => 'primary'],
                ['label' => 'Lihat pesanan tertahan', 'href' => route('admin.index', ['focus' => 'pesanan', 'mode' => 'overview']), 'kind' => 'secondary'],
            ],
            'aktivitas' => [
                ['label' => 'Kembali ke dasbor', 'href' => route('admin.index'), 'kind' => 'primary'],
                ['label' => 'Buka pesanan', 'href' => route('admin.index', ['focus' => 'pesanan', 'mode' => 'manage']), 'kind' => 'secondary'],
            ],
            'users' => $isOwner ? [
                ['label' => 'Tambah user', 'href' => route('admin.index', ['focus' => 'users', 'mode' => 'create']), 'kind' => 'primary'],
                ['label' => 'Audit role aktif', 'href' => route('admin.index', ['focus' => 'users', 'mode' => 'overview']), 'kind' => 'secondary'],
            ] : [],
            default => [
                ['label' => 'Kelola pesanan hari ini', 'href' => route('admin.index', ['focus' => 'pesanan', 'mode' => 'manage']), 'kind' => 'primary'],
                ['label' => 'Verifikasi pembayaran', 'href' => route('admin.index', ['focus' => 'pembayaran', 'mode' => 'manage']), 'kind' => 'secondary'],
            ],
        };

        return [...$focusActions, ...$base];
    }

    private function buildFormSections(string $focus): array
    {
        return match ($focus) {
            'kategori' => [
                [
                    'title' => 'Informasi kategori',
                    'fields' => [
                        ['label' => 'Nama kategori', 'type' => 'text', 'placeholder' => 'Contoh: Papan Ucapan Premium'],
                        ['label' => 'Slug', 'type' => 'text', 'placeholder' => 'papan-ucapan-premium'],
                    ],
                ],
                [
                    'title' => 'Publikasi',
                    'fields' => [
                        ['label' => 'Deskripsi singkat', 'type' => 'textarea', 'placeholder' => 'Dipakai untuk kartu kategori dan navigasi katalog'],
                        ['label' => 'Status tampil', 'type' => 'toggle', 'placeholder' => 'Aktif'],
                    ],
                ],
            ],
            'produk' => [
                [
                    'title' => 'Data utama produk',
                    'fields' => [
                        ['label' => 'Nama produk', 'type' => 'text', 'placeholder' => 'Papan Duka Cita Eksklusif'],
                        ['label' => 'Kategori', 'type' => 'select', 'placeholder' => 'Pilih kategori'],
                        ['label' => 'Harga dasar', 'type' => 'currency', 'placeholder' => 'Rp 1.200.000'],
                    ],
                ],
                [
                    'title' => 'Aturan layanan',
                    'fields' => [
                        ['label' => 'Mode layanan', 'type' => 'chips', 'placeholder' => 'Sewa / Jasa / Hybrid'],
                        ['label' => 'Bisa custom', 'type' => 'toggle', 'placeholder' => 'Ya'],
                        ['label' => 'Deskripsi katalog', 'type' => 'textarea', 'placeholder' => 'Ringkas, jelas, mudah dibaca admin dan pelanggan'],
                    ],
                ],
            ],
            'promo' => [
                [
                    'title' => 'Identitas promo',
                    'fields' => [
                        ['label' => 'Kode promo', 'type' => 'text', 'placeholder' => 'SADITA10'],
                        ['label' => 'Tipe diskon', 'type' => 'select', 'placeholder' => 'Persentase / nominal'],
                        ['label' => 'Nilai diskon', 'type' => 'currency', 'placeholder' => '10% atau Rp 50.000'],
                    ],
                ],
                [
                    'title' => 'Periode kampanye',
                    'fields' => [
                        ['label' => 'Tanggal mulai', 'type' => 'date', 'placeholder' => 'Pilih tanggal'],
                        ['label' => 'Tanggal berakhir', 'type' => 'date', 'placeholder' => 'Pilih tanggal'],
                        ['label' => 'Kuota', 'type' => 'number', 'placeholder' => '50'],
                    ],
                ],
            ],
            'pelanggan' => [
                [
                    'title' => 'Kontak pelanggan',
                    'fields' => [
                        ['label' => 'Nama lengkap', 'type' => 'text', 'placeholder' => 'Nama pelanggan'],
                        ['label' => 'No. WhatsApp', 'type' => 'text', 'placeholder' => '08xxxxxxxxxx'],
                        ['label' => 'Email', 'type' => 'text', 'placeholder' => 'opsional@email.com'],
                    ],
                ],
            ],
            'ulasan' => [
                [
                    'title' => 'Moderasi ulasan',
                    'fields' => [
                        ['label' => 'Nama pengulas', 'type' => 'text', 'placeholder' => 'Nama tampil publik'],
                        ['label' => 'Komentar', 'type' => 'textarea', 'placeholder' => 'Isi testimoni pelanggan'],
                        ['label' => 'Status tampil', 'type' => 'toggle', 'placeholder' => 'Tampil di website'],
                    ],
                ],
            ],
            'pesanan' => [
                [
                    'title' => 'Informasi pesanan',
                    'fields' => [
                        ['label' => 'Kode pesanan', 'type' => 'text', 'placeholder' => 'SDT-20260705-001'],
                        ['label' => 'Pelanggan', 'type' => 'select', 'placeholder' => 'Cari pelanggan'],
                        ['label' => 'Produk utama', 'type' => 'select', 'placeholder' => 'Pilih item'],
                    ],
                ],
                [
                    'title' => 'Kontrol operasional',
                    'fields' => [
                        ['label' => 'Status pesanan', 'type' => 'chips', 'placeholder' => 'Menunggu / Diproses / Selesai'],
                        ['label' => 'Grand total', 'type' => 'currency', 'placeholder' => 'Rp 0'],
                        ['label' => 'Catatan admin', 'type' => 'textarea', 'placeholder' => 'Arahan produksi, pengiriman, atau revisi pelanggan'],
                    ],
                ],
            ],
            'pembayaran' => [
                [
                    'title' => 'Verifikasi transaksi',
                    'fields' => [
                        ['label' => 'Pesanan terkait', 'type' => 'select', 'placeholder' => 'Pilih kode pesanan'],
                        ['label' => 'Metode bayar', 'type' => 'select', 'placeholder' => 'Transfer / cash / QRIS'],
                        ['label' => 'Jumlah dibayar', 'type' => 'currency', 'placeholder' => 'Rp 0'],
                    ],
                ],
                [
                    'title' => 'Validasi',
                    'fields' => [
                        ['label' => 'Bukti transfer', 'type' => 'upload', 'placeholder' => 'Upload gambar'],
                        ['label' => 'Status', 'type' => 'chips', 'placeholder' => 'Pending / Lunas / Ditolak'],
                    ],
                ],
            ],
            'users' => [
                [
                    'title' => 'Akun admin',
                    'fields' => [
                        ['label' => 'Nama', 'type' => 'text', 'placeholder' => 'Nama lengkap tim'],
                        ['label' => 'Email', 'type' => 'text', 'placeholder' => 'email@domain.com'],
                        ['label' => 'Password awal', 'type' => 'password', 'placeholder' => 'Minimal 8 karakter'],
                    ],
                ],
                [
                    'title' => 'Hak akses',
                    'fields' => [
                        ['label' => 'Role', 'type' => 'select', 'placeholder' => 'Owner / Admin / Staff'],
                        ['label' => 'Akses admin', 'type' => 'toggle', 'placeholder' => 'Aktif'],
                    ],
                ],
            ],
            default => [],
        };
    }

    private function buildActivityFeed(User $user): array
    {
        return $this->notificationQuery($user)
            ->with('user:id,name')
            ->latest()
            ->limit(20)
            ->get()
            ->map(fn (ActivityLog $log) => [
                'category' => $log->action === 'pesanan_baru_masuk' ? 'order' : 'system',
                'title' => match ($log->action) {
                    'pesanan_baru_masuk' => 'Pesanan baru masuk',
                    'verifikasi_pembayaran' => 'Pembayaran diverifikasi',
                    'ubah_status_pesanan' => 'Status pesanan diubah',
                    'moderasi_ulasan' => 'Moderasi ulasan',
                    'edit_harga_produk' => 'Harga produk diubah',
                    default => str($log->action)->replace('_', ' ')->title()->toString(),
                },
                'user' => $log->user?->name ?? 'System',
                'subject' => $log->payload['label']
                    ?? $log->payload['kode_pesanan']
                    ?? $log->payload['nama']
                    ?? (class_basename((string) $log->subject_type) ?: '-'),
                'stamp' => optional($log->created_at)?->toISOString(),
                'time' => $this->formatNotificationTime($log->created_at),
            ])->all();
    }

    private function buildNotificationUnreadCount(User $user): int
    {
        return $this->notificationQuery($user)
            ->when(
                $user->notifications_seen_at,
                fn (Builder $query) => $query->where('created_at', '>', $user->notifications_seen_at)
            )
            ->count();
    }

    private function notificationQuery(User $user): Builder
    {
        return ActivityLog::query()
            ->when(
                $user->isStaff(),
                fn (Builder $query) => $query->where('action', 'pesanan_baru_masuk')
            );
    }

    private function formatNotificationTime(mixed $timestamp): string
    {
        if (! $timestamp) {
            return '-';
        }

        $time = Carbon::parse($timestamp)->timezone('Asia/Jakarta');
        $now = now()->timezone('Asia/Jakarta');
        $seconds = max(0, (int) floor((float) $time->diffInSeconds($now)));

        if ($seconds < 60) {
            return $seconds.' detik lalu';
        }

        $minutes = max(1, (int) floor((float) $time->diffInMinutes($now)));
        if ($minutes < 60) {
            return $minutes.' menit lalu';
        }

        $hours = max(1, (int) floor((float) $time->diffInHours($now)));
        if ($hours < 24) {
            return $hours.' jam lalu';
        }

        $days = max(1, (int) floor((float) $time->diffInDays($now)));
        if ($days < 7) {
            return $days.' hari lalu';
        }

        if ($days < 8) {
            return '1 minggu lalu';
        }

        return $time->translatedFormat('d M Y');
    }

    private function logAdminCreate(string $focus, mixed $record): void
    {
        if (! $record instanceof Model) {
            return;
        }

        ActivityLogger::log('buat_'.$focus, $record, [
            'focus' => $focus,
            'label' => $this->resolveRecordLabel($focus, $record),
        ]);
    }

    private function logAdminUpdate(string $focus, Model $record, array $beforeSnapshot): void
    {
        $logged = false;

        if (
            $focus === 'produk' &&
            array_key_exists('harga_dasar', $beforeSnapshot) &&
            (int) $beforeSnapshot['harga_dasar'] !== (int) $record->getAttribute('harga_dasar')
        ) {
            ActivityLogger::editHargaProduk(
                $record,
                (int) $beforeSnapshot['harga_dasar'],
                (int) $record->getAttribute('harga_dasar')
            );
            $logged = true;
        }

        if (
            $focus === 'pesanan' &&
            array_key_exists('status', $beforeSnapshot) &&
            (string) $beforeSnapshot['status'] !== (string) $record->getAttribute('status')
        ) {
            ActivityLogger::ubahStatusPesanan(
                $record,
                (string) $beforeSnapshot['status'],
                (string) $record->getAttribute('status')
            );
            $logged = true;
        }

        if (
            $focus === 'pembayaran' &&
            array_key_exists('status', $beforeSnapshot) &&
            (string) $beforeSnapshot['status'] !== (string) $record->getAttribute('status')
        ) {
            ActivityLogger::verifikasiPembayaran(
                $record,
                (string) $record->getAttribute('status'),
                $record->getAttribute('alasan_penolakan')
            );
            $logged = true;
        }

        if (
            $focus === 'ulasan' &&
            array_key_exists('is_tampil', $beforeSnapshot) &&
            (bool) $beforeSnapshot['is_tampil'] !== (bool) $record->getAttribute('is_tampil')
        ) {
            ActivityLogger::moderasiUlasan($record, (bool) $record->getAttribute('is_tampil'));
            $logged = true;
        }

        if ($logged) {
            return;
        }

        ActivityLogger::log('update_'.$focus, $record, [
            'focus' => $focus,
            'label' => $this->resolveRecordLabel($focus, $record),
        ]);
    }

    private function logAdminDelete(string $focus, Model $record, string $recordLabel): void
    {
        ActivityLogger::log('hapus_'.$focus, $record, [
            'focus' => $focus,
            'label' => $recordLabel,
        ]);
    }

    private function snapshotRecordForLog(Model $record): array
    {
        return $record->attributesToArray();
    }

    private function statusLabel(string $status): string
    {
        return match ($status) {
            Pesanan::STATUS_MENUNGGU => 'Menunggu Bayar',
            Pesanan::STATUS_DIPROSES => 'Diproses',
            Pesanan::STATUS_SIAPKIRIM => 'Siap Kirim',
            Pesanan::STATUS_SELESAI => 'Selesai',
            Pesanan::STATUS_DIBATALKAN => 'Dibatalkan',
            default => $status,
        };
    }
}
