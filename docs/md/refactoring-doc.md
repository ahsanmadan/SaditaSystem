# Refactoring Doc - SaditaSystem

Dokumen ini berisi area yang sudah atau sedang dirapikan, serta arah refactoring yang dianggap penting untuk project SaditaSystem.

## 1. Area yang Sudah Dirapikan

### 1.1 Optimasi Admin Panel

Beberapa query admin panel sebelumnya terasa berat, terutama pada dashboard yang memuat statistik dan ringkasan data. Perbaikannya difokuskan pada:

- mengurangi beban query statistik dashboard
- memisahkan logika KPI ke service khusus
- menambahkan cache untuk data dashboard

Area kode yang terkait:

- `app/Services/Analytics/DashboardKpiService.php`
- `app/Support/DashboardCache.php`
- `app/Filament/Widgets/StatsOverviewWidget.php`

Referensi branch/remote yang masih terlihat:

- `origin/feature/optimasi-cache-dashboard`

### 1.2 Perbaikan Akses Admin Lokal

Area akses admin lokal pernah menjadi perhatian dalam pengembangan, terutama terkait environment lokal dan navigasi/admin resource. Namun, branch spesifik yang mendokumentasikan perbaikan ini tidak terlihat lagi pada daftar branch aktif saat ini.

Karena itu, bagian ini sebaiknya dipahami sebagai riwayat perbaikan yang pernah dilakukan, bukan referensi branch aktif yang masih tersedia.

### 1.3 Perbaikan Flow Order

Flow order publik sebelumnya gagal ketika input waktu pengiriman dari UI langsung disimpan ke kolom database bertipe `time`. Refactor kecil dilakukan dengan:

- membuat value dropdown menjadi format waktu valid
- menambah normalizer waktu di controller agar request lama tetap aman

Area kode yang terkait:

- `app/Http/Controllers/OrderController.php`
- `resources/views/pages/home/order.blade.php`

## 2. Area yang Masih Perlu Refactoring

### 2.1 Invoice Status

Tampilan invoice saat ini sudah memiliki logika dasar untuk membaca status pesanan dan pembayaran. Namun, area ini masih dapat disempurnakan agar seluruh tampilan status, label pembayaran, dan aksi lanjutan benar-benar konsisten pada semua kondisi pesanan.

### 2.2 Seeder

Seeder masih perlu dirapikan, terutama pada bagian data transaksi, karena:

- `DatabaseSeeder` dan `KatalogSeeder` sudah cukup aman untuk dijalankan ulang karena memakai pola `updateOrCreate` atau `updateOrInsert`
- `TransactionSeeder` masih menghasilkan data baru setiap kali dijalankan, sehingga tidak bersifat idempotent
- pemisahan tanggung jawab antar seeder masih bisa dibuat lebih jelas agar maintenance lebih mudah

### 2.3 Copy Public UI

Beberapa tampilan public masih memiliki:

- copy lama yang belum sepenuhnya sinkron dengan scope bisnis SaditaSystem saat ini
- wording yang masih bisa dipoles agar lebih konsisten antara landing page, order flow, dan dokumentasi fitur

### 2.4 Testing Flow

Flow testing masih perlu diperhatikan karena environment test dan environment CI belum sepenuhnya identik. Saat ini:

- `phpunit.xml` menggunakan SQLite in-memory
- workflow GitHub Actions menggunakan file SQLite fisik di `database/database.sqlite`

Perbedaan ini dapat memunculkan perilaku test yang berbeda pada kondisi tertentu, terutama ketika migration atau seed data berkembang.

## 3. Prinsip Refactoring yang Dipakai

- perubahan dibuat sekecil mungkin tapi tetap menyelesaikan masalah inti
- hindari refactor besar yang menyentuh area tidak relevan
- utamakan alur bisnis utama: create order, admin review, pembayaran, dan dashboard operasional
- verifikasi dilakukan lewat build, route check, atau browser test jika memungkinkan
