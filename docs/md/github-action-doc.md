# Github Action Doc - SaditaSystem

Project ini memiliki workflow GitHub Actions untuk menjalankan pengecekan dasar setiap ada push atau pull request ke branch utama pengembangan.

## File Workflow

Lokasi workflow:

- `.github/workflows/laravel.yml`

## Nama Workflow

- `Laravel CI`

## Trigger Workflow

Workflow berjalan ketika:

- ada `push` ke branch `main` atau `develop`
- ada `pull_request` ke branch `main` atau `develop`

## Langkah yang Dilakukan Workflow

### 1. Checkout Repository

GitHub Actions mengambil isi repository terbaru.

### 2. Setup PHP

Workflow menyiapkan:

- PHP 8.3
- extension `mbstring`, `dom`, `fileinfo`, `sqlite3`

### 3. Cache Composer

Cache composer dipakai agar install dependency lebih cepat pada run berikutnya.

### 4. Siapkan `.env`

Workflow menyalin `.env.example` menjadi `.env`.

### 5. Install Dependency Backend

Menjalankan:

```bash
composer install --prefer-dist --no-progress --no-interaction
```

### 6. Generate App Key

Menjalankan:

```bash
php artisan key:generate
```

### 7. Siapkan SQLite untuk CI

Workflow membuat file database SQLite khusus untuk proses CI.

### 8. Jalankan Migration

Menjalankan migration dengan environment SQLite.

### 9. Setup Node.js

Workflow menyiapkan Node.js versi 20 dan cache npm.

### 10. Install Dependency Frontend

Menjalankan:

```bash
npm ci
```

### 11. Build Frontend

Menjalankan:

```bash
npm run build
```

### 12. Run Tests

Menjalankan:

```bash
php artisan test
```

## Fungsi Workflow

GitHub Action ini dipakai untuk:

- memastikan dependency bisa di-install
- memastikan migration bisa jalan di CI
- memastikan asset frontend bisa dibuild
- memastikan test minimal dapat dijalankan

## Catatan

- workflow ini sudah cukup baik sebagai dasar CI
- area yang masih perlu perhatian adalah kestabilan beberapa feature test agar hasil CI makin konsisten
