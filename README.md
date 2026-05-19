# SaditaSystem

Sistem Informasi Manajemen Bisnis Sadita untuk layanan sewa, jasa dekorasi, dan hantaran.

## Stack

- Backend: Laravel 13
- Admin panel: Filament 5
- PHP: 8.3 atau lebih baru
- Database lokal/produksi: MySQL atau MariaDB
- Frontend: Vite dan Tailwind CSS 4
- Package manager: Composer dan npm

## Catatan Penting Untuk XAMPP

Proyek ini membutuhkan **PHP 8.3+**. Banyak instalasi XAMPP lama masih memakai PHP 8.1 atau 8.2, sehingga `composer install` atau `php artisan` bisa gagal walaupun MySQL XAMPP-nya berjalan.

Cek versi PHP:

```bash
php -v
```

Jika hasilnya di bawah PHP 8.3, gunakan XAMPP yang sudah membawa PHP 8.3+, atau gunakan PHP 8.3 terpisah lalu pastikan folder PHP tersebut masuk ke `PATH`.

## Setup Lokal Dengan XAMPP

1. Clone repository.

```bash
git clone https://github.com/ahsanmadan/SaditaSystem.git
cd SaditaSystem
```

2. Pastikan berada di branch `develop`.

```bash
git checkout develop
git pull origin develop
```

3. Install dependency.

```bash
composer install
npm install
```

4. Buat file `.env`.

```bash
copy .env.example .env
php artisan key:generate
```

Untuk Git Bash atau terminal Linux/macOS:

```bash
cp .env.example .env
php artisan key:generate
```

5. Jalankan MySQL di XAMPP, lalu buat database kosong lewat phpMyAdmin.

Nama database yang disarankan:

```text
sadita_system
```

6. Pastikan konfigurasi database di `.env` sesuai XAMPP.

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sadita_system
DB_USERNAME=root
DB_PASSWORD=
DB_CHARSET=utf8mb4
DB_COLLATION=utf8mb4_unicode_ci
```

Jika MySQL XAMPP memakai port lain, misalnya `3307`, ubah `DB_PORT` sesuai port yang aktif.

7. Jalankan migration dan seeder.

```bash
php artisan migrate --seed
```

8. Jalankan server.

Terminal 1:

```bash
php artisan serve
```

Terminal 2:

```bash
npm run dev
```

Buka aplikasi di:

```text
http://localhost:8000
```

Admin panel:

```text
http://localhost:8000/admin
```

## Akun Demo

Jika `php artisan migrate --seed` berhasil, akun admin demo dibuat oleh seeder:

```text
email: admin
password: admin
```

## Error XAMPP Yang Sering Terjadi

### `php` tidak dikenali

Artinya folder PHP belum masuk `PATH`.

Solusi cepat:

```bash
C:\xampp\php\php.exe artisan serve
```

Atau tambahkan `C:\xampp\php` ke environment variable `PATH`.

### `SQLSTATE[HY000] [1049] Unknown database`

Database belum dibuat di phpMyAdmin.

Buat database:

```text
sadita_system
```

Lalu ulangi:

```bash
php artisan migrate --seed
```

### `SQLSTATE[HY000] [2002] Connection refused`

MySQL XAMPP belum menyala atau port salah.

Cek di XAMPP Control Panel, lalu sesuaikan `DB_PORT` di `.env`.

### Composer menolak karena versi PHP

Project membutuhkan PHP 8.3+.

Solusi:

- upgrade XAMPP ke versi dengan PHP 8.3+
- atau install PHP 8.3 terpisah dan arahkan Composer/terminal ke PHP tersebut

## Git Workflow Tim

Gunakan branch `develop` sebagai base kerja.

```bash
git checkout develop
git pull origin develop
git checkout -b feature/nama_fitur
```

Setelah fitur selesai:

```bash
git add .
git commit -m "feat: deskripsi fitur dalam bahasa indonesia"
git push origin feature/nama_fitur
```

Commit prefix yang dipakai:

- `feat`: fitur baru
- `fix`: perbaikan bug
- `chore`: konfigurasi, dependency, maintenance
- `docs`: dokumentasi
- `style`: formatting
- `test`: pengujian

Feature branch direview dan dimerge ke `develop` oleh Lead Programmer.
