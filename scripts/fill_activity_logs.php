<?php

declare(strict_types=1);

use App\Models\ActivityLog;
use App\Models\Kategori;
use App\Models\KodePromo;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Ulasan;
use App\Models\User;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Carbon;

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$userId = User::query()->value('id');
$kategori = Kategori::query()->first();
$produk = Produk::query()->first();
$promo = KodePromo::query()->first();
$pelanggan = Pelanggan::query()->first();
$pesanan = Pesanan::query()->first();
$pembayaran = Pembayaran::query()->with('pesanan')->first();
$ulasan = Ulasan::query()->first();
$adminUser = User::query()->first();

$definitions = [
    ['action' => 'buat_kategori', 'focus' => 'kategori', 'record' => $kategori, 'label' => $kategori?->nama ?? 'Kategori Dekorasi'],
    ['action' => 'update_kategori', 'focus' => 'kategori', 'record' => $kategori, 'label' => $kategori?->nama ?? 'Kategori Dekorasi'],
    ['action' => 'buat_produk', 'focus' => 'produk', 'record' => $produk, 'label' => $produk?->nama ?? 'Produk Sadita'],
    ['action' => 'edit_harga_produk', 'focus' => 'produk', 'record' => $produk, 'label' => $produk?->nama ?? 'Produk Sadita'],
    ['action' => 'update_produk', 'focus' => 'produk', 'record' => $produk, 'label' => $produk?->nama ?? 'Produk Sadita'],
    ['action' => 'buat_promo', 'focus' => 'promo', 'record' => $promo, 'label' => $promo?->kode ?? 'PROMO-SADITA'],
    ['action' => 'update_promo', 'focus' => 'promo', 'record' => $promo, 'label' => $promo?->kode ?? 'PROMO-SADITA'],
    ['action' => 'buat_pelanggan', 'focus' => 'pelanggan', 'record' => $pelanggan, 'label' => $pelanggan?->nama_lengkap ?? 'Pelanggan Baru'],
    ['action' => 'update_pelanggan', 'focus' => 'pelanggan', 'record' => $pelanggan, 'label' => $pelanggan?->nama_lengkap ?? 'Pelanggan Baru'],
    ['action' => 'buat_pesanan', 'focus' => 'pesanan', 'record' => $pesanan, 'label' => $pesanan?->kode_pesanan ?? 'SDT-TEST-001'],
    ['action' => 'ubah_status_pesanan', 'focus' => 'pesanan', 'record' => $pesanan, 'label' => $pesanan?->kode_pesanan ?? 'SDT-TEST-001'],
    ['action' => 'update_pesanan', 'focus' => 'pesanan', 'record' => $pesanan, 'label' => $pesanan?->kode_pesanan ?? 'SDT-TEST-001'],
    ['action' => 'buat_pembayaran', 'focus' => 'pembayaran', 'record' => $pembayaran, 'label' => $pembayaran?->pesanan?->kode_pesanan ?? 'Pembayaran Manual'],
    ['action' => 'verifikasi_pembayaran', 'focus' => 'pembayaran', 'record' => $pembayaran, 'label' => $pembayaran?->pesanan?->kode_pesanan ?? 'Pembayaran Manual'],
    ['action' => 'update_pembayaran', 'focus' => 'pembayaran', 'record' => $pembayaran, 'label' => $pembayaran?->pesanan?->kode_pesanan ?? 'Pembayaran Manual'],
    ['action' => 'buat_ulasan', 'focus' => 'ulasan', 'record' => $ulasan, 'label' => $ulasan?->nama_pengulas ?? 'Ulasan Pelanggan'],
    ['action' => 'moderasi_ulasan', 'focus' => 'ulasan', 'record' => $ulasan, 'label' => $ulasan?->nama_pengulas ?? 'Ulasan Pelanggan'],
    ['action' => 'buat_users', 'focus' => 'users', 'record' => $adminUser, 'label' => $adminUser?->name ?? 'Admin Sadita'],
    ['action' => 'update_users', 'focus' => 'users', 'record' => $adminUser, 'label' => $adminUser?->name ?? 'Admin Sadita'],
    ['action' => 'hapus_promo', 'focus' => 'promo', 'record' => $promo, 'label' => $promo?->kode ?? 'PROMO-SADITA'],
];

foreach ($definitions as $index => $item) {
    $createdAt = Carbon::now('Asia/Jakarta')->subMinutes(($index + 1) * 7);

    ActivityLog::query()->create([
        'user_id' => $userId,
        'action' => $item['action'],
        'subject_type' => $item['record'] ? get_class($item['record']) : null,
        'subject_id' => $item['record']?->getKey(),
        'payload' => [
            'focus' => $item['focus'],
            'label' => $item['label'],
        ],
        'ip_address' => '127.0.0.1',
        'created_at' => $createdAt,
        'updated_at' => $createdAt,
    ]);
}

echo json_encode([
    'inserted' => count($definitions),
    'total_logs' => ActivityLog::query()->count(),
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL;
