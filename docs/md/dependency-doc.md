# Dependency Doc - SaditaSystem

Dokumen ini berisi rincian dependency yang digunakan oleh project SaditaSystem, baik untuk runtime aplikasi, kebutuhan development, maupun layanan eksternal yang terhubung ke sistem.

Dokumen ini disusun berdasarkan dependency yang benar-benar terdaftar di `composer.json`, `composer.lock`, `package.json`, dan `package-lock.json`, lalu dicocokkan dengan penggunaan aktual di dalam kode project.

## 1. Tujuan Dokumen

Dependency documentation ini digunakan untuk:

- mencatat pustaka, framework, dan alat bantu yang dipakai project
- menjaga konsistensi versi antar lingkungan development
- membedakan dependency runtime dan dependency development
- mendokumentasikan sumber package dan lisensinya
- mencatat layanan eksternal yang diintegrasikan ke aplikasi
- memantau status keamanan dependency melalui audit dependency

## 2. Dependency Runtime Backend

Dependency runtime backend adalah package yang dibutuhkan saat aplikasi Laravel berjalan.

### 2.1 `laravel/framework`

- versi: `v13.6.0`
- jenis: runtime backend
- fungsi: framework utama aplikasi
- penggunaan:
  - routing
  - controller
  - migration
  - validation
  - queue, session, cache
  - Eloquent ORM
- sumber: `https://github.com/laravel/framework.git`
- lisensi: `MIT`
- status penggunaan: aktif

### 2.2 `filament/filament`

- versi: `v5.6.0`
- jenis: runtime backend
- fungsi: framework admin panel
- penggunaan:
  - `app/Filament/Resources`
  - `app/Filament/Widgets`
  - `app/Providers/Filament/AdminPanelProvider.php`
- sumber: `https://github.com/filamentphp/panels.git`
- lisensi: `MIT`
- status penggunaan: aktif

### 2.3 `laravel/tinker`

- versi: `v3.0.2`
- jenis: runtime backend
- fungsi: shell interaktif Laravel untuk inspeksi data dan debugging
- sumber: `https://github.com/laravel/tinker.git`
- lisensi: `MIT`
- status penggunaan: aktif sebagai alat bantu runtime/development

## 3. Dependency Development Backend

Dependency development backend dipakai saat proses pengembangan, testing, formatting, dan debugging.

### 3.1 `fakerphp/faker`

- versi: `v1.24.1`
- jenis: development backend
- fungsi: generator data dummy
- penggunaan:
  - factory
  - seeder transaksi/testing
- sumber: `https://github.com/FakerPHP/Faker.git`
- lisensi: `MIT`

### 3.2 `laravel/pail`

- versi: `v1.2.6`
- jenis: development backend
- fungsi: membaca log Laravel secara realtime
- penggunaan:
  - dipanggil pada script `composer run dev`
- sumber: `https://github.com/laravel/pail.git`
- lisensi: `MIT`

### 3.3 `laravel/pint`

- versi: `v1.29.0`
- jenis: development backend
- fungsi: formatter kode PHP
- penggunaan:
  - script `composer run format`
- sumber: `https://github.com/laravel/pint.git`
- lisensi: `MIT`

### 3.4 `mockery/mockery`

- versi: `1.6.12`
- jenis: development backend
- fungsi: membuat mock object untuk pengujian
- sumber: `https://github.com/mockery/mockery.git`
- lisensi: `BSD-3-Clause`

### 3.5 `nunomaduro/collision`

- versi: `v8.9.3`
- jenis: development backend
- fungsi: menampilkan error CLI Laravel dengan format yang lebih jelas
- sumber: `https://github.com/nunomaduro/collision.git`
- lisensi: `MIT`

### 3.6 `phpunit/phpunit`

- versi: `12.5.20`
- jenis: development backend
- fungsi: framework testing utama
- penggunaan:
  - `tests/Feature`
  - `tests/Unit`
- sumber: `https://github.com/sebastianbergmann/phpunit.git`
- lisensi: `BSD-3-Clause`

## 4. Dependency Frontend

Dependency frontend pada project ini seluruhnya berada pada kelompok development dependency, karena dipakai untuk proses build, styling, dan formatting asset.

### 4.1 `vite`

- versi: `8.0.8`
- jenis: development frontend
- fungsi: bundler frontend
- penggunaan:
  - script `npm run dev`
  - script `npm run build`
- sumber: `https://github.com/vitejs/vite.git`
- lisensi: `MIT`

### 4.2 `laravel-vite-plugin`

- versi: `3.0.1`
- jenis: development frontend
- fungsi: integrasi Laravel dengan Vite
- sumber: `https://github.com/laravel/vite-plugin`
- lisensi: `MIT`

