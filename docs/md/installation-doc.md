# Installation Doc - SaditaSystem

Dokumen ini berisi langkah instalasi dan menjalankan SaditaSystem secara lokal.

## Kebutuhan Awal

- PHP 8.3
- Composer
- Node.js dan npm
- MySQL atau MariaDB
- Git

## 1. Clone Repository

```bash
git clone https://github.com/ahsanmadan/SaditaSystem.git
cd SaditaSystem
```

## 2. Install Dependency Backend dan Frontend

### Opsi cepat yang direkomendasikan

Repository ini sudah menyediakan script setup bawaan dari `composer.json`:

```bash
composer run setup
```

Script ini akan menjalankan:

- `composer install`
- membuat `.env` jika belum ada
- `php artisan key:generate`
- `php artisan migrate --force`
- `npm install --ignore-scripts`
- `npm run build`

### Opsi manual

```bash
composer install
npm install
```

## 3. Siapkan File Environment

```bash
copy .env.example .env
php artisan key:generate
```

Jika memakai shell Linux/macOS, gunakan:

```bash
cp .env.example .env
php artisan key:generate
```

## 4. Konfigurasi Database

Pastikan database MySQL sudah dibuat, misalnya:

- nama database: `sadita_system`
- host: `127.0.0.1`
- port: `3306`
- username: `root`
- password: kosong atau sesuai konfigurasi lokal

Contoh `.env`:

```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sadita_system
DB_USERNAME=root
DB_PASSWORD=
SESSION_DRIVER=database
CACHE_STORE=database
FILAMENT_SPA_MODE=true
```

## 5. Jalankan Migration

```bash
php artisan migrate
```

Jika ingin mengisi data awal:

```bash
php artisan db:seed
```

Pastikan juga tabel `sessions` dan `cache` sudah ikut termigrate karena keduanya dipakai sebagai mode default yang direkomendasikan untuk tim.

## 6. Jalankan Project

### Opsi manual

```bash
php artisan serve
npm run dev
```

### Opsi script composer

```bash
composer run dev
```

Jika admin panel terasa lambat setelah instalasi awal, jalankan juga:

```bash
composer run optimize-local
```

## 7. Akses Aplikasi

- web publik: `http://localhost:8000`
- admin panel: `http://localhost:8000/admin`

## 8. Akun Demo Admin

- username / email: `admin`
- password: `admin`

## Catatan Penting

- untuk local development tim, database utama project ini adalah MySQL / MariaDB
- mode yang direkomendasikan untuk session dan cache adalah `database`
- mode `file` hanya dipakai sementara jika tabel `sessions` atau `cache` belum siap
- jika memakai XAMPP, pastikan Apache dan MySQL berjalan
- jika ingin memakai integrasi pembayaran DOKU, isi variabel `DOKU_*` pada `.env`
- jika ingin memakai fitur chatbot / AI, isi variabel `GROQ_API_KEY` dan `GROQ_MODEL`
- jika ada masalah akses asset upload, jalankan:

```bash
php artisan storage:link
```

- jika config terasa tidak sinkron, bersihkan cache:

```bash
php artisan optimize:clear
```

## Optimasi XAMPP / PHP untuk Admin Panel

Kalau memakai XAMPP dan admin panel terasa berat, aktifkan OPcache di `php.ini`:

```ini
zend_extension=opcache
opcache.enable=1
opcache.enable_cli=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=16
opcache.max_accelerated_files=20000
opcache.revalidate_freq=2
```

Setelah edit `php.ini`, restart Apache.

Catatan tambahan:

- repo ini mendukung SPA mode Filament lewat `FILAMENT_SPA_MODE=true`
- setelah migration, cek bahwa tabel `sessions` dan `cache` memang ada
- jika ingin melihat panduan optimasi lebih lengkap, baca [performance doc](./performance-doc.md)
