# performance doc

Dokumen ini merangkum langkah optimasi lokal yang paling relevan untuk SaditaSystem, khususnya saat admin panel Filament terasa lambat di Windows + XAMPP.

## fokus bottleneck yang sudah teridentifikasi

- waktu tunggu terbesar ada di sisi server sebelum halaman selesai dikirim
- admin panel terasa paling lambat saat:
  - submit login menuju `/admin`
  - pindah menu resource seperti users, produk, pesanan, pembayaran
- penyebab paling mungkin:
  - bootstrap Laravel + Filament berat di Windows
  - cache Laravel belum dipanaskan
  - OPcache PHP belum aktif
  - perpindahan menu admin masih full reload jika SPA mode belum aktif

## optimasi yang sudah diterapkan di repo

- SPA mode Filament diaktifkan lewat `FILAMENT_SPA_MODE=true`
- widget info bawaan Filament dihapus dari dashboard agar beban awal lebih ringan
- tabel resource utama memakai `deferLoading()`
- cache key dashboard dirapikan agar invalidasi konsisten
- tersedia script:

```bash
composer run optimize-local
```

Script ini menjalankan:

```bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## langkah yang wajib dilakukan di lokal

### 1. panaskan cache Laravel

```bash
composer run optimize-local
```

Jika sedang debugging perubahan route/config/view, bersihkan dulu:

```bash
php artisan optimize:clear
```

### 2. aktifkan OPcache di XAMPP / PHP

Cari file `php.ini`, lalu pastikan konfigurasi berikut aktif:

```ini
zend_extension=opcache
opcache.enable=1
opcache.enable_cli=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=16
opcache.max_accelerated_files=20000
opcache.revalidate_freq=2
```

Setelah itu restart Apache.

Catatan:
- kalau `zend_extension=opcache` sudah ada tapi dikomentari, cukup hapus `;`
- di beberapa instalasi XAMPP, OPcache sudah tersedia tapi belum aktif

### 3. cek mode SPA Filament

Secara default repo sekarang memakai:

```env
FILAMENT_SPA_MODE=true
```

Kalau ingin membandingkan sebelum dan sesudah, ubah sementara ke:

```env
FILAMENT_SPA_MODE=false
```

lalu jalankan:

```bash
php artisan optimize:clear
```

## resource dan widget yang paling patut diawasi

### resource yang relatif berat

- `PelanggansTable`
  - memakai `withCount`, `withMax`, dan filter berbasis relasi
- `PesanansTable`
  - memuat relasi `pelanggan` dan `pembayaranTerakhir`
- `PembayaransTable`
  - memuat relasi `pesanan.pelanggan` dan `verifikator`

### widget dashboard yang paling patut diawasi

- `StatsOverviewWidget`
  - agregasi KPI harian/bulanan
- `OmzetChartWidget`
  - agregasi 30 hari terakhir
- `StatusPesananChartWidget`
  - agregasi count per status
- `PesananTerbaruWidget`
  - daftar pesanan terbaru + relasi pelanggan

## urutan optimasi yang disarankan untuk tim

1. jalankan `composer run optimize-local`
2. aktifkan OPcache dan restart Apache
3. pastikan `FILAMENT_SPA_MODE=true`
4. baru ukur ulang login -> dashboard -> menu resource
5. jika masih lambat, profiling query SQL dan response time per resource
