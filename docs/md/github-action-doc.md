# GitHub Action Documentation - SaditaSystem

Project ini menggunakan GitHub Actions untuk menjalankan proses *Continuous Integration* (CI) setiap ada perubahan pada branch utama pengembangan. Workflow ini bertujuan untuk memastikan aplikasi dapat di-*build*, migrasi database dapat dijalankan, dan pengujian dasar berhasil dieksekusi.

## Lokasi File Workflow

Workflow GitHub Actions berada pada file berikut:

- `.github/workflows/laravel.yml`

## Nama Workflow

Nama workflow yang digunakan adalah:

- `Laravel CI`

## Trigger Workflow

Workflow akan berjalan secara otomatis ketika:

- terjadi `push` ke branch `main` atau `develop`
- terjadi `pull_request` ke branch `main` atau `develop`

## Job yang Dijalankan

Workflow ini memiliki satu job utama, yaitu:

- `tests`
- nama tampilan job: `Run Tests & Checks`

Job ini dijalankan pada environment:

- `ubuntu-latest`

## Langkah-Langkah Workflow

### 1. Checkout Repository

Workflow mengambil source code terbaru dari repository menggunakan `actions/checkout`.

### 2. Setup PHP

Workflow menyiapkan environment PHP menggunakan `shivammathur/setup-php` dengan konfigurasi:

- PHP `8.3`
- extension:
  - `mbstring`
  - `dom`
  - `fileinfo`
  - `sqlite3`

### 3. Cache Composer Dependencies

Workflow mengambil direktori cache Composer, lalu menyimpan dependency cache agar proses instalasi dependency lebih cepat pada eksekusi berikutnya.

### 4. Menyiapkan File Environment

Workflow menyalin file `.env.example` menjadi `.env` dengan perintah:

```bash
cp .env.example .env
```

### 5. Install Dependency Backend

Workflow menginstal dependency backend Laravel menggunakan Composer dengan perintah:

```bash
composer install --prefer-dist --no-progress --no-interaction
```

### 6. Generate Application Key

Workflow menghasilkan application key Laravel dengan perintah:

```bash
php artisan key:generate
```

### 7. Membuat Database SQLite untuk CI

Workflow membuat file database SQLite khusus untuk proses CI dengan langkah:

```bash
mkdir -p database
touch database/database.sqlite
```

### 8. Menjalankan Database Migration

Workflow menjalankan migration menggunakan database SQLite dengan konfigurasi environment:

- `DB_CONNECTION=sqlite`
- `DB_DATABASE=database/database.sqlite`

Perintah yang dijalankan:

```bash
php artisan migrate --force
```

### 9. Setup Node.js

Workflow menyiapkan Node.js menggunakan `actions/setup-node` dengan konfigurasi:

- Node.js `20`
- cache: `npm`

### 10. Install Dependency Frontend

Workflow menginstal dependency frontend menggunakan perintah:

```bash
npm ci
```

### 11. Build Frontend Assets

Workflow membangun asset frontend menggunakan perintah:

```bash
npm run build
```

### 12. Menjalankan Pengujian

Workflow menjalankan pengujian Laravel menggunakan SQLite yang sama dengan perintah:

```bash
php artisan test
```

dengan environment:

- `DB_CONNECTION=sqlite`
- `DB_DATABASE=database/database.sqlite`

## Fungsi Workflow

Workflow GitHub Actions ini digunakan untuk:

- memastikan source code dapat dijalankan di environment CI
- memastikan dependency backend dan frontend dapat diinstal dengan baik
- memastikan migration database dapat dijalankan tanpa error
- memastikan asset frontend berhasil dibangun
- memastikan pengujian dasar aplikasi dapat dijalankan

## Kesimpulan

Workflow `Laravel CI` pada project SaditaSystem sudah mencakup proses dasar CI yang penting, yaitu:

- setup environment backend
- setup environment frontend
- konfigurasi database SQLite untuk CI
- proses build
- pengujian otomatis

Workflow ini sudah cukup baik sebagai fondasi awal untuk menjaga kualitas project setiap ada perubahan pada branch `main` dan `develop`.
