# Refactoring Doc - SaditaSystem

Dokumen ini berisi area yang sudah atau sedang dirapikan, serta arah refactoring yang dianggap penting untuk project SaditaSystem.

## 1. Area yang Sudah Dirapikan

### 1.1 Optimasi Admin Panel

Beberapa query admin panel sebelumnya terasa berat, terutama pada dashboard dan tabel yang memuat banyak relasi. Perbaikannya difokuskan pada:

- mengurangi beban query statistik dashboard
- merapikan cache widget
- mengurangi eager loading yang tidak perlu

Branch terkait:

- `feature/optimasi-admin-panel`

### 1.2 Perbaikan Akses Admin Lokal

Masalah akses menu admin pada local/XAMPP sempat muncul karena kondisi environment dan route create yang belum lengkap. Area ini sudah pernah dirapikan pada branch:

- `feature/perbaikan-admin-xampp`

### 1.3 Perbaikan Flow Order

Flow order publik sebelumnya gagal ketika input waktu pengiriman dari UI langsung disimpan ke kolom database bertipe `time`. Refactor kecil dilakukan dengan:

- membuat value dropdown menjadi format waktu valid
- menambah normalizer waktu di controller agar request lama tetap aman

Branch terkait:

- `feature/perbaikan-flow-order`

## 2. Area yang Masih Perlu Refactoring

### 2.1 Invoice Status

Saat ini invoice masih memiliki indikasi status yang belum sepenuhnya dinamis. Area ini perlu dirapikan agar tampilan invoice konsisten dengan status pesanan dan pembayaran.

### 2.2 Seeder

Seeder masih perlu dirapikan karena:

- belum sepenuhnya idempotent
- ada potensi inkonsistensi antar file seeder

### 2.3 Copy dan Encoding Public UI

Beberapa tampilan public masih memiliki:

- copy lama yang belum sinkron dengan scope sekarang
- karakter mojibake / encoding rusak

### 2.4 Testing Flow

Feature test belum sepenuhnya stabil karena environment testing belum sinkron dengan kebutuhan migration tertentu.

## 3. Prinsip Refactoring yang Dipakai

- perubahan dibuat sekecil mungkin tapi tetap menyelesaikan masalah inti
- hindari refactor besar yang menyentuh area tidak relevan
- utamakan alur bisnis utama: create order, admin review, dan pembayaran
- verifikasi dilakukan lewat build, route check, atau browser test jika memungkinkan
