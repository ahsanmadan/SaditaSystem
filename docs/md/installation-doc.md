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

```bash
composer install
npm install
```

## 3. Siapkan File Environment

```bash
copy .env.example .env
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
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sadita_system
DB_USERNAME=root
DB_PASSWORD=
```

## 5. Jalankan Migration

```bash
php artisan migrate
```

Jika ingin mengisi data awal:

```bash
php artisan db:seed
```

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

## 7. Akses Aplikasi

- web publik: `http://127.0.0.1:8000`
- admin panel: `http://127.0.0.1:8000/admin`

## 8. Akun Demo Admin

- username / email: `admin`
- password: `admin`

## Catatan Penting

- untuk local development tim, database utama project ini adalah MySQL / MariaDB
- jika memakai XAMPP, pastikan Apache dan MySQL berjalan
- jika ada masalah akses asset upload, jalankan:

```bash
php artisan storage:link
```

- jika config terasa tidak sinkron, bersihkan cache:

```bash
php artisan optimize:clear
```
