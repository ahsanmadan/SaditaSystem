# Feature Doc - SaditaSystem

Dokumen ini merangkum fitur yang saat ini sudah ada di SaditaSystem berdasarkan struktur route, resource Filament, dan model yang tersedia.

## 1. Public Website

### 1.1 Landing Page

- halaman utama Sadita Decoration
- menampilkan branding dan katalog umum
- route: `GET /`

### 1.2 Form Pemesanan

- pelanggan dapat mengisi form pemesanan tanpa login
- input utama:
  - nama pengirim
  - nomor WhatsApp
  - nama penerima
  - alamat
  - tanggal pengiriman
  - waktu pengiriman
  - pesan/ucapan
  - instruksi khusus
- route:
  - `GET /order`
  - `POST /order`

### 1.3 Invoice Pesanan

- menampilkan invoice berdasarkan `kode_pesanan`
- route: `GET /invoice/{order_id}`

### 1.4 Tracking Pesanan

- API tracking pesanan berdasarkan kode pesanan
- route: `GET /api/track/{order_id}`

## 2. Admin Panel

Admin panel menggunakan Filament dan berada di:

- `GET /admin`

### 2.1 Resource Admin

Resource yang tersedia saat ini:

- Users
- Kategoris
- Produks
- Pelanggans
- Pesanans
- Pembayarans
- Ulasans

### 2.2 Dashboard Admin

Dashboard memakai widget Filament, termasuk:

- overview statistik
- chart omzet
- chart status pesanan
- daftar pesanan terbaru

## 3. Domain Bisnis yang Dikelola

Project diarahkan untuk 3 tipe layanan:

- `sewa`
- `jasa dekorasi`
- `hantaran`

## 4. Model yang Dipakai

- `User`
- `Kategori`
- `Produk`
- `GambarProduk`
- `Pelanggan`
- `Pesanan`
- `DetailPesanan`
- `Pembayaran`
- `Pengiriman`
- `PengeluaranPesanan`
- `PengembalianPesanan`
- `Ulasan`
- `EmailLog`
- `ActivityLog`

## 5. Alur Fitur Utama Saat Ini

1. pelanggan membuka halaman order
2. pelanggan mengisi form
3. sistem membuat data pelanggan, pesanan, detail pesanan, dan pengiriman
4. sistem mengarahkan user ke invoice pesanan
5. admin dapat mengelola data pesanan dari panel admin

## 6. Fitur yang Masih Bertahap

- tracking pesanan tanpa login masih akan terus disempurnakan
- flow pembayaran masih perlu dirapikan agar cocok dengan status invoice
- beberapa bagian public UI masih butuh penyelarasan copy dan pengalaman mobile
