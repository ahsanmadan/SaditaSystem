# Daftar Use Case Diagram Berdasarkan Developer - Sadita System

Dokumen ini memuat pembagian Use Case Diagram yang disesuaikan dengan tanggung jawab masing-masing developer (**Bagas**, **Maulid**, **Asan**, dan **Jeli Mayora**) serta usulan fitur baru agar sistem berjalan selaras dengan database saat ini (bebas dari alur titip belanja agar tetap fokus pada E-Commerce dengan harga pasti/fixed).

---

## 1. Modul Bagas: Use Case Order (Customer Side)
Fokus pada alur interaksi pelanggan (guest) mulai dari memilih produk, melakukan checkout sesuai tipe layanan, melakukan pembayaran penuh via payment gateway, melacak pesanan, hingga mengunduh invoice.

### Penambahan Fitur Baru untuk Bagas:
- **Pemesanan Tipe Layanan Sewa & Jasa/Paket**: Memilih durasi sewa yang valid untuk produk rental atau paket box hantaran fixed.
- **Pembayaran Tagihan Tambahan via Gateway**: Melakukan pembayaran denda/biaya kerusakan sewa jika ada lewat Payment Gateway.

```mermaid
usecaseDiagram
    actor Pelanggan as "Pelanggan (Guest)"
    actor Gateway as "Payment Gateway"
    
    rect "Sadita System - Modul Order (Bagas)"
        usecase UC_B01 as "Memilih Produk (Sewa/Jasa/Paket)"
        usecase UC_B02 as "Mengisi Form & Submit Pesanan"
        usecase UC_B03 as "Melakukan Pembayaran (Utama / Tambahan)"
        usecase UC_B04 as "Mengunduh Invoice"
        usecase UC_B05 as "Melacak Status Pesanan (Kode Pesanan saja)"
        usecase UC_B06 as "Verifikasi Pesanan Selesai"
    end
    
    Pelanggan --> UC_B01
    Pelanggan --> UC_B02
    Pelanggan --> UC_B03
    Pelanggan --> UC_B04
    Pelanggan --> UC_B05
    Pelanggan --> UC_B06
    
    UC_B03 --> Gateway
```

---

## 2. Modul Maulid: Use Case Testimoni & Pengeluaran (Testimony & Expense Management)
Fokus pada testimoni/ulasan produk oleh customer serta penambahan modul operasional keuangan untuk mencatat pengeluaran harian per pesanan (agar porsi kerja lebih seimbang dan lengkap).

### Penambahan Fitur Baru untuk Maulid:
- **Pencatatan Pengeluaran Operasional Pesanan**: Admin dapat menginput pengeluaran (misal: beli busa dekorasi, beli bunga segar) untuk memantau profitabilitas bersih pesanan.

```mermaid
usecaseDiagram
    actor Pelanggan as "Pelanggan (Guest)"
    actor Admin as "Admin/Owner"
    
    rect "Sadita System - Modul Testimoni & Pengeluaran (Maulid)"
        usecase UC_M01 as "Memasukkan Testimoni/Ulasan"
        usecase UC_M02 as "Melihat Testimoni"
        usecase UC_M03 as "Melihat Detail Testimoni"
        usecase UC_M04 as "Melihat Testimoni Berdasarkan Filter"
        usecase UC_M05 as "Admin Mengubah Status Testimoni (0/1)"
        usecase UC_M06 as "Admin Menghapus Testimoni"
        usecase UC_M07 as "Admin Mencatat Pengeluaran Pesanan"
        usecase UC_M08 as "Admin Melihat Rekapitulasi Pengeluaran"
    end
    
    Pelanggan --> UC_M01
    Pelanggan --> UC_M02
    Pelanggan --> UC_M03
    Pelanggan --> UC_M04
    
    Admin --> UC_M05
    Admin --> UC_M06
    Admin --> UC_M07
    Admin --> UC_M08
```

---

