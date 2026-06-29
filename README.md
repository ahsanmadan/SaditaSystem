# SaditaSystem

SaditaSystem adalah sistem informasi manajemen bisnis untuk **Sadita Decoration** yang difokuskan pada layanan **papan ucapan**, **hantaran**, dan **dekorasi**. Project ini dibangun untuk mendukung alur bisnis:

`request order -> review admin -> finalisasi harga -> pembayaran -> proses -> selesai`

Project ini menggunakan Laravel untuk backend, Filament untuk admin panel, serta Vite + Tailwind CSS untuk frontend publik.

## Tim Pengembang

- `Bagatio Putra Joandri` - `2411081005` - Project Manager & AI Specialist
- `Ahsan Ramadan` - `2411081002` - Lead Programmer
- `Jeli Mayora` - `2411081012` - System Analyst
- `Aprilla Maulida` - `211083002` - Quality Assurance

## Ruang Lingkup Project

- public website untuk landing page dan form pemesanan tanpa login
- admin panel untuk operasional internal
- pengelolaan kategori, produk, pelanggan, pesanan, dan pembayaran
- alur pembayaran yang saat ini terintegrasi dengan DOKU
- tracking pesanan yang dikembangkan bertahap

Catatan penting:

- project ini **tidak** ditujukan untuk florist / buket bunga
- pelanggan **tidak diwajibkan login**
- database utama local development dan production adalah **MySQL / MariaDB**

## Stack Utama

- PHP `^8.3`
- Laravel `^13.0`
- Filament `^5.6`
- MySQL / MariaDB
- Vite `^8.0.0`
- Tailwind CSS `^4.0.0`

## Fitur Inti

### Public Website

- landing page Sadita Decoration
- katalog layanan berdasarkan kategori
- form pemesanan tanpa login
- halaman invoice pesanan
- pelacakan pesanan berbasis kode order

### Admin Panel

- login admin
- dashboard operasional
- kelola kategori
- kelola produk
- kelola pelanggan
- kelola pesanan
- kelola pembayaran
- kelola ulasan

## Struktur Folder Singkat

```text
SaditaSystem/
|-- app/
|   |-- Filament/
|   |-- Http/Controllers/
|   |-- Models/
|   |-- Observers/
|   `-- Services/
|-- database/
|   |-- migrations/
|   `-- seeders/
|-- docs/
|   |-- md/
|   `-- pic/
|-- public/
|-- resources/
|-- routes/
|-- tests/
|-- .github/workflows/
|-- composer.json
|-- package.json
`-- nixpacks.toml
```

## Cara Menjalankan Singkat

### Opsi cepat

```bash
composer run setup
composer run dev
```

### Opsi manual

```bash
composer install
npm install
copy .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
npm run dev
```

Untuk panduan instalasi yang lebih lengkap, lihat:

- [installation doc](./docs/md/installation-doc.md)

## Akun Demo Admin

Seeder default membuat akun admin demo:

- username / email: `admin`
- password: `admin`

## Dokumentasi Pendukung

- [installation doc](./docs/md/installation-doc.md)
- [feature doc](./docs/md/feature-doc.md)
- [changelog](./docs/md/changelog.md)
- [dependency doc](./docs/md/dependency-doc.md)
- [refactoring doc](./docs/md/refactoring-doc.md)
- [github action doc](./docs/md/github-action-doc.md)
- [performance doc](./docs/md/performance-doc.md)

## Tampilan Awal Web

![homepage public](./docs/pic/homepage-public.png)

## Deployment

Deploy production saat ini diarahkan ke Railway dengan konfigurasi utama di:

- `nixpacks.toml`

## Lisensi

Project ini menggunakan lisensi `MIT`.