### 4.3 `tailwindcss`

- versi: `4.2.2`
- jenis: development frontend
- fungsi: utility-first CSS framework
- penggunaan:
  - styling public page
  - styling komponen UI
- sumber: `https://github.com/tailwindlabs/tailwindcss.git`
- lisensi: `MIT`

### 4.4 `@tailwindcss/vite`

- versi: `4.2.2`
- jenis: development frontend
- fungsi: plugin Tailwind untuk Vite
- sumber: `https://github.com/tailwindlabs/tailwindcss.git`
- lisensi: `MIT`

### 4.5 `prettier`

- versi: `3.8.3`
- jenis: development frontend
- fungsi: formatter file frontend
- sumber: `prettier/prettier`
- lisensi: `MIT`

### 4.6 `prettier-plugin-blade`

- versi: `3.1.4`
- jenis: development frontend
- fungsi: formatter untuk file Blade
- sumber: `https://github.com/fortephp/chisel.git`
- lisensi: `MIT`

### 4.7 `concurrently`

- versi: `9.2.1`
- jenis: development frontend/tooling
- fungsi: menjalankan beberapa proses sekaligus
- penggunaan:
  - dipakai pada script `composer run dev`
  - menyalakan server, queue listener, pail, dan Vite secara bersamaan
- sumber: `https://github.com/open-cli-tools/concurrently.git`
- lisensi: `MIT`

## 5. Layanan Eksternal dan Integrasi

Selain package lokal, project ini juga memiliki ketergantungan pada layanan eksternal melalui environment variable dan service internal.

### 5.1 DOKU Payment

- jenis: layanan eksternal
- fungsi: payment gateway untuk checkout pembayaran
- indikator penggunaan:
  - `app/Services/Payments/DokuCheckoutService.php`
  - `app/Http/Controllers/DokuPaymentController.php`
  - variabel `.env` `DOKU_*`
- status penggunaan: aktif

### 5.2 Groq API

- jenis: layanan eksternal
- fungsi: mendukung fitur chatbot / AI pada public website
- indikator penggunaan:
  - variabel `.env` `GROQ_API_KEY`
  - variabel `.env` `GROQ_MODEL`
  - komponen chatbot pada public UI
- status penggunaan: opsional, tergantung konfigurasi environment

## 6. Sumber Dependency

Sumber dependency pada project ini berasal dari:

- Packagist / Composer ecosystem untuk dependency PHP
- npm registry untuk dependency frontend
- repository GitHub resmi masing-masing package

File referensi yang menjadi sumber utama dokumen ini:

- `composer.json`
- `composer.lock`
- `package.json`
- `package-lock.json`

## 7. Status Kerentanan Dependency

Status kerentanan dependency sebaiknya dipantau secara berkala menggunakan:

- `composer audit --locked`
- `npm audit --package-lock-only --audit-level=high`

Berdasarkan audit lokal terakhir pada kondisi repo saat ini:

### 7.1 Composer Audit

- ditemukan advisory keamanan pada beberapa package backend dan transitive dependency
- package yang terdampak antara lain:
  - `filament/filament`
  - `filament/actions`
  - `filament/infolists`
  - `filament/tables`
  - `laravel/framework`
  - `guzzlehttp/guzzle`
  - `guzzlehttp/psr7`
  - beberapa package Symfony terkait
- tingkat severity yang muncul mencakup `medium` dan `high`

### 7.2 npm Audit

- ditemukan vulnerability pada dependency frontend/tooling
- package yang terdampak antara lain:
  - `vite`
  - `concurrently`
  - `postcss`
  - `shell-quote`
- tingkat severity yang muncul mencakup `moderate`, `high`, dan `critical`

### 7.3 Catatan Keamanan

- status audit di atas menggambarkan kondisi dependency saat dokumen ini diperbarui
- vulnerability dapat berubah setelah upgrade package
- update dependency perlu diuji kembali agar tidak merusak kompatibilitas fitur yang sudah berjalan

## 8. Kesimpulan

Dependency pada SaditaSystem saat ini terbagi jelas menjadi:

- dependency runtime backend untuk Laravel, Filament, dan fitur bisnis inti
- dependency development backend untuk testing, formatting, dan debugging
- dependency frontend untuk build asset, styling, dan formatting
- layanan eksternal untuk payment dan AI

Secara umum, struktur dependency project sudah cukup jelas dan modern, tetapi masih perlu perhatian rutin pada area keamanan dependency, terutama package Filament, Laravel ecosystem, dan tooling frontend yang terdeteksi oleh hasil audit.
