# Dependency Laravel Proyek PBL SaditaSystem

Dokumen ini berisi identifikasi dependency/package Laravel yang digunakan atau kemungkinan akan digunakan pada proyek PBL SaditaSystem, dengan penjelasan berformat 5W+1H.

## 1. Laravel Framework

Nama package: `laravel/framework`

| 5W+1H | Penjelasan |
|---|---|
| What | Framework utama yang digunakan untuk membangun aplikasi SaditaSystem. |
| Why | Dibutuhkan karena menyediakan struktur MVC, routing, middleware, migration, validation, authentication, dan Eloquent ORM. |
| Who | Developer backend dan full-stack sebagai pengembang utama aplikasi. |
| When | Digunakan sejak awal pengembangan dan dipakai terus di seluruh proses pembuatan fitur. |
| Where | Digunakan di seluruh project, seperti `routes`, `app`, `database`, `resources/views`, dan `tests`. |
| How | Diinstal melalui Composer lalu dipakai untuk mengatur alur route, controller, model, view, dan database. |

Referensi:

- [Laravel Documentation](https://laravel.com/docs/13.x)

---

## 2. Filament

Nama package: `filament/filament`

| 5W+1H | Penjelasan |
|---|---|
| What | Package admin panel untuk Laravel. |
| Why | Dibutuhkan agar pembuatan dashboard admin, CRUD data, table, form, dan widget menjadi lebih cepat. |
| Who | Admin, owner, staff operasional, dan developer yang mengembangkan panel admin. |
| When | Digunakan saat mengelola data kategori, produk, pelanggan, pesanan, pembayaran, dan ulasan. |
| Where | Digunakan pada area `/admin`, terutama di `app/Filament/Resources`, `app/Filament/Widgets`, dan `app/Providers/Filament/AdminPanelProvider.php`. |
| How | Diinstal dengan Composer, lalu resource admin dibuat melalui struktur Filament agar otomatis memiliki halaman list, create, edit, dan view. |

Referensi:

- [Filament Documentation](https://filamentphp.com/docs)
- [Filament Panel Configuration](https://filamentphp.com/docs/5.x/panel-configuration)

---

## 3. Midtrans PHP

Nama package: `midtrans/midtrans-php`

| 5W+1H | Penjelasan |
|---|---|
| What | Library PHP resmi untuk integrasi payment gateway Midtrans. |
| Why | Dibutuhkan agar sistem bisa menangani pembayaran digital tanpa membuat mekanisme pembayaran online sendiri. |
| Who | Developer saat integrasi, admin saat verifikasi pembayaran, dan pelanggan saat melakukan pembayaran pesanan. |
| When | Digunakan ketika order sudah dibuat dan pelanggan masuk ke tahap pembayaran. |
| Where | Digunakan pada fitur invoice, token pembayaran, notifikasi transaksi, dan pembaruan status pembayaran. |
| How | Data transaksi dikirim dari Laravel ke Midtrans, lalu hasil transaksi atau notifikasi dari Midtrans digunakan untuk memperbarui status pembayaran di sistem. |

Referensi:

- [Midtrans Documentation](https://docs.midtrans.com/)
- [midtrans-php GitHub](https://github.com/Midtrans/midtrans-php)

---

## 4. Simple QrCode

Nama package: `simplesoftwareio/simple-qrcode`

| 5W+1H | Penjelasan |
|---|---|
| What | Package untuk membuat QR code di Laravel/PHP. |
| Why | Dibutuhkan untuk menghasilkan QR code dengan cepat tanpa membuat generator QR manual. |
| Who | Developer saat implementasi, serta admin dan pelanggan yang nantinya memakai hasil QR code tersebut. |
| When | Digunakan saat menampilkan invoice, tautan tracking, atau tautan pembayaran. |
| Where | Bisa digunakan di halaman invoice publik, tracking pesanan, atau tampilan cetak/admin. |
| How | Package dipanggil dari controller atau Blade view untuk menghasilkan QR code yang berisi link atau kode tertentu. |

Referensi:

- [Simple QrCode GitHub](https://github.com/SimpleSoftwareIO/simple-qrcode)

---

## 5. Laravel Pint

Nama package: `laravel/pint`

| 5W+1H | Penjelasan |
|---|---|
| What | Formatter kode PHP resmi dari Laravel. |
| Why | Dibutuhkan agar format kode tetap rapi dan konsisten saat dikerjakan oleh banyak anggota tim. |
| Who | Developer yang menulis dan merapikan kode PHP di proyek. |
| When | Digunakan setelah ada perubahan kode dan sebelum commit atau pull request. |
| Where | Digunakan pada file PHP di dalam project, misalnya `app`, `database`, `routes`, dan `tests`. |
| How | Dijalankan melalui `vendor/bin/pint` atau script `composer run format` untuk merapikan style code secara otomatis. |

Referensi:

- [Laravel Pint Documentation](https://laravel.com/docs/13.x/pint)

---

## 6. Laravel Pail

Nama package: `laravel/pail`

| 5W+1H | Penjelasan |
|---|---|
| What | Tool Laravel untuk membaca log aplikasi secara real-time. |
| Why | Dibutuhkan agar proses debugging lebih cepat saat terjadi error atau warning. |
| Who | Developer selama proses development dan pengujian lokal. |
| When | Digunakan saat server lokal berjalan dan fitur sedang diuji. |
| Where | Digunakan di environment development, terutama saat menjalankan workflow harian project. |
| How | Dijalankan dengan `php artisan pail`, lalu log aplikasi akan tampil langsung di terminal. |

Referensi:

- [Laravel Logging Documentation](https://laravel.com/docs/13.x/logging)

---

## 7. Spatie Laravel Permission

Nama package: `spatie/laravel-permission`

| 5W+1H | Penjelasan |
|---|---|
| What | Package Laravel untuk mengatur role dan permission user. |
| Why | Dibutuhkan jika hak akses owner, admin, dan staff perlu dibedakan lebih detail. |
| Who | Developer sebagai pengatur sistem akses, dan user admin sesuai role yang dimiliki. |
| When | Digunakan saat sistem membutuhkan pembagian hak akses yang lebih rinci. |
| Where | Bisa digunakan pada middleware, policy, role user, dan kontrol akses di panel admin. |
| How | Diinstal lewat Composer, lalu role dan permission dibuat serta dihubungkan ke user sesuai kebutuhan sistem. |

Referensi:

- [Spatie Laravel Permission Docs](https://spatie.be/docs/laravel-permission)
- [Spatie Laravel Permission GitHub](https://github.com/spatie/laravel-permission)

---

## 8. Laravel Excel

Nama package: `maatwebsite/excel`

| 5W+1H | Penjelasan |
|---|---|
| What | Package Laravel untuk export dan import file Excel/CSV. |
| Why | Dibutuhkan jika sistem perlu membuat laporan transaksi, data pelanggan, atau rekap pesanan dalam bentuk file Excel. |
| Who | Developer saat implementasi, serta admin atau owner saat memakai fitur laporan. |
| When | Digunakan ketika sistem sudah membutuhkan fitur laporan dan ekspor data. |
| Where | Bisa digunakan di modul laporan, pembayaran, pelanggan, atau pesanan pada panel admin. |
| How | Diinstal lewat Composer lalu digunakan melalui class export/import agar data dari database bisa diubah menjadi file Excel atau CSV. |

Referensi:

- [Laravel Excel Documentation](https://laravel-excel.com/)
- [Laravel Excel GitHub](https://github.com/SpartnerNL/Laravel-Excel)
