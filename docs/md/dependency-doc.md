# Dependency Doc - SaditaSystem

Dokumen ini berisi dependency utama yang digunakan atau disiapkan dalam project SaditaSystem.

## Dependency Backend

### 1. laravel/framework

- fungsi: framework utama aplikasi
- alasan dipakai: menyediakan struktur MVC, routing, migration, validation, middleware, dan ORM
- lokasi penggunaan: seluruh project Laravel

### 2. filament/filament

- fungsi: membangun admin panel
- alasan dipakai: mempercepat pembuatan CRUD, table, form, dan dashboard admin
- lokasi penggunaan:
  - `app/Filament/Resources`
  - `app/Filament/Widgets`
  - `app/Providers/Filament/AdminPanelProvider.php`

### 3. midtrans/midtrans-php

- fungsi: integrasi payment gateway Midtrans
- alasan dipakai: menyiapkan flow pembayaran digital untuk pesanan
- lokasi penggunaan: flow invoice dan pembayaran

### 4. simplesoftwareio/simple-qrcode

- fungsi: generator QR code
- alasan dipakai: mendukung kebutuhan QR untuk invoice atau tracking jika dibutuhkan

### 5. laravel/tinker

- fungsi: shell interaktif Laravel
- alasan dipakai: membantu inspeksi data dan debugging

## Dependency Development

### 6. laravel/pint

- fungsi: formatter kode PHP
- alasan dipakai: menjaga style code tetap rapi dan konsisten

### 7. laravel/pail

- fungsi: membaca log Laravel secara realtime
- alasan dipakai: membantu debugging saat development

### 8. fakerphp/faker

- fungsi: generator data dummy
- alasan dipakai: membantu factory dan data testing

### 9. phpunit/phpunit

- fungsi: framework testing
- alasan dipakai: menjalankan unit test dan feature test

### 10. mockery/mockery

- fungsi: mock object untuk testing
- alasan dipakai: membantu pengujian dengan dependency yang dimock

### 11. nunomaduro/collision

- fungsi: menampilkan error CLI Laravel dengan output yang lebih jelas
- alasan dipakai: mempermudah debugging saat menjalankan command artisan

## Dependency Frontend

### 12. vite

- fungsi: bundler frontend
- alasan dipakai: build asset CSS dan JS secara cepat

### 13. laravel-vite-plugin

- fungsi: menghubungkan Laravel dengan Vite
- alasan dipakai: supaya asset frontend bisa dimuat dengan benar dari Laravel

### 14. tailwindcss

- fungsi: utility-first CSS framework
- alasan dipakai: mempercepat styling halaman publik dan komponen UI

### 15. @tailwindcss/vite

- fungsi: plugin Tailwind untuk integrasi Vite
- alasan dipakai: mengikuti pendekatan Tailwind CSS 4

### 16. prettier

- fungsi: formatter frontend
- alasan dipakai: merapikan file view dan asset frontend

### 17. prettier-plugin-blade

- fungsi: formatter khusus Blade
- alasan dipakai: menjaga file Blade tetap rapi dan konsisten

### 18. concurrently

- fungsi: menjalankan beberapa proses sekaligus
- alasan dipakai: dipakai pada script `composer run dev` untuk menyalakan server, queue, log, dan vite bersamaan

## Catatan

- detail versi dependency bisa dilihat langsung di `composer.json` dan `package.json`
- dependency doc ini dipisahkan dari README agar dokumentasi utama tetap ringkas