## 3. Modul Asan: Use Case Order Management (Admin Operations & Gateway Integration)
Fokus pada panel admin untuk operasional pengelolaan pesanan, pembuatan tagihan tambahan (denda/kerusakan), pemantauan dashboard analitik, pembuatan laporan bulanan, serta sinkronisasi pembayaran otomatis.

### Penambahan Fitur Baru untuk Asan:
- **Dashboard & Laporan Bulanan (Analytics)**: Halaman dashboard ringkasan performa bisnis dan cetak laporan pendapatan periodik (PDF/Excel).
- **Pembuatan Link Tagihan Tambahan**: Membuat invoice denda keterlambatan/kerusakan otomatis untuk dikirim ke customer.
- **Integrasi Callback Payment Gateway**: Sistem menangani notifikasi pembayaran otomatis dari gateway dan mengubah status menjadi "Lunas".

```mermaid
usecaseDiagram
    actor Admin as "Admin/Owner"
    actor Gateway as "Payment Gateway"
    
    rect "Sadita System - Modul Order Management (Asan)"
        usecase UC_A01 as "Login Admin"
        usecase UC_A02 as "Melihat Daftar Pesanan"
        usecase UC_A03 as "Melihat Detail Pesanan"
        usecase UC_A04 as "Mencari Pesanan (Kode/Nama Customer)"
        usecase UC_A05 as "Mengubah Status Pesanan (Pipeline)"
        usecase UC_A06 as "Menghapus Pesanan"
        usecase UC_A07 as "Melihat Dashboard Ringkasan"
        usecase UC_A08 as "Menghasilkan Laporan Bulanan"
        usecase UC_A09 as "Membuat Tagihan Tambahan & Link Bayar"
        usecase UC_A10 as "Sinkronisasi Callback Payment Gateway"
    end
    
    Admin --> UC_A01
    Admin --> UC_A02
    Admin --> UC_A03
    Admin --> UC_A04
    Admin --> UC_A05
    Admin --> UC_A06
    Admin --> UC_A07
    Admin --> UC_A08
    Admin --> UC_A09
    
    Gateway --> UC_A10
```

---

## 4. Modul Jeli Mayora: Use Case Product & Return (Catalog & Rental Return Management)
Fokus pada manajemen produk & kategori di katalog serta penambahan modul return untuk mengelola barang-barang sewa (seperti papan bunga, box hantaran) yang dikembalikan oleh pelanggan.

### Penambahan Fitur Baru untuk Jeli:
- **Mengelola Kategori Produk (CRUD Kategori)**: Pengaturan kategori (seperti Papan Bunga, Hantaran, Dekorasi).
- **Manajemen Return Sewa**: Memantau deadline pengembalian sewa, mencatat kondisi barang (Baik/Rusak), menghitung denda keterlambatan, dan mengembalikan stok otomatis.
- **Availability Calendar (Ketersediaan Stok)**: Mengelola ketersediaan stok produk sewa per tanggal pemesanan untuk menghindari over-booking.

```mermaid
usecaseDiagram
    actor Admin as "Admin/Owner"
    
    rect "Sadita System - Modul Product & Return (Jeli)"
        usecase UC_J01 as "Login ke Sistem Admin"
        usecase UC_J02 as "Melihat Produk"
        usecase UC_J03 as "Mencari Produk"
        usecase UC_J04 as "Menambahkan Produk (CRUD)"
        usecase UC_J05 as "Mengubah Produk"
        usecase UC_J06 as "Mengubah Status Produk Tersedia/Tidak"
        usecase UC_J07 as "Menghapus Produk"
        usecase UC_J08 as "Mengelola Kategori Produk (CRUD)"
        usecase UC_J09 as "Mengatur Ketersediaan Stok per Tanggal"
        usecase UC_J10 as "Mengelola Return & Denda Barang Sewa"
    end
    
    Admin --> UC_J01
    Admin --> UC_J02
    Admin --> UC_J03
    Admin --> UC_J04
    Admin --> UC_J05
    Admin --> UC_J06
    Admin --> UC_J07
    Admin --> UC_J08
    Admin --> UC_J09
    Admin --> UC_J10
```
